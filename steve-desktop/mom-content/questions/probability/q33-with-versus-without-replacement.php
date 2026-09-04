// === NAME - DESCRIPTION: With Replacement or Without - Compute the same two-draw probability both ways and say which assumption makes the draws independent ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 3.5, and it only works next to q32. Same bag, same event, one word changed. Putting the
// two numbers side by side is what makes "independent" mean something concrete: with replacement the
// second draw forgets the first, without replacement it does not.
//
// Part (d) is the one that matters: a student can produce both numbers and still not know which
// scenario the multiplication rule for INDEPENDENT events applies to.
$anstypes = array("numfunc", "numfunc", "numfunc", "choices")

$i = rand(0, 2)

$contexts = array("A bag of marbles", "A drawer of socks", "A box of pens")
$context = $contexts[$i]

// The container noun on its own. Part (d)'s CORRECT option names the container, so hard-coding
// "bag" there put the wrong word on the right answer for two seeds out of three.
$containers = array("bag", "drawer", "box")
$container = $containers[$i]

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

$pWith = ($r / $total) * ($r / $total)
$pWithout = ($r / $total) * (($r - 1) / $rem)
$diff = round($pWith - $pWithout, 4)

$questions[3] = array(
  "With replacement, because the " . $container . " is restored to its starting state and the second draw is unaffected by the first",
  "Without replacement, because removing one " . $c1 . " makes the remaining " . $item . " a smaller and more predictable group",
  "Both, because the two draws are separate physical actions either way",
  "Neither, because two draws from the same container can never be independent"
)

$answer[0] = $pWith
$answer[1] = $pWithout
$answer[2] = $diff
$answer[3] = 0

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
      <p><span class="term-label">(a) With replacement.</span> The ' . $c1 . ' goes back, so the ' . $container . ' is identical for the second draw: `' . $r . '/' . $total . ' xx ' . $r . '/' . $total . ' = ' . $pWith . '`. Same fraction twice.</p>
      <p><span class="term-label">(b) Without replacement.</span> One ' . $c1 . ' is gone and so is one ' . $item . ' overall: `' . $r . '/' . $total . ' xx ' . ($r - 1) . '/' . $rem . ' = ' . $pWithout . '`. BOTH numbers change: dropping only the numerator is the usual slip.</p>
      <p><span class="term-label">(c) The gap.</span> `' . $pWith . ' - ' . $pWithout . ' = ' . $diff . '`. Without replacement is always the smaller of the two here, because removing one ' . $c1 . ' leaves proportionally fewer behind.</p>
      <p><span class="term-label">(d) Which one is independent.</span> With replacement. Independence means the second draw does not care what the first was, and putting the ' . $c1 . ' back is literally what makes that true. Without replacement the second probability depends on the first branch: which is why q32 needed a tree and this half does not.</p>
      <p><span class="term-label">Worth carrying.</span> "Independent" is not a property of the objects; it is a property of the PROCEDURE. Same ' . $container . ', same colours, one word changed, and the answer changes with it.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 6px 0;">$context holds <b>$r $c1</b> and <b>$b $c2</b> $item, $total in total. Two are drawn, and in both scenarios below the event is <b>both are $c1</b>.</p>
    <p style="margin:6px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;">Only one word differs between the two scenarios. Enter answers as fractions or decimals rounded to 4 places.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Drawn <b>with replacement</b>: P(both $c1). $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Drawn <b>without replacement</b>: P(both $c1). $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> How much larger is (a) than (b)? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> In which scenario are the two draws <b>independent</b>, and why? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$sol
