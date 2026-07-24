// === NAME - DESCRIPTION: Mutually Exclusive Yes/No - Identify which event pairs are mutually exclusive ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices", "choices")

$choices[0] = array("Yes, they are mutually exclusive", "No, they are not mutually exclusive")
$answer[0] = 1   // King vs Heart -> overlap is the King of Hearts -> NOT mutually exclusive

$choices[1] = array("Yes, they are mutually exclusive", "No, they are not mutually exclusive")
$answer[1] = 0   // Income $25K+ vs income < $20K -> no overlap -> MUTUALLY EXCLUSIVE

$choices[2] = array("Yes, they are mutually exclusive", "No, they are not mutually exclusive")
$answer[2] = 1   // Even die roll vs roll greater than 3 -> {4,6} overlap -> NOT mutually exclusive

$displayformat[0] = "select"
$displayformat[1] = "select"
$displayformat[2] = "select"

// --- Card-deck SVG builder (reused for part a). Produces neutral and highlighted versions. ---
$ranks = array("A","2","3","4","5","6","7","8","9","10","J","Q","K")
$suit_glyph0 = "&#9824;"
$suit_glyph1 = "&#9829;"
$suit_glyph2 = "&#9830;"
$suit_glyph3 = "&#9827;"
$suit_glyphs = array($suit_glyph0, $suit_glyph1, $suit_glyph2, $suit_glyph3)

$deck_open = '<svg width="576" height="248" viewBox="0 0 576 248" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Standard 52-card deck" style="display:block;margin:10px auto;background:#fff">'
$deck_q = $deck_open
$deck_sol_a = $deck_open

for ($r = 0..12) {
  $cx = 30 + $r * 42 + 19
  $col_header = '<text x="' . $cx . '" y="18" font-size="13" font-weight="700" fill="#1f2937" text-anchor="middle">' . $ranks[$r] . '</text>'
  $deck_q = $deck_q . $col_header
  $deck_sol_a = $deck_sol_a . $col_header
}

for ($s = 0..3) {
  $color = "#1f2937"
  if ($s == 1 || $s == 2) { $color = "#dc2626" }
  $row_y = 30 + $s * 52
  $row_label = '<text x="15" y="' . ($row_y + 32) . '" font-size="22" fill="' . $color . '" text-anchor="middle">' . $suit_glyphs[$s] . '</text>'
  $deck_q = $deck_q . $row_label
  $deck_sol_a = $deck_sol_a . $row_label
  for ($r = 0..12) {
    $cardx = 30 + $r * 42
    $rank_txt = '<text x="' . ($cardx + 4) . '" y="' . ($row_y + 14) . '" font-size="11" font-weight="700" fill="' . $color . '">' . $ranks[$r] . '</text>'
    $suit_txt = '<text x="' . ($cardx + 19) . '" y="' . ($row_y + 38) . '" font-size="18" fill="' . $color . '" text-anchor="middle">' . $suit_glyphs[$s] . '</text>'
    // Question: neutral
    $rect_q = '<rect x="' . $cardx . '" y="' . $row_y . '" width="38" height="48" rx="5" fill="#ffffff" stroke="#d1d5db" stroke-width="1"/>'
    $deck_q = $deck_q . $rect_q . $rank_txt . $suit_txt
    // Solution part a: kings (rank=12) orange, hearts (suit=1) purple, K of hearts green
    $is_king = ($r == 12)
    $is_heart = ($s == 1)
    $bg_a = "#ffffff"
    $st_a = "#d1d5db"
    $sw_a = 1
    if ($is_king && $is_heart) {
      $bg_a = "#bbf7d0"
      $st_a = "#10b981"
      $sw_a = 2
    } else {
      if ($is_king) {
        $bg_a = "#fed7aa"
        $st_a = "#ea580c"
        $sw_a = 2
      }
      if ($is_heart) {
        $bg_a = "#e9d5ff"
        $st_a = "#9333ea"
        $sw_a = 2
      }
    }
    $rect_a = '<rect x="' . $cardx . '" y="' . $row_y . '" width="38" height="48" rx="5" fill="' . $bg_a . '" stroke="' . $st_a . '" stroke-width="' . $sw_a . '"/>'
    $deck_sol_a = $deck_sol_a . $rect_a . $rank_txt . $suit_txt
  }
}

$deck_q = $deck_q . '</svg>'
$deck_sol_a = $deck_sol_a . '</svg>'

// --- Die-strip SVG builder (reused for part c). ---
$pip_TL = '<circle cx="20" cy="20" r="6" fill="#1f2937"/>'
$pip_TR = '<circle cx="60" cy="20" r="6" fill="#1f2937"/>'
$pip_ML = '<circle cx="20" cy="40" r="6" fill="#1f2937"/>'
$pip_MC = '<circle cx="40" cy="40" r="6" fill="#1f2937"/>'
$pip_MR = '<circle cx="60" cy="40" r="6" fill="#1f2937"/>'
$pip_BL = '<circle cx="20" cy="60" r="6" fill="#1f2937"/>'
$pip_BR = '<circle cx="60" cy="60" r="6" fill="#1f2937"/>'
$pips_face1 = $pip_MC
$pips_face2 = $pip_TL . $pip_BR
$pips_face3 = $pip_TL . $pip_MC . $pip_BR
$pips_face4 = $pip_TL . $pip_TR . $pip_BL . $pip_BR
$pips_face5 = $pip_TL . $pip_TR . $pip_MC . $pip_BL . $pip_BR
$pips_face6 = $pip_TL . $pip_TR . $pip_ML . $pip_MR . $pip_BL . $pip_BR
$pips = array("", $pips_face1, $pips_face2, $pips_face3, $pips_face4, $pips_face5, $pips_face6)

$die_w = 60
$die_gap = 10
$die_total_w = 6 * $die_w + 5 * $die_gap

$dice_open = '<svg width="' . $die_total_w . '" height="76" viewBox="0 0 ' . $die_total_w . ' 76" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="The 6 faces of a standard die" style="display:block;margin:10px auto">'
$dice_q = $dice_open
$dice_sol_c = $dice_open

for ($f = 1..6) {
  $x = ($f - 1) * ($die_w + $die_gap)
  $pip_grp = '<g transform="translate(' . ($x + 5) . ',9) scale(0.58)">' . $pips[$f] . '</g>'
  // Question: neutral
  $dice_q = $dice_q . '<rect x="' . ($x + 2) . '" y="6" width="56" height="56" rx="10" fill="#ffffff" stroke="#d1d5db" stroke-width="2"/>' . $pip_grp
  // Solution part c: even (2,4,6) orange (E), >3 (4,5,6) purple (F), overlap (4,6) green
  $is_even = ($f == 2 || $f == 4 || $f == 6)
  $is_gt3 = ($f >= 4)
  $bg_c = "#ffffff"
  $st_c = "#d1d5db"
  if ($is_even && $is_gt3) {
    $bg_c = "#bbf7d0"
    $st_c = "#10b981"
  } else {
    if ($is_even) {
      $bg_c = "#fed7aa"
      $st_c = "#ea580c"
    }
    if ($is_gt3) {
      $bg_c = "#e9d5ff"
      $st_c = "#9333ea"
    }
  }
  $dice_sol_c = $dice_sol_c . '<rect x="' . ($x + 2) . '" y="6" width="56" height="56" rx="10" fill="' . $bg_c . '" stroke="' . $st_c . '" stroke-width="2"/>' . $pip_grp
}

$dice_q = $dice_q . '</svg>'
$dice_sol_c = $dice_sol_c . '</svg>'

$legend_a = '<div style="display:flex;justify-content:center;gap:14px;margin:4px 0 12px 0;font-size:13px;color:#374151;flex-wrap:wrap"><span><span style="display:inline-block;width:14px;height:14px;background:#fed7aa;border:2px solid #ea580c;border-radius:3px;vertical-align:middle"></span> King only</span><span><span style="display:inline-block;width:14px;height:14px;background:#e9d5ff;border:2px solid #9333ea;border-radius:3px;vertical-align:middle"></span> Heart only</span><span><span style="display:inline-block;width:14px;height:14px;background:#bbf7d0;border:2px solid #10b981;border-radius:3px;vertical-align:middle"></span> overlap (King AND Heart)</span></div>'

$legend_c = '<div style="display:flex;justify-content:center;gap:14px;margin:4px 0 12px 0;font-size:13px;color:#374151;flex-wrap:wrap"><span><span style="display:inline-block;width:14px;height:14px;background:#fed7aa;border:2px solid #ea580c;border-radius:3px;vertical-align:middle"></span> E = Even only</span><span><span style="display:inline-block;width:14px;height:14px;background:#e9d5ff;border:2px solid #9333ea;border-radius:3px;vertical-align:middle"></span> F = greater than 3 only</span><span><span style="display:inline-block;width:14px;height:14px;background:#bbf7d0;border:2px solid #10b981;border-radius:3px;vertical-align:middle"></span> overlap (E &cap; F)</span></div>'

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
      <p>Two events are <b>mutually exclusive</b> when they share <b>no</b> outcomes (E &cap; F = &empty;).</p>
      <p><b>(a) King and Heart</b> &mdash; the King of Hearts belongs to both events:</p>
      '.$deck_sol_a.'
      '.$legend_a.'
      <p>The green-highlighted card is the overlap. Since the overlap is non-empty, the events are <b>not mutually exclusive</b>.</p>
      <p><b>(b) Income $25,000+</b> vs <b>income less than $20,000</b> &mdash; the two income ranges share no values (no number is both &ge; 25,000 and &lt; 20,000), so the events are <b>mutually exclusive</b>.</p>
      <p><b>(c) Even die roll</b> = {2, 4, 6} and <b>greater than 3</b> = {4, 5, 6}:</p>
      '.$dice_sol_c.'
      '.$legend_c.'
      <p>The green dice (4 and 6) belong to both events. The overlap is non-empty, so the events are <b>not mutually exclusive</b>.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        (a) No &nbsp;&bull;&nbsp; (b) Yes &nbsp;&bull;&nbsp; (c) No
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">For each pair of events, decide whether the two events are <b>mutually exclusive</b>.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> A single card is drawn from a 52-card deck. <b>E</b> = "the card is a King" and <b>F</b> = "the card is a Heart".
    $deck_q
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> A randomly chosen adult's annual income is recorded. <b>E</b> = "income is $25,000 or more" and <b>F</b> = "income is less than $20,000".
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> A fair die is rolled. <b>E</b> = "the roll is even" and <b>F</b> = "the roll is greater than 3".
    $dice_q
    <div style="margin-top:12px;text-align:center;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
