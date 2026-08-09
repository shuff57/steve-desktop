// === NAME - DESCRIPTION: Say What a Standard Deviation Means in Context - Give the units of a standard deviation, judge whether a stated value is unusually far from the mean, and pick the sentence that interprets the spread correctly ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Students can compute a standard deviation and still not be able to say what it IS. This question
// never asks them to compute one -- it hands the number over and asks what it means: what units it
// carries, how far from the mean is far, and which plain-English sentence describes it correctly.
//
// The value in part (b) is placed a whole number of deviations out so "how many deviations away" is
// exact, and the two contexts have deliberately different units so the units answer cannot be guessed.
$anstypes = array("choices", "number", "choices", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "The time taken to complete an online form is recorded for many users."
  $unitWord = "seconds"
  $thing = "completion time"
  $unitOpt0 = "seconds"
  $unitOpt1 = "square seconds"
}
else {
  $intro = "The weight of apples picked from an orchard is recorded for a whole harvest."
  $unitWord = "grams"
  $thing = "weight"
  $unitOpt0 = "grams"
  $unitOpt1 = "square grams"
}

$mu = 10 * rand(6, 14)
$sigma = 5 * rand(2, 5)
$kAway = rand(2, 3)
$xFar = $mu + $kAway * $sigma

$questions[0] = array(
  "In " . $unitOpt0 . " &mdash; the same units as the measurements themselves.",
  "In " . $unitOpt1 . ", because the deviations were squared along the way.",
  "It has no units, because it is a kind of average.",
  "In percent, because it describes a proportion of the data."
)
$answer[0] = 0

$answer[1] = $kAway
$abstolerance[1] = 0.005
$answerboxsize = 5

$questions[2] = array(
  "Yes. It sits " . $kAway . " standard deviations above the mean, and anything beyond about two is generally treated as unusual.",
  "No. It is only " . $kAway . " units above the mean, which is a small difference.",
  "There is no way to judge without knowing how many measurements were taken.",
  "Yes, but only because it is above the mean rather than below it."
)
$answer[2] = 0

$questions[3] = array(
  "A typical " . $thing . " differs from the mean by roughly " . $sigma . " " . $unitWord . ".",
  "Every " . $thing . " differs from the mean by exactly " . $sigma . " " . $unitWord . ".",
  "The largest " . $thing . " is " . $sigma . " " . $unitWord . " above the smallest.",
  "About " . $sigma . " percent of the measurements are above the mean."
)
$answer[3] = 0

$twoSd = $mu + 2 * $sigma
$twoSdLow = $mu - 2 * $sigma

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
      <p><span class="term-label">Part (a) &mdash; the units.</span> The deviations are squared partway through the calculation, which is why the VARIANCE comes out in square ' . $unitWord . ' and cannot be interpreted directly. Taking the square root at the end undoes that, so the standard deviation lands back in <b>' . $unitOpt0 . '</b> &mdash; the same units as the data. That is the whole reason the last step is there.</p>
      <p><span class="term-label">Part (b) &mdash; measuring the distance in deviations.</span> The value sits `' . $xFar . ' - ' . $mu . ' = ' . ($xFar - $mu) . '` ' . $unitWord . ' above the mean. Divide by the standard deviation to say how far that is in the units that matter:</p>
      <p style="text-align:center;">`(' . $xFar . ' - ' . $mu . ') -: ' . $sigma . ' = ` <b>' . $kAway . '</b> standard deviations</p>
      <p><span class="term-label">Part (c) &mdash; is that unusual?</span> "Far from the mean" is meaningless in raw ' . $unitWord . ', because whether ' . ($xFar - $mu) . ' is a lot depends entirely on how spread out the data is. Counted in standard deviations it becomes answerable: beyond about two is the usual rule of thumb for unusual, so ' . $kAway . ' deviations out qualifies. Here that means anything above ' . $twoSd . ' or below ' . $twoSdLow . ' would draw attention.</p>
      <p><span class="term-label">Part (d) &mdash; saying it in a sentence.</span> A standard deviation is a TYPICAL distance from the mean &mdash; roughly how far an ordinary observation falls from the center. It is not a distance every value has, and it is not a percentage. The word doing the work is "typical": individual values sit closer or further, and the standard deviation summarizes that in one number.</p>
      <p><span class="term-label">The check that catches most errors.</span> A standard deviation can never be negative, and it should always be small compared with the full spread of the data. An answer bigger than the range means something has gone wrong.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$intro The mean is <b>$mu $unitWord</b> and the standard deviation is <b>$sigma $unitWord</b>.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What units is the standard deviation measured in? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> One $thing of <b>$xFar $unitWord</b> is recorded. How many standard deviations above the mean is it? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Would you call that $thing unusual? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Which sentence correctly says what a standard deviation of $sigma $unitWord means? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
