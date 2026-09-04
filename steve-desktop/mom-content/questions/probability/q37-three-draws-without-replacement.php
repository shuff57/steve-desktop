// === NAME - DESCRIPTION: Three Draws Without Replacement - Multiply along a three-step path, then a complement, then a third-draw conditional given the first two ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 3.5. Three draws is where drawing the tree stops being worth it: eight paths to label
// and a diagram that no longer fits. The multiplication rule carries the reasoning, and the
// solution guide says exactly that. Each draw steps the denominator down by one and the numerator by
// whichever colour was removed, so every fraction is read off the changing collection rather than
// guessed.
//
// $r is kept small relative to the collection so every numerator stays non-negative on every seed.
$anstypes = array("numfunc", "numfunc", "numfunc", "numfunc")

$i = rand(0, 2)

$contexts = array(
  "A bag of marbles",
  "A drawer of socks",
  "A box of pens"
)
$context = $contexts[$i]

$c1Names = array("red", "black", "blue")
$c2Names = array("blue", "white", "green")
$c1 = $c1Names[$i]
$c2 = $c2Names[$i]

$itemNames = array("marbles", "socks", "pens")
$item = $itemNames[$i]

$r = rand(3, 5)
$total = 10
$b = $total - $r
$r3 = $r - 2   // after two $c1 draws
$b3 = $b - 1   // after one $c2 draw
$n3 = $total - 2

$pAll = ($r / $total) * (($r - 1) / ($total - 1)) * (($r - 2) / ($total - 2))
$pNoC2 = $pAll
$pAtLeastOne = 1 - $pNoC2
$pC1Third = $r3 / $n3

$answer[0] = $pAll
$answer[1] = $pAtLeastOne
$answer[2] = $pC1Third
$answer[3] = $r3

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
      <p><span class="term-label">Three draws is where the tree stops earning its space.</span> Eight paths and a diagram that no longer fits the page. The multiplication rule carries the whole argument now: multiply the fraction on each branch, and let each draw change the collection.</p>
      <ul>
        <li><b>(a) All three $c1.</b> `' . $r . '/' . $total . ' xx ' . ($r - 1) . '/' . ($total - 1) . ' xx ' . ($r - 2) . '/' . ($total - 2) . ' = ' . $pAll . '`. Every fraction drops the denominator by 1 and the numerator by 1, because a $c1 keeps coming out and nothing goes back.</li>
        <li><b>(b) At least one $c2.</b> The only way to get none is three $c1: the path from (a), ' . $pAll . '. So at least one $c2 is `1 - ' . $pAll . ' = ' . $pAtLeastOne . '`. Adding the other seven paths gives the same number and takes seven products.</li>
        <li><b>(c) Third $c1, given the first two.</b> Two $c1 have already been removed, and two $item in total: `' . $r3 . ' -: ' . $n3 . ' = ' . $pC1Third . '`. The denominator is ' . $n3 . ' (not 10), and the numerator is ' . $r3 . ' (not ' . $r . '): both have been reduced by what the first two draws took.</li>
        <li><b>(d) The numerator you used in (c).</b> ' . $r3 . ' $c1 remain after the first two draws.</li>
      </ul>
      <p><span class="term-label">The check every draw gives you.</span> On each step the denominator has fallen by exactly one, and no numerator has gone below zero. If a fraction shows a denominator that did not drop, that branch is not reading the changing collection.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 4px 0;">$context holds <b>$r $c1</b> and <b>$b $c2</b> $item, $total in total. Three are drawn at random, <b>without replacement</b>: none is put back.</p>
    <p style="margin:6px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;">With three draws the tree is not worth drawing. Track the collection as each draw removes something, and multiply along the path instead. Enter answers as fractions or decimals rounded to 4 places, and the count in (d) as a whole number.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> <b>P(all three $c1)</b> $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> <b>P(at least one $c2)</b>, using the complement. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Given the first two were both $c1, the probability the third is also $c1. $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> How many $c1 remain to draw the third one from, in the situation in (c)? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$sol
