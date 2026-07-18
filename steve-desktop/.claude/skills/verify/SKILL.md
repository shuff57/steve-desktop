---
name: verify
description: Build, launch and drive the S.T.E.V.E Tauri desktop app to observe a change actually running. Use when verifying any change to the Svelte frontend, the agent loop, or the embedded browser.
---

# Verifying S.T.E.V.E Desktop

Tauri 2 + Svelte 5 + bun, Windows/WebView2. The app is a native window, so you
drive it over **CDP**, not Playwright's own browser.

## Launch

```bash
cd steve-desktop
bun install                       # deps are not committed
bun run tauri:dev > /tmp/tauri.log 2>&1 &
```

First build is a cold cargo compile (~250 crates, several minutes). Wait on the
signal rather than polling blindly — it prints its own CDP port:

```bash
until grep -aqE "CDP enabled on port|error(\[E[0-9]+\])?:" /tmp/tauri.log; do sleep 5; done
grep -ao "CDP enabled on port [0-9]*" /tmp/tauri.log
```

`lib.rs` scans 9222-9242 and takes the first free port, so **the port changes
between runs** — always read it from the log. Restarting? `taskkill //F //IM
steve-desktop.exe //T` first; the tauri watcher does not always reclaim it.

## Drive it

`scratchpad/cdp.js` in this session is a ~50-line CDP driver:
`bun cdp.js <port> <urlSubstring> '<js expression>'`. Rebuild it if gone —
`GET /json/list` for targets, then `Runtime.evaluate` over the
`webSocketDebuggerUrl`. The main UI target is `http://localhost:5174/`.
Click things by dispatching real clicks; Svelte 5 binds pick them up:

```js
[...document.querySelectorAll('button')].find(b => /Save & Start/.test(b.innerText)).click()
```

`withGlobalTauri: true` is set, so `window.__TAURI__.core.invoke` is available
for talking to plugins directly. Useful for reading the DB:

```js
await invoke('plugin:sql|load', {db: 'sqlite:steve.db'});
await invoke('plugin:sql|select', {db: 'sqlite:steve.db', query: 'SELECT * FROM app_settings', values: []});
```

Note there is no `window.__TAURI__.sql` — go through `core.invoke`.

## Gotchas that cost time

- **Setup gate.** `App.svelte` blocks everything behind
  `getSetting('setup_complete') === 'true'`. Complete the wizard through the UI
  (pick Ollama — local, needs no credentials, and the agent ignores the provider
  config anyway). Never type real credentials into it.
- **DB writes need `sql:allow-execute`** in `src-tauri/capabilities/default.json`.
  `sql:default` grants load/select/close only. Without it every write silently
  fails and setup can never persist.
- **`SetupWizard.saveAndComplete` swallows all errors** and calls `oncomplete()`
  regardless, so the wizard reports success even when it saved nothing. Do not
  trust the UI advancing — check `app_settings` / `provider_configs` in the DB.
- **The agent needs a server on `localhost:3456`** (`agent-api.ts:9`,
  hardcoded). Nothing in this repo provides it. To drive the agent loop, stand up
  a mock that accepts `POST /api/agent` and returns
  `{"action":"click","params":{"ref":"e12"},"reasoning":"..."}`. The request body
  carries the `dom` field — the snapshot exactly as the model sees it, which is
  the best evidence available for capture changes.

## Driving the agent against a page

Serve a fixture over HTTP (`file:` is blocked in some tools) and point the
embedded browser at it via the Browse tab. For assessment-shaped DOM, mirror
`~/.claude/skills/steve/sc.py`: options are bare `<label class="question_btn">`
clicked directly, Submit Answer disabled until a selection.

Always target a **non-first** option. Historically every option serialised to the
same CSS selector, so clicking the first one is the failure mode — a test that
picks option A cannot tell success from the bug.
