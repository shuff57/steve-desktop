// === NAME - DESCRIPTION: The Standard Error for n = 5 - sigma/sqrt(5) ~ 0.2291 ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's standard error for n = 5 (Try It Now 6.4 step 2).
// Part: (a) numfunc - sigma/sqrt(5) = 0.5122/sqrt(5) ~ 0.2291.
// Invariant: ~ 0.2291 on every seed.

$anstypes = array("numfunc")

$answer[0] = 0.2291
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
      <p><span class="term-label">The standard error.</span> The standard deviation of the sampling distribution of the sample mean is `sigma/sqrt(n)`. With `sigma ~= 0.5122` and `n = 5`:</p>
      <p>`sigma_bar(x) = 0.5122/sqrt(5) ~= 0.5122/2.236 ~= 0.2291`</p>
      <p><span class="term-label">The exchange rate.</span> Five times the work buys less than half the spread: going from single people to groups of five divides the spread by `sqrt(5) ~= 2.24`, not by 5. To halve it again you would need groups of 20. Precision gets expensive fast, and knowing the exchange rate is what stops you over-promising.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">In the pocket-change lab, the class data has `x-bar = 0.70` and `s ~= 0.5122`. Find the standard error for the averages of groups of five (n = 5).</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The standard error for `n = 5`. (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
