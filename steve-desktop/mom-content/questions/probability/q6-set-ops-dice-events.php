// === NAME - DESCRIPTION: Set Ops on Dice Events - Find A union B, A intersect B, A complement for two events on a single die ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("ntuple", "ntuple", "ntuple")

// Three (A, B) scenarios on S = {1..6}. A_mask / B_mask encode membership: bit (face-1) is set if the face is in the set.
$Adesc      = array("the number is even",       "the number is a multiple of 3", "the number is prime")
$Bdesc      = array("the number is greater than 3", "the number is odd",          "the number is at least 4")
$Adisplay   = array("{2, 4, 6}",                "{3, 6}",                        "{2, 3, 5}")
$Bdisplay   = array("{4, 5, 6}",                "{1, 3, 5}",                     "{4, 5, 6}")
$unions     = array("{2,4,5,6}",                "{1,3,5,6}",                     "{2,3,4,5,6}")
$inters     = array("{4,6}",                    "{3}",                           "{5}")
$comps      = array("{1,3,5}",                  "{1,2,4,5}",                     "{1,4,6}")
$Amasks     = array(42,                          36,                              22)
$Bmasks     = array(56,                          21,                              56)

$picked = jointrandfrom($Adesc, $Bdesc, $Adisplay, $Bdisplay, $unions, $inters, $comps, $Amasks, $Bmasks)
$A_desc = $picked[0]
$B_desc = $picked[1]
$A_display = $picked[2]
$B_display = $picked[3]
$answer[0] = $picked[4]
$answer[1] = $picked[5]
$answer[2] = $picked[6]
$A_mask = $picked[7]
$B_mask = $picked[8]

$displayformat[0] = "set"
$answerformat[0] = "anyorder"
$displayformat[1] = "set"
$answerformat[1] = "anyorder"
$displayformat[2] = "set"
$answerformat[2] = "anyorder"

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

// Build TWO die strips:
//   $svg_q   - shown in the question (no highlights, all 6 faces neutral)
//   $svg_sol - shown in the solution (A only / B only / both color-coded)
$die_w = 78
$gap = 14
$total_w = 6 * $die_w + 5 * $gap

$svg_open = '<svg width="' . $total_w . '" height="120" viewBox="0 0 ' . $total_w . ' 120" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Six dice faces" style="display:block;margin:12px auto;background:#fff">'
$svg_q = $svg_open
$svg_sol = $svg_open

for ($f = 1..6) {
  $bit = 2^($f - 1)
  $in_A = (floor($A_mask / $bit) % 2) == 1
  $in_B = (floor($B_mask / $bit) % 2) == 1
  $x = ($f - 1) * ($die_w + $gap)
  $pip_grp = '<g transform="translate(' . ($x + 7) . ',13) scale(0.78)">' . $pips[$f] . '</g>'
  $label_txt = '<text x="' . ($x + 40) . '" y="100" font-size="14" fill="#374151" text-anchor="middle" font-weight="600">' . $f . '</text>'
  // Question: always neutral.
  $rect_q = '<rect x="' . ($x + 2) . '" y="8" width="74" height="74" rx="12" fill="#ffffff" stroke="#d1d5db" stroke-width="2"/>'
  $svg_q = $svg_q . $rect_q . $pip_grp . $label_txt
  // Solution: color by membership.
  $bg_s = "#ffffff"
  $stroke_s = "#d1d5db"
  if ($in_A && $in_B) {
    $bg_s = "#bbf7d0"
    $stroke_s = "#10b981"
  } else {
    if ($in_A) {
      $bg_s = "#fed7aa"
      $stroke_s = "#ea580c"
    }
    if ($in_B) {
      $bg_s = "#e9d5ff"
      $stroke_s = "#9333ea"
    }
  }
  $rect_s = '<rect x="' . ($x + 2) . '" y="8" width="74" height="74" rx="12" fill="' . $bg_s . '" stroke="' . $stroke_s . '" stroke-width="2"/>'
  $svg_sol = $svg_sol . $rect_s . $pip_grp . $label_txt
}

$svg_q = $svg_q . '</svg>'
$svg_sol = $svg_sol . '</svg>'

$legend = '<div style="display:flex;justify-content:center;gap:14px;margin:4px 0 12px 0;font-size:13px;color:#374151;flex-wrap:wrap">'
$legend = $legend . '<span><span style="display:inline-block;width:14px;height:14px;background:#fed7aa;border:2px solid #ea580c;border-radius:3px;vertical-align:middle"></span> in A only</span>'
$legend = $legend . '<span><span style="display:inline-block;width:14px;height:14px;background:#e9d5ff;border:2px solid #9333ea;border-radius:3px;vertical-align:middle"></span> in B only</span>'
$legend = $legend . '<span><span style="display:inline-block;width:14px;height:14px;background:#bbf7d0;border:2px solid #10b981;border-radius:3px;vertical-align:middle"></span> in both A and B</span>'
$legend = $legend . '<span><span style="display:inline-block;width:14px;height:14px;background:#ffffff;border:2px solid #d1d5db;border-radius:3px;vertical-align:middle"></span> in neither</span>'
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
      <p>S = { 1, 2, 3, 4, 5, 6 }, A = '.$A_display.', B = '.$B_display.'.</p>
      '.$svg_sol.'
      '.$legend.'
      <p><b>(a) A &cup; B</b> contains every element that is in A, in B, or in both: <b>'.$answer[0].'</b>.</p>
      <p><b>(b) A &cap; B</b> contains only the elements in <i>both</i> A and B: <b>'.$answer[1].'</b>.</p>
      <p><b>(c) `bar A`</b> (complement of A in S) contains elements of S that are <i>not</i> in A: <b>'.$answer[2].'</b>.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        A &cup; B = '.$answer[0].' &nbsp;&bull;&nbsp; A &cap; B = '.$answer[1].' &nbsp;&bull;&nbsp; `bar A` = '.$answer[2].'
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A fair six-sided die is rolled. The sample space is <b>S = { 1, 2, 3, 4, 5, 6 }</b>.</p>
    $svg_q
    <p style="margin:0.5em 0 0 0;">Let <b>A</b> = $A_desc, so <b>A = $A_display</b>.</p>
    <p style="margin:0.5em 0 0 0;">Let <b>B</b> = $B_desc, so <b>B = $B_display</b>.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find <b>A &cup; B</b>.
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find <b>A &cap; B</b>.
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find <b>`bar A`</b> (the complement of A in S).
    <div style="margin-top:12px;text-align:center;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
