// === NAME - DESCRIPTION: Lab: Jeans Survey Wrong Curve - the p-value the same statistic would get on the normal curve, and the wrong conclusion it would produce ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The Jeans Survey's wrong-curve lesson (Try It Now 8.6.4 step 6). Parts: (a) numfunc - the
// p-value the SAME test statistic would produce against the (wrong) normal curve
// (b) choices - what the wrong curve would have led you to conclude.
// Invariant: ~ 0.031 on every seed, (b) is constant.

loadlibrary("stats");

$anstypes = array("numfunc", "choices")

$pWrong = 1 - normalcdf(1.86)

$answer[0] = $pWrong
$abstolerance[0] = 0.005

$questions[1] = array(
  "Reject `H_0`: the wrong-curve p-value 0.031 is below 0.05, which is the OPPOSITE of the correct decision.",
  "Fail to reject `H_0`: the wrong-curve p-value 0.031 is above 0.05.",
  "The decision is the same either way."
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
      <p><span class="term-label">Part (a): the wrong-curve p-value.</span> Using the standard normal for the same test statistic: `P(Z > 1.86) ~~ ' . round($pWrong, 4) . '`.</p>
      <p><span class="term-label">Part (b): the wrong conclusion.</span> ' . $questions[1][0] . '</p>
      <p>Reading a t-score against a normal table does not produce an error message: it produces a smaller p-value than the data supports, a mistake that only ever fails in the direction that manufactures findings. Nothing about the arithmetic would have looked wrong; the only thing separating a published finding from a non-finding here was picking the right curve.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">The <b>Jeans Survey</b> test statistic is t = 1.86 with df = 7, and the correct p-value on the t distribution is 0.053: which does not reject at `alpha = 0.05`. Now compute what you would have concluded had you INCORRECTLY used the normal distribution.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What p-value would the SAME test statistic 1.86 produce against the (wrong) normal curve?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What would the wrong curve have led you to conclude?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
