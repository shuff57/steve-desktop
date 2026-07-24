// === NAME - DESCRIPTION: Simplex Identify Pivot - Identify pivot column, pivot row, and minimum ratio from a simplex tableau ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

/* ---------- 1. Answer setup ---------- */
// Tableau (hardcoded):
//   BV  | x1  x2  s1  s2 | RHS
//   s1  |  2   1   1   0 |  12
//   s2  |  1   3   0   1 |  18
//   Z   | -6  -4   0   0 |   0
//
// Pivot column: x1 (most negative indicator -6) -> choice index 0
// Pivot row: s1 (ratio 12/2=6 < 18/1=18) -> choice index 0
// Minimum ratio: 6

$anstypes = array("choices", "choices", "number")
$answerformat[2] = "integer"

// Part (a): which column is the pivot column?
// Options: x1, x2, s1, s2  -- correct: x1 (1-indexed: answer = 1)
$choices[0] = array("x1", "x2", "s1", "s2")
$displayformat[0] = "select"
$noshuffle[0] = "all"
$answer[0] = 1

// Part (b): which row is the pivot row?
// Options: s1 row, s2 row, Z row, Cannot determine  -- correct: s1 row (1-indexed: answer = 1)
$choices[1] = array("s1 row", "s2 row", "Z row", "Cannot determine")
$displayformat[1] = "select"
$noshuffle[1] = "all"
$answer[1] = 1

// Part (c): minimum ratio
$answer[2] = 6

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
  .sx-table .pivot-col { background:#fff3cd; }
  .sx-table .pivot-row { background:#d1ecf1; }
  .sx-table .pivot-cell { background:#c3e6cb; font-weight:700; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><b>(a) Pivot column</b> -- most-negative-indicator rule:</p>
      <p>Look at the objective (Z) row for the most negative value. The indicators are -6 (x<sub>1</sub>) and -4 (x<sub>2</sub>). The most negative is <b>-6</b>, so <b>x<sub>1</sub> is the pivot column</b> (entering variable).</p>
      <p><b>(b) Pivot row</b> -- minimum-ratio test:</p>
      <p>Divide each RHS by the corresponding positive entry in the pivot column:</p>
      <ul style="margin:4px 0 8px 0;">
        <li>s<sub>1</sub> row: 12 / 2 = <b>6</b></li>
        <li>s<sub>2</sub> row: 18 / 1 = 18</li>
      </ul>
      <p>The minimum ratio is 6, so <b>s<sub>1</sub> is the pivot row</b> (departing variable). The pivot element is highlighted below.</p>
      <table class="sx-table">
        <tr><th>BV</th><th class="pivot-col">x<sub>1</sub></th><th>x<sub>2</sub></th><th>s<sub>1</sub></th><th>s<sub>2</sub></th><th>RHS</th><th>Ratio</th></tr>
        <tr class="pivot-row"><td>s<sub>1</sub></td><td class="pivot-cell">2</td><td>1</td><td>1</td><td>0</td><td>12</td><td><b>12/2 = 6</b></td></tr>
        <tr><td>s<sub>2</sub></td><td class="pivot-col">1</td><td>3</td><td>0</td><td>1</td><td>18</td><td>18/1 = 18</td></tr>
        <tr><td>Z</td><td class="pivot-col">-6</td><td>-4</td><td>0</td><td>0</td><td>0</td><td>--</td></tr>
      </table>
      <p><b>(c) Minimum ratio:</b> 6.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Pivot column: x<sub>1</sub> &nbsp;|&nbsp; Pivot row: s<sub>1</sub> &nbsp;|&nbsp; Minimum ratio: 6
      </div>
    </div>
  </details>
</div>'

/* ---------- 3. Question Text ---------- */
$questiontext = '
<div style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <p>Use the simplex tableau below to answer the questions about the next pivot step.</p>
  <style>
    .sx-q { border-collapse:collapse; margin:12px 0; font-family:ui-monospace,SFMono-Regular,Menlo,monospace; font-size:15px; }
    .sx-q th, .sx-q td { border:1px solid #dee1e3; padding:8px 18px; text-align:center; }
    .sx-q th { background:#f7f9fa; font-weight:600; color:#21242c; border-bottom:2px solid #dee1e3; }
  </style>
  <table class="sx-q">
    <tr><th>BV</th><th>x<sub>1</sub></th><th>x<sub>2</sub></th><th>s<sub>1</sub></th><th>s<sub>2</sub></th><th>RHS</th></tr>
    <tr><td>s<sub>1</sub></td><td>2</td><td>1</td><td>1</td><td>0</td><td>12</td></tr>
    <tr><td>s<sub>2</sub></td><td>1</td><td>3</td><td>0</td><td>1</td><td>18</td></tr>
    <tr><td>Z</td><td>-6</td><td>-4</td><td>0</td><td>0</td><td>0</td></tr>
  </table>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which column is the pivot column (the entering variable)?
    <div style="margin-top:12px; text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which row is the pivot row (the departing variable)?
    <div style="margin-top:12px; text-align:center;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is the minimum ratio for the pivot row?
    <div style="margin-top:12px; text-align:center;">$answerbox[2]</div>
  </div>
</div>'

//question text

$questiontext

///

$solutionguide
