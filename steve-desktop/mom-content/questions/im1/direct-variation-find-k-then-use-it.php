// === NAME - DESCRIPTION: Direct Variation, Find k Then Use It - Get the constant of variation from one pair, then find the output for a new input and the input for a new output ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.5. Section 2.5.4: y = kx, and k is the unit rate. k comes from one pair (k = y / x), then
// y = kx gives the output at x2 and the input at y3. k is a multiple of 0.25 or 0.5 and the inputs are
// whole numbers, so every value is exact. The inputs x1 < x2 < x3 keep the three numbers apart, and the first output is at least 1.5 so no unit reads "1 liters".
$anstypes = array("number", "number", "number")

$c = rand(0, 5)
$leftA = array("A block of", "A weight of", "In", "A wall of", "A worker who works", "A pot of rice for")
$xUA = array("pounds", "newtons", "hours", "square meters", "hours", "servings")
$xOneA = array("pound", "newton", "hour", "square meter", "hour", "serving")
$midA = array("costs", "stretches the spring", "the train travels", "needs", "earns", "uses")
$yUA = array("dollars", "centimeters", "kilometers", "liters of paint", "dollars", "cups of rice")
$introA = array("The price of a block of cheese varies directly with its weight.", "The length a spring stretches varies directly with the weight hung on it.", "The distance a train travels varies directly with the time.", "The paint needed for a wall varies directly with its area.", "A worker is paid by the hour, so the pay varies directly with the hours worked.", "The rice needed varies directly with the number of servings.")
$stepA = array(0.5, 0.25, 8, 0.25, 1, 0.25)
$kLoA = array(4, 3, 5, 3, 10, 3)
$kHiA = array(12, 8, 12, 6, 20, 6)

$left = $leftA[$c]
$xU = $xUA[$c]
$xOne = $xOneA[$c]
$mid = $midA[$c]
$yU = $yUA[$c]
$intro = $introA[$c]

$k = round($stepA[$c] * rand($kLoA[$c], $kHiA[$c]), 2)
$x1 = rand(2, 6)
$y1 = round($k * $x1, 2)
$x2 = rand(7, 12)
$y2 = round($k * $x2, 2)
$x3 = $x2 + rand(2, 5)
$y3 = round($k * $x3, 2)

$answer[0] = $k
$answer[1] = $y2
$answer[2] = $x3
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
      <p><span class="term-label">(a) The constant k.</span> For direct variation, k is y divided by x, the output for 1 unit of input: `k = ' . $y1 . ' / ' . $x1 . ' = ' . $k . '`. That is ' . $k . ' ' . $yU . ' per ' . $xOne . '.</p>
      <p><span class="term-label">The equation.</span> `y = ' . $k . ' x`.</p>
      <p><span class="term-label">(b) The output.</span> Put in x = ' . $x2 . ': `y = ' . $k . ' * ' . $x2 . ' = ' . $y2 . '` ' . $yU . '.</p>
      <p><span class="term-label">(c) The input.</span> Put in y = ' . $y3 . ' and solve: `' . $k . ' x = ' . $y3 . '`, so `x = ' . $y3 . ' / ' . $k . ' = ' . $x3 . '` ' . $xU . '.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$intro $left $x1 $xU $mid $y1 $yU.</p>
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Enter numbers only. Every answer is exact, so there is nothing to round.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the constant of variation `k`, in $yU per $xOne. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> How many $yU go with $x2 $xU? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> How many $xU go with $y3 $yU? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$sol
