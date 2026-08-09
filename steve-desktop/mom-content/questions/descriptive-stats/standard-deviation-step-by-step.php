// === NAME - DESCRIPTION: Standard Deviation Step by Step - Work a sample standard deviation through its three stages, the mean, the sum of squared deviations and the divide by n minus 1, then say what the finished number measures ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "A cafe recorded how many minutes each of six deliveries took to arrive."
  $unitWord = "minutes"
  $thing = "deliveries"
}
else {
  $intro = "A lab recorded the weight, in grams, of each of six samples."
  $unitWord = "grams"
  $thing = "samples"
}

// The six deviations are a fixed set that sums to zero, so the mean comes out EXACTLY on a whole
// number however the question is randomized. That matters: every later stage is built on the mean,
// so a mean with a recurring decimal would make the squared deviations unfair to compute by hand.
$dev = array(0 - 6, 0 - 3, 0 - 1, 1, 3, 6)
$scale = rand(1, 3)
$mean = 10 * rand(3, 9)

$vals = array(0, 0, 0, 0, 0, 0)
$sumSq = 0
$valueList = ""
for ($k=0..5) {
  $d = $dev[$k] * $scale
  $vals[$k] = $mean + $d
  $sumSq = $sumSq + $d * $d
  if ($k == 0) { $valueList = "" . $vals[$k] }
  if ($k > 0) { $valueList = $valueList . ", " . $vals[$k] }
}

$n = 6
$nMinus = 5
$variance = $sumSq / $nMinus
$sd = round(sqrt($variance), 2)
$varShown = round($variance, 2)

$answer[0] = $mean
$reltolerance[0] = 0.005
$abstolerance[0] = 0.05

$answer[1] = $sumSq
$reltolerance[1] = 0.005
$abstolerance[1] = 0.5

$answer[2] = $sd
$reltolerance[2] = 0.01
$abstolerance[2] = 0.02

$questions[3] = array(
  "Roughly how far a typical value sits from the mean, in the same units as the data.",
  "The distance between the largest and the smallest value.",
  "The average of the values, once the extremes have been removed.",
  "The proportion of values that lie above the mean."
)
$answer[3] = 0

$devRows = ""
for ($k=0..5) {
  $d = $vals[$k] - $mean
  $devRows = $devRows . '<tr><td style="border:1px solid #d1d5db; padding:5px 18px; text-align:center;">' . $vals[$k] . '</td><td style="border:1px solid #d1d5db; padding:5px 18px; text-align:center;">' . $d . '</td><td style="border:1px solid #d1d5db; padding:5px 18px; text-align:center;">' . ($d * $d) . '</td></tr>'
}

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
  .sol-tbl td, .sol-tbl th { border:1px solid #d1d5db; padding:5px 18px; text-align:center; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><span class="term-label">Step 1 &mdash; the mean.</span> Add the six values and divide by 6: `bar x = ' . $mean . '` ' . $unitWord . '. Every later step measures distance from this number, so an error here carries all the way through.</p>
      <p><span class="term-label">Step 2 &mdash; deviations, then square them.</span> Subtract the mean from each value, then square. Squaring is what stops the positive and negative deviations cancelling &mdash; their plain sum is always exactly zero, which is why the deviations cannot simply be averaged.</p>
      <table class="sol-tbl" style="border-collapse:collapse; margin:8px 0;">
        <tr style="background:#f0f4ff;"><th>Value</th><th>`x - bar x`</th><th>`(x - bar x)^2`</th></tr>
        ' . $devRows . '
      </table>
      <p>The squared deviations total <b>' . $sumSq . '</b>.</p>
      <p><span class="term-label">Step 3 &mdash; divide by `n - 1`, then take the square root.</span> This is a SAMPLE, so divide by ' . $nMinus . ' rather than 6: `s^2 = ' . $sumSq . '/' . $nMinus . ' = ' . $varShown . '`. That is the variance, and it is in squared ' . $unitWord . '. Taking the square root brings it back to the units of the data: `s = ' . $sd . '` ' . $unitWord . '.</p>
      <p><span class="term-label">Why `n - 1`.</span> A sample tends to sit a little closer to its own mean than the wider population does, so dividing by `n` would understate the spread. Dividing by one less nudges it back up. Dividing by `n` is the standard slip and it always gives an answer slightly too small.</p>
      <p><b>Answer:</b> (a) ' . $mean . ' &nbsp;&nbsp; (b) ' . $sumSq . ' &nbsp;&nbsp; (c) ' . $sd . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 12px 0;">$intro Treat the six readings as a <b>sample</b>. They are, in $unitWord:</p>
    <p style="margin:0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; font-family:ui-monospace,Menlo,Consolas,monospace; font-size:15px; line-height:1.8;">$valueList</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the <b>mean</b>, `bar x`? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the <b>sum of the squared deviations</b>, `sum(x - bar x)^2`? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is the <b>sample standard deviation</b>, `s`? Give your answer to two decimal places. $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> What does the standard deviation measure? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
