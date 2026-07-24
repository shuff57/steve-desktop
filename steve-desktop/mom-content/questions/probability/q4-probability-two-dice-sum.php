// === NAME - DESCRIPTION: P(Sum=k Two Dice) - Probability that the sum of two dice equals a target value ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc")

// k in {5,6,7,8,9,10} -> count of (i,j) with i+j=k out of 36 ordered pairs
$ks      = array(5,     6,     7,     8,     9,    10)
$nums    = array(4,     5,     6,     5,     4,    3)
$reduced = array("1/9", "5/36","1/6", "5/36","1/9","1/12")

$picked = jointrandfrom($ks, $nums, $reduced)
$k = $picked[0]
$num = $picked[1]
$frac = $picked[2]

$answer = $num / 36
$abstolerance[0] = 0.005
$decimal_show = round($answer, 4)

// Build TWO 6x6 inline SVG sum grids in one pass:
//   $svg_q   - shown in the question (no highlights, students decide which sums to count)
//   $svg_sol - shown in the solution (cells with sum == k highlighted in green)
$svg_open = '<svg width="320" height="340" viewBox="0 0 320 340" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="6 by 6 grid of dice sums" style="display:block;margin:12px auto;background:#fff">'
$svg_q = $svg_open
$svg_sol = $svg_open
$header_corner = '<text x="22" y="30" font-size="14" font-weight="700" fill="#6b7280" text-anchor="middle">+</text>'
$svg_q = $svg_q . $header_corner
$svg_sol = $svg_sol . $header_corner
for ($j = 1..6) {
  $cx = 22 + $j * 45
  $col_header = '<text x="' . $cx . '" y="30" font-size="14" font-weight="700" fill="#1f2937" text-anchor="middle">' . $j . '</text>'
  $svg_q = $svg_q . $col_header
  $svg_sol = $svg_sol . $col_header
}
for ($i = 1..6) {
  $cy_label = 18 + $i * 45 + 22
  $row_label = '<text x="22" y="' . $cy_label . '" font-size="14" font-weight="700" fill="#1f2937" text-anchor="middle">' . $i . '</text>'
  $svg_q = $svg_q . $row_label
  $svg_sol = $svg_sol . $row_label
  for ($j = 1..6) {
    $sum = $i + $j
    $rx = 1 + $j * 45
    $ry = 18 + $i * 45
    // Question: always neutral.
    $rect_q = '<rect x="' . $rx . '" y="' . $ry . '" width="42" height="42" rx="6" fill="#ffffff" stroke="#d1d5db" stroke-width="1"/>'
    $text_q = '<text x="' . ($rx + 21) . '" y="' . ($ry + 27) . '" font-size="15" font-weight="600" fill="#374151" text-anchor="middle">' . $sum . '</text>'
    $svg_q = $svg_q . $rect_q . $text_q
    // Solution: highlight cells where sum == k.
    $bg_s = "#ffffff"
    $tc_s = "#374151"
    $sw_s = 1
    $stroke_s = "#d1d5db"
    if ($sum == $k) {
      $bg_s = "#bbf7d0"
      $tc_s = "#065f46"
      $sw_s = 2
      $stroke_s = "#10b981"
    }
    $rect_s = '<rect x="' . $rx . '" y="' . $ry . '" width="42" height="42" rx="6" fill="' . $bg_s . '" stroke="' . $stroke_s . '" stroke-width="' . $sw_s . '"/>'
    $text_s = '<text x="' . ($rx + 21) . '" y="' . ($ry + 27) . '" font-size="15" font-weight="600" fill="' . $tc_s . '" text-anchor="middle">' . $sum . '</text>'
    $svg_sol = $svg_sol . $rect_s . $text_s
  }
}
$svg_q = $svg_q . '</svg>'
$svg_sol = $svg_sol . '</svg>'

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
      <p><b>Sample space:</b> two dice give n(S) = 6 &times; 6 = 36 equally likely ordered pairs (i, j).</p>
      <p><b>Event:</b> sum = '.$k.'. The shaded cells are the favorable outcomes.</p>
      '.$svg_sol.'
      <p>n(E) = <b>'.$num.'</b></p>
      <p>P(sum = '.$k.') = '.$num.'/36 = <b>'.$frac.' &approx; '.$decimal_show.'</b></p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        P(sum = '.$k.') = '.$frac.' &approx; '.$decimal_show.'
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">You roll two fair, standard six-sided dice. The grid below shows the 36 equally likely outcomes (rows = first die, columns = second die); each cell shows that pair's sum.</p>
    $svg_q
    <p style="margin:0.5em 0 0 0;">Find the probability that the <b>sum of the two faces equals $k</b>.</p>
    <p style="margin:0.5em 0 0 0; font-size:14px; color:#555;">Enter as a fraction (e.g. <code>5/36</code>) or as a decimal rounded to 4 places.</p>
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
