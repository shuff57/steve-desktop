// === NAME - DESCRIPTION: Break-Even Point - Find intersection of linear cost and revenue functions ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

// Contexts (parallel arrays)
$products = array("backpacks", "yoga mats", "phone cases", "ceramic mugs", "graphic t-shirts")
$companys = array("HikeRight", "ZenMat Co.", "ShellGuard", "Kiln Works", "InkLab Apparel")
$j = rand(0, 4)
$product = $products[$j]
$company = $companys[$j]

// Build clean integer break-even
// C(x) = mc*x + b, R(x) = mr*x. Solve: mr*x = mc*x + b => x = b/(mr-mc).
// Pick (mr-mc) and break-even x such that x is a "nice" integer in 40..400.
$mc = rand(5, 14)               // marginal cost per unit
$margin = rand(3, 9)            // profit margin per unit
$mr = $mc + $margin             // selling price
$x_be = 10 * rand(4, 30)        // break-even quantity (multiple of 10)
$b = $margin * $x_be            // fixed costs => clean intersection
$rev_be = $mr * $x_be

// Build LaTeX-ready strings
$Ceq = $mc . "x + " . $b
$Req = $mr . "x"

$anstypes = array("number", "number")
$answerformat[0] = "integer"
$answerformat[1] = "integer"
$answer[0] = $x_be
$answer[1] = $rev_be

// Shared CSS & JS
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
      <p>We are asked to find the <b>break-even point</b> for ' . $company . '. At break-even, the company neither makes a profit nor loses money, so total revenue equals total cost.</p>

      <p><b>The cost and revenue functions are:</b></p>
      <p class="calc">Cost: `C(x) = ' . $Ceq . '`</p>
      <p class="calc">Revenue: `R(x) = ' . $Req . '`</p>

      <p class="step">Step 1. Set revenue equal to cost.</p>
      <p>At the break-even point, the income from sales exactly covers the cost of production. Write `R(x) = C(x)`:</p>
      <p class="calc">`' . $mr . 'x = ' . $mc . 'x + ' . $b . '`</p>

      <p class="step">Step 2. Solve the equation for `x`.</p>
      <p>Subtract `' . $mc . 'x` from both sides so the variable lives only on the left:</p>
      <p class="calc">`' . $mr . 'x - ' . $mc . 'x = ' . $b . '`</p>
      <p>Combine the like terms `' . $mr . 'x - ' . $mc . 'x = ' . $margin . 'x` (this `' . $margin . '` is the profit per unit, the difference between selling price and marginal cost):</p>
      <p class="calc">`' . $margin . 'x = ' . $b . '`</p>
      <p>Divide both sides by `' . $margin . '`:</p>
      <p class="calc">`x = ' . $b . ' \\div ' . $margin . ' = ' . $x_be . '`</p>
      <p>So the company must sell <b>' . $x_be . ' units</b> to break even.</p>

      <p class="step">Step 3. Find the revenue at the break-even point.</p>
      <p>Substitute `x = ' . $x_be . '` into the revenue function `R(x) = ' . $mr . 'x`:</p>
      <p class="calc">`R(' . $x_be . ') = ' . $mr . ' \\cdot ' . $x_be . ' = ' . $rev_be . '`</p>
      <p>(As a sanity check, plug into `C(x)` too: `C(' . $x_be . ') = ' . $mc . ' \\cdot ' . $x_be . ' + ' . $b . ' = ' . ($mc * $x_be) . ' + ' . $b . ' = ' . $rev_be . '`. Revenue and cost match, confirming break-even.)</p>

      <div class="answer-box">
        <b>Break-even quantity:</b> ' . $x_be . ' units<br>
        <b>Revenue at break-even:</b> $' . $rev_be . '
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:Arial; font-size:medium; line-height:1.6;">
<p>$company sells $product. Their daily cost (in dollars) to produce `x` units is</p>
<p style="text-align:center;">`C(x) = $Ceq`,</p>
<p>and their daily revenue (in dollars) from selling `x` units is</p>
<p style="text-align:center;">`R(x) = $Req`.</p>
<p><b>a.)</b> How many units must they sell to break even? $answerbox[0] units</p>
<p><b>b.)</b> What is the revenue (in dollars) at the break-even point? $$answerbox[1]</p>
</div>


// === ANSWER ===

$solutionguide
