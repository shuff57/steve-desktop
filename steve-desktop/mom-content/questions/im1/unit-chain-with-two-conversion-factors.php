// === NAME - DESCRIPTION: Unit Chain with Two Conversion Factors - Change both units of a rate by multiplying by two ratios that equal one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.5. Section 2.5.2: a rate with two units, so the chain needs two conversion factors, one
// for the top unit and one for the bottom unit. Seven conversions; part b is a different one from part a.
// Each starting value is a multiple of a step chosen so the answer has at most one decimal place:
// 15 mph, 9 km/h, whole m/s, halves of ft/s, yd/min, halves of in/s, multiples of 0.8 cups/min.
$anstypes = array("number", "number")

$i = rand(0, 6)
$j = ($i + rand(1, 6)) % 7

$preA = array("A car drives at", "A delivery van drives at", "A cyclist rides at", "A conveyor belt moves at", "A garden bot rolls at", "A sliding door closes at", "A leaky faucet drips")
$fromA = array("miles per hour", "kilometers per hour", "meters per second", "feet per second", "yards per minute", "inches per second", "cups per minute")
$toA = array("feet per second", "meters per second", "kilometers per hour", "inches per minute", "inches per second", "feet per minute", "gallons per hour")
$factsA = array("1 mile = 5280 feet and 1 hour = 3600 seconds", "1 kilometer = 1000 meters and 1 hour = 3600 seconds", "1 kilometer = 1000 meters and 1 hour = 3600 seconds", "1 foot = 12 inches and 1 minute = 60 seconds", "1 yard = 36 inches and 1 minute = 60 seconds", "1 foot = 12 inches and 1 minute = 60 seconds", "1 gallon = 16 cups and 1 hour = 60 minutes")
$mulA = array(15, 9, 1, 0.5, 1, 0.5, 0.8)
$loA = array(1, 2, 2, 3, 5, 3, 1)
$hiA = array(5, 12, 25, 12, 20, 12, 5)
$numA = array(5280, 1000, 3600, 720, 36, 60, 60)
$denA = array(3600, 3600, 1000, 1, 60, 12, 16)
$frFromA = array("text(mi)/text(h)", "text(km)/text(h)", "text(m)/text(s)", "text(ft)/text(s)", "text(yd)/text(min)", "text(in)/text(s)", "text(cups)/text(min)")
$c1A = array("(5280 text(ft))/(1 text(mi))", "(1000 text(m))/(1 text(km))", "(1 text(km))/(1000 text(m))", "(12 text(in))/(1 text(ft))", "(36 text(in))/(1 text(yd))", "(1 text(ft))/(12 text(in))", "(1 text(gal))/(16 text(cups))")
$c2A = array("(1 text(h))/(3600 text(s))", "(1 text(h))/(3600 text(s))", "(3600 text(s))/(1 text(h))", "(60 text(s))/(1 text(min))", "(1 text(min))/(60 text(s))", "(60 text(s))/(1 text(min))", "(60 text(min))/(1 text(h))")
$frToA = array("text(ft)/text(s)", "text(m)/text(s)", "text(km)/text(h)", "text(in)/text(min)", "text(in)/text(s)", "text(ft)/text(min)", "text(gal)/text(h)")

$vA = round($mulA[$i] * rand($loA[$i], $hiA[$i]), 2)
$ansA = round($vA * $numA[$i] / $denA[$i], 2)
$preTxtA = $preA[$i]
$fromTxtA = $fromA[$i]
$toTxtA = $toA[$i]
$factsTxtA = $factsA[$i]
$chainA = "$vA " . $frFromA[$i] . " * " . $c1A[$i] . " * " . $c2A[$i] . " = $ansA " . $frToA[$i]

$vB = round($mulA[$j] * rand($loA[$j], $hiA[$j]), 2)
$ansB = round($vB * $numA[$j] / $denA[$j], 2)
$preTxtB = $preA[$j]
$fromTxtB = $fromA[$j]
$toTxtB = $toA[$j]
$factsTxtB = $factsA[$j]
$chainB = "$vB " . $frFromA[$j] . " * " . $c1A[$j] . " * " . $c2A[$j] . " = $ansB " . $frToA[$j]

$answer[0] = $ansA
$answer[1] = $ansB
$abstolerance[0] = 0.001
$abstolerance[1] = 0.001

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
      <p><span class="term-label">The idea.</span> Each unit in the rate gets its own conversion factor. Write each factor so the unit you want to remove sits on the opposite side from where it already is. Then both old units cancel.</p>
      <p><span class="term-label">(a)</span> `' . $chainA . '`. The units cancel to ' . $toTxtA . ', so the answer is ' . $ansA . '.</p>
      <p><span class="term-label">(b)</span> `' . $chainB . '`. The units cancel to ' . $toTxtB . ', so the answer is ' . $ansB . '.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Change each rate to the new units by multiplying by conversion factors.</p>
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Enter numbers only. Every answer is exact, so there is nothing to round.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> $preTxtA $vA $fromTxtA. Write it in $toTxtA. Use $factsTxtA. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> $preTxtB $vB $fromTxtB. Write it in $toTxtB. Use $factsTxtB. $answerbox[1]
  </div>
</div>

// === ANSWER ===

$sol
