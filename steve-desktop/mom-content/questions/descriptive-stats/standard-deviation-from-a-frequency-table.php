// === NAME - DESCRIPTION: Standard Deviation from a Frequency Table - Build the xf and squared-deviation columns a grouped calculation needs, total them, and finish with the sample standard deviation ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The existing 2.7 question walks a standard deviation from a LIST. Data arrives as a frequency table
// at least as often, and the extra step: weighting each squared deviation by how many times that
// value occurred: is where students drop the count and average the distinct values instead.
//
// What the student BUILDS is the two working columns. Once xf and f(x - mean)^2 are on the page the
// answer is one division and one square root, so the columns are the calculation.
//
// The mean is forced onto a whole number by adjusting one frequency, which keeps every deviation an
// integer and every squared deviation exact: otherwise the middle column is a wall of decimals and
// the question tests typing rather than method.
$anstypes = array("number", "number", "number", "number", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "A pharmacy recorded how many prescriptions each customer collected."
  $colName = "Prescriptions collected"
  $unitWord = "prescriptions"
  $who = "customers"
}
else {
  $intro = "A tutor recorded how many practice problems each student finished in one session."
  $colName = "Problems finished"
  $unitWord = "problems"
  $who = "students"
}

// Five values, evenly spaced, with the mean pinned to the middle one by symmetric frequencies.
$step = 2
$x0 = rand(4, 8)
$x1 = $x0 + $step
$x2 = $x1 + $step
$x3 = $x2 + $step
$x4 = $x3 + $step

// Symmetric frequencies put the mean exactly on $x2, whatever the random draw.
$fOut = rand(2, 5)
$fMid = rand(3, 6)
$fCen = rand(4, 8)
$f0 = $fOut
$f1 = $fMid
$f2 = $fCen
$f3 = $fMid
$f4 = $fOut

$n = $f0 + $f1 + $f2 + $f3 + $f4
$sumXF = $x0 * $f0 + $x1 * $f1 + $x2 * $f2 + $x3 * $f3 + $x4 * $f4
$mean = $x2

// Deviations are -2step, -step, 0, step, 2step. Squared and weighted:
$d0 = $x0 - $mean
$d4 = $x4 - $mean
$sq0 = $d0 * $d0
$sq1 = ($x1 - $mean) * ($x1 - $mean)
$ssq = $sq0 * $f0 + $sq1 * $f1 + 0 * $f2 + $sq1 * $f3 + $sq0 * $f4

$nm1 = $n - 1
$variance = $ssq / $nm1
$sd = round(sqrt($variance), 2)
$varShown = round($variance, 3)

$answer[0] = $sumXF
$answer[1] = $mean
$answer[2] = $ssq
$answer[3] = $sd
$abstolerance[3] = 0.015
$answerboxsize = 7

$questions[4] = array(
  "It weights each squared deviation by how many " . $who . " had that value. Leaving `f` out would treat a value that occurred eight times as though it had occurred once.",
  "It converts the squared deviations back into the original units.",
  "It is only there to make the arithmetic tidier; the answer is the same without it.",
  "It corrects for the fact that the sample standard deviation divides by `n - 1`."
)
$answer[4] = 0

$solutionguide = '
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
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><span class="term-label">Step 1: the xf column and the mean.</span> Each value has to count once for every ' . $who . ' who had it, so multiply value by frequency and total the column:</p>
      <p style="text-align:center;">`sum xf = ' . $sumXF . '` over `n = ' . $n . '` ' . $who . ', so `bar x = ' . $sumXF . ' -: ' . $n . ' = ` <b>' . $mean . '</b> ' . $unitWord . '</p>
      <p>Averaging the five DISTINCT values instead of the ' . $n . ' observations is the first place this goes wrong.</p>
      <p><span class="term-label">Step 2: the squared-deviation column.</span> For each row take `x - bar x`, square it, then multiply by that row&rsquo;s frequency. Squaring removes the signs: without it the deviations cancel to zero every time, which is why the raw deviations are never totalled.</p>
      <table style="border-collapse:collapse; margin:8px 0; font-size:15px;">
        <tr style="background:#f0f4ff;"><th style="border:1px solid #d1d5db; padding:5px 12px;">x</th><th style="border:1px solid #d1d5db; padding:5px 12px;">f</th><th style="border:1px solid #d1d5db; padding:5px 12px;">x &minus; x&#772;</th><th style="border:1px solid #d1d5db; padding:5px 12px;">f(x &minus; x&#772;)&sup2;</th></tr>
        <tr><td style="border:1px solid #d1d5db; padding:5px 12px; text-align:center;">' . $x0 . '</td><td style="border:1px solid #d1d5db; padding:5px 12px; text-align:center;">' . $f0 . '</td><td style="border:1px solid #d1d5db; padding:5px 12px; text-align:center;">' . $d0 . '</td><td style="border:1px solid #d1d5db; padding:5px 12px; text-align:center;">' . ($sq0 * $f0) . '</td></tr>
        <tr><td style="border:1px solid #d1d5db; padding:5px 12px; text-align:center;">' . $x1 . '</td><td style="border:1px solid #d1d5db; padding:5px 12px; text-align:center;">' . $f1 . '</td><td style="border:1px solid #d1d5db; padding:5px 12px; text-align:center;">' . ($x1 - $mean) . '</td><td style="border:1px solid #d1d5db; padding:5px 12px; text-align:center;">' . ($sq1 * $f1) . '</td></tr>
        <tr><td style="border:1px solid #d1d5db; padding:5px 12px; text-align:center;">' . $x2 . '</td><td style="border:1px solid #d1d5db; padding:5px 12px; text-align:center;">' . $f2 . '</td><td style="border:1px solid #d1d5db; padding:5px 12px; text-align:center;">0</td><td style="border:1px solid #d1d5db; padding:5px 12px; text-align:center;">0</td></tr>
        <tr><td style="border:1px solid #d1d5db; padding:5px 12px; text-align:center;">' . $x3 . '</td><td style="border:1px solid #d1d5db; padding:5px 12px; text-align:center;">' . $f3 . '</td><td style="border:1px solid #d1d5db; padding:5px 12px; text-align:center;">' . ($x3 - $mean) . '</td><td style="border:1px solid #d1d5db; padding:5px 12px; text-align:center;">' . ($sq1 * $f3) . '</td></tr>
        <tr><td style="border:1px solid #d1d5db; padding:5px 12px; text-align:center;">' . $x4 . '</td><td style="border:1px solid #d1d5db; padding:5px 12px; text-align:center;">' . $f4 . '</td><td style="border:1px solid #d1d5db; padding:5px 12px; text-align:center;">' . $d4 . '</td><td style="border:1px solid #d1d5db; padding:5px 12px; text-align:center;">' . ($sq0 * $f4) . '</td></tr>
        <tr style="background:#f8fafc;"><td style="border:1px solid #d1d5db; padding:5px 12px;" colspan="3"><b>Total</b></td><td style="border:1px solid #d1d5db; padding:5px 12px; text-align:center;"><b>' . $ssq . '</b></td></tr>
      </table>
      <p><span class="term-label">Step 3: divide by `n - 1`, then take the root.</span> This is a SAMPLE, so the divisor is ' . $n . ' &minus; 1 = ' . $nm1 . ':</p>
      <p style="text-align:center;">`s^2 = ' . $ssq . ' -: ' . $nm1 . ' = ' . $varShown . '` &nbsp;&nbsp; `s = sqrt(' . $varShown . ') = ` <b>' . $sd . '</b> ' . $unitWord . '</p>
      <p><span class="term-label">Does the answer look right?</span> A standard deviation is a typical distance from the mean, so it should be comfortably smaller than the full spread of the data &#40;here ' . $x0 . ' to ' . $x4 . '&#41; and never negative. Roughly a quarter to a third of the range is the usual ballpark.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$intro The results for all $n $who are below. Treat this as a <b>sample</b>.</p>
    <table style="border-collapse:collapse; margin:12px 0 0 0; background:#fff;">
      <tr style="background:#f0f4ff;">
        <th style="border:1px solid #d1d5db; padding:6px 18px;">$colName (x)</th>
        <th style="border:1px solid #d1d5db; padding:6px 18px;">Frequency (f)</th>
      </tr>
      <tr><td style="border:1px solid #d1d5db; padding:7px 18px; text-align:center;">$x0</td><td style="border:1px solid #d1d5db; padding:7px 18px; text-align:center;">$f0</td></tr>
      <tr><td style="border:1px solid #d1d5db; padding:7px 18px; text-align:center;">$x1</td><td style="border:1px solid #d1d5db; padding:7px 18px; text-align:center;">$f1</td></tr>
      <tr><td style="border:1px solid #d1d5db; padding:7px 18px; text-align:center;">$x2</td><td style="border:1px solid #d1d5db; padding:7px 18px; text-align:center;">$f2</td></tr>
      <tr><td style="border:1px solid #d1d5db; padding:7px 18px; text-align:center;">$x3</td><td style="border:1px solid #d1d5db; padding:7px 18px; text-align:center;">$f3</td></tr>
      <tr><td style="border:1px solid #d1d5db; padding:7px 18px; text-align:center;">$x4</td><td style="border:1px solid #d1d5db; padding:7px 18px; text-align:center;">$f4</td></tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Total the <b>xf</b> column. What is `sum xf`? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the sample mean, `bar x`? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Total the weighted squared deviations. What is `sum f(x - bar x)^2`? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Find the sample standard deviation `s`. Round to two decimal places. $answerbox[3]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">e.</span> What is the <b>f</b> doing in `sum f(x - bar x)^2`? $answerbox[4]
  </div>
</div>

// === ANSWER ===

$solutionguide
