// === NAME - DESCRIPTION: LP Word Problem Setup - Identify objective coefficients and constraint coefficients from a scenario ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

$scenarios = array(0, 1, 2)
$s = randfrom("0,1,2")

if ($s == 0) {
  $item1 = "soccer balls"
  $item2 = "basketballs"
  $r1 = "leather (sq ft)"
  $r2 = "stitching time (min)"
  $a1 = rand(2, 4)        // leather per soccer ball
  $a2 = rand(3, 5)        // leather per basketball
  $b1 = rand(8, 12)       // stitching per soccer ball
  $b2 = rand(5, 9)        // stitching per basketball
  $R1 = 20 * rand(8, 14)  // leather available
  $R2 = 30 * rand(8, 14)  // stitching min available
  $p1 = rand(5, 9)        // profit per soccer ball
  $p2 = rand(6, 10)       // profit per basketball
} elseif ($s == 1) {
  $item1 = "wooden chairs"
  $item2 = "wooden tables"
  $r1 = "lumber (board ft)"
  $r2 = "labor (hours)"
  $a1 = rand(4, 6)
  $a2 = rand(8, 12)
  $b1 = rand(2, 4)
  $b2 = rand(5, 7)
  $R1 = 10 * rand(10, 16)
  $R2 = 5 * rand(10, 16)
  $p1 = rand(15, 25)
  $p2 = rand(30, 45)
} else {
  $item1 = "regular coffee bags"
  $item2 = "premium coffee bags"
  $r1 = "Colombian beans (lb)"
  $r2 = "Brazilian beans (lb)"
  $a1 = rand(1, 2)
  $a2 = rand(3, 5)
  $b1 = rand(3, 5)
  $b2 = rand(1, 2)
  $R1 = 5 * rand(10, 16)
  $R2 = 5 * rand(10, 16)
  $p1 = rand(3, 7)
  $p2 = rand(8, 14)
}

// Build the resource table as HTML
$resourcetbl = '
<table style="border-collapse:collapse; width:100%; font-family:inherit; font-size:inherit; margin:8px 0;">
  <tr>
    <th style="background:#f7f9fa; font-weight:600; color:#21242c; border:1px solid #dee1e3; padding:10px 14px; text-align:left;">Resource</th>
    <th style="background:#f7f9fa; font-weight:600; color:#21242c; border:1px solid #dee1e3; padding:10px 14px; text-align:center;">per ' . $item1 . ' (x<sub>1</sub>)</th>
    <th style="background:#f7f9fa; font-weight:600; color:#21242c; border:1px solid #dee1e3; padding:10px 14px; text-align:center;">per ' . $item2 . ' (x<sub>2</sub>)</th>
    <th style="background:#f7f9fa; font-weight:600; color:#21242c; border:1px solid #dee1e3; padding:10px 14px; text-align:center;">Available</th>
  </tr>
  <tr>
    <td style="padding:10px 14px; border:1px solid #dee1e3;">' . $r1 . '</td>
    <td style="padding:10px 14px; border:1px solid #dee1e3; text-align:center;">' . $a1 . '</td>
    <td style="padding:10px 14px; border:1px solid #dee1e3; text-align:center;">' . $a2 . '</td>
    <td style="padding:10px 14px; border:1px solid #dee1e3; text-align:center;">' . $R1 . '</td>
  </tr>
  <tr>
    <td style="padding:10px 14px; border:1px solid #dee1e3;">' . $r2 . '</td>
    <td style="padding:10px 14px; border:1px solid #dee1e3; text-align:center;">' . $b1 . '</td>
    <td style="padding:10px 14px; border:1px solid #dee1e3; text-align:center;">' . $b2 . '</td>
    <td style="padding:10px 14px; border:1px solid #dee1e3; text-align:center;">' . $R2 . '</td>
  </tr>
  <tr>
    <td style="padding:10px 14px; border:1px solid #dee1e3;"><b>Profit per unit ($)</b></td>
    <td style="padding:10px 14px; border:1px solid #dee1e3; text-align:center;"><b>' . $p1 . '</b></td>
    <td style="padding:10px 14px; border:1px solid #dee1e3; text-align:center;"><b>' . $p2 . '</b></td>
    <td style="padding:10px 14px; border:1px solid #dee1e3; text-align:center;">—</td>
  </tr>
</table>'

// Answers: 6 numbers
$anstypes = array("number","number","number","number","number","number")
$answer[0] = $p1   // coeff of x1 in objective
$answer[1] = $p2   // coeff of x2 in objective
$answer[2] = $a1   // coeff of x1 in constraint 1
$answer[3] = $a2   // coeff of x2 in constraint 1
$answer[4] = $b1   // coeff of x1 in constraint 2
$answer[5] = $b2   // coeff of x2 in constraint 2
for ($k = 0; $k < 6; $k++) { $answerformat[$k] = "integer" }

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
            <td style="text-align:center;"><b>Variables</b></td>
            <td>Let `x_1` = number of ' . $item1 . ', `x_2` = number of ' . $item2 . '.</td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Objective</b></td>
            <td>Total profit: `Z = ' . $p1 . ' x_1 + ' . $p2 . ' x_2` &nbsp; (maximize)</td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Constraint 1 (' . $r1 . ')</b></td>
            <td>`' . $a1 . ' x_1 + ' . $a2 . ' x_2 \\le ' . $R1 . '`</td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Constraint 2 (' . $r2 . ')</b></td>
            <td class="col-check-bot">`' . $b1 . ' x_1 + ' . $b2 . ' x_2 \\le ' . $R2 . '`, with `x_1, x_2 \\ge 0`.
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                Full LP: <br>
                Maximize `Z = ' . $p1 . ' x_1 + ' . $p2 . ' x_2`<br>
                subject to `' . $a1 . ' x_1 + ' . $a2 . ' x_2 \\le ' . $R1 . '`, `' . $b1 . ' x_1 + ' . $b2 . ' x_2 \\le ' . $R2 . '`, `x_1, x_2 \\ge 0`.
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
<p>A factory makes <b>$item1</b> (`x_1` per unit) and <b>$item2</b> (`x_2` per unit). Each unit uses the resources shown below, subject to the daily limits given.</p>

$resourcetbl

<p>Set up the linear-programming problem to <b>maximize profit</b>. Fill in the missing coefficients.</p>

<p><b>Objective:</b> Maximize `Z =` $answerbox[0] `x_1` `+` $answerbox[1] `x_2`</p>

<p><b>Constraint 1 ($r1):</b> &nbsp; $answerbox[2] `x_1` `+` $answerbox[3] `x_2` `<=` $R1</p>

<p><b>Constraint 2 ($r2):</b> &nbsp; $answerbox[4] `x_1` `+` $answerbox[5] `x_2` `<=` $R2</p>

<p>with `x_1 >= 0`, `x_2 >= 0`.</p>
</div>


// === ANSWER ===

$solutionguide
