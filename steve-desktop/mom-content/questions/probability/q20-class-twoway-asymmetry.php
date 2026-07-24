// === NAME - DESCRIPTION: P(A|B) vs P(B|A) Asymmetry Walkthrough - From the joint probability, compute both conditional directions to expose the asymmetry ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc", "numfunc", "numfunc")

// Goal: Demonstrate that P(A|B) != P(B|A). Steps:
//   (a) Joint P(STEM and Senior) = a / total
//   (b) P(Senior | STEM) = (a) / P(STEM) = a / stem
//   (c) P(STEM | Senior) = (a) / P(Senior) = a / senior
$total = 240
$a = rand(50, 80)    // STEM Senior
$b = rand(40, 70)    // STEM Junior
$c = rand(40, 70)    // Hum  Senior
$d = $total - $a - $b - $c

$stem   = $a + $b
$hum    = $c + $d
$senior = $a + $c
$junior = $b + $d

$answer[0] = $a / $total
$answer[1] = $a / $stem
$answer[2] = $a / $senior
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005
$abstolerance[2] = 0.005

$pJoint_show     = round($answer[0], 4)
$pSenGivSTEM_show = round($answer[1], 4)
$pSTEMGivSen_show = round($answer[2], 4)

$cell_plain  = 'border:1px solid #dee1e3; padding:8px 14px; text-align:center;'
$cell_green  = 'border:2px solid #10b981; padding:8px 14px; text-align:center; background:#bbf7d0; font-weight:700; color:#065f46;'
$cell_purple = 'border:2px solid #9333ea; padding:8px 14px; text-align:center; background:#e9d5ff; color:#581c87;'
$head_style  = 'border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px; font-weight:600;'
$head_purple = 'border:2px solid #9333ea; background:#e9d5ff; padding:8px 14px; font-weight:700; color:#581c87;'

$tbl_b = '<table style="border-collapse:collapse; margin:0.5em auto;"><tr><th style="' . $head_style . '">&nbsp;</th><th style="' . $head_style . '">Senior</th><th style="' . $head_style . '">Junior</th><th style="' . $head_purple . '">Total</th></tr>'
$tbl_b = $tbl_b . '<tr><th style="' . $head_purple . '">STEM</th><td style="' . $cell_green . '">' . $a . '</td><td style="' . $cell_purple . '">' . $b . '</td><td style="' . $head_purple . '">' . $stem . '</td></tr>'
$tbl_b = $tbl_b . '<tr><th style="' . $head_style . '">Humanities</th><td style="' . $cell_plain . '">' . $c . '</td><td style="' . $cell_plain . '">' . $d . '</td><td style="' . $cell_plain . '">' . $hum . '</td></tr>'
$tbl_b = $tbl_b . '<tr><th style="' . $head_style . '">Total</th><td style="' . $cell_plain . '">' . $senior . '</td><td style="' . $cell_plain . '">' . $junior . '</td><td style="' . $cell_plain . '">' . $total . '</td></tr>'
$tbl_b = $tbl_b . '</table>'

$tbl_c = '<table style="border-collapse:collapse; margin:0.5em auto;"><tr><th style="' . $head_style . '">&nbsp;</th><th style="' . $head_purple . '">Senior</th><th style="' . $head_style . '">Junior</th><th style="' . $head_style . '">Total</th></tr>'
$tbl_c = $tbl_c . '<tr><th style="' . $head_style . '">STEM</th><td style="' . $cell_green . '">' . $a . '</td><td style="' . $cell_plain . '">' . $b . '</td><td style="' . $cell_plain . '">' . $stem . '</td></tr>'
$tbl_c = $tbl_c . '<tr><th style="' . $head_style . '">Humanities</th><td style="' . $cell_purple . '">' . $c . '</td><td style="' . $cell_plain . '">' . $d . '</td><td style="' . $cell_plain . '">' . $hum . '</td></tr>'
$tbl_c = $tbl_c . '<tr><th style="' . $head_style . '">Total</th><td style="' . $head_purple . '">' . $senior . '</td><td style="' . $cell_plain . '">' . $junior . '</td><td style="' . $cell_plain . '">' . $total . '</td></tr>'
$tbl_c = $tbl_c . '</table>'

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
      <p>Goal: demonstrate P(Senior | STEM) &ne; P(STEM | Senior) by computing both from the same joint count.</p>
      <p><b>(a) Joint P(STEM &cap; Senior).</b> The joint cell is '.$a.'; total is '.$total.': '.$a.'/'.$total.' = <b>'.$pJoint_show.'</b>.</p>
      <p><b>(b) P(Senior | STEM).</b> Divide the joint count by the STEM-row total (purple = '.$stem.').</p>
      '.$tbl_b.'
      <p>P(Senior | STEM) = '.$a.'/'.$stem.' = <b>'.$pSenGivSTEM_show.'</b>.</p>
      <p><b>(c) P(STEM | Senior).</b> Same joint count, but divide by the Senior-column total (purple = '.$senior.').</p>
      '.$tbl_c.'
      <p>P(STEM | Senior) = '.$a.'/'.$senior.' = <b>'.$pSTEMGivSen_show.'</b>.</p>
      <p><i>Asymmetry:</i> parts (b) and (c) share the same numerator ('.$a.') but divide by different denominators ('.$stem.' vs '.$senior.'). That is why <b>P(A | B) &ne; P(B | A)</b> in general.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Answers: '.$pJoint_show.', '.$pSenGivSTEM_show.', '.$pSTEMGivSen_show.'.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A college surveyed $total students about their major (STEM or Humanities) and class year (Senior or Junior).</p>
    <table style="border-collapse:collapse; margin:1em auto;">
      <tr><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">&nbsp;</th><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">Senior</th><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">Junior</th><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">Total</th></tr>
      <tr><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">STEM</th><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$a</td><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$b</td><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$stem</td></tr>
      <tr><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">Humanities</th><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$c</td><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$d</td><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$hum</td></tr>
      <tr><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">Total</th><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$senior</td><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$junior</td><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$total</td></tr>
    </table>
    <p style="margin:0.5em 0 0 0;">A student is selected at random.</p>
    <p style="margin:0.5em 0 0 0; font-size:14px; color:#555;">Enter answers as decimals rounded to 4 places (or exact fractions).</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find <b>P(STEM &cap; Senior)</b>.
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find <b>P(Senior | STEM)</b>.
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find <b>P(STEM | Senior)</b>.
    <div style="margin-top:12px;text-align:center;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
