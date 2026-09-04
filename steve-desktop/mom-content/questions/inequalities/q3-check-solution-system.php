// === NAME - DESCRIPTION: Check Point Satisfies System of Inequalities - Test ordered pair against two-inequality system ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

$anstypes = array("choices", "choices", "choices")
$displayformat[0] = "select"
$displayformat[1] = "select"
$displayformat[2] = "select"
$noshuffle[0] = "all"
$noshuffle[1] = "all"
$noshuffle[2] = "all"

// Build two linear inequalities of the form Ax + By [op] C and a test point (x0, y0).

$A1 = nonzerorand(-3, 3)
$B1 = randfrom("1,2,3")
$A2 = nonzerorand(-3, 3)
$B2 = randfrom("1,2,3")
if ($A1 == $A2 && $B1 == $B2) { $A2 = -$A1 }

// 0 = "<=", 1 = ">="
$d1 = rand(0, 1)
$d2 = rand(0, 1)
$d1sym = "<="
if ($d1 == 1) { $d1sym = ">=" }
$d2sym = "<="
if ($d2 == 1) { $d2sym = ">=" }

// Test point
$x0 = nonzerorand(-4, 4)
$y0 = nonzerorand(-4, 4)

// Build C so inequality (may or may not) hold at (x0, y0)
$lhs1 = $A1 * $x0 + $B1 * $y0
$lhs2 = $A2 * $x0 + $B2 * $y0

$hold1 = rand(0, 1)
$hold2 = rand(0, 1)

$delta1 = rand(2, 5)
$delta2 = rand(2, 5)

// For <= : holds when C >= lhs (shift C up by delta); fails when C < lhs (shift down).
// For >= : holds when C <= lhs (shift C down by delta); fails when C > lhs (shift up).
$C1 = $lhs1 + $delta1
if ($d1 == 0 && $hold1 == 0) { $C1 = $lhs1 - $delta1 }
if ($d1 == 1 && $hold1 == 1) { $C1 = $lhs1 - $delta1 }

$C2 = $lhs2 + $delta2
if ($d2 == 0 && $hold2 == 0) { $C2 = $lhs2 - $delta2 }
if ($d2 == 1 && $hold2 == 1) { $C2 = $lhs2 - $delta2 }

$is_solution = 0
if ($hold1 == 1 && $hold2 == 1) { $is_solution = 1 }

// Build the inequality 1 display string
$Apart1 = $A1 . "x"
if ($A1 == 1) { $Apart1 = "x" }
elseif ($A1 == -1) { $Apart1 = "-x" }
$Bvar1 = $B1 . "y"
if ($B1 == 1) { $Bvar1 = "y" }
$Bpart1 = " + " . $Bvar1
$eq1_latex = $Apart1 . $Bpart1 . " " . $d1sym . " " . $C1

// Build the inequality 2 display string
$Apart2 = $A2 . "x"
if ($A2 == 1) { $Apart2 = "x" }
elseif ($A2 == -1) { $Apart2 = "-x" }
$Bvar2 = $B2 . "y"
if ($B2 == 1) { $Bvar2 = "y" }
$Bpart2 = " + " . $Bvar2
$eq2_latex = $Apart2 . $Bpart2 . " " . $d2sym . " " . $C2

// Answers (0-based indexes for choices)
$questions[0] = "No,Yes"
$questions[1] = "No,Yes"
$questions[2] = "No (not a solution),Yes (it is a solution)"
$answer[0] = $hold1
$answer[1] = $hold2
$answer[2] = $is_solution

// Solution narrative pieces
if ($hold1 == 1) { $hold1_word = "TRUE: the point satisfies inequality 1" } else { $hold1_word = "FALSE: the point does NOT satisfy inequality 1" }
if ($hold2 == 1) { $hold2_word = "TRUE: the point satisfies inequality 2" } else { $hold2_word = "FALSE: the point does NOT satisfy inequality 2" }
if ($is_solution == 1) { $final_word = "is a solution" } else { $final_word = "is NOT a solution" }

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em 1em; background:#fafafa; }
  .sol-body p { margin: 0.6em 0; }
  .sol-body .step { font-weight:700; color:#1865f2; margin-top:1em; }
  .sol-body .calc { margin: 0.4em 0 0.4em 1.5em; font-size:17px; }
  .sol-body .answer-box { margin: 1em 0 0 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p>A point `(x, y)` is a <b>solution to a system of inequalities</b> only when it satisfies <b>every</b> inequality in the system. If even one inequality fails, the point is not a solution. So our plan is: test each inequality separately, then combine the verdicts.</p>

      <p class="step">Step 1. Check inequality 1: `' . $eq1_latex . '`.</p>
      <p>Substitute `x = ' . $x0 . '` and `y = ' . $y0 . '` into the left side `' . $A1 . 'x + ' . $B1 . 'y`:</p>
      <p class="calc">`(' . $A1 . ')(' . $x0 . ') + (' . $B1 . ')(' . $y0 . ') = ' . ($A1 * $x0) . ' + ' . ($B1 * $y0) . ' = ' . $lhs1 . '`</p>
      <p>Now compare to the right side:</p>
      <p class="calc">Is `' . $lhs1 . ' ' . $d1sym . ' ' . $C1 . '`?</p>
      <p><b>' . $hold1_word . '</b></p>

      <p class="step">Step 2. Check inequality 2: `' . $eq2_latex . '`.</p>
      <p>Substitute the same `x = ' . $x0 . '` and `y = ' . $y0 . '` into the left side `' . $A2 . 'x + ' . $B2 . 'y`:</p>
      <p class="calc">`(' . $A2 . ')(' . $x0 . ') + (' . $B2 . ')(' . $y0 . ') = ' . ($A2 * $x0) . ' + ' . ($B2 * $y0) . ' = ' . $lhs2 . '`</p>
      <p>Compare to the right side:</p>
      <p class="calc">Is `' . $lhs2 . ' ' . $d2sym . ' ' . $C2 . '`?</p>
      <p><b>' . $hold2_word . '</b></p>

      <p class="step">Step 3. Combine the verdicts.</p>
      <p>For the point to solve the SYSTEM, BOTH inequalities must hold. Looking at the two checks above, the point ' . $final_word . ' of the system.</p>
      <p>(Visually, this matches the picture: each inequality shades one half-plane. The solution set of the system is the overlap, so a point only counts if it lies in both shaded regions.)</p>

      <div class="answer-box">
        The point (' . $x0 . ', ' . $y0 . ') <b>' . $final_word . '</b> of the system.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<p style="margin:0 0 4px 0;">Consider the system of linear inequalities:</p>
<p style="margin:0 0 4px 0; text-align:center; font-size:17px;">`$eq1_latex`</p>
<p style="margin:0 0 4px 0; text-align:center; font-size:17px;">`$eq2_latex`</p>
<p style="margin:8px 0 0 0;">Test the point ($x0, $y0).</p>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Does ($x0, $y0) satisfy inequality 1? <span style="margin-left:8px;">$answerbox[0]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Does ($x0, $y0) satisfy inequality 2? <span style="margin-left:8px;">$answerbox[1]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is ($x0, $y0) a solution to the system? <span style="margin-left:8px;">$answerbox[2]</span>
</div>
</div>


// === ANSWER ===

$solutionguide
