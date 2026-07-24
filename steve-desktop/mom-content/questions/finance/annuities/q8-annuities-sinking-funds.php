// === NAME - DESCRIPTION: 6.3 Annuities and Sinking Funds ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("finance")

// Part A: Ordinary Annuity Future Value
$m_choices = array(50, 100, 150, 200, 250, 300, 400, 500)
$m = $m_choices[rand(0,7)]
$rate_pcts = array(4,5,6,8,9,10,12)
$rate_pct = $rate_pcts[rand(0,6)]
$n_choices = array(12, 4, 2, 1)
$n_labels  = array("monthly","quarterly","semi-annually","annually")
$ni = rand(0, 3)
$n = $n_choices[$ni]
$n_label = $n_labels[$ni]
$t = rand(5, 30)
$bigN = $n * $t
$FV = round(futureValue($rate_pct, $bigN, $n, -$m, 0), 2)

// Part B: Sinking Fund Payment
$goal_choices = array(10000, 15000, 20000, 25000, 30000, 50000, 100000)
$goal = $goal_choices[rand(0,6)]
$r2_pcts = array(4,5,6,8)
$rate2_pct = $r2_pcts[rand(0,3)]
$n2_choices = array(12, 4)
$n2_labels  = array("monthly","quarterly")
$n2i = rand(0, 1)
$n2 = $n2_choices[$n2i]
$n2_label = $n2_labels[$n2i]
$t2 = rand(5, 20)
$bigN2 = $n2 * $t2
$pmt = round(abs(payment($rate2_pct, $bigN2, $n2, 0, $goal)), 2)

// Answer setup
$anstypes = array("number","number")
$answer[0] = $FV
$answer[1] = $pmt
$abstolerance = 0.01
$reqdecimals = "r2"
$ansprompt[0] = "Future value FV = $"
$ansprompt[1] = "Required payment = $"

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
            <td style="text-align:center;"><b>Part A<br>`A = m[((1+r/n)^{nt} - 1)/(r/n)]`</b></td>
            <td>`m = $' . $m . '`, `r = ' . $rate_pct . '%`, `n = ' . $n . '` (' . $n_label . '), `t = ' . $t . '` yr<br>
            `r/n = ' . ($rate_pct/100) . '/' . $n . ' = ' . round(($rate_pct/100)/$n, 8) . '`<br>
            `(1 + r/n)^{nt} = (1 + ' . round(($rate_pct/100)/$n, 8) . ')^{' . $bigN . '} = ' . round((1 + ($rate_pct/100)/$n)^$bigN, 6) . '`<br>
            `A = ' . $m . ' times (' . round((1 + ($rate_pct/100)/$n)^$bigN, 6) . ' - 1) / ' . round(($rate_pct/100)/$n, 8) . '`
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Future Value `A = $' . $FV . '`</b>
              </div>
            </td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Part B<br>`m = (A cdot r/n)/((1+r/n)^{nt} - 1)`</b></td>
            <td class="col-check-bot">`A = $' . $goal . '`, `r = ' . $rate2_pct . '%`, `n = ' . $n2 . '` (' . $n2_label . '), `t = ' . $t2 . '` yr<br>
            `r/n = ' . ($rate2_pct/100) . '/' . $n2 . ' = ' . round(($rate2_pct/100)/$n2, 8) . '`<br>
            `(1 + r/n)^{nt} = (1 + ' . round(($rate2_pct/100)/$n2, 8) . ')^{' . $bigN2 . '} = ' . round((1 + ($rate2_pct/100)/$n2)^$bigN2, 6) . '`<br>
            `m = ' . $goal . ' times ' . round(($rate2_pct/100)/$n2, 8) . ' / (' . round((1 + ($rate2_pct/100)/$n2)^$bigN2, 6) . ' - 1)`
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Required Payment `m = $' . $pmt . '`</b>
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

  <p><b>Part A &mdash; Ordinary Annuity (Future Value)</b></p>
  <p>You deposit <b>$$m</b> per period into an account earning <b>$rate_pct%</b> per year, compounded <b>$n_label</b>. Deposits are made at the end of each period (ordinary annuity). What is the future value of this annuity after <b>$t</b> years?</p>
  <p>$answerbox[0]</p>

  <hr>

  <p><b>Part B &mdash; Sinking Fund (Required Payment)</b></p>
  <p>You want to accumulate <b>$$goal</b> in <b>$t2</b> years by making equal deposits at the end of each period into an account earning <b>$rate2_pct%</b> per year, compounded <b>$n2_label</b>. How much must each deposit be?</p>
  <p>$answerbox[1]</p>

</div>

// === ANSWER ===

$solutionguide
