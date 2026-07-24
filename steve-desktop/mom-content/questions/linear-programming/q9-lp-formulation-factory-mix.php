// === NAME - DESCRIPTION: LP Formulation Factory Mix - Given a two-product factory scenario, identify the objective function coefficients, resource constraints, and non-negativity constraints. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

// Two-product factory: produces Product X and Product Y.
// Resources: labor hours, machine hours (two binding constraints).
// Objective: maximize profit.
//
// Randomization strategy:
//   - Pick a product pair from a short list (3 scenarios).
//   - Randomize per-unit profits (px, py) and resource coefficients (a1,b1,a2,b2)
//     and RHS limits (R1, R2) within ranges chosen so all values stay small integers.
//   - The constraint matrix and RHS vary independently; no clean-corner guarantee
//     is needed here because this is a formulation-only question (no solve step).
//
// Parts:
//   (a) Objective: coefficient of x  --> number, answer = px
//   (b) Objective: coefficient of y  --> number, answer = py
//   (c) Labor constraint: coeff of x, coeff of y, RHS  --> three numbers, answers = a1, b1, R1
//   (d) Machine constraint: coeff of x, coeff of y, RHS --> three numbers, answers = a2, b2, R2
//   (e) Non-negativity: choices dropdown
//
// Answer index map:
//   0 = px  (obj coeff x)
//   1 = py  (obj coeff y)
//   2 = a1  (labor coeff x)
//   3 = b1  (labor coeff y)
//   4 = R1  (labor RHS)
//   5 = a2  (machine coeff x)
//   6 = b2  (machine coeff y)
//   7 = R2  (machine RHS)
//   8 = non-negativity choice (index 0)

// ---- Product pair ----
$pairs = array(
  array("chairs", "tables"),
  array("bikes", "scooters"),
  array("desks", "bookshelves")
)
$factories = array(
  "FurniCraft",
  "VeloWorks",
  "OfficeEdge"
)
$pi = rand(0, 2)
$prodX = $pairs[$pi][0]
$prodY = $pairs[$pi][1]
$company = $factories[$pi]

// ---- Profit coefficients ----
// px in {8,10,12,15}, py in {6,8,10,12}, keep px > py for natural problem shape
$px_opts = array(10, 12, 15, 18)
$py_opts = array(6, 8, 10, 12)
$px = randfrom($px_opts)
$py = randfrom($py_opts)

// ---- Labor constraint: a1*x + b1*y <= R1 ----
// a1 in {2,3,4}, b1 in {1,2,3}, R1 in {60,72,80,90}
$a1_opts = array(2, 3, 4)
$b1_opts = array(1, 2, 3)
$R1_opts = array(60, 72, 80, 90)
$a1 = randfrom($a1_opts)
$b1 = randfrom($b1_opts)
$R1 = randfrom($R1_opts)

// ---- Machine constraint: a2*x + b2*y <= R2 ----
// a2 in {1,2,3}, b2 in {2,3,4}, R2 in {40,48,60,72}
// Keep a2 != a1 or b2 != b1 so the two constraints are visually distinct
$a2_opts = array(1, 2, 3)
$b2_opts = array(2, 3, 4)
$R2_opts = array(40, 48, 60, 72)
$a2 = randfrom($a2_opts)
$b2 = randfrom($b2_opts)
$R2 = randfrom($R2_opts)

// ---- Answer setup ----
$anstypes = array("numfunc","numfunc","numfunc","numfunc","numfunc","numfunc","numfunc","numfunc","choices")

$answerboxsize[0] = 5
$answerboxsize[1] = 5
$answerboxsize[2] = 5
$answerboxsize[3] = 5
$answerboxsize[4] = 5
$answerboxsize[5] = 5
$answerboxsize[6] = 5
$answerboxsize[7] = 5

$answer[0] = $px
$answer[1] = $py
$answer[2] = $a1
$answer[3] = $b1
$answer[4] = $R1
$answer[5] = $a2
$answer[6] = $b2
$answer[7] = $R2

// Part (e): non-negativity
$choices[8] = array(
  "x ≥ 0 and y ≥ 0",
  "x > 0 and y > 0",
  "x ≥ 0 or y ≥ 0",
  "x ≥ 0 and y ≤ 0"
)
$answer[8] = 0
$displayformat[8] = "select"
$noshuffle[8] = "all"

// ---- Solution guide ----
$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
  .lp-table { border-collapse:collapse; width:100%; margin:8px 0; font-size:14px; }
  .lp-table th { background:#f7f9fa; font-weight:600; border:1px solid #dee1e3; padding:6px 12px; text-align:center; }
  .lp-table td { padding:6px 12px; border:1px solid #dee1e3; text-align:center; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Solution Guide
    </summary>
    <div class="sol-body">
      <p><b>Step 1 — Define variables:</b><br>
      Let x = number of ' . $prodX . ' produced per week.<br>
      Let y = number of ' . $prodY . ' produced per week.</p>
      <p><b>Step 2 — Objective function:</b><br>
      Each ' . $prodX . ' earns $' . $px . ' profit; each ' . $prodY . ' earns $' . $py . ' profit.<br>
      Maximize Z = ' . $px . 'x + ' . $py . 'y</p>
      <p><b>Step 3 — Resource constraints:</b></p>
      <table class="lp-table">
        <tr>
          <th>Resource</th>
          <th>' . ucfirst($prodX) . ' (x)</th>
          <th>' . ucfirst($prodY) . ' (y)</th>
          <th>Available</th>
          <th>Constraint</th>
        </tr>
        <tr>
          <td>Labor (hrs)</td>
          <td>' . $a1 . '</td>
          <td>' . $b1 . '</td>
          <td>' . $R1 . '</td>
          <td>' . $a1 . 'x + ' . $b1 . 'y &le; ' . $R1 . '</td>
        </tr>
        <tr>
          <td>Machine (hrs)</td>
          <td>' . $a2 . '</td>
          <td>' . $b2 . '</td>
          <td>' . $R2 . '</td>
          <td>' . $a2 . 'x + ' . $b2 . 'y &le; ' . $R2 . '</td>
        </tr>
      </table>
      <p><b>Step 4 — Non-negativity:</b><br>
      You cannot produce a negative quantity: x &ge; 0 and y &ge; 0.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Complete LP formulation:</b><br>
        Maximize Z = ' . $px . 'x + ' . $py . 'y<br>
        Subject to:<br>
        &nbsp;&nbsp;' . $a1 . 'x + ' . $b1 . 'y &le; ' . $R1 . ' &nbsp;(labor)<br>
        &nbsp;&nbsp;' . $a2 . 'x + ' . $b2 . 'y &le; ' . $R2 . ' &nbsp;(machine)<br>
        &nbsp;&nbsp;x &ge; 0, y &ge; 0
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.4; color:#21242c; max-width:688px;">
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<p style="margin:0 0 6px 0;"><b>$company</b> produces two products each week: <b>$prodX</b> (x) and <b>$prodY</b> (y). Each $prodX earns <b>$$px profit</b>; each $prodY earns <b>$$py profit</b>. Two resources limit weekly production:</p>
<table style="border-collapse:collapse; width:100%; margin:4px 0;">
<tr><th style="background:#f7f9fa; font-weight:600; border:1px solid #dee1e3; padding:6px 12px; text-align:left;">Resource</th><th style="background:#f7f9fa; font-weight:600; border:1px solid #dee1e3; padding:6px 12px; text-align:center;">Per $prodX (x)</th><th style="background:#f7f9fa; font-weight:600; border:1px solid #dee1e3; padding:6px 12px; text-align:center;">Per $prodY (y)</th><th style="background:#f7f9fa; font-weight:600; border:1px solid #dee1e3; padding:6px 12px; text-align:center;">Weekly Limit</th></tr>
<tr><td style="padding:6px 12px; border:1px solid #dee1e3;">Labor (hours)</td><td style="padding:6px 12px; text-align:center; border:1px solid #dee1e3;">$a1</td><td style="padding:6px 12px; text-align:center; border:1px solid #dee1e3;">$b1</td><td style="padding:6px 12px; text-align:center; border:1px solid #dee1e3;">$R1</td></tr>
<tr><td style="padding:6px 12px; border:1px solid #dee1e3;">Machine (hours)</td><td style="padding:6px 12px; text-align:center; border:1px solid #dee1e3;">$a2</td><td style="padding:6px 12px; text-align:center; border:1px solid #dee1e3;">$b2</td><td style="padding:6px 12px; text-align:center; border:1px solid #dee1e3;">$R2</td></tr>
</table>
<p style="margin:6px 0 0 0;">Write the LP formulation by entering the missing values in each part below.</p>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a</span><b>Objective function.</b> Maximize Z = <span style="margin-left:4px;">$answerbox[0]</span> x + <span style="margin-left:4px;">$answerbox[1]</span> y
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b</span><b>Labor constraint.</b> <span style="margin-left:4px;">$answerbox[2]</span> x + <span style="margin-left:4px;">$answerbox[3]</span> y &le; <span style="margin-left:4px;">$answerbox[4]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c</span><b>Machine constraint.</b> <span style="margin-left:4px;">$answerbox[5]</span> x + <span style="margin-left:4px;">$answerbox[6]</span> y &le; <span style="margin-left:4px;">$answerbox[7]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d</span><b>Non-negativity constraints.</b> Select the correct statement: <span style="margin-left:8px;">$answerbox[8]</span>
</div>
</div>

///

$solutionguide
