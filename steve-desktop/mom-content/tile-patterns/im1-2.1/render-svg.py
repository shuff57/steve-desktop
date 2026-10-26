#!/usr/bin/env python3
"""Emit one clean SVG per tile-pattern question from the extracted cell geometry.

Each tile is a unit square on its figure's lattice. Outlined tiles are drawn as
white-filled rects with a dark stroke; solid tiles keep the source's red fill.
Figures are re-laid-out on a single global tile pitch so the seven SVGs are
visually consistent with each other rather than each carrying its own raster scale.
"""
import json, sys, os, re

_HERE = os.path.dirname(os.path.abspath(__file__))
GEOM = os.environ.get('GEOM') or os.path.join(_HERE, 'geom.json')
OUT  = sys.argv[1] if len(sys.argv) > 1 else os.path.join(_HERE, 'svg')


TILE      = 34.0     # px per unit square in the output
GAP       = 1.15     # multiples of TILE between figures
PAD       = 10.0
LABEL_GAP = 26.0     # room under the figures for the "Fig. n" captions
STROKE    = 1.6
INK       = '#1a1a1a'
FILL      = '#d6756f'   # matches the source raster's tile red
FILL_EDGE = '#b04a44'

def esc(s): return (s or '').replace('&','&amp;').replace('<','&lt;').replace('>','&gt;')

def build(qid, rec):
    figs = rec['figs']
    if not figs:
        return None, 'no figures extracted'
    # column extents, to lay figures out left-to-right on one pitch
    widths = [f['ncols'] for f in figs]
    heights = [f['nrows'] for f in figs]
    xs, x = [], 0.0
    for f in figs:
        xs.append(x); x += f['ncols']*TILE + GAP*TILE
    total_w = x - GAP*TILE
    max_h = max(heights)*TILE
    W = total_w + 2*PAD
    H = max_h + 2*PAD + LABEL_GAP

    parts = []
    parts.append(f'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {W:.1f} {H:.1f}" '
                 f'width="{W:.0f}" height="{H:.0f}" role="img" '
                 f'aria-label="{esc(rec["title"])}">')
    parts.append(f'  <title>{esc(rec["title"])}</title>')
    # white backdrop so the figure reads the same on any page colour
    parts.append(f'  <rect width="{W:.1f}" height="{H:.1f}" fill="#ffffff"/>')

    body = []
    for i, f in enumerate(figs, 1):
        ox = PAD + xs[i-1]
        oy = PAD + (max_h - f['nrows']*TILE)      # bottom-align the figures
        for key, solid in sorted(f['cells'].items(), key=lambda kv: [int(n) for n in kv[0].split(',')]):
            r, c = (int(n) for n in key.split(','))
            x0 = ox + c*TILE; y0 = oy + r*TILE
            fill = FILL if solid else '#ffffff'
            stroke = FILL_EDGE if solid else INK
            body.append(f'    <rect x="{x0:.2f}" y="{y0:.2f}" width="{TILE:.2f}" height="{TILE:.2f}" '
                        f'fill="{fill}" stroke="{stroke}" stroke-width="{STROKE}"/>')
        cx = ox + f['ncols']*TILE/2
        caption = (f.get('label') or f'Fig. {i}')   # a lone figure is captioned in the source
        body.append(f'    <text x="{cx:.2f}" y="{oy + f["nrows"]*TILE + LABEL_GAP*0.72:.2f}" '
                    f'text-anchor="middle" font-family="Helvetica,Arial,sans-serif" '
                    f'font-size="{LABEL_GAP*0.72:.1f}" fill="{INK}">{esc(caption)}</text>')
    parts.append('  <g stroke-linejoin="miter">')
    parts.extend(body)
    parts.append('  </g>')
    parts.append('</svg>')
    return '\n'.join(parts) + '\n', None

def main():
    data = json.load(open(GEOM))
    os.makedirs(OUT, exist_ok=True)
    index = []
    for qid, rec in sorted(data.items(), key=lambda kv: kv[1]['slot']):
        svg, err = build(qid, rec)
        if err:
            print(f'  SKIP {qid}: {err}'); continue
        path = os.path.join(OUT, f'im1-2.1-tiles-slot{rec["slot"]:02d}-qid{qid}.svg')
        open(path, 'w').write(svg)
        counts = [len(f['cells']) for f in rec['figs']]
        index.append((rec['slot'], qid, counts, rec['title'], os.path.basename(path)))
        print(f'  wrote {os.path.basename(path)}  figures={counts}')
    print()
    print(f'{"slot":>4} {"qid":>7}  tile counts        title')
    for slot, qid, counts, title, name in index:
        print(f'{slot:>4} {qid:>7}  {str(counts):18} {title}')
    return 0 if index else 1

if __name__ == '__main__':
    sys.exit(main())
