// === NAME - DESCRIPTION: Y-hat Notation - What does the hat mean in regression ===
// === SET QUESTION TYPE TO: multiple_choice ===

// === COMMON CONTROL ===

$questions = array(
  "The predicted value of the response variable based on the model",
  "The actual observed value of the response variable",
  "The average of all observed y-values in the data set",
  "The error in the model's prediction"
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
      <p>The "hat" on `hat{y}` tells us this is a <b>predicted value</b>, not something we actually observed. When we plug an x-value into the regression equation, the result is our model\'s best estimate for y.</p>
      <p>Two ways to think about `hat{y}`:</p>
      <ul>
        <li>A prediction for a single observation with that x-value</li>
        <li>An estimate of the average response among all observations with that x-value</li>
      </ul>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Key idea:</b> `hat{y}` = predicted by the model. `y` = actually observed. The hat means "estimated."
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">In a regression equation we write `hat{y} = 41 + 0.59x`. What does `hat{y}` (read "y-hat") represent?</p>
  </div>
</div>


// === ANSWER ===

$solutionguide
