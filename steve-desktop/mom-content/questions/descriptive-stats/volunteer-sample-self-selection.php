// === NAME - DESCRIPTION: Are Volunteers a Reliable Sample - judge a sample that is large enough but self-selected, and name self-selection as the flaw ===
// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL ===

$pop = rand(2, 6) * 1000
$sampR = round($pop / 5 / 50, 0) * 50
$pct = round($sampR / $pop * 100, 1)

$questions = array(
  "No: even though the sample is large enough, a sample of volunteers is self-selected, which is not reliable.",
  "Yes: " . $sampR . " out of " . $pop . " is a large enough fraction to be reliable.",
  "Yes: volunteers are more motivated, so their answers are more accurate.",
  "No: " . $sampR . " is too small a sample for a population of " . $pop . "."
)
$answer = 0

$solutionguide = '
<div style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <p><b>The size is fine. The selection is not.</b> ' . $sampR . ' out of ' . $pop . ' is about ' . $pct . '%: ample.</p>
  <p>The problem is the word <b>volunteers</b>. People who step forward differ systematically from people who do not: they care more, have more time, or hold a stronger opinion. That is a <b>self-selected sample</b>, and no amount of extra volunteers fixes it: recruiting twice as many produces twice as many of the same kind of person.</p>
  <p>Contrast this with the previous problem, where the sample was drawn <i>randomly</i>. Same sort of fraction, opposite verdict, and the only thing that changed was how members got in.</p>
  <p><b>Answer:</b> no: a sample of ' . $sampR . ' volunteers is not a reliable measure, because it is self-selected.</p>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    Is a sample of <b>$sampR volunteers</b> a reliable measure for a population of <b>$pop</b>?
  </div>
</div>

// === ANSWER ===

$solutionguide
