// === NAME - DESCRIPTION: Read Four Probabilities off a Venn Diagram - Take the four region counts from a two-circle Venn and produce a marginal, a joint, a union and a neither ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 3.5. A Venn is worth drawing because the four regions ARE the four cases, and once a
// student sees that the union is three of them and "neither" is the fourth, the addition rule stops
// needing to be remembered. The diagram is inline SVG -- MyOpenMath has no Venn primitive.
//
// The overlap count is deliberately non-zero, so the circles genuinely intersect and the picture
// matches the arithmetic on every seed.
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
$aTotal = $aOnly + $both
$bTotal = $bOnly + $both
$union = $aOnly + $both + $bOnly

$answer[0] = $aTotal / $total
$answer[1] = $both / $total
$answer[2] = $union / $total
$answer[3] = $neither / $total

// --- the Venn, built as inline SVG ---
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
      <p><span class="term-label">The four regions are the four cases.</span> ' . $aOnly . ' + ' . $both . ' + ' . $bOnly . ' + ' . $neither . ' = ' . $total . ', which is everyone. Every question below is a matter of deciding which regions to add.</p>
      <ul>
        <li><b>(a) P(' . $aName . ')</b> is the WHOLE left circle, both of its regions: `(' . $aOnly . ' + ' . $both . ') -: ' . $total . ' = ' . ($aTotal / $total) . '`. The overlap belongs to ' . $aName . ' too &mdash; leaving it out is the most common error here.</li>
        <li><b>(b) P(both)</b> is the lens in the middle alone: `' . $both . ' -: ' . $total . ' = ' . ($both / $total) . '`.</li>
        <li><b>(c) P(' . $aName . ' or ' . $bName . ')</b> is three regions: `(' . $aOnly . ' + ' . $both . ' + ' . $bOnly . ') -: ' . $total . ' = ' . ($union / $total) . '`.</li>
        <li><b>(d) P(neither)</b> is the corner outside both circles: `' . $neither . ' -: ' . $total . ' = ' . ($neither / $total) . '`.</li>
      </ul>
      <p><span class="term-label">Two checks the picture gives you free.</span> (c) and (d) must add to 1, because every person is either in the union or outside it. And the addition rule is visible rather than remembered: `P(A) + P(B)` counts the middle ' . $both . ' twice, which is exactly why it gets subtracted once.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> <b>P($aName)</b> $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> <b>P($aName and $bName)</b> $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> <b>P($aName or $bName)</b> $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> <b>P(neither)</b> $answerbox[3]
  </div>
</div>

// === ANSWER ===

$sol
