// === NAME - DESCRIPTION: CI for difference of two means (unequal variances) - SE, t*, ME, bounds. TOP-MISS drill ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc","numfunc","numfunc","numfunc")

// Three scenarios with Welch (unequal variance) t-interval.
// Use min(n1,n2) - 1 as conservative df. Random confidence level over 90/95/99.

$intros = array(
  "A nutritionist compares daily calorie intake for two diet groups. Group 1 (`n_1 = 32`): mean = 2150 cal, `s_1 = 320` cal. Group 2 (`n_2 = 28`): mean = 1980 cal, `s_2 = 290` cal.",
  "A teacher compares hours of weekly homework for two grade levels. Group 1 (`n_1 = 24`): mean = 11.4 hr, `s_1 = 4.1` hr. Group 2 (`n_2 = 30`): mean = 9.0 hr, `s_2 = 3.6` hr.",
  "A trainer compares vertical jump (inches) before and after two different programs (independent groups). Group 1 (`n_1 = 18`): mean = 22.6, `s_1 = 3.1`. Group 2 (`n_2 = 22`): mean = 20.1, `s_2 = 3.4`."
)
$xbar1s = array(2150, 11.4, 22.6)
$xbar2s = array(1980,  9.0, 20.1)
$s1s    = array(320,   4.1,  3.1)
$s2s    = array(290,   3.6,  3.4)
$n1s    = array(32,    24,   18)
$n2s    = array(28,    30,   22)
$dfs    = array(27,    23,   17)
$picked = jointrandfrom($intros, $xbar1s, $xbar2s, $s1s, $s2s, $n1s, $n2s, $dfs)
$intro = $picked[0]
$xb1   = $picked[1]
$xb2   = $picked[2]
$sd1   = $picked[3]
$sd2   = $picked[4]
$n1    = $picked[5]
$n2    = $picked[6]
$df    = $picked[7]

$se   = round( sqrt( ($sd1*$sd1)/$n1 + ($sd2*$sd2)/$n2 ) , 4 )
$diff = round( $xb1 - $xb2 , 4 )

$conf = randfrom("90,95,99")
// t* approximate critical values for common dfs. Use a table-lookup style.
$tstar = 2.05
if ($conf == 90) {
  if ($df == 17) { $tstar = 1.740 }
  if ($df == 23) { $tstar = 1.714 }
  if ($df == 27) { $tstar = 1.703 }
}
if ($conf == 95) {
  if ($df == 17) { $tstar = 2.110 }
  if ($df == 23) { $tstar = 2.069 }
  if ($df == 27) { $tstar = 2.052 }
}
if ($conf == 99) {
  if ($df == 17) { $tstar = 2.898 }
  if ($df == 23) { $tstar = 2.807 }
  if ($df == 27) { $tstar = 2.771 }
}

$me = round( $tstar * $se , 4 )
$lo = round( $diff - $me , 4 )
$hi = round( $diff + $me , 4 )

$answer[0] = $se
$answer[1] = $me
$answer[2] = $lo
$answer[3] = $hi

$abstolerance[0] = 0.05
$abstolerance[1] = 0.10
$abstolerance[2] = 0.15
$abstolerance[3] = 0.15

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
      <p><b>Formula (Welch, unequal variances).</b> `"SE" = sqrt(s_1^2/n_1 + s_2^2/n_2)`. CI: `(bar(x)_1 - bar(x)_2) +- t^** xx "SE"` with conservative `"df" = min(n_1, n_2) - 1`.</p>
      <p><b>Part a.</b> `"SE" = sqrt(' . $sd1 . '^2/' . $n1 . ' + ' . $sd2 . '^2/' . $n2 . ') approx ' . $se . '`</p>
      <p><b>Part b.</b> At ' . $conf . '% with `"df" = ' . $df . '`, `t^** approx ' . $tstar . '`, so `"ME" = ' . $tstar . ' xx ' . $se . ' approx ' . $me . '`.</p>
      <p><b>Part c-d.</b> CI: `(' . $diff . ' +- ' . $me . ') = (' . $lo . ',\ ' . $hi . ')`.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Interpret:</b> we are ' . $conf . '% confident the true difference `mu_1 - mu_2` lies between ' . $lo . ' and ' . $hi . '. If the interval contains 0, no significant difference at that confidence level.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">$intro</p>
    <p style="margin:0;">Construct a `$conf`% confidence interval for `mu_1 - mu_2` using a two-sample t-interval that does not assume equal variances. Use the <b>conservative degrees of freedom</b> (the smaller of `n_1 - 1` and `n_2 - 1`), so `"df" = $df`. Round answers to two decimals.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Standard error of `bar(x)_1 - bar(x)_2`: $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Margin of error at `$conf`% confidence: $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Lower bound: $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Upper bound: $answerbox[3]
  </div>
</div>


// === ANSWER ===

$solutionguide
