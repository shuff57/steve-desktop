// === NAME - DESCRIPTION: 6.2 Comparing Future Values ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Shared deposit and time
$P_choices = array(1000, 2000, 5000, 8000, 10000)
$P = $P_choices[rand(0, 4)]
$t_choices = array(5, 8, 10, 12, 15)
$t = $t_choices[rand(0, 4)]

// Bank A: higher compounding frequency
$r1_pct_choices = array(4, 5, 6, 8, 10)
$r1_pct = $r1_pct_choices[rand(0, 4)]
$r1 = $r1_pct / 100
$n1_choices = array(12, 365)
$n1_labels = array("monthly", "daily")
$ni1 = rand(0, 1)
$n1 = $n1_choices[$ni1]
$n1_label = $n1_labels[$ni1]

// Bank B: lower compounding frequency
$r2_pct_choices = array(4, 5, 6, 8, 10)
$r2_pct = $r2_pct_choices[rand(0, 4)]
$r2 = $r2_pct / 100
$n2_choices = array(2, 4)
$n2_labels = array("semi-annually", "quarterly")
$ni2 = rand(0, 1)
$n2 = $n2_choices[$ni2]
$n2_label = $n2_labels[$ni2]

// Calculate future values: A = P(1 + r/n)^(nt)
$FV1 = round($P * (1 + $r1/$n1)^($n1*$t), 2)
$FV2 = round($P * (1 + $r2/$n2)^($n2*$t), 2)

// Determine winner (0 = Bank A, 1 = Bank B)
if ($FV1 > $FV2) {
  $winner = 0
} else {
  $winner = 1
}

// Answer setup
$anstypes = array("number", "number", "choices")
$answer[0] = $FV1
$answer[1] = $FV2
$answer[2] = $winner
$abstolerance[0] = 0.01
$abstolerance[1] = 0.01
$ansprompt[0] = "Bank A future value = $"
$ansprompt[1] = "Bank B future value = $"
$questions[2] = array("Bank A", "Bank B")

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
            <td style="text-align:center;"><b>Bank A<br>`A = P(1+r/n)^{nt}`</b></td>
            <td>`P = $' . $P . '`, `r = ' . $r1_pct . '%`, `n = ' . $n1 . '` (' . $n1_label . '), `t = ' . $t . '` yr<br>
            `A = ' . $P . '(1 + ' . $r1 . '/' . $n1 . ')^{' . ($n1*$t) . '}`
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Bank A Future Value `= $' . $FV1 . '`</b>
              </div>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Bank B<br>`A = P(1+r/n)^{nt}`</b></td>
            <td>`P = $' . $P . '`, `r = ' . $r2_pct . '%`, `n = ' . $n2 . '` (' . $n2_label . '), `t = ' . $t . '` yr<br>
            `A = ' . $P . '(1 + ' . $r2 . '/' . $n2 . ')^{' . ($n2*$t) . '}`
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Bank B Future Value `= $' . $FV2 . '`</b>
              </div>
            </td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Compare</b></td>
            <td class="col-check-bot">
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>' . ($winner == 0 ? "Bank A" : "Bank B") . ' gives the higher future value ($' . ($winner == 0 ? $FV1 : $FV2) . ' vs $' . ($winner == 0 ? $FV2 : $FV1) . ')</b>
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

  <p>You have <b>$$P</b> to deposit for <b>$t years</b>. Two banks offer different rates and compounding:</p>

  <hr>

  <p><b>Part A &mdash; Bank A</b></p>
  <p>Bank A offers <b>$r1_pct%</b> compounded <b>$n1_label</b>. What is the future value of your deposit?</p>
  <p>$answerbox[0]</p>

  <hr>

  <p><b>Part B &mdash; Bank B</b></p>
  <p>Bank B offers <b>$r2_pct%</b> compounded <b>$n2_label</b>. What is the future value of your deposit?</p>
  <p>$answerbox[1]</p>

  <hr>

  <p><b>Part C &mdash; Which Bank is Better?</b></p>
  <p>Based on your calculations, which bank gives you more money after $t years?</p>
  <p>$answerbox[2]</p>

</div>

// === ANSWER ===

$solutionguide
