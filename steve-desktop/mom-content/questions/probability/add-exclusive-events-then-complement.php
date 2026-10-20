// === NAME - DESCRIPTION: Add Mutually Exclusive Probabilities, Then Take the Complement - Find P(A or B) with no overlap to subtract, then P(neither), and say why nothing is subtracted ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 3.3. The addition rule when the two events cannot happen together. One pick is made, so
// the overlap is zero and P(A or B) is just P(A) + P(B). "Neither" is the complement. Part c asks
// why nothing is subtracted, and part d turns the complement into a headcount.

$anstypes = array("numfunc", "numfunc", "choices", "numfunc")

$i = rand(0, 3)
$stories = array("Klaus picks exactly one vacation each year.", "A student picks exactly one lunch from the cafeteria.", "A commuter takes exactly one way to work each day.", "A customer buys exactly one ice cream flavour.")
$story = $stories[$i]
$namesA = array("beach", "pizza", "bus", "chocolate")
$namesB = array("mountains", "salad", "bike", "vanilla")
$nameA = $namesA[$i]
$nameB = $namesB[$i]
$trips = array("years", "school days", "work days", "customers")
$unit = $trips[$i]

$pa = rand(2, 9) * 5
$pb = rand(2, 9) * 5
$pA = $pa / 100
$pB = $pb / 100
$pU = round($pA + $pB, 4)
$pN = round(1 - $pU, 4)
$n = randfrom(array(20, 40, 50, 100))
$count = $pN * $n

$questions[2] = array(
  "The events cannot both happen, so there is no overlap to subtract",
  "The events are independent, so there is no overlap to subtract",
  "The events are rare, so the overlap is too small to matter",
  "Subtraction is only for the complement"
)
$answer[0] = $pU
$answer[1] = $pN
$answer[2] = 0
$answer[3] = $count
$displayformat[2] = "select"

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
      <p><span class="term-label">(a) Add.</span> Only one pick is made, so ' . $nameA . ' and ' . $nameB . ' cannot both happen. `P(' . $nameA . ' and ' . $nameB . ') = 0`, so the addition rule leaves `P(' . $nameA . ' or ' . $nameB . ') = ' . $pA . ' + ' . $pB . ' = ' . $pU . '`.</p>
      <p><span class="term-label">(b) Complement.</span> "Neither" is everything outside those two, so `P(neither) = 1 - ' . $pU . ' = ' . $pN . '`.</p>
      <p><span class="term-label">(c) Why nothing is subtracted.</span> The addition rule subtracts `P(A and B)`. For events that cannot happen together that number is 0.</p>
      <p><span class="term-label">(d) Headcount.</span> `' . $pN . ' xx ' . $n . ' = ' . $count . '` ' . $unit . '.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$story P($nameA) = $pA and P($nameB) = $pB.</p>
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Enter each probability as a decimal.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> <b>P($nameA or $nameB)</b> $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> <b>P(neither)</b> $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> In part a, why is nothing subtracted?<br><br>$answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Out of $n $unit, about how many are neither? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$sol
