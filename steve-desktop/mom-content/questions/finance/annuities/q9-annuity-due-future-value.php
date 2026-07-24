// === NAME - DESCRIPTION: 6.3 Annuity Due (Future Value) ===
// === SET QUESTION TYPE TO: number ===

// === COMMON CONTROL ===

loadlibrary("finance")

// Randomization choices
$m_choices = array(100, 150, 200, 250, 300, 400, 500)
$rate_pct_choices = array(4, 5, 6, 8, 9, 10, 12)
$n_choices = array(12, 4, 2, 1)
$n_labels = array("monthly","quarterly","semi-annually","annually")
$t_choices = array(3, 4, 5, 6, 8, 10, 15, 20)

// Random selections
$m = $m_choices[rand(0, count($m_choices)-1)]
$rate_pct = $rate_pct_choices[rand(0, count($rate_pct_choices)-1)]
$ni = rand(0, count($n_choices)-1)
$t = $t_choices[rand(0, count($t_choices)-1)]

// Derived values
$n = $n_choices[$ni]
$n_label = $n_labels[$ni]
$bigN = $n * $t
$FV_due = round(futureValue($rate_pct, $bigN + 1, $n, -$m, 0) - $m, 2)

// Answer setup
$anstypes = array("number")
$answer[0] = $FV_due
$abstolerance = 0.01
$reqdecimals = "r2"
$ansprompt[0] = "Future value FV = $"

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
            <td class="col-cat-bot" style="text-align:center;"><b>Annuity Due<br>`A = m[((1+r/n)^{nt+1} - 1)/(r/n)] - m`</b></td>
            <td class="col-check-bot">
              `m = $' . $m . '`, `r = ' . $rate_pct . '%`, `n = ' . $n . '` (' . $n_label . '), `t = ' . $t . '` yr<br>
              `nt = ' . $bigN . '`, so `nt + 1 = ' . ($bigN+1) . '`<br>
              `r/n = ' . ($rate_pct/100) . '/' . $n . ' = ' . round(($rate_pct/100)/$n, 8) . '`<br>
              `(1 + r/n)^{nt+1} = (1 + ' . round(($rate_pct/100)/$n, 8) . ')^{' . ($bigN+1) . '} = ' . round((1 + ($rate_pct/100)/$n)^($bigN+1), 6) . '`<br>
              `A = ' . $m . ' times (' . round((1 + ($rate_pct/100)/$n)^($bigN+1), 6) . ' - 1)/' . round(($rate_pct/100)/$n, 8) . ' - ' . $m . '`
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Future Value `A = $' . $FV_due . '`</b>
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

  <p><b>Annuity Due (Future Value)</b></p>
  <p>You deposit <b>$$m</b> per period into an account earning <b>$rate_pct%</b> per year, compounded <b>$n_label</b>. Deposits are made at the <b>beginning</b> of each period (annuity due). What is the future value of this annuity after <b>$t</b> years?</p>
  <p>$answerbox[0]</p>

</div>

// === ANSWER ===

$solutionguide
