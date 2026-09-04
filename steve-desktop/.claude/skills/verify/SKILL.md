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

- **A headless/background launch starts the window minimized**, and the Rust
  backend refuses to create any embedded browser tab while minimized
  (`window.is_minimized()` guard, "nowhere to render"). Fix is NOT
  `window.__TAURI__.window.getCurrentWindow().unminimize()` from the page
  (blocked by capabilities — `core:window:allow-unminimize` not granted), and
  NOT a raw `ShowWindow` on `Get-Process -Id <pid> | MainWindowHandle` (that
  handle is a different, wrong top-level window — it reports restored but
  nothing changes). The real fix: enumerate every top-level window for the
  PID and restore the one titled `S.T.E.V.E - Smart Task Execution &
  Verification Engine` — full PowerShell snippet in
  `.claude/skills/open-period/SKILL.md` step 2. Confirmed live 2026-09-04.
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
- **The agent runs via `run_agent_cli`** (`src-tauri/src/lib.rs`), a Tauri
  command that spawns the `claude`/`opencode` CLI through Rust — no server, no
  mock. To smoke-test the agent path, watch for it to spawn (or invoke
  `run_agent_cli` directly with `engine`/`prompt`) rather than standing up
  anything on a port.
- **Never let the owning `.svelte` component unmount while a `run_agent_cli`
  run is in flight — a source edit is only one way to do it.** Vite HMR is one
  cause; switching views/tabs unmounts the component exactly the same way and
  orphans the run just as silently. The Rust side keeps running and its result
  still lands, but into the destroyed instance's state — the report modal
  never shows. Stay on the view that owns the run (and make zero source
  edits) until the result has rendered and been dismissed; queue fixes and
  view switches for after.
- **`timeoutSecs` does not reliably fire.** A run has been observed stuck past
  its configured timeout (`timeoutSecs: 900` still running at 28 minutes, no
  timeout error). Don't treat a configured timeout as a ceiling on how long to
  wait — poll for actual completion instead (see next bullet).
- **Don't infer "is the run still alive" from a DOM element's presence.** A
  collapsed or hidden panel reads as unmounted even when the run backing it is
  fine, producing a false abandoned-run alarm. Reliable liveness signals: the
  CLI process is still running, or the run's manifest file on disk is still
  changing. Check one of those before concluding a run died.
- **Don't re-diagnose a live readout you've already gotten wrong.** A UI
  counter (e.g. a context/token readout) got three different confident
  explanations across two sessions before the honest one ("don't know,
  unresolved") arrived. If a number has already been explained wrong once on
  unchanged evidence, the correct move on the next look is to say so and
  gather new evidence — not offer another guess.

## Driving the agent against a page

Serve a fixture over HTTP (`file:` is blocked in some tools) and point the
embedded browser at it via the Browse tab. For assessment-shaped DOM, mirror
`~/.claude/skills/steve/sc.py`: options are bare `<label class="question_btn">`
clicked directly, Submit Answer disabled until a selection.

Always target a **non-first** option. Historically every option serialised to the
same CSS selector, so clicking the first one is the failure mode — a test that
picks option A cannot tell success from the bug.
