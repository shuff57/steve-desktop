// === NAME - DESCRIPTION: Line Passes Through Means - Identify guaranteed point on regression line ===
// === SET QUESTION TYPE TO: multiple_choice ===

// === COMMON CONTROL ===

$questions = array(
  "The point `(bar{x}, bar{y})`: the point of averages.",
  "The point `(0, 0)`: the origin.",
  "The point with the largest x-value in the dataset.",
  "The point with the largest y-value in the dataset."
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
      <p>The least squares line is built so that it always passes through the <b>point of averages</b> `(bar{x}, bar{y})`. You can see this from the intercept formula:</p>
      <p style="text-align:center;">`b_0 = bar{y} - b_1 bar{x}`</p>
      <p>Plug `x = bar{x}` into the regression equation:</p>
      <p>`hat{y} = b_0 + b_1 bar{x} = (bar{y} - b_1 bar{x}) + b_1 bar{x} = bar{y}`</p>
      <p>So when `x = bar{x}`, the predicted `hat{y}` equals `bar{y}`: the line goes through `(bar{x}, bar{y})`.</p>
      <p>It does <b>not</b> have to pass through the origin, nor through any specific raw data point.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Key idea:</b> The least squares line always passes through `(bar{x}, bar{y})`: the center of the data cloud.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Which point is <b>guaranteed</b> to lie on every least squares regression line, no matter what the dataset looks like?</p>
  </div>
</div>


// === ANSWER ===

$solutionguide
