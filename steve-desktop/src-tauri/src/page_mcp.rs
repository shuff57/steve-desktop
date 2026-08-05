//! The page tools, served to the orchestrating CLI over a local HTTP MCP endpoint.
//!
//! The CLI used to drive the browser itself: it was handed the app's CDP port and read pages
//! directly over Bash, so student data landed in its context with no in-app seam to mask at.
//! Here it gets tools instead of a port. Every read goes through the app, which masks it, and the
//! CLI never learns the port at all — with no port there is no raw-read path to fall back to,
//! which is what makes the masking a boundary rather than a convention.
//!
//! Rust owns only the transport. Every handler lives in TypeScript (`src/lib/page-tool.ts`),
//! because that is where the run mask and the CDP client already are. A tool call arrives here,
//! is emitted to the webview as an event carrying a request id, and the frontend answers with the
//! `page_tool_result` command.
//!
//! Transport is MCP Streamable HTTP, minimal but to spec: POST returns a single
//! `application/json` body (permitted — SSE is the other option, not a requirement), a
//! notification gets `202 Accepted` with no body, and GET/DELETE get `405`. Bound to loopback
//! with a per-run bearer token, because anything on this machine could otherwise read the user's
//! gradebook through it.

use std::collections::HashMap;
use std::sync::atomic::{AtomicU64, Ordering};
use std::sync::Mutex;
use std::time::Duration;

use serde_json::{json, Value};
use tauri::{Emitter, Manager};
use tokio::io::{AsyncBufReadExt, AsyncReadExt, AsyncWriteExt, BufReader};
use tokio::sync::oneshot;

/// A tool call can be a whole agent loop on a slow local model, so this is generous. It exists
/// only so a wedged webview eventually releases the CLI instead of hanging it forever.
const CALL_TIMEOUT: Duration = Duration::from_secs(600);

/// A page observation is tens of KB; nothing legitimate approaches this.
const MAX_BODY: usize = 4 * 1024 * 1024;

/// Version we answer `initialize` with when the client asks for something we do not know.
const PROTOCOL_VERSION: &str = "2025-06-18";

#[derive(Default)]
pub struct PageTools {
    running: Mutex<Option<Running>>,
    pending: Mutex<HashMap<u64, oneshot::Sender<Result<String, String>>>>,
    seq: AtomicU64,
}

struct Running {
    shutdown: Option<oneshot::Sender<()>>,
}

#[derive(serde::Serialize, Clone)]
pub struct PageToolsEndpoint {
    port: u16,
    token: String,
}

/// The tools as the CLI sees them.
///
/// `page_task` is the cheap grain — one hop for a whole sub-task instead of one per click. It runs
/// on a local/OpenAI-compatible model, which can be down or simply weaker than the orchestrator,
/// so its reply says when to fall back and the primitives are always available.
fn tool_definitions() -> Value {
    json!([
        {
            "name": "page_read",
            "description":
                "Read the page the user is on, as numbered elements plus its text. Every element \
                 carries an index — [3]<button>Save</button> — and that index is how you act on it. \
                 Names and student ids come back as tokens like ⟦STU4⟧; pass a token straight back \
                 in another page tool and the app substitutes the real value locally. Call this \
                 after every action to see what changed; never assume an action worked.",
            "inputSchema": { "type": "object", "properties": {}, "additionalProperties": false }
        },
        {
            "name": "page_task",
            "description":
                "Hand one page sub-task to the in-app page agent, which works out the clicks itself \
                 and reports back. Use this for anything multi-step on a single page ('open the \
                 grades tab and find the lowest score'). Cheaper than driving it click by click. \
                 The reply always includes the page AFTER the work, so check the result rather than \
                 trusting the report. If the reply says the sub-task model is unavailable, nothing \
                 was attempted — do the work yourself with page_click / page_type / page_navigate.",
            "inputSchema": {
                "type": "object",
                "properties": {
                    "task": {
                        "type": "string",
                        "description": "One concrete sub-task on the current page, in plain words."
                    }
                },
                "required": ["task"],
                "additionalProperties": false
            }
        },
        {
            "name": "page_map",
            "description":
                "Fetch the site map for the site you are automating — what pages it has and what \
                 an agent can do on each. Ask with a `query` naming the thing you need (a page, \
                 an area, an action) and only the relevant slice comes back, so this stays cheap \
                 and exact. Prefer this over assuming a URL or re-discovering the site. No page \
                 needs to be open. Returns a plain message when the site has no map yet.",
            "inputSchema": {
                "type": "object",
                "properties": {
                    "query": {
                        "type": "string",
                        "description": "What you are looking for — a page, area or action. Optional."
                    }
                },
                "additionalProperties": false
            }
        },
        {
            "name": "page_click",
            "description":
                "Click the element with this index, from the most recent page_read. Also selects an \
                 <option> inside a dropdown. Returns what happened plus the page afterwards.",
            "inputSchema": {
                "type": "object",
                "properties": { "index": { "type": "integer", "description": "Element index from page_read." } },
                "required": ["index"],
                "additionalProperties": false
            }
        },
        {
            "name": "page_type",
            "description":
                "Type text into the input with this index, from the most recent page_read. A token \
                 like ⟦STU4⟧ in the text is replaced with the real value before it reaches the page.",
            "inputSchema": {
                "type": "object",
                "properties": {
                    "index": { "type": "integer", "description": "Element index from page_read." },
                    "text": { "type": "string" }
                },
                "required": ["index", "text"],
                "additionalProperties": false
            }
        },
        {
            "name": "page_navigate",
            "description":
                "Navigate the user's tab to a URL. A URL containing a token like ⟦PID7⟧ is restored \
                 to the real id before it is used, so you can follow a link you only saw masked. \
                 Refused if the URL leaves the course or site this run started in.",
            "inputSchema": {
                "type": "object",
                "properties": { "url": { "type": "string" } },
                "required": ["url"],
                "additionalProperties": false
            }
        },
        {
            "name": "page_screenshot",
            "description":
                "Take a picture of the page and save it to the app's Artifacts gallery. Returns the \
                 absolute path, which is what page_attach_file wants. Flashes the screen first so \
                 the user sees the moment their screen was captured.",
            "inputSchema": { "type": "object", "properties": {}, "additionalProperties": false }
        },
        {
            "name": "page_record",
            "description":
                "Record the tab you are driving to a video in the Artifacts gallery. Records only \
                 that tab, never the rest of the screen. Stop returns the .mp4 path. Only record \
                 when the task asks for it.",
            "inputSchema": {
                "type": "object",
                "properties": { "action": { "type": "string", "enum": ["start", "stop"] } },
                "required": ["action"],
                "additionalProperties": false
            }
        },
        {
            "name": "page_tabs",
            "description":
                "Open, switch between, close and sign into the app's browser tabs — for a task that \
                 spans more than one site. 'list' shows every tab and which are yours. 'login' \
                 submits credentials saved on this machine: you never see them, and it is the only \
                 sanctioned way to sign in — never type a password yourself. The other page tools \
                 always act on the ACTIVE tab, so activate the one you mean first.",
            "inputSchema": {
                "type": "object",
                "properties": {
                    "action": {
                        "type": "string",
                        "enum": ["list", "open", "activate", "navigate", "close", "login"]
                    },
                    "id": { "type": "string", "description": "Tab id, from action=list." },
                    "url": { "type": "string", "description": "For open and navigate." }
                },
                "required": ["action"],
                "additionalProperties": false
            }
        },
        {
            "name": "page_attach_file",
            "description":
                "Attach a file to a file input on the page — how a screenshot or recording gets onto \
                 an email. Give the element index of the file input (click the paperclip first if \
                 the page has not created one yet) and the absolute path from page_screenshot or \
                 page_record. Works the same for .png and .mp4. There is no OS file picker to drive.",
            "inputSchema": {
                "type": "object",
                "properties": {
                    "index": { "type": "integer", "description": "Element index of the file input." },
                    "path": { "type": "string", "description": "Absolute path of the file." }
                },
                "required": ["index", "path"],
                "additionalProperties": false
            }
        }
    ])
}

// ── Commands ───────────────────────────────────────────────────────────────

/// Bind the endpoint and start serving. Call this AFTER the webview is listening for
/// `page-tool-call`: nothing queues, so a call that arrives with no listener just waits out
/// CALL_TIMEOUT.
#[tauri::command]
pub async fn start_page_tools(app: tauri::AppHandle) -> Result<PageToolsEndpoint, String> {
    stop_page_tools(app.clone()).await?;

    let listener = tokio::net::TcpListener::bind("127.0.0.1:0")
        .await
        .map_err(|e| format!("Failed to bind the page-tool endpoint: {}", e))?;
    let port = listener
        .local_addr()
        .map_err(|e| format!("Failed to read the page-tool port: {}", e))?
        .port();
    let token = uuid::Uuid::new_v4().to_string();

    let (shutdown_tx, mut shutdown_rx) = oneshot::channel::<()>();
    {
        let state = app.state::<PageTools>();
        let mut guard = state.running.lock().map_err(|e| e.to_string())?;
        *guard = Some(Running { shutdown: Some(shutdown_tx) });
    }

    let serve_app = app.clone();
    let serve_token = token.clone();
    tauri::async_runtime::spawn(async move {
        loop {
            tokio::select! {
                accepted = listener.accept() => {
                    let Ok((stream, _)) = accepted else { continue };
                    let conn_app = serve_app.clone();
                    let conn_token = serve_token.clone();
                    tauri::async_runtime::spawn(async move {
                        let _ = serve_connection(stream, conn_app, conn_token).await;
                    });
                }
                _ = &mut shutdown_rx => break,
            }
        }
    });

    Ok(PageToolsEndpoint { port, token })
}

/// Stop serving and fail every call still in flight.
///
/// Failing them is the point: if the owning view unmounted mid-run, the answers are never coming,
/// and a CLI blocked on a tool it will never get an answer to looks exactly like a hung run.
#[tauri::command]
pub async fn stop_page_tools(app: tauri::AppHandle) -> Result<(), String> {
    let state = app.state::<PageTools>();
    let shutdown = {
        let mut guard = state.running.lock().map_err(|e| e.to_string())?;
        guard.take().and_then(|mut r| r.shutdown.take())
    };
    if let Some(tx) = shutdown {
        let _ = tx.send(());
    }
    let waiting: Vec<_> = {
        let mut guard = state.pending.lock().map_err(|e| e.to_string())?;
        guard.drain().map(|(_, tx)| tx).collect()
    };
    for tx in waiting {
        let _ = tx.send(Err("The app stopped serving page tools.".into()));
    }
    Ok(())
}

/// The frontend's answer to one emitted `page-tool-call`.
#[tauri::command]
pub fn page_tool_result(
    app: tauri::AppHandle,
    id: u64,
    ok: bool,
    output: String,
) -> Result<(), String> {
    let state = app.state::<PageTools>();
    let tx = {
        let mut guard = state.pending.lock().map_err(|e| e.to_string())?;
        guard.remove(&id)
    };
    // A late answer (the call already timed out) is not an error worth surfacing to the UI.
    if let Some(tx) = tx {
        let _ = tx.send(if ok { Ok(output) } else { Err(output) });
    }
    Ok(())
}

// ── Transport ──────────────────────────────────────────────────────────────

async fn serve_connection(
    stream: tokio::net::TcpStream,
    app: tauri::AppHandle,
    token: String,
) -> std::io::Result<()> {
    let (rd, mut wr) = stream.into_split();
    let mut rd = BufReader::new(rd);

    loop {
        let mut request_line = String::new();
        if rd.read_line(&mut request_line).await? == 0 {
            return Ok(()); // client hung up
        }
        let method = request_line.split_whitespace().next().unwrap_or("").to_string();

        let mut content_length = 0usize;
        let mut authorization = String::new();
        let mut origin: Option<String> = None;
        loop {
            let mut header = String::new();
            if rd.read_line(&mut header).await? == 0 {
                return Ok(());
            }
            let header = header.trim_end();
            if header.is_empty() {
                break;
            }
            if let Some((name, value)) = header.split_once(':') {
                let value = value.trim();
                match name.trim().to_ascii_lowercase().as_str() {
                    "content-length" => content_length = value.parse().unwrap_or(0),
                    "authorization" => authorization = value.to_string(),
                    "origin" => origin = Some(value.to_string()),
                    _ => {}
                }
            }
        }

        if content_length > MAX_BODY {
            respond(&mut wr, "413 Payload Too Large", None, "").await?;
            return Ok(());
        }
        let mut body = vec![0u8; content_length];
        if content_length > 0 {
            rd.read_exact(&mut body).await?;
        }

        // A cross-site page cannot set Authorization without a preflight, so the token is the real
        // control — but the spec asks for this and it is three lines against DNS rebinding.
        let bad_origin = origin
            .as_deref()
            .is_some_and(|o| !o.starts_with("http://127.0.0.1") && !o.starts_with("http://localhost"));
        if bad_origin {
            respond(&mut wr, "403 Forbidden", None, "").await?;
            return Ok(());
        }
        if authorization != format!("Bearer {}", token) {
            respond(&mut wr, "401 Unauthorized", None, "").await?;
            return Ok(());
        }

        match method.as_str() {
            "POST" => {
                let Ok(request) = serde_json::from_slice::<Value>(&body) else {
                    respond(&mut wr, "400 Bad Request", None, "").await?;
                    return Ok(());
                };
                // No id means a notification (or a response we did not ask for): acknowledge it and
                // send no body, per the Streamable HTTP transport.
                let Some(id) = request.get("id").cloned().filter(|v| !v.is_null()) else {
                    respond(&mut wr, "202 Accepted", None, "").await?;
                    continue;
                };
                let reply = dispatch(&app, &request, id).await;
                respond(
                    &mut wr,
                    "200 OK",
                    Some("application/json"),
                    &reply.to_string(),
                )
                .await?;
            }
            // We never push server-initiated messages, so there is no stream to open and nothing
            // to tear down. 405 is the spec's answer for both.
            "GET" | "DELETE" => respond(&mut wr, "405 Method Not Allowed", None, "").await?,
            _ => respond(&mut wr, "405 Method Not Allowed", None, "").await?,
        }
    }
}

async fn respond<W: AsyncWriteExt + Unpin>(
    wr: &mut W,
    status: &str,
    content_type: Option<&str>,
    body: &str,
) -> std::io::Result<()> {
    let mut head = format!("HTTP/1.1 {}\r\nContent-Length: {}\r\n", status, body.len());
    if let Some(ct) = content_type {
        head.push_str(&format!("Content-Type: {}\r\n", ct));
    }
    head.push_str("\r\n");
    wr.write_all(head.as_bytes()).await?;
    wr.write_all(body.as_bytes()).await?;
    wr.flush().await
}

async fn dispatch(app: &tauri::AppHandle, request: &Value, id: Value) -> Value {
    if let Some(reply) = dispatch_local(request, id.clone()) {
        return reply;
    }
    // The only method left is tools/call, which is the one that needs the webview.
    let params = request.get("params").cloned().unwrap_or_else(|| json!({}));
    let name = params.get("name").and_then(Value::as_str).unwrap_or("").to_string();
    let arguments = params.get("arguments").cloned().unwrap_or_else(|| json!({}));
    match call_tool(app, &name, arguments).await {
        Ok(text) => ok(id, tool_content(&text, false)),
        // Tool failures belong in the result, not in a JSON-RPC error: the model has to see
        // "that element does not exist" to correct itself.
        Err(why) => ok(id, tool_content(&why, true)),
    }
}

/// Everything answerable without touching the webview. `None` means "this is `tools/call`".
fn dispatch_local(request: &Value, id: Value) -> Option<Value> {
    let method = request.get("method").and_then(Value::as_str).unwrap_or("");
    match method {
        "initialize" => {
            // Echo the client's protocol version when it names one. Answering with our own instead
            // is the usual reason a server loads cleanly and then lists no tools — which looks
            // exactly like the server not being configured at all.
            let version = request
                .get("params")
                .and_then(|p| p.get("protocolVersion"))
                .and_then(Value::as_str)
                .unwrap_or(PROTOCOL_VERSION)
                .to_string();
            Some(ok(id, json!({
                "protocolVersion": version,
                "capabilities": { "tools": {} },
                "serverInfo": { "name": "steve-page", "version": env!("CARGO_PKG_VERSION") }
            })))
        }
        "ping" => Some(ok(id, json!({}))),
        "tools/list" => Some(ok(id, json!({ "tools": tool_definitions() }))),
        "tools/call" => None,
        _ => Some(json!({
            "jsonrpc": "2.0",
            "id": id,
            "error": { "code": -32601, "message": format!("Unknown method: {}", method) }
        })),
    }
}

fn ok(id: Value, result: Value) -> Value {
    json!({ "jsonrpc": "2.0", "id": id, "result": result })
}

fn tool_content(text: &str, is_error: bool) -> Value {
    json!({ "content": [{ "type": "text", "text": text }], "isError": is_error })
}

/// Hand the call to the webview and wait for its answer.
async fn call_tool(app: &tauri::AppHandle, name: &str, arguments: Value) -> Result<String, String> {
    let state = app.state::<PageTools>();
    let id = state.seq.fetch_add(1, Ordering::Relaxed);
    let (tx, rx) = oneshot::channel();
    {
        let mut guard = state.pending.lock().map_err(|e| e.to_string())?;
        guard.insert(id, tx);
    }

    if let Err(e) = app.emit(
        "page-tool-call",
        json!({ "id": id, "name": name, "arguments": arguments }),
    ) {
        if let Ok(mut guard) = state.pending.lock() {
            guard.remove(&id);
        }
        return Err(format!("Could not reach the app window: {}", e));
    }

    match tokio::time::timeout(CALL_TIMEOUT, rx).await {
        Ok(Ok(result)) => result,
        // The frontend dropped the responder — the view that owned this run is gone.
        Ok(Err(_)) => Err("The app stopped answering page tools; the run's view may have closed."
            .to_string()),
        Err(_) => {
            if let Ok(mut guard) = state.pending.lock() {
                guard.remove(&id);
            }
            Err(format!("Timed out after {}s waiting for the app.", CALL_TIMEOUT.as_secs()))
        }
    }
}

#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn every_tool_declares_a_schema_the_cli_can_call() {
        let tools = tool_definitions();
        let tools = tools.as_array().unwrap();
        let names: Vec<_> = tools
            .iter()
            .map(|t| t["name"].as_str().unwrap())
            .collect();
        assert_eq!(
            names,
            vec![
                "page_read",
                "page_task",
                "page_map",
                "page_click",
                "page_type",
                "page_navigate",
                "page_screenshot",
                "page_record",
                "page_tabs",
                "page_attach_file"
            ]
        );
        for tool in tools {
            assert!(!tool["description"].as_str().unwrap().is_empty());
            assert_eq!(tool["inputSchema"]["type"], "object");
        }
    }

    #[test]
    fn initialize_echoes_the_clients_protocol_version() {
        // Answering with our own version instead is the usual reason a server loads but lists no
        // tools — indistinguishable from the server not being configured at all.
        let reply = dispatch_local(
            &json!({
                "jsonrpc": "2.0", "id": 1, "method": "initialize",
                "params": { "protocolVersion": "2025-03-26" }
            }),
            json!(1),
        )
        .unwrap();
        assert_eq!(reply["result"]["protocolVersion"], "2025-03-26");
        assert!(reply["result"]["capabilities"]["tools"].is_object());
        assert_eq!(reply["id"], 1);
    }

    #[test]
    fn a_client_that_names_no_version_still_gets_one() {
        let reply = dispatch_local(&json!({"method": "initialize"}), json!(1)).unwrap();
        assert_eq!(reply["result"]["protocolVersion"], PROTOCOL_VERSION);
    }

    #[test]
    fn tools_list_is_answered_here_but_tools_call_is_not() {
        let listed = dispatch_local(&json!({"method": "tools/list"}), json!(2)).unwrap();
        assert_eq!(listed["result"]["tools"].as_array().unwrap().len(), 10);
        // tools/call is the one method that has to reach the webview.
        assert!(dispatch_local(&json!({"method": "tools/call"}), json!(3)).is_none());
    }

    #[test]
    fn an_unknown_method_is_a_jsonrpc_error() {
        let reply = dispatch_local(&json!({"method": "resources/list"}), json!(4)).unwrap();
        assert_eq!(reply["error"]["code"], -32601);
    }

    #[test]
    fn a_tool_failure_is_a_result_not_a_transport_error() {
        let content = tool_content("no element [9] on this page", true);
        assert_eq!(content["isError"], true);
        assert_eq!(content["content"][0]["text"], "no element [9] on this page");
    }
}
