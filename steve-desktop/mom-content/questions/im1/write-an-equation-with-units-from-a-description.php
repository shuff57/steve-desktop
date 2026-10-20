// === NAME - DESCRIPTION: Write an Equation with Units from a Description - Read the starting value and the rate from a story, then use output = starting value + rate x input ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.5. Section 2.5.3: output = starting value + rate x input. The story gives the rate as "A every Y",
// so the unit rate is A/Y, negative when the amount goes down. Y is 1, 2, 4 or 5, so the rate has at most
// two decimals. Part c uses an input that is a multiple of Y, so the output is a whole number. For the
// three "goes down" stories B is at least 9A + 5, which keeps the output above zero for k up to 9.
$anstypes = array("number", "number", "number")

$c = rand(0, 5)
$outVA = array("S", "V", "h", "F", "P", "W")
$inVA = array("w", "t", "d", "m", "n", "m")
$outUA = array("dollars", "liters", "centimeters", "kilograms", "percent", "liters")
$inSA = array("week", "minute", "day", "month", "hour", "minute")
$inPA = array("weeks", "minutes", "days", "months", "hours", "minutes")
$signA = array(1, -1, 1, -1, -1, 1)
$l1aA = array("Gear starts a savings account with", "A tank holds", "A plant is", "A feed bin holds", "A phone battery is at", "A pool has")
$l1bA = array("dollars.", "liters of water.", "centimeters tall.", "kilograms of birdseed.", "percent.", "liters of water.")
$l2aA = array("Gear adds", "The tank drains", "The plant grows", "The bin loses", "The battery drops", "A hose adds")
$l2bA = array("dollars", "liters", "centimeters", "kilograms", "percent", "liters")
$aLoA = array(2, 3, 2, 3, 2, 3)
$aHiA = array(9, 15, 8, 12, 5, 15)
$aMulA = array(5, 1, 1, 1, 1, 1)
$bLoA = array(5, 0, 2, 0, 0, 5)
$bHiA = array(30, 0, 12, 0, 0, 20)
$bMulA = array(10, 0, 5, 0, 0, 10)

$outV = $outVA[$c]
$inV = $inVA[$c]
$outU = $outUA[$c]
$inS = $inSA[$c]
$inP = $inPA[$c]
$sgn = $signA[$c]
$l2a = $l2aA[$c]
$l2b = $l2bA[$c]

$nA = $aMulA[$c] * rand($aLoA[$c], $aHiA[$c])
$nY = randfrom(array(1, 2, 4, 5))
$every = "every $nY $inP"
if ($nY == 1) {
  $every = "every $inS"
}
$nB = $bMulA[$c] * rand($bLoA[$c], $bHiA[$c])
if ($sgn < 0) {
  $nB = 9 * $nA + 5 * rand(1, 10)
}
$line1 = $l1aA[$c] . " $nB " . $l1bA[$c]
$line2 = "$l2a $nA $l2b $every."

$sA = $sgn * $nA
$rate = round($sA / $nY, 2)
$kk = rand(3, 9)
$nN = $nY * $kk
$endV = round($nB + $rate * $nN, 2)

$answer[0] = $nB
$answer[1] = $rate
$answer[2] = $endV
$abstolerance[0] = 0.001
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
      <p><span class="term-label">(a) Starting value.</span> The starting value is the output when the input is 0. The story starts at ' . $nB . ' ' . $outU . ', so a = ' . $nB . '.</p>
      <p><span class="term-label">(b) Rate.</span> The unit rate is the change in the output for every increase of 1 in the input: ' . $sA . ' &divide; ' . $nY . ' = ' . $rate . ' ' . $outU . ' per ' . $inS . '. The sign is negative when the amount goes down.</p>
      <p><span class="term-label">The equation.</span> `' . $outV . ' = ' . $nB . ' + (' . $rate . ')' . $inV . '`.</p>
      <p><span class="term-label">(c) Use it.</span> `' . $outV . ' = ' . $nB . ' + (' . $rate . ')(' . $nN . ') = ' . $endV . '`. After ' . $nN . ' ' . $inP . ' the amount is ' . $endV . ' ' . $outU . '.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$line1 $line2</p>
    <p style="margin:10px 0 0 0;">Write the equation in this form, where `$outV` is the amount in $outU and `$inV` is the number of $inP:</p>
    <p style="margin:6px 0 0 0; text-align:center;">`$outV = a + b $inV`</p>
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Enter numbers only. Use a decimal if you need one, and a negative number if the amount goes down.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The starting value `a`, in $outU. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The rate `b`, in $outU per $inS. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Use your equation. How many $outU are there when `$inV = $nN`? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$sol
