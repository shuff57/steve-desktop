# S.T.E.V.E — Design

The durable design context for this app. Source of truth for identity; the *values*
live in `src/app.css` (`:root` = light, `[data-theme="dark"]` = dark). Keep the two
in sync — this doc explains the *why*, `app.css` holds the *what*.

## Identity

**S.T.E.V.E — Sitting Through Every Video Entirely.** A desktop agent that watches
mandatory training videos start-to-finish so you don't have to. The product's whole
pitch is **transparent, observable, interruptible** — you can watch it work and grab
the wheel at any moment.

The design serves that pitch. Direction: **night-shift operator console.** A calm,
focused monitor you leave running in the corner — patient, unblinking, a little wry.
Not a generic SaaS dashboard. One confident warm accent against deep ink; everything
legible at a glance from across the room.

- **Default theme is dark** ("Technical & Precise"). Light ("Playful & Friendly") is
  the alternate. Both are first-class; the identity reads in either.
- **Tone of voice:** deadpan diligence. Second person, plain, faintly dry. "S.T.E.V.E is
  watching so you don't have to." Never breathless.

## Palette

The accent is a **warm brass-gold** — the glow of the screen STEVE never looks away
from. It's the one non-neutral in the UI; keep it to interactive/active elements
(primary buttons, focus rings, active nav, "watching" state), never as decoration.

Because gold is light, the accent is theme-split: **deep bronze** in light mode (so it
survives as text/link on paper), **bright gold** on dark. All pairs below are measured
≥4.5:1 (WCAG AA):

| Role | Light | Dark | Note |
|---|---|---|---|
| Canvas (`--bg-primary`) | `#faf8f3` warm paper | `#14121c` indigo-ink | never pure `#fff` / `#000` (halation) |
| Raised surface (`--bg-card`/`--bg-panel`) | `#fffdf8` | `#1a1728` | cards/panels |
| Sidebar (`--bg-sidebar`) | `#1a1626` | `#100e18` | dark in **both** themes — its own light-on-dark text tokens |
| Body ink (`--text-primary`) | `#241f16` (15.4:1) | `#ece7d9` (15.0:1) | warm ink, not cold slate |
| Secondary (`--text-secondary`) | `#5a5240` (7.3:1) | `#b3ab97` (8.1:1) | |
| **Accent** (`--color-primary`) | `#7a5f17` bronze (5.7:1) | `#e6b84d` gold (10.0:1) | works as text/link |
| Accent label (`--color-primary-text`) | `#ffffff` (6.0:1) | `#14121c` (10.0:1) | text drawn on the accent |

Status hues (`success`/`warning`/`running`/`danger`/`accent`) stay as-is — saturated,
translucent tints that read on either ground. Note `warning` (amber) sits near the
brand gold; keep them apart in layout — brand gold = "active/primary", amber = "caution".

## Type

**System fonts only — no bundled webfonts.** The app must render on any Chromium build
without shipping font files, so type uses the CSS generic keywords that resolve to each
OS's native UI face:

- **Sans (`--font-sans`): `ui-sans-serif, system-ui, sans-serif`** — Segoe UI on Windows,
  SF Pro on macOS, Roboto/system on ChromeOS/Linux. Native, fast, zero payload.
- **Mono (`--font-mono`): `ui-monospace, 'Cascadia Code', Consolas, 'SF Mono', monospace`**
  — for the agent log, timers, and instrument-panel readouts. Pair with
  `font-variant-numeric: tabular-nums` on counters so digits don't jitter as they tick.

The console character comes from color, layout, and motion — not a signature typeface.
Do **not** add `@fontsource`/webfont packages; portability is the deliberate constraint here.

## Motion

STEVE's UX is literally emilkowalski's thesis — **observable and interruptible**. Motion
rules live in the vendored global skills; follow them here:

- **`apple-design`** — springs over fixed-duration transitions for anything the user can
  interrupt (pausing STEVE mid-video, grabbing a progress control); animate from the
  *current* value, never the target.
- **`review-animations`** (+ `STANDARDS.md`) — the craft bar. Non-negotiables that apply
  here: **UI motion <300ms**, `transform`/`opacity` only, `ease-out` on ent/exit
  (`ease-in` on UI is a block), honor `prefers-reduced-motion`, and **no animation on
  keyboard-triggered or 100+/day actions** (start/pause/skip fire constantly — keep them
  instant). Restraint first: a monitor you watch all day should be calm.
- **`animation-vocabulary`** — when you need the precise word for a motion before building it.

Run `/review-animations` over any motion diff before merging.

## Hard rules

- Never pure `#ffffff` background or pure `#000000` — use the warm/ink ramps above.
- One accent only (brass-gold). No second brand color; status hues are functional, not decorative.
- Accent theme-splits (bronze light / gold dark) — don't use one value for both; it fails contrast somewhere.
- Sidebar is dark in both themes; it uses the dedicated `--sidebar-*` tokens, not the canonical text tokens.
- Keep token **names** stable — components reference them. Reskinning = value swap only (mirrors bookSHelf's theme-from-website discipline).
- All accent/text pairs must clear 4.5:1. Re-run the check in `app.css` history if you retune.

## Known cleanups (pre-existing, out of this pass's scope)

- `.btn` and `body` use `transition: all` — `review-animations` flags this; narrow to
  explicit properties (`transform`, `background-color`).
- `input:focus` uses `outline: none` — acceptable here because a `box-shadow` focus ring
  replaces it, but confirm `:focus-visible` semantics if keyboard-vs-click focus matters.
