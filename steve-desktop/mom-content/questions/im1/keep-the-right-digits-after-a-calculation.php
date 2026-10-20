// === NAME - DESCRIPTION: Keep the Right Digits - Decide which measurement limits a result, then give a rate and a converted amount to that many digits ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.5. Section 2.5.2: a result cannot be more precise than the numbers it came from. One of the
// two measurements has two digits and the other has three, so the two-digit one limits the answer.
// The rate is always between 1 and 10, so two digits means one decimal place. If the rate lands exactly
// on a rounding half, the last digit of the three-digit measurement is nudged so the rounding is not a tie. Part c multiplies a
// two-digit mass by the two-digit factor 2.2. The value 7.5 kg is skipped because 7.5 x 2.2 = 16.5 sits
// exactly on a rounding boundary.
$anstypes = array("choices", "number", "number")

$c = rand(0, 3)
$preA = array("A tank takes in", "A runner covers", "A sprinkler uses", "A belt carries a box")
$dWordA = array("liters", "kilometers", "gallons", "meters")
$midA = array("of water in", "in", "of water in", "in")
$tWordA = array("minutes", "hours", "hours", "seconds")
$tOneA = array("minute", "hour", "hour", "second")
$itemA = array("battery pack", "bag of flour", "toolbox", "backpack")

$pre = $preA[$c]
$dWord = $dWordA[$c]
$mid = $midA[$c]
$tWord = $tWordA[$c]
$tOne = $tOneA[$c]
$item = $itemA[$c]

$lim = rand(0, 1)
$aa = rand(1, 4)
$bb = rand(0, 9)
$cc = rand(1, 9)
$dd = rand(5, 9)
$ee = rand(1, 9)
if ($lim == 0) {
  $nD = (10 * $dd + $ee) / 10
  $nT = (100 * $aa + 10 * $bb + $cc) / 100
} else {
  $nD = (100 * $dd + 10 * $bb + $cc) / 100
  $nT = (10 * $aa + $ee) / 10
}
$t10 = $nD / $nT * 10
if (abs($t10 - floor($t10) - 0.5) < 0.000001) {
  $cc = $cc + 1
  if ($cc == 10) {
    $cc = 2
  }
  if ($lim == 0) {
    $nT = (100 * $aa + 10 * $bb + $cc) / 100
  } else {
    $nD = (100 * $dd + 10 * $bb + $cc) / 100
  }
}
$dTxt = "$nD $dWord"
$tTxt = "$nT $tWord"
$limTxt = $dTxt
$otherTxt = $tTxt
if ($lim == 1) {
  $limTxt = $tTxt
  $otherTxt = $dTxt
}
$vRaw = round($nD / $nT, 4)
$vr = round($nD / $nT, 1)

$mi = 10 * rand(1, 9) + rand(1, 9)
if ($mi == 75) {
  $mi = 74
}
$mass = $mi / 10
$prodRaw = round($mass * 2.2, 2)
$pr = round($mass * 2.2, 1)
if ($prodRaw >= 9.95) {
  $pr = round($mass * 2.2, 0)
}

$questions[0] = array("the $dTxt", "the $tTxt")
$answer[0] = $lim
$answer[1] = $vr
$answer[2] = $pr
$abstolerance[1] = 0.001
$abstolerance[2] = 0.001

$sol = '<style>
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
      <p><span class="term-label">(a) What limits the answer.</span> ' . $limTxt . ' has two digits. ' . $otherTxt . ' has three. A result cannot be more precise than the numbers it came from, so the two-digit measurement limits the answer: keep two digits.</p>
      <p><span class="term-label">(b) The rate.</span> ' . $nD . ' &divide; ' . $nT . ' = ' . $vRaw . ' ' . $dWord . ' per ' . $tOne . ' on a calculator. Keep two digits: ' . $vr . ' ' . $dWord . ' per ' . $tOne . '.</p>
      <p><span class="term-label">(c) The converted mass.</span> ' . $mass . ' &times; 2.2 = ' . $prodRaw . '. The mass and the 2.2 each have two digits, so keep two digits: ' . $pr . ' pounds.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$pre $nD $dWord $mid $nT $tWord.</p>
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Enter numbers only. Keep the number of digits that the less precise measurement has.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which measurement limits how precise the rate can be?<br><br>$answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find the rate in $dWord per $tOne. Give it with the digits from part a. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> A $item has a mass of $mass kilograms. One kilogram is about 2.2 pounds. How many pounds is the $item? The 2.2 has two digits too. $answerbox[2]
  </div>
</div>

// === ANSWER ===

$sol
