// === NAME - DESCRIPTION: Simplex Candle Shop - Maximize candle shop profit using the simplex method ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

$anstypes = array("number", "number", "number", "file")
$answerformat[0] = "integer"
$answerformat[1] = "integer"
$answerformat[2] = "integer"
$scoremethod[3] = "takeanything"
$answerformat[3] = "images,.pdf"

/* ---------- 1. Context ---------- */
// Max Z = 20x1 + 25x2
// s.t. x1 + 2x2 <= 14 (wax), x1 + x2 <= 8 (labor)
// Solution: x1=2, x2=6, Z=190

$answer[0] = 2
$answer[1] = 6
$answer[2] = 190

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
      <p>Let x<sub>1</sub> = number of jar candles, x<sub>2</sub> = number of pillar candles.</p>
      <p>Maximize Z = 20x<sub>1</sub> + 25x<sub>2</sub></p>
      <p>Subject to:<br>
        &nbsp;&nbsp;x<sub>1</sub> + 2x<sub>2</sub> &le; 14 &nbsp;(pounds of wax)<br>
        &nbsp;&nbsp;x<sub>1</sub> + x<sub>2</sub> &le; 8 &nbsp;(hours of labor)<br>
        &nbsp;&nbsp;x<sub>1</sub>, x<sub>2</sub> &ge; 0
      </p>

      <p><b>2. Convert to standard form</b> by adding slack variables s<sub>1</sub> and s<sub>2</sub>:</p>
      <p>
        x<sub>1</sub> + 2x<sub>2</sub> + s<sub>1</sub> = 14<br>
        x<sub>1</sub> + x<sub>2</sub> + s<sub>2</sub> = 8
      </p>

      <p><b>3. Initial simplex tableau:</b></p>
      <table class="sx-table">
        <tr><th>BV</th><th>x<sub>1</sub></th><th>x<sub>2</sub></th><th>s<sub>1</sub></th><th>s<sub>2</sub></th><th>RHS</th></tr>
        <tr><td>s<sub>1</sub></td><td>1</td><td>2</td><td>1</td><td>0</td><td>14</td></tr>
        <tr><td>s<sub>2</sub></td><td>1</td><td>1</td><td>0</td><td>1</td><td>8</td></tr>
        <tr><td>Z</td><td>-20</td><td>-25</td><td>0</td><td>0</td><td>0</td></tr>
      </table>

      <p><b>4. Pivot 1:</b> x<sub>2</sub> enters (most negative: -25). Ratios: 14/2 = 7, 8/1 = 8. s<sub>1</sub> departs. Pivot on (s<sub>1</sub>, x<sub>2</sub>) = 2.</p>
      <table class="sx-table">
        <tr><th>BV</th><th>x<sub>1</sub></th><th>x<sub>2</sub></th><th>s<sub>1</sub></th><th>s<sub>2</sub></th><th>RHS</th></tr>
        <tr><td>x<sub>2</sub></td><td>1/2</td><td>1</td><td>1/2</td><td>0</td><td>7</td></tr>
        <tr><td>s<sub>2</sub></td><td>1/2</td><td>0</td><td>-1/2</td><td>1</td><td>1</td></tr>
        <tr><td>Z</td><td>-15/2</td><td>0</td><td>25/2</td><td>0</td><td>175</td></tr>
      </table>

      <p><b>5. Pivot 2:</b> x<sub>1</sub> enters (most negative: -15/2). Ratios: 7/(1/2) = 14, 1/(1/2) = 2. s<sub>2</sub> departs. Pivot on (s<sub>2</sub>, x<sub>1</sub>) = 1/2.</p>
      <table class="sx-table">
        <tr><th>BV</th><th>x<sub>1</sub></th><th>x<sub>2</sub></th><th>s<sub>1</sub></th><th>s<sub>2</sub></th><th>RHS</th></tr>
        <tr><td>x<sub>2</sub></td><td>0</td><td>1</td><td>1</td><td>-1</td><td>6</td></tr>
        <tr><td>x<sub>1</sub></td><td>1</td><td>0</td><td>-1</td><td>2</td><td>2</td></tr>
        <tr><td>Z</td><td>0</td><td>0</td><td>5</td><td>15</td><td>190</td></tr>
      </table>

      <p>All indicators are non-negative, so this is optimal.</p>

      <p><b>6. Optimal solution:</b></p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        x<sub>1</sub> = 2 (jar candles), &nbsp;
        x<sub>2</sub> = 6 (pillar candles), &nbsp;
        Maximum profit Z = $190
      </div>
    </div>
  </details>
</div>'

/* ---------- 3. Question Text ---------- */

//question text// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">

  <p>A candle shop produces two products: <b>jar candles</b> (x<sub>1</sub>) and <b>pillar candles</b> (x<sub>2</sub>). Each jar candle generates $20 in profit, and each pillar candle generates $25 in profit.</p>

  <p>Production is limited by two resources:</p>

  <div style="border-radius:12px; overflow:hidden; box-shadow:0 4px 6px -1px rgba(0,0,0,0.08),0 2px 4px -2px rgba(0,0,0,0.05); border:1px solid #e5e7eb; display:inline-block; margin:8px 0;">
    <table style="border-collapse:collapse; font-family:inherit; font-size:inherit;">
      <tr>
        <th style="background:#f7f9fa; font-weight:600; color:#21242c; border-bottom:2px solid #dee1e3; padding:10px 18px; text-align:left;">Resource</th>
        <th style="background:#f7f9fa; font-weight:600; color:#21242c; border-bottom:2px solid #dee1e3; padding:10px 18px; text-align:center; border-left:1px solid #e5e7eb;">Jar (x<sub>1</sub>)</th>
        <th style="background:#f7f9fa; font-weight:600; color:#21242c; border-bottom:2px solid #dee1e3; padding:10px 18px; text-align:center; border-left:1px solid #e5e7eb;">Pillar (x<sub>2</sub>)</th>
        <th style="background:#f7f9fa; font-weight:600; color:#21242c; border-bottom:2px solid #dee1e3; padding:10px 18px; text-align:center; border-left:1px solid #e5e7eb;">Available</th>
      </tr>
      <tr>
        <td style="padding:10px 18px; border-bottom:1px solid #dee1e3;">Wax (lbs)</td>
        <td style="padding:10px 18px; text-align:center; border-left:1px solid #e5e7eb; border-bottom:1px solid #dee1e3;">1</td>
        <td style="padding:10px 18px; text-align:center; border-left:1px solid #e5e7eb; border-bottom:1px solid #dee1e3;">2</td>
        <td style="padding:10px 18px; text-align:center; border-left:1px solid #e5e7eb; border-bottom:1px solid #dee1e3;">14</td>
      </tr>
      <tr>
        <td style="padding:10px 18px;">Labor (hrs)</td>
        <td style="padding:10px 18px; text-align:center; border-left:1px solid #e5e7eb;">1</td>
        <td style="padding:10px 18px; text-align:center; border-left:1px solid #e5e7eb;">1</td>
        <td style="padding:10px 18px; text-align:center; border-left:1px solid #e5e7eb;">8</td>
      </tr>
    </table>
  </div>

  <p><b>Use the simplex method</b> to find the production quantities that maximize total profit. Set up the initial tableau, perform all necessary pivots, and enter the optimal values below.</p>

  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Optimal number of jar candles (x<sub>1</sub>):
    <div style="margin-top:12px; text-align:center;">$answerbox[0]</div>
  </div>

  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Optimal number of pillar candles (x<sub>2</sub>):
    <div style="margin-top:12px; text-align:center;">$answerbox[1]</div>
  </div>

  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Maximum profit (Z):
    <div style="margin-top:12px; text-align:center;">$answerbox[2]</div>
  </div>

  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Upload your simplex tableau work:
    <div style="margin-top:12px; text-align:center;">$answerbox[3]</div>
  </div>

</div>

// === ANSWER ===
$solutionguide
