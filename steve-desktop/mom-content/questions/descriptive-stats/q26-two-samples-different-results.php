// === NAME - DESCRIPTION: Sampling Variability Between Two Samples - Recognize that two random samples from the same population give different means without either being an error, and predict that larger samples shrink but do not eliminate the gap ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices")

// Four quantities students at a college could be surveyed about. All measured in hours so the
// gap can also be reported in minutes without a per-context conversion.
$ci = rand(0, 3)

$quantityAll = array(
  "the average number of hours students at their college sleep each night",
  "the average number of hours students at their college spend studying outside of class each week",
  "the average number of hours per day students at their college spend on screens outside of class",
  "the average number of hours students at their college work at a paid job each week"
)
$shortAll = array(
  "hours of sleep per night",
  "hours of study time per week",
  "hours of screen time per day",
  "hours of paid work per week"
)
$quantityText = $quantityAll[$ci]
$shortText = $shortAll[$ci]

// Means in tenths, so the two reported values always show a nonzero decimal place.
$wLo = array(6, 11, 3, 12)
$wHi = array(7, 18, 5, 22)
$whole = rand($wLo[$ci], $wHi[$ci])
$d1 = rand(1, 4)
$gapT = rand(2, 5)
$t1 = 10 * $whole + $d1
$t2 = $t1 + $gapT

$m1 = round($t1 / 10, 1)
$m2 = round($t2 / 10, 1)
$gap = round($gapT / 10, 1)
$gapMin = $gapT * 6

// Randomize which researcher reports the higher mean.
$mvals = array($m1, $m2)
$fm = rand(0, 1)
$fmOther = 1 - $fm
$meanA = $mvals[$fm]
$meanB = $mvals[$fmOther]

// Randomize which researcher took the larger sample, so "the bigger sample must be right"
// is not always attached to the same name or the same mean.
$nSmall = 50 * rand(5, 10)
$nBig = $nSmall + 50 * rand(1, 5)
$nvals = array($nSmall, $nBig)
$fn = rand(0, 1)
$fnOther = 1 - $fn
$nA = $nvals[$fn]
$nB = $nvals[$fnOther]
$nA10 = 10 * $nA
$nB10 = 10 * $nB
$nBigger = $nBig

// Which name goes with the larger sample, for the feedback.
$nameAAll = array("Doreen Whitaker", "Priya Raghunathan", "Marcus Ellery", "Ingrid Sandoval")
$nameBAll = array("Jung Park", "Tobias Lindqvist", "Amara Nwosu", "Rafael Duarte")
$ri = rand(0, 3)
$nameA = $nameAAll[$ri]
$nameB = $nameBAll[$ri]
$namePair = array($nameB, $nameA)
$nameBiggerSample = $namePair[$fn]

$classmateAll = array("Sam Whitlock", "Dana Reyes", "Kofi Mensah", "Leila Haddad")
$mi = rand(0, 3)
$classmate = $classmateAll[$mi]

// (a) and (b) are both recognition tasks: the student has to pick the right explanation,
// not phrase one: so both parts are multiple choice.
$choices[0] = array(
  "<b>Neither of them made a mistake.</b> Two different samples from the same population give different results, and a gap this small is ordinary sampling variability, not an error.",
  "<b>The one with the larger sample is correct and the other is wrong.</b> A larger sample gives the right answer, so the smaller sample&rsquo;s mean should be discarded.",
  "<b>One of them must have made a counting error.</b> The population is the same, so two correctly collected samples would have to produce the same mean.",
  "<b>The two results should be averaged</b> to get the correct value for the population."
)
$answer[0] = 0

$choices[1] = array(
  "The gap would <b>typically shrink, but it would not vanish</b>.",
  "The gap would <b>disappear entirely</b> and the two sample means would match.",
  "The gap would <b>grow</b>, because more students means more variation.",
  "The gap would be <b>unaffected</b> by the sample size."
)
$answer[1] = 0

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
      <p><span class="term-label">a. Name what causes the gap:</span> ' . $nameA . ' and ' . $nameB . ' surveyed <i>different students</i>. Two different random samples from the same population almost never produce the same average: that is <b>sampling variability</b>, and it is the normal behavior of sampling, not a sign that someone miscounted. Both results are correct results; they are just results about two different sets of students.</p>
      <p><b>Check the size of the gap:</b> ' . $meanA . ' and ' . $meanB . ' differ by ' . $gap . ' hours, about ' . $gapMin . ' minutes. On a quantity that varies by hours from one student to the next, that is a small gap: exactly the size of disagreement two honest samples are expected to produce.</p>
      <p><b>Why not the other three:</b> the <i>larger sample</i> (' . $nameBiggerSample . '&rsquo;s ' . $nBigger . ' students) is expected to sit closer to the population average, but &ldquo;closer on average&rdquo; is not &ldquo;correct,&rdquo; and it does not make the other sample wrong. A <i>counting error</i> would be one explanation for a difference, but it is not needed here: the difference is fully explained by the samples containing different people. And <i>averaging the two</i> treats them as two attempts at one number; combining samples is reasonable, but it produces another estimate with its own variability, not &ldquo;the correct value.&rdquo;</p>
      <p><span class="term-label">b. What ten times as many students does:</span> larger samples pin down the population average more tightly, so each sample mean would sit closer to the true value: and therefore the two would typically sit closer to <i>each other</i>. With ' . $nA10 . ' and ' . $nB10 . ' students, the gap would very likely be smaller than ' . $gap . ' hours.</p>
      <p><b>But it would not close.</b> Two different samples are still two different samples. Sampling variability gets smaller as the sample grows; it never reaches zero. If two researchers ever reported <i>identical</i> means to the decimal place, that would be the surprising outcome, not the expected one.</p>
      <p><b>The distinction worth keeping:</b> a bigger sample fixes <i>sampling variability</i>: the random wobble from surveying one group of students instead of another. It does not fix <i>bias</i>. If ' . $nameA . ' had surveyed only students leaving the library at midnight, ten times as many of them would give a very precise measurement of the wrong population. Size shrinks the wobble; only good sampling design fixes the aim.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$nameA and $nameB each decide to study $quantityText. Working separately, they each take a random sample of students from the same student body.</p>
    <p style="margin:0.75em 0 0 0;">$nameA samples <b>$nA students</b> and reports a mean of <b>$meanA hours</b>. $nameB samples <b>$nB students</b> and reports a mean of <b>$meanB hours</b>.</p>
    <p style="margin:0.75em 0 0 0;">A classmate, $classmate, says one of them must have made a mistake: the population is the same, so the two averages should have come out the same, and somebody must have miscounted.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which response to $classmate is <b>correct</b>? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Suppose each researcher repeated the study with <b>ten times as many students</b>: $nA10 students and $nB10 students. What would happen to the gap of <b>$gap hours</b> between their two reported means for $shortText? $answerbox[1]
  </div>
</div>

// === ANSWER ===

$solutionguide
