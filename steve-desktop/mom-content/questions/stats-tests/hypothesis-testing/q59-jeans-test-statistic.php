// === NAME - DESCRIPTION: Lab: Jeans Survey Test Statistic - SE, t with df = 7, and p-value ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The Jeans Survey's numbers (Try It Now 8.6.4 steps 2-4). Parts: (a) numfunc - the standard
// error (b) numfunc - the test statistic t (c) numfunc - the p-value.
// Invariant: ~ 0.2687, ~ 1.86, ~ 0.053 on every seed.

loadlibrary("stats");

$anstypes = array("numfunc", "numfunc", "numfunc")

$se = 0.76 / sqrt(8)
$t = (3.5 - 3) / $se
$p = 1 - tcdf($t, 7)

$answer[0] = $se
$answer[1] = $t
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
      <p><span class="term-label">Part (a) &mdash; the standard error.</span> With `sigma` unknown you estimate it from the sample: `s/sqrt(n) = 0.76/sqrt(8) = 0.76/2.8284 = ' . round($se, 4) . '`.</p>
      <p><span class="term-label">Part (b) &mdash; the test statistic.</span> `t = (bar(x) - mu_0)/(s/sqrt(n)) = (3.5 - 3)/' . round($se, 4) . ' = 0.5/' . round($se, 4) . ' = ' . round($t, 3) . '` on the t distribution with `df = 7`.</p>
      <p><span class="term-label">Part (c) &mdash; the p-value.</span> Right-tailed: `P(T_7 > ' . round($t, 3) . ') ~~ ' . round($p, 4) . '`.</p>
      <p>With `sigma` unknown you estimate it from the same eight numbers you used to estimate the center &mdash; that substitution is the entire reason the distribution changes, and the t distribution accounts for it with fatter tails. At `df = 7` the t curve needs 1.895 to clear the 5% mark where the normal is satisfied by 1.645.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">The <b>Jeans Survey</b>: young adults own an average of three pairs of jeans. Eight students report owning an average of 3.5 pairs of jeans with a sample standard deviation of 0.76. Test at `alpha = 0.05` whether the average is higher than three. Assume the population is normal.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Compute the standard error `s/sqrt(n)`.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Compute the test statistic `t` (df = 7).
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Compute the p-value.
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
