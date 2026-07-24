// === NAME - DESCRIPTION: Two-Way Conditional - Compute P(A|B) and P(B|A) from a 2x2 table to highlight asymmetry ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc", "numfunc")

$total = 200
$a = rand(60, 90)
$b = rand(40, 70)
$c = rand(20, 40)
$d = $total - $a - $b - $c

$coffee   = $a + $b
$tea      = $a + $c
$nocoffee = $c + $d
$notea    = $b + $d

$answer[0] = $a / $tea
$answer[1] = $a / $coffee
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005

$pCondTea_show    = round($answer[0], 4)
$pCondCoffee_show = round($answer[1], 4)

// Cell styles for solution-side tables.
$cell_plain   = 'border:1px solid #dee1e3; padding:8px 14px; text-align:center;'
$cell_green   = 'border:2px solid #10b981; padding:8px 14px; text-align:center; background:#bbf7d0; font-weight:700; color:#065f46;'
$cell_purple  = 'border:2px solid #9333ea; padding:8px 14px; text-align:center; background:#e9d5ff; color:#581c87;'
$head_style   = 'border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px; font-weight:600;'
$head_purple  = 'border:2px solid #9333ea; background:#e9d5ff; padding:8px 14px; font-weight:700; color:#581c87;'

// Table for part (a) - P(Coffee | Tea): the Tea column is the reduced sample space (purple); the (Coffee, Tea) cell is favorable (green).
$tbl_a = '<table style="border-collapse:collapse; margin:0.5em auto;"><tr><th style="' . $head_style . '">&nbsp;</th><th style="' . $head_purple . '">Drinks Tea</th><th style="' . $head_style . '">No Tea</th><th style="' . $head_style . '">Total</th></tr>'
$tbl_a = $tbl_a . '<tr><th style="' . $head_style . '">Drinks Coffee</th><td style="' . $cell_green . '">' . $a . '</td><td style="' . $cell_plain . '">' . $b . '</td><td style="' . $cell_plain . '">' . $coffee . '</td></tr>'
$tbl_a = $tbl_a . '<tr><th style="' . $head_style . '">No Coffee</th><td style="' . $cell_purple . '">' . $c . '</td><td style="' . $cell_plain . '">' . $d . '</td><td style="' . $cell_plain . '">' . $nocoffee . '</td></tr>'
$tbl_a = $tbl_a . '<tr><th style="' . $head_style . '">Total</th><td style="' . $head_purple . '">' . $tea . '</td><td style="' . $cell_plain . '">' . $notea . '</td><td style="' . $cell_plain . '">' . $total . '</td></tr>'
$tbl_a = $tbl_a . '</table>'

// Table for part (b) - P(Tea | Coffee): the Coffee row is the reduced sample space (purple); the (Coffee, Tea) cell is favorable (green).
$tbl_b = '<table style="border-collapse:collapse; margin:0.5em auto;"><tr><th style="' . $head_style . '">&nbsp;</th><th style="' . $head_style . '">Drinks Tea</th><th style="' . $head_style . '">No Tea</th><th style="' . $head_purple . '">Total</th></tr>'
$tbl_b = $tbl_b . '<tr><th style="' . $head_purple . '">Drinks Coffee</th><td style="' . $cell_green . '">' . $a . '</td><td style="' . $cell_purple . '">' . $b . '</td><td style="' . $head_purple . '">' . $coffee . '</td></tr>'
$tbl_b = $tbl_b . '<tr><th style="' . $head_style . '">No Coffee</th><td style="' . $cell_plain . '">' . $c . '</td><td style="' . $cell_plain . '">' . $d . '</td><td style="' . $cell_plain . '">' . $nocoffee . '</td></tr>'
$tbl_b = $tbl_b . '<tr><th style="' . $head_style . '">Total</th><td style="' . $cell_plain . '">' . $tea . '</td><td style="' . $cell_plain . '">' . $notea . '</td><td style="' . $cell_plain . '">' . $total . '</td></tr>'
$tbl_b = $tbl_b . '</table>'

$legend_a = '<div style="display:flex;justify-content:center;gap:14px;margin:4px 0 12px 0;font-size:13px;color:#374151;flex-wrap:wrap"><span><span style="display:inline-block;width:14px;height:14px;background:#e9d5ff;border:2px solid #9333ea;border-radius:3px;vertical-align:middle"></span> conditioning on Drinks Tea (the Tea column = new sample space, total = '.$tea.')</span><span><span style="display:inline-block;width:14px;height:14px;background:#bbf7d0;border:2px solid #10b981;border-radius:3px;vertical-align:middle"></span> favorable: also drinks Coffee (count = '.$a.')</span></div>'
$legend_b = '<div style="display:flex;justify-content:center;gap:14px;margin:4px 0 12px 0;font-size:13px;color:#374151;flex-wrap:wrap"><span><span style="display:inline-block;width:14px;height:14px;background:#e9d5ff;border:2px solid #9333ea;border-radius:3px;vertical-align:middle"></span> conditioning on Drinks Coffee (the Coffee row = new sample space, total = '.$coffee.')</span><span><span style="display:inline-block;width:14px;height:14px;background:#bbf7d0;border:2px solid #10b981;border-radius:3px;vertical-align:middle"></span> favorable: also drinks Tea (count = '.$a.')</span></div>'

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
      <p>For a conditional from a two-way table, restrict to the row or column named in the condition, then divide the joint count by the marginal of the condition.</p>
      <p><b>(a)</b> P(Coffee | Tea): condition on the Tea column.</p>
      '.$tbl_a.'
      '.$legend_a.'
      <p>P(Coffee | Tea) = '.$a.' / '.$tea.' = <b>'.$pCondTea_show.'</b></p>
      <p><b>(b)</b> P(Tea | Coffee): condition on the Coffee row.</p>
      '.$tbl_b.'
      '.$legend_b.'
      <p>P(Tea | Coffee) = '.$a.' / '.$coffee.' = <b>'.$pCondCoffee_show.'</b></p>
      <p><i>Notice:</i> the two conditional probabilities differ because the denominators ('.$tea.' vs '.$coffee.') differ.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        (a) '.$pCondTea_show.' &nbsp;&bull;&nbsp; (b) '.$pCondCoffee_show.'
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A coffee shop surveyed $total customers and recorded whether each customer drinks coffee, tea, both, or neither.</p>
    <table style="border-collapse:collapse; margin:1em auto;">
      <tr><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">&nbsp;</th><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">Drinks Tea</th><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">No Tea</th><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">Total</th></tr>
      <tr><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">Drinks Coffee</th><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$a</td><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$b</td><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$coffee</td></tr>
      <tr><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">No Coffee</th><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$c</td><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$d</td><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$nocoffee</td></tr>
      <tr><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">Total</th><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$tea</td><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$notea</td><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$total</td></tr>
    </table>
    <p style="margin:0.5em 0 0 0;">A customer is selected at random.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find <b>P(Drinks Coffee | Drinks Tea)</b>.
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find <b>P(Drinks Tea | Drinks Coffee)</b>.
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
