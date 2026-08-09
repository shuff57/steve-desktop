// === NAME - DESCRIPTION: Sample Versus Population Standard Deviation - Compute both divisors on the same squared-deviation total, see which is larger, and say why a sample divides by one less ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Students meet two formulas that differ by a single character and learn to pick whichever their
// calculator offers. This question hands over the squared-deviation total ready-made -- the arithmetic
// is not the point -- and asks for BOTH answers on the same data, so the difference between them is
// something measured rather than asserted, and part (c) can ask which is larger and why.
//
// The squared total is chosen divisible by both n and n-1, so both answers are exact and the
// comparison is not muddied by rounding.
$anstypes = array("number", "number", "choices", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "A quality inspector measured the diameter, in millimeters, of some ball bearings."
  $unitWord = "millimeters"
}
else {
  $intro = "A biologist measured the wingspan, in centimeters, of some captured birds."
  $unitWord = "centimeters"
}

$n = rand(6, 10)
$nm1 = $n - 1
$k = rand(3, 9)
// Divisible by both n and n-1, which are always coprime for consecutive integers.
$ssq = $k * $n * $nm1

$varPop = $ssq / $n
$varSamp = $ssq / $nm1
$sdPop = round(sqrt($varPop), 3)
$sdSamp = round(sqrt($varSamp), 3)

$answer[0] = $sdSamp
$abstolerance[0] = 0.0015
$answer[1] = $sdPop
$abstolerance[1] = 0.0015
$answerboxsize = 7

$questions[2] = array(
  "The sample standard deviation is larger, because dividing by the smaller number `n - 1` makes the result bigger.",
  "The population standard deviation is larger, because a population contains more values than a sample.",
  "They are equal; the two formulas always give the same answer.",
  "Which is larger depends on whether the data is skewed."
)
$answer[2] = 0

$questions[3] = array(
  "A sample's values sit closer to their own sample mean than to the true population mean, so the squared total comes out too small. Dividing by `n - 1` instead of `n` corrects for that and stops the spread being underestimated.",
  "Dividing by `n - 1` is a convention with no reason behind it; either divisor is acceptable.",
  "It is because a sample always contains exactly one outlier that has to be removed.",
  "It is so that the standard deviation and the variance come out in the same units."
)
$answer[3] = 0

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
      <p><span class="term-label">Same total, two divisors.</span> Both formulas start from the same squared-deviation total, `sum(x - "mean")^2 = ' . $ssq . '`. Only the divisor differs.</p>
      <p><b>Sample:</b> `s = sqrt(' . $ssq . ' -: ' . $nm1 . ') = sqrt(' . $varSamp . ') = ` <b>' . $sdSamp . '</b> ' . $unitWord . '<br>
         <b>Population:</b> `sigma = sqrt(' . $ssq . ' -: ' . $n . ') = sqrt(' . $varPop . ') = ` <b>' . $sdPop . '</b> ' . $unitWord . '</p>
      <p><span class="term-label">Which is larger, and why that is obvious.</span> Dividing by ' . $nm1 . ' rather than ' . $n . ' means dividing by less, so the sample version is always the bigger of the two. It has to be: the correction exists precisely to push the estimate UP.</p>
      <p><span class="term-label">Why a sample needs the correction.</span> A sample&rsquo;s deviations are measured from `bar x`, the sample&rsquo;s own mean &mdash; and `bar x` sits, by construction, in the middle of exactly those values. Distances measured from your own center are the smallest they could be from any center, so the squared total lands slightly too low as an estimate of the population&rsquo;s spread. Shrinking the divisor from ' . $n . ' to ' . $nm1 . ' pushes the answer back up by about the right amount.</p>
      <p>Nothing corrects a population calculation, because there is no estimating going on: `mu` really is the center of the whole group.</p>
      <p><span class="term-label">How to choose in practice.</span> Ask what the numbers ARE, not how many there are. Every member of the group you care about, `sigma` and divide by `n`. A part of it, standing in for the rest, `s` and divide by `n - 1`. On a calculator these are usually offered as `sigma_x` and `s_x`, and taking whichever appears first is the most common way this goes wrong. Note also how small the gap is here &mdash; the two formulas converge as `n` grows, which is why the choice matters most for small samples.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$intro There are <b>$n</b> measurements, and the squared deviations from the mean have already been totalled for you:</p>
    <p style="margin:12px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; text-align:center;">`sum(x - "mean")^2 = $ssq`</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Treating these as a <b>sample</b>, find `s`. Round to three decimal places. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Treating the same numbers as an entire <b>population</b>, find `sigma`. Round to three decimal places. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which of the two is larger, and why? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Why does the sample formula divide by `n - 1` rather than by `n`? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
