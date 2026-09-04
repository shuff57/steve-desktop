// === NAME - DESCRIPTION: Sort a Purchase Order into All Three Data Types - Supply Order - From one shopping list, identify which set of values is quantitative discrete, which is quantitative continuous, and which is qualitative ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices", "choices")

// Randomize the buyer, the supplier, the item categories, the counts and the measured amounts.
$names = array("Grant Halloway", "Priya Raman", "Marcus Ozuna", "Ines Duarte", "Tobias Fenn", "Naomi Whitfield", "Andre Bekele", "Clara Nystrom")
$stores = array("Redmond Supply Co.", "Cedar Falls Hardware", "Northfield Industrial", "Port Haven Trade Depot", "Ashford Builders Supply")
$buyer = $names[rand(0, count($names)-1)]
$store = $stores[rand(0, count($stores)-1)]

// Two solid categories (sold by weight) and one liquid category (sold by volume).
$solidCat = array("nails", "screws", "bolts", "washers", "brackets")
$solidTypes = array(
  array("box nails", "roofing nails", "finishing nails", "masonry nails"),
  array("wood screws", "machine screws", "set screws", "socket screws"),
  array("carriage bolts", "hex bolts", "anchor bolts", "eye bolts"),
  array("flat washers", "lock washers", "fender washers", "spring washers"),
  array("corner brackets", "shelf brackets", "angle brackets", "joist brackets")
)
$liquidCat = array("oil", "paint", "solvent", "adhesive")
$liquidTypes = array(
  array("machine oil", "cutting oil", "chain oil", "hydraulic oil"),
  array("primer paint", "enamel paint", "latex paint", "epoxy paint"),
  array("mineral spirits", "paint thinner", "degreaser", "acetone"),
  array("wood glue", "contact cement", "epoxy resin", "construction adhesive")
)

$s1 = rand(0, count($solidCat)-1)
$s2 = rand(0, count($solidCat)-1) where ($s2 != $s1)
$lq = rand(0, count($liquidCat)-1)
$offA = rand(0, 3)
$offB = rand(0, 3)
$offC = rand(0, 3)

$catA = $solidCat[$s1]
$catB = $liquidCat[$lq]
$catC = $solidCat[$s2]

// How many types of each were bought. The middle category stays at a single type.
$nA = rand(2, 3)
$nB = 1
$nC = rand(2, 4)
$nA1 = $nA - 1
$nB1 = $nB - 1
$nC1 = $nC - 1

$listA = ""
$listB = ""
$listC = ""
$namesAll = ""
$amountsAll = ""
$sepA = ""
$sepB = ""
$sepC = ""
$sepN = ""
$sepM = ""

for ($i=0..$nA1) {
  $tA = $solidTypes[$s1][($offA + $i) % 4]
  $wA = rand(2, 12)/2
  $listA = $listA . $sepA . $wA . " kg " . $tA
  $namesAll = $namesAll . $sepN . $tA
  $amountsAll = $amountsAll . $sepM . $wA . " kg"
  $sepA = ", "
  $sepN = ", "
  $sepM = ", "
}

for ($i=0..$nB1) {
  $tB = $liquidTypes[$lq][($offB + $i) % 4]
  $vB = rand(4, 20)/2
  $listB = $listB . $sepB . $vB . " L " . $tB
  $namesAll = $namesAll . $sepN . $tB
  $amountsAll = $amountsAll . $sepM . $vB . " L"
  $sepB = ", "
}

for ($i=0..$nC1) {
  $tC = $solidTypes[$s2][($offC + $i) % 4]
  $wC = rand(2, 12)/2
  $listC = $listC . $sepC . $wC . " kg " . $tC
  $namesAll = $namesAll . $sepN . $tC
  $amountsAll = $amountsAll . $sepM . $wC . " kg"
  $sepC = ", "
}

$countsAll = $nA . ", " . $nB . ", " . $nC

// The four candidate sets, in role order: 0 = discrete, 1 = continuous, 2 = qualitative, 3 = distractor.
$optText = array(
  "The number of types purchased in each category: " . $countsAll,
  "The measured amounts on the order: " . $amountsAll,
  "The product names on the order: " . $namesAll,
  "The supplier the order was placed with: " . $store . " (the same single value on every line of the order)"
)

// Fix one permutation for all three parts, so the same four sets appear in the same order each time.
$perms = array(
  array(0, 1, 2, 3),
  array(2, 0, 3, 1),
  array(3, 2, 1, 0),
  array(1, 3, 0, 2),
  array(2, 3, 1, 0),
  array(0, 3, 2, 1)
)
$p = $perms[rand(0, count($perms)-1)]
$opts = array($optText[$p[0]], $optText[$p[1]], $optText[$p[2]], $optText[$p[3]])

$pos = array(0, 0, 0, 0)
for ($i=0..3) {
  $pos[$p[$i]] = $i
}

$choices[0] = $opts
$choices[1] = $opts
$choices[2] = $opts
$noshuffle[0] = "all"
$noshuffle[1] = "all"
$noshuffle[2] = "all"

$answer[0] = $pos[0]
$answer[1] = $pos[1]
$answer[2] = $pos[2]

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
  .term-row { margin:0.6em 0; }
  .term-label { font-weight:700; color:#1865f2; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p>One order can hold all three data types at once. Ask of each set of values: was it <b>counted</b>, was it <b>measured</b>, or does it <b>name a category</b>?</p>
      <div class="term-row"><span class="term-label">a. Quantitative discrete:</span> the number of types purchased in each category: ' . $countsAll . '. These come from counting how many kinds were bought, and a count lands only on whole values. ' . $buyer . ' cannot buy 2.5 types of ' . $catA . '.</div>
      <div class="term-row"><span class="term-label">b. Quantitative continuous:</span> the measured amounts: ' . $amountsAll . '. Each came off a scale or a measuring container, and that scale can always be read more finely, so fractions and decimals are possible.</div>
      <div class="term-row"><span class="term-label">c. Qualitative (categorical):</span> the product names: ' . $namesAll . '. These name a category rather than a quantity; there is no average product name.</div>
      <p style="margin-top:1em;"><b>Why the supplier is not the answer to any part:</b> ' . $store . ' is one label attached to the whole order, the same on every line, so it is not one of the data sets recorded here. Being made of words does not by itself make something the qualitative data set in this order.</p>
      <p><b>The trap to avoid:</b> the counts and the weights are both numbers, so "it is a number" does not settle it. Counting gives whole things: "how many?" is discrete. Measuring gives a reading on a scale: "how much?" is continuous.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;"><b>$buyer</b>, a purchasing manager, places one order with <b>$store</b> and has to describe it to the accountant. The order is:</p>
    <ul style="margin:12px 0 0 1.2em;">
      <li>$nA types of $catA ($listA)</li>
      <li>$nB type of $catB ($listB)</li>
      <li>$nC types of $catC ($listC)</li>
    </ul>
    <p style="margin:12px 0 0 0;">The same four sets of values are offered in each part below. Sort them.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which set is <b>quantitative discrete</b>? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which set is <b>quantitative continuous</b>? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which set is <b>qualitative (categorical)</b>? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
