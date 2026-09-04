// === NAME - DESCRIPTION: Find the Value a Given Number of Standard Deviations from the Mean - Convert between a z-score and a raw value in both directions, and read what a negative z-score says about a measurement ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The bank already computes a z-score from a raw value. This question runs the relationship the OTHER
// way, given how many standard deviations from the mean, produce the value, which is the form the
// empirical rule and every later normal-distribution question need.
//
// `sigma` is chosen so that a half-deviation is still a whole number, so no part produces a value with
// a trailing decimal and the arithmetic never distracts from the idea. The negative direction is always
// exercised, because "1.5 standard deviations BELOW" is where sign errors live.
$anstypes = array("number", "number", "number", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "Scores on a standardized reading test are summarized by their mean and standard deviation."
  $unitWord = "points"
  $thing = "score"
  $lowWord = "read less well than average"
}
else {
  $intro = "The weights of bags of flour leaving a mill are summarized by their mean and standard deviation."
  $unitWord = "grams"
  $thing = "weight"
  $lowWord = "weigh less than average"
}

$mu = 10 * rand(8, 20)
$sigma = 2 * rand(4, 9)

$kUp = rand(2, 3)
$xUp = $mu + $kUp * $sigma
$xDownHalf = $mu - 1.5 * $sigma

// Part (c) runs backwards: a raw value is given and the z-score is wanted. Built from a whole
// number of deviations so z comes out exact.
$kBack = rand(1, 2)
$xBack = $mu - $kBack * $sigma
$zBack = 0 - $kBack

$answer[0] = $xUp
$answer[1] = $xDownHalf
$abstolerance[1] = 0.005
$answer[2] = $zBack
$abstolerance[2] = 0.005
$answerboxsize = 7

$questions[3] = array(
  "It sits BELOW the mean. The sign says which side of the mean the value is on, and the size says how many standard deviations away it is.",
  "It sits above the mean, because a standard deviation is never negative.",
  "It means the measurement was recorded incorrectly, since a value cannot be negative.",
  "It sits exactly on the mean; only the size of a z-score carries information."
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
      <p><span class="term-label">The one relationship, both ways.</span> `x = mu + z sigma` going out from the mean, and `z = (x - mu)/sigma` coming back. Everything below is one of those two.</p>
      <p><span class="term-label">Part (a): ' . $kUp . ' deviations ABOVE.</span> Start at the mean and add ' . $kUp . ' whole steps of ' . $sigma . ':</p>
      <p style="text-align:center;">`x = ' . $mu . ' + ' . $kUp . '(' . $sigma . ') = ` <b>' . $xUp . '</b> ' . $unitWord . '</p>
      <p><span class="term-label">Part (b): 1.5 deviations BELOW.</span> Same move, subtracting, and the step can be a fraction of a deviation:</p>
      <p style="text-align:center;">`x = ' . $mu . ' - 1.5(' . $sigma . ') = ` <b>' . $xDownHalf . '</b> ' . $unitWord . '</p>
      <p>"Below" is the whole instruction in that part. Adding instead of subtracting is the most common error here, and the answer still looks plausible, so it rarely gets caught by eye.</p>
      <p><span class="term-label">Part (c): backwards.</span> Now the value is known and the z-score is wanted:</p>
      <p style="text-align:center;">`z = (' . $xBack . ' - ' . $mu . ')/' . $sigma . ' = ` <b>' . $zBack . '</b></p>
      <p><span class="term-label">What the sign is for.</span> A z-score carries two pieces of information at once. Its SIZE is how many standard deviations from the mean the value lies; its SIGN is which side. So a ' . $thing . ' with `z = ' . $zBack . '` is ' . $kBack . ' standard deviation&#40;s&#41; BELOW the mean: it would ' . $lowWord . '. Dropping the minus sign throws away half the answer.</p>
      <p><span class="term-label">Why bother converting at all.</span> A z-score has no units, so it lets you compare a ' . $thing . ' against a measurement of something else entirely: which is exactly what the next questions in this section do.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$intro</p>
    <p style="margin:12px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;">Mean `mu = $mu` $unitWord &nbsp;&nbsp;&nbsp; Standard deviation `sigma = $sigma` $unitWord</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What $thing is <b>$kUp standard deviations above</b> the mean? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What $thing is <b>1.5 standard deviations below</b> the mean? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> A $thing of <b>$xBack $unitWord</b> was recorded. What is its z-score? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> What does a <b>negative</b> z-score tell you about a measurement? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
