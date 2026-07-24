// === NAME - DESCRIPTION: Leverage vs Influential - Classify point based on description ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices")

// Part a: point with extreme x, NOT influential (removing it barely changes the line)
$questions[0] = array(
  "High leverage but not influential.",
  "High leverage and influential.",
  "An outlier with no special effect on the line.",
  "A typical point, neither high leverage nor influential."
)
$answer[0] = 0

// Part b: point with extreme x AND removing it changes the line a lot
$questions[1] = array(
  "High leverage and influential.",
  "High leverage but not influential.",
  "An outlier with a typical x-value.",
  "A typical point, neither high leverage nor influential."
)
$answer[1] = 0

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
      <p><b>Definitions:</b></p>
      <ul>
        <li><b>High-leverage point:</b> has an extreme `x`-value relative to the rest of the data. It has the <b>potential</b> to pull the regression line strongly.</li>
        <li><b>Influential point:</b> a point whose removal noticeably changes the regression line (slope and/or intercept).</li>
      </ul>
      <p>Every influential point tends to be high leverage, but <b>not every high-leverage point is influential</b>. If an extreme-`x` point falls close to the trend of the rest of the data, removing it barely changes the line — it is high leverage but not influential.</p>
      <p><b>Part a:</b> Extreme `x`, but the line barely changes when removed &rArr; <b>high leverage but not influential</b>.</p>
      <p><b>Part b:</b> Extreme `x`, and the slope changes substantially when removed &rArr; <b>high leverage and influential</b>.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#fff4e5; border-left:4px solid #f59e0b; border-radius:0 8px 8px 0;">
        <b>Rule of thumb:</b> decide <i>leverage</i> by looking at the `x`-axis; decide <i>influence</i> by comparing the fit with and without the point.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">Two students are analyzing a scatterplot and its least squares regression line. Each identifies an unusual point and tests what happens when it is removed.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> <b>Point A</b> has an unusually large `x`-value compared to the rest of the data. When Point A is removed and the regression is refit, the slope and intercept change by almost nothing. How would you best classify Point A? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> <b>Point B</b> also has an unusually large `x`-value. When Point B is removed and the regression is refit, the slope changes from 3.4 to 1.1 and the intercept shifts substantially. How would you best classify Point B? $answerbox[1]
  </div>
</div>


// === ANSWER ===

$solutionguide
