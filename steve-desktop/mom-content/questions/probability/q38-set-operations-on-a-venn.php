// === NAME - DESCRIPTION: Set Operations on a Venn - Read A and not B, neither, A or B, and the complement of the union off a four-region Venn ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 3.5. Part (a), A and not B, is the one with NO formula: it has to be read off the
// picture as the crescent of A alone. There is no addition rule for it and no shortcut, which is
// exactly why it is asked first. The other three are there to reinforce that the union is three
// regions and its complement is the fourth.
//
// The four region counts are drawn from ranges so no region is zero and the diagram never collapses.
$anstypes = array("numfunc", "numfunc", "numfunc", "numfunc")

$i = rand(0, 2)

$contexts = array(
  "students surveyed about two clubs",
  "shoppers surveyed about two store brands",
  "commuters surveyed about two apps"
)
$context = $contexts[$i]

$aNames = array("Drama", "Brand X", "Transit app")
$bNames = array("Chess", "Brand Y", "Parking app")
$aName = $aNames[$i]
$bName = $bNames[$i]

$both = 4 * rand(3, 8)
$aOnly = 4 * rand(5, 12)
$bOnly = 4 * rand(4, 10)
$neither = 4 * rand(4, 9)
$total = $aOnly + $both + $bOnly + $neither
$union = $aOnly + $both + $bOnly
$notUnion = $neither

$answer[0] = $aOnly / $total
$answer[1] = $neither / $total
$answer[2] = $union / $total
$answer[3] = $notUnion / $total

// --- the Venn ---
$vw = 440
$vh = 250
$svg = '<svg width="' . $vw . '" height="' . $vh . '" viewBox="0 0 ' . $vw . ' ' . $vh . '" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Venn diagram of ' . $aName . ' and ' . $bName . ' with four region counts" style="display:block;margin:12px auto;background:#fff">'
$svg = $svg . '<rect x="6" y="6" width="' . ($vw - 12) . '" height="' . ($vh - 12) . '" fill="none" stroke="#9ca3af" stroke-width="2" rx="8"/>'
$svg = $svg . '<circle cx="175" cy="120" r="88" fill="#1865f2" fill-opacity="0.10" stroke="#1865f2" stroke-width="2"/>'
$svg = $svg . '<circle cx="265" cy="120" r="88" fill="#059669" fill-opacity="0.10" stroke="#059669" stroke-width="2"/>'
$svg = $svg . '<text x="112" y="42" font-family="Arial" font-size="16" font-weight="bold" fill="#1865f2" text-anchor="middle">' . $aName . '</text>'
$svg = $svg . '<text x="328" y="42" font-family="Arial" font-size="16" font-weight="bold" fill="#059669" text-anchor="middle">' . $bName . '</text>'
$svg = $svg . '<text x="128" y="128" font-family="Arial" font-size="20" text-anchor="middle" fill="#21242c">' . $aOnly . '</text>'
$svg = $svg . '<text x="220" y="128" font-family="Arial" font-size="20" text-anchor="middle" fill="#21242c">' . $both . '</text>'
$svg = $svg . '<text x="312" y="128" font-family="Arial" font-size="20" text-anchor="middle" fill="#21242c">' . $bOnly . '</text>'
$svg = $svg . '<text x="' . ($vw - 30) . '" y="' . ($vh - 22) . '" font-family="Arial" font-size="20" text-anchor="middle" fill="#21242c">' . $neither . '</text>'
$svg = $svg . '<text x="' . ($vw - 30) . '" y="' . ($vh - 44) . '" font-family="Arial" font-size="12" text-anchor="middle" fill="#6b7280">neither</text>'
$svg = $svg . '</svg>'

$sol = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
  .term-label { font-weight:700; color:#1865f2; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary><span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span> Step-by-Step Solution</summary>
    <div class="sol-body">
      <p><span class="term-label">(a) A and not B has no formula.</span> There is no addition rule that produces it: it is the crescent of the ' . $aName . ' circle alone, and the only way to get it is to read it off the picture: the region in ' . $aName . ' but outside the overlap, which is ' . $aOnly . '. So `' . $aOnly . ' -: ' . $total . ' = ' . ($aOnly / $total) . '`. This is the part that cannot be solved by remembering a rule, and that is the point of asking it.</p>
      <ul>
        <li><b>(b) Neither.</b> The corner outside both circles: `' . $neither . ' -: ' . $total . ' = ' . ($neither / $total) . '`.</li>
        <li><b>(c) A or B.</b> All three regions inside the union: `(' . $aOnly . ' + ' . $both . ' + ' . $bOnly . ') -: ' . $total . ' = ' . ($union / $total) . '`.</li>
        <li><b>(d) The complement of the union.</b> Everyone who is NOT in ' . $aName . ' or ' . $bName . ': the fourth region again: `' . $notUnion . ' -: ' . $total . ' = ' . ($notUnion / $total) . '`. (c) and (d) must add to 1, because the union and its complement partition everyone.</li>
      </ul>
      <p><span class="term-label">The check.</span> The four regions must sum to the total: `' . $aOnly . ' + ' . $both . ' + ' . $bOnly . ' + ' . $neither . ' = ' . $total . '`. If they do not, a region was miscounted: most often by putting the overlap somewhere it does not belong.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 4px 0;">$total $context. The Venn diagram shows how many fall in each region.</p>
    $svg
    <p style="margin:6px 0 0 0; color:#374151; font-size:14px;">One person is chosen at random. Enter each probability as a fraction or a decimal rounded to 4 places.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> <b>P($aName and not $bName)</b> $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> <b>P(neither $aName nor $bName)</b> $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> <b>P($aName or $bName)</b> $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> <b>P(not ($aName or $bName))</b>, the complement of the union. $answerbox[3]
  </div>
</div>

// === ANSWER ===

$sol
