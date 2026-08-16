// === NAME - DESCRIPTION: The Outlier Fences - Q1 - 1.5(IQR) and Q3 + 1.5(IQR) for U(0,1) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's Plot-the-Data step. Parts: (a) numfunc - lower fence = 0.25 - 1.5(0.50) = -0.50
// (b) numfunc - upper fence = 0.75 + 1.5(0.50) = 1.50.
// Invariant: (a) = -0.50 and (b) = 1.50 on every seed.

$anstypes = array("numfunc", "numfunc")

$answer[0] = -0.5
$answer[1] = 1.5
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
      <p><span class="term-label">Step 1 &mdash; the IQR.</span> `IQR = Q3 - Q1 = 0.75 - 0.25 = 0.50`</p>
      <p><span class="term-label">Step 2 &mdash; the fences.</span></p>
      <p>`Q1 - 1.5(IQR) = 0.25 - 1.5(0.50) = 0.25 - 0.75 = -0.50`</p>
      <p>`Q3 + 1.5(IQR) = 0.75 + 1.5(0.50) = 0.75 + 0.75 = 1.50`</p>
      <p><span class="term-label">Step 3 &mdash; compare to the possible values.</span> The generator only ever produces values between 0 and 1, and both fences sit outside that interval. So no value this generator can produce could ever be flagged as a potential outlier. That is a real property of the uniform distribution, not a quirk of the numbers: with the data spread evenly and no tails, there is nothing far from the middle for the rule to catch.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Compute the two outlier fences for the theoretical distribution `X ~ U(0, 1)`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The lower fence.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The upper fence.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
