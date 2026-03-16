# Decisions — steve-desktop

## Task 12
- Decided to use the constrained export surface from task spec exactly (no `runDiscovery` in this retry scope).
- Decided to keep discovery parsing resilient only to markdown code fences and invalid JSON fallback (`confidence: low`).

## Task 18
- Chose to preserve OGRE embedded webview command surface (`create_embedded_browser`, navigation/bounds/injection/url/show-hide/destroy, CDP/OAuth helpers) to maintain frontend IPC compatibility.
- Chose to remove `tauri-plugin-single-instance` and `tauri-plugin-updater` to match no-sidecar/no-updater requirement and keep dependency surface minimal.


## Task 18
- Chose to preserve OGRE embedded webview command surface (, navigation/bounds/injection/url/show-hide/destroy, CDP/OAuth helpers) to maintain frontend IPC compatibility.
- Chose to remove  and  to match no-sidecar/no-updater requirement and keep dependency surface minimal.

## Task 31
- Chose to wire `App.svelte` routing to concrete pages (`Dashboard`, `Browser`, `Skills`, `Settings`) and remove placeholder rendering for browser/skills routes.
- Chose to integrate `AgentChat` into `ActionPanel` and keep Discovery tab as "coming soon" placeholder because current discovery module export surface is incomplete and would require out-of-scope refactor.
- Chose to add compatibility exports in `agent-api.ts` and `skills-api.ts` to satisfy existing page imports while keeping behavior minimal and build-safe.
