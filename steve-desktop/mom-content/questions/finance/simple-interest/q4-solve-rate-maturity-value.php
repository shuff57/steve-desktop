// === NAME - DESCRIPTION: 6.1 Solving for Rate and Maturity Value ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Part A: Solve for simple interest rate r given P, A (maturity value), t
$P_choices = array(500, 600, 700, 800, 900, 1000, 1200, 1500, 2000, 2500, 3000, 3500, 4000, 4500, 5000)
$r_pct_choices = array(4, 5, 6, 7, 8, 9, 10, 12, 15)
$t_choices = array(1, 2, 3, 4, 5)

$P = $P_choices[rand(0, 14)]
$r_pct = $r_pct_choices[rand(0, 8)]
$t = $t_choices[rand(0, 4)]
$r_decimal = $r_pct / 100
$A = round($P * (1 + $r_decimal * $t), 2)

// Part B: Solve for maturity value M given desired proceeds, discount rate d, days
$proceeds_choices = array(500, 1000, 1500, 2000, 2500, 3000)
$d_pct_choices = array(4, 5, 6, 8, 10, 12)
$days_choices = array(60, 90, 120, 180, 270)

$proceeds = $proceeds_choices[rand(0, 5)]
$d_pct = $d_pct_choices[rand(0, 5)]
$days = $days_choices[rand(0, 4)]
$d_decimal = $d_pct / 100
$t_frac = $days / 360
$M = round($proceeds / (1 - $d_decimal * $t_frac), 2)

// Answer setup
$anstypes = array("number", "number")
$answer[0] = round(($A / $P - 1) / $t * 100, 4)
$answer[1] = $M
$abstolerance[0] = 0.0001
$abstolerance[1] = 0.01
$reqdecimals[1] = "r2"
$ansprompt[0] = "Interest rate r = "
$ansprompt[1] = "Maturity value M = $"

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
            <td style="text-align:center;"><b>Part A<br>`r = (A/P - 1)/t`</b></td>
            <td>`P = $' . $P . '`, `A = $' . $A . '`, `t = ' . $t . '` yr<br>
            `r = (' . $A . '/' . $P . ' - 1)/' . $t . '`<br>
            `r = (' . round($A/$P, 6) . ' - 1)/' . $t . '`<br>
            `r = ' . round(($A/$P - 1), 6) . '/' . $t . ' = ' . round(($A/$P - 1)/$t, 6) . '`<br>
            Convert to percent: `times 100`
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Interest Rate `r = ' . $answer[0] . '%`</b>
              </div>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Part B<br>`M = P/(1 - dt)`</b></td>
            <td class="col-check-bot">`"Proceeds" = $' . $proceeds . '`, `d = ' . $d_pct . '%`, days `= ' . $days . '`<br>
            `t = ' . $days . '/360 = ' . round($t_frac, 6) . '` yr<br>
            `1 - dt = 1 - ' . $d_decimal . ' times ' . round($t_frac, 6) . ' = ' . round(1 - $d_decimal * $t_frac, 6) . '`<br>
            `M = ' . $proceeds . '/' . round(1 - $d_decimal * $t_frac, 6) . ' = ' . $M . '`
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Maturity Value `M = $' . $M . '`</b>
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

  <p><b>Part A &mdash; Solving for the Interest Rate</b></p>
  <p>A loan of <b>$$P</b> accumulates to <b>$$A</b> after <b>$t</b> year(s) at simple interest. What is the annual interest rate? Give your answer as a percent rounded to 4 decimal places.</p>
  <p>$answerbox[0] %</p>

  <hr>

  <p><b>Part B &mdash; Finding the Maturity Value (Banker&#39;s Discount)</b></p>
  <p>A borrower needs <b>$$proceeds</b> in proceeds today. A bank offers a simple discount note with an annual discount rate of <b>$d_pct%</b> for <b>$days</b> days (360-day year). What must the maturity value <b>M</b> of the note be?</p>
  <p>$answerbox[1]</p>

</div>

// === ANSWER ===

$solutionguide
