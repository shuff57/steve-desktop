mod page_mcp;

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

/// Live CDP-screencast recording. The capture runs in a background tokio task (connects to the
/// browser debug endpoint, screencasts the driven tab, pipes frames to ffmpeg); `stop` signals it
/// to finish and `handle` yields the finished mp4 path. One recording at a time.
struct RecordingState {
    stop: Option<std::sync::Arc<std::sync::atomic::AtomicBool>>,
    handle: Option<tokio::task::JoinHandle<Result<String, String>>>,
}

/// Live agent-CLI runs, keyed by the frontend's per-run session id → the spawned engine's OS pid,
/// so the UI can terminate a run in progress. Stop kills the WHOLE tree of that one pid (on Windows
/// the pid is a cmd.exe/node subtree), never a blanket claude.exe sweep — only the app-spawned run
/// dies, not the user's own Claude session. Entries auto-remove when a run ends (RAII guard in
/// run_agent_cli), so a stale/reused pid is never targeted.
#[derive(Default)]
struct AgentProcs(Mutex<HashMap<String, u32>>);

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
    offscreen: Option<bool>,
) -> Result<(), String> {
    let parsed: url::Url = url.parse().map_err(|e| format!("Invalid URL: {}", e))?;
    // Transient/background tabs are born off-screen: they must render to register as a CDP
    // target and load, but the user should never see them flash over the UI.
    let x0 = if offscreen == Some(true) { -4000.0 } else { 0.0 };

    let app_clone = app.clone();
    let (tx, rx) = oneshot::channel::<Result<(), String>>();
    // WebView2 controllers are STA COM objects: `add_child` must run on the main thread. Off it
    // (as a plain tauri::async_runtime::spawn task), creation still reports Ok — the tab gets
    // registered — but the controller's native message pump is never serviced again once that
    // one-off task thread exits, so every later call into it hangs (`wv.url()` fails with
    // "failed to receive message from webview") and it never appears as a CDP target. The Linux
    // branch below already does this correctly for the same reason (GTK has the same constraint).
    //
    // Scheduling alone is not enough: run_on_main_thread only QUEUES the closure, so returning
    // right after it left the caller racing the creation it just asked for. The frontend took the
    // Ok as "the webview exists", set browserCreated, and immediately called show_webview /
    // set_webview_bounds against a label the main thread had not registered yet — those calls hit
    // a half-built controller, and the tab ended up alive in the tab strip but never a CDP target,
    // with every later call failing "failed to receive message from webview". A creation failure
    // was invisible for the same reason: the error stayed in the closure and the caller saw Ok.
    // So await the closure's result, exactly as the Linux branch does, and report it.
    let scheduled = app.run_on_main_thread(move || {
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

        let result = (|| -> Result<(), String> {
            let window = app_clone
                .get_window("main")
                .ok_or_else(|| "Main window not found for embedded browser".to_string())?;

            // A child webview is positioned inside the main window's client area, so a window with
            // no client area gives it nowhere to render. add_child still returns Ok, and the tab
            // appears in the strip, but it never paints, never registers as a CDP target, and every
            // later call into it fails "failed to receive message from webview". The only symptom
            // the user got was the CDP watchdog's "debug endpoint has been unresponsive" ~40s later,
            // which describes a consequence and names nothing actionable. Minimizing the app is
            // enough to cause it (measured: is_minimized true, inner_size 0x0, tabs dead; restoring
            // the window made the very next tab register immediately).
            //
            // Refuse up front and say which window state is the problem. Deliberately NOT
            // auto-restoring: pulling the window to the foreground mid-run is the same
            // steal-the-user's-focus behaviour the browser-args note above rejected.
            if window.is_minimized().unwrap_or(false) {
                return Err("The app window is minimized, so an embedded browser tab has nowhere \
                            to render. Restore the window and try again."
                    .to_string());
            }
            match window.inner_size() {
                Ok(size) if size.width == 0 || size.height == 0 => {
                    return Err(format!(
                        "The app window has no drawable area ({}x{}), so an embedded browser tab \
                         has nowhere to render. Restore or resize the window and try again.",
                        size.width, size.height
                    ));
                }
                _ => {}
            }

            window
                .add_child(
                    builder,
                    tauri::LogicalPosition::new(x0, 60.0),
                    tauri::LogicalSize::new(800.0, 600.0),
                )
                .map_err(|e| format!("Failed to create embedded browser: {}", e))?;
            {
                let state = app_clone.state::<Mutex<WebviewState>>();
                let mut guard = state
                    .lock()
                    .map_err(|e| format!("Webview state lock poisoned: {}", e))?;
                guard.tabs.insert(tab_id.clone(), label.clone());
            }
            let _ = app_clone.emit("browser-status", "embedded-open");
            Ok(())
        })();

        if let Err(e) = &result {
            eprintln!("[steve] {}", e);
            let _ = app_clone.emit("browser-status", "error");
        }
        let _ = tx.send(result);
    });
    if let Err(e) = scheduled {
        eprintln!("[steve] Failed to schedule embedded browser creation: {}", e);
        let _ = app.emit("browser-status", "error");
        return Err(format!("Failed to schedule embedded browser creation: {}", e));
    }

    rx.await
        .map_err(|_| "Main thread response channel closed".to_string())??;

    Ok(())
}

#[cfg(target_os = "linux")]
#[tauri::command]
async fn create_embedded_browser(
    app: tauri::AppHandle,
    tab_id: String,
    url: String,
    offscreen: Option<bool>,
) -> Result<(), String> {
    let url_str = url.clone();
    let app_clone = app.clone();
    let tab_id_clone = tab_id.clone();
    let x0 = if offscreen == Some(true) { -4000.0_f64 } else { 0.0_f64 };
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
                    position: wry::dpi::LogicalPosition::new(x0, 60.0_f64).into(),
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

/// Absolute form of a project-relative path — for handing files to spawned CLIs, whose cwd is
/// the temp dir (see run_agent_cli), not the project root.
#[tauri::command]
fn resolve_path(path: String) -> Result<String, String> {
    Ok(resolve_project_path(&path)?.to_string_lossy().into_owned())
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

// ── Artifacts: agent captures (screenshots + screen recordings) ────────────
// Live-account screenshots can hold student data, so they live under the OS app-data dir
// (OUTSIDE the git repo) — never in project_root where they could be committed. The agent saves
// screenshots here (told the absolute path via the exec prompt) and drives recording via the
// __steveControl bridge → start/stop_recording below.

fn now_ms() -> u128 {
    std::time::SystemTime::now()
        .duration_since(std::time::UNIX_EPOCH)
        .map(|d| d.as_millis())
        .unwrap_or(0)
}

fn artifacts_path(app: &tauri::AppHandle) -> Result<std::path::PathBuf, String> {
    let dir = app
        .path()
        .app_data_dir()
        .map_err(|e| e.to_string())?
        .join("artifacts");
    std::fs::create_dir_all(&dir).map_err(|e| e.to_string())?;
    Ok(dir)
}

/// Reject anything that could escape the artifacts dir — the name must be a bare filename.
fn safe_artifact_name(name: &str) -> Result<(), String> {
    if name.is_empty() || name.contains('/') || name.contains('\\') || name.contains("..") {
        return Err(format!("invalid artifact name: {}", name));
    }
    Ok(())
}

#[derive(serde::Serialize)]
struct ArtifactMeta {
    name: String,
    kind: String, // "image" | "video" | "other"
    size: u64,
    mtime: u64, // ms since epoch
    /// data: URL for image tiles; None for video/other (the frontend shows a placeholder + opens externally).
    thumb: Option<String>,
}

#[tauri::command]
fn artifacts_dir(app: tauri::AppHandle) -> Result<String, String> {
    Ok(artifacts_path(&app)?.to_string_lossy().to_string())
}

/// Save bytes into the artifacts gallery and return the absolute path.
///
/// `write_file` takes a String, so a PNG round-tripped through it would be mangled. The name is
/// reduced to its final component: the caller is ultimately an agent, and a name carrying `..`
/// would write wherever it liked.
#[tauri::command]
fn save_artifact(app: tauri::AppHandle, name: String, bytes: Vec<u8>) -> Result<String, String> {
    const MAX: usize = 64 * 1024 * 1024;
    if bytes.is_empty() {
        return Err("nothing to save".into());
    }
    if bytes.len() > MAX {
        return Err(format!("artifact is {} bytes; limit is {}", bytes.len(), MAX));
    }
    let leaf = name
        .rsplit(['/', '\\'])
        .next()
        .filter(|s| !s.is_empty() && *s != "." && *s != "..")
        .ok_or("invalid artifact name")?;
    let dir = artifacts_path(&app)?;
    std::fs::create_dir_all(&dir).map_err(|e| e.to_string())?;
    let target = dir.join(leaf);
    std::fs::write(&target, bytes).map_err(|e| e.to_string())?;
    Ok(target.to_string_lossy().to_string())
}

#[tauri::command]
fn list_artifacts(app: tauri::AppHandle) -> Result<Vec<ArtifactMeta>, String> {
    use base64::Engine;
    let dir = artifacts_path(&app)?;
    let mut items: Vec<ArtifactMeta> = Vec::new();
    for entry in std::fs::read_dir(&dir).map_err(|e| e.to_string())?.flatten() {
        let p = entry.path();
        if !p.is_file() {
            continue;
        }
        let name = entry.file_name().to_string_lossy().to_string();
        let ext = p
            .extension()
            .and_then(|e| e.to_str())
            .unwrap_or("")
            .to_lowercase();
        let (kind, mime): (&str, &str) = match ext.as_str() {
            "png" => ("image", "image/png"),
            "jpg" | "jpeg" => ("image", "image/jpeg"),
            "webp" => ("image", "image/webp"),
            "gif" => ("image", "image/gif"),
            "mp4" => ("video", "video/mp4"),
            "webm" => ("video", "video/webm"),
            _ => ("other", ""),
        };
        let meta = match entry.metadata() {
            Ok(m) => m,
            Err(_) => continue,
        };
        let mtime = meta
            .modified()
            .ok()
            .and_then(|t| t.duration_since(std::time::UNIX_EPOCH).ok())
            .map(|d| d.as_millis() as u64)
            .unwrap_or(0);
        // ponytail: images embed full base64 (screenshots are small; a few MB total is fine). Add a
        // downscale via the `image` crate only if a big gallery makes the IPC payload sluggish.
        let thumb = if kind == "image" {
            std::fs::read(&p)
                .ok()
                .map(|b| format!("data:{};base64,{}", mime, base64::engine::general_purpose::STANDARD.encode(b)))
        } else {
            None
        };
        items.push(ArtifactMeta {
            name,
            kind: kind.to_string(),
            size: meta.len(),
            mtime,
            thumb,
        });
    }
    items.sort_by(|a, b| b.mtime.cmp(&a.mtime)); // newest first
    Ok(items)
}

#[tauri::command]
fn delete_artifact(app: tauri::AppHandle, name: String) -> Result<(), String> {
    safe_artifact_name(&name)?;
    let p = artifacts_path(&app)?.join(&name);
    std::fs::remove_file(&p).map_err(|e| e.to_string())
}

/// Read an artifact as a data: URL so the app can show it INLINE (image tag / video element).
/// Binary-safe (read_file is read_to_string and corrupts PNG/mp4 bytes), so this is its own command.
#[tauri::command]
fn read_artifact(app: tauri::AppHandle, name: String) -> Result<String, String> {
    use base64::Engine;
    safe_artifact_name(&name)?;
    let p = artifacts_path(&app)?.join(&name);
    let ext = p
        .extension()
        .and_then(|e| e.to_str())
        .unwrap_or("")
        .to_lowercase();
    let mime = match ext.as_str() {
        "png" => "image/png",
        "jpg" | "jpeg" => "image/jpeg",
        "webp" => "image/webp",
        "gif" => "image/gif",
        "mp4" => "video/mp4",
        "webm" => "video/webm",
        _ => "application/octet-stream",
    };
    let bytes = std::fs::read(&p).map_err(|e| e.to_string())?;
    Ok(format!(
        "data:{};base64,{}",
        mime,
        base64::engine::general_purpose::STANDARD.encode(bytes)
    ))
}

#[tauri::command]
fn open_artifact(app: tauri::AppHandle, name: String) -> Result<(), String> {
    safe_artifact_name(&name)?;
    let p = artifacts_path(&app)?.join(&name);
    if !p.exists() {
        return Err("not found".into());
    }
    let s = p.to_string_lossy().to_string();
    #[cfg(windows)]
    {
        use std::os::windows::process::CommandExt;
        std::process::Command::new("cmd")
            .args(["/c", "start", "", &s])
            .creation_flags(0x0800_0000)
            .spawn()
            .map_err(|e| e.to_string())?;
    }
    #[cfg(target_os = "macos")]
    {
        std::process::Command::new("open").arg(&s).spawn().map_err(|e| e.to_string())?;
    }
    #[cfg(target_os = "linux")]
    {
        std::process::Command::new("xdg-open").arg(&s).spawn().map_err(|e| e.to_string())?;
    }
    Ok(())
}

/// Resolve the browser-level CDP ws endpoint and the target id of the tab to record. Recording the
/// TAB (not the OS window) means we capture only the embedded webview — never other, occluding
/// windows. `target_url` disambiguates when several tabs are open; without it, the first embedded
/// (non-app-UI) page target is used.
async fn resolve_browser_and_target(
    port: u16,
    target_url: Option<&str>,
) -> Result<(String, String), String> {
    let ver: serde_json::Value = reqwest::get(format!("http://127.0.0.1:{}/json/version", port))
        .await
        .map_err(|e| e.to_string())?
        .json()
        .await
        .map_err(|e| e.to_string())?;
    let browser_ws = ver["webSocketDebuggerUrl"]
        .as_str()
        .ok_or("no browser ws endpoint")?
        .to_string();

    let list: serde_json::Value = reqwest::get(format!("http://127.0.0.1:{}/json/list", port))
        .await
        .map_err(|e| e.to_string())?
        .json()
        .await
        .map_err(|e| e.to_string())?;
    let arr = list.as_array().ok_or("bad /json/list")?;
    let is_embedded = |u: &str| u.starts_with("http") && !u.starts_with("http://localhost:5174");
    let pick = arr
        .iter()
        .find(|t| t["type"] == "page" && target_url.is_some() && t["url"].as_str() == target_url)
        .or_else(|| {
            arr.iter()
                .find(|t| t["type"] == "page" && t["url"].as_str().map(is_embedded).unwrap_or(false))
        });
    let target_id = pick
        .and_then(|t| t["id"].as_str())
        .ok_or("no embedded tab target to record")?
        .to_string();
    Ok((browser_ws, target_id))
}

/// The background capture: attach to the target (flatten session, so it coexists with the agent's
/// own CDP connection), start a jpeg screencast, and pipe each frame to ffmpeg (image2pipe) until
/// `stop` is set. Returns the finalized mp4 path.
async fn record_screencast(
    browser_ws: String,
    target_id: String,
    out_path: String,
    stop: std::sync::Arc<std::sync::atomic::AtomicBool>,
) -> Result<String, String> {
    use base64::Engine;
    use futures_util::{SinkExt, StreamExt};
    use tokio::io::AsyncWriteExt;
    use tokio_tungstenite::tungstenite::Message;

    let mut cmd = tokio::process::Command::new("ffmpeg");
    cmd.args([
        "-f", "image2pipe", "-framerate", "10", "-i", "-", "-pix_fmt", "yuv420p", "-y", &out_path,
    ])
    .stdin(std::process::Stdio::piped())
    .stdout(std::process::Stdio::null())
    .stderr(std::process::Stdio::null());
    #[cfg(windows)]
    {
        use std::os::windows::process::CommandExt;
        cmd.creation_flags(0x0800_0000); // CREATE_NO_WINDOW
    }
    let mut ff = cmd
        .spawn()
        .map_err(|e| format!("ffmpeg failed to start (is it on PATH?): {}", e))?;
    let mut ff_stdin = ff.stdin.take().ok_or("no ffmpeg stdin")?;

    let (ws, _) = tokio_tungstenite::connect_async(browser_ws.as_str())
        .await
        .map_err(|e| format!("CDP ws connect failed: {}", e))?;
    let (mut write, mut read) = ws.split();

    // Attach to the target with a flattened session — multiple attachments to one target are
    // allowed, so this does not fight the agent's own connection.
    write
        .send(Message::Text(format!(
            r#"{{"id":1,"method":"Target.attachToTarget","params":{{"targetId":"{}","flatten":true}}}}"#,
            target_id
        )))
        .await
        .ok();

    let mut session = String::new();
    while session.is_empty() {
        match tokio::time::timeout(std::time::Duration::from_secs(3), read.next()).await {
            Ok(Some(Ok(Message::Text(t)))) => {
                let v: serde_json::Value = serde_json::from_str(&t).unwrap_or_default();
                if v["id"] == 1 {
                    if let Some(s) = v["result"]["sessionId"].as_str() {
                        session = s.to_string();
                    } else {
                        return Err("attachToTarget failed".into());
                    }
                } else if v["method"] == "Target.attachedToTarget" {
                    if let Some(s) = v["params"]["sessionId"].as_str() {
                        session = s.to_string();
                    }
                }
            }
            Ok(Some(Ok(_))) => {}
            Ok(Some(Err(e))) => return Err(e.to_string()),
            Ok(None) => return Err("ws closed before attach".into()),
            Err(_) => return Err("timed out attaching to the tab".into()),
        }
    }

    write
        .send(Message::Text(format!(
            r#"{{"id":2,"sessionId":"{}","method":"Page.enable"}}"#,
            session
        )))
        .await
        .ok();
    write
        .send(Message::Text(format!(
            r#"{{"id":3,"sessionId":"{}","method":"Page.startScreencast","params":{{"format":"jpeg","quality":60,"everyNthFrame":1}}}}"#,
            session
        )))
        .await
        .ok();

    let mut frames: u64 = 0;
    while !stop.load(std::sync::atomic::Ordering::Relaxed) {
        match tokio::time::timeout(std::time::Duration::from_millis(400), read.next()).await {
            Ok(Some(Ok(Message::Text(t)))) => {
                let v: serde_json::Value = match serde_json::from_str(&t) {
                    Ok(v) => v,
                    Err(_) => continue,
                };
                if v["method"] == "Page.screencastFrame" {
                    if let Some(data) = v["params"]["data"].as_str() {
                        if let Ok(bytes) = base64::engine::general_purpose::STANDARD.decode(data) {
                            if ff_stdin.write_all(&bytes).await.is_ok() {
                                frames += 1;
                            }
                        }
                    }
                    let frame_sid = v["params"]["sessionId"].as_i64().unwrap_or(0);
                    write
                        .send(Message::Text(format!(
                            r#"{{"id":4,"sessionId":"{}","method":"Page.screencastFrameAck","params":{{"sessionId":{}}}}}"#,
                            session, frame_sid
                        )))
                        .await
                        .ok();
                }
            }
            Ok(Some(Ok(_))) => {}
            Ok(Some(Err(_))) | Ok(None) => break, // ws closed
            Err(_) => {}                           // timeout → re-check stop
        }
    }

    write
        .send(Message::Text(format!(
            r#"{{"id":5,"sessionId":"{}","method":"Page.stopScreencast"}}"#,
            session
        )))
        .await
        .ok();
    drop(ff_stdin); // EOF → ffmpeg writes the trailer and exits
    let _ = ff.wait().await;
    if frames == 0 {
        return Err("no frames captured".into());
    }
    Ok(out_path)
}

/// Start recording the DRIVEN TAB (the embedded webview) via CDP screencast → ffmpeg. Captures the
/// page the agent is on — cursor glide, flash, everything injected into it — and never any other
/// window. Agent-invokable via the __steveControl bridge. One recording at a time.
#[tauri::command]
async fn start_recording(
    app: tauri::AppHandle,
    target_url: Option<String>,
    state: tauri::State<'_, Mutex<RecordingState>>,
) -> Result<String, String> {
    {
        let rec = state.lock().map_err(|e| e.to_string())?;
        if rec.handle.is_some() {
            return Err("already recording".into());
        }
    }
    let port = app
        .state::<CdpPortState>()
        .port
        .ok_or("CDP debug port unavailable")?;
    let (browser_ws, target_id) = resolve_browser_and_target(port, target_url.as_deref()).await?;
    let out = artifacts_path(&app)?.join(format!("recording-{}.mp4", now_ms()));
    let out_s = out.to_string_lossy().to_string();

    let stop = std::sync::Arc::new(std::sync::atomic::AtomicBool::new(false));
    let handle = tokio::spawn(record_screencast(
        browser_ws,
        target_id,
        out_s.clone(),
        stop.clone(),
    ));

    let mut rec = state.lock().map_err(|e| e.to_string())?;
    rec.stop = Some(stop);
    rec.handle = Some(handle);
    Ok(out_s)
}

/// Stop the recording: signal the capture task, wait for ffmpeg to finalize, return the mp4 path.
#[tauri::command]
async fn stop_recording(
    state: tauri::State<'_, Mutex<RecordingState>>,
) -> Result<Option<String>, String> {
    let (stop, handle) = {
        let mut rec = state.lock().map_err(|e| e.to_string())?;
        (rec.stop.take(), rec.handle.take())
    };
    let Some(stop) = stop else {
        return Ok(None);
    };
    stop.store(true, std::sync::atomic::Ordering::Relaxed);
    match handle {
        Some(h) => match h.await {
            Ok(Ok(path)) => Ok(Some(path)),
            Ok(Err(e)) => Err(e),
            Err(e) => Err(format!("recording task failed: {}", e)),
        },
        None => Ok(None),
    }
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

/// Raw `/json` target list from the CDP endpoint, as text.
///
/// The WebView cannot fetch this itself: DevTools' HTTP endpoint sends no CORS
/// headers, so `fetch("http://127.0.0.1:<port>/json")` from the app UI fails
/// outright. That silently disabled marker-pinning — findTargetWsByMarker always
/// returned null and every connect fell back to first-found discovery, which is
/// the bug marker-pinning exists to prevent. WebSocket connections are not
/// blocked, so only this listing step needs to happen in Rust.
#[tauri::command]
async fn cdp_list_targets(port: u16) -> Result<String, String> {
    let client = reqwest::Client::builder()
        .timeout(std::time::Duration::from_secs(3))
        .build()
        .map_err(|e| format!("Failed to create HTTP client: {}", e))?;

    client
        .get(&format!("http://127.0.0.1:{}/json", port))
        .send()
        .await
        .map_err(|e| format!("Failed to fetch CDP targets: {}", e))?
        .text()
        .await
        .map_err(|e| format!("Failed to read CDP targets: {}", e))
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

    // Skip the app's OWN UI window. Matching hardcoded dev ports was a latent bug: the list said
    // 1420/5173 while vite actually serves 5174, leaving the app UI eligible — so CDP could attach
    // to the app's own DOM instead of the embedded page. Any loopback origin is the app UI; the
    // embedded browser drives external sites. Mirrors MAIN_APP_PATTERNS in cdp-client.ts.
    let is_app_ui = |url: &str| {
        url.starts_with("tauri://localhost")
            || url.starts_with("https://tauri.localhost")
            || ["http://localhost", "https://localhost", "http://127.0.0.1", "https://127.0.0.1"]
                .iter()
                .any(|p| {
                    url.strip_prefix(p)
                        .is_some_and(|rest| rest.is_empty() || rest.starts_with('/') || rest.starts_with(':'))
                })
    };

    let target = targets.iter().find(|t| {
        t.target_type == "page" && t.url != "about:blank" && !t.url.is_empty() && !is_app_ui(&t.url)
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
        // Prefer an executable extension over a bare, extensionless file. npm installs a POSIX
        // shell script named `opencode` (which CreateProcess can't run — os error 193) right next
        // to the real `opencode.cmd`; taking the bare name first is exactly that bug. On Unix
        // `exts` is [""], so this still matches the bare binary.
        for ext in &exts {
            let candidate = dir.join(format!("{}{}", binary, ext));
            if candidate.is_file() {
                return Some(candidate);
            }
        }
        let direct = dir.join(binary);
        if direct.is_file() {
            return Some(direct);
        }
    }
    None
}

/// Whether the `claude` agent CLI is resolvable on PATH — the real readiness signal the Dashboard
/// shows. The app spawns `claude` itself, so PATH presence is what it can actually verify.
#[tauri::command]
fn claude_cli_available() -> bool {
    resolve_on_path("claude").is_some()
}

/// Build a `claude …` command with the given args, resolving the binary on PATH and routing a
/// .cmd/.bat shim through cmd.exe (CreateProcess can't run a shim directly). Windows spawns are
/// windowless — the user sees the app's own UI and their browser, never a console.
fn claude_command(args: &[&str]) -> Result<tokio::process::Command, String> {
    let bin = resolve_on_path("claude").ok_or_else(|| {
        "Claude Code CLI (`claude`) not found on PATH. Install it, then try again.".to_string()
    })?;
    let is_shim = bin
        .extension()
        .and_then(|e| e.to_str())
        .map(|e| {
            let e = e.to_lowercase();
            e == "cmd" || e == "bat"
        })
        .unwrap_or(false);
    let mut cmd = if is_shim {
        let sysroot = std::env::var("SystemRoot").unwrap_or_else(|_| "C:\\Windows".into());
        let mut c = tokio::process::Command::new(format!("{}\\System32\\cmd.exe", sysroot));
        c.arg("/c").arg(&bin).args(args);
        c
    } else {
        let mut c = tokio::process::Command::new(&bin);
        c.args(args);
        c
    };
    #[cfg(windows)]
    {
        use std::os::windows::process::CommandExt;
        cmd.creation_flags(0x0800_0000); // CREATE_NO_WINDOW
    }
    Ok(cmd)
}

/// Pay the agent CLI's cold-start cost at app startup, while nothing is playing yet.
///
/// The FIRST spawn of `claude`/`opencode` in a process's lifetime is expensive — Node module load
/// plus MCP config resolve, cold off disk. `cdp-watchdog.ts` already documents that this stalls the
/// WebView2 main thread ~15s, and carries a warmup window purely to stop it raising a false
/// "endpoint unresponsive" alarm. What that note misses is what the same stall does to a video: the
/// media element PAUSES, nothing replays it, and it sits there. Measured on a live SafeSchools
/// section, video started at 0 and a run fired immediately:
///
///   cold spawn, first run of the app   pause at ct 47.35, still paused 2 minutes later
///   warm spawn, same shallow buffer    173s continuous, never paused
///   warm spawn, deep buffer            182s at rate 1.000
///   no agent at all                    full 315s section, no mid-video pause
///
/// Only coldness separates those, so this runs the expensive spawn once at setup — before any tab
/// or video can exist, which is what makes it a fix rather than a race the user can lose. `--version`
/// is enough: the cost is loading the binary and its module tree off disk, and it neither contacts
/// the API nor starts a session.
///
/// Deliberately not the alternative fix of resuming a video that got paused: the app cannot tell its
/// own stall-induced pause from a legitimate one (an in-video knowledge check pauses exactly the same
/// way), and silently resuming past a check would skip required training.
fn prewarm_agent_engines() {
    for engine in ["claude", "opencode"] {
        let Some(bin) = resolve_on_path(engine) else { continue };
        std::thread::spawn(move || {
            let mut cmd = std::process::Command::new(&bin);
            cmd.arg("--version")
                .stdin(std::process::Stdio::null())
                .stdout(std::process::Stdio::null())
                .stderr(std::process::Stdio::null());
            #[cfg(windows)]
            {
                use std::os::windows::process::CommandExt;
                cmd.creation_flags(0x0800_0000); // CREATE_NO_WINDOW
            }
            // Best-effort: a missing or unhappy CLI must never keep the app from starting. Reap it
            // rather than leaving a zombie, but do not care what it said.
            if let Ok(mut child) = cmd.spawn() {
                let _ = child.wait();
            }
        });
    }
}

/// An in-flight `claude auth login` process, held between start (which returns the sign-in URL) and
/// submit (which feeds back the pasted code). One login at a time.
struct LoginChild {
    child: tokio::process::Child,
    stdin: tokio::process::ChildStdin,
}

#[derive(Default)]
struct LoginProc(Mutex<Option<LoginChild>>);

/// Current Claude auth as reported by `claude auth status` (JSON: loggedIn, email,
/// subscriptionType, …). Non-JSON / error output is normalised to `{ loggedIn: false }` so the UI
/// always gets a shape it can read.
#[tauri::command]
async fn claude_auth_status() -> Result<serde_json::Value, String> {
    let out = claude_command(&["auth", "status"])?
        .output()
        .await
        .map_err(|e| format!("Failed to run claude auth status: {}", e))?;
    let stdout = String::from_utf8_lossy(&out.stdout);
    if let Ok(v) = serde_json::from_str::<serde_json::Value>(stdout.trim()) {
        return Ok(v);
    }
    Ok(serde_json::json!({ "loggedIn": false }))
}

/// Start the browser sign-in: spawn `claude auth login --claudeai` (subscription login, no method
/// picker), read its stdout until it prints the OAuth URL, and keep the process alive with stdin
/// open. The CLI opens the browser itself; the user approves, copies the code claude.com shows, and
/// pastes it back via `claude_login_submit`. Returns the URL so the UI can also offer it as a link.
#[tauri::command]
async fn claude_login_start(state: tauri::State<'_, LoginProc>) -> Result<String, String> {
    use tokio::io::{AsyncBufReadExt, BufReader};

    // Drop any prior in-flight login before starting a new one.
    {
        let prev = state.0.lock().unwrap().take();
        if let Some(mut l) = prev {
            let _ = l.child.start_kill();
        }
    }

    let mut cmd = claude_command(&["auth", "login", "--claudeai"])?;
    cmd.stdin(std::process::Stdio::piped())
        .stdout(std::process::Stdio::piped())
        .stderr(std::process::Stdio::piped());
    let mut child = cmd
        .spawn()
        .map_err(|e| format!("Failed to start claude auth login: {}", e))?;
    let stdout = child.stdout.take().ok_or("no stdout from claude login")?;
    let stdin = child.stdin.take().ok_or("no stdin for claude login")?;

    // The URL is printed immediately; bound the wait so a hung login can't wedge the UI.
    let mut lines = BufReader::new(stdout).lines();
    let found = tokio::time::timeout(std::time::Duration::from_secs(20), async {
        while let Ok(Some(line)) = lines.next_line().await {
            if let Some(idx) = line.find("https://") {
                let u = line[idx..].trim().to_string();
                if u.contains("oauth") {
                    return Some(u);
                }
            }
        }
        None
    })
    .await
    .map_err(|_| "Timed out waiting for the Claude sign-in link.".to_string())?;
    let url = found.ok_or("Claude login exited before returning a sign-in link.".to_string())?;

    // Keep draining stdout so the CLI's pipe never blocks while it waits for the pasted code.
    tokio::spawn(async move { while let Ok(Some(_)) = lines.next_line().await {} });

    *state.0.lock().unwrap() = Some(LoginChild { child, stdin });
    Ok(url)
}

/// Finish sign-in: write the pasted code to the waiting `claude auth login` process, close its
/// stdin so it finalises the token, and return the resulting `claude auth status`.
#[tauri::command]
async fn claude_login_submit(
    state: tauri::State<'_, LoginProc>,
    code: String,
) -> Result<serde_json::Value, String> {
    use tokio::io::AsyncWriteExt;

    let login = state.0.lock().unwrap().take();
    let mut login = login.ok_or("No sign-in is in progress — start again.".to_string())?;
    let line = format!("{}\n", code.trim());
    login
        .stdin
        .write_all(line.as_bytes())
        .await
        .map_err(|e| format!("Failed to send the code: {}", e))?;
    let _ = login.stdin.flush().await;
    drop(login.stdin); // EOF → the CLI stops waiting and stores the token
    let _ = tokio::time::timeout(std::time::Duration::from_secs(25), login.child.wait()).await;
    // The credential lands a beat after the process exits, so a status query fired immediately can
    // still read "not signed in" (the UI then looked stale until the user left + returned). Poll a
    // few times until it settles instead of trusting the first read.
    let mut status = claude_auth_status().await?;
    for _ in 0..6 {
        if status.get("loggedIn").and_then(|v| v.as_bool()).unwrap_or(false) {
            break;
        }
        tokio::time::sleep(std::time::Duration::from_millis(500)).await;
        status = claude_auth_status().await?;
    }
    Ok(status)
}

/// Abort an in-flight sign-in (user cancelled).
#[tauri::command]
async fn claude_login_cancel(state: tauri::State<'_, LoginProc>) -> Result<(), String> {
    let login = state.0.lock().unwrap().take();
    if let Some(mut l) = login {
        let _ = l.child.start_kill();
        let _ = l.child.wait().await;
    }
    Ok(())
}

/// Sign out (`claude auth logout`) and return the refreshed status.
#[tauri::command]
async fn claude_auth_logout() -> Result<serde_json::Value, String> {
    let _ = claude_command(&["auth", "logout"])?
        .output()
        .await
        .map_err(|e| format!("Failed to run claude auth logout: {}", e))?;
    claude_auth_status().await
}

/// Path to opencode's credential store — the same `auth.json` opencode's own `auth login` writes
/// (`$XDG_DATA_HOME/opencode` or `~/.local/share/opencode`). We read/write it directly so a
/// non-technical user can add a cloud provider key from the app without opencode's terminal picker.
fn opencode_auth_path(app: &tauri::AppHandle) -> Result<std::path::PathBuf, String> {
    let base = if let Ok(x) = std::env::var("XDG_DATA_HOME") {
        std::path::PathBuf::from(x)
    } else {
        app.path()
            .home_dir()
            .map_err(|e| format!("Failed to get home dir: {}", e))?
            .join(".local")
            .join("share")
    };
    Ok(base.join("opencode").join("auth.json"))
}

fn read_opencode_auth(
    app: &tauri::AppHandle,
) -> Result<serde_json::Map<String, serde_json::Value>, String> {
    let path = opencode_auth_path(app)?;
    if !path.exists() {
        return Ok(serde_json::Map::new());
    }
    let raw = std::fs::read_to_string(&path)
        .map_err(|e| format!("Failed to read {}: {}", path.display(), e))?;
    let v: serde_json::Value =
        serde_json::from_str(&raw).map_err(|e| format!("opencode auth.json is not valid JSON: {}", e))?;
    Ok(v.as_object().cloned().unwrap_or_default())
}

/// Whether opencode has a credential for the given provider (e.g. "ollama-cloud"). Gates the
/// run-time model picker — no key means no models to choose.
#[tauri::command]
async fn opencode_has_credential(app: tauri::AppHandle, provider: String) -> Result<bool, String> {
    Ok(read_opencode_auth(&app)?.contains_key(provider.trim()))
}

/// Save an API-key credential for an opencode provider (e.g. "ollama-cloud"), merged into the same
/// auth.json opencode reads at run time. Other providers already there are preserved.
#[tauri::command]
async fn opencode_save_key(
    app: tauri::AppHandle,
    provider: String,
    key: String,
) -> Result<(), String> {
    let provider = provider.trim();
    let key = key.trim();
    if provider.is_empty() {
        return Err("Pick a provider first.".into());
    }
    if key.is_empty() {
        return Err("Enter the API key.".into());
    }
    let path = opencode_auth_path(&app)?;
    let mut map = read_opencode_auth(&app)?;
    map.insert(
        provider.to_string(),
        serde_json::json!({ "type": "api", "key": key }),
    );
    if let Some(parent) = path.parent() {
        std::fs::create_dir_all(parent)
            .map_err(|e| format!("Failed to create {}: {}", parent.display(), e))?;
    }
    let out = serde_json::to_string_pretty(&serde_json::Value::Object(map))
        .map_err(|e| e.to_string())?;
    std::fs::write(&path, out).map_err(|e| format!("Failed to write {}: {}", path.display(), e))?;
    Ok(())
}

/// Terminate a running agent CLI by its frontend session id. Kills only that one spawned pid's
/// tree — on Windows `taskkill /T /F`, elsewhere the pid — so the user's own Claude session and
/// other work are untouched. No-op (Ok) if the session already finished.
#[tauri::command]
async fn stop_agent_cli(app: tauri::AppHandle, session_id: String) -> Result<bool, String> {
    let pid = {
        let st = app
            .try_state::<AgentProcs>()
            .ok_or_else(|| "agent process registry unavailable".to_string())?;
        let m = st.0.lock().map_err(|e| e.to_string())?;
        m.get(&session_id).copied()
    };
    let Some(pid) = pid else { return Ok(false) };

    #[cfg(windows)]
    {
        let sysroot = std::env::var("SystemRoot").unwrap_or_else(|_| "C:\\Windows".into());
        let status = tokio::process::Command::new(format!("{}\\System32\\taskkill.exe", sysroot))
            .args(["/PID", &pid.to_string(), "/T", "/F"])
            .creation_flags(0x0800_0000) // CREATE_NO_WINDOW
            .status()
            .await
            .map_err(|e| format!("taskkill failed: {}", e))?;
        // Not fatal if the tree already exited between lookup and kill.
        let _ = status;
    }
    #[cfg(not(windows))]
    {
        let _ = tokio::process::Command::new("kill")
            .args(["-TERM", &pid.to_string()])
            .status()
            .await;
    }
    // The run's own RAII guard removes the registry entry when its process dies; drop it here too
    // so a repeated Stop is an immediate no-op.
    if let Some(st) = app.try_state::<AgentProcs>() {
        if let Ok(mut m) = st.0.lock() {
            m.remove(&session_id);
        }
    }
    Ok(true)
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
    // opencode only. Selects one of its configured agents; `summary` carries almost no
    // tools. See the opencode arm below for why that matters. None keeps today's behaviour.
    agent: Option<String>,
    // claude only. A `{"mcpServers":{...}}` blob, injected with --mcp-config. Paired with the
    // --strict-mcp-config below, this is the ONLY server the spawned CLI loads — the user's own
    // MCP servers stay out of a run that is driving their live gradebook.
    mcp_config: Option<String>,
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
            if let Some(cfg) = mcp_config.as_ref().filter(|s| !s.trim().is_empty()) {
                args.extend(["--mcp-config".into(), cfg.clone()]);
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
            // The prompt goes in on stdin (opencode run reads it), same as claude.
            args.push("run".into());
            // `--agent` is opencode's equivalent of claude's `--disallowed-tools "*"`: it
            // picks which tool set gets loaded. The default agent boots the entire coding
            // stack — skills, tool schemas, git snapshots — costing ~29.3K input tokens
            // before the prompt is even added, which overruns a local Ollama's 32768 ceiling
            // and truncates the reply. Measured here: default 29329, plan 29609, title 4945,
            // summary 4560. `--pure` does NOT help; it only skips external plugins.
            // Callers that just want a completion (grading) pass "summary"; the browser
            // agent passes None because it genuinely needs the tools.
            if let Some(a) = agent.filter(|s| !s.trim().is_empty()) {
                args.extend(["--agent".into(), a]);
            }
            // Raw JSON events on stdout, one per line — for both live progress and final-text
            // parsing. opencode has no separate stream flag: `--format json` IS the event stream
            // (verified against the installed CLI: step_start / tool_use / text / step_finish).
            args.extend(["--format".into(), "json".into()]);
            // Full-shell autonomous mode: auto-approve tool use so the agent can drive the browser
            // over CDP without a permission prompt (there is no interactive stdin to answer one).
            if bypass_permissions {
                args.push("--auto".into());
            }
            // We can't impose our own session id on opencode (it mints `ses_…` itself), so resume
            // continues its last session rather than one keyed by our uuid.
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

    // Register this run's pid so the UI can stop it; the guard removes it on every exit path
    // (normal, error, or timeout `?`), so the registry only ever holds genuinely-live runs.
    struct DeregGuard<'a> {
        app: &'a tauri::AppHandle,
        sid: &'a str,
    }
    impl Drop for DeregGuard<'_> {
        fn drop(&mut self) {
            if let Some(st) = self.app.try_state::<AgentProcs>() {
                if let Ok(mut m) = st.0.lock() {
                    m.remove(self.sid);
                }
            }
        }
    }
    if let Some(pid) = child.id() {
        if let Some(st) = app.try_state::<AgentProcs>() {
            if let Ok(mut m) = st.0.lock() {
                m.insert(session_id.clone(), pid);
            }
        }
    }
    let _dereg = DeregGuard { app: &app, sid: &session_id };

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

// ── mom-island filesystem ──────────────────────────────────────────────────
// The MOM question bank lives on disk, so the walk/read/copy belong here: a WebView has no
// filesystem, and the integration previously imported node:fs directly, which threw on import
// and white-screened the whole app. These commands are the only FS door for mom-island.

#[derive(serde::Serialize)]
#[serde(rename_all = "camelCase")]
struct MomQuestion {
    slug: String,
    path: String,
    has_manifest: bool,
}

#[derive(serde::Serialize)]
struct MomFamily {
    name: String,
    count: usize,
    questions: Vec<MomQuestion>,
}

#[derive(serde::Serialize)]
#[serde(rename_all = "camelCase")]
struct MomQuestionRead {
    path: String,
    contents: String,
    manifest_text: Option<String>,
}

/// One assignment manifest under `books/`: its path relative to `books/` and its raw JSON.
/// The TS side parses; Rust only reads (the WebView can't touch the filesystem).
#[derive(serde::Serialize)]
#[serde(rename_all = "camelCase")]
struct MomBookFile {
    path: String,
    text: String,
}

#[derive(serde::Serialize)]
#[serde(rename_all = "camelCase")]
struct MomDraftResult {
    draft_path: String,
    family: String,
    slug: String,
}

/// Family folders that are Windows artifacts, never content. Mirrors JUNK_FAMILY_RE in loader.ts.
fn mom_is_junk_family(name: &str) -> bool {
    name.eq_ignore_ascii_case("nul")
        || name.eq_ignore_ascii_case("$APPDATA")
        || name.len() >= 7 && name[..7].eq_ignore_ascii_case("C:Users")
}

/// Mirrors SLUG_RE in draft.ts: leading alphanumeric, then alphanumeric / . _ -
fn mom_is_valid_slug(slug: &str) -> bool {
    let mut chars = slug.chars();
    match chars.next() {
        Some(c) if c.is_ascii_alphanumeric() => {}
        _ => return false,
    }
    chars.all(|c| c.is_ascii_alphanumeric() || c == '.' || c == '_' || c == '-')
}

/// Recursively collect every `.php` FILE under `dir`, one MomQuestion each. `slug` is the
/// file's path relative to the family root, with forward slashes — this matches how the book
/// manifests reference questions (`questions/<family>/<slug>`), so a book entry and the bank
/// share one identity.
fn mom_collect_php(dir: &std::path::Path, family_root: &std::path::Path, out: &mut Vec<MomQuestion>) {
    let entries = match std::fs::read_dir(dir) {
        Ok(e) => e,
        Err(_) => return,
    };
    for entry in entries.flatten() {
        let path = entry.path();
        if path.is_dir() {
            mom_collect_php(&path, family_root, out);
        } else if path.extension().and_then(|e| e.to_str()) == Some("php") {
            let slug = path
                .strip_prefix(family_root)
                .unwrap_or(&path)
                .to_string_lossy()
                .replace('\\', "/");
            let has_manifest = path
                .parent()
                .map(|d| d.join("manifest.json").exists())
                .unwrap_or(false);
            out.push(MomQuestion {
                slug,
                path: path.to_string_lossy().to_string(),
                has_manifest,
            });
        }
    }
}

/// Walk `<root>/questions/<family>/…`. A question is any `.php` FILE anywhere under a family,
/// flat or nested (the real bank mixes both, and `frq/` nests by subtopic). A missing
/// questions dir yields an empty list rather than an error — the UI renders "nothing here
/// yet" instead of failing.
fn mom_walk(root: &std::path::Path) -> Vec<MomFamily> {
    let questions_dir = root.join("questions");
    let mut families: Vec<MomFamily> = Vec::new();
    let entries = match std::fs::read_dir(&questions_dir) {
        Ok(e) => e,
        Err(_) => return families,
    };

    for entry in entries.flatten() {
        let family_name = entry.file_name().to_string_lossy().to_string();
        if mom_is_junk_family(&family_name) {
            continue;
        }
        let family_path = entry.path();
        if !family_path.is_dir() {
            continue;
        }

        let mut questions: Vec<MomQuestion> = Vec::new();
        mom_collect_php(&family_path, &family_path, &mut questions);

        if questions.is_empty() {
            continue;
        }
        questions.sort_by(|a, b| a.slug.cmp(&b.slug));
        families.push(MomFamily {
            name: family_name,
            count: questions.len(),
            questions,
        });
    }

    families.sort_by(|a, b| a.name.cmp(&b.name));
    families
}

/// Recursively collect every `.json` assignment manifest under `books/`.
fn mom_collect_books(dir: &std::path::Path, books_root: &std::path::Path, out: &mut Vec<MomBookFile>) {
    let entries = match std::fs::read_dir(dir) {
        Ok(e) => e,
        Err(_) => return,
    };
    for entry in entries.flatten() {
        let path = entry.path();
        if path.is_dir() {
            mom_collect_books(&path, books_root, out);
        } else if path.extension().and_then(|e| e.to_str()) == Some("json") {
            if let Ok(text) = std::fs::read_to_string(&path) {
                let rel = path
                    .strip_prefix(books_root)
                    .unwrap_or(&path)
                    .to_string_lossy()
                    .replace('\\', "/");
                out.push(MomBookFile { path: rel, text });
            }
        }
    }
}

#[tauri::command]
async fn mom_load_index(root: String) -> Result<Vec<MomFamily>, String> {
    Ok(mom_walk(std::path::Path::new(&root)))
}

/// Read every assignment manifest under `<root>/books/`, raw. TS parses the JSON.
#[tauri::command]
async fn mom_load_books(root: String) -> Result<Vec<MomBookFile>, String> {
    let books_dir = std::path::Path::new(&root).join("books");
    let mut out: Vec<MomBookFile> = Vec::new();
    mom_collect_books(&books_dir, &books_dir, &mut out);
    out.sort_by(|a, b| a.path.cmp(&b.path));
    Ok(out)
}

/// Overwrite one assignment manifest under `<root>/books/`.
///
/// TS does the edit, because these files are hand-formatted and only the caller knows how to
/// change a field without reflowing the rest. This command's job is the guard: the target must
/// resolve to an existing `.json` INSIDE the books dir, so a crafted path cannot write elsewhere.
#[tauri::command]
async fn mom_write_book(root: String, path: String, text: String) -> Result<(), String> {
    let books_dir = std::path::Path::new(&root).join("books");
    let canon_books = books_dir
        .canonicalize()
        .map_err(|e| format!("books dir unavailable: {}", e))?;

    let target = books_dir.join(&path);
    // Canonicalize the EXISTING file: this command updates manifests, it does not create them,
    // so a path that does not resolve is a mistake rather than something to be helpful about.
    let canon_target = target
        .canonicalize()
        .map_err(|_| format!("manifest not found: {}", path))?;
    if !canon_target.starts_with(&canon_books) {
        return Err(format!("path escapes books dir: {}", path));
    }
    if canon_target.extension().and_then(|e| e.to_str()) != Some("json") {
        return Err(format!("refusing to write a non-json file: {}", path));
    }

    std::fs::write(&canon_target, text)
        .map_err(|e| format!("Failed to write {}: {}", canon_target.display(), e))
}

/// Create a NEW json file under `<root>/books/`, making any missing parent directories.
///
/// Separate from `mom_write_book` on purpose: that one canonicalizes an existing target, which is
/// the right guard for an update and useless for a create. The guards here do the same job without
/// needing the file to exist — the relative path may not escape (no `..`, no root/prefix
/// component), it must end in `.json`, and an existing file is an error rather than an overwrite,
/// so creating a book that is already there can never silently blank it.
#[tauri::command]
async fn mom_create_book_file(root: String, path: String, text: String) -> Result<(), String> {
    let rel = std::path::Path::new(&path);
    if rel.components().any(|c| !matches!(c, std::path::Component::Normal(_))) {
        return Err(format!("path must be relative and contain no '..': {}", path));
    }
    if rel.extension().and_then(|e| e.to_str()) != Some("json") {
        return Err(format!("refusing to write a non-json file: {}", path));
    }

    let books_dir = std::path::Path::new(&root).join("books");
    books_dir
        .canonicalize()
        .map_err(|e| format!("books dir unavailable: {}", e))?;

    let target = books_dir.join(rel);
    if target.exists() {
        return Err(format!("already exists: {}", path));
    }
    if let Some(parent) = target.parent() {
        std::fs::create_dir_all(parent)
            .map_err(|e| format!("Failed to create {}: {}", parent.display(), e))?;
    }
    std::fs::write(&target, text).map_err(|e| format!("Failed to write {}: {}", target.display(), e))
}

/// The MOM question-writing skill, compiled INTO the binary.
///
/// `include_str!` rather than a bundled resource on purpose: a resource has to be declared in
/// `tauri.conf.json`, copied by the bundler, and then located at runtime through a path that
/// differs between `tauri dev` and an installed build — three chances to ship an app whose skill
/// silently is not there. Embedding makes "the binary exists" and "the skill exists" the same fact.
const MOM_SKILL: &str = include_str!("../../skills/mom-question/SKILL.md");
const MOM_TRANSFER_SKILL: &str = include_str!("../../skills/mom-transfer/SKILL.md");

/// Install the bundled skill into the user's Claude Code skills directory.
///
/// It has to go THERE, not next to the app: `run_agent_cli` deliberately spawns the CLI in a
/// neutral temp cwd so the agent does not inherit this repo's instructions, which means a skill
/// shipped beside the app would never be discovered. `~/.claude/skills` is the one location that is
/// independent of the working directory.
///
/// Rewrites only when the content differs, so it is idempotent across launches and an upgraded app
/// ships an upgraded skill. That does mean hand-edits to this one file are replaced — it is
/// app-managed content, and the file says so. Failure is never fatal: a desktop app that refuses to
/// start because it could not write an optional file would be a worse bug than the missing skill.
fn install_mom_skill(app: &tauri::AppHandle) {
    install_bundled_skill(app, "mom-question", MOM_SKILL);
    install_bundled_skill(app, "mom-transfer", MOM_TRANSFER_SKILL);
}

/// Write one embedded skill to `~/.claude/skills/<name>/SKILL.md`.
fn install_bundled_skill(app: &tauri::AppHandle, name: &str, content: &str) {
    let Ok(home) = app.path().home_dir() else {
        return;
    };
    let dir = home.join(".claude").join("skills").join(name);
    let target = dir.join("SKILL.md");

    if std::fs::read_to_string(&target).is_ok_and(|existing| existing == content) {
        return; // already current
    }
    if let Err(e) = std::fs::create_dir_all(&dir) {
        eprintln!("[steve] could not create {}: {}", dir.display(), e);
        return;
    }
    match std::fs::write(&target, content) {
        Ok(()) => println!("[steve] installed {} skill -> {}", name, target.display()),
        Err(e) => eprintln!("[steve] could not install {} skill: {}", name, e),
    }
}

/// Reject a relative path that could escape the root it will be joined to.
fn mom_safe_rel(path: &str) -> Result<&std::path::Path, String> {
    let rel = std::path::Path::new(path);
    if rel
        .components()
        .any(|c| !matches!(c, std::path::Component::Normal(_)))
    {
        return Err(format!("path must be relative and contain no '..': {}", path));
    }
    Ok(rel)
}

/// Read a text file under the mom content root. Empty string when it does not exist yet.
///
/// Used for the writer's learned-rules file, which legitimately does not exist until the loop has
/// learned something — so a missing file is a normal state, not an error to surface.
#[tauri::command]
async fn mom_read_text(root: String, path: String) -> Result<String, String> {
    let rel = mom_safe_rel(&path)?;
    let target = std::path::Path::new(&root).join(rel);
    match std::fs::read_to_string(&target) {
        Ok(s) => Ok(s),
        Err(e) if e.kind() == std::io::ErrorKind::NotFound => Ok(String::new()),
        Err(e) => Err(format!("Failed to read {}: {}", target.display(), e)),
    }
}

/// Write a text file under the mom content root, creating parent directories.
///
/// Restricted to `reference/` on purpose: this is the path the app's own reflection step writes
/// through, and nothing generated should be able to overwrite a question or an assignment manifest
/// — those have their own commands, with their own guards.
#[tauri::command]
async fn mom_write_text(root: String, path: String, text: String) -> Result<(), String> {
    let rel = mom_safe_rel(&path)?;
    if !path.replace('\\', "/").starts_with("reference/") {
        return Err(format!("refusing to write outside reference/: {}", path));
    }
    let target = std::path::Path::new(&root).join(rel);
    if let Some(parent) = target.parent() {
        std::fs::create_dir_all(parent)
            .map_err(|e| format!("Failed to create {}: {}", parent.display(), e))?;
    }
    std::fs::write(&target, text).map_err(|e| format!("Failed to write {}: {}", target.display(), e))
}

/// Save an image pasted into the question writer, returning the path the agent can open.
///
/// The agent reads an example screenshot from a FILE, so a clipboard image has to be spilled to
/// disk. It goes to the OS temp dir, never into `mom-content/` — a scratch screenshot is not
/// course content and must not end up committed alongside the question bank.
///
/// The bytes are checked against the real image magic numbers rather than trusting the extension,
/// so this cannot be used to drop arbitrary content somewhere with a `.png` on the end.
#[tauri::command]
async fn mom_save_pasted_image(bytes: Vec<u8>) -> Result<String, String> {
    const MAX: usize = 20 * 1024 * 1024;
    if bytes.is_empty() {
        return Err("empty image".into());
    }
    if bytes.len() > MAX {
        return Err(format!("image is {} bytes; limit is {}", bytes.len(), MAX));
    }

    let ext = if bytes.starts_with(&[0x89, b'P', b'N', b'G', 0x0D, 0x0A, 0x1A, 0x0A]) {
        "png"
    } else if bytes.starts_with(&[0xFF, 0xD8, 0xFF]) {
        "jpg"
    } else if bytes.starts_with(b"GIF87a") || bytes.starts_with(b"GIF89a") {
        "gif"
    } else if bytes.len() > 12 && bytes.starts_with(b"RIFF") && &bytes[8..12] == b"WEBP" {
        "webp"
    } else {
        return Err("clipboard content is not a PNG, JPEG, GIF or WebP image".into());
    };

    let dir = std::env::temp_dir().join("steve-mom-paste");
    std::fs::create_dir_all(&dir).map_err(|e| format!("Failed to create {}: {}", dir.display(), e))?;
    let name = format!(
        "paste-{}.{}",
        std::time::SystemTime::now()
            .duration_since(std::time::UNIX_EPOCH)
            .map(|d| d.as_millis())
            .unwrap_or(0),
        ext
    );
    let path = dir.join(name);
    std::fs::write(&path, &bytes).map_err(|e| format!("Failed to write {}: {}", path.display(), e))?;
    Ok(path.to_string_lossy().to_string())
}

/// Resolve the in-repo `mom-content/` dir, so the app defaults there instead of making the
/// user paste a path. Searches the working dir and its ancestors for a `mom-content/questions`
/// (dev runs from the app dir or src-tauri, so an ancestor always holds it); "" if not found,
/// in which case the user supplies `mom_root` by hand as before.
#[tauri::command]
async fn mom_default_root() -> Result<String, String> {
    let mut dir = std::env::current_dir().map_err(|e| e.to_string())?;
    loop {
        for cand in [dir.join("mom-content"), dir.join("steve-desktop").join("mom-content")] {
            if cand.join("questions").is_dir() {
                return Ok(cand.to_string_lossy().to_string());
            }
        }
        match dir.parent() {
            Some(p) => dir = p.to_path_buf(),
            None => return Ok(String::new()),
        }
    }
}

#[tauri::command]
async fn mom_read_manifest(folder: String) -> Result<Option<String>, String> {
    let path = std::path::Path::new(&folder).join("manifest.json");
    match std::fs::read_to_string(&path) {
        Ok(text) => Ok(Some(text)),
        Err(e) if e.kind() == std::io::ErrorKind::NotFound => Ok(None),
        Err(e) => Err(format!("Failed to read {}: {}", path.display(), e)),
    }
}

/// Resolve family+slug against the walked index (the index is the source of truth — a
/// caller-supplied path is never trusted), then read the question's .php and its manifest.
#[tauri::command]
async fn mom_read_question(
    root: String,
    family: String,
    slug: String,
) -> Result<MomQuestionRead, String> {
    let families = mom_walk(std::path::Path::new(&root));
    let fam = families
        .iter()
        .find(|f| f.name == family)
        .ok_or_else(|| format!("Unknown family: {}", family))?;
    let question = fam
        .questions
        .iter()
        .find(|q| q.slug == slug)
        .ok_or_else(|| format!("Unknown question: {}/{}", family, slug))?;

    // question.path is the .php file itself now (walk collects files, not folders).
    let path = std::path::Path::new(&question.path);
    let contents = std::fs::read_to_string(path)
        .map_err(|e| format!("Failed to read {}: {}", path.display(), e))?;
    let manifest_text = path
        .parent()
        .and_then(|d| std::fs::read_to_string(d.join("manifest.json")).ok());

    Ok(MomQuestionRead {
        path: question.path.clone(),
        contents,
        manifest_text,
    })
}

/// Copy a template into `<draftsDir>/<family>/<slug>.php`. The source must resolve inside
/// `<momRoot>/questions` — a template path that escapes it is refused.
#[tauri::command]
async fn mom_create_draft(
    mom_root: String,
    drafts_dir: String,
    template_path: String,
    family: String,
    slug: String,
) -> Result<MomDraftResult, String> {
    if !mom_is_valid_slug(&slug) {
        return Err(format!("invalid slug: {}", slug));
    }

    let questions_dir = std::path::Path::new(&mom_root).join("questions");
    let candidate = std::path::Path::new(&template_path);
    let source = if candidate.is_absolute() {
        candidate.to_path_buf()
    } else {
        questions_dir.join(candidate)
    };

    // Resolve symlinks/.. before the containment check so the guard can't be walked around.
    let canon_questions = questions_dir
        .canonicalize()
        .map_err(|e| format!("questions dir unavailable: {}", e))?;
    let canon_source = source
        .canonicalize()
        .map_err(|_| format!("template not found: {}", template_path))?;
    if !canon_source.starts_with(&canon_questions) {
        return Err(format!(
            "template path escapes questions dir: {}",
            template_path
        ));
    }

    let draft_dir = std::path::Path::new(&drafts_dir).join(&family);
    std::fs::create_dir_all(&draft_dir)
        .map_err(|e| format!("Failed to create {}: {}", draft_dir.display(), e))?;
    let draft_path = draft_dir.join(format!("{}.php", slug));
    std::fs::copy(&canon_source, &draft_path)
        .map_err(|e| format!("Failed to copy template: {}", e))?;

    Ok(MomDraftResult {
        draft_path: draft_path.to_string_lossy().to_string(),
        family,
        slug,
    })
}

#[cfg(test)]
mod mom_tests {
    use super::*;

    fn fixture_root() -> std::path::PathBuf {
        // src-tauri/../src/integrations/mom/__tests__/fixtures/mom
        std::path::Path::new(env!("CARGO_MANIFEST_DIR"))
            .parent()
            .unwrap()
            .join("src/integrations/mom/__tests__/fixtures/mom")
    }

    #[test]
    fn junk_families_are_skipped() {
        assert!(mom_is_junk_family("nul"));
        assert!(mom_is_junk_family("NUL"));
        assert!(mom_is_junk_family("$APPDATA"));
        assert!(mom_is_junk_family("C:Usersshuff"));
        assert!(!mom_is_junk_family("frq"));
        assert!(!mom_is_junk_family("descriptive-statistics"));
    }

    #[test]
    fn slug_validation_matches_the_ts_rule() {
        assert!(mom_is_valid_slug("q1-test"));
        assert!(mom_is_valid_slug("a.b_c-1"));
        assert!(!mom_is_valid_slug("-leading"));
        assert!(!mom_is_valid_slug("has space"));
        assert!(!mom_is_valid_slug(""));
    }

    #[test]
    fn walks_php_files_into_families_flat_and_nested() {
        let families = mom_walk(&fixture_root());
        let by = |n: &str| families.iter().find(|f| f.name == n);

        // Nested family: frq/descriptive-statistics/*.php — slug carries the subtopic path.
        let frq = by("frq").expect("frq family");
        assert_eq!(frq.questions[0].slug, "descriptive-statistics/q1-test.php");
        assert!(frq.questions[0].has_manifest, "manifest sits beside the .php");

        // Flat family: each .php file directly under the topic is its own question.
        let stats = by("descriptive-stats").expect("descriptive-stats family");
        assert_eq!(stats.count, 2, "two .php files -> two questions, not one folder");
        assert_eq!(stats.questions[0].slug, "q1-mean.php");
        assert!(!stats.questions[0].has_manifest);
    }

    #[test]
    fn missing_questions_dir_yields_empty() {
        let families = mom_walk(std::path::Path::new("/no/such/mom/root"));
        assert!(families.is_empty());
    }

    #[test]
    fn loads_book_manifests() {
        let books_dir = fixture_root().join("books");
        let mut out = Vec::new();
        mom_collect_books(&books_dir, &books_dir, &mut out);
        assert_eq!(out.len(), 1, "one assignment manifest in the fixture");
        assert!(out[0].path.ends_with(".json"));
        assert!(out[0].text.contains("\"questions\""));
    }
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
    // Every embedded course tab is a separate native WebView2 window, and Chromium deprioritizes
    // one hard whenever it isn't the OS-focused window: video playback measured as slow as 1/174x
    // real time. Fixing this by forcing OS focus (SetForegroundWindow) doesn't work reliably —
    // Windows silently blocks a background process from stealing focus without a user gesture —
    // and even when it does work, it would yank the user's actual keyboard focus away from
    // whatever else they're doing every time an embedded video polls. Disabling the timer/occluded-
    // window throttles avoids both problems: playback stays real-time regardless of which window
    // has focus. Set once, before any webview (main or embedded) is created — WebView2 only reads
    // this env var at environment-creation time, so setting it later would miss the first one.
    //
    // Deliberately NOT included: --disable-renderer-backgrounding. It sounds like the same family
    // as the two flags below, but empirically it breaks child WebView2 controller creation outright
    // — a tab created with it present never becomes a CDP target and every later call into it hangs
    // with "failed to receive message from webview" (the same symptom the main-thread fix above
    // addresses, but this is a distinct cause: reproduced with run_on_main_thread intact, and
    // disappears the instant this one flag is dropped, isolated by testing each of the three flags
    // individually). The other two flags already cover JS timer throttling, which is the dominant
    // cause of the slow-video symptom; do not add this one back without re-verifying embedded tabs
    // still register as CDP targets.
    let mut browser_args = String::new();
    if let Some(port) = cdp_port {
        browser_args.push_str(&format!("--remote-debugging-port={} --remote-allow-origins=*", port));
        eprintln!("[steve] CDP enabled on port {} (dynamic allocation)", port);
    } else {
        eprintln!("[steve] CDP unavailable: all ports 9222-9242 are in use");
    }
    browser_args.push_str(" --disable-background-timer-throttling --disable-backgrounding-occluded-windows");
    std::env::set_var("WEBVIEW2_ADDITIONAL_BROWSER_ARGUMENTS", browser_args);

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
        Migration {
            version: 8,
            description: "create_run_journal",
            // Audit trail for skill replays against live sites: one row per run (per roster row
            // when parameterized). row_label may hold real student data — local DB only, never
            // model-bound or exported.
            sql: "CREATE TABLE IF NOT EXISTS run_journal (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    skill_id TEXT,
    skill_name TEXT NOT NULL,
    row_label TEXT,
    status TEXT NOT NULL,
    detail TEXT,
    created_at TEXT DEFAULT (datetime('now'))
);",
            kind: MigrationKind::Up,
        },
        Migration {
            version: 9,
            description: "fold_ogre_shapes_into_skills_and_site_profiles",
            // Bare ALTERs are safe here: the plugin records applied versions, so this runs
            // exactly once per DB (SQLite has no ADD COLUMN IF NOT EXISTS). Verify any change
            // against a POPULATED db — an empty table hides the ADD COLUMN default rule below.
            //
            // site_profiles is replaced outright rather than migrated — the old
            // (domain, page_name, profile_json) shape had no production reader, only test
            // fixtures. The crawler's on-disk profiles under ~/.agents/site-profiles/ are a
            // separate store (site-profiles.ts) and are untouched by this.
            sql: "ALTER TABLE skills ADD COLUMN source_id TEXT;
ALTER TABLE skills ADD COLUMN learned_corrections TEXT;
-- SQLite rejects a non-constant DEFAULT in ADD COLUMN, so `DEFAULT (datetime('now'))` here
-- aborted the whole migration: it rolled back, source_id never existed, and db.ts (which already
-- selects it) threw on every read — silently killing the entire Skills panel. Add the column
-- bare and backfill instead.
ALTER TABLE skills ADD COLUMN updated_at TEXT;
UPDATE skills SET updated_at = datetime('now') WHERE updated_at IS NULL;
CREATE UNIQUE INDEX IF NOT EXISTS idx_skills_source ON skills(source, source_id) WHERE source_id IS NOT NULL;
DROP TABLE IF EXISTS site_profiles;
CREATE TABLE site_profiles (
    id TEXT PRIMARY KEY NOT NULL,
    name TEXT NOT NULL,
    url_patterns TEXT NOT NULL,
    selectors TEXT NOT NULL,
    feedback TEXT NOT NULL,
    save TEXT NOT NULL,
    navigation TEXT NOT NULL,
    extraction TEXT DEFAULT NULL,
    created_at TEXT NOT NULL DEFAULT (datetime('now')),
    updated_at TEXT NOT NULL DEFAULT (datetime('now'))
);",
            kind: MigrationKind::Up,
        },
        Migration {
            version: 10,
            description: "ogre_grading_tables",
            // The ogre island's remaining tables. Its other six (provider_configs,
            // app_settings, oauth_tokens, site_credentials, site_profiles, skills) are
            // already steve.db tables — migration 9 widened the last two to ogre's shape.
            //
            // O.G.R.E's island_id column is dropped in the port: it defaulted to 'ogre' on
            // every row of ogre-only tables, and in the shared tables it would mislabel
            // steve's own rows. `skills.source` already separates rubrics from steve skills.
            //
            // student_response holds real student work — local DB only. Anything model-bound
            // goes through model-gate redaction first.
            sql: "CREATE TABLE IF NOT EXISTS grading_sessions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    provider_id TEXT,
    model TEXT,
    student_count INTEGER,
    mean_score REAL,
    min_score REAL,
    max_score REAL,
    median_score REAL,
    max_possible_score REAL,
    page_url TEXT,
    question_id TEXT,
    custom_instructions TEXT,
    created_at TEXT NOT NULL DEFAULT (datetime('now'))
);
CREATE TABLE IF NOT EXISTS response_embeddings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    session_id INTEGER REFERENCES grading_sessions(id),
    rubric_hash TEXT NOT NULL,
    student_response TEXT,
    score REAL NOT NULL,
    feedback TEXT,
    embedding BLOB NOT NULL,
    embedding_model TEXT NOT NULL,
    created_at TEXT DEFAULT (datetime('now'))
);
CREATE INDEX IF NOT EXISTS idx_embeddings_rubric_hash ON response_embeddings(rubric_hash);
CREATE INDEX IF NOT EXISTS idx_embeddings_model ON response_embeddings(embedding_model);
CREATE TABLE IF NOT EXISTS batch_session (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    url TEXT NOT NULL,
    last_student_name TEXT NOT NULL,
    timestamp TEXT NOT NULL DEFAULT (datetime('now'))
);
CREATE UNIQUE INDEX IF NOT EXISTS idx_batch_session_url ON batch_session(url);
INSERT OR IGNORE INTO app_settings (key, value) VALUES ('history_visible_columns', '[\"timestamp\",\"provider\",\"model\",\"studentCount\",\"meanScore\",\"pageUrl\"]');",
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
            mom_load_index,
            mom_load_books,
            mom_default_root,
            mom_create_book_file,
            mom_read_text,
            mom_write_text,
            mom_read_manifest,
            mom_read_question,
            mom_create_draft,
            mom_save_pasted_image,
            mom_write_book,
            inject_webview_script,
            scan_local_skills,
            start_oauth_callback_server,
            stop_oauth_callback_server,
            get_cdp_port,
            discover_cdp_target,
            cdp_list_targets,
            create_dir,
            write_file,
            read_file,
            resolve_path,
            delete_file,
            list_files,
            keyring_set,
            keyring_get,
            keyring_delete,
            run_agent_cli,
            stop_agent_cli,
            claude_cli_available,
            claude_auth_status,
            claude_login_start,
            claude_login_submit,
            claude_login_cancel,
            claude_auth_logout,
            opencode_save_key,
            opencode_has_credential,
            artifacts_dir,
            save_artifact,
            list_artifacts,
            delete_artifact,
            read_artifact,
            open_artifact,
            start_recording,
            stop_recording,
            page_mcp::start_page_tools,
            page_mcp::stop_page_tools,
            page_mcp::page_tool_result,
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
        .manage(Mutex::new(RecordingState { stop: None, handle: None }))
        .manage(AgentProcs::default())
        .manage(LoginProc::default())
        .manage(page_mcp::PageTools::default())
        .setup(|app| {
            // Ship the question-writing skill to where a spawned CLI can actually find it.
            install_mom_skill(app.handle());

            // Before any webview exists, so no video can be playing to lose. See the fn's note.
            prewarm_agent_engines();

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
