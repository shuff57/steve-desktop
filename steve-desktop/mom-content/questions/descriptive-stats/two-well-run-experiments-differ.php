// === NAME - DESCRIPTION: Can Two Well-Run Experiments Differ - recognize sampling variability as the reason two sound studies of the same population disagree ===
// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL ===

$ci = rand(0, 3)
$topics = array("weekly exercise time", "hours of sleep per night", "minutes spent commuting", "cups of coffee per week")
$topic = $topics[$ci]
$n = rand(3, 9) * 50

$questions = array(
  "Yes. Two different random samples contain different individuals, so they will not give identical results even when both studies are run correctly.",
  "No. If both experiments are well run, they must produce the same data.",
  "Yes, but only if one of the two samples was not truly random.",
  "No. Different results always mean at least one of the studies contained an error."
)
$answer = 0

$solutionguide = '<div style="font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <p><b>Yes: and this is the normal case, not a failure case.</b></p>
  <p>Two random samples of ' . $n . ' contain <i>different people</i>. ' . $topic . ' varies from person to person, so two draws from the same population land on different mixes of high and low values. The results differ even though neither researcher did anything wrong.</p>
  <p>That difference has a name: <b>sampling variability</b>. It is a property of sampling itself, not a mistake. It shrinks as the sample grows, but it never reaches zero short of a census.</p>
  <p><b>The trap</b> is treating any disagreement as proof that someone erred: concluding one study must be wrong before checking whether the gap is even bigger than ordinary sampling variability would produce.</p>
  <p><b>Answer:</b> yes, it is possible.</p>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    Two researchers each survey a random sample of <b>$n</b> people from the same population about $topic, and they get different data. Is it possible for two experiments to be <b>well run</b>, with similar sample sizes, and still get different data?
  </div>
</div>

// === ANSWER ===

$solutionguide
