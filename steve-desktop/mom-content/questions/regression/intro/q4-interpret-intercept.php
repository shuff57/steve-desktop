// === NAME - DESCRIPTION: Interpret Y-Intercept - Homework hours and exam score ===
// === SET QUESTION TYPE TO: multiple_choice ===

// === COMMON CONTROL ===

$b = rand(30, 50)
$m = rand(15, 35) / 10

$questions = array(
  "When hours of homework per week is 0, the predicted final exam score is " . $b . " points.",
  "For each additional hour of homework, the final exam score increases by " . $b . " points.",
  "The average final exam score in the data set is " . $b . " points.",
  "The final exam score will never be less than " . $b . " points."
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
      <p>The <b>y-intercept</b> is the predicted value of `y` when `x = 0`. Plugging in `x = 0`:</p>
      <p>`hat{y} = ' . $b . ' + ' . $m . '(0) = ' . $b . '`</p>
      <p>In context: a student who does 0 hours of homework per week has a predicted exam score of ' . $b . ' points.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Note:</b> The y-intercept may not always make practical sense, but it is the starting point of the model when `x = 0`.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A teacher models the relationship between hours of homework per week `x` and final exam score `y` (points). The regression equation is:</p>
    <p style="margin:0 0 10px 0; text-align:center;">`hat{y} = $b + {$m}x`</p>
    <p style="margin:0;">Which of the following is the best interpretation of the <b>y-intercept</b>?</p>
  </div>
</div>


// === ANSWER ===

$solutionguide
