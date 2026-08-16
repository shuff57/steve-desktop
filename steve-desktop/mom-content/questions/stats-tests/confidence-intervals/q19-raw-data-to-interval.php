// === NAME - DESCRIPTION: Raw Data to a t-Interval - bar(x), s, and the upper endpoint from a list ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A raw list of n values. Parts: (a) numfunc - bar(x) (b) numfunc - s (divide by n - 1)
// (c) numfunc - the upper endpoint of the CL% t-interval.
// Invariant: (a) and (b) are the precomputed summary statistics, (c) = bar(x) + t*SE exactly.

$anstypes = array("numfunc", "numfunc", "numfunc")

$contexts = array(
  array("the effective period of a tranquilizer, in hours", array(2.7, 2.8, 3.0, 2.3, 2.3, 2.2, 2.8, 2.1, 2.4), 0.95, 2.306),
  array("the grams of fat per serving of chocolate chip cookies", array(8, 8, 10, 7, 9, 9), 0.90, 2.015),
  array("the number of colors on a national flag", array(1, 2, 2, 2, 2, 2, 2, 2, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 4, 4, 4, 4, 4, 4, 4, 5, 5, 5, 5, 5, 5), 0.95, 2.024)
)
// [ctx, data, cl, t]

$i = rand(0, 2)
$ctx = $contexts[$i][0]
$data = $contexts[$i][1]
$cl = $contexts[$i][2]
$t = $contexts[$i][3]

$n = count($data)
$sum = 0
$sumSq = 0
foreach ($data as $v) {
  $sum = $sum + $v
  $sumSq = $sumSq + $v * $v
}
$xbar = $sum / $n
$s = sqrt(($sumSq - $n * $xbar * $xbar) / ($n - 1))
$se = $s / sqrt($n)
$ebm = $t * $se
$hi = $xbar + $ebm

$clPct = round($cl * 100)
$dataList = joinarray($data, ", ")

$answer[0] = $xbar
$abstolerance[0] = 0.005
$answer[1] = $s
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
      <p><span class="term-label">Part (a) &mdash; the sample mean.</span> The ' . $n . ' values sum to ' . round($sum, 4) . ', so `bar(x) = ' . round($sum, 4) . '/' . $n . ' = ' . round($xbar, 4) . '`.</p>
      <p><span class="term-label">Part (b) &mdash; the sample standard deviation.</span> Divide by `n - 1 = ' . ($n - 1) . '` (this is a sample, not a population): `s = ' . round($s, 4) . '`. Be careful which standard deviation you copy down &mdash; a calculator that reports both will show S_x (dividing by n - 1) and sigma_x (dividing by n). This section always wants S_x.</p>
      <p><span class="term-label">Part (c) &mdash; the upper endpoint.</span> With `df = ' . ($n - 1) . '` and `t_(alpha/2) = ' . $t . '`:</p>
      <p>`SE = s/sqrt(n) = ' . round($se, 4) . '`, `EBM = ' . $t . ' * ' . round($se, 4) . ' = ' . round($ebm, 4) . '`</p>
      <p>`bar(x) + EBM = ' . round($xbar, 4) . ' + ' . round($ebm, 4) . ' = ' . round($hi, 4) . '`</p>
      <p>Before any of the interval machinery runs, you have to reduce the list to bar(x), s, and n yourself &mdash; the same one-variable-statistics routine you used back in the descriptive-statistics chapter.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A researcher records $ctx for a random sample: $dataList. The population standard deviation is unknown. Build a $clPct% confidence interval for the population mean.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The sample mean `bar(x)`. (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The sample standard deviation `s`. (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> The UPPER endpoint of the interval. (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
