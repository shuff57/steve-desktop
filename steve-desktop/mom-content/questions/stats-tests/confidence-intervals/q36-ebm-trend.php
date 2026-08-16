// === NAME - DESCRIPTION: The EBM Trend - what happens to the error bound and the width as the confidence level rises ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's trend question (Try It Now 7.9 step 3). Parts: (a) choices - what happens to the
// EBM as the confidence level increases (b) choices - what happens to the width.
// Invariant: both answers are constant on every seed.

$anstypes = array("choices", "choices")

$questions[0] = array(
  "It increases &mdash; raising the confidence level means demanding more area under the middle of the t-curve, which forces the boundaries further out into the tails",
  "It decreases &mdash; raising the confidence level means the interval can be tighter",
  "It stays the same &mdash; the confidence level does not affect the error bound"
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  "It increases &mdash; the width grows right along with the error bound",
  "It decreases &mdash; the width shrinks as the error bound grows",
  "It stays the same &mdash; the width does not depend on the error bound"
)
$answer[1] = 0
$noshuffle[1] = "all"

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
      <p><span class="term-label">Part (a) &mdash; the EBM.</span> As the confidence level increases, the EBM increases. Higher confidence means insisting that more of the t-curve\'s area sits inside the interval, which pushes the cutoffs further into the tails and makes `t_(alpha/2)` larger; the standard error never moved.</p>
      <p><span class="term-label">Part (b) &mdash; the width.</span> The width grows right along with the error bound &mdash; the 99% interval is four times as wide as the 50% one.</p>
      <p><span class="term-label">The trade.</span> Certainty and precision pull against each other. The 99% interval is very likely to contain `mu` and spans over $100,000, which is almost useless to a buyer; the 50% interval pins the mean to a $26,000 range and is wrong about half the time. Ninety and ninety-five percent are conventions because they sit in the usable middle, not because there is anything mathematically special about them.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">The demonstration data produced error bounds of about $13,023 at 50%, $24,965 at 80%, $38,817 at 95%, and $52,115 at 99% confidence.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What happens to the EBM as the confidence level increases?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What happens to the width of the confidence interval?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
