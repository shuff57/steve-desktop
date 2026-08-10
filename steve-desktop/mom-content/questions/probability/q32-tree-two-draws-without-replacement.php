// === NAME - DESCRIPTION: Two Draws Without Replacement on a Tree - Fill the second-stage branch probabilities, multiply along a path, and add the paths that satisfy the event ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 3.5. The tree is the point: without replacement, the SECOND-stage probabilities differ
// depending on which branch you are on, and that is invisible in a formula but obvious in a picture.
// Part (a) asks for one of those second-stage numbers before any multiplying happens, so a student
// who assumes the draws are identical is caught immediately.
//
// Drawn as inline SVG -- MyOpenMath has no tree primitive.
$anstypes = array("numfunc", "numfunc", "numfunc", "numfunc")

$i = rand(0, 2)

$contexts = array(
  "a bag of marbles",
  "a drawer of socks",
  "a box of pens"
)
$context = $contexts[$i]

$c1Names = array("red", "black", "blue")
$c2Names = array("blue", "white", "green")
$c1 = $c1Names[$i]
$c2 = $c2Names[$i]

$itemNames = array("marbles", "socks", "pens")
$item = $itemNames[$i]

$r = rand(3, 6)
$total = 10
$b = $total - $r
$rem = $total - 1

// Second-stage numerators depend on the first draw -- this is the whole lesson.
$rGivenR = $r - 1
$rGivenB = $r

$pRR = ($r / $total) * ($rGivenR / $rem)
$pBB = ($b / $total) * (($b - 1) / $rem)
$pAtLeastOne = 1 - $pBB
$pExactlyOne = ($r / $total) * ($b / $rem) + ($b / $total) * ($r / $rem)

$answer[0] = $rGivenR / $rem
$answer[1] = $pRR
$answer[2] = $pExactlyOne
$answer[3] = $pAtLeastOne

// --- the tree ---
$tw = 470
$th = 260
$svg = '<svg width="' . $tw . '" height="' . $th . '" viewBox="0 0 ' . $tw . ' ' . $th . '" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Tree diagram for two draws without replacement" style="display:block;margin:12px auto;background:#fff">'
$svg = $svg . '<circle cx="30" cy="130" r="5" fill="#21242c"/>'
$svg = $svg . '<line x1="35" y1="130" x2="165" y2="65" stroke="#1865f2" stroke-width="2"/>'
$svg = $svg . '<line x1="35" y1="130" x2="165" y2="195" stroke="#059669" stroke-width="2"/>'
$svg = $svg . '<text x="88" y="88" font-family="Arial" font-size="13" fill="#1865f2">' . $r . '/' . $total . '</text>'
$svg = $svg . '<text x="88" y="180" font-family="Arial" font-size="13" fill="#059669">' . $b . '/' . $total . '</text>'
$svg = $svg . '<text x="175" y="70" font-family="Arial" font-size="14" font-weight="bold" fill="#1865f2">' . $c1 . '</text>'
$svg = $svg . '<text x="175" y="200" font-family="Arial" font-size="14" font-weight="bold" fill="#059669">' . $c2 . '</text>'
$svg = $svg . '<line x1="215" y1="62" x2="330" y2="28" stroke="#1865f2" stroke-width="2"/>'
$svg = $svg . '<line x1="215" y1="70" x2="330" y2="105" stroke="#1865f2" stroke-width="2"/>'
$svg = $svg . '<line x1="215" y1="190" x2="330" y2="158" stroke="#059669" stroke-width="2"/>'
$svg = $svg . '<line x1="215" y1="198" x2="330" y2="232" stroke="#059669" stroke-width="2"/>'
$svg = $svg . '<text x="258" y="36" font-family="Arial" font-size="13" fill="#b91c1c" font-weight="bold">(a)</text>'
$svg = $svg . '<text x="258" y="100" font-family="Arial" font-size="13" fill="#374151">' . $b . '/' . $rem . '</text>'
$svg = $svg . '<text x="258" y="156" font-family="Arial" font-size="13" fill="#374151">' . $rGivenB . '/' . $rem . '</text>'
$svg = $svg . '<text x="258" y="228" font-family="Arial" font-size="13" fill="#374151">' . ($b - 1) . '/' . $rem . '</text>'
$svg = $svg . '<text x="340" y="33" font-family="Arial" font-size="13" fill="#21242c">' . $c1 . ', ' . $c1 . '</text>'
$svg = $svg . '<text x="340" y="110" font-family="Arial" font-size="13" fill="#21242c">' . $c1 . ', ' . $c2 . '</text>'
$svg = $svg . '<text x="340" y="163" font-family="Arial" font-size="13" fill="#21242c">' . $c2 . ', ' . $c1 . '</text>'
$svg = $svg . '<text x="340" y="237" font-family="Arial" font-size="13" fill="#21242c">' . $c2 . ', ' . $c2 . '</text>'
$svg = $svg . '<text x="30" y="20" font-family="Arial" font-size="12" fill="#6b7280">first draw</text>'
$svg = $svg . '<text x="228" y="20" font-family="Arial" font-size="12" fill="#6b7280">second draw</text>'
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
      <p><span class="term-label">(a) The missing branch is the whole idea.</span> One ' . $c1 . ' has already been taken and not put back, so ' . $rGivenR . ' remain out of ' . $rem . ': `' . $rGivenR . '/' . $rem . '`. Look at the branch below it &mdash; from the ' . $c2 . ' side, ' . $c1 . ' is still `' . $rGivenB . '/' . $rem . '`. Same colour, different probability, because the first draw changed what is left.</p>
      <p><span class="term-label">(b) Multiply ALONG a path.</span> `' . $r . '/' . $total . ' xx ' . $rGivenR . '/' . $rem . ' = ' . $pRR . '`.</p>
      <p><span class="term-label">(c) Add ACROSS paths.</span> Exactly one ' . $c1 . ' happens two ways &mdash; ' . $c1 . ' then ' . $c2 . ', or ' . $c2 . ' then ' . $c1 . ': `' . $r . '/' . $total . ' xx ' . $b . '/' . $rem . '` plus `' . $b . '/' . $total . ' xx ' . $rGivenB . '/' . $rem . '` = ' . $pExactlyOne . '. Two different paths, the same outcome described in words.</p>
      <p><span class="term-label">(d) At least one is easier backwards.</span> The only way to get NO ' . $c1 . ' is the bottom path: `' . $b . '/' . $total . ' xx ' . ($b - 1) . '/' . $rem . ' = ' . $pBB . '`. So at least one is `1 - ' . $pBB . ' = ' . $pAtLeastOne . '`. Adding the other three paths gives the same number and takes three times as long.</p>
      <p><span class="term-label">The check the tree gives you.</span> All four leaf probabilities must sum to 1. If yours do not, a second-stage branch still has the first-stage denominator on it.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 4px 0;">$context holds <b>$r $c1</b> and <b>$b $c2</b> $item. Two are drawn at random <b>without replacement</b> &mdash; the first is not put back before the second is taken.</p>
    $svg
    <p style="margin:6px 0 0 0; color:#374151; font-size:14px;">The branch marked <b style="color:#b91c1c;">(a)</b> is missing. Enter answers as fractions or decimals rounded to 4 places.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The missing branch: given the first was <b>$c1</b>, the probability the second is also <b>$c1</b>. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> <b>P(both $c1)</b> $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> <b>P(exactly one $c1)</b> $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> <b>P(at least one $c1)</b> $answerbox[3]
  </div>
</div>

// === ANSWER ===

$sol
