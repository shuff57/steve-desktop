// === NAME - DESCRIPTION: Interpret Slope - Years of education and salary ===
// === SET QUESTION TYPE TO: multiple_choice ===

// === COMMON CONTROL ===

$m = rand(25, 55) / 10
$b = rand(10, 25)

$questions = array(
  "For each additional year of education, the predicted annual salary increases by " . $m . " thousand dollars.",
  "For each additional year of education, the predicted annual salary decreases by " . $m . " thousand dollars.",
  "The predicted annual salary is " . $m . " thousand dollars when years of education is zero.",
  "There is a " . $m . "% chance that salary will increase with more education."
)
$answer = 0

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p>The <b>slope</b> tells us how much the predicted response changes for each one-unit increase in the explanatory variable.</p>
      <p>Here the slope is <b>' . $m . '</b>, which means: for each additional year of education, the predicted annual salary increases by ' . $m . ' thousand dollars.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Key idea:</b> The slope is a rate of change per unit of x, not a probability, and not the y-intercept.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">An economist models the relationship between years of education `x` and annual salary `y` (in thousands of dollars). The regression equation is:</p>
    <p style="margin:0 0 10px 0; text-align:center;">`hat{y} = $b + {$m}x`</p>
    <p style="margin:0;">Which of the following is the best interpretation of the <b>slope</b>?</p>
  </div>
</div>


// === ANSWER ===

$solutionguide
