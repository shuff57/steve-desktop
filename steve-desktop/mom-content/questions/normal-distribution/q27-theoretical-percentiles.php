// === NAME - DESCRIPTION: The Theoretical Percentiles - P15 and P85 from the model N(129.42, 2.52) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's Theoretical-Distribution step on the pilot model N(129.42, 2.52).
// Parts: (a) numfunc - P15 = 129.4167 - 1.0364(2.5207) ~ 126.80
// (b) numfunc - P85 = 129.4167 + 1.0364(2.5207) ~ 132.03.
// Invariant: ~ 126.80 and ~ 132.03 on every seed.

loadlibrary("stats");

$anstypes = array("numfunc", "numfunc")

$answer[0] = 126.80
$answer[1] = 132.03
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
  .term-label { font-weight:700; color:#1865f2; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><span class="term-label">Every percentile is the mean plus a fixed number of standard deviations.</span> The z-score with 15% of the area below it is `-1.0364`, and by symmetry the 85th percentile sits at `+1.0364`.</p>
      <p><span class="term-label">Part (a) &mdash; the 15th percentile.</span></p>
      <p>`P15 = 129.4167 - 1.0364(2.5207) = 129.4167 - 2.6125 ~= 126.80`</p>
      <p><span class="term-label">Part (b) &mdash; the 85th percentile.</span></p>
      <p>`P85 = 129.4167 + 1.0364(2.5207) = 129.4167 + 2.6125 ~= 132.03`</p>
      <p>The 85th percentile means the model predicts 85% of all the racer\'s laps come in under 132.03 seconds &mdash; a claim about every lap she will ever run, not about the ones you happened to sample.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A group ran a short pilot before the lap-time lab and sampled twelve lap times. Their theoretical model is `X ~ N(129.42, 2.52)`. Find the percentiles from the model.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The 15th percentile.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The 85th percentile.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
