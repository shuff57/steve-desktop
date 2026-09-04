// === NAME - DESCRIPTION: Is a Large Random Sample Reliable - judge a sample that is both large and randomly drawn, and name what makes it reliable ===
// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL ===

$pop = rand(4, 9) * 1000
$frac = rand(15, 25) / 100
$samp = round($pop * $frac, 0) 
$sampR = round($samp / 50, 0) * 50
$pct = round($sampR / $pop * 100, 1)

$questions = array(
  "Yes: provided the " . $sampR . " were selected by a random method rather than by convenience or self-selection.",
  "Yes: any sample of more than 1,000 is reliable regardless of how it was chosen.",
  "No: a reliable sample must include at least half the population.",
  "No: " . $sampR . " is too small a number to say anything about " . $pop . " people."
)
$answer = 0

$solutionguide = '
<div style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <p><b>Size is necessary, not sufficient.</b> A sample of ' . $sampR . ' out of ' . $pop . ' is about ' . $pct . '% of the population: plenty large for a reliable estimate.</p>
  <p>But the number alone never settles it. What makes a sample reliable is <i>how it was selected</i>: every member of the population must have had a chance to be chosen. A large convenience sample or a large self-selected sample is still biased, and the size only makes the wrong answer more precise.</p>
  <p><b>Answer:</b> yes: a sample of ' . $sampR . ' is a reliable measure for a population of ' . $pop . ', provided it was drawn randomly.</p>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    Is a sample size of <b>$sampR</b> a reliable measure for a population of <b>$pop</b>?
  </div>
</div>

// === ANSWER ===

$solutionguide
