// === NAME - DESCRIPTION: One-mean t test statistic (abstract) - Compute t and identify df ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc","numfunc")

// Three abstract scenarios. Each gives xbar, mu_0, s, n.
// t = (xbar - mu_0) / (s / sqrt(n)). df = n - 1.

$xbars = array(12.6, 75.2,  5.4)
$mu0s  = array(12.0, 78.0,  5.0)
$sds   = array( 1.8, 11.5,  0.8)
$ns    = array(  30,   40,   25)

$picked = jointrandfrom($xbars, $mu0s, $sds, $ns)
$xbar = $picked[0]
$mu0  = $picked[1]
$s    = $picked[2]
$n    = $picked[3]

$se = $s / sqrt($n)
$t  = round( ($xbar - $mu0) / $se , 3 )
$df = $n - 1

$answer[0] = $t
$answer[1] = $df

$abstolerance[0] = 0.02
$abstolerance[1] = 0.5

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><b>Formula.</b> `t = (bar(x) - mu_0) / (s / sqrt(n))` with `df = n - 1`.</p>
      <p>`SE = s / sqrt(n) = ' . $s . ' / sqrt(' . $n . ') approx ' . round($se,4) . '`</p>
      <p>`t = (' . $xbar . ' - ' . $mu0 . ') / ' . round($se,4) . ' approx ' . $t . '`</p>
      <p>`df = n - 1 = ' . $df . '`</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">A one-sample t-test is run with the following information:</p>
    <ul style="margin:0;">
      <li>Sample mean `bar(x) = $xbar`</li>
      <li>Hypothesized mean `mu_0 = $mu0`</li>
      <li>Sample standard deviation `s = $s`</li>
      <li>Sample size `n = $n`</li>
    </ul>
    <p style="margin:8px 0 0 0;">Sigma is unknown. Compute the test statistic and degrees of freedom.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Test statistic `t` (round to two decimals): $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Degrees of freedom: $answerbox[1]
  </div>
</div>


// === ANSWER ===

$solutionguide
