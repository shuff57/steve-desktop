// === NAME - DESCRIPTION: Lab: Language Survey Test Statistic - p', SE, and z for the two-tailed proportion test ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The Language Survey's numbers (Try It Now 8.6.3 steps 3-4). Parts: (a) numfunc - the sample
// proportion (b) numfunc - the standard error (c) numfunc - the test statistic.
// Invariant: ~ 0.56, ~ 0.0988, ~ 1.39 on every seed.

loadlibrary("stats");

$anstypes = array("numfunc", "numfunc", "numfunc")

$phat = 14 / 25
$se = sqrt(0.423 * 0.577 / 25)
$z = ($phat - 0.423) / $se

$answer[0] = $phat
$answer[1] = $se
$answer[2] = $z
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005
$abstolerance[2] = 0.02

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
      <p><span class="term-label">Part (a) &mdash; the sample proportion.</span> `p\' = x/n = 14/25 = ' . round($phat, 4) . '`.</p>
      <p><span class="term-label">Part (b) &mdash; the standard error.</span> `sqrt((p_0)(1-p_0)/n) = sqrt((0.423)(0.577)/25) = sqrt(0.009763) = ' . round($se, 4) . '`.</p>
      <p><span class="term-label">Part (c) &mdash; the test statistic.</span> `z = (p\' - p_0)/SE = (' . round($phat, 4) . ' - 0.423)/' . round($se, 4) . ' = ' . round($z, 3) . '`.</p>
      <p>The standard error uses the null proportion, and "different from" is two-sided &mdash; the p-value will collect area in both tails, which is why the same data is harder to call significant when the question is posed that way.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">The <b>Language Survey</b>: about 42.3% of Californians speak a language other than English at home. Mei-Lin runs the question in her class of 25 students, and 14 of them report speaking a language other than English at home. Test at `alpha = 0.05` whether this differs from 42.3%.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Compute the sample proportion `p'`.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Compute the standard error.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Compute the test statistic `z`.
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
