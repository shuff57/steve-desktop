// === NAME - DESCRIPTION: Slack Variable Conversion - Convert two LP constraint inequalities to equations with slack variables ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

// Two constraint inequalities of the form
//   a x1 + b x2 <= c
//   d x1 + e x2 <= f
// Convert to:
//   a x1 + b x2 + s1 = c
//   d x1 + e x2 + s2 = f
// We ask for the coefficient of each slack variable (always +1), and the RHS values (unchanged).
// To make it slightly less trivial, we also ask for the coefficients of x1 and x2 to verify
// students copy the inequality correctly into equation form.

$a = rand(2, 6)
$b = rand(2, 6)
$c = 5 * rand(4, 14)
$d = rand(2, 6)
$e = rand(2, 6)
$f = 5 * rand(4, 14)

// Answers: 6 numbers per constraint -> [a, b, slack1=1, c, d, e, slack2=1, f] but
// students only need to fill in the slack coefficient and RHS for each. Make it 4 boxes.
//   [0] slack coefficient in eq 1   (=1)
//   [1] RHS of eq 1                 (=c)
//   [2] slack coefficient in eq 2   (=1)
//   [3] RHS of eq 2                 (=f)

$anstypes = array("number","number","number","number")
$answer[0] = 1
$answer[1] = $c
$answer[2] = 1
$answer[3] = $f
for ($k = 0; $k < 4; $k++) { $answerformat[$k] = "integer" }

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
            <td style="text-align:center;"><b>Concept</b></td>
            <td>A slack variable absorbs the "leftover" amount of a resource. For any `\\le` constraint, add a non-negative slack variable to the left side to make the inequality an equation. The slack variable always has coefficient <b>+1</b>, and the right-hand side is unchanged.</td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Constraint 1</b></td>
            <td>`' . $a . ' x_1 + ' . $b . ' x_2 \\le ' . $c . '` &nbsp; → &nbsp; `' . $a . ' x_1 + ' . $b . ' x_2 + s_1 = ' . $c . '`<br>
            Coefficient of `s_1` = <b>1</b>; RHS = <b>' . $c . '</b>.</td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Constraint 2</b></td>
            <td class="col-check-bot">`' . $d . ' x_1 + ' . $e . ' x_2 \\le ' . $f . '` &nbsp; → &nbsp; `' . $d . ' x_1 + ' . $e . ' x_2 + s_2 = ' . $f . '`<br>
            Coefficient of `s_2` = <b>1</b>; RHS = <b>' . $f . '</b>.
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                Both slack coefficients are <b>+1</b>; right-hand sides stay <b>' . $c . '</b> and <b>' . $f . '</b>.<br>
                Also: `s_1 \\ge 0` and `s_2 \\ge 0`.
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
<p>Convert each of the following inequality constraints to an equation by introducing a non-negative slack variable. Use `s_1` for the first constraint and `s_2` for the second.</p>

<p><b>Constraint 1:</b> &nbsp; `$a x_1 + $b x_2 \le $c`</p>

<p style="margin-left:1em;">Equation: &nbsp; `$a x_1 + $b x_2 +$ $answerbox[0]$\, s_1 =$ $answerbox[1]</p>

<p><b>Constraint 2:</b> &nbsp; `$d x_1 + $e x_2 \le $f`</p>

<p style="margin-left:1em;">Equation: &nbsp; `$d x_1 + $e x_2 +$ $answerbox[2]$\, s_2 =$ $answerbox[3]</p>
</div>


// === ANSWER ===

$solutionguide
