// === NAME - DESCRIPTION: Conditional via Reduced Sample Space - Two dice, given sum=k: find |reduced SS|, P(first=v|sum=k), P(first odd|sum=k) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc", "numfunc", "numfunc")

// Randomize the conditioning sum k. Each k has a clean reduced sample space
// and a pre-chosen target first-die value v.
$ks    = array(5,  6,  7,  8,  9)
$ns    = array(4,  5,  6,  5,  4)            // size of reduced sample space (outcomes with sum=k)
$vs    = array(2,  4,  3,  5,  4)            // target first-die value for part (b)
$pb_num = array(1, 1, 1, 1, 1)               // numerator of P(first=v | sum=k) (always 1)
$pc_num = array(2, 3, 3, 2, 2)               // numerator of P(first odd | sum=k)
$pc_den = array(4, 5, 6, 5, 4)
$pb_dec = array(0.25, 0.2, 0.16666666666666666, 0.2, 0.25)
$pc_dec = array(0.5, 0.6, 0.5, 0.4, 0.5)

$idx = rand(0, 4)
$k   = $ks[$idx]
$n   = $ns[$idx]
$v   = $vs[$idx]
$w   = $k - $v

$answer[0] = $n
$answer[1] = $pb_dec[$idx]
$answer[2] = $pc_dec[$idx]
$abstolerance[1] = 0.005
$abstolerance[2] = 0.005

$pb_show = $pb_num[$idx] . "/" . $n
$pc_show = $pc_num[$idx] . "/" . $pc_den[$idx]
$pb_dec_show = round($pb_dec[$idx], 4)
$pc_dec_show = round($pc_dec[$idx], 4)

// Enumerate the reduced sample space for the solution display
$pairs = ""
$glyph_pairs = ""
$dice_faces = array("", "⚀", "⚁", "⚂", "⚃", "⚄", "⚅")
$i = 1
while ($i <= 6) {
  $j = $k - $i
  if ($j >= 1 && $j <= 6) {
    if ($pairs != "") { $pairs = $pairs . ", " }
    $pairs = $pairs . "(" . $i . "," . $j . ")"
    if ($glyph_pairs != "") { $glyph_pairs = $glyph_pairs . "&nbsp; " }
    $glyph_pairs = $glyph_pairs . $dice_faces[$i] . $dice_faces[$j]
  }
  $i = $i + 1
}

// Build the 6x6 sum table: first die down the left, second die across the top, sum in each cell.
// Plain (no highlighting) — students identify which cells sum to $k themselves.
$cell_base = "border:1px solid #cbd5e1; padding:6px 10px; text-align:center; min-width:34px;"
$head_cell = "border:1px solid #cbd5e1; padding:6px 10px; text-align:center; background:#f3f4f6; font-size:22px; line-height:1; min-width:34px;"
$corner    = "border:1px solid #cbd5e1; padding:6px 10px; text-align:center; background:#e5e7eb; font-weight:700; min-width:34px;"

$grid = '<table style="border-collapse:collapse; margin:0.5em auto; font-family:Arial; font-size:14px;"><tr><th style="' . $corner . '">+</th>'
$jj = 1
while ($jj <= 6) {
  $grid = $grid . '<th style="' . $head_cell . '">' . $dice_faces[$jj] . '</th>'
  $jj = $jj + 1
}
$grid = $grid . '</tr>'

$ii = 1
while ($ii <= 6) {
  $grid = $grid . '<tr><th style="' . $head_cell . '">' . $dice_faces[$ii] . '</th>'
  $jj = 1
  while ($jj <= 6) {
    $sum = $ii + $jj
    $grid = $grid . '<td style="' . $cell_base . '">' . $sum . '</td>'
    $jj = $jj + 1
  }
  $grid = $grid . '</tr>'
  $ii = $ii + 1
}
$grid = $grid . '</table>'

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
      <p>Condition on the event B = "sum = '.$k.'". The reduced sample space lists every ordered outcome (first die, second die) with first + second = '.$k.':</p>
      <p style="margin-left:1em;"><b>'.$pairs.'</b></p>
      <p style="margin-left:1em; font-size:26px; letter-spacing:4px;">'.$glyph_pairs.'</p>
      <p><b>(a) Size of the reduced sample space.</b> Count the ordered pairs above: <b>'.$n.'</b>.</p>
      <p><b>(b) P(first die = '.$v.' | sum = '.$k.').</b> Of the '.$n.' outcomes in the reduced sample space, exactly one &mdash; ('.$v.','.$w.') &mdash; has first die = '.$v.'. So the conditional probability is '.$pb_show.' = <b>'.$pb_dec_show.'</b>.</p>
      <p><b>(c) P(first die is odd | sum = '.$k.').</b> Count outcomes in the reduced sample space whose first die is odd: '.$pc_show.' = <b>'.$pc_dec_show.'</b>.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Answers: reduced sample space size = '.$n.', P(first='.$v.' | sum='.$k.') = '.$pb_dec_show.', P(first odd | sum='.$k.') = '.$pc_dec_show.'.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 0.6em 0;"><span style="font-size:28px; letter-spacing:6px; vertical-align:middle;">⚂ ⚄</span>&nbsp; Two fair six-sided dice are rolled and the outcomes (first die, second die) are observed. You are told that the <b>sum equals $k</b>.</p>
    <p style="margin:0;">All questions below are computed on the <b>reduced sample space</b> &mdash; the set of outcomes consistent with sum = $k.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    $grid
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> How many ordered outcomes (first die, second die) have sum = $k? (Size of the reduced sample space.)
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find <b>P(first die = $v &nbsp;|&nbsp; sum = $k)</b>.
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find <b>P(first die is odd &nbsp;|&nbsp; sum = $k)</b>.
    <div style="margin-top:12px;text-align:center;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
