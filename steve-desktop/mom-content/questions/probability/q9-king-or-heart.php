// === NAME - DESCRIPTION: P(King or Heart) - Apply Addition Rule on a single card draw with two overlapping card categories ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc")

// Parallel arrays of (rank, suit) pairs. Overlap is always exactly 1 card (named rank in named suit).
// Suit indices: 0=spade, 1=heart, 2=diamond, 3=club. Rank indices: 0=A, 1=2, ..., 10=J, 11=Q, 12=K.
$rankLabel  = array("a King", "a Queen", "a Jack", "an Ace")
$suitLabel  = array("a Heart", "a Club",  "a Diamond", "a Spade")
$rankIdxs   = array(12,        11,         10,           0)
$suitIdxs   = array(1,         3,          2,            0)
$rankNum    = array(4,         4,          4,            4)
$suitNum    = array(13,        13,         13,           13)
$bothNum    = array(1,         1,          1,            1)
$unionNum   = array(16,        16,         16,           16)
$reducedFrac= array("4/13",    "4/13",     "4/13",       "4/13")

$picked = jointrandfrom($rankLabel, $suitLabel, $rankIdxs, $suitIdxs, $rankNum, $suitNum, $bothNum, $unionNum, $reducedFrac)
$rank = $picked[0]
$suit = $picked[1]
$rank_idx = $picked[2]
$suit_idx = $picked[3]
$nR = $picked[4]
$nS = $picked[5]
$nB = $picked[6]
$nU = $picked[7]
$frac = $picked[8]

$answer = $nU / 52
$abstolerance[0] = 0.005
$decimal_show = round($answer, 4)

// Build TWO 4x13 card-deck SVGs in one pass:
//   $svg_q   - shown in the question (no highlights, full deck for reference)
//   $svg_sol - shown in the solution (rank-only / suit-only / both color-coded)
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
    $rank_q = '<text x="' . ($cardx + 4) . '" y="' . ($row_y + 14) . '" font-size="11" font-weight="700" fill="' . $color . '">' . $ranks[$r] . '</text>'
    $suit_q = '<text x="' . ($cardx + 19) . '" y="' . ($row_y + 38) . '" font-size="18" fill="' . $color . '" text-anchor="middle">' . $suit_glyphs[$s] . '</text>'
    // Question: always neutral.
    $rect_q = '<rect x="' . $cardx . '" y="' . $row_y . '" width="38" height="48" rx="5" fill="#ffffff" stroke="#d1d5db" stroke-width="1"/>'
    $svg_q = $svg_q . $rect_q . $rank_q . $suit_q
    // Solution: rank-only = orange, suit-only = purple, both = green.
    $is_rank = ($r == $rank_idx)
    $is_suit = ($s == $suit_idx)
    $bg_s = "#ffffff"
    $stroke_s = "#d1d5db"
    $sw_s = 1
    if ($is_rank && $is_suit) {
      $bg_s = "#bbf7d0"
      $stroke_s = "#10b981"
      $sw_s = 2
    } else {
      if ($is_rank) {
        $bg_s = "#fed7aa"
        $stroke_s = "#ea580c"
        $sw_s = 2
      }
      if ($is_suit) {
        $bg_s = "#e9d5ff"
        $stroke_s = "#9333ea"
        $sw_s = 2
      }
    }
    $rect_s = '<rect x="' . $cardx . '" y="' . $row_y . '" width="38" height="48" rx="5" fill="' . $bg_s . '" stroke="' . $stroke_s . '" stroke-width="' . $sw_s . '"/>'
    $svg_sol = $svg_sol . $rect_s . $rank_q . $suit_q
  }
}

$svg_q = $svg_q . '</svg>'
$svg_sol = $svg_sol . '</svg>'

$legend = '<div style="display:flex;justify-content:center;gap:18px;margin:6px 0 12px 0;font-size:13px;color:#374151;flex-wrap:wrap">'
$legend = $legend . '<span><span style="display:inline-block;width:14px;height:14px;background:#fed7aa;border:2px solid #ea580c;border-radius:3px;vertical-align:middle"></span> ' . $rank . ' only</span>'
$legend = $legend . '<span><span style="display:inline-block;width:14px;height:14px;background:#e9d5ff;border:2px solid #9333ea;border-radius:3px;vertical-align:middle"></span> ' . $suit . ' only</span>'
$legend = $legend . '<span><span style="display:inline-block;width:14px;height:14px;background:#bbf7d0;border:2px solid #10b981;border-radius:3px;vertical-align:middle"></span> both ('. $rank .' AND '. $suit .')</span>'
$legend = $legend . '</div>'

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
      <p><b>Addition Rule:</b> P(A &cup; B) = P(A) + P(B) &minus; P(A &cap; B). Use it because the events overlap.</p>
      '.$svg_sol.'
      '.$legend.'
      <p>P('.$rank.') = '.$nR.'/52, P('.$suit.') = '.$nS.'/52, P('.$rank.' AND '.$suit.') = '.$nB.'/52.</p>
      <p>P('.$rank.' OR '.$suit.') = '.$nR.'/52 + '.$nS.'/52 &minus; '.$nB.'/52 = '.$nU.'/52 = <b>'.$frac.' &approx; '.$decimal_show.'</b></p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        P('.$rank.' or '.$suit.') = '.$frac.' &approx; '.$decimal_show.'
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A single card is drawn from a well-shuffled standard 52-card deck. All 52 cards are shown below for reference.</p>
    $svg_q
    <p style="margin:0.5em 0 0 0;">Find the probability that the card is <b>$rank OR $suit</b>.</p>
    <p style="margin:0.5em 0 0 0; font-size:14px; color:#555;">Enter as a fraction (e.g. <code>4/13</code>) or as a decimal rounded to 4 places.</p>
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
