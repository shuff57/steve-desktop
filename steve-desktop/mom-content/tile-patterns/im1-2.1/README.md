# IM1 2.1 — tile patterns as SVG

Clean vector rebuilds of the tile-pattern figures in **2.1 Growth in Linear Functions HW**
(`cid=334437`, `aid=23995519`; manifest `books/integrated-math-1/hw/2-1-1-growth-in-linear-functions-hw.json`).

The homework ships its figures as hand-drawn **raster screenshots** (`files.myopenmath.com/qimages/…`,
one per question — they are *not* Asymptote or `PLdraw`, so there was no drawing code to lift).
These SVGs replace them: pure vector, one shared tile pitch, no page-background tint.

| File | Slot | QID | Tiles in Figs 1–3 | Equation |
|------|-----:|-----|--------------------|----------|
| `im1-2.1-tiles-slot02-qid342755.svg` | 2 | 342755 | 6 (Fig. 2 only) | `y = mx + (-2m + 6)` |
| `im1-2.1-tiles-slot04-qid342861.svg` | 4 | 342861 | 7, 9, 11 | `y = 2x + d`, `d = 7 − 2a` |
| `im1-2.1-tiles-slot05-qid342944.svg` | 5 | 342944 | 6, 10, 14 | `y = 4x + 2` |
| `im1-2.1-tiles-slot06-qid342945.svg` | 6 | 342945 | 3, 5, 7 | `y = 2x + 1` |
| `im1-2.1-tiles-slot07-qid342946.svg` | 7 | 342946 | 5, 9, 13 | `y = 4x + 1` |
| `im1-2.1-tiles-slot08-qid342947.svg` | 8 | 342947 | 6, 10, 14 | `y = 4x + 2` |
| `im1-2.1-tiles-slot10-qid343676.svg` | 10 | 343676 | 5, 7, 9 | `y = 2x + 3` |
| `im1-2.1-tiles-slot11-qid344390.svg` | 11 | 344390 | 6, 9, 12 | `y = 3x + 3` |

## Why these are trustworthy

Every tile count above is the question's own **answer key evaluated at x = 1, 2, 3**, not a
reading of the picture. `extract-grid.py` fails its self-test if any extracted figure count
disagrees with that key, so a figure that drifts from the answer it is supposed to illustrate
cannot pass silently.

## Rebuilding

```bash
python3 extract-grid.py --dump     # tiles/*.png -> geom.json, prints SELFTEST PASS/FAIL
python3 render-svg.py svg          # geom.json -> one SVG per question
```

Needs the source rasters in `tiles/<qid>.png` (see below), then:

Both resolve paths relative to this directory, so no `/tmp` path is baked in; override with
`TILES=…` / `GEOM=…`. Neither writes to MyOpenMath — the pipeline is offline. Re-running both from
the rasters reproduces the eight SVGs above **byte-for-byte**.

The rasters are not committed. To refetch: read each question's source from
`course/moddataset.php?id=<qid>&cid=334437`, then take the image behind the `$tile` variable from
`course/testquestion2.php?cid=334437&qsetid=<qid>` — except slot 2, whose figure is an inline
`<img>` in the question text rather than an image field.



## Swapping these into MyOpenMath — BLOCKED ON PERMISSIONS

`dropin-png/` holds a ready-to-upload replacement for each question's `$tile` image, built by
`make-dropin-png.py`. It is deliberately a *different* renderer from `render-svg.py`: MyOpenMath stamps the
captions itself, and every question calls `textonimage($tile, "Fig. $a", 30,110, ...)` with pixel
offsets tuned to the ORIGINAL raster. So a drop-in must be exactly the source width x height, put each
figure's tiles at their ORIGINAL pixel coordinates, and contain NO caption text — otherwise every
"Fig. n" lands off its figure.

Verified two ways:

- **Composite** — original raster and drop-in were each rendered behind the *same* `textonimage`
  caption spans at their real offsets, and the captions sit under the same figures in both.
- **Geometry A/B** — running `extract-grid.py` over the originals and over the drop-ins yields
  **identical cell maps** for all 7 questions, so the drop-ins encode exactly the same tiles as the
  figures they replace.

The only intended visual difference: the originals carry a soft grey hand-drawn halo, the drop-ins
have crisp uniform strokes. Comparing raw ink pixels reports a large mismatch, which is that halo
and not a geometry change — a pixel IoU is the wrong test here, cell-map equality is the right one.

The swap could not be applied. **The MOM question editor is read-only for this account**, in the global
class (cid 334437) and in the source course (cid 340140) alike:

- `CodeMirror.isReadOnly() === true` on the control/qtext editors
- the whole image panel is disabled — `imgfile`, `newimgvar`, `imgvar-<id>`, `delimg-<id>`
- visually greyed out in the editor (the "Choose File" / `$tile` / "Delete?" row)

Three save attempts were made on qid 342944 and each was verified as a no-op afterwards. Worth
recording *why*, because every failure was silent:

1. `<button type="button">Save</button>` is not a submit button, so clicking it does nothing.
2. The source `<textarea>`s are `readonly` and empty; CodeMirror holds the real text, so a native
   form POST cannot carry the question source at all.
3. `imgfile` is `disabled`, so the browser drops it from `FormData` and the upload is discarded
   without any error. The app's real save path is `saveEditors()` (syncs CodeMirror into the form)
   followed by `quickSaveQuestion()`, an AJAX POST to `moddataset.php?...&quick=1`.

That last call did return `200` with a JSON body, and still changed nothing — which is what pinned the
cause on permissions rather than on plumbing. Do not try to force it: the disabled control is an access
restriction, not an obstacle to route around.

**Nothing was changed.** Read back after every attempt: control section, answer key, `$anstypes`,
description and qtype all intact; `usesrand`/`useashelp`/`usewithans` unchanged; the live question still
renders the original raster.

### To apply once you have edit access

Per question, in `course/moddataset.php?id=<qid>&cid=334437`:

1. Upload `dropin-png/<qid>.png` under a **new** variable (e.g. `$tilev2`). Do NOT tick Delete on the
   existing image — each image id maps to exactly one question (72538, 72543, 72546, 72547, 72548,
   72597, 72775), so keeping the original costs nothing and makes the change reversible.
2. In the control section, change that one `textonimage($tile,` call to `textonimage($tilev2,`.
3. Save, then load `course/testquestion2.php?cid=334437&qsetid=<qid>` and confirm the captions still
   sit under their figures.

Slot 2 (342755) is not in `dropin-png/`: its figure is an inline `<img>` in the question text rather than
a `$tile` image, so it needs a URL edit instead of an upload. Its source raster also has a baked-in
"Figure 2" caption that must not be doubled up.

## Two gotchas worth keeping

- **Slot 2 hides its figure in the question text**, as an `<img>` on an S3 upload, so a scan of
  the *rendered* page misses it — it has no `hasimg` flag either. Scan the question source for
  inline `<img src>` as well as the editor's image field.
- **Two drawing styles.** Most figures are black outlines around a white interior, which colour
  cannot distinguish from the page; those are recovered by flood-filling the page inward and
  treating unreachable interior as a tile. 343676 and 344390 are solid red, where the tile is
  just the coloured area. Solid fills also draw their internal borders inconsistently, so their
  pitch comes from the shortest silhouette run instead of from edge detection.
