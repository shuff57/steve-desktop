// === NAME - DESCRIPTION: Conditional from a Venn - Compute a conditional both directions, where the denominator is a circle total rather than the grand total ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 3.5. The point of a conditional on a Venn is WHICH circle you are restricted to: the
// denominator is the total of that circle only, never the grand total. Asking both directions forces
// the student to notice the denominator changes, and the two numbers are deliberately different so
// the asymmetry is visible, not just asserted.
//
// $aOnly and $bOnly are drawn from disjoint ranges, so the two circle totals always differ and the
// two conditionals can never come out the same. The diagram is inline SVG.
$anstypes = array("numfunc", "numfunc", "numfunc", "numfunc")

$i = rand(0, 2)

// "$total $context" opens the stem, so each string has to survive being read as a whole sentence.
// "100 customers who ordered a drink and who ordered dessert" says all 100 did both: the exact
// claim the Venn then contradicts. "sorted by whether" is what keeps the count neutral.
$contexts = array(
  "students in a class, sorted by whether they play an instrument and whether they play a sport",
  "customers, sorted by whether they ordered a drink and whether they ordered dessert",
  "gym members, sorted by whether they go mornings and whether they take classes"
)
$context = $contexts[$i]

$aNames = array("play an instrument", "ordered a drink", "go mornings")
$bNames = array("play a sport", "ordered dessert", "take classes")
$aName = $aNames[$i]
$bName = $bNames[$i]

$aShort = array("instrument", "drink", "morning")
$bShort = array("sport", "dessert", "class")
$aS = $aShort[$i]
$bS = $bShort[$i]

$both = 4 * rand(3, 8)
$aOnly = 4 * rand(6, 10)      // 24, 28, 32, 36, 40
$bOnly = 4 * rand(3, 4)       // 12, 16: disjoint from $aOnly, so totals never tie
$neither = 4 * rand(4, 9)
$total = $aOnly + $both + $bOnly + $neither
$aTotal = $aOnly + $both
$bTotal = $bOnly + $both

$pBGivenA = $both / $aTotal
$pAGivenB = $both / $bTotal

$answer[0] = $pBGivenA
$answer[1] = $pAGivenB
$answer[2] = $aTotal
$answer[3] = $bTotal

// --- the Venn ---
$vw = 440
$vh = 250
$svg = '<svg width="' . $vw . '" height="' . $vh . '" viewBox="0 0 ' . $vw . ' ' . $vh . '" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Venn diagram of ' . $aName . ' and ' . $bName . ' with four region counts" style="display:block;margin:12px auto;background:#fff">'
$svg = $svg . '<rect x="6" y="6" width="' . ($vw - 12) . '" height="' . ($vh - 12) . '" fill="none" stroke="#9ca3af" stroke-width="2" rx="8"/>'
$svg = $svg . '<circle cx="175" cy="120" r="88" fill="#1865f2" fill-opacity="0.10" stroke="#1865f2" stroke-width="2"/>'
$svg = $svg . '<circle cx="265" cy="120" r="88" fill="#059669" fill-opacity="0.10" stroke="#059669" stroke-width="2"/>'
$svg = $svg . '<text x="112" y="42" font-family="Arial" font-size="15" font-weight="bold" fill="#1865f2" text-anchor="middle">' . $aName . '</text>'
$svg = $svg . '<text x="328" y="42" font-family="Arial" font-size="15" font-weight="bold" fill="#059669" text-anchor="middle">' . $bName . '</text>'
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
      <p><span class="term-label">A conditional shrinks the space to ONE circle.</span> &ldquo;Given ' . $bName . '&rdquo; means we only care about people who did, and that is the whole ' . $bName . ' circle: ' . $bTotal . ' people, the overlap plus the ' . $bName . '-only crescent. The grand total ' . $total . ' is no longer the denominator.</p>
      <ul>
        <li><b>(a) P(' . $bS . ' | ' . $aS . ').</b> Restrict to the ' . $aName . ' circle, which holds ' . $aTotal . ' = ' . $aOnly . ' + ' . $both . '. Of those, ' . $both . ' also ' . $bName . ': `' . $both . ' -: ' . $aTotal . ' = ' . $pBGivenA . '`.</li>
        <li><b>(b) P(' . $aS . ' | ' . $bS . ').</b> Restrict to the OTHER circle, ' . $bTotal . ' = ' . $bOnly . ' + ' . $both . '. The same ' . $both . ' are in the overlap: `' . $both . ' -: ' . $bTotal . ' = ' . $pAGivenB . '`. Same top, different bottom.</li>
        <li><b>(c) and (d) are the denominators.</b> ' . $aTotal . ' for the first, ' . $bTotal . ' for the second. The circle totals differ, so the two conditionals differ: that asymmetry is the whole lesson.</li>
      </ul>
      <p><span class="term-label">The trap this question exists for.</span> Using the grand total ' . $total . ' for both would give identical numbers and answer neither question. A conditional is only meaningful once you have committed to which circle you are standing inside.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 4px 0;">$total $context. The Venn shows how many fall in each region.</p>
    $svg
    <p style="margin:6px 0 0 0; color:#374151; font-size:14px;">One person is chosen at random. For each conditional, the denominator is a circle total: not the grand total. Enter probabilities as fractions or decimals rounded to 4 places, and the totals as whole numbers.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> <b>P($bS | $aS)</b>, given $aName. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> <b>P($aS | $bS)</b>, given $bName. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> The denominator you used in (a), as a whole number. $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> The denominator you used in (b), as a whole number. $answerbox[3]
  </div>
</div>

// === ANSWER ===

$sol
