// === NAME - DESCRIPTION: Confidence Interval for Difference of Two Proportions - SE, ME, and bounds ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc","numfunc","numfunc","numfunc")

// Three scenarios with clean p-values. Confidence level randomized over 90/95/99.
// z*: 90 -> 1.645, 95 -> 1.960, 99 -> 2.576.

$ctxs = array(
  "A drug trial compares a new treatment to a placebo. In the treatment group, <b>80</b> of <b>200</b> patients reported improvement. In the placebo group, <b>60</b> of <b>200</b> reported improvement.",
  "A community survey asks about support for a new park. In an urban sample of <b>300</b> residents, <b>150</b> supported the park. In a rural sample of <b>250</b> residents, <b>100</b> supported the park.",
  "A factory tests two stamping processes. The old process produced <b>24</b> defective parts in <b>400</b>, while the new process produced <b>16</b> defective parts in <b>400</b>."
)

$n1s = array(200, 300, 400)
$x1s = array( 80, 150,  24)
$n2s = array(200, 250, 400)
$x2s = array( 60, 100,  16)

$p1s   = array(0.40, 0.50, 0.06)
$p2s   = array(0.30, 0.40, 0.04)
$diffs = array(0.10, 0.10, 0.02)
$SEs   = array(0.0474, 0.0424, 0.0154)

$g1_labels = array("Treatment", "Urban", "Old process")
$g2_labels = array("Placebo",   "Rural", "New process")

$picked = jointrandfrom($ctxs, $n1s, $x1s, $n2s, $x2s, $p1s, $p2s, $diffs, $SEs, $g1_labels, $g2_labels)
$ctx   = $picked[0]
$n1    = $picked[1]
$x1    = $picked[2]
$n2    = $picked[3]
$x2    = $picked[4]
$p1    = $picked[5]
$p2    = $picked[6]
$diff  = $picked[7]
$se    = $picked[8]
$g1lab = $picked[9]
$g2lab = $picked[10]

$conf = randfrom("90,95,99")
$zStar = 1.645
if ($conf == 95) { $zStar = 1.960 }
if ($conf == 99) { $zStar = 2.576 }

$me   = round($zStar * $se, 4)
$lo   = round($diff - $me, 4)
$hi   = round($diff + $me, 4)

$answer[0] = $se
$answer[1] = $me
$answer[2] = $lo
$answer[3] = $hi

$abstolerance[0] = 0.001
$abstolerance[1] = 0.002
$abstolerance[2] = 0.002
$abstolerance[3] = 0.002

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
      <p><b>Formulas.</b> For a CI on `p_1 - p_2`:</p>
      <ul>
        <li>`hat(p)_1 = ' . $p1 . '`, `hat(p)_2 = ' . $p2 . '`, difference `= ' . $diff . '`</li>
        <li>`"SE" = sqrt(hat(p)_1(1 - hat(p)_1)/n_1 + hat(p)_2(1 - hat(p)_2)/n_2)`</li>
        <li>`"ME" = z^** xx "SE"`, where `z^**` depends on the confidence level</li>
        <li>CI: `(hat(p)_1 - hat(p)_2) +- "ME"`</li>
      </ul>
      <p><b>Part a.</b> `"SE" = sqrt(' . $p1 . '(1 - ' . $p1 . ')/' . $n1 . ' + ' . $p2 . '(1 - ' . $p2 . ')/' . $n2 . ') approx ' . $se . '`</p>
      <p><b>Part b.</b> At ' . $conf . '%, `z^** = ' . $zStar . '`, so `"ME" = ' . $zStar . ' xx ' . $se . ' approx ' . $me . '`.</p>
      <p><b>Part c-d.</b> CI: `(' . $diff . ' - ' . $me . ',\ ' . $diff . ' + ' . $me . ') = (' . $lo . ',\ ' . $hi . ')`.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Interpret:</b> we are ' . $conf . '% confident the true difference `p_1 - p_2` is between `' . $lo . '` and `' . $hi . '`. If the interval contains 0, the data do not give strong evidence of a difference at that confidence level.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">$ctx</p>
    <p style="margin:0;">Construct a `$conf`% confidence interval for `p_1 - p_2`, where `p_1` is the $g1lab proportion and `p_2` is the $g2lab proportion. Round all answers to three decimal places.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Standard error of the difference: $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Margin of error at `$conf`% confidence: $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Lower bound of the CI: $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Upper bound of the CI: $answerbox[3]
  </div>
</div>


// === ANSWER ===

$solutionguide
