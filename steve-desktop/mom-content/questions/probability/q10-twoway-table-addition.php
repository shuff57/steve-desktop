// === NAME - DESCRIPTION: Two-Way Table Addition Rule - Read joint and marginal counts from a 2x2 table and apply the Addition Rule ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc", "numfunc")

// 2x2 table: Engineering / Sales rows, Senior / Junior columns. Total = 100 employees.
$total = 100
$a = rand(18, 28)
$b = rand(12, 22)
$c = rand(18, 28)
$d = $total - $a - $b - $c

$eng = $a + $b
$sales = $c + $d
$sr = $a + $c
$jr = $b + $d

$answer[0] = $a / $total
$answer[1] = ($eng + $sr - $a) / $total
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005

$pSenEng = round($answer[0], 4)
$pUnion  = round($answer[1], 4)

// Reusable cell style strings for the solution-side tables.
$cell_plain  = 'border:1px solid #dee1e3; padding:8px 14px; text-align:center;'
$cell_green  = 'border:2px solid #10b981; padding:8px 14px; text-align:center; background:#bbf7d0; font-weight:700; color:#065f46;'
$cell_orange = 'border:2px solid #ea580c; padding:8px 14px; text-align:center; background:#fed7aa; font-weight:700; color:#7c2d12;'
$cell_purple = 'border:2px solid #9333ea; padding:8px 14px; text-align:center; background:#e9d5ff; font-weight:700; color:#581c87;'
$head_style  = 'border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px; font-weight:600;'

// Solution table for part (a): only the intersection cell (Engineering AND Senior) is highlighted.
$tbl_inter = '<table style="border-collapse:collapse; margin:0.5em auto;"><tr><th style="' . $head_style . '">&nbsp;</th><th style="' . $head_style . '">Senior</th><th style="' . $head_style . '">Junior</th><th style="' . $head_style . '">Total</th></tr>'
$tbl_inter = $tbl_inter . '<tr><th style="' . $head_style . '">Engineering</th><td style="' . $cell_green . '">' . $a . '</td><td style="' . $cell_plain . '">' . $b . '</td><td style="' . $cell_plain . '">' . $eng . '</td></tr>'
$tbl_inter = $tbl_inter . '<tr><th style="' . $head_style . '">Sales</th><td style="' . $cell_plain . '">' . $c . '</td><td style="' . $cell_plain . '">' . $d . '</td><td style="' . $cell_plain . '">' . $sales . '</td></tr>'
$tbl_inter = $tbl_inter . '<tr><th style="' . $head_style . '">Total</th><td style="' . $cell_plain . '">' . $sr . '</td><td style="' . $cell_plain . '">' . $jr . '</td><td style="' . $cell_plain . '">' . $total . '</td></tr>'
$tbl_inter = $tbl_inter . '</table>'

// Solution table for part (b): a = green (overlap), b = orange (Eng only), c = purple (Senior only).
$tbl_union = '<table style="border-collapse:collapse; margin:0.5em auto;"><tr><th style="' . $head_style . '">&nbsp;</th><th style="' . $head_style . '">Senior</th><th style="' . $head_style . '">Junior</th><th style="' . $head_style . '">Total</th></tr>'
$tbl_union = $tbl_union . '<tr><th style="' . $head_style . '">Engineering</th><td style="' . $cell_green . '">' . $a . '</td><td style="' . $cell_orange . '">' . $b . '</td><td style="' . $cell_plain . '">' . $eng . '</td></tr>'
$tbl_union = $tbl_union . '<tr><th style="' . $head_style . '">Sales</th><td style="' . $cell_purple . '">' . $c . '</td><td style="' . $cell_plain . '">' . $d . '</td><td style="' . $cell_plain . '">' . $sales . '</td></tr>'
$tbl_union = $tbl_union . '<tr><th style="' . $head_style . '">Total</th><td style="' . $cell_plain . '">' . $sr . '</td><td style="' . $cell_plain . '">' . $jr . '</td><td style="' . $cell_plain . '">' . $total . '</td></tr>'
$tbl_union = $tbl_union . '</table>'

$legend_union = '<div style="display:flex;justify-content:center;gap:14px;margin:4px 0 12px 0;font-size:13px;color:#374151;flex-wrap:wrap"><span><span style="display:inline-block;width:14px;height:14px;background:#fed7aa;border:2px solid #ea580c;border-radius:3px;vertical-align:middle"></span> Engineering only</span><span><span style="display:inline-block;width:14px;height:14px;background:#e9d5ff;border:2px solid #9333ea;border-radius:3px;vertical-align:middle"></span> Senior only</span><span><span style="display:inline-block;width:14px;height:14px;background:#bbf7d0;border:2px solid #10b981;border-radius:3px;vertical-align:middle"></span> both (Eng AND Senior)</span></div>'

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
      <p>Total employees: '.$total.'.</p>
      <p><b>(a)</b> P(Engineering &cap; Senior) is just the green-highlighted joint cell divided by the total:</p>
      '.$tbl_inter.'
      <p>P(Eng &cap; Senior) = '.$a.' / '.$total.' = <b>'.$pSenEng.'</b></p>
      <p><b>(b)</b> Use the Addition Rule. The three highlighted regions form the union without double-counting:</p>
      '.$tbl_union.'
      '.$legend_union.'
      <p>P(Eng &cup; Senior) = '.$eng.'/'.$total.' + '.$sr.'/'.$total.' &minus; '.$a.'/'.$total.' = '.($eng + $sr - $a).'/'.$total.' = <b>'.$pUnion.'</b></p>
      <p>(Equivalently: '.$a.' + '.$b.' + '.$c.' green/orange/purple cells = '.($a + $b + $c).' employees in the union.)</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        (a) '.$pSenEng.' &nbsp;&bull;&nbsp; (b) '.$pUnion.'
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A company has $total employees. They are classified by department (Engineering or Sales) and by seniority (Senior or Junior).</p>
    <table style="border-collapse:collapse; margin:1em auto;">
      <tr><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">&nbsp;</th><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">Senior</th><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">Junior</th><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">Total</th></tr>
      <tr><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">Engineering</th><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$a</td><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$b</td><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$eng</td></tr>
      <tr><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">Sales</th><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$c</td><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$d</td><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$sales</td></tr>
      <tr><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">Total</th><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$sr</td><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$jr</td><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$total</td></tr>
    </table>
    <p style="margin:0.5em 0 0 0;">An employee is selected at random. Find:</p>
    <p style="margin:0.5em 0 0 0; font-size:14px; color:#555;">Enter each answer as a decimal rounded to 4 places (or an exact fraction).</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> P(Engineering <b>AND</b> Senior)
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> P(Engineering <b>OR</b> Senior)
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
