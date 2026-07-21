use std::collections::HashMap;
use std::sync::{Arc, Mutex};

use tauri::menu::{MenuBuilder, MenuItemBuilder};
use tauri::tray::{MouseButton, TrayIconBuilder, TrayIconEvent};
use tauri::{Emitter, Manager};
#[cfg(not(target_os = "linux"))]
use tauri::webview::WebviewBuilder;
#[cfg(not(target_os = "linux"))]
use tauri::WebviewUrl;
use tauri_plugin_sql::{Migration, MigrationKind};
use tokio::sync::oneshot;

#[cfg(target_os = "linux")]
use gtk::prelude::*;
#[cfg(target_os = "linux")]
use std::cell::RefCell;
#[cfg(target_os = "linux")]
use wry::WebViewBuilderExtUnix;

#[cfg(target_os = "linux")]
thread_local! {
    static LINUX_WEBVIEWS: RefCell<HashMap<String, wry::WebView>> = RefCell::new(HashMap::new());
    static GTK_FIXED: RefCell<Option<gtk::Fixed>> = const { RefCell::new(None) };
    static WEBVIEW_URLS: RefCell<HashMap<String, String>> = RefCell::new(HashMap::new());
}

struct WebviewState {
    tabs: HashMap<String, String>,
}

type EvalRegistry = Arc<Mutex<HashMap<String, oneshot::Sender<String>>>>;

#[derive(serde::Serialize)]
struct OAuthCallbackResult {
    code: String,
    state: String,
    port: u16,
}

struct OAuthCallbackState {
    cancel_tx: Option<oneshot::Sender<()>>,
}

struct CdpPortState {
    port: Option<u16>,
}

#[derive(serde::Deserialize)]
#[allow(dead_code)]
struct CdpTarget {
    id: String,
    #[serde(rename = "type")]
    target_type: String,
    title: String,
    url: String,
    #[serde(rename = "webSocketDebuggerUrl")]
    ws_debugger_url: Option<String>,
}

#[cfg(not(target_os = "linux"))]
#[tauri::command]
async fn create_embedded_browser(
    app: tauri::AppHandle,
    tab_id: String,
    url: String,
) -> Result<(), String> {
    let parsed: url::Url = url.parse().map_err(|e| format!("Invalid URL: {}", e))?;

    let app_clone = app.clone();
    tauri::async_runtime::spawn(async move {
        let label = format!("embedded-browser-{}", tab_id);
        let emit_nav = app_clone.clone();
        let emit_load = app_clone.clone();
        let emit_newwin = app_clone.clone();
        let tab_id_nav = tab_id.clone();
        let tab_id_load = tab_id.clone();
        let tab_id_newwin = tab_id.clone();

        let builder = WebviewBuilder::new(label.clone(), WebviewUrl::External(parsed))
            .on_navigation(move |url| {
                let url_str = url.to_string();
                let tid = tab_id_nav.clone();
                let _ = emit_nav.emit(
                    "browser-url-changed",
                    serde_json::json!({"tabId": tid, "url": url_str}),
                );
                true
            })
            .on_page_load(move |wv, _payload| {
                if let Ok(url) = wv.url() {
                    let tid = tab_id_load.clone();
                    let _ = emit_load.emit(
                        "browser-page-loaded",
                        serde_json::json!({"tabId": tid, "url": url.to_string()}),
                    );
                }
            })
            .on_new_window(move |url, _features| {
                let h = emit_newwin.clone();
                let u = url.to_string();
                let tid = tab_id_newwin.clone();
                tauri::async_runtime::spawn(async move {
                    let label = {
                        let state = h.state::<Mutex<WebviewState>>();
                        let guard = state.lock().unwrap();
                        guard.tabs.get(&tid).cloned()
                    };
                    if let Some(label) = label {
                        if let Some(wv) = h.get_webview(&label) {
                            if let Ok(parsed) = u.parse::<url::Url>() {
                                let _ = wv.navigate(parsed);
                            }
                        }
                    }
                });
                tauri::webview::NewWindowResponse::Deny
            });

        if let Some(window) = app_clone.get_window("main") {
            match window.add_child(
                builder,
                tauri::LogicalPosition::new(0.0, 60.0),
                tauri::LogicalSize::new(800.0, 600.0),
            ) {
                Ok(_) => {
                    {
                        let state = app_clone.state::<Mutex<WebviewState>>();
                        let mut guard = state.lock().unwrap();
                        guard.tabs.insert(tab_id.clone(), label.clone());
                    }
                    let _ = app_clone.emit("browser-status", "embedded-open");
                }
                Err(e) => {
                    eprintln!("Failed to create embedded browser: {}", e);
                    let _ = app_clone.emit("browser-status", "error");
                }
            }
        } else {
            eprintln!("Main window not found for embedded browser");
            let _ = app_clone.emit("browser-status", "error");
        }
    });

    Ok(())
}

#[cfg(target_os = "linux")]
#[tauri::command]
async fn create_embedded_browser(
    app: tauri::AppHandle,
    tab_id: String,
    url: String,
) -> Result<(), String> {
    let url_str = url.clone();
    let app_clone = app.clone();
    let tab_id_clone = tab_id.clone();
    let (tx, rx) = oneshot::channel::<Result<(), String>>();

    app.run_on_main_thread(move || {
        let result = (|| -> Result<(), String> {
            let label = format!("embedded-browser-{}", tab_id_clone);

            let has_fixed = GTK_FIXED.with(|f| f.borrow().is_some());
            if !has_fixed {
                let _ = app_clone.emit("browser-status", "error");
                return Err("GtkFixed not initialized — setup() may not have run".to_string());
            }

            let app_nav = app_clone.clone();
            let app_load = app_clone.clone();
            let app_newwin = app_clone.clone();
            let label_nav = label.clone();
            let label_load = label.clone();
            let label_newwin = label.clone();
            let tab_id_nav = tab_id.clone();
            let tab_id_load = tab_id.clone();

            let builder = wry::WebViewBuilder::new()
                .with_url(&url_str)
                .with_bounds(wry::Rect {
                    position: wry::dpi::LogicalPosition::new(0.0_f64, 60.0_f64).into(),
                    size: wry::dpi::LogicalSize::new(800.0_f64, 600.0_f64).into(),
                })
                .with_navigation_handler(move |url| {
                    let url_s = url.to_string();
                    WEBVIEW_URLS.with(|urls| {
                        urls.borrow_mut().insert(label_nav.clone(), url_s.clone());
                    });
                    let _ = app_nav.emit(
                        "browser-url-changed",
                        serde_json::json!({
                            "tabId": tab_id_nav,
                            "url": url_s
                        }),
                    );
                    true
                })
                .with_on_page_load_handler(move |event, url| {
                    use wry::PageLoadEvent;
                    if matches!(event, PageLoadEvent::Finished) {
                        let url_s = url.to_string();
                        WEBVIEW_URLS.with(|urls| {
                            urls.borrow_mut().insert(label_load.clone(), url_s.clone());
                        });
                        let _ = app_load.emit(
                            "browser-page-loaded",
                            serde_json::json!({
                                "tabId": tab_id_load,
                                "url": url_s
                            }),
                        );
                    }
                })
                .with_new_window_req_handler(move |url, _features| {
                    let url_s = url.to_string();

                    LINUX_WEBVIEWS.with(|webviews| {
                        if let Some(wv) = webviews.borrow().get(&label_newwin) {
                            let _ = wv.load_url(&url_s);
                        }
                    });

                    WEBVIEW_URLS.with(|urls| {
                        urls.borrow_mut().insert(label_newwin.clone(), url_s.clone());
                    });

                    let _ = app_newwin.emit(
                        "browser-url-changed",
                        serde_json::json!({
                            "tabId": tab_id_clone,
                            "url": url_s
                        }),
                    );

                    wry::NewWindowResponse::Deny
                });

            let webview_result = GTK_FIXED.with(|f| {
                let fixed_ref = f.borrow();
                let fixed = fixed_ref.as_ref().expect("GTK_FIXED checked above");
                builder.build_gtk(fixed)
            });

            match webview_result {
                Ok(wv) => {
                    LINUX_WEBVIEWS.with(|webviews| {
                        webviews.borrow_mut().insert(label.clone(), wv);
                    });
                    WEBVIEW_URLS.with(|urls| {
                        urls.borrow_mut().insert(label.clone(), url_str.clone());
                    });
                    {
                        let state = app_clone.state::<Mutex<WebviewState>>();
                        let mut guard = state.lock().map_err(|e| format!("Lock poisoned: {}", e))?;
                        guard.tabs.insert(tab_id.clone(), label.clone());
                    }
                    let _ = app_clone.emit("browser-status", "embedded-open");
                    Ok(())
                }
                Err(e) => {
                    let _ = app_clone.emit("browser-status", "error");
                    Err(format!("Failed to create Linux embedded browser: {}", e))
                }
            }
        })();

        let _ = tx.send(result);
    })
    .map_err(|_| "Failed to run on main thread".to_string())?;

    rx.await
        .map_err(|_| "Main thread response channel closed".to_string())??;

    Ok(())
}

#[cfg(not(target_os = "linux"))]
#[tauri::command]
async fn navigate_embedded(app: tauri::AppHandle, tab_id: String, url: String) -> Result<(), String> {
    let label = {
        let state = app.state::<Mutex<WebviewState>>();
        let guard = state.lock().unwrap();
        guard
            .tabs
            .get(&tab_id)
            .cloned()
            .ok_or_else(|| format!("Tab {} not found", tab_id))?
    };
    let wv = app
        .get_webview(&label)
        .ok_or_else(|| "Embedded browser not open".to_string())?;
    let parsed: url::Url = url.parse().map_err(|e| format!("Invalid URL: {}", e))?;
    wv.navigate(parsed)
        .map_err(|e| format!("Navigation failed: {}", e))?;
    Ok(())
}

#[cfg(target_os = "linux")]
#[tauri::command]
async fn navigate_embedded(app: tauri::AppHandle, tab_id: String, url: String) -> Result<(), String> {
    let label = {
        let state = app.state::<Mutex<WebviewState>>();
        let guard = state.lock().map_err(|e| format!("Lock poisoned: {}", e))?;
        guard
            .tabs
            .get(&tab_id)
            .cloned()
            .ok_or_else(|| format!("Tab {} not found", tab_id))?
    };
    let label_clone = label.clone();
    let url_str_clone = url.clone();
    app.run_on_main_thread(move || {
        LINUX_WEBVIEWS.with(|webviews| {
            if let Some(wv) = webviews.borrow().get(&label_clone) {
                if let Err(e) = wv.load_url(&url_str_clone) {
                    eprintln!("Navigate failed: {}", e);
                }
            }
        });
        WEBVIEW_URLS.with(|urls| {
            urls.borrow_mut()
                .insert(label_clone.clone(), url_str_clone.clone());
        });
    })
    .map_err(|_| "Failed to run on main thread".to_string())?;
    Ok(())
}

#[cfg(not(target_os = "linux"))]
#[tauri::command]
async fn go_back(app: tauri::AppHandle, tab_id: String) -> Result<(), String> {
    let label = {
        let state = app.state::<Mutex<WebviewState>>();
        let guard = state.lock().unwrap();
        guard
            .tabs
            .get(&tab_id)
            .cloned()
            .ok_or_else(|| format!("Tab {} not found", tab_id))?
    };
    let wv = app
        .get_webview(&label)
        .ok_or_else(|| "Embedded browser not open".to_string())?;
    wv.eval("history.back()")
        .map_err(|e| format!("Failed to go back: {}", e))?;
    Ok(())
}

#[cfg(target_os = "linux")]
#[tauri::command]
async fn go_back(app: tauri::AppHandle, tab_id: String) -> Result<(), String> {
    let label = {
        let state = app.state::<Mutex<WebviewState>>();
        let guard = state.lock().map_err(|e| format!("Lock poisoned: {}", e))?;
        guard
            .tabs
            .get(&tab_id)
            .cloned()
            .ok_or_else(|| format!("Tab {} not found", tab_id))?
    };
    app.run_on_main_thread(move || {
        LINUX_WEBVIEWS.with(|webviews| {
            if let Some(wv) = webviews.borrow().get(&label) {
                if let Err(e) = wv.evaluate_script("history.back()") {
                    eprintln!("go_back failed: {}", e);
                }
            }
        });
    })
    .map_err(|_| "Failed to run on main thread".to_string())?;
    Ok(())
}

#[cfg(not(target_os = "linux"))]
#[tauri::command]
async fn go_forward(app: tauri::AppHandle, tab_id: String) -> Result<(), String> {
    let label = {
        let state = app.state::<Mutex<WebviewState>>();
        let guard = state.lock().unwrap();
        guard
            .tabs
            .get(&tab_id)
            .cloned()
            .ok_or_else(|| format!("Tab {} not found", tab_id))?
    };
    let wv = app
        .get_webview(&label)
        .ok_or_else(|| "Embedded browser not open".to_string())?;
    wv.eval("history.forward()")
        .map_err(|e| format!("Failed to go forward: {}", e))?;
    Ok(())
}

#[cfg(target_os = "linux")]
#[tauri::command]
async fn go_forward(app: tauri::AppHandle, tab_id: String) -> Result<(), String> {
    let label = {
        let state = app.state::<Mutex<WebviewState>>();
        let guard = state.lock().map_err(|e| format!("Lock poisoned: {}", e))?;
        guard
            .tabs
            .get(&tab_id)
            .cloned()
            .ok_or_else(|| format!("Tab {} not found", tab_id))?
    };
    app.run_on_main_thread(move || {
        LINUX_WEBVIEWS.with(|webviews| {
            if let Some(wv) = webviews.borrow().get(&label) {
                if let Err(e) = wv.evaluate_script("history.forward()") {
                    eprintln!("go_forward failed: {}", e);
                }
            }
        });
    })
    .map_err(|_| "Failed to run on main thread".to_string())?;
    Ok(())
}

#[cfg(not(target_os = "linux"))]
#[tauri::command]
async fn reload_browser(app: tauri::AppHandle, tab_id: String) -> Result<(), String> {
    let label = {
        let state = app.state::<Mutex<WebviewState>>();
        let guard = state.lock().unwrap();
        guard
            .tabs
            .get(&tab_id)
            .cloned()
            .ok_or_else(|| format!("Tab {} not found", tab_id))?
    };
    let wv = app
        .get_webview(&label)
        .ok_or_else(|| "Embedded browser not open".to_string())?;
    wv.eval("location.reload()")
        .map_err(|e| format!("Failed to reload: {}", e))?;
    Ok(())
}

#[cfg(target_os = "linux")]
#[tauri::command]
async fn reload_browser(app: tauri::AppHandle, tab_id: String) -> Result<(), String> {
    let label = {
        let state = app.state::<Mutex<WebviewState>>();
        let guard = state.lock().map_err(|e| format!("Lock poisoned: {}", e))?;
        guard
            .tabs
            .get(&tab_id)
            .cloned()
            .ok_or_else(|| format!("Tab {} not found", tab_id))?
    };
    app.run_on_main_thread(move || {
        LINUX_WEBVIEWS.with(|webviews| {
            if let Some(wv) = webviews.borrow().get(&label) {
                if let Err(e) = wv.evaluate_script("location.reload()") {
                    eprintln!("reload_browser failed: {}", e);
                }
            }
        });
    })
    .map_err(|_| "Failed to run on main thread".to_string())?;
    Ok(())
}

#[cfg(not(target_os = "linux"))]
#[tauri::command]
async fn set_webview_bounds(
    app: tauri::AppHandle,
    tab_id: String,
    x: f64,
    y: f64,
    width: f64,
    height: f64,
) -> Result<(), String> {
    let label = {
        let state = app.state::<Mutex<WebviewState>>();
        let guard = state.lock().unwrap();
        guard
            .tabs
            .get(&tab_id)
            .cloned()
            .ok_or_else(|| format!("Tab {} not found", tab_id))?
    };
    let wv = app
        .get_webview(&label)
        .ok_or_else(|| "Embedded browser not open".to_string())?;
    wv.set_position(tauri::LogicalPosition::new(x, y))
        .map_err(|e| format!("Failed to set position: {}", e))?;
    wv.set_size(tauri::LogicalSize::new(width, height))
        .map_err(|e| format!("Failed to set size: {}", e))?;
    Ok(())
}

#[cfg(target_os = "linux")]
#[tauri::command]
async fn set_webview_bounds(
    app: tauri::AppHandle,
    tab_id: String,
    x: f64,
    y: f64,
    width: f64,
    height: f64,
) -> Result<(), String> {
    let label = {
        let state = app.state::<Mutex<WebviewState>>();
        let guard = state.lock().map_err(|e| format!("Lock poisoned: {}", e))?;
        guard
            .tabs
            .get(&tab_id)
            .cloned()
            .ok_or_else(|| format!("Tab {} not found", tab_id))?
    };

    app.run_on_main_thread(move || {
        LINUX_WEBVIEWS.with(|webviews| {
            if let Some(wv) = webviews.borrow().get(&label) {
                let rect = wry::Rect {
                    position: wry::dpi::LogicalPosition::new(x, y).into(),
                    size: wry::dpi::LogicalSize::new(width, height).into(),
                };
                if let Err(e) = wv.set_bounds(rect) {
                    eprintln!("Failed to set bounds: {}", e);
                }
            }
        });
    })
    .map_err(|_| "Failed to run on main thread".to_string())?;

    Ok(())
}

#[tauri::command]
async fn hide_webview(app: tauri::AppHandle, tab_id: String) -> Result<(), String> {
    let label = {
        let state = app.state::<Mutex<WebviewState>>();
        let guard = state.lock().unwrap();
        guard
            .tabs
            .get(&tab_id)
            .cloned()
            .ok_or_else(|| format!("Tab {} not found", tab_id))?
    };

    #[cfg(target_os = "linux")]
    {
        app.run_on_main_thread(move || {
            LINUX_WEBVIEWS.with(|webviews| {
                if let Some(wv) = webviews.borrow().get(&label) {
                    if let Err(e) = wv.set_visible(false) {
                        eprintln!("Failed to hide Linux webview: {}", e);
                    }
                }
            });
        })
        .map_err(|_| "Failed to run on main thread".to_string())?;

        Ok(())
    }

    #[cfg(not(target_os = "linux"))]
    {
        let wv = app
            .get_webview(&label)
            .ok_or_else(|| "Embedded browser not open".to_string())?;
        wv.hide().map_err(|e| format!("Failed to hide: {}", e))?;
        Ok(())
    }
}

#[tauri::command]
async fn show_webview(app: tauri::AppHandle, tab_id: String) -> Result<(), String> {
    let label = {
        let state = app.state::<Mutex<WebviewState>>();
        let guard = state.lock().unwrap();
        guard
            .tabs
            .get(&tab_id)
            .cloned()
            .ok_or_else(|| format!("Tab {} not found", tab_id))?
    };

    #[cfg(target_os = "linux")]
    {
        app.run_on_main_thread(move || {
            LINUX_WEBVIEWS.with(|webviews| {
                if let Some(wv) = webviews.borrow().get(&label) {
                    if let Err(e) = wv.set_visible(true) {
                        eprintln!("Failed to show Linux webview: {}", e);
                    }
                }
            });
        })
        .map_err(|_| "Failed to run on main thread".to_string())?;

        Ok(())
    }

    #[cfg(not(target_os = "linux"))]
    {
        let wv = app
            .get_webview(&label)
            .ok_or_else(|| "Embedded browser not open".to_string())?;
        wv.show().map_err(|e| format!("Failed to show: {}", e))?;
        Ok(())
    }
}

#[tauri::command]
async fn get_embedded_url(app: tauri::AppHandle, tab_id: String) -> Result<String, String> {
    let label = {
        let state = app.state::<Mutex<WebviewState>>();
        let guard = state.lock().map_err(|e| format!("Lock poisoned: {}", e))?;
        guard
            .tabs
            .get(&tab_id)
            .cloned()
            .ok_or_else(|| format!("Tab {} not found", tab_id))?
    };

    #[cfg(target_os = "linux")]
    {
        let (tx, rx) = oneshot::channel::<String>();
        app.run_on_main_thread(move || {
            let url = WEBVIEW_URLS.with(|urls| urls.borrow().get(&label).cloned().unwrap_or_default());
            let _ = tx.send(url);
        })
        .map_err(|_| "Failed to run on main thread".to_string())?;
        let url = rx.await.map_err(|_| "Channel closed".to_string())?;
        Ok(url)
    }

    #[cfg(not(target_os = "linux"))]
    {
        let wv = app
            .get_webview(&label)
            .ok_or_else(|| "Embedded browser not open".to_string())?;
        let url = wv.url().map_err(|e| format!("Failed to get URL: {}", e))?;
        Ok(url.to_string())
    }
}

#[tauri::command]
async fn destroy_webview(app: tauri::AppHandle, tab_id: String) -> Result<(), String> {
    let label = {
        let state = app.state::<Mutex<WebviewState>>();
        let guard = state.lock().unwrap();
        guard.tabs.get(&tab_id).cloned()
    };
    if let Some(label) = label.clone() {
        #[cfg(target_os = "linux")]
        {
            let label_clone = label.clone();
            app.run_on_main_thread(move || {
                LINUX_WEBVIEWS.with(|webviews| {
                    webviews.borrow_mut().remove(&label_clone);
                });
                WEBVIEW_URLS.with(|urls| {
                    urls.borrow_mut().remove(&label_clone);
                });
            })
            .map_err(|_| "Failed to run on main thread".to_string())?;
        }

        #[cfg(not(target_os = "linux"))]
        {
            if let Some(wv) = app.get_webview(&label) {
                wv.close().map_err(|e| format!("Failed to close: {}", e))?;
            }
        }

        let state = app.state::<Mutex<WebviewState>>();
        let mut guard = state.lock().unwrap();
        guard.tabs.remove(&tab_id);
    }
    Ok(())
}

#[tauri::command]
async fn inject_autofill(app: tauri::AppHandle, tab_id: String, script: String) -> Result<(), String> {
    let label = {
        let state = app.state::<Mutex<WebviewState>>();
        let guard = state.lock().map_err(|e| format!("Lock poisoned: {}", e))?;
        guard
            .tabs
            .get(&tab_id)
            .cloned()
            .ok_or_else(|| format!("Tab {} not found", tab_id))?
    };

    #[cfg(target_os = "linux")]
    {
        app.run_on_main_thread(move || {
            LINUX_WEBVIEWS.with(|webviews| {
                if let Some(wv) = webviews.borrow().get(&label) {
                    if let Err(e) = wv.evaluate_script(&script) {
                        eprintln!("inject_autofill failed: {}", e);
                    }
                }
            });
        })
        .map_err(|_| "Failed to run on main thread".to_string())?;
        Ok(())
    }

    #[cfg(not(target_os = "linux"))]
    {
        let wv = app
            .get_webview(&label)
            .ok_or_else(|| "Embedded browser not open".to_string())?;
        wv.eval(&script)
            .map_err(|e| format!("Failed to inject autofill script: {}", e))?;
        Ok(())
    }
}

#[cfg(not(target_os = "linux"))]
#[tauri::command]
async fn eval_webview_script(
    app: tauri::AppHandle,
    tab_id: String,
    script: String,
) -> Result<String, String> {
    let eval_id = uuid::Uuid::new_v4().to_string();
    let label = {
        let state = app.state::<Mutex<WebviewState>>();
        let guard = state.lock().unwrap();
        guard
            .tabs
            .get(&tab_id)
            .cloned()
            .ok_or_else(|| format!("Tab {} not found", tab_id))?
    };
    let wv = app
        .get_webview(&label)
        .ok_or_else(|| "Embedded browser not open".to_string())?;

    let (tx, rx) = oneshot::channel::<String>();

    let registry = app.state::<EvalRegistry>();
    {
        let mut guard = registry.lock().unwrap();
        guard.insert(eval_id.clone(), tx);
    }

    let escaped_script = script
        .replace('\\', "\\\\")
        .replace('`', "\\`")
        .replace("${", "\\${");
    let wrapper = format!(
        r#"
        (async () => {{
            try {{
                let __result = (0, eval)(`{}`);
                if (__result instanceof Promise) {{
                    __result = await __result;
                }}
                await window.__TAURI_INTERNALS__.invoke('_eval_callback', {{
                    id: '{}',
                    success: true,
                    result: JSON.stringify(__result !== undefined ? __result : null)
                }});
            }} catch (__error) {{
                await window.__TAURI_INTERNALS__.invoke('_eval_callback', {{
                    id: '{}',
                    success: false,
                    error: String(__error)
                }});
            }}
        }})();
    "#,
        escaped_script, eval_id, eval_id
    );

    wv.eval(&wrapper)
        .map_err(|e| format!("Failed to inject eval script: {}", e))?;

    match tokio::time::timeout(tokio::time::Duration::from_secs(120), rx).await {
        Ok(Ok(result)) => Ok(result),
        Ok(Err(_)) => {
            let mut guard = registry.lock().unwrap();
            guard.remove(&eval_id);
            Err("Callback channel closed unexpectedly".to_string())
        }
        Err(_) => {
            let mut guard = registry.lock().unwrap();
            guard.remove(&eval_id);
            Err("Timeout waiting for eval result (120s)".to_string())
        }
    }
}

#[cfg(target_os = "linux")]
#[tauri::command]
async fn eval_webview_script(
    app: tauri::AppHandle,
    tab_id: String,
    script: String,
) -> Result<String, String> {
    let label = {
        let state = app.state::<Mutex<WebviewState>>();
        let guard = state.lock().map_err(|e| format!("Lock poisoned: {}", e))?;
        guard
            .tabs
            .get(&tab_id)
            .cloned()
            .ok_or_else(|| format!("Tab {} not found", tab_id))?
    };
    app.run_on_main_thread(move || {
        LINUX_WEBVIEWS.with(|webviews| {
            if let Some(wv) = webviews.borrow().get(&label) {
                if let Err(e) = wv.evaluate_script(&script) {
                    eprintln!("eval_webview_script (fire-and-forget) failed: {}", e);
                }
            }
        });
    })
    .map_err(|_| "Failed to run on main thread".to_string())?;
    Ok("null".to_string())
}

#[tauri::command]
async fn inject_webview_script(
    app: tauri::AppHandle,
    tab_id: String,
    script: String,
) -> Result<(), String> {
    let label = {
        let state = app.state::<Mutex<WebviewState>>();
        let guard = state.lock().map_err(|e| format!("Lock poisoned: {}", e))?;
        guard
            .tabs
            .get(&tab_id)
            .cloned()
            .ok_or_else(|| format!("Tab {} not found", tab_id))?
    };

    #[cfg(target_os = "linux")]
    {
        app.run_on_main_thread(move || {
            LINUX_WEBVIEWS.with(|webviews| {
                if let Some(wv) = webviews.borrow().get(&label) {
                    if let Err(e) = wv.evaluate_script(&script) {
                        eprintln!("inject_webview_script failed: {}", e);
                    }
                }
            });
        })
        .map_err(|_| "Failed to run on main thread".to_string())?;
        Ok(())
    }

    #[cfg(not(target_os = "linux"))]
    {
        let wv = app
            .get_webview(&label)
            .ok_or_else(|| "Embedded browser not open".to_string())?;
        wv.eval(&script)
            .map_err(|e| format!("Failed to inject script: {}", e))
    }
}

#[derive(serde::Serialize)]
struct LocalSkillFile {
    folder: String,
    content: String,
}

#[tauri::command]
async fn scan_local_skills(app: tauri::AppHandle) -> Result<Vec<LocalSkillFile>, String> {
    let home = app
        .path()
        .home_dir()
        .map_err(|e| format!("Failed to get home dir: {}", e))?;
    let skills_dir = home.join(".config").join("opencode").join("skills");

    if !skills_dir.exists() {
        return Ok(vec![]);
    }

    let mut results = Vec::new();
    let entries = std::fs::read_dir(&skills_dir)
        .map_err(|e| format!("Failed to read skills dir: {}", e))?;

    for entry in entries {
        let entry = entry.map_err(|e| format!("Failed to read dir entry: {}", e))?;
        let path = entry.path();
        if !path.is_dir() {
            continue;
        }
        let Some(folder_name) = path.file_name().and_then(|n| n.to_str()) else {
            continue;
        };
        let folder = folder_name.to_string();
        let skill_file = path.join("SKILL.md");
        let content = if skill_file.exists() {
            std::fs::read_to_string(&skill_file)
                .map_err(|e| format!("Failed to read {}: {}", skill_file.display(), e))?
        } else {
            continue;
        };
        results.push(LocalSkillFile { folder, content });
    }
    Ok(results)
}

// ── Minimal project-scoped filesystem commands ─────────────────────────────
// site-profiles.ts persists SiteProfile JSON under `.agents/site-profiles/...`. These resolve
// such relative paths against the project root (first ancestor of CWD holding a `.git`), so
// they land next to the existing `.agents/` fixtures regardless of the dev CWD (src-tauri).
fn project_root() -> std::path::PathBuf {
    let cwd = std::env::current_dir().unwrap_or_else(|_| std::path::PathBuf::from("."));
    let mut dir: &std::path::Path = &cwd;
    loop {
        if dir.join(".git").exists() {
            return dir.to_path_buf();
        }
        match dir.parent() {
            Some(p) => dir = p,
            None => return cwd,
        }
    }
}

// Trust boundary: only relative, non-traversing paths — never absolute or `..`.
fn resolve_project_path(rel: &str) -> Result<std::path::PathBuf, String> {
    if std::path::Path::new(rel).is_absolute() || rel.split(['/', '\\']).any(|s| s == "..") {
        return Err(format!("invalid path: {}", rel));
    }
    Ok(project_root().join(rel))
}

#[tauri::command]
fn create_dir(path: String, recursive: bool) -> Result<(), String> {
    let target = resolve_project_path(&path)?;
    if recursive {
        std::fs::create_dir_all(&target).map_err(|e| e.to_string())
    } else {
        std::fs::create_dir(&target).map_err(|e| e.to_string())
    }
}

#[tauri::command]
fn write_file(path: String, contents: String) -> Result<(), String> {
    let target = resolve_project_path(&path)?;
    if let Some(parent) = target.parent() {
        std::fs::create_dir_all(parent).map_err(|e| e.to_string())?;
    }
    std::fs::write(&target, contents).map_err(|e| e.to_string())
}

#[tauri::command]
fn read_file(path: String) -> Result<String, String> {
    let target = resolve_project_path(&path)?;
    std::fs::read_to_string(&target).map_err(|e| format!("not found: {}", e))
}

#[tauri::command]
fn delete_file(path: String) -> Result<(), String> {
    let target = resolve_project_path(&path)?;
    std::fs::remove_file(&target).map_err(|e| e.to_string())
}

#[tauri::command]
fn list_files(path: String, recursive: bool) -> Result<Vec<String>, String> {
    fn walk(dir: &std::path::Path, root: &std::path::Path, recursive: bool, out: &mut Vec<String>) {
        let Ok(entries) = std::fs::read_dir(dir) else {
            return;
        };
        for entry in entries.flatten() {
            let p = entry.path();
            if p.is_dir() {
                if recursive {
                    walk(&p, root, recursive, out);
                }
            } else if let Ok(rel) = p.strip_prefix(root) {
                out.push(rel.to_string_lossy().replace('\\', "/"));
            }
        }
    }
    let base = resolve_project_path(&path)?;
    let mut out = Vec::new();
    if base.exists() {
        walk(&base, &project_root(), recursive, &mut out);
    }
    Ok(out)
}

// ── OS keychain (Windows Credential Manager / macOS Keychain) ──────────────
// Secrets (site-credential passwords) live here, encrypted at rest by the OS, instead of
// plaintext in steve.db. Keyed by an arbitrary string from the TS layer (e.g. "credential:7").
const KEYRING_SERVICE: &str = "steve-desktop";

#[tauri::command]
fn keyring_set(key: String, secret: String) -> Result<(), String> {
    keyring::Entry::new(KEYRING_SERVICE, &key)
        .and_then(|e| e.set_password(&secret))
        .map_err(|e| e.to_string())
}

#[tauri::command]
fn keyring_get(key: String) -> Result<Option<String>, String> {
    let entry = keyring::Entry::new(KEYRING_SERVICE, &key).map_err(|e| e.to_string())?;
    match entry.get_password() {
        Ok(p) => Ok(Some(p)),
        Err(keyring::Error::NoEntry) => Ok(None),
        Err(e) => Err(e.to_string()),
    }
}

#[tauri::command]
fn keyring_delete(key: String) -> Result<(), String> {
    let entry = keyring::Entry::new(KEYRING_SERVICE, &key).map_err(|e| e.to_string())?;
    match entry.delete_credential() {
        Ok(()) | Err(keyring::Error::NoEntry) => Ok(()),
        Err(e) => Err(e.to_string()),
    }
}

#[cfg(not(target_os = "linux"))]
#[tauri::command]
async fn _eval_callback(
    app: tauri::AppHandle,
    id: String,
    success: bool,
    result: Option<String>,
    error: Option<String>,
) -> Result<(), String> {
    let registry = app.state::<EvalRegistry>();
    let tx = {
        let mut guard = registry.lock().unwrap();
        guard.remove(&id)
    };

    if let Some(tx) = tx {
        let response = if success {
            result.unwrap_or_else(|| "null".to_string())
        } else {
            format!(
                r#"{{"__error": "{}"}}"#,
                error.unwrap_or_else(|| "Unknown error".to_string())
            )
        };
        let _ = tx.send(response);
    }

    Ok(())
}

async fn process_oauth_connection(
    stream: &mut tokio::net::TcpStream,
    actual_port: u16,
) -> Result<OAuthCallbackResult, String> {
    use tokio::io::{AsyncReadExt, AsyncWriteExt};

    let mut buf = vec![0u8; 4096];
    let mut total = 0usize;
    loop {
        let n = stream
            .read(&mut buf[total..])
            .await
            .map_err(|e| format!("Read failed: {}", e))?;
        if n == 0 {
            break;
        }
        total += n;
        if buf[..total].windows(4).any(|w| w == b"\r\n\r\n") {
            break;
        }
        if total >= buf.len() {
            break;
        }
    }

    let request = String::from_utf8_lossy(&buf[..total]).to_string();
    let first_line = request.lines().next().unwrap_or("");
    let path = first_line.split_whitespace().nth(1).unwrap_or("");
    let query_string = path.split('?').nth(1).unwrap_or("");

    let params: HashMap<String, String> = url::form_urlencoded::parse(query_string.as_bytes())
        .into_owned()
        .collect();

    if let Some(error) = params.get("error") {
        let desc = params
            .get("error_description")
            .cloned()
            .unwrap_or_default();
        let body = format!(
            "<html><body><h1>Authentication Failed</h1><p>{}: {}</p></body></html>",
            error, desc
        );
        let response = format!(
            "HTTP/1.1 400 Bad Request\r\nContent-Type: text/html\r\nContent-Length: {}\r\nConnection: close\r\n\r\n{}",
            body.len(),
            body
        );
        let _ = stream.write_all(response.as_bytes()).await;
        let _ = stream.shutdown().await;
        return Err(format!("OAuth error: {}: {}", error, desc));
    }

    let code = params
        .get("code")
        .ok_or("Missing 'code' parameter in OAuth callback")?
        .clone();
    let state_val = params
        .get("state")
        .ok_or("Missing 'state' parameter in OAuth callback")?
        .clone();

    let body =
        "<html><body><h1>Authentication successful!</h1><p>You can close this tab.</p></body></html>";
    let response = format!(
        "HTTP/1.1 200 OK\r\nContent-Type: text/html\r\nContent-Length: {}\r\nConnection: close\r\n\r\n{}",
        body.len(),
        body
    );
    let _ = stream.write_all(response.as_bytes()).await;
    let _ = stream.shutdown().await;

    Ok(OAuthCallbackResult {
        code,
        state: state_val,
        port: actual_port,
    })
}

#[tauri::command]
async fn start_oauth_callback_server(
    app: tauri::AppHandle,
    port: u16,
) -> Result<OAuthCallbackResult, String> {
    let listener = {
        let mut bound = None;
        for offset in 0u16..20 {
            let try_port = port.checked_add(offset).ok_or("Port overflow")?;
            match tokio::net::TcpListener::bind(format!("0.0.0.0:{}", try_port)).await {
                Ok(l) => {
                    bound = Some(l);
                    break;
                }
                Err(e) if e.kind() == std::io::ErrorKind::AddrInUse => continue,
                Err(e) if e.kind() == std::io::ErrorKind::PermissionDenied => continue,
                Err(e) => return Err(format!("Failed to bind port {}: {}", try_port, e)),
            }
        }
        bound.ok_or_else(|| format!("All ports {}-{} are in use or reserved", port, port + 19))?
    };

    let actual_port = listener
        .local_addr()
        .map_err(|e| format!("Failed to get local addr: {}", e))?
        .port();

    let (cancel_tx, cancel_rx) = oneshot::channel::<()>();
    {
        let state = app.state::<Mutex<OAuthCallbackState>>();
        let mut guard = state.lock().unwrap();
        guard.cancel_tx = Some(cancel_tx);
    }

    let result = tokio::select! {
        accept_result = listener.accept() => {
            match accept_result {
                Ok((mut stream, _)) => process_oauth_connection(&mut stream, actual_port).await,
                Err(e) => Err(format!("Accept failed: {}", e)),
            }
        }
        _ = cancel_rx => {
            Err("OAuth cancelled".to_string())
        }
        _ = tokio::time::sleep(std::time::Duration::from_secs(300)) => {
            Err("OAuth timeout (5 minutes)".to_string())
        }
    };

    {
        let state = app.state::<Mutex<OAuthCallbackState>>();
        let mut guard = state.lock().unwrap();
        guard.cancel_tx = None;
    }

    result
}

#[tauri::command]
async fn stop_oauth_callback_server(app: tauri::AppHandle) -> Result<(), String> {
    let state = app.state::<Mutex<OAuthCallbackState>>();
    let cancel_tx = {
        let mut guard = state.lock().unwrap();
        guard.cancel_tx.take()
    };

    if let Some(tx) = cancel_tx {
        let _ = tx.send(());
    }

    Ok(())
}

#[tauri::command]
fn get_cdp_port(state: tauri::State<CdpPortState>) -> Result<Option<u16>, String> {
    Ok(state.port)
}

#[tauri::command]
async fn discover_cdp_target(port: u16) -> Result<Option<String>, String> {
    let client = reqwest::Client::builder()
        .timeout(std::time::Duration::from_secs(3))
        .build()
        .map_err(|e| format!("Failed to create HTTP client: {}", e))?;

    let url = format!("http://127.0.0.1:{}/json", port);
    let targets: Vec<CdpTarget> = client
        .get(&url)
        .send()
        .await
        .map_err(|e| format!("Failed to fetch CDP targets: {}", e))?
        .json()
        .await
        .map_err(|e| format!("Failed to parse CDP targets: {}", e))?;

    let target = targets.iter().find(|t| {
        t.target_type == "page"
            && t.url != "about:blank"
            && !t.url.is_empty()
            && !t.url.starts_with("tauri://localhost")
            && !t.url.starts_with("https://tauri.localhost")
            && !t.url.starts_with("http://localhost:1420")
            && !t.url.starts_with("http://localhost:5173")
    });

    Ok(target.and_then(|t| t.ws_debugger_url.clone()))
}

/// Locate `binary` on PATH, honouring PATHEXT on Windows.
///
/// Needed because CreateProcess cannot run a .cmd/.bat shim directly, and an
/// npm-installed `claude` is exactly that. Returns the resolved full path.
fn resolve_on_path(binary: &str) -> Option<std::path::PathBuf> {
    let exts: Vec<String> = if cfg!(windows) {
        std::env::var("PATHEXT")
            .unwrap_or_else(|_| ".COM;.EXE;.BAT;.CMD".into())
            .split(';')
            .filter(|e| !e.is_empty())
            .map(|e| e.to_lowercase())
            .collect()
    } else {
        vec![String::new()]
    };

    let path = std::env::var_os("PATH")?;
    for dir in std::env::split_paths(&path) {
        let direct = dir.join(binary);
        if direct.is_file() {
            return Some(direct);
        }
        for ext in &exts {
            let candidate = dir.join(format!("{}{}", binary, ext));
            if candidate.is_file() {
                return Some(candidate);
            }
        }
    }
    None
}

/// Run a local coding-agent CLI headlessly and return its stdout.
///
/// The prompt goes in on stdin, never argv: a DOM snapshot runs to tens of KB and
/// Windows caps a command line at ~32K. It also keeps page text out of the process
/// table and away from any shell quoting.
///
/// Tools are disabled (`--allowed-tools ""`). The agent's whole job is to answer with
/// one JSON action, so it has no business touching the filesystem, and a page it just
/// scraped is untrusted input.
#[tauri::command]
async fn run_agent_cli(
    app: tauri::AppHandle,
    engine: String,
    prompt: String,
    session_id: String,
    resume: bool,
    model: Option<String>,
    system_prompt: Option<String>,
    bypass_permissions: bool,
    timeout_secs: Option<u64>,
    stream: Option<bool>,
) -> Result<String, String> {
    let streaming = stream == Some(true);
    let bin = resolve_on_path(&engine).ok_or_else(|| {
        format!(
            "{} not found on PATH. Install it, or pick a different engine in Settings.",
            engine
        )
    })?;

    let mut args: Vec<String> = Vec::new();
    match engine.as_str() {
        "claude" => {
            args.push("-p".into());
            // stream-json emits one NDJSON event per turn/tool-use so the UI can show live
            // progress; --verbose is required to pair with it under --print. The plain json
            // envelope (single blob at the end) is kept for the non-streaming single-action path.
            if streaming {
                args.extend(["--output-format".into(), "stream-json".into(), "--verbose".into()]);
            } else {
                args.extend(["--output-format".into(), "json".into()]);
            }
            // Default: NO tools — the CLI is a pure reasoning engine that emits one JSON
            // action, and the TS loop performs every browser action. `--disallowed-tools "*"`
            // is what actually sandboxes it: an empty `--allowed-tools ""` is a no-op (the
            // spawned CLI still inherits the user's global settings and can run Bash), verified
            // live. Bypass mode instead grants the CLI its own tools and auto-approves them, so
            // it can act autonomously on the machine.
            if bypass_permissions {
                args.push("--dangerously-skip-permissions".into());
            } else {
                args.extend(["--disallowed-tools".into(), "*".into()]);
            }
            args.push("--strict-mcp-config".into());
            if resume {
                args.extend(["--resume".into(), session_id.clone()]);
            } else {
                args.extend(["--session-id".into(), session_id.clone()]);
                if let Some(sp) = system_prompt.filter(|s| !s.trim().is_empty()) {
                    args.extend(["--system-prompt".into(), sp]);
                }
            }
            if let Some(m) = model.filter(|s| !s.trim().is_empty()) {
                args.extend(["--model".into(), m]);
            }
        }
        "opencode" => {
            // UNTESTED: opencode is not installed here, so this path has never run.
            args.push("run".into());
            if resume {
                args.push("--continue".into());
            }
            if let Some(m) = model.filter(|s| !s.trim().is_empty()) {
                args.extend(["-m".into(), m]);
            }
        }
        other => return Err(format!("Unknown agent engine: {}", other)),
    }

    // A .cmd/.bat shim has to go through cmd.exe; a real .exe must not.
    let is_shim = bin
        .extension()
        .and_then(|e| e.to_str())
        .map(|e| {
            let e = e.to_lowercase();
            e == "cmd" || e == "bat"
        })
        .unwrap_or(false);

    let mut command = if is_shim {
        let sysroot = std::env::var("SystemRoot").unwrap_or_else(|_| "C:\\Windows".into());
        let mut c = tokio::process::Command::new(format!("{}\\System32\\cmd.exe", sysroot));
        c.arg("/c").arg(&bin).args(&args);
        c
    } else {
        let mut c = tokio::process::Command::new(&bin);
        c.args(&args);
        c
    };

    // Neutral cwd: the CLI picks up CLAUDE.md/AGENTS.md from wherever it runs, and the
    // agent must not inherit this repo's instructions.
    command
        .current_dir(std::env::temp_dir())
        .stdin(std::process::Stdio::piped())
        .stdout(std::process::Stdio::piped())
        .stderr(std::process::Stdio::piped());

    #[cfg(windows)]
    {
        use std::os::windows::process::CommandExt;
        command.creation_flags(0x0800_0000); // CREATE_NO_WINDOW
    }

    let mut child = command
        .spawn()
        .map_err(|e| format!("Failed to spawn {}: {}", engine, e))?;

    {
        use tokio::io::AsyncWriteExt;
        let mut stdin = child
            .stdin
            .take()
            .ok_or_else(|| "Failed to open stdin".to_string())?;
        stdin
            .write_all(prompt.as_bytes())
            .await
            .map_err(|e| format!("Failed to write prompt: {}", e))?;
        stdin
            .shutdown()
            .await
            .map_err(|e| format!("Failed to close stdin: {}", e))?;
    }

    // Single-action turns keep the 180s default; an autonomous crawl passes its own budget.
    let secs = timeout_secs.unwrap_or(180);

    // Streaming path: read stdout line-by-line, emit each NDJSON event to the UI as it
    // arrives, and accumulate the whole thing for the return value. stderr is drained by a
    // detached task so a full stderr pipe can never deadlock the read.
    if streaming {
        use tokio::io::{AsyncBufReadExt, AsyncReadExt, BufReader};
        if let Some(mut se) = child.stderr.take() {
            tokio::spawn(async move {
                let mut sink = Vec::new();
                let _ = se.read_to_end(&mut sink).await;
            });
        }
        let stdout_pipe = child
            .stdout
            .take()
            .ok_or_else(|| "Failed to open stdout".to_string())?;
        let sid = session_id.clone();
        let read = async {
            let mut lines = BufReader::new(stdout_pipe).lines();
            let mut acc = String::new();
            while let Some(line) = lines.next_line().await.map_err(|e| e.to_string())? {
                let _ = app.emit(
                    "agent-cli-progress",
                    serde_json::json!({ "sessionId": sid, "line": line }),
                );
                acc.push_str(&line);
                acc.push('\n');
            }
            let status = child.wait().await.map_err(|e| e.to_string())?;
            Ok::<(String, std::process::ExitStatus), String>((acc, status))
        };
        let (stdout, status) = tokio::time::timeout(tokio::time::Duration::from_secs(secs), read)
            .await
            .map_err(|_| format!("{} timed out after {}s", engine, secs))?
            .map_err(|e| format!("Failed to read {} output: {}", engine, e))?;
        if !status.success() && stdout.trim().is_empty() {
            return Err(format!("{} exited with {} and no output", engine, status));
        }
        return Ok(stdout);
    }

    let out = tokio::time::timeout(
        tokio::time::Duration::from_secs(secs),
        child.wait_with_output(),
    )
    .await
    .map_err(|_| format!("{} timed out after {}s", engine, secs))?
    .map_err(|e| format!("Failed to read {} output: {}", engine, e))?;

    let stdout = String::from_utf8_lossy(&out.stdout).to_string();

    // A non-zero exit is not always fatal: claude reports a bad model or a hit limit by
    // exiting 1 while printing a usable {"is_error":true,...} envelope on stdout and
    // leaving stderr empty. Hand any stdout back so the caller can surface the real
    // message; only a genuinely silent failure becomes an error here.
    if !out.status.success() && stdout.trim().is_empty() {
        let stderr = String::from_utf8_lossy(&out.stderr);
        return Err(format!(
            "{} exited with {} and no output: {}",
            engine,
            out.status,
            stderr.trim().chars().take(400).collect::<String>()
        ));
    }

    Ok(stdout)
}

#[cfg_attr(mobile, tauri::mobile_entry_point)]
pub fn run() {
    let cdp_port: Option<u16> = {
        let mut found = None;
        for port in 9222u16..=9242 {
            match std::net::TcpListener::bind(format!("127.0.0.1:{}", port)) {
                Ok(_listener) => {
                    found = Some(port);
                    break;
                }
                Err(_) => continue,
            }
        }
        found
    };
    if let Some(port) = cdp_port {
        std::env::set_var(
            "WEBVIEW2_ADDITIONAL_BROWSER_ARGUMENTS",
            format!("--remote-debugging-port={} --remote-allow-origins=*", port),
        );
        eprintln!("[steve] CDP enabled on port {} (dynamic allocation)", port);
    } else {
        eprintln!("[steve] CDP unavailable: all ports 9222-9242 are in use");
    }

    let migrations = vec![
        Migration {
            version: 1,
            description: "create_provider_configs_and_enable_wal",
            sql: "PRAGMA journal_mode=WAL;
CREATE TABLE IF NOT EXISTS provider_configs (
    id TEXT PRIMARY KEY NOT NULL,
    api_url TEXT,
    api_key TEXT,
    model TEXT,
    is_active INTEGER NOT NULL DEFAULT 0,
    created_at TEXT NOT NULL DEFAULT (datetime('now')),
    updated_at TEXT NOT NULL DEFAULT (datetime('now'))
);",
            kind: MigrationKind::Up,
        },
        Migration {
            version: 2,
            description: "create_app_settings_with_defaults",
            sql: "CREATE TABLE IF NOT EXISTS app_settings (
    key TEXT PRIMARY KEY NOT NULL,
    value TEXT
);
INSERT OR IGNORE INTO app_settings (key, value) VALUES ('setup_complete', 'false');",
            kind: MigrationKind::Up,
        },
        Migration {
            version: 3,
            description: "create_oauth_tokens",
            sql: "CREATE TABLE IF NOT EXISTS oauth_tokens (
    provider TEXT PRIMARY KEY NOT NULL,
    access_token TEXT NOT NULL,
    refresh_token TEXT,
    token_type TEXT,
    expires_at INTEGER,
    created_at TEXT NOT NULL DEFAULT (datetime('now')),
    updated_at TEXT NOT NULL DEFAULT (datetime('now'))
);",
            kind: MigrationKind::Up,
        },
        Migration {
            version: 4,
            description: "create_skills",
            sql: "CREATE TABLE IF NOT EXISTS skills (
    id TEXT PRIMARY KEY NOT NULL,
    name TEXT NOT NULL,
    description TEXT,
    content TEXT NOT NULL,
    source TEXT NOT NULL DEFAULT 'local',
    is_active INTEGER DEFAULT 1,
    url_pattern TEXT,
    created_at TEXT DEFAULT (datetime('now'))
);",
            kind: MigrationKind::Up,
        },
        Migration {
            version: 5,
            description: "create_site_profiles",
            sql: "CREATE TABLE IF NOT EXISTS site_profiles (
    id TEXT PRIMARY KEY,
    domain TEXT NOT NULL,
    page_name TEXT NOT NULL,
    profile_json TEXT NOT NULL,
    created_at TEXT DEFAULT (datetime('now')),
    updated_at TEXT DEFAULT (datetime('now')),
    UNIQUE(domain, page_name)
);",
            kind: MigrationKind::Up,
        },
        Migration {
            version: 6,
            description: "create_site_credentials",
            sql: "CREATE TABLE IF NOT EXISTS site_credentials (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    site_name TEXT NOT NULL,
    url_pattern TEXT NOT NULL,
    username TEXT NOT NULL,
    password TEXT NOT NULL,
    notes TEXT,
    created_at TEXT DEFAULT (datetime('now')),
    updated_at TEXT DEFAULT (datetime('now'))
);",
            kind: MigrationKind::Up,
        },
        Migration {
            version: 7,
            description: "create_bookmarks",
            sql: "CREATE TABLE IF NOT EXISTS bookmarks (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    url TEXT NOT NULL UNIQUE,
    created_at TEXT DEFAULT (datetime('now'))
);",
            kind: MigrationKind::Up,
        },
    ];

    tauri::Builder::default()
        .invoke_handler(tauri::generate_handler![
            create_embedded_browser,
            navigate_embedded,
            go_back,
            go_forward,
            reload_browser,
            set_webview_bounds,
            hide_webview,
            show_webview,
            get_embedded_url,
            destroy_webview,
            inject_autofill,
            eval_webview_script,
            #[cfg(not(target_os = "linux"))]
            _eval_callback,
            inject_webview_script,
            scan_local_skills,
            start_oauth_callback_server,
            stop_oauth_callback_server,
            get_cdp_port,
            discover_cdp_target,
            create_dir,
            write_file,
            read_file,
            delete_file,
            list_files,
            keyring_set,
            keyring_get,
            keyring_delete,
            run_agent_cli,
        ])
        .plugin(tauri_plugin_shell::init())
        .plugin(tauri_plugin_http::init())
        .plugin(
            tauri_plugin_sql::Builder::default()
                .add_migrations("sqlite:steve.db", migrations)
                .build(),
        )
        .plugin(tauri_plugin_process::init())
        .manage(Mutex::new(WebviewState {
            tabs: HashMap::new(),
        }))
        .manage(EvalRegistry::new(Mutex::new(HashMap::new())))
        .manage(Mutex::new(OAuthCallbackState { cancel_tx: None }))
        .manage(CdpPortState { port: cdp_port })
        .setup(|app| {
            // Set window icon explicitly (required for Linux dev mode)
            if let Some(window) = app.get_webview_window("main") {
                if let Some(icon) = app.default_window_icon() {
                    let _ = window.set_icon(icon.clone());
                }
            }

            let tray_menu = MenuBuilder::new(app)
                .item(
                    &MenuItemBuilder::new("Open Dashboard")
                        .id("open-dashboard")
                        .build(app)?,
                )
                .separator()
                .item(&MenuItemBuilder::new("Settings").id("settings").build(app)?)
                .item(&MenuItemBuilder::new("View Logs").id("logs").build(app)?)
                .separator()
                .item(&MenuItemBuilder::new("Quit").id("quit").build(app)?)
                .build()?;

            let tray_icon = {
                let icon_bytes = include_bytes!("../icons/128x128.png");
                let img = image::load_from_memory(icon_bytes).expect("Failed to load tray icon");
                let rgba = img.to_rgba8();
                let (w, h) = rgba.dimensions();
                tauri::image::Image::new_owned(rgba.into_raw(), w, h)
            };

            let _tray = TrayIconBuilder::new()
                .menu(&tray_menu)
                .icon(tray_icon)
                .tooltip("S.T.E.V.E Desktop")
                .on_menu_event(move |app, event| match event.id().as_ref() {
                    "open-dashboard" => {
                        if let Some(window) = app.get_webview_window("main") {
                            let _ = window.unminimize();
                            let _ = window.show();
                            let _ = window.set_focus();
                        }
                    }
                    "settings" => {
                        if let Some(window) = app.get_webview_window("main") {
                            let _ = window.unminimize();
                            let _ = window.show();
                            let _ = window.set_focus();
                            let _ = window.emit("navigate-to-settings", ());
                        }
                    }
                    "logs" => {
                        if let Some(window) = app.get_webview_window("main") {
                            let _ = window.unminimize();
                            let _ = window.show();
                            let _ = window.set_focus();
                            let _ = window.emit("navigate-to-logs", ());
                        }
                    }
                    "quit" => {
                        app.exit(0);
                    }
                    _ => {}
                })
                .on_tray_icon_event(|tray, event| {
                    if let TrayIconEvent::Click {
                        button: MouseButton::Left,
                        ..
                    } = event
                    {
                        if let Some(window) = tray.app_handle().get_webview_window("main") {
                            let _ = window.unminimize();
                            let _ = window.show();
                            let _ = window.set_focus();
                        }
                    }
                })
                .build(app)?;

            #[cfg(target_os = "linux")]
            {
                let window = app
                    .get_webview_window("main")
                    .ok_or("Main window not found for GtkFixed setup")?;
                let vbox = window
                    .default_vbox()
                    .map_err(|e| format!("Failed to get default vbox: {}", e))?;

                let overlay = gtk::Overlay::new();

                let children: Vec<gtk::Widget> = vbox.children();
                for child in &children {
                    vbox.remove(child);
                    overlay.add(child);
                    break;
                }

                let fixed = gtk::Fixed::new();
                overlay.add_overlay(&fixed);
                overlay.set_overlay_pass_through(&fixed, true);

                vbox.pack_start(&overlay, true, true, 0);
                overlay.show_all();

                GTK_FIXED.with(|f| {
                    *f.borrow_mut() = Some(fixed);
                });
            }

            Ok(())
        })
        .build(tauri::generate_context!())
        .expect("error while building tauri application")
        .run(|_app_handle, _event| {});
}
