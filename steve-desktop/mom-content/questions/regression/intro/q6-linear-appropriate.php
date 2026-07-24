// === NAME - DESCRIPTION: Linear Model Appropriate - Physics experiment with curved data ===
// === SET QUESTION TYPE TO: multiple_choice ===

// === COMMON CONTROL ===

$questions = array(
  "A linear model is not appropriate because the data follow a curved pattern.",
  "A linear model is appropriate because there is a clear relationship between the variables.",
  "A linear model is appropriate because we can always draw a straight line through any data.",
  "A linear model is not appropriate because the data have too many points."
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
      <p>When data follow a curved pattern, a straight line will systematically overpredict in some regions and underpredict in others. A linear model should only be used when the overall trend is roughly straight.</p>
      <p>Having a strong relationship between variables does not automatically mean a line is a good fit. The <b>shape</b> of the trend matters.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Key idea:</b> A linear model is appropriate when the scatterplot shows a roughly straight-line pattern, not when data curve or bend.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">In an introductory physics experiment, students measure the height of a bouncing ball over time. The scatterplot shows a strong relationship between the variables, but the data clearly follow a curved (parabolic) pattern rather than a straight line.</p>
    <p style="margin:0;">Which statement best describes whether a linear model is appropriate?</p>
  </div>
</div>


// === ANSWER ===

$solutionguide
