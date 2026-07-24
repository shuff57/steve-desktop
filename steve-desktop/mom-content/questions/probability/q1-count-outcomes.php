// === NAME - DESCRIPTION: Count Outcomes - Sample space sizes for coins, dice, and a mixed coin/die experiment ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc", "numfunc", "numfunc")

// Randomize each experiment's parameter independently.
$nc  = rand(2, 4)              // coins for part (a): 2,3,4
$nd  = rand(2, 3)              // dice for part (b): 2,3
$nmc = rand(1, 3)              // coins for part (c) alongside one die: 1,2,3

$answer[0] = 2^$nc
$answer[1] = 6^$nd
$answer[2] = 6 * (2^$nmc)

$diceword = "dice"
if ($nd == 1) { $diceword = "die" }
$coinword_a = "coins"
if ($nc == 1) { $coinword_a = "coin" }
$coinword_c = "coins"
if ($nmc == 1) { $coinword_c = "coin" }

// --- Coin SVGs (heads / tails) ---
$coin_h = '<svg width="48" height="48" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="coin showing heads" style="display:inline-block;vertical-align:middle;margin:0 4px"><circle cx="24" cy="24" r="20" fill="#fde68a" stroke="#b45309" stroke-width="2"/><circle cx="24" cy="24" r="16" fill="none" stroke="#b45309" stroke-width="0.8" stroke-dasharray="2,2"/><text x="24" y="31" font-size="20" font-weight="800" fill="#78350f" text-anchor="middle" font-family="Georgia,serif">H</text></svg>'
$coin_t = '<svg width="48" height="48" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="coin showing tails" style="display:inline-block;vertical-align:middle;margin:0 4px"><circle cx="24" cy="24" r="20" fill="#e5e7eb" stroke="#6b7280" stroke-width="2"/><circle cx="24" cy="24" r="16" fill="none" stroke="#6b7280" stroke-width="0.8" stroke-dasharray="2,2"/><text x="24" y="31" font-size="20" font-weight="800" fill="#1f2937" text-anchor="middle" font-family="Georgia,serif">T</text></svg>'
$coin_pair = '<span style="display:inline-flex;align-items:center;gap:2px">' . $coin_h . '<span style="color:#6b7280;font-weight:600;margin:0 6px">or</span>' . $coin_t . '</span>'

// --- Die strip SVG (all 6 faces) ---
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

$die_w = 48
$die_gap = 8
$die_total_w = 6 * $die_w + 5 * $die_gap

$die_strip = '<svg width="' . $die_total_w . '" height="60" viewBox="0 0 ' . $die_total_w . ' 60" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="The 6 faces of a standard die" style="display:inline-block;vertical-align:middle;margin:0 4px">'
for ($f = 1..6) {
  $x = ($f - 1) * ($die_w + $die_gap)
  $die_strip = $die_strip . '<rect x="' . ($x + 2) . '" y="6" width="44" height="44" rx="8" fill="#ffffff" stroke="#d1d5db" stroke-width="2"/>'
  $die_strip = $die_strip . '<g transform="translate(' . ($x + 5) . ',11) scale(0.46)">' . $pips[$f] . '</g>'
}
$die_strip = $die_strip . '</svg>'

// --- Single die SVG (used inline next to "Roll one die and flip ..." in part c) ---
// Show a generic die with 6 visible faces would crowd the line; use a single die showing "?" pip pattern.
// Using the 6-pip face is a recognizable "die" silhouette without implying a specific outcome.
$single_die = '<svg width="48" height="48" viewBox="0 0 80 80" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="standard die" style="display:inline-block;vertical-align:middle;margin:0 4px"><rect x="2" y="2" width="76" height="76" rx="12" fill="#ffffff" stroke="#d1d5db" stroke-width="3"/>' . $pips_face6 . '</svg>'

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
      <p>Use the <b>multiplication principle</b>: when an experiment is built from independent stages, multiply the number of outcomes at each stage.</p>
      <p><b>(a)</b> Each coin has 2 outcomes (H or T), so '.$nc.' coins give <b>2<sup>'.$nc.'</sup> = '.$answer[0].'</b> outcomes.</p>
      <p><b>(b)</b> Each die has 6 faces, so '.$nd.' '.$diceword.' give <b>6<sup>'.$nd.'</sup> = '.$answer[1].'</b> outcomes.</p>
      <p><b>(c)</b> One die (6) combined with '.$nmc.' '.$coinword_c.' (2<sup>'.$nmc.'</sup> = '.(2^$nmc).') gives <b>6 &times; '.(2^$nmc).' = '.$answer[2].'</b> outcomes.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        (a) '.$answer[0].' &nbsp;&bull;&nbsp; (b) '.$answer[1].' &nbsp;&bull;&nbsp; (c) '.$answer[2].'
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">For each experiment below, count the number of outcomes in the sample space.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Flip <b>$nc</b> fair $coinword_a. Each coin has two faces:
    <div style="margin:10px 0;text-align:center;">$coin_pair</div>
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Roll <b>$nd</b> standard $diceword. Each die has six faces:
    <div style="margin:10px 0;text-align:center;">$die_strip</div>
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Roll one die and flip <b>$nmc</b> $coinword_c. The die has six faces and each coin has two faces:
    <div style="margin:10px 0;text-align:center;">$die_strip</div>
    <div style="margin:10px 0;text-align:center;">$coin_pair</div>
    <div style="margin-top:12px;text-align:center;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
