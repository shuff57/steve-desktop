// === NAME - DESCRIPTION: Sample Space and Complement of a Colour - Count the outcomes in a bag of coloured objects, find the probability of one colour, and find the probability of not that colour ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 3.1. The sample space, one probability and its complement, on a bag of coloured objects.
// The complement part is the point: P(not red) is found by counting what is left, or by 1 - P(red).
$anstypes = array("numfunc", "numfunc", "numfunc")

$i = rand(0, 3)
$containers = array("bag", "box", "jar", "bowl")
$objs = array("marbles", "tiles", "beads", "buttons")
$obj1s = array("marble", "tile", "bead", "button")
$container = $containers[$i]
$obj = $objs[$i]
$obj1 = $obj1s[$i]

$cols = diffrands(0, 5, 3)
$names = array("red", "blue", "green", "yellow", "white", "black")
$colA = $names[$cols[0]]
$colB = $names[$cols[1]]
$colC = $names[$cols[2]]

$k = rand(2, 3)
$nA = rand(2, 6)
$nB = rand(2, 6)
$nC = rand(2, 6) * ($k - 2)
$total = $nA + $nB + $nC
$rest = $total - $nA

if ($k == 3) {
  $listing = $nA . " " . $colA . ", " . $nB . " " . $colB . " and " . $nC . " " . $colC
} else {
  $listing = $nA . " " . $colA . " and " . $nB . " " . $colB
}

$pA = $nA / $total
$pNot = $rest / $total

$answer[0] = $total
$answer[1] = $pA
$answer[2] = $pNot


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
      <p><span class="term-label">Sample space.</span> Every ' . $obj1 . ' in the ' . $container . ' is one possible outcome, so there are ' . $total . ' outcomes. They are equally likely.</p>
      <p><span class="term-label">One colour.</span> ' . $nA . ' of the ' . $total . ' are ' . $colA . '. `P(' . $colA . ') = ' . $nA . '/' . $total . ' = ' . $pA . '`.</p>
      <p><span class="term-label">Not that colour.</span> Count what is left: ' . $total . ' - ' . $nA . ' = ' . $rest . ' are not ' . $colA . '. `P(not ' . $colA . ') = ' . $rest . '/' . $total . ' = ' . $pNot . '`. Or use the complement rule, `1 - ' . $pA . ' = ' . $pNot . '`. The two probabilities always add to 1.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A $container holds $listing $obj. One $obj1 is picked at random.</p>
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Enter probabilities as a fraction or a decimal rounded to 4 places.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> How many outcomes are in the sample space? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> <b>P($colA)</b> $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> <b>P(not $colA)</b> $answerbox[2]
  </div>
</div>

// === ANSWER ===

$sol
