// === NAME - DESCRIPTION: The Home Alpha and Tails - alpha = 0.10 and alpha/2 = 0.05 ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's tail areas (Try It Now 7.6): alpha = 1 - 0.90 = 0.10, alpha/2 = 0.05.
// Parts: (a) numfunc - alpha (b) numfunc - alpha/2.
// Invariant: 0.10 and 0.05 on every seed.

$anstypes = array("numfunc", "numfunc")

$answer[0] = 0.10
$answer[1] = 0.05
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
      <p><span class="term-label">Part (a) &mdash; alpha.</span> Take alpha from the confidence level:</p>
      <p>`alpha = 1 - CL = 1 - 0.90 = 0.10`</p>
      <p><span class="term-label">Part (b) &mdash; alpha/2.</span> The t-distribution is symmetric, so the leftover area divides evenly between the two tails:</p>
      <p>`alpha/2 = 0.10/2 = 0.05`</p>
      <p>The three areas have to total 1: `0.05 + 0.90 + 0.05 = 1.00`. That check catches the single most common mistake in this step, which is putting the full alpha in each tail instead of half of it &mdash; a slip that would send you to the t-value for an 80% interval while your paper still says 90%.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">For the 90% confidence interval built from the demonstration data, state the tail areas.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> How much area is in both tails combined? `alpha =`
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> How much area is in each tail? `alpha/2 =`
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
