---
name: eyes
description: Screenshot a page, a local HTML file, or a rendered MOM question as tiled JPEGs and read them visually. Use when a check says "healthy" but you need to see what a human sees — MathJax typesetting, tables, duplicated prompts, layout — or when a page is longer than one viewport.
---

# Eyes — looking at a page instead of parsing it

`Page.captureScreenshot` (`cdp-actions.ts:165`) is **viewport-only**: anything below the fold
is not in the image, and a full-page grab of a long page downscales past readability. `pixelshot`
renders the whole page and slices it into tiles sized for a vision model.

This is an **eyes** tool, not a mapping tool. It returns pixels — no DOM, no links, no selectors.
It does not replace `site-map.ts`, `fingerprint.ts` or any capture path; `tiles.json` is
`{url, page_height, tiles[], complete}` and nothing else.

Borrowed from [PixelRAG](https://github.com/StarTrail-org/PixelRAG)'s `pixelbrowse` skill. Only the
renderer is used. The embedding/index half (torch + a 2B VL model) is not installed and is not wanted.

## Install

```bash
uv tool install pixelrag     # 26 packages, no torch, ~5s; installs `pixelshot` on PATH
```

Render-only deps (`cef-capi-py`, `pillow`, `pyturbojpeg`, `pymupdf`) all have Windows wheels — it
ships its own Chromium, so no Chrome install and no Playwright.

## The flags that matter

```
--tile-height 1568     ALWAYS. Claude downscales images with a long edge over 1568 (2576 on Opus);
                       the 8192 default comes back as unreadable mush.
--wait-network-idle    ALWAYS for URLs. Without it JS/MathJax/SPA pages capture blank or half-drawn.
--viewport-width 1280  desktop layout. Default 875 is article/mobile width.
--cdp-url <url>        attach to an already-running browser and use ITS cookies (see below).
```

## Reading a live page (authenticated)

Attach to the app's own WebView2 so the page renders inside Steve's real session. Measured: it
creates its own target, renders, closes it — target count goes 1 → 1, the app UI is never navigated.

```bash
PORT=$(curl -s http://127.0.0.1:9223/json/version >/dev/null && echo 9223)   # or read it from the tauri log
pixelshot "<url>" -o ./px --tile-height 1568 --viewport-width 1280 --wait-network-idle \
  --cdp-url http://127.0.0.1:$PORT
```

Then `Read` the tile at `./px/<sanitized-url>.png.tiles/tile_0000.jpg` (`tile_0001.jpg` … going down).

- ~3s attached, ~10s cold when it launches its own browser.
- A dead session is instantly obvious — the login box is right there in the tile. Faster than
  `asksForAPassword`, and it needs no capture.
- Verify the port really is ours before attaching: `/json/version` must report **`Edg/`**.
  Same rule as browser-harness — a wrong endpoint is a wrong browser.

## Reading a local HTML file — Windows trap

pixelshot flattens the input's **whole absolute path** into the output directory name, so a normal
scratchpad path blows past `MAX_PATH` and fails with `[WinError 3] The system cannot find the path
specified`. Render from a short directory:

```bash
mkdir -p /c/pxt && cp <file>.html /c/pxt/q.html
pixelshot "C:\pxt\q.html" -o "C:\pxt\out" --tile-height 1568 --viewport-width 900 --wait-network-idle
```

## Looking at a MOM question, end to end

The reason this skill exists. `questionHealth` reads the sandbox's own diagnostics out of the HTML —
it cannot see typesetting, and a question that reads fine as text can render wrong to a student.
Render through the app's real pipeline, then look:

```bash
curl -s -X POST https://mom.huffpalmer.fyi/ -H "Content-Type: text/plain" \
  --data-binary "@<question>.php" -o /c/pxt/raw.html
```

```ts
// scratchpad ab.ts — run with `bun <abs-path-to>/ab.ts <src.php> <raw.html> <out.html>`
// Imports are ABSOLUTE on purpose: a scratchpad script resolves relative imports from its own
// location, not the shell's cwd, so './src/...' does not exist from there.
import { readFileSync, writeFileSync } from 'fs';
import { prepareRenderHtml } from 'C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/src/integrations/mom/render-html.ts';
import { questionHealth } from 'C:/Users/shuff/Documents/GitHub/steve-desktop/steve-desktop/src/integrations/mom/health.ts';
const src = readFileSync(process.argv[2], 'utf8'), raw = readFileSync(process.argv[3], 'utf8');
writeFileSync(process.argv[4], prepareRenderHtml(raw, false));   // what the preview iframe shows
console.log(JSON.stringify(questionHealth(src, raw)));
```

Tile the prepared HTML, not the raw sandbox output — raw is missing `fixMathDelimiters`, so you will
"find" the `$`-delimiter bug the app already repairs and chase a fix that exists.

**Proven on `annuity/loan-amortization-payment.php` (2026-07-31):** `questionHealth` returned
`{errors: [], warnings: []}` while the tile showed `Monthly payment = $ Monthly payment = $[box]` —
the prompt printed twice, on both parts. Cause: the question writes `<p>$ansprompt[0] $answerbox[0]</p>`
and IMathAS *also* emits the prompt with the answerbox. **7 of 421 bank questions match that pattern**
(`grep -rlE '\$ansprompt\[[0-9]+\][^\n]*\$answerbox\[[0-9]+\]' --include=*.php`).

The same run was also the first visual confirmation that `prepareRenderHtml` works: raw HTML typesets
the second prompt as italic math (`Monthlypayment`), prepared HTML does not. That fix had only ever
been tested against strings.

## PII — hard rule

**Never tile a roster, a gradebook, a submission list, or any page carrying student names.**

Every PII guard in this repo is text-shaped — `⟦D1⟧` tokens, `dataFree`, projecting fields in-page so
only counts cross CDP. A tile is **pixels**: redaction cannot touch it, and a JPEG of a gradebook on
disk is a criterion-4 failure with no cleanup path. Question content, sandbox renders, course
structure, public pages: fine. People: no.

Delete tiles when done — they are throwaway.
