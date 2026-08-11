// === NAME - DESCRIPTION: Read a Given Tree - Multiply along two named paths, total the second-stage event across both, and confirm the four leaves sum to 1 ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 3.5. The screening-test shape: a RARE first stage and two very different second stages,
// so the tree is doing real work -- the two "positive" paths are not equally likely and only the
// picture keeps that visible. Every branch is already drawn; the student multiplies along a path
// and adds across paths, then uses the one check a tree gives for free: the four leaves sum to 1.
//
// Drawn as inline SVG -- MyOpenMath has no tree primitive.
$anstypes = array("numfunc", "numfunc", "numfunc", "numfunc")

$i = rand(0, 2)

$contexts = array(
  "Adults are screened for a rare condition",
  "Patients are tested for a rare infection",
  "Employees are screened for a rare marker"
)
$context = $contexts[$i]

$hasNames = array("carry the condition", "have the infection", "carry the marker")
$hasName = $hasNames[$i]

$posNames = array("test positive", "test positive", "screen positive")
$posName = $posNames[$i]

// The negative leaf label has to match the verb the positive one uses -- hard-coding "test negative"
// paired "screen positive" with "test negative" on the third context.
$negNames = array("test negative", "test negative", "screen negative")
$negName = $negNames[$i]

$whoLabels = array("adult", "patient", "employee")
$who = $whoLabels[$i]

// Rare first stage, then two very different second stages.
$p = rand(4, 9)      // P(has the condition) = p/100
$q = 100 - $p        // P(does not) = q/100
$s1 = rand(80, 95)   // given has: P(tests positive) = s1/100
$s2 = rand(4, 9)     // given not: P(tests positive) = s2/100

$pDec = $p / 100
$qDec = $q / 100
$s1Dec = $s1 / 100
$n1Dec = (100 - $s1) / 100
$s2Dec = $s2 / 100
$n2Dec = (100 - $s2) / 100

// The four leaves.
$l1 = $pDec * $s1Dec          // has, positive
$l2 = $pDec * $n1Dec          // has, negative
$l3 = $qDec * $s2Dec          // not, positive
$l4 = $qDec * $n2Dec          // not, negative
$sum = $l1 + $l2 + $l3 + $l4  // must equal 1 on every seed
$pPositive = $l1 + $l3        // the second-stage event, totalled across both paths

$answer[0] = $l1
$answer[1] = $l3
$answer[2] = $pPositive
$answer[3] = $sum

// --- the tree ---
// Leaf text starts at x=340 and the longest label ("not carry the marker and screen negative", 40
// chars of Arial 13) runs ~260px, so a 470-wide viewBox CLIPPED it -- and clipped the two bottom
// leaves to the same visible string, which made them impossible to tell apart. The card's content
// box is 648px, so 640 is the widest this can be and still fit.
$tw = 640
$th = 260
$svg = '<svg width="' . $tw . '" height="' . $th . '" viewBox="0 0 ' . $tw . ' ' . $th . '" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Tree diagram of a two-stage screening test" style="display:block;margin:12px auto;background:#fff">'
$svg = $svg . '<circle cx="30" cy="130" r="5" fill="#21242c"/>'
$svg = $svg . '<line x1="35" y1="130" x2="165" y2="65" stroke="#1865f2" stroke-width="2"/>'
$svg = $svg . '<line x1="35" y1="130" x2="165" y2="195" stroke="#059669" stroke-width="2"/>'
$svg = $svg . '<text x="82" y="88" font-family="Arial" font-size="13" fill="#1865f2">' . $p . '/' . 100 . '</text>'
$svg = $svg . '<text x="82" y="180" font-family="Arial" font-size="13" fill="#059669">' . $q . '/' . 100 . '</text>'
$svg = $svg . '<text x="175" y="64" font-family="Arial" font-size="14" font-weight="bold" fill="#1865f2">' . $hasName . '</text>'
$svg = $svg . '<text x="175" y="202" font-family="Arial" font-size="14" font-weight="bold" fill="#059669">does not ' . $hasName . '</text>'
$svg = $svg . '<line x1="215" y1="62" x2="330" y2="28" stroke="#1865f2" stroke-width="2"/>'
$svg = $svg . '<line x1="215" y1="70" x2="330" y2="105" stroke="#1865f2" stroke-width="2"/>'
$svg = $svg . '<line x1="215" y1="190" x2="330" y2="158" stroke="#059669" stroke-width="2"/>'
$svg = $svg . '<line x1="215" y1="198" x2="330" y2="232" stroke="#059669" stroke-width="2"/>'
$svg = $svg . '<text x="255" y="36" font-family="Arial" font-size="13" fill="#374151">' . $s1 . '/' . 100 . '</text>'
$svg = $svg . '<text x="255" y="100" font-family="Arial" font-size="13" fill="#374151">' . (100 - $s1) . '/' . 100 . '</text>'
$svg = $svg . '<text x="255" y="156" font-family="Arial" font-size="13" fill="#374151">' . $s2 . '/' . 100 . '</text>'
$svg = $svg . '<text x="255" y="228" font-family="Arial" font-size="13" fill="#374151">' . (100 - $s2) . '/' . 100 . '</text>'
$svg = $svg . '<text x="340" y="33" font-family="Arial" font-size="13" fill="#21242c">' . $hasName . ' and ' . $posName . '</text>'
$svg = $svg . '<text x="340" y="110" font-family="Arial" font-size="13" fill="#21242c">' . $hasName . ' and ' . $negName . '</text>'
$svg = $svg . '<text x="340" y="163" font-family="Arial" font-size="13" fill="#21242c">not ' . $hasName . ' and ' . $posName . '</text>'
$svg = $svg . '<text x="340" y="237" font-family="Arial" font-size="13" fill="#21242c">not ' . $hasName . ' and ' . $negName . '</text>'
$svg = $svg . '<text x="30" y="20" font-family="Arial" font-size="12" fill="#6b7280">first stage</text>'
$svg = $svg . '<text x="228" y="20" font-family="Arial" font-size="12" fill="#6b7280">second stage</text>'
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
      <p><span class="term-label">The tree is read, not drawn.</span> Every branch is labelled, so each part is a matter of picking a path and multiplying. The two stages are deliberately not alike &mdash; the first is rare (' . $p . '/100) and the second depends hugely on which branch you are on.</p>
      <ul>
        <li><b>(a) The top path.</b> ' . $hasName . ' then ' . $posName . ': `' . $p . '/100 xx ' . $s1 . '/100 = ' . $l1 . '`. That product is the whole point of a tree &mdash; one leaf, one multiplication.</li>
        <li><b>(b) The bottom path.</b> not ' . $hasName . ' then ' . $posName . ': `' . $q . '/100 xx ' . $s2 . '/100 = ' . $l3 . '`. Notice the second fraction is tiny, because people who do not ' . $hasName . ' rarely ' . $posName . '.</li>
        <li><b>(c) Total P(' . $posName . ').</b> Add the two paths that end in a positive: `' . $l1 . ' + ' . $l3 . ' = ' . $pPositive . '`. The event has no single path &mdash; it crosses both first-stage branches, so the tree answers it by adding across paths.</li>
        <li><b>(d) The free check.</b> Add all four leaves: `' . $l1 . ' + ' . $l2 . ' + ' . $l3 . ' + ' . $l4 . ' = ' . $sum . '`. They must total exactly 1, because every ' . $who . ' is somewhere on the tree. If yours do not, a branch still has the wrong denominator.</li>
      </ul>
      <p><span class="term-label">Why this is worth a tree.</span> The two positive paths are NOT equally likely &mdash; one is large and one is tiny. A formula like &ldquo;add the positives&rdquo; is exactly right but impossible to apply until the tree shows you which products to add.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 4px 0;">$context. The tree is complete &mdash; every branch is labelled.</p>
    $svg
    <p style="margin:6px 0 0 0; color:#374151; font-size:14px;">One $who is chosen at random. Enter answers as fractions or decimals rounded to 4 places.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> P($hasName <b>and</b> $posName), along the top path. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> P(not $hasName <b>and</b> $posName), along the bottom path. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> <b>P($posName)</b>, totalled across both paths. $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Confirm the four leaves sum to 1: give that total. $answerbox[3]
  </div>
</div>

// === ANSWER ===

$sol
