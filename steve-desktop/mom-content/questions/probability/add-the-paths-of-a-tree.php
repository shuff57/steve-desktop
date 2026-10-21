// === NAME - DESCRIPTION: Add the Paths of a Tree - Multiply along two paths that end in the same outcome, then add them to get the overall probability ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 3.5. A two-stage tree given in words, not draws. The first stage splits the group, the
// second stage has a different rate in each branch. P(outcome) is the sum of the two paths that end
// in it. Part d asks which paths are added, so the student says the rule rather than only using it.
$anstypes = array("numfunc", "numfunc", "numfunc", "choices")

$i = rand(0, 3)
$stories = array("At a gym, X% of the members are students. Y% of the students renew their membership, and Z% of the other members renew.", "At a store, X% of the shoppers use the app. Y% of the app users buy something, and Z% of the other shoppers buy something.", "In a town, X% of the households have a garden. Y% of the households with a garden compost, and Z% of the others compost.", "On a campus, X% of the students live in a dorm. Y% of the dorm students own a bike, and Z% of the other students own a bike.")
$s = $stories[$i]
$names1 = array("student", "app user", "household with a garden", "dorm student")
$names2 = array("renews", "buys", "composts", "owns a bike")
$g1 = $names1[$i]
$notnames = array("not a student", "not an app user", "no garden", "not a dorm student")
$n1 = $notnames[$i]
$o1 = $names2[$i]
$negs = array("does not renew", "does not buy", "does not compost", "does not own a bike")
$o1n = $negs[$i]

$x = 10 * rand(3, 7)
$y = 10 * rand(4, 9)
$z = 10 * rand(1, 4)
$text = str_replace("Z", $z, str_replace("Y", $y, str_replace("X", $x, $s)))
$pX = $x / 100
$pY = $y / 100
$pZ = $z / 100
$pN = 1 - $pX
$path1 = $pX * $pY
$path2 = $pN * $pZ
$total = $path1 + $path2

$questions[3] = array(
  "Add the two paths that end in the outcome",
  "Multiply the two paths that end in the outcome",
  "Use only the path with the larger probability",
  "Subtract the smaller path from the larger"
)
$answer[0] = $path1
$answer[1] = $path2
$answer[2] = $total
$answer[3] = 0
$displayformat[3] = "select"

// The tree, drawn as inline SVG (MyOpenMath has no tree primitive). Every branch carries its percent;
// the leaves are named but their probabilities are left for the student to find in parts a to c.
$tw = 640
$th = 250
$svg = '<svg width="' . $tw . '" height="' . $th . '" viewBox="0 0 ' . $tw . ' ' . $th . '" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Tree diagram of the two stages" style="display:block;margin:12px auto;background:#fff">'
$svg = $svg . '<circle cx="30" cy="125" r="5" fill="#21242c"/>'
$svg = $svg . '<line x1="35" y1="125" x2="165" y2="62" stroke="#1865f2" stroke-width="2"/>'
$svg = $svg . '<line x1="35" y1="125" x2="165" y2="188" stroke="#059669" stroke-width="2"/>'
$svg = $svg . '<text x="92" y="80" text-anchor="end" font-family="Arial" font-size="13" fill="#1865f2">' . $x . '%</text>'
$svg = $svg . '<text x="92" y="182" text-anchor="end" font-family="Arial" font-size="13" fill="#059669">' . (100 - $x) . '%</text>'
$svg = $svg . '<text x="208" y="49" text-anchor="end" font-family="Arial" font-size="14" font-weight="bold" fill="#1865f2">' . $g1 . '</text>'
$svg = $svg . '<text x="208" y="207" text-anchor="end" font-family="Arial" font-size="14" font-weight="bold" fill="#059669">' . $n1 . '</text>'
$svg = $svg . '<line x1="215" y1="59" x2="330" y2="28" stroke="#1865f2" stroke-width="2"/>'
$svg = $svg . '<line x1="215" y1="67" x2="330" y2="100" stroke="#1865f2" stroke-width="2"/>'
$svg = $svg . '<line x1="215" y1="183" x2="330" y2="150" stroke="#059669" stroke-width="2"/>'
$svg = $svg . '<line x1="215" y1="191" x2="330" y2="222" stroke="#059669" stroke-width="2"/>'
$svg = $svg . '<text x="262" y="30" text-anchor="middle" font-family="Arial" font-size="13" fill="#374151">' . $y . '%</text>'
$svg = $svg . '<text x="262" y="107" text-anchor="middle" font-family="Arial" font-size="13" fill="#374151">' . (100 - $y) . '%</text>'
$svg = $svg . '<text x="262" y="157" text-anchor="middle" font-family="Arial" font-size="13" fill="#374151">' . $z . '%</text>'
$svg = $svg . '<text x="262" y="236" text-anchor="middle" font-family="Arial" font-size="13" fill="#374151">' . (100 - $z) . '%</text>'
$svg = $svg . '<text x="337" y="33" font-family="Arial" font-size="12" fill="#21242c">' . $g1 . ' and ' . $o1 . '</text>'
$svg = $svg . '<text x="337" y="105" font-family="Arial" font-size="12" fill="#21242c">' . $g1 . ' and ' . $o1n . '</text>'
$svg = $svg . '<text x="337" y="155" font-family="Arial" font-size="12" fill="#21242c">' . $n1 . ' and ' . $o1 . '</text>'
$svg = $svg . '<text x="337" y="227" font-family="Arial" font-size="12" fill="#21242c">' . $n1 . ' and ' . $o1n . '</text>'
$svg = $svg . '<text x="30" y="12" font-family="Arial" font-size="12" fill="#6b7280">first stage</text>'
$svg = $svg . '<text x="228" y="12" font-family="Arial" font-size="12" fill="#6b7280">second stage</text>'
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
      <p><span class="term-label">The tree.</span> The first branch splits into "' . $g1 . '" with probability ' . $pX . ' and "not" with probability ' . $pN . '. Each of those branches splits again into "' . $o1 . '" or not, with a different rate: ' . $pY . ' on the first branch and ' . $pZ . ' on the second.</p>
      <p><span class="term-label">(a) First path.</span> Multiply along the path: `' . $pX . ' xx ' . $pY . ' = ' . $path1 . '`.</p>
      <p><span class="term-label">(b) Second path.</span> `' . $pN . ' xx ' . $pZ . ' = ' . $path2 . '`.</p>
      <p><span class="term-label">(c) Overall.</span> The two paths cannot happen together, so add them: `' . $path1 . ' + ' . $path2 . ' = ' . $total . '`.</p>
      <p><span class="term-label">(d) The rule.</span> Multiply along a path, add the paths that end in the same outcome.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$text One is picked at random.</p>
    $svg
    <p style="margin:6px 0 0 0; color:#374151; font-size:14px;">Enter each probability as a decimal.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> <b>P($g1 and $o1)</b> $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> <b>P($n1 and $o1)</b> $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> <b>P($o1)</b> overall $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> How do you get the answer to part c from parts a and b?<br><br>$answerbox[3]
  </div>
</div>

// === ANSWER ===

$sol
