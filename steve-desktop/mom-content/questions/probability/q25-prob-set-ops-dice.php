// === NAME - DESCRIPTION: Probability of Set Ops on Dice Events - Find P(A union B), P(A intersect B), P(A complement) for two events on a single die ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc", "numfunc", "numfunc")

// Four (A, B) scenarios on S = {1..6}. Test-distinct from Quiz q6 (which returns sets).
// A_mask / B_mask encode membership: bit (face-1) is set if the face is in the set.
$Adesc      = array("the number is at most 3", "the number is a perfect square", "the number is composite",       "the number is odd")
$Bdesc      = array("the number is even",      "the number is a factor of 6",    "the number is at least 3",      "the number is at most 4")
$Adisplay   = array("{1, 2, 3}",               "{1, 4}",                         "{4, 6}",                         "{1, 3, 5}")
$Bdisplay   = array("{2, 4, 6}",               "{1, 2, 3, 6}",                   "{3, 4, 5, 6}",                   "{1, 2, 3, 4}")
$unionSizes = array(5,                          5,                                4,                                5)
$interSizes = array(1,                          1,                                2,                                2)
$compSizes  = array(3,                          4,                                4,                                3)
$unionDisp  = array("{1,2,3,4,6}",             "{1,2,3,4,6}",                    "{3,4,5,6}",                      "{1,2,3,4,5}")
$interDisp  = array("{2}",                     "{1}",                            "{4,6}",                          "{1,3}")
$compDisp   = array("{4,5,6}",                 "{2,3,5,6}",                      "{1,2,3,5}",                      "{2,4,6}")
$Amasks     = array(7,                          9,                                40,                               21)
$Bmasks     = array(42,                         39,                               60,                               15)

$picked = jointrandfrom($Adesc, $Bdesc, $Adisplay, $Bdisplay, $unionSizes, $interSizes, $compSizes, $unionDisp, $interDisp, $compDisp, $Amasks, $Bmasks)
$A_desc = $picked[0]
$B_desc = $picked[1]
$A_display = $picked[2]
$B_display = $picked[3]
$unionSize = $picked[4]
$interSize = $picked[5]
$compSize = $picked[6]
$union_display = $picked[7]
$inter_display = $picked[8]
$comp_display = $picked[9]
$A_mask = $picked[10]
$B_mask = $picked[11]

$answer[0] = $unionSize / 6
$answer[1] = $interSize / 6
$answer[2] = $compSize / 6
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005
$abstolerance[2] = 0.005

// Reduced-fraction labels for k/6, indexed by k = 0..6.
$fracStr = array("0", "1/6", "1/3", "1/2", "2/3", "5/6", "1")
$decStr  = array("0", "0.1667", "0.3333", "0.5", "0.6667", "0.8333", "1")
$union_frac = $fracStr[$unionSize]
$inter_frac = $fracStr[$interSize]
$comp_frac  = $fracStr[$compSize]
$union_dec  = $decStr[$unionSize]
$inter_dec  = $decStr[$interSize]
$comp_dec   = $decStr[$compSize]

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
      <p>S = { 1, 2, 3, 4, 5, 6 }, A = '.$A_display.', B = '.$B_display.'. Each face is equally likely, so P(face) = 1/6.</p>
      '.$svg_sol.'
      '.$legend.'
      <p><b>(a) P(A &cup; B).</b> A &cup; B = '.$union_display.', which has '.$unionSize.' faces, so P(A &cup; B) = '.$unionSize.'/6 = <b>'.$union_frac.'</b> &approx; '.$union_dec.'.</p>
      <p><b>(b) P(A &cap; B).</b> A &cap; B = '.$inter_display.', which has '.$interSize.' face(s), so P(A &cap; B) = '.$interSize.'/6 = <b>'.$inter_frac.'</b> &approx; '.$inter_dec.'.</p>
      <p><b>(c) P(<span style="text-decoration:overline;">A</span>).</b> <span style="text-decoration:overline;">A</span> = '.$comp_display.' has '.$compSize.' faces, so P(<span style="text-decoration:overline;">A</span>) = '.$compSize.'/6 = <b>'.$comp_frac.'</b> &approx; '.$comp_dec.'. Equivalently, 1 &minus; P(A).</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        P(A &cup; B) = '.$union_frac.' &nbsp;&bull;&nbsp; P(A &cap; B) = '.$inter_frac.' &nbsp;&bull;&nbsp; P(<span style="text-decoration:overline;">A</span>) = '.$comp_frac.'
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A fair six-sided die is rolled. The sample space is <b>S = { 1, 2, 3, 4, 5, 6 }</b> and each face is equally likely.</p>
    $svg_q
    <p style="margin:0.5em 0 0 0;">Let <b>A</b> = $A_desc, so <b>A = $A_display</b>.</p>
    <p style="margin:0.5em 0 0 0;">Let <b>B</b> = $B_desc, so <b>B = $B_display</b>.</p>
    <p style="margin:0.6em 0 0 0;color:#374151;font-size:14px;">Enter each answer as a fraction (e.g. <code>5/6</code>) or a decimal (e.g. <code>0.8333</code>).</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find <b>P(A &cup; B)</b>.
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find <b>P(A &cap; B)</b>.
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find <b>P(<span style="text-decoration:overline;">A</span>)</b>, the probability of the complement of A.
    <div style="margin-top:12px;text-align:center;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
