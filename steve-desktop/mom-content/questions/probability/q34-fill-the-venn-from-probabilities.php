// === NAME - DESCRIPTION: Fill a Venn from Three Probabilities - Given P(A), P(B) and P(A and B), work out all four region probabilities and confirm they account for everyone ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 3.5, and the mirror of q31: there the regions are given and the probabilities are read;
// here the totals are given and the REGIONS have to be recovered. That direction is the one students
// find hard, because P(A) is not a region -- it is two regions added together, and the overlap has
// to be taken back out to get the piece that is A alone.
//
// The diagram is drawn with the four regions blank, so it is a worksheet rather than a readout.
$anstypes = array("numfunc", "numfunc", "numfunc", "numfunc")

$i = rand(0, 2)

$contexts = array(
  "adults surveyed about two streaming services",
  "patients surveyed about two symptoms",
  "drivers surveyed about two route apps"
)
$context = $contexts[$i]

$aNames = array("Service A", "Headache", "App A")
$bNames = array("Service B", "Fatigue", "App B")
$aName = $aNames[$i]
$bName = $bNames[$i]

$aPct = 5 * rand(8, 12)
$bPct = 5 * rand(6, 10)
$jointPct = 5 * rand(3, 5)
$aOnlyPct = $aPct - $jointPct
$bOnlyPct = $bPct - $jointPct
$unionPct = $aPct + $bPct - $jointPct
$neitherPct = 100 - $unionPct

$aDec = $aPct / 100
$bDec = $bPct / 100
$jointDec = $jointPct / 100

$answer[0] = $aOnlyPct / 100
$answer[1] = $bOnlyPct / 100
$answer[2] = $neitherPct / 100
$answer[3] = $unionPct / 100

$vw = 440
$vh = 250
$svg = '<svg width="' . $vw . '" height="' . $vh . '" viewBox="0 0 ' . $vw . ' ' . $vh . '" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Blank Venn diagram with four regions to fill for ' . $aName . ' and ' . $bName . '" style="display:block;margin:12px auto;background:#fff">'
$svg = $svg . '<rect x="6" y="6" width="' . ($vw - 12) . '" height="' . ($vh - 12) . '" fill="none" stroke="#9ca3af" stroke-width="2" rx="8"/>'
$svg = $svg . '<circle cx="175" cy="120" r="88" fill="#1865f2" fill-opacity="0.10" stroke="#1865f2" stroke-width="2"/>'
$svg = $svg . '<circle cx="265" cy="120" r="88" fill="#059669" fill-opacity="0.10" stroke="#059669" stroke-width="2"/>'
$svg = $svg . '<text x="112" y="42" font-family="Arial" font-size="16" font-weight="bold" fill="#1865f2" text-anchor="middle">' . $aName . '</text>'
$svg = $svg . '<text x="328" y="42" font-family="Arial" font-size="16" font-weight="bold" fill="#059669" text-anchor="middle">' . $bName . '</text>'
$svg = $svg . '<text x="128" y="128" font-family="Arial" font-size="17" text-anchor="middle" fill="#b91c1c" font-weight="bold">(a)</text>'
$svg = $svg . '<text x="220" y="128" font-family="Arial" font-size="17" text-anchor="middle" fill="#21242c">' . $jointDec . '</text>'
$svg = $svg . '<text x="312" y="128" font-family="Arial" font-size="17" text-anchor="middle" fill="#b91c1c" font-weight="bold">(b)</text>'
$svg = $svg . '<text x="' . ($vw - 30) . '" y="' . ($vh - 22) . '" font-family="Arial" font-size="17" text-anchor="middle" fill="#b91c1c" font-weight="bold">(c)</text>'
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
      <p><span class="term-label">Start from the middle and work outwards.</span> The overlap is the only region you were handed directly: ' . $jointDec . '. Everything else is a subtraction from it.</p>
      <ul>
        <li><b>(a) ' . $aName . ' only.</b> `P(A) - P(A and B) = ' . $aDec . ' - ' . $jointDec . ' = ' . ($aOnlyPct / 100) . '`. P(A) is the WHOLE circle, so taking out the overlap leaves the crescent.</li>
        <li><b>(b) ' . $bName . ' only.</b> `' . $bDec . ' - ' . $jointDec . ' = ' . ($bOnlyPct / 100) . '`.</li>
        <li><b>(c) Neither.</b> The union is `' . ($aOnlyPct / 100) . ' + ' . $jointDec . ' + ' . ($bOnlyPct / 100) . ' = ' . ($unionPct / 100) . '`, so what is left outside both circles is `1 - ' . ($unionPct / 100) . ' = ' . ($neitherPct / 100) . '`.</li>
        <li><b>(d) The union again</b>, this time from the formula: `' . $aDec . ' + ' . $bDec . ' - ' . $jointDec . ' = ' . ($unionPct / 100) . '`. Same number as the three regions added.</li>
      </ul>
      <p><span class="term-label">The check.</span> All four regions must total 1: `' . ($aOnlyPct / 100) . ' + ' . $jointDec . ' + ' . ($bOnlyPct / 100) . ' + ' . ($neitherPct / 100) . ' = 1`. If yours do not, the usual cause is treating P(A) as the crescent instead of the whole circle &mdash; which double-counts or drops the overlap.</p>
      <p><span class="term-label">Why this direction is the hard one.</span> A probability like P(A) is not a place on the diagram. It is two regions added, and the diagram only becomes usable once it is split back apart.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 6px 0;">Among $context: <b>P($aName) = $aDec</b>, <b>P($bName) = $bDec</b>, and <b>P(both) = $jointDec</b>.</p>
    $svg
    <p style="margin:6px 0 0 0; color:#374151; font-size:14px;">Only the overlap is filled in. Work out the three regions marked <b style="color:#b91c1c;">(a)</b>, <b style="color:#b91c1c;">(b)</b> and <b style="color:#b91c1c;">(c)</b>. Enter decimals or fractions.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> <b>$aName only</b> $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> <b>$bName only</b> $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> <b>Neither</b> $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> <b>P($aName or $bName)</b>, from the addition rule. $answerbox[3]
  </div>
</div>

// === ANSWER ===

$sol
