// === NAME - DESCRIPTION: The t-Interval for a Mean - SE, EBM, and the upper endpoint when sigma is unknown ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// bar(x), s, n, CL. Parts: (a) numfunc - SE = s/sqrt(n) (b) numfunc - EBM = t_(alpha/2) * SE
// (c) numfunc - the upper endpoint bar(x) + EBM.
// Invariant: (a) = s/sqrt(n), (b) = t*SE, (c) = bar(x) + EBM exactly on every seed.

$anstypes = array("numfunc", "numfunc", "numfunc")

$contexts = array(
  array("the mean number of hours slept per night", 8.9833, 1.2904, 12, 0.95, 2.201),
  array("the mean sensory rate for acupuncture subjects", 8.2267, 1.6722, 15, 0.95, 2.145),
  array("the mean number of hours of television per week", 6.133, 5.514, 15, 0.98, 2.624),
  array("the mean number of industrial chemicals in cord blood", 127.45, 25.965, 20, 0.90, 1.729)
)
// [ctx, xbar, s, n, cl, t]

$i = rand(0, 3)
$ctx = $contexts[$i][0]
$xbar = $contexts[$i][1]
$s = $contexts[$i][2]
$n = $contexts[$i][3]
$cl = $contexts[$i][4]
$t = $contexts[$i][5]

$se = $s / sqrt($n)
$ebm = $t * $se
$hi = $xbar + $ebm

$answer[0] = $se
$abstolerance[0] = 0.005
$answer[1] = $ebm
$abstolerance[1] = 0.005
$answer[2] = $hi
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
      <p><span class="term-label">Part (a) &mdash; the standard error.</span> `SE = s/sqrt(n) = ' . $s . '/sqrt(' . $n . ') = ' . round($se, 4) . '`</p>
      <p><span class="term-label">Part (b) &mdash; the error bound.</span> `EBM = t_(alpha/2) * s/sqrt(n) = ' . $t . ' * ' . round($se, 4) . ' = ' . round($ebm, 4) . '`</p>
      <p><span class="term-label">Part (c) &mdash; the upper endpoint.</span> `bar(x) + EBM = ' . $xbar . ' + ' . round($ebm, 4) . ' = ' . round($hi, 4) . '`</p>
      <p>This is the same formula you already used, with one substitution: swap the known `sigma` for the sample\'s `s`, swap `z` for `t`, and nothing else changes. The t-distribution builds the extra uncertainty of estimating sigma into its fatter tails, so it demands a bigger multiplier to still deliver the same coverage.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A researcher is studying $ctx. A random sample of `n = $n` gives a sample mean of `bar(x) = $xbar` and a sample standard deviation of `s = $s`. The population standard deviation is unknown. Build a ' . round($cl * 100) . '% confidence interval for the population mean.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the standard error of the mean? (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the error bound `EBM`? (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is the UPPER endpoint of the interval? (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
