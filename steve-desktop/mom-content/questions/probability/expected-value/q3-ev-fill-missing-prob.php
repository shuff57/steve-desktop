// === NAME - DESCRIPTION: Find Missing Probability and Compute E(X) - Use sum-to-1 to fill the table, then evaluate E(X) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc", "numfunc")

// Each scenario gives 4 X values and 4 probabilities with one slot missing (shown as ? in the question).
// Position 0..3 indicates which slot is missing; the others are fixed.
// Missing probability and E(X) are precomputed.

// Scenario 0: X = 0,1,2,3; P = 0.2, 0.3, ?, 0.1 -> missing 0.4; E(X) = 1.4
// Scenario 1: X = -1,0,1,2; P = 0.25, ?, 0.30, 0.15 -> missing 0.30; E(X) = 0.35
// Scenario 2: X = 1,2,3,4; P = 0.10, 0.20, 0.40, ? -> missing 0.30; E(X) = 2.90
// Scenario 3: X = 0,5,10,15; P = ?, 0.40, 0.30, 0.10 -> missing 0.20; E(X) = 6.5
// Scenario 4: X = 2,4,6,8; P = 0.10, ?, 0.30, 0.20 -> missing 0.40; E(X) = 5.2

$x0s   = array(0,  -1, 1,  0,  2)
$x1s   = array(1,   0, 2,  5,  4)
$x2s   = array(2,   1, 3,  10, 6)
$x3s   = array(3,   2, 4,  15, 8)

// Probability display strings (use "?" for the missing slot)
$p0disp = array("0.2",  "0.25", "0.10", "?",    "0.10")
$p1disp = array("0.3",  "?",    "0.20", "0.40", "?")
$p2disp = array("?",    "0.30", "0.40", "0.30", "0.30")
$p3disp = array("0.1",  "0.15", "?",    "0.10", "0.20")

$missingProbs = array(0.4, 0.30, 0.30, 0.20, 0.40)
$evs          = array(1.4, 0.35, 2.90, 6.5,  5.2)

// Plain calculation strings for the solution
$missingCalc = array(
  "1 - (0.2 + 0.3 + 0.1) = 1 - 0.6 = 0.4",
  "1 - (0.25 + 0.30 + 0.15) = 1 - 0.70 = 0.30",
  "1 - (0.10 + 0.20 + 0.40) = 1 - 0.70 = 0.30",
  "1 - (0.40 + 0.30 + 0.10) = 1 - 0.80 = 0.20",
  "1 - (0.10 + 0.30 + 0.20) = 1 - 0.60 = 0.40"
)
$evCalc = array(
  "(0)(0.2) + (1)(0.3) + (2)(0.4) + (3)(0.1) = 0 + 0.3 + 0.8 + 0.3 = 1.4",
  "(-1)(0.25) + (0)(0.30) + (1)(0.30) + (2)(0.15) = -0.25 + 0 + 0.30 + 0.30 = 0.35",
  "(1)(0.10) + (2)(0.20) + (3)(0.40) + (4)(0.30) = 0.10 + 0.40 + 1.20 + 1.20 = 2.90",
  "(0)(0.20) + (5)(0.40) + (10)(0.30) + (15)(0.10) = 0 + 2 + 3 + 1.5 = 6.5",
  "(2)(0.10) + (4)(0.40) + (6)(0.30) + (8)(0.20) = 0.2 + 1.6 + 1.8 + 1.6 = 5.2"
)

$picked = jointrandfrom($x0s, $x1s, $x2s, $x3s, $p0disp, $p1disp, $p2disp, $p3disp, $missingProbs, $evs, $missingCalc, $evCalc)
$x0 = $picked[0]
$x1 = $picked[1]
$x2 = $picked[2]
$x3 = $picked[3]
$pd0 = $picked[4]
$pd1 = $picked[5]
$pd2 = $picked[6]
$pd3 = $picked[7]
$answer[0] = $picked[8]
$answer[1] = $picked[9]
$mCalc = $picked[10]
$eCalc = $picked[11]
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><b>Part a:</b> All probabilities in a valid distribution must sum to 1. The missing probability is</p>
      <p>$mCalc</p>
      <p>So the missing probability is <b>' . $answer[0] . '</b>.</p>
      <p><b>Part b:</b> Use `E(X) = sum x_i cdot P(X = x_i)` with all four rows of the completed table.</p>
      <p>$eCalc</p>
      <p>So <b>`E(X) = ' . $answer[1] . '`</b>.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A discrete random variable `X` has the following distribution. One probability is missing.</p>
    <table style="border-collapse:collapse; margin:0 auto; font-size:16px;">
      <tr>
        <th style="border:1px solid #21242c; padding:6px 16px; background:#f0f4ff;">`X`</th>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$x0</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$x1</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$x2</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$x3</td>
      </tr>
      <tr>
        <th style="border:1px solid #21242c; padding:6px 16px; background:#f0f4ff;">`P(X)`</th>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$pd0</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$pd1</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$pd2</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$pd3</td>
      </tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the missing probability. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Compute `E(X)`. $answerbox[1]
  </div>
</div>


// === ANSWER ===

$solutionguide
