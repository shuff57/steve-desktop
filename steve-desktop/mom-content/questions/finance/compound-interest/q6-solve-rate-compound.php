// === NAME - DESCRIPTION: 6.2 Solving for Rate (Compound Interest) ===
// === SET QUESTION TYPE TO: number ===

// === COMMON CONTROL ===

// Solve for interest rate r given P, A, n, t
$P_values = array(1000,2000,3000,5000,8000,10000)
$ratios = array(1.3,1.4,1.5,1.6,1.8,2.0,2.5,3.0)
$n_values = array(4,12)
$n_labels = array("quarterly","monthly")
$t_values = array(3,4,5,6,8,10)

$P = randfrom($P_values)
$ratio = randfrom($ratios)
$A = round($P * $ratio, 0)
$n_idx = rand(0, count($n_values)-1)
$n = $n_values[$n_idx]
$n_label = $n_labels[$n_idx]
$t = randfrom($t_values)

$r_decimal = $n * (($A/$P)^(1/($n*$t)) - 1)
$answer[0] = round($r_decimal * 100, 4)
$anstypes = array("number")
$ansprompt[0] = "Annual interest rate r = "

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
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Solve for r<br>`r = n[(A/P)^{1/{nt}} - 1]`</b></td>
            <td class="col-check-bot">
              <p>Given: `P = $' . $P . '`, `A = $' . $A . '`, `n = ' . $n . '` (' . $n_label . '), `t = ' . $t . '` years</p>
              <p>Step 1: `A/P = ' . $A . '/' . $P . ' = ' . round($A/$P, 6) . '`</p>
              <p>Step 2: `nt = ' . $n . ' times ' . $t . ' = ' . ($n*$t) . '`</p>
              <p>Step 3: `(A/P)^{1/{nt}} = ' . round($A/$P, 6) . '^{1/{' . ($n*$t) . '}} = ' . round(($A/$P)^(1/($n*$t)), 6) . '`</p>
              <p>Step 4: `' . round(($A/$P)^(1/($n*$t)), 6) . ' - 1 = ' . round(($A/$P)^(1/($n*$t)) - 1, 6) . '`</p>
              <p>Step 5: `r = ' . $n . ' times ' . round(($A/$P)^(1/($n*$t)) - 1, 6) . ' = ' . round($n * (($A/$P)^(1/($n*$t)) - 1), 6) . '`</p>
              <p>Step 6: Convert to percent: `r = ' . $answer[0] . '%`</p>
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Answer: `r = ' . $answer[0] . '%`</b>
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

  <p><b>Solving for the Interest Rate</b></p>
  <p>An investment of <b>$$P</b> grows to <b>$$A</b> when compounded <b>$n_label</b> over <b>$t years</b>. Use the compound interest formula to solve for the annual interest rate <b>r</b> (as a percentage). Round your answer to 4 decimal places.</p>
  <p>$answerbox[0] %</p>

</div>

// === ANSWER ===

$solutionguide
