// === NAME - DESCRIPTION: P(Card) - Probability of a single named card category from a 52-card deck ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc")

// Pedagogically balanced card categories from a standard 52-card deck.
// $mode + $pick describe which cells to highlight in the SOLUTION visual.
//   mode "rank"  -> highlight the column whose rank index equals pick
//   mode "suit"  -> highlight the row whose suit index equals pick
//   mode "face"  -> highlight ranks J, Q, K (rank index >= 10)
//   mode "red"   -> highlight hearts and diamonds (suit indices 1, 2)
//   mode "black" -> highlight spades and clubs (suit indices 0, 3)
$labels  = array("a king", "a queen", "a jack", "an ace", "a heart", "a club", "a diamond", "a spade", "a face card", "a red card", "a black card")
$nums    = array(4,        4,         4,        4,        13,        13,        13,           13,        12,             26,            26)
$dens    = array(52,       52,        52,       52,       52,        52,        52,           52,        52,             52,            52)
$reduced = array("1/13",   "1/13",    "1/13",   "1/13",   "1/4",     "1/4",     "1/4",        "1/4",     "3/13",         "1/2",         "1/2")
$modes   = array("rank",   "rank",    "rank",   "rank",   "suit",    "suit",    "suit",       "suit",    "face",         "red",         "black")
$picks   = array(12,       11,        10,       0,        1,         3,         2,            0,         0,              0,             0)

$picked = jointrandfrom($labels, $nums, $dens, $reduced, $modes, $picks)
$label = $picked[0]
$num = $picked[1]
$den = $picked[2]
$frac = $picked[3]
$mode = $picked[4]
$pick = $picked[5]

$answer = $num / $den
$abstolerance[0] = 0.005
$decimal_show = round($answer, 4)

// Build TWO 4x13 card-deck SVGs in one pass:
//   $svg_q   - shown in the question (no highlights, full deck for reference)
//   $svg_sol - shown in the solution (cards matching $label highlighted in green)
$ranks = array("A","2","3","4","5","6","7","8","9","10","J","Q","K")
$suit_glyph0 = "&#9824;"  // ♠
$suit_glyph1 = "&#9829;"  // ♥
$suit_glyph2 = "&#9830;"  // ♦
$suit_glyph3 = "&#9827;"  // ♣
$suit_glyphs = array($suit_glyph0, $suit_glyph1, $suit_glyph2, $suit_glyph3)

$svg_open = '<svg width="576" height="248" viewBox="0 0 576 248" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Standard 52-card deck" style="display:block;margin:12px auto;background:#fff">'
$svg_q = $svg_open
$svg_sol = $svg_open

for ($r = 0..12) {
  $cx = 30 + $r * 42 + 19
  $col_header = '<text x="' . $cx . '" y="18" font-size="13" font-weight="700" fill="#1f2937" text-anchor="middle">' . $ranks[$r] . '</text>'
  $svg_q = $svg_q . $col_header
  $svg_sol = $svg_sol . $col_header
}

for ($s = 0..3) {
  $color = "#1f2937"
  if ($s == 1 || $s == 2) { $color = "#dc2626" }
  $row_y = 30 + $s * 52
  $row_label = '<text x="15" y="' . ($row_y + 32) . '" font-size="22" fill="' . $color . '" text-anchor="middle">' . $suit_glyphs[$s] . '</text>'
  $svg_q = $svg_q . $row_label
  $svg_sol = $svg_sol . $row_label
  for ($r = 0..12) {
    $cardx = 30 + $r * 42
    // Question: always neutral.
    $rect_q = '<rect x="' . $cardx . '" y="' . $row_y . '" width="38" height="48" rx="5" fill="#ffffff" stroke="#d1d5db" stroke-width="1"/>'
    $rank_q = '<text x="' . ($cardx + 4) . '" y="' . ($row_y + 14) . '" font-size="11" font-weight="700" fill="' . $color . '">' . $ranks[$r] . '</text>'
    $suit_q = '<text x="' . ($cardx + 19) . '" y="' . ($row_y + 38) . '" font-size="18" fill="' . $color . '" text-anchor="middle">' . $suit_glyphs[$s] . '</text>'
    $svg_q = $svg_q . $rect_q . $rank_q . $suit_q
    // Solution: highlight cards matching the chosen category.
    $h = false
    if ($mode == "rank" && $r == $pick) { $h = true }
    if ($mode == "suit" && $s == $pick) { $h = true }
    if ($mode == "face" && $r >= 10)    { $h = true }
    if ($mode == "red" && ($s == 1 || $s == 2)) { $h = true }
    if ($mode == "black" && ($s == 0 || $s == 3)) { $h = true }
    $bg_s = "#ffffff"
    $stroke_s = "#d1d5db"
    $sw_s = 1
    if ($h) {
      $bg_s = "#bbf7d0"
      $stroke_s = "#10b981"
      $sw_s = 2
    }
    $rect_s = '<rect x="' . $cardx . '" y="' . $row_y . '" width="38" height="48" rx="5" fill="' . $bg_s . '" stroke="' . $stroke_s . '" stroke-width="' . $sw_s . '"/>'
    $svg_sol = $svg_sol . $rect_s . $rank_q . $suit_q
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
      <p><b>Sample space:</b> a 52-card deck has n(S) = 52 equally likely outcomes.</p>
      <p><b>Event:</b> drawing '.$label.': the highlighted cards below are the favorable outcomes.</p>
      '.$svg_sol.'
      <p>n(E) = <b>'.$num.'</b></p>
      <p>P('.$label.') = n(E) / n(S) = <b>'.$num.'/'.$den.' = '.$frac.' &approx; '.$decimal_show.'</b></p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        P('.$label.') = '.$frac.' &approx; '.$decimal_show.'
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A single card is drawn from a well-shuffled standard 52-card deck. All 52 cards are shown below for reference.</p>
    $svg_q
    <p style="margin:0.5em 0 0 0;">Find the probability that the card is <b>$label</b>.</p>
    <p style="margin:0.5em 0 0 0; font-size:14px; color:#555;">Enter your answer as a fraction (e.g. <code>1/4</code>) or as a decimal rounded to 4 places.</p>
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
