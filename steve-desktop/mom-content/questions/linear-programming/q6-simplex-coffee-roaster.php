// === NAME - DESCRIPTION: Simplex Coffee Roaster - Maximize coffee roaster profit using the simplex method ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

$anstypes = array("numfunc", "numfunc", "numfunc")
/* ---------- 1. Context ---------- */
// Max Z = 18x1 + 15x2
// s.t. 3x1 + 2x2 <= 24 (green beans, lbs)
//      x1  + 2x2 <= 16 (roasting time, hrs)
// Solution: x1=4, x2=6, Z=162

$answer[0] = 4
$answer[1] = 6
$answer[2] = 162

/* ---------- 2. Solution Guide ---------- */
$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
  .sx-table { border-collapse:collapse; margin:12px auto; font-family:ui-monospace,SFMono-Regular,Menlo,monospace; font-size:14px; }
  .sx-table th, .sx-table td { border:1px solid #dee1e3; padding:6px 14px; text-align:center; }
  .sx-table th { background:#f7f9fa; font-weight:600; color:#21242c; border-bottom:2px solid #dee1e3; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><b>1. Define variables and set up the LP:</b></p>
      <p>Let x<sub>1</sub> = pounds of dark roast blend, x<sub>2</sub> = pounds of light roast blend.</p>
      <p>Maximize Z = 18x<sub>1</sub> + 15x<sub>2</sub></p>
      <p>Subject to:<br>
        &nbsp;&nbsp;3x<sub>1</sub> + 2x<sub>2</sub> &le; 24 &nbsp;(green bean supply, lbs)<br>
        &nbsp;&nbsp;x<sub>1</sub> + 2x<sub>2</sub> &le; 16 &nbsp;(roasting time, hrs)<br>
        &nbsp;&nbsp;x<sub>1</sub>, x<sub>2</sub> &ge; 0
      </p>
      <p><b>2. Convert to standard form</b> by adding slack variables s<sub>1</sub> (bean slack) and s<sub>2</sub> (time slack):</p>
      <p>
        3x<sub>1</sub> + 2x<sub>2</sub> + s<sub>1</sub> = 24<br>
        x<sub>1</sub> + 2x<sub>2</sub> + s<sub>2</sub> = 16
      </p>
      <p><b>3. Initial simplex tableau:</b></p>
      <table class="sx-table">
        <tr><th>BV</th><th>x<sub>1</sub></th><th>x<sub>2</sub></th><th>s<sub>1</sub></th><th>s<sub>2</sub></th><th>RHS</th></tr>
        <tr><td>s<sub>1</sub></td><td>3</td><td>2</td><td>1</td><td>0</td><td>24</td></tr>
        <tr><td>s<sub>2</sub></td><td>1</td><td>2</td><td>0</td><td>1</td><td>16</td></tr>
        <tr><td>Z</td><td>-18</td><td>-15</td><td>0</td><td>0</td><td>0</td></tr>
      </table>
      <p><b>4. Pivot 1:</b> x<sub>1</sub> enters (most negative indicator: -18). Minimum-ratio test: 24/3 = 8, 16/1 = 16. s<sub>1</sub> departs (ratio = 8). Pivot element = 3.</p>
      <p>R<sub>1</sub> &larr; R<sub>1</sub>/3 &nbsp;|&nbsp; R<sub>2</sub> &larr; R<sub>2</sub> &minus; R<sub>1,new</sub> &nbsp;|&nbsp; Z &larr; Z + 18 &middot; R<sub>1,new</sub></p>
      <table class="sx-table">
        <tr><th>BV</th><th>x<sub>1</sub></th><th>x<sub>2</sub></th><th>s<sub>1</sub></th><th>s<sub>2</sub></th><th>RHS</th></tr>
        <tr><td>x<sub>1</sub></td><td>1</td><td>2/3</td><td>1/3</td><td>0</td><td>8</td></tr>
        <tr><td>s<sub>2</sub></td><td>0</td><td>4/3</td><td>-1/3</td><td>1</td><td>8</td></tr>
        <tr><td>Z</td><td>0</td><td>-3</td><td>6</td><td>0</td><td>144</td></tr>
      </table>
      <p><b>5. Pivot 2:</b> x<sub>2</sub> enters (only negative indicator: -3). Minimum-ratio test: 8/(2/3) = 12, 8/(4/3) = 6. s<sub>2</sub> departs (ratio = 6). Pivot element = 4/3.</p>
      <p>R<sub>2</sub> &larr; R<sub>2</sub> &times; (3/4) &nbsp;|&nbsp; R<sub>1</sub> &larr; R<sub>1</sub> &minus; (2/3) &middot; R<sub>2,new</sub> &nbsp;|&nbsp; Z &larr; Z + 3 &middot; R<sub>2,new</sub></p>
      <table class="sx-table">
        <tr><th>BV</th><th>x<sub>1</sub></th><th>x<sub>2</sub></th><th>s<sub>1</sub></th><th>s<sub>2</sub></th><th>RHS</th></tr>
        <tr><td>x<sub>1</sub></td><td>1</td><td>0</td><td>1/2</td><td>-1/2</td><td>4</td></tr>
        <tr><td>x<sub>2</sub></td><td>0</td><td>1</td><td>-1/4</td><td>3/4</td><td>6</td></tr>
        <tr><td>Z</td><td>0</td><td>0</td><td>21/4</td><td>9/4</td><td>162</td></tr>
      </table>
      <p>All objective-row indicators are non-negative, so this is optimal.</p>
      <p><b>6. Optimal solution:</b></p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        x<sub>1</sub> = 4 (lbs dark roast blend), &nbsp;
        x<sub>2</sub> = 6 (lbs light roast blend), &nbsp;
        Maximum profit Z = $162
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <p>A small-batch coffee roaster produces two blends: <b>dark roast</b> (x<sub>1</sub>, lbs) and <b>light roast</b> (x<sub>2</sub>, lbs). The dark blend sells for $18 profit per lb and the light blend for $15 profit per lb.</p>
  <p>Each batch is limited by bean supply and roasting time:</p>
  <div style="border-radius:12px; overflow:hidden; box-shadow:0 4px 6px -1px rgba(0,0,0,0.08),0 2px 4px -2px rgba(0,0,0,0.05); border:1px solid #e5e7eb; display:inline-block; margin:8px 0;">
    <table style="border-collapse:collapse; font-family:inherit; font-size:inherit;">
      <tr>
        <th style="background:#f7f9fa; font-weight:600; color:#21242c; border-bottom:2px solid #dee1e3; padding:10px 18px; text-align:left;">Resource</th>
        <th style="background:#f7f9fa; font-weight:600; color:#21242c; border-bottom:2px solid #dee1e3; padding:10px 18px; text-align:center; border-left:1px solid #e5e7eb;">Dark Roast (x<sub>1</sub>)</th>
        <th style="background:#f7f9fa; font-weight:600; color:#21242c; border-bottom:2px solid #dee1e3; padding:10px 18px; text-align:center; border-left:1px solid #e5e7eb;">Light Roast (x<sub>2</sub>)</th>
        <th style="background:#f7f9fa; font-weight:600; color:#21242c; border-bottom:2px solid #dee1e3; padding:10px 18px; text-align:center; border-left:1px solid #e5e7eb;">Available</th>
      </tr>
      <tr>
        <td style="padding:10px 18px; border-bottom:1px solid #dee1e3;">Green beans (lbs)</td>
        <td style="padding:10px 18px; text-align:center; border-left:1px solid #e5e7eb; border-bottom:1px solid #dee1e3;">3</td>
        <td style="padding:10px 18px; text-align:center; border-left:1px solid #e5e7eb; border-bottom:1px solid #dee1e3;">2</td>
        <td style="padding:10px 18px; text-align:center; border-left:1px solid #e5e7eb; border-bottom:1px solid #dee1e3;">24</td>
      </tr>
      <tr>
        <td style="padding:10px 18px;">Roasting time (hrs)</td>
        <td style="padding:10px 18px; text-align:center; border-left:1px solid #e5e7eb;">1</td>
        <td style="padding:10px 18px; text-align:center; border-left:1px solid #e5e7eb;">2</td>
        <td style="padding:10px 18px; text-align:center; border-left:1px solid #e5e7eb;">16</td>
      </tr>
    </table>
  </div>
  <p><b>Use the simplex method</b> to find the blend quantities that maximize total profit. Set up the initial tableau, perform all necessary pivots, and enter the optimal values below.</p>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Optimal lbs of dark roast blend (x<sub>1</sub>):
    <div style="margin-top:12px; text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Optimal lbs of light roast blend (x<sub>2</sub>):
    <div style="margin-top:12px; text-align:center;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Maximum profit (Z):
    <div style="margin-top:12px; text-align:center;">$answerbox[2]</div>
  </div>
</div>

// === ANSWER ===

$solutionguide
