// === NAME - DESCRIPTION: Is a Tiny Sample Representative - judge a sample that is a large FRACTION of a small population but too few responses to support any conclusion ===
// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL ===

$pop = rand(5, 9)
$pct = round(2 / $pop * 100, 1)

$questions = array(
  "No. Two responses are not enough to justify any conclusion; with a population this small it would be better to survey everyone.",
  "Yes. Two out of " . $pop . " is a large fraction of the population, so it is representative.",
  "Yes. Any sample is representative as long as it was chosen at random.",
  "No. A sample must always contain at least 30 members, regardless of the population size."
)
$answer = 0

$solutionguide = '<div style="font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <p><b>This is the mirror image of the reliability questions before it.</b></p>
  <p>As a <i>fraction</i>, 2 of ' . $pop . ' looks generous: about ' . $pct . '%, far higher than the tiny share a national poll takes. So the fraction cannot be what matters.</p>
  <p>What matters is the <b>count</b>. Two responses cannot show a pattern: one unusual person moves the result by half. There is no way to separate signal from noise with two observations, whatever share of the population they are.</p>
  <p><b>And there is a better option here.</b> Because the population is only ' . $pop . ', you do not need to sample at all: survey everyone and you have the exact answer instead of an estimate. Sampling exists to avoid the cost of a census; at this size there is no cost to avoid.</p>
  <p><b>Answer:</b> no, a sample of two is not representative of a population of ' . $pop . '.</p>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    Is a sample size of <b>two</b> representative of a population of <b>$pop</b>?
  </div>
</div>

// === ANSWER ===

$solutionguide
