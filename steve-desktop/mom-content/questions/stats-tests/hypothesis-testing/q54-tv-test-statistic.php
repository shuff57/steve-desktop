// === NAME - DESCRIPTION: Lab: Television Survey Test Statistic - SE, z, and p-value for the sigma-known mean test ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The Television Survey's numbers (Try It Now 8.6.2). Parts: (a) numfunc - the standard error
// (b) numfunc - the test statistic (c) numfunc - the p-value.
// Invariant: ~ 0.3651, ~ -2.19, ~ 0.0142 on every seed.

loadlibrary("stats");

$anstypes = array("numfunc", "numfunc", "numfunc")

$se = 2 / sqrt(30)
$z = (3.2 - 4) / $se
$p = normalcdf($z)

$answer[0] = $se
$answer[1] = $z
$answer[2] = $p
$abstolerance[0] = 0.005
$abstolerance[1] = 0.02
$abstolerance[2] = 0.005

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
      <p><span class="term-label">Part (a): the standard error.</span> `sigma/sqrt(n) = 2/sqrt(30) = 2/5.4772 = ' . round($se, 4) . '`.</p>
      <p><span class="term-label">Part (b): the test statistic.</span> `z = (bar(x) - mu_0)/(sigma/sqrt(n)) = (3.2 - 4)/' . round($se, 4) . ' = -0.8/' . round($se, 4) . ' = ' . round($z, 3) . '`.</p>
      <p><span class="term-label">Part (c): the p-value.</span> The alternative is `mu < 4`, so this is a left-tailed test and the p-value is the area to the left of z = ' . round($z, 3) . ': `P(Z < ' . round($z, 3) . ') ~~ ' . round($p, 4) . '`.</p>
      <p>The `sqrt(n)` in the denominator converts "how far below" into "how surprising": a class average of 3.6 hours is the same distance below 4 whether it came from 8 students or 80, but it is far more surprising coming from 80.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">The <b>Television Survey</b>: Americans watch television on average four hours per day, `sigma = 2` known. A class of 30 students reports an average of 3.2 hours per day. Test at `alpha = 0.05` whether the average for students is lower.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Compute the standard error `sigma/sqrt(n)`.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Compute the test statistic `z`.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Compute the p-value.
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
