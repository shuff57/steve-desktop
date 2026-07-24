// === NAME - DESCRIPTION: 6.1 Simple Interest and Discount ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Scenario randomization
$contexts = array(
  array("borrows", "personal loan", "car repair"),
  array("invests", "savings account", "vacation fund"),
  array("borrows", "short-term loan", "home improvement"),
  array("deposits", "certificate of deposit", "emergency fund"),
  array("borrows", "business loan", "new equipment")
)
$ci = rand(0, 4)
$ctx_verb = $contexts[$ci][0]
$ctx_type = $contexts[$ci][1]
$ctx_goal = $contexts[$ci][2]

// Part A: I = Prt
$P = rand(5, 50) * 100
$rate_pcts = array(3,4,5,6,7,8,9,10,12,15)
$ri = rand(0, 9)
$rate_pct = $rate_pcts[$ri]
$r = $rate_pct / 100
$time_choices = array(1, 1.5, 2, 2.5, 3, 4, 5)
$ti = rand(0, 6)
$t = $time_choices[$ti]
$I = round($P * $r * $t, 2)
$A = round($P + $I, 2)

// Time display string
if ($t == 1) {
  $t_disp = "1 year"
} elseif ($t == 1.5) {
  $t_disp = "18 months"
} elseif ($t == 2) {
  $t_disp = "2 years"
} elseif ($t == 2.5) {
  $t_disp = "30 months"
} elseif ($t == 3) {
  $t_disp = "3 years"
} elseif ($t == 4) {
  $t_disp = "4 years"
} else {
  $t_disp = "5 years"
}

// Part B: P = A2 / (1 + r2 * t2)
$A2_choices = array(1200,1500,2000,2500,3000,4000,5000,6000,8000,10000)
$A2 = $A2_choices[rand(0,9)]
$rate2_pcts = array(4,5,6,8,9,10)
$rate2_pct = $rate2_pcts[rand(0,5)]
$r2 = $rate2_pct / 100
$t2 = rand(1,5)
$denom2 = 1 + $r2 * $t2
$P2 = round($A2 / $denom2, 2)

// Part C: Simple Discount -- D = Mdt, proceeds = M - D (360-day year)
$M_choices = array(1000,2000,5000,10000,20000)
$M = $M_choices[rand(0,4)]
$disc_pcts = array(4,5,6,8,10)
$disc_pct = $disc_pcts[rand(0,4)]
$d = $disc_pct / 100
$day_choices = array(90,120,180,270)
$days = $day_choices[rand(0,3)]
$t3 = $days / 360
$D = round($M * $d * $t3, 2)
$proceeds = round($M - $D, 2)

// Answer setup
$anstypes = array("number","number","number")
$answer[0] = $I
$answer[1] = $P2
$answer[2] = $proceeds
$abstolerance = 0.01
$reqdecimals = "r2"
$ansprompt[0] = "Interest earned: $"
$ansprompt[1] = "Present value: $"
$ansprompt[2] = "Proceeds: $"

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
            <td style="text-align:center;"><b>Part A<br>`I = Prt`</b></td>
            <td>`I = ' . $P . ' times ' . $r . ' times ' . $t . ' = ' . $I . '`
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Interest `I = $' . $I . '`</b>
              </div>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Part B<br>`P = A/(1+rt)`</b></td>
            <td>`1 + rt = 1 + ' . $r2 . ' times ' . $t2 . ' = ' . $denom2 . '`<br>
            `P = ' . $A2 . ' / ' . $denom2 . ' = ' . $P2 . '`
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Present Value `P = $' . $P2 . '`</b>
              </div>
            </td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Part C<br>`"Proceeds" = M - Mdt`</b></td>
            <td class="col-check-bot">`D = ' . $M . ' times ' . $d . ' times ' . round($t3, 4) . ' = ' . $D . '`<br>
            `"Proceeds" = ' . $M . ' - ' . $D . ' = ' . $proceeds . '`
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Proceeds `= $' . $proceeds . '`</b>
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

  <p><b>Part A &mdash; Simple Interest</b></p>
  <p>A person $ctx_verb <b>$$P</b> for $ctx_goal using a $ctx_type at a simple interest rate of <b>$rate_pct%</b> per year for <b>$t_disp</b>.</p>
  <p>Find the interest.</p>
  <p>$answerbox[0]</p>

  <hr>

  <p><b>Part B &mdash; Finding Present Value</b></p>
  <p>How much money must be deposited today at a simple interest rate of <b>$rate2_pct%</b> per year to accumulate <b>$$A2</b> in <b>$t2</b> years?</p>
  <p>$answerbox[1]</p>

  <hr>

  <p><b>Part C &mdash; Simple Discount</b></p>
  <p>A $days-day promissory note has a maturity value of <b>$$M</b> and a discount rate of <b>$disc_pct%</b> per year. Using a 360-day year, what are the proceeds (the amount the borrower actually receives)?</p>
  <p>$answerbox[2]</p>

</div>

// === ANSWER ===

$solutionguide
