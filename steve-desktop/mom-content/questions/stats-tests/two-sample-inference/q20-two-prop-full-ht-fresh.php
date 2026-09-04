// === NAME - DESCRIPTION: Two-Proportion HT (Fresh Context) - Compute pooled p, z-stat, p-value, and decision for a two-tailed two-proportion test ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats");

$anstypes = array("numfunc", "numfunc", "choices")

// Each scenario: story, x1, n1, x2, n2, alpha
$cases = array(
  array("Two physical-therapy clinics report different success rates for a knee-rehab protocol. Clinic A: 124 of 180 fully recovered. Clinic B: 96 of 165 fully recovered. Is the success rate different at `alpha = 0.05`?",
        124, 180, 96, 165, 0.05),
  array("A school compares ePortfolio completion across two grading rubrics. Rubric R1: 88 of 145 students completed. Rubric R2: 70 of 150. Test for a difference at `alpha = 0.05`.",
        88, 145, 70, 150, 0.05),
  array("Two pharmacy outlets report flu-shot uptake during a campaign. Outlet 1: 244 of 600 customers got vaccinated. Outlet 2: 188 of 500. Test for a difference at `alpha = 0.01`.",
        244, 600, 188, 500, 0.01),
  array("A SaaS company A/B tests a new onboarding flow. Variant A: 312 of 1000 users completed onboarding. Variant B: 366 of 1000. Test for a difference at `alpha = 0.05`.",
        312, 1000, 366, 1000, 0.05),
  array("A polling firm compares two cities' approval of a transit plan. City P: 156 of 280 approve. City Q: 134 of 260 approve. Test for a difference at `alpha = 0.05`.",
        156, 280, 134, 260, 0.05)
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$x1 = $cases[$i][1]
$n1 = $cases[$i][2]
$x2 = $cases[$i][3]
$n2 = $cases[$i][4]
$alpha = $cases[$i][5]

$p1 = $x1 / $n1
$p2 = $x2 / $n2
$pPool = ($x1 + $x2) / ($n1 + $n2)
$se = sqrt($pPool * (1 - $pPool) * (1/$n1 + 1/$n2))
$z = ($p1 - $p2) / $se
$p = 2 * (1 - normalcdf(abs($z)))
$reject = $p < $alpha ? 0 : 1

$answer[0] = $z
$answer[1] = $p
$answer[2] = $reject
$reltolerance[0] = 0.02
$reltolerance[1] = 0.05
$abstolerance[0] = 0.02
$abstolerance[1] = 0.005

$choices[2] = array(
  "Reject `H_0`: there is significant evidence of a difference at `alpha = " . $alpha . "`",
  "Fail to reject `H_0`: there is NOT significant evidence at `alpha = " . $alpha . "`"
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
      <p><b>Sample proportions:</b> `hat(p)_1 = ' . $x1 . '/' . $n1 . ' ~~ ' . round($p1, 4) . '`, `hat(p)_2 = ' . $x2 . '/' . $n2 . ' ~~ ' . round($p2, 4) . '`.</p>
      <p><b>Pooled `hat(p)`:</b> `(' . $x1 . '+' . $x2 . ')/(' . $n1 . '+' . $n2 . ') ~~ ' . round($pPool, 4) . '`.</p>
      <p><b>SE:</b> `sqrt(hat(p)_(pool)(1-hat(p)_(pool))(1/n_1+1/n_2)) ~~ ' . round($se, 5) . '`.</p>
      <p><b>z:</b> `(hat(p)_1 - hat(p)_2)/"SE" ~~ ' . round($z, 3) . '`. <b>p-value (two-tailed):</b> `~~ ' . round($p, 4) . '`.</p>
      <p><b>Decision:</b> ' . ($reject == 0 ? "`p < alpha` so REJECT `H_0`." : "`p >= alpha` so FAIL TO REJECT `H_0`.") . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx Use a two-tailed pooled two-proportion z-test.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Compute the <b>z-statistic</b>. (Round to 3 dp.) $answerbox[0]
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
