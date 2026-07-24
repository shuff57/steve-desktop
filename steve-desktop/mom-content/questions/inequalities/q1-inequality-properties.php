// === NAME - DESCRIPTION: Linear Inequality Properties - Identify boundary slope, intercept, line style, and shaded region ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

// Build inequality ax + by [op] c, with integer slope -intercept form
// Generate y [<= or >= or < or >] mx + b style by picking a, b, c such that
// boundary line is y = (-a/b)x + (c/b) with INTEGER slope and intercept.

$dir_choice = rand(0, 3)     // 0: <=, 1: >=, 2: <, 3: >
$dirs = array("le","ge","lt","gt")
$dirsym = array("\\le", "\\ge", "<", ">")
$dirtxt = array("≤", "≥", "<", ">")
$dir = $dirs[$dir_choice]
$dsym = $dirsym[$dir_choice]
$dtxt = $dirtxt[$dir_choice]

// Strict vs non-strict for the boundary-line answer
$is_solid = ($dir == "le" or $dir == "ge") ? 1 : 0

// Pick a clean boundary y = mx + b with integer m, b
$m = randfrom("-3,-2,-1,1,2,3")
$bint = randfrom("-4,-3,-2,-1,1,2,3,4")

// Pick coefficients so the inequality ax + by [op] c rewrites to y [op'] mx + bint
// Choose positive b coefficient B in {1,2}. Then a = -m*B, c = bint*B.
// Direction stays same because we divided by positive B.
$Bcoef = randfrom("1,2")
$Acoef = -$m * $Bcoef
$Ccoef = $bint * $Bcoef

// Build display string for "Acoef x + Bcoef y [op] Ccoef"
$Apart = ($Acoef == 1) ? "x" : (($Acoef == -1) ? "-x" : $Acoef . "x")
$Bpart = ($Bcoef == 1) ? "y" : $Bcoef . "y"
if ($Bcoef > 0 and $Acoef != 0) {
  $sign = " + "
  $left = $Apart . $sign . $Bpart
} else {
  $left = $Apart . " + " . $Bpart
}
if ($Acoef == 0) { $left = $Bpart }
$ineq_latex = $left . " " . $dsym . " " . $Ccoef

// Does the inequality region include the origin (0,0)?
// Check: A*0 + B*0 = 0 against c.
$zero_holds = 0
if ($dir == "le" and 0 <= $Ccoef) { $zero_holds = 1 }
if ($dir == "ge" and 0 >= $Ccoef) { $zero_holds = 1 }
if ($dir == "lt" and 0 <  $Ccoef) { $zero_holds = 1 }
if ($dir == "gt" and 0 >  $Ccoef) { $zero_holds = 1 }

// Answers:
//   [0] slope of boundary line (number)
//   [1] y-intercept of boundary line (number)
//   [2] line style: solid / dashed
//   [3] does (0,0) satisfy the inequality: yes / no

$anstypes = array("number","number","choices","choices")
$answer[0] = $m
$answer[1] = $bint
$answer[2] = $is_solid       // 0 -> "Dashed", 1 -> "Solid"
$answer[3] = $zero_holds     // 0 -> "No",     1 -> "Yes"

$answerformat[0] = "integer"
$answerformat[1] = "integer"
$questions[2] = "Dashed,Solid"
$questions[3] = "No,Yes"

$css_block = '
<style>
    .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
    .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
    .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; border:none; }
    .rubric-container details[open] summary { box-shadow: inset 0 -1px 0 #ccc; }
    .rubric-container summary::-webkit-details-marker { display:none; }
    .arrow-open { display:none; }
    .rubric-container details[open] .arrow-closed { display:none; }
    .rubric-container details[open] .arrow-open { display:inline; }
    .rubric-content { overflow:hidden; max-height:0; opacity:0; transition:max-height 300ms ease-out, opacity 300ms ease-out, padding 200ms ease-out; margin-top:0; background:#fafafa; box-sizing:border-box; padding:0 0.75em; }
    .rubric-container details[open] .rubric-content { max-height:2000px; opacity:1; padding:0.75em; }
    .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
    .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
    .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; user-select:text; }
    .row-colored { background:#fff9ea; }
    .col-header { width:25%; border-top-left-radius:8px; }
    .col-check { border-top-right-radius:8px; }
    .col-cat-bot { border-bottom-left-radius:8px; }
    .col-check-bot { border-bottom-right-radius:8px; }
</style>
<script>
document.addEventListener("DOMContentLoaded", function() {
  var details = document.querySelectorAll(".rubric-container details");
  details.forEach(function(det) {
    var content = det.querySelector(".rubric-content");
    det.addEventListener("toggle", function() {
      if (det.open) {
        content.style.maxHeight = content.scrollHeight + "px";
        content.style.opacity = "1";
      } else {
        content.style.maxHeight = content.scrollHeight + "px";
        content.offsetHeight;
        content.style.maxHeight = "0";
        content.style.opacity = "0";
      }
    });
    content.addEventListener("transitionend", function() {
      if (!det.open) content.style.maxHeight = null;
    });
  });
});
</script>'

$style_word = $is_solid ? "solid" : "dashed"
$style_why  = $is_solid ? "the inequality is non-strict (allows equality)" : "the inequality is strict (does not allow equality)"
$origin_word = $zero_holds ? "yes" : "no"

$solutionguide = $css_block . '
<div class="rubric-container">
  <details>
    <summary>
      <span class="arrow-closed">▸</span><span class="arrow-open">▾</span>
      Step-by-Step Solution
    </summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr>
            <th class="col-header">Step</th>
            <th class="col-check">Work</th>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Given</b></td>
            <td>`' . $ineq_latex . '`</td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Step 1</b></td>
            <td>Solve the boundary equation `' . $left . ' = ' . $Ccoef . '` for `y` to put it in slope-intercept form.<br>
            `y = ' . $m . 'x + (' . $bint . ')`<br>
            Slope = ' . $m . ', y-intercept = ' . $bint . '.</td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Step 2</b></td>
            <td>Line style: <b>' . $style_word . '</b>, because ' . $style_why . '.</td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Step 3</b></td>
            <td class="col-check-bot">Test (0,0): substitute into the original inequality and check.<br>
            `(' . $Acoef . ')(0) + (' . $Bcoef . ')(0) = 0 ' . $dsym . ' ' . $Ccoef . '` is <b>' . $origin_word . '</b>, so the region containing the origin <b>' . ($zero_holds ? "is" : "is NOT") . '</b> the solution.
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                Slope = <b>' . $m . '</b>, y-intercept = <b>' . $bint . '</b>, boundary is <b>' . $style_word . '</b>, origin satisfies: <b>' . $origin_word . '</b>.
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:Arial; font-size:medium; line-height:1.6;">
<p>Consider the linear inequality</p>
<p style="text-align:center; font-size:1.2em;">`$ineq_latex`</p>
<p>Rewrite the boundary line in slope-intercept form `y = m x + b`, then answer:</p>
<p><b>a.)</b> Slope `m` of the boundary line: $answerbox[0]</p>
<p><b>b.)</b> y-intercept `b` of the boundary line: $answerbox[1]</p>
<p><b>c.)</b> Is the boundary line solid or dashed? $answerbox[2]</p>
<p><b>d.)</b> Does the point (0, 0) satisfy the inequality? $answerbox[3]</p>
</div>


// === ANSWER ===

$solutionguide
