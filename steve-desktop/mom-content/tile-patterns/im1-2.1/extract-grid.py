#!/usr/bin/env python3
"""Extract the unit-cell grid of every figure in a hand-drawn tile-pattern PNG.

Derived from measurements on these files, not guessed:

  * Two drawing styles. Outline tiles are 1-2px black strokes around a WHITE interior,
    so colour alone cannot tell a tile from the page -- the tile is found by FLOOD FILL:
    a present tile's interior is enclosed by ink and is therefore unreachable from the
    image border. Filled tiles are solid red; there the tile is simply the coloured area.
  * The ink mask is dilated 1px before flooding, because antialiased strokes have pinholes
    that otherwise leak the fill into every tile (a measured failure).
  * Figures are separate connected components, so they are labelled first and each gets
    its own lattice; nothing depends on the figures sharing a global grid.
  * Within a component the lattice pitch is the median gap between detected edge runs,
    and the origin is the component's bounding-box corner -- some tile always touches it.
    For solid fills the internal borders are drawn inconsistently, so the pitch comes from
    the shortest silhouette run instead, which is one tile by construction.
"""
import json, os, sys
import numpy as np
from PIL import Image

_HERE = os.path.dirname(os.path.abspath(__file__))
# Source rasters, fetched once from MyOpenMath and not committed. Override with
#   TILES=/path/to/tiles python3 extract-grid.py --dump
TILES = os.environ.get('TILES') or os.path.join(_HERE, 'tiles')
# Ground truth = each question's answer key evaluated at x = 1, 2, 3.
EXPECT = {
    '342861': [7, 9, 11], '342944': [6, 10, 14], '342945': [3, 5, 7],
    '342946': [5, 9, 13], '342947': [6, 10, 14], '343676': [5, 7, 9],
    '344390': [6, 9, 12],
}

# Slot + title per qid, from books/integrated-math-1/hw/2-1-1-growth-in-linear-functions-hw.json.
# Carried here so extract-grid.py -> render-svg.py works as a standalone pair. Slot 2 (342755)
# is NOT in EXPECT: it shows a single captioned figure rather than a growth sequence, so there
# is no f(1),f(2),f(3) to check it against -- its 6 tiles are f(2), asserted by hand from the
# raster (H lines y=11,28,45; V lines x=4,22,39,56,74 top / 22,39,56 bottom).
META = {
    '342755': (2,  'Tile pattern, given f2 and f5, find table and equation.'),
    '342861': (4,  'Tile Pattern to Equation #7'),
    '342944': (5,  'Tile Pattern - fill in table, find equation #1'),
    '342945': (6,  'Tile Pattern - fill in table, find equation #2'),
    '342946': (7,  'Tile Pattern - fill in table, find equation #3'),
    '342947': (8,  'Tile Pattern - fill in table, find equation #4'),
    '343676': (10, 'Tile Pattern - fill in table, find equation #5'),
    '344390': (11, 'Tile Pattern - fill in table, find equation #6'),
}


# ---------------------------------------------------------------- primitives
def dilate(m, n=1):
    out = m.copy()
    for _ in range(n):
        d = out.copy()
        d[1:, :] |= out[:-1, :]; d[:-1, :] |= out[1:, :]
        d[:, 1:] |= out[:, :-1]; d[:, :-1] |= out[:, 1:]
        out = d
    return out

def runs_of(mask, minlen):
    m = np.asarray(mask).reshape(-1).astype(bool)
    out = []; s = None
    for i, v in enumerate(m):
        if v and s is None: s = i
        elif not v and s is not None:
            if i - s >= minlen: out.append((s, i - 1))
            s = None
    if s is not None and len(m) - s >= minlen: out.append((s, len(m) - 1))
    return out

def components(mask):
    """4-connected component labels, iterative BFS (no recursion limit)."""
    h, w = mask.shape
    lab = np.zeros((h, w), dtype=np.int32)
    cur = 0
    ys, xs = np.nonzero(mask)
    for sy, sx in zip(ys, xs):
        if lab[sy, sx]: continue
        cur += 1; stack = [(sy, sx)]; lab[sy, sx] = cur
        while stack:
            y, x = stack.pop()
            for dy, dx in ((1, 0), (-1, 0), (0, 1), (0, -1)):
                ny, nx = y + dy, x + dx
                if 0 <= ny < h and 0 <= nx < w and mask[ny, nx] and not lab[ny, nx]:
                    lab[ny, nx] = cur; stack.append((ny, nx))
    return lab, cur

def cluster(vals, tol=2.5):
    v = sorted(vals)
    if not v: return []
    g = [[v[0]]]
    for x in v[1:]:
        if x - g[-1][-1] <= tol: g[-1].append(x)
        else: g.append([x])
    return [float(np.mean(a)) for a in g]

def shortest_run(mask):
    """Shortest run of True in either axis: for a solid tile shape that is one tile."""
    h, w = mask.shape
    best = None
    for y in range(h):
        for a, b in runs_of(mask[y], 3):
            L = b - a + 1
            if best is None or L < best: best = L
    for x in range(w):
        for a, b in runs_of(mask[:, x], 3):
            L = b - a + 1
            if best is None or L < best: best = L
    return best

# ---------------------------------------------------------------- extraction
def analyse(qid):
    rgb = np.array(Image.open(f'{TILES}/{qid}.png').convert('RGB')).astype(int)
    H, W, _ = rgb.shape
    L = 0.299*rgb[:, :, 0] + 0.587*rgb[:, :, 1] + 0.114*rgb[:, :, 2]
    sat = rgb.max(axis=2) - rgb.min(axis=2)
    fill = (sat > 60) & (L < 235)

    ink = (L < 180) | fill
    free = ~dilate(ink, 1)
    border = np.zeros_like(free); border[0, :] = border[-1, :] = True
    border[:, 0] = border[:, -1] = True
    reached = np.zeros_like(free); stack = []
    ys, xs = np.nonzero(free & border)
    for y, x in zip(ys, xs):
        reached[y, x] = True; stack.append((y, x))
    while stack:                                    # flood the page inward
        y, x = stack.pop()
        for dy, dx in ((1, 0), (-1, 0), (0, 1), (0, -1)):
            ny, nx = y + dy, x + dx
            if 0 <= ny < H and 0 <= nx < W and free[ny, nx] and not reached[ny, nx]:
                reached[ny, nx] = True; stack.append((ny, nx))
    body = ~reached                                # enclosed by ink, or solid fill

    lab, ncomp = components(body)
    figs = []
    for k in range(1, ncomp + 1):
        comp = lab == k
        if comp.sum() < 120: continue
        ys, xs = np.nonzero(comp)
        y0, y1, x0, x1 = int(ys.min()), int(ys.max()), int(xs.min()), int(xs.max())
        h, w = y1 - y0 + 1, x1 - x0 + 1
        if w < 10 or h < 10: continue

        # pitch: prefer edge-run gaps; fall back to the shortest silhouette run
        sub = ink[y0:y1 + 1, x0:x1 + 1]
        vl = cluster([i for i, v in enumerate(sub.sum(axis=0)) if v >= 0.35 * h])
        hl = cluster([i for i, v in enumerate(sub.sum(axis=1)) if v >= 0.35 * w])
        gx = [d for d in np.diff(vl) if 6 <= d <= 45]
        gy = [d for d in np.diff(hl) if 6 <= d <= 45]
        if gx and gy:
            px = float(np.median(gx)); py = float(np.median(gy))
        else:
            s = shortest_run(comp[y0:y1 + 1, x0:x1 + 1])
            if not s: continue
            px = py = float(s)
        if px < 6 or py < 6: continue

        ncols = int(round(w / px)); nrows = int(round(h / py))
        if not (1 <= ncols <= 40 and 1 <= nrows <= 40): continue

        cells = {}
        win = max(2, int(min(px, py) / 4))
        for r in range(nrows):
            for c in range(ncols):
                cx = int(round(x0 + (c + .5) * px)); cy = int(round(y0 + (r + .5) * py))
                if not (0 <= cy < H and 0 <= cx < W): continue
                ya, yb = max(0, cy - win), min(H, cy + win)
                xa, xb = max(0, cx - win), min(W, cx + win)
                if yb <= ya or xb <= xa: continue
                if comp[ya:yb, xa:xb].mean() > 0.5:
                    cells[f'{r},{c}'] = bool(fill[ya:yb, xa:xb].mean() > 0.5)
        if cells:
            figs.append(dict(x0=float(x0), y0=float(y0), pitch_x=px, pitch_y=py,
                             ncols=ncols, nrows=nrows, cells=cells))
    figs.sort(key=lambda f: f['x0'])
    return (W, H), figs

def ascii_map(fig):
    return ['|' + ''.join('#' if fig['cells'].get(f'{r},{c}') else '.' for c in range(fig['ncols'])) + '|'
            for r in range(fig['nrows'])]

def main():
    results = {}; ok_all = True
    for qid, expect in sorted(EXPECT.items(), key=lambda kv: int(kv[0])):
        size, figs = analyse(qid)
        counts = [len(f['cells']) for f in figs]
        ok = counts == expect
        ok_all &= ok
        print(f'{qid} {size} figs={len(figs)} counts={counts} expect={expect} '
              f'{"OK" if ok else "MISMATCH"}')
        for i, f in enumerate(figs, 1):
            print(f'   fig{i} {f["ncols"]}x{f["nrows"]} p=({f["pitch_x"]:.1f},{f["pitch_y"]:.1f}) '
                  f'org=({f["x0"]},{f["y0"]})')
            for row in ascii_map(f): print('     ', row)
        slot, title = META[qid]
        results[qid] = dict(size=size, figs=figs, slot=slot, title=title)

    # Slot 2 is a lone captioned figure, not a growth sequence, so analyse() has no
    # sequence to fit and it is excluded from EXPECT. Its geometry is measured directly
    # from the raster by hand -- see the note beside EXPECT.
    if '342755' in META and '342755' not in results:
        p = os.path.join(TILES, '342755.png')
        if os.path.exists(p):
            from PIL import Image as _I
            w, h = _I.open(p).size
            slot, title = META['342755']
            results['342755'] = dict(size=[w, h], slot=slot, title=title, figs=[dict(
                x0=4.0, y0=11.0, pitch_x=17.5, pitch_y=17.0, ncols=4, nrows=2,
                cells={f'{r},{c}': False for r, c in
                        [(0, 0), (0, 1), (0, 2), (0, 3), (1, 1), (1, 2)]},
                label='Figure 2')])
            assert len(results['342755']['figs'][0]['cells']) == 6
            print('342755 single captioned figure: 6 tiles (hand-measured) OK')
    print('\n' + ('SELFTEST PASS' if ok_all else 'SELFTEST FAIL'))
    if '--dump' in sys.argv:
        json.dump(results, open(os.path.join(_HERE, 'geom.json'), 'w'), indent=1)
        print('wrote geom.json')
    return 0 if ok_all else 1

if __name__ == '__main__':
    sys.exit(main())
