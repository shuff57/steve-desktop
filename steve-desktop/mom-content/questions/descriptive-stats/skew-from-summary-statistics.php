// === NAME - DESCRIPTION: Infer the Skew from the Mean and Median Alone - With no picture available, use the gap between the mean and the median to name the shape, then say which measure of center to report ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The other shape questions in 2.6 hand over a drawing. This one deliberately does not: in practice a
// student meets a mean and a median in a report far more often than a histogram, and the rule runs
// backwards perfectly well. Mean above median means the tail is on the high side; mean below means it
// is on the low side; close together means roughly symmetric.
//
// Which of the three cases appears is randomized, and the numbers are BUILT from the case rather than
// judged after the fact, so no seed can produce a borderline gap where the intended answer is arguable.
$anstypes = array("choices", "choices", "choices")

$caseIdx = rand(0, 2)

$ci = rand(0, 1)
if ($ci == 0) {
  $subject = "the household incomes in a small town"
  $unitWord = "thousand dollars"
  $tailStory = "a handful of very high earners"
  $lowStory = "a handful of households with almost no income"
}
else {
  $subject = "the time visitors spent on a museum website"
  $unitWord = "seconds"
  $tailStory = "a few visitors who left the page open for a very long time"
  $lowStory = "a few visitors who closed the page almost immediately"
}

$med = 10 * rand(4, 9)
// The gap is large in the skewed cases and tiny in the symmetric one, so the three are never
// confusable. It is computed from $caseIdx, so the numbers and the key cannot disagree.
$gap = 5 * rand(3, 6)
$mean = $med
if ($caseIdx == 0) { $mean = $med + $gap }
if ($caseIdx == 1) { $mean = $med - $gap }
if ($caseIdx == 2) { $mean = $med + 1 }

$questions[0] = array(
  "Skewed right &mdash; the mean sits well ABOVE the median, so the tail runs toward the large values",
  "Skewed left &mdash; the mean sits well BELOW the median, so the tail runs toward the small values",
  "Roughly symmetric &mdash; the mean and the median are almost the same",
  "The shape cannot be guessed from a mean and a median at all"
)
$answer[0] = $caseIdx

$questions[1] = array(
  "The median. It is resistant, so the few extreme values do not drag it the way they drag the mean.",
  "The mean. It uses every value, so it is always the more accurate summary.",
  "Either one; with a gap this size the choice makes no practical difference.",
  "Neither; a skewed distribution has no center that can be reported."
)
$answer[1] = 0
if ($caseIdx == 2) {
  $questions[1] = array(
    "Either one. With the mean and the median this close the distribution is roughly symmetric, so both describe the center equally well.",
    "The median only, because the median is always the safer choice.",
    "The mean only, because the mean uses every value.",
    "Neither, because a symmetric distribution has two centers."
  )
  $answer[1] = 0
}

$questions[2] = array(
  "The mean is computed from every value, so observations far out in one tail pull it that way. The median only depends on the middle position, so it stays put.",
  "The median is more sensitive to extreme values than the mean, so it moves first.",
  "The mean and the median always differ by the same amount in any data set.",
  "A gap between them means an arithmetic mistake was made in one of the two."
)
$answer[2] = 0

$shapeName = "roughly symmetric"
if ($caseIdx == 0) { $shapeName = "skewed right" }
if ($caseIdx == 1) { $shapeName = "skewed left" }

$tailWhere = "there is no tail worth speaking of on either side"
if ($caseIdx == 0) { $tailWhere = "a tail runs out toward the LARGE values &mdash; " . $tailStory }
if ($caseIdx == 1) { $tailWhere = "a tail runs out toward the SMALL values &mdash; " . $lowStory }

$relText = "sits almost exactly on"
if ($caseIdx == 0) { $relText = "sits " . $gap . " " . $unitWord . " ABOVE" }
if ($caseIdx == 1) { $relText = "sits " . $gap . " " . $unitWord . " BELOW" }

$reportText = "Either measure is a fair summary here, because there is no long tail dragging the mean away from the middle."
if ($caseIdx == 0) { $reportText = "Report the MEDIAN. The high tail inflates the mean, so quoting the mean would suggest a typical case that sits above most of the data." }
if ($caseIdx == 1) { $reportText = "Report the MEDIAN. The low tail deflates the mean, so quoting the mean would suggest a typical case that sits below most of the data." }

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
      <p><span class="term-label">Part (a) &mdash; compare the two numbers.</span> The mean ' . $relText . ' the median. Whichever side the mean falls on is the side the tail is on, so this distribution is <b>' . $shapeName . '</b>: ' . $tailWhere . '.</p>
      <p>The rule in one line: <b>the mean chases the tail.</b> It is the same rule you use on a histogram, read in the other direction &mdash; from the numbers to the shape rather than from the shape to the numbers.</p>
      <p><span class="term-label">Part (b) &mdash; which number to publish.</span> ' . $reportText . '</p>
      <p><span class="term-label">Part (c) &mdash; why the gap opens at all.</span> The mean adds every observation, so a value far out in a tail contributes its whole distance to the total. The median only asks which value sits in the middle position; moving an extreme value further out does not change which one is in the middle. So the tail moves the mean and leaves the median where it was, and the gap between them is the footprint of the tail.</p>
      <p><span class="term-label">What this does NOT tell you.</span> The size of the gap says the tail exists, not how many observations are in it &mdash; a long tail can hold very few values. And two quite different shapes can produce the same mean and median, so when a picture is available, look at it.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 12px 0;">A report gives two summary numbers for $subject. <b>No histogram is provided.</b></p>
    <table style="border-collapse:collapse; margin:0; background:#fff;">
      <tr style="background:#f0f4ff;"><th style="border:1px solid #d1d5db; padding:7px 22px; text-align:left;">Measure</th><th style="border:1px solid #d1d5db; padding:7px 22px;">Value</th></tr>
      <tr><td style="border:1px solid #d1d5db; padding:7px 22px;">Mean</td><td style="border:1px solid #d1d5db; padding:7px 22px; text-align:center;">$mean $unitWord</td></tr>
      <tr><td style="border:1px solid #d1d5db; padding:7px 22px;">Median</td><td style="border:1px solid #d1d5db; padding:7px 22px; text-align:center;">$med $unitWord</td></tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What shape does this data most likely have? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> If you had to publish ONE number as the typical value, which should it be? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Why do the mean and the median come apart at all? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
