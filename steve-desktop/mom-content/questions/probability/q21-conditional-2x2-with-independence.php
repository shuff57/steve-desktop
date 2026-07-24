// === NAME - DESCRIPTION: Conditional 2x2 Independence - Marginal, joint, conditional probabilities from a commute table, then test independence ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc", "numfunc", "numfunc", "choices")

// 2x2 table: rows = commute method (Train / Other), columns = arrival (On Time / Late).
// Total fixed at 200. Pick 3 cells with rand; 4th cell ensures row/col integrity.
// Build so marginal P(Train) and conditional P(Train|OnTime) differ noticeably, confirming dependence.
$total = 200

// Cell (Train, On Time) — favorable cell for joint and conditional.
$a = rand(55, 75)    // Train AND On Time
$b = rand(15, 30)    // Train AND Late
$c = rand(40, 60)    // Other AND On Time
$d = $total - $a - $b - $c   // Other AND Late (computed)

$train   = $a + $b           // Train row total
$other   = $c + $d           // Other row total
$ontime  = $a + $c           // On Time column total
$late    = $b + $d           // Late column total

// Probabilities
$pTrain        = $train  / $total          // P(Train) — marginal
$pJoint        = $a      / $total          // P(Train AND On Time)
$pCondOnTime   = $a      / $ontime         // P(Train | On Time)

$answer[0] = $pTrain
$answer[1] = $pJoint
$answer[2] = $pCondOnTime
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005
$abstolerance[2] = 0.005

$pTrain_show      = round($pTrain,      4)
$pJoint_show      = round($pJoint,      4)
$pCond_show       = round($pCondOnTime, 4)
$pTrain_frac      = $train . '/' . $total
$pJoint_frac      = $a     . '/' . $total
$pCond_frac       = $a     . '/' . $ontime

// Independence check: compare P(Train|OnTime) to P(Train).
// Compute the correct answer dynamically since the random scenario may
// occasionally land on near-equality (independence) instead of dependence.
$choices[3] = array("Yes, they are independent", "No, they are not independent")
$indep_gap = abs($pTrain - $pCondOnTime)
if ($indep_gap < 0.005) {
  $answer[3] = 0
  $indep_phrase = "match closely, so"
} else {
  $answer[3] = 1
  $indep_phrase = "differ, so"
}
$displayformat[3] = "select"
$noshuffle[3] = "all"
$indep_label = $choices[3][$answer[3]]

// Cell styles
$cell_plain  = 'border:1px solid #dee1e3; padding:8px 14px; text-align:center;'
$cell_green  = 'border:2px solid #10b981; padding:8px 14px; text-align:center; background:#bbf7d0; font-weight:700; color:#065f46;'
$cell_purple = 'border:2px solid #9333ea; padding:8px 14px; text-align:center; background:#e9d5ff; color:#581c87;'
$head_style  = 'border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px; font-weight:600;'
$head_purple = 'border:2px solid #9333ea; background:#e9d5ff; padding:8px 14px; font-weight:700; color:#581c87;'

// Solution table for part (c): On Time column highlighted purple (conditioning event); Train+OnTime cell green (favorable).
$tbl_c = '<table style="border-collapse:collapse; margin:0.5em auto;">'
$tbl_c = $tbl_c . '<tr><th style="' . $head_style . '">&nbsp;</th><th style="' . $head_purple . '">On Time</th><th style="' . $head_style . '">Late</th><th style="' . $head_style . '">Total</th></tr>'
$tbl_c = $tbl_c . '<tr><th style="' . $head_style . '">Train</th><td style="' . $cell_green . '">' . $a . '</td><td style="' . $cell_plain . '">' . $b . '</td><td style="' . $cell_plain . '">' . $train . '</td></tr>'
$tbl_c = $tbl_c . '<tr><th style="' . $head_style . '">Other</th><td style="' . $cell_purple . '">' . $c . '</td><td style="' . $cell_plain . '">' . $d . '</td><td style="' . $cell_plain . '">' . $other . '</td></tr>'
$tbl_c = $tbl_c . '<tr><th style="' . $head_style . '">Total</th><td style="' . $head_purple . '">' . $ontime . '</td><td style="' . $cell_plain . '">' . $late . '</td><td style="' . $cell_plain . '">' . $total . '</td></tr>'
$tbl_c = $tbl_c . '</table>'

$legend_c = '<div style="display:flex;justify-content:center;gap:14px;margin:4px 0 12px 0;font-size:13px;color:#374151;flex-wrap:wrap"><span><span style="display:inline-block;width:14px;height:14px;background:#e9d5ff;border:2px solid #9333ea;border-radius:3px;vertical-align:middle"></span> On Time column = new sample space (total = ' . $ontime . ')</span><span><span style="display:inline-block;width:14px;height:14px;background:#bbf7d0;border:2px solid #10b981;border-radius:3px;vertical-align:middle"></span> favorable: Train &cap; On Time (count = ' . $a . ')</span></div>'

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
      <p><b>(a) P(Train)</b> uses the Train row total divided by the grand total.</p>
      <p>P(Train) = '.$train.' / '.$total.' = <b>'.$pTrain_frac.' &approx; '.$pTrain_show.'</b></p>
      <p><b>(b) P(Train &cap; On Time)</b> is the single cell where both conditions hold.</p>
      <p>P(Train &cap; On Time) = '.$a.' / '.$total.' = <b>'.$pJoint_frac.' &approx; '.$pJoint_show.'</b></p>
      <p><b>(c) P(Train | On Time)</b> restricts the sample space to the On Time column (total = '.$ontime.'). Divide the favorable cell by the column total.</p>
      '.$tbl_c.'
      '.$legend_c.'
      <p>P(Train | On Time) = P(Train &cap; On Time) / P(On Time) = ('.$a.'/'.$total.') / ('.$ontime.'/'.$total.') = '.$a.'/'.$ontime.' = <b>'.$pCond_frac.' &approx; '.$pCond_show.'</b></p>
      <p><b>(d) Independence check:</b> two events are independent when P(A|B) = P(A).</p>
      <p>P(Train) = '.$pTrain_show.' &nbsp; vs &nbsp; P(Train | On Time) = '.$pCond_show.'.</p>
      <p>Since these probabilities '.$indep_phrase.' commute method and on-time arrival are <b>'.$indep_label.'</b> in this dataset.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        (a) '.$pTrain_show.' &nbsp;&bull;&nbsp; (b) '.$pJoint_show.' &nbsp;&bull;&nbsp; (c) '.$pCond_show.' &nbsp;&bull;&nbsp; (d) '.$indep_label.'
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A city transit office surveyed <b>$total</b> commuters and recorded their usual commute method (Train or Other) and whether they typically arrive on time.</p>
    <table style="border-collapse:collapse; margin:1em auto;">
      <tr><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">&nbsp;</th><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">On Time</th><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">Late</th><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">Total</th></tr>
      <tr><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">Train</th><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$a</td><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$b</td><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$train</td></tr>
      <tr><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">Other</th><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$c</td><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$d</td><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$other</td></tr>
      <tr><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">Total</th><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$ontime</td><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$late</td><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$total</td></tr>
    </table>
    <p style="margin:0.5em 0 0 0;">A commuter is selected at random. Enter probabilities as decimals rounded to 4 places (or exact fractions).</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find <b>P(Train)</b>.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find <b>P(Train &cap; On Time)</b>.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find <b>P(Train | On Time)</b>.
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Based on your answers to (a) and (c), are the events "commutes by Train" and "arrives On Time" <b>independent</b>?
    <span style="margin-left:8px;">$answerbox[3]</span>
  </div>
</div>


// === ANSWER ===

$solutionguide
