// === NAME - DESCRIPTION: Simplex Three Variables - Maximize tech accessory profit with three products using the simplex method ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

$anstypes = array("numfunc", "numfunc", "numfunc", "numfunc")
/* ---------- 1. Context ---------- */
// Max Z = 5x1 + 4x2 + 3x3
// s.t. 2x1 + x2 + x3 <= 10  (labor hours)
//      x1  + x2 + 2x3 <= 8   (material lbs)
// Solution: x1=2, x2=6, x3=0, Z=34

$answer[0] = 2
$answer[1] = 6
$answer[2] = 0
$answer[3] = 34

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
      <p>Let x<sub>1</sub> = phone cases, x<sub>2</sub> = charging cables, x<sub>3</sub> = wall chargers (units produced).</p>
      <p>Maximize Z = 5x<sub>1</sub> + 4x<sub>2</sub> + 3x<sub>3</sub></p>
      <p>Subject to:<br>
        &nbsp;&nbsp;2x<sub>1</sub> + x<sub>2</sub> + x<sub>3</sub> &le; 10 &nbsp;(labor hours)<br>
        &nbsp;&nbsp;x<sub>1</sub> + x<sub>2</sub> + 2x<sub>3</sub> &le; 8 &nbsp;(material, lbs)<br>
        &nbsp;&nbsp;x<sub>1</sub>, x<sub>2</sub>, x<sub>3</sub> &ge; 0
      </p>
      <p><b>2. Convert to standard form</b> by adding slack variables s<sub>1</sub> (labor slack) and s<sub>2</sub> (material slack):</p>
      <p>
        2x<sub>1</sub> + x<sub>2</sub> + x<sub>3</sub> + s<sub>1</sub> = 10<br>
        x<sub>1</sub> + x<sub>2</sub> + 2x<sub>3</sub> + s<sub>2</sub> = 8
      </p>
      <p><b>3. Initial simplex tableau:</b></p>
      <table class="sx-table">
        <tr><th>BV</th><th>x<sub>1</sub></th><th>x<sub>2</sub></th><th>x<sub>3</sub></th><th>s<sub>1</sub></th><th>s<sub>2</sub></th><th>RHS</th></tr>
        <tr><td>s<sub>1</sub></td><td>2</td><td>1</td><td>1</td><td>1</td><td>0</td><td>10</td></tr>
        <tr><td>s<sub>2</sub></td><td>1</td><td>1</td><td>2</td><td>0</td><td>1</td><td>8</td></tr>
        <tr><td>Z</td><td>-5</td><td>-4</td><td>-3</td><td>0</td><td>0</td><td>0</td></tr>
      </table>
      <p><b>4. Pivot 1:</b> x<sub>1</sub> enters (most negative indicator: -5). Minimum-ratio test: 10/2 = 5, 8/1 = 8. s<sub>1</sub> departs (ratio = 5). Pivot element = 2.</p>
      <p>R<sub>1</sub> &larr; R<sub>1</sub>/2 &nbsp;|&nbsp; R<sub>2</sub> &larr; R<sub>2</sub> &minus; R<sub>1,new</sub> &nbsp;|&nbsp; Z &larr; Z + 5 &middot; R<sub>1,new</sub></p>
      <table class="sx-table">
        <tr><th>BV</th><th>x<sub>1</sub></th><th>x<sub>2</sub></th><th>x<sub>3</sub></th><th>s<sub>1</sub></th><th>s<sub>2</sub></th><th>RHS</th></tr>
        <tr><td>x<sub>1</sub></td><td>1</td><td>1/2</td><td>1/2</td><td>1/2</td><td>0</td><td>5</td></tr>
        <tr><td>s<sub>2</sub></td><td>0</td><td>1/2</td><td>3/2</td><td>-1/2</td><td>1</td><td>3</td></tr>
        <tr><td>Z</td><td>0</td><td>-3/2</td><td>-1/2</td><td>5/2</td><td>0</td><td>25</td></tr>
      </table>
      <p><b>5. Pivot 2:</b> x<sub>2</sub> enters (most negative: -3/2). Minimum-ratio test: 5/(1/2) = 10, 3/(1/2) = 6. s<sub>2</sub> departs (ratio = 6). Pivot element = 1/2.</p>
      <p>R<sub>2</sub> &larr; R<sub>2</sub> &times; 2 &nbsp;|&nbsp; R<sub>1</sub> &larr; R<sub>1</sub> &minus; (1/2) &middot; R<sub>2,new</sub> &nbsp;|&nbsp; Z &larr; Z + (3/2) &middot; R<sub>2,new</sub></p>
      <table class="sx-table">
        <tr><th>BV</th><th>x<sub>1</sub></th><th>x<sub>2</sub></th><th>x<sub>3</sub></th><th>s<sub>1</sub></th><th>s<sub>2</sub></th><th>RHS</th></tr>
        <tr><td>x<sub>1</sub></td><td>1</td><td>0</td><td>-1</td><td>1</td><td>-1</td><td>2</td></tr>
        <tr><td>x<sub>2</sub></td><td>0</td><td>1</td><td>3</td><td>-1</td><td>2</td><td>6</td></tr>
        <tr><td>Z</td><td>0</td><td>0</td><td>4</td><td>1</td><td>3</td><td>34</td></tr>
      </table>
      <p>All objective-row indicators are non-negative, so this is optimal. x<sub>3</sub> = 0 (wall chargers are not produced in the optimal mix).</p>
      <p><b>6. Optimal solution:</b></p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        x<sub>1</sub> = 2 (phone cases), &nbsp;
        x<sub>2</sub> = 6 (charging cables), &nbsp;
        x<sub>3</sub> = 0 (wall chargers), &nbsp;
        Maximum profit Z = $34
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <p>A tech accessories company produces three products: <b>phone cases</b> (x<sub>1</sub>), <b>charging cables</b> (x<sub>2</sub>), and <b>wall chargers</b> (x<sub>3</sub>). Profit per unit: $5 for cases, $4 for cables, and $3 for chargers.</p>
  <p>Production is limited by two resources:</p>
  <div style="border-radius:12px; overflow:hidden; box-shadow:0 4px 6px -1px rgba(0,0,0,0.08),0 2px 4px -2px rgba(0,0,0,0.05); border:1px solid #e5e7eb; display:inline-block; margin:8px 0;">
    <table style="border-collapse:collapse; font-family:inherit; font-size:inherit;">
      <tr>
        <th style="background:#f7f9fa; font-weight:600; color:#21242c; border-bottom:2px solid #dee1e3; padding:10px 18px; text-align:left;">Resource</th>
        <th style="background:#f7f9fa; font-weight:600; color:#21242c; border-bottom:2px solid #dee1e3; padding:10px 18px; text-align:center; border-left:1px solid #e5e7eb;">Case (x<sub>1</sub>)</th>
        <th style="background:#f7f9fa; font-weight:600; color:#21242c; border-bottom:2px solid #dee1e3; padding:10px 18px; text-align:center; border-left:1px solid #e5e7eb;">Cable (x<sub>2</sub>)</th>
        <th style="background:#f7f9fa; font-weight:600; color:#21242c; border-bottom:2px solid #dee1e3; padding:10px 18px; text-align:center; border-left:1px solid #e5e7eb;">Charger (x<sub>3</sub>)</th>
        <th style="background:#f7f9fa; font-weight:600; color:#21242c; border-bottom:2px solid #dee1e3; padding:10px 18px; text-align:center; border-left:1px solid #e5e7eb;">Available</th>
      </tr>
      <tr>
        <td style="padding:10px 18px; border-bottom:1px solid #dee1e3;">Labor (hrs)</td>
        <td style="padding:10px 18px; text-align:center; border-left:1px solid #e5e7eb; border-bottom:1px solid #dee1e3;">2</td>
        <td style="padding:10px 18px; text-align:center; border-left:1px solid #e5e7eb; border-bottom:1px solid #dee1e3;">1</td>
        <td style="padding:10px 18px; text-align:center; border-left:1px solid #e5e7eb; border-bottom:1px solid #dee1e3;">1</td>
        <td style="padding:10px 18px; text-align:center; border-left:1px solid #e5e7eb; border-bottom:1px solid #dee1e3;">10</td>
      </tr>
      <tr>
        <td style="padding:10px 18px;">Material (lbs)</td>
        <td style="padding:10px 18px; text-align:center; border-left:1px solid #e5e7eb;">1</td>
        <td style="padding:10px 18px; text-align:center; border-left:1px solid #e5e7eb;">1</td>
        <td style="padding:10px 18px; text-align:center; border-left:1px solid #e5e7eb;">2</td>
        <td style="padding:10px 18px; text-align:center; border-left:1px solid #e5e7eb;">8</td>
      </tr>
    </table>
  </div>
  <p><b>Use the simplex method</b> to find the production quantities that maximize total profit. Show your complete tableau work, then enter the optimal values below.</p>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Optimal number of phone cases (x<sub>1</sub>):
    <div style="margin-top:12px; text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Optimal number of charging cables (x<sub>2</sub>):
    <div style="margin-top:12px; text-align:center;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Optimal number of wall chargers (x<sub>3</sub>):
    <div style="margin-top:12px; text-align:center;">$answerbox[2]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Maximum profit (Z):
    <div style="margin-top:12px; text-align:center;">$answerbox[3]</div>
  </div>
</div>

// === ANSWER ===

$solutionguide
