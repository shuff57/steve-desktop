// === NAME - DESCRIPTION: Slope and Intercept from Standard Form - Find m and b given Ax + By = C ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

// Build Ax + By = C in standard form where dividing by -B gives integer slope and y-intercept
// We choose integer slope m and intercept b first, then set A = -m*B and C = b*B with B > 0.
$m = randfrom("-3,-2,-1,1,2,3")
$b = randfrom("-5,-4,-3,-2,-1,1,2,3,4,5")
$B = randfrom("1,2,3")

$A = -$m * $B
$C = $b * $B

// Display
$Apart = ($A == 1) ? "x" : (($A == -1) ? "-x" : $A . "x")
if ($B > 0) { $Bpart = " + " . (($B == 1) ? "y" : $B . "y") }
else        { $Bpart = " - " . (($B == -1) ? "y" : abs($B) . "y") }
if ($A == 0) { $eq_latex = (($B == 1) ? "y" : $B . "y") . " = " . $C }
else         { $eq_latex = $Apart . $Bpart . " = " . $C }

$anstypes = array("number", "number")
$answerformat[0] = "integer"
$answerformat[1] = "integer"
$answer[0] = $m
$answer[1] = $b

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

$negA = -$A
if ($negA == 1) { $negAx = "x" }
elseif ($negA == -1) { $negAx = "-x" }
else { $negAx = $negA . "x" }

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
      <p>The line is given in <b>standard form</b> `Ax + By = C`. To read the slope and y-intercept directly, we need to rearrange the equation into <b>slope-intercept form</b> `y = mx + b`, where `m` is the slope and `b` is the y-intercept.</p>

      <p><b>Starting equation:</b></p>
      <p class="calc">`' . $eq_latex . '`</p>

      <p class="step">Step 1. Isolate the `y` term.</p>
      <p>Subtract the `x` term from both sides so the right side carries only the `x` term and the constant. Here we subtract `' . $A . 'x` from both sides:</p>
      <p class="calc">`' . $B . 'y = ' . $negAx . ' + ' . $C . '`</p>
      <p>(If `A` is negative, subtracting a negative `Ax` becomes adding `|A|x` to the right side. Either way, the goal is the same: get the `y` term alone on the left.)</p>

      <p class="step">Step 2. Solve for `y` by dividing through by `' . $B . '`.</p>
      <p>The coefficient of `y` is ' . $B . ', so divide <b>every term</b> on both sides by ' . $B . ' to leave a single `y` on the left:</p>
      <p class="calc">`y = (' . $negAx . ')/' . $B . ' + ' . $C . '/' . $B . '`</p>
      <p>Simplify each fraction. Because we chose `B = ' . $B . '` to divide cleanly into both `' . $negA . '` and `' . $C . '`, the result has integer slope and integer y-intercept:</p>
      <p class="calc">`y = ' . $m . 'x + ' . $b . '`</p>

      <p class="step">Step 3. Read off `m` and `b`.</p>
      <p>Compare with the template `y = mx + b`:</p>
      <ul style="margin:0.4em 0 0.4em 1.5em;">
        <li>The coefficient of `x` is the slope: <b>m = ' . $m . '</b>.</li>
        <li>The constant term is the y-intercept: <b>b = ' . $b . '</b>.</li>
      </ul>
      <p>(Quick check: the y-intercept is where the line crosses the y-axis, i.e. where `x = 0`. Plugging `x = 0` into `y = ' . $m . 'x + ' . $b . '` gives `y = ' . $b . '`, matching our value.)</p>

      <div class="answer-box">
        Slope: <b>m = ' . $m . '</b> &nbsp;&nbsp; y-intercept: <b>b = ' . $b . '</b>
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:Arial; font-size:medium; line-height:1.6;">
<p>Find the slope and y-intercept of the line</p>
<p style="text-align:center; font-size:1.2em;">`$eq_latex`.</p>
<p><b>a.)</b> Slope `m =` $answerbox[0]</p>
<p><b>b.)</b> y-intercept `b =` $answerbox[1]</p>
</div>


// === ANSWER ===

$solutionguide
