---
name: open-period
description: "Given a class period number, launch S.T.E.V.E if needed, open a MOM tab and an Aeries tab, log in with the app's own saved credentials, and land each tab on that period's course/gradebook. Use on '/open-period <n>', 'open period 3', 'pull up period 6's gradebooks'."
license: MIT
---

# open-period

One command: give it a period number, get back a MOM tab and an Aeries tab, both
logged in, both already on the right course/gradebook for that period. No
credentials ever pass through the conversation — the app's own saved logins do
the work (`site_credentials`, secrets in the OS keychain).

## Steps

### 1. Ensure the app is running, CDP reachable

```bash
curl -s http://127.0.0.1:9222/json/version   # or scan 9223..9242 if this fails
```

If nothing answers on any port 9222-9242, launch it (see `verify` skill for the
full launch procedure):

```bash
cd steve-desktop
bun run tauri:dev > /tmp/tauri.log 2>&1 &
until grep -aqE "CDP enabled on port|error(\[E[0-9]+\])?:" /tmp/tauri.log; do sleep 3; done
grep -ao "CDP enabled on port [0-9]*" /tmp/tauri.log
```

`Port 5174 is already in use` means a stale process from a previous session is
still holding it — find and kill it, then relaunch:

```bash
netstat -ano | grep ":5174" | grep LISTENING   # last column is the PID
# PowerShell: Stop-Process -Id <pid> -Force
```

### 2. Restore the window if minimized — REQUIRED, not optional

**A window launched headless from a background shell starts minimized.**
Confirmed live 2026-09-04: the Rust backend refuses to create any embedded
browser tab while minimized (`src-tauri/src/lib.rs` — `window.is_minimized()`
guard, "nowhere to render"), so step 3 fails outright until this is done.

Two traps found the hard way, both required to fix it right:

- `window.__TAURI__.window.getCurrentWindow().unminimize()` from inside the
  page is **blocked by capabilities** (`core:window:allow-unminimize` not
  granted) — don't bother trying it via CDP eval.
- A raw Win32 `ShowWindow` on `Get-Process -Id <pid> | MainWindowHandle` looks
  like it works (`IsIconic` reports false) but **does nothing** — that handle
  is the wrong window. The real main window is a *different* top-level HWND
  for the same PID, titled `S.T.E.V.E - Smart Task Execution & Verification
  Engine`. Enumerate all windows for the PID and restore the one with that
  title:

```powershell
Add-Type -TypeDefinition '
using System; using System.Text; using System.Collections.Generic; using System.Runtime.InteropServices;
public class WinEnum {
  public delegate bool EnumWindowsProc(IntPtr hWnd, IntPtr lParam);
  [DllImport("user32.dll")] public static extern bool EnumWindows(EnumWindowsProc lpEnumFunc, IntPtr lParam);
  [DllImport("user32.dll")] public static extern int GetWindowThreadProcessId(IntPtr hWnd, out int lpdwProcessId);
  [DllImport("user32.dll")] public static extern int GetWindowText(IntPtr hWnd, StringBuilder text, int count);
  [DllImport("user32.dll")] public static extern bool ShowWindow(IntPtr hWnd, int nCmdShow);
  [DllImport("user32.dll")] public static extern bool SetForegroundWindow(IntPtr hWnd);
  public static List<IntPtr> GetWindowsForPid(int pid) {
    var result = new List<IntPtr>();
    EnumWindows((hWnd, lParam) => { int wpid; GetWindowThreadProcessId(hWnd, out wpid); if (wpid == pid) result.Add(hWnd); return true; }, IntPtr.Zero);
    return result;
  }
}'
$pid = (Get-Process steve-desktop).Id
foreach ($h in [WinEnum]::GetWindowsForPid($pid)) {
  $sb = New-Object System.Text.StringBuilder 256
  [WinEnum]::GetWindowText($h, $sb, 256) | Out-Null
  if ($sb.ToString() -like 'S.T.E.V.E*') { [WinEnum]::ShowWindow($h, 9) | Out-Null; [WinEnum]::SetForegroundWindow($h) | Out-Null }
}
```

Skip this entirely if the app was already running from a normal (non-headless)
launch — check first with the CDP-targets probe below before assuming it's
needed.

### 3. Run the script

```bash
cd steve-desktop
node scripts/open-period.mjs <period>              # e.g. `node scripts/open-period.mjs 3`
node scripts/open-period.mjs <period> --port 9223   # if CDP wasn't on 9222
node scripts/open-period.mjs <period> --refresh     # force re-scrape both sites
```

It reuses an already-open MOM/Aeries tab if one exists (re-navigating it),
otherwise opens a fresh one. Login goes through the app's own
`window.__steveControl.login(tabId)` — same on-device path as clicking
"🔑 Log in now" by hand, so the password never leaves the app process, let
alone reaches this conversation.

Report back exactly what it prints: the matched course/gradebook name and id
for each side, or "no course/gradebook with a '<n>' prefix" if that period
doesn't exist on one platform (real and expected — e.g. period 5 is a CS
course with no MOM presence in this school's schedule).

## How period matching works

Both MOM and Aeries put the period number as a leading token on the course
name — MOM: `"3 Intro to Stats - 2627"`; Aeries: the row text around the
gradebook link starts `"3 Year..."`. The script regexes `^(\d+)\s` out of
each and keys a period→{cid|gradebook} map by it, cached to
`scripts/_profile/period-map.json` (gitignored — see the existing
`scripts/_profile/` rule; same category as `site-profile.mjs`'s cache:
scraped live-site content, regenerable, never meant to ship).

A period missing from the cache triggers a fresh scrape of that one side
automatically — you don't need `--refresh` unless the courses changed
mid-cache (a schedule change, a section added).

## Guardrails

- Never ask the user for raw Aeries/MOM credentials for this flow — the saved
  logins already cover it. If a saved credential is missing or wrong (empty
  password, bad username), send the user to the app's own Settings →
  Credentials screen, not to a chat message.
- This only *opens and navigates* — it makes no writes to either gradebook.
  Anything from here that DOES write (creating assignments, entering grades)
  is a separate, explicitly-confirmed step.
- If step 3 throws "never registered as a CDP target", the window-restore in
  step 2 was skipped or didn't take — re-check it before anything else.
