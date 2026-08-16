// === NAME - DESCRIPTION: Working Backwards from an Interval - the error bound and the sample mean ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// An interval (lo, hi). Parts: (a) numfunc - EBM = (hi - lo)/2 (b) numfunc - bar(x) = (lo + hi)/2.
// Invariant: (a) = (hi - lo)/2 and (b) = (lo + hi)/2 exactly on every seed.

$anstypes = array("numfunc", "numfunc")

$contexts = array(
  array("a confidence interval for a population mean", 67.18, 68.82),
  array("a confidence interval for a population mean", 42.12, 47.88),
  array("a confidence interval for the mean household income, in dollars", 69720, 69922)
)
// [ctx, lo, hi]

$i = rand(0, 2)
$ctx = $contexts[$i][0]
$lo = $contexts[$i][1]
$hi = $contexts[$i][2]

$ebm = ($hi - $lo) / 2
$xbar = ($lo + $hi) / 2

$answer[0] = $ebm
$abstolerance[0] = 0.005
$answer[1] = $xbar
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
      <p><span class="term-label">Part (a) &mdash; the error bound.</span> An interval is symmetric around its centre, so the error bound is exactly half the width:</p>
      <p>`EBM = (hi - lo)/2 = (' . $hi . ' - ' . $lo . ')/2 = ' . round($ebm, 4) . '`</p>
      <p><span class="term-label">Part (b) &mdash; the sample mean.</span> The sample mean sits exactly in the middle of the interval:</p>
      <p>`bar(x) = (lo + hi)/2 = (' . $lo . ' + ' . $hi . ')/2 = ' . round($xbar, 4) . '`</p>
      <p>Each quantity has two routes &mdash; subtract the sample mean from the upper value, or halve the width; average the endpoints, or subtract the error bound from the upper value. Both routes to each answer agree, which is a useful check that the interval was read correctly.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A study reports $ctx as `(' . $lo . ', ' . $hi . ')` and nothing else. Recover the two ingredients that went into it.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The error bound `EBM`.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The sample mean `bar(x)`.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
