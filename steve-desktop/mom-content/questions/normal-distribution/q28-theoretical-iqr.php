// === NAME - DESCRIPTION: The Theoretical IQR - 1.349 sigma from the model N(129.42, 2.52) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's Theoretical-Distribution step on the pilot model N(129.42, 2.52).
// Part: (a) numfunc - IQR = 131.12 - 127.72 = 3.40.
// Invariant: ~ 3.40 on every seed.

$anstypes = array("numfunc")

$answer[0] = 3.40
$abstolerance[0] = 0.005

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
      <p><span class="term-label">The quartiles are the 25th and 75th percentiles.</span> From the model, `Q1 ~= 127.72` and `Q3 ~= 131.12`, so:</p>
      <p>`IQR = Q3 - Q1 = 131.12 - 127.72 = 3.40`</p>
      <p><span class="term-label">The model fixes the spacing.</span> The theoretical IQR is exactly `1.349 sigma` &mdash; the same multiple of sigma for every normal distribution there has ever been. Once you commit to the normal model, the spacing between the quartiles is no longer something your data gets to decide; it is fixed by the model, and your data only gets a vote on sigma.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A group ran a short pilot before the lap-time lab and sampled twelve lap times. Their theoretical model is `X ~ N(129.42, 2.52)`. The quartiles from the model are `Q1 ~= 127.72` and `Q3 ~= 131.12`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the interquartile range `IQR = Q3 - Q1`.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
