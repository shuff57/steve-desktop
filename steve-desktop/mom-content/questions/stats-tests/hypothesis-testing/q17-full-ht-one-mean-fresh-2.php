// === NAME - DESCRIPTION: Full HT for One Mean (Fresh Context 2) - Compute t-statistic, p-value, decision at alpha for a one-mean test with a randomized story context ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats");

$anstypes = array("numfunc", "numfunc", "choices")

// Each: story, units, mu0, n, xbar, s, alpha, tail (-1 left, 0 two, +1 right), context claim (the Ha in plain words)
$cases = array(
  array("A bottling line is supposed to fill bottles to a mean of 500 mL. A quality engineer tests whether the mean fill has drifted away from 500 mL.",
        "mL", 500, 32, 497.5, 5.4, 0.05, 0, "the mean fill amount differs from 500 mL"),
  array("A study claims a new keyboard layout lowers the mean typing-error rate below the standard 18 errors per page.",
        "errors", 18, 40, 16.2, 6.5, 0.05, -1, "the mean typing-error rate is below 18 errors per page"),
  array("A gym claims its members' mean weekly workout time exceeds the national average of 150 minutes.",
        "min", 150, 45, 158, 28, 0.05, 1, "the mean weekly workout time exceeds 150 minutes"),
  array("A clinic's mean patient wait was historically 24 minutes. After a scheduling change, staff test whether the mean wait differs from 24 minutes.",
        "min", 24, 50, 21.8, 7.9, 0.01, 0, "the mean patient wait time differs from 24 minutes"),
  array("A poultry farm claims its hens' mean egg mass is greater than the breed standard of 58 g.",
        "g", 58, 36, 60.2, 5.0, 0.05, 1, "the mean egg mass is greater than 58 g")
)

$i = rand(0, count($cases)-1)
$ctx   = $cases[$i][0]
$units = $cases[$i][1]
$mu0   = $cases[$i][2]
$n     = $cases[$i][3]
$xbar  = $cases[$i][4]
$s     = $cases[$i][5]
$alpha = $cases[$i][6]
$tail  = $cases[$i][7]
$claim = $cases[$i][8]

$df = $n - 1
$se = $s / sqrt($n)
$t  = ($xbar - $mu0) / $se

// p-value
if ($tail == 0) {
  $p = 2 * (1 - tcdf(abs($t), $df))
} else {
  if ($tail == 1) { $p = 1 - tcdf($t, $df) } else { $p = tcdf($t, $df) }
}

// Decision: answer index 0 = Reject, 1 = Fail to reject
$reject = $p < $alpha ? 0 : 1
$tailWord = $tail == 0 ? "two-tailed" : ($tail == 1 ? "right-tailed" : "left-tailed")

$answer[0] = $t
$answer[1] = $p
$answer[2] = $reject
$reltolerance[0] = 0.02
$reltolerance[1] = 0.05
$abstolerance[0] = 0.02
$abstolerance[1] = 0.005

// 4 options: 2 real decisions (context-anchored) + 2 misdirects. answer = $reject (0 = Reject correct, 1 = Fail correct)
$choices[2] = array(
  "Reject `H_0`: at `alpha = " . $alpha . "` there is significant evidence that " . $claim . ".",
  "Fail to reject `H_0`: at `alpha = " . $alpha . "` there is not enough evidence that " . $claim . ".",
  "Reject `H_0`: a statistically significant result proves that " . $claim . " with certainty.",
  "Fail to reject `H_0`: this proves the population mean is exactly " . $mu0 . " " . $units . "."
)
$noshuffle[2] = "all"

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
      <p><b>Setup:</b> `"df" = ' . $df . '`; `"SE" = s/sqrt(n) = ' . $s . '/sqrt(' . $n . ') ~~ ' . round($se, 4) . '`.</p>
      <p><b>Test statistic:</b> `t = (bar(x) - mu_0)/"SE" = (' . $xbar . ' - ' . $mu0 . ')/' . round($se, 4) . ' ~~ ' . round($t, 3) . '`.</p>
      <p><b>p-value (' . $tailWord . '):</b> `~~ ' . round($p, 4) . '`.</p>
      <p><b>Decision:</b> Compare `' . round($p, 4) . '` to `alpha = ' . $alpha . '`. ' . ($reject == 0 ? "`p < alpha` so REJECT `H_0`." : "`p >= alpha` so FAIL TO REJECT `H_0`.") . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx Sample: `n = $n`, `bar(x) = $xbar` $units, `s = $s` $units. Use a $tailWord t-test at `alpha = $alpha`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Compute the <b>t-statistic</b>. (Round to 3 dp.) $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Compute the <b>p-value</b>. (Round to 4 dp.) $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is your <b>decision</b>? $answerbox[2]
  </div>
</div>


// === ANSWER ===

$solutionguide
