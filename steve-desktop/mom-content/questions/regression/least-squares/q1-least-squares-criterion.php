// === NAME - DESCRIPTION: Least Squares Criterion - Why minimize squared residuals ===
// === SET QUESTION TYPE TO: multiple_choice ===

// === COMMON CONTROL ===

$questions = array(
  "It minimizes the sum of the squared residuals, which heavily penalizes large prediction errors.",
  "It minimizes the sum of the residuals, so that positive and negative errors cancel out.",
  "It minimizes the sum of the absolute residuals, because absolute value is easier to compute.",
  "It chooses the line that passes through the largest number of data points."
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
      <p>The least squares line is the line that makes the sum of the <b>squared residuals</b> as small as possible:</p>
      <p style="text-align:center;">`"minimize " sum_{i=1}^{n} (y_i - hat{y}_i)^2`</p>
      <p>Squaring matters for two reasons:</p>
      <ul>
        <li>Squaring turns every error positive, so errors above and below the line do not cancel the way they would if we just added residuals.</li>
        <li>Squaring penalizes large misses much more than small ones — being off by 4 contributes 16 to the total, while being off by 2 only contributes 4. Big errors hurt.</li>
      </ul>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Key idea:</b> "Least squares" literally means the criterion it optimizes — the smallest possible sum of squared residuals.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">Many different lines could be drawn through a scatterplot. The <b>least squares regression line</b> is a specific line chosen by a mathematical rule.</p>
    <p style="margin:0;">Which statement best describes the rule that defines the least squares line?</p>
  </div>
</div>


// === ANSWER ===

$solutionguide
