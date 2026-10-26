#!/usr/bin/env python3
"""Render caption-free drop-in PNGs at each source raster's EXACT pixel size.

MyOpenMath stamps the "Fig. n" captions itself via textonimage($tile, ..., x, y, ...),
with pixel offsets tuned to the original raster. So a drop-in replacement must:
  * be exactly the source width x height, and
  * put each figure's tiles at their ORIGINAL pixel coordinates, and
  * contain NO caption text (textonimage supplies it).
This is deliberately a different renderer from render-svg.py, which draws clean
standalone figures for human use rather than pixel-faithful drop-ins.
"""
import json, os, subprocess, sys
from PIL import Image

D = '/home/shuff57/Documents/GitHub/steve-desktop/steve-desktop/mom-content/tile-patterns/im1-2.1'
GEOM = sys.argv[1] if len(sys.argv) > 1 else '/tmp/opencode/im21/geom.json'
OUT = sys.argv[2] if len(sys.argv) > 2 else '/tmp/opencode/im21/dropin'
os.makedirs(OUT, exist_ok=True)

# 342755's figure is an inline <img> in the question text, not a $tile image, and its
# source already carries a baked-in "Figure 2" caption in the raster. Leave it alone.
SKIP = {'342755'}
INK, FILL, FILL_EDGE = '#1a1a1a', '#d6756f', '#b04a44'

geom = json.load(open(GEOM))
for qid, rec in geom.items():
    if qid in SKIP:
        print(f'  skip {qid} (inline <img>, not a $tile image)'); continue
    src = Image.open(f'{D}/source-rasters/{qid}.png')
    W, H = src.size
    parts = [f'<svg xmlns="http://www.w3.org/2000/svg" width="{W}" height="{H}" '
             f'viewBox="0 0 {W} {H}">']
    parts.append(f'  <rect width="{W}" height="{H}" fill="#ffffff"/>')
    for f in rec['figs']:
        sw = max(1.0, min(2.2, min(f['pitch_x'], f['pitch_y']) / 13.0))
        for key, solid in sorted(f['cells'].items(), key=lambda kv: [int(n) for n in kv[0].split(',')]):
            r, c = (int(n) for n in key.split(','))
            x = f['x0'] + c * f['pitch_x']; y = f['y0'] + r * f['pitch_y']
            w = f['pitch_x']; h = f['pitch_y']
            parts.append(f'  <rect x="{x:.2f}" y="{y:.2f}" width="{w:.2f}" height="{h:.2f}" '
                         f'fill="{FILL if solid else "#ffffff"}" '
                         f'stroke="{FILL_EDGE if solid else INK}" stroke-width="{sw:.2f}"/>')
    parts.append('</svg>')
    svg = '\n'.join(parts) + '\n'
    sp = f'{OUT}/{qid}.svg'
    open(sp, 'w').write(svg)
    png = f'{OUT}/{qid}.png'
    subprocess.run(['google-chrome', '--headless', '--disable-gpu', '--no-sandbox',
                    f'--screenshot={png}', f'--window-size={W},{H}',
                    '--default-background-color=FFFFFFFF',
                    f'file://{sp}'], capture_output=True, timeout=90)
    got = Image.open(png).size if os.path.exists(png) else None
    ok = 'OK' if got == (W, H) else f'SIZE MISMATCH got {got}'
    print(f'  {qid}: source {W}x{H} -> {ok}')
