// === COMMON CONTROL ===
// Section 6.4: Loan Amortization: monthly payment + total interest paid

loadlibrary("finance")

// Scenario context
$contexts = array(
  array("car loan", "buys a car", "car"),
  array("home improvement loan", "renovates their home", "home improvement project"),
  array("student loan", "finances school expenses", "education"),
  array("personal loan", "borrows money", "major purchase"),
  array("small business loan", "starts a business", "business")
)
$ci = rand(0, count($contexts)-1)
$ctx_type = $contexts[$ci][0]
$ctx_action = $contexts[$ci][1]
$ctx_purpose = $contexts[$ci][2]

// Loan principal: realistic dollar amounts
$PV_choices = array(5000, 8000, 10000, 12000, 15000, 18000, 20000, 25000)
$PV = $PV_choices[rand(0, count($PV_choices)-1)]

// Annual rate
$rate_pcts = array(4, 5, 6, 7, 8, 9, 10, 12)
$rate_pct = $rate_pcts[rand(0, count($rate_pcts)-1)]

// Term in years (always monthly compounding for amortization)
$t_choices = array(2, 3, 4, 5, 6)
$t = $t_choices[rand(0, count($t_choices)-1)]
$n = 12
$n_label = "monthly"
$bigN = $n * $t

// Monthly payment (loan: PV given, FV = 0)
$monthly_pmt = round(abs(payment($rate_pct, $bigN, $n, $PV, 0)), 2)

// Total paid and total interest
$total_paid = round($monthly_pmt * $bigN, 2)
$total_interest = round($total_paid - $PV, 2)

// Answer setup
$anstypes = array("number", "number")
$answer[0] = $monthly_pmt
$answer[1] = $total_interest
$abstolerance[0] = 0.01
$abstolerance[1] = 0.01
$reqdecimals[0] = "r2"
$reqdecimals[1] = "r2"
$ansprompt[0] = "Monthly payment = $"
$ansprompt[1] = "Total interest paid = $"

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

$r_monthly = round($rate_pct / 100 / 12, 8)

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
            <td>
              Loan amount PV = $' . $PV . '<br>
              Annual rate r = ' . $rate_pct . '%, compounded monthly (n = 12)<br>
              Term = ' . $t . ' years &rarr; N = ' . $bigN . ' monthly payments<br>
              Find: monthly payment m, then total interest
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Part A<br>Monthly Payment</b></td>
            <td>
              Formula: m = PV &times; (r/n) / [1 &minus; (1 + r/n)<sup>&minus;N</sup>]<br>
              r/n = ' . $rate_pct . '% &divide; 12 = ' . $r_monthly . '<br>
              m = |payment(' . $rate_pct . ', ' . $bigN . ', 12, ' . $PV . ', 0)|
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Monthly Payment = $' . $monthly_pmt . '</b>
              </div>
            </td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Part B<br>Total Interest</b></td>
            <td class="col-check-bot">
              Total paid = $' . $monthly_pmt . ' &times; ' . $bigN . ' payments = $' . $total_paid . '<br>
              Total interest = Total paid &minus; Principal<br>
              = $' . $total_paid . ' &minus; $' . $PV . '
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Total Interest Paid = $' . $total_interest . '</b>
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

<p><b>Loan Amortization</b></p>
<p>A person takes out a <b>$ctx_type</b> of <b>$$PV</b> to pay for their $ctx_purpose. The loan has an annual interest rate of <b>$rate_pct%</b>, compounded <b>monthly</b>, with a term of <b>$t years</b>.</p>

<p><b>Part A.</b> What is the monthly payment?</p>
<p>$ansprompt[0] $answerbox[0]</p>

<p><b>Part B.</b> What is the total amount of interest paid over the life of the loan?</p>
<p>$ansprompt[1] $answerbox[1]</p>

</div>

// === ANSWER ===

$solutionguide
