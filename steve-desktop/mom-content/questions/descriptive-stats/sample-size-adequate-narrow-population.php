// === NAME - DESCRIPTION: Is the Sample Large Enough for the School - judge whether a sample drawn from one school supports a conclusion about that school ===
// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL ===

$n = rand(2, 4) * 50
$school = rand(6, 12) * 100

$questions = array(
  "Yes. " . $n . " students drawn at random from the " . $school . " at the school is a large enough share to describe that school.",
  "No. A sample can never describe a population unless it includes more than half of it.",
  "No. " . $n . " is below the minimum of 1,000 responses needed for any reliable survey.",
  "Yes, but only because the two researchers happened to agree with each other."
)
$answer = 0

$solutionguide = '<div style="font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <p><b>Match the sample against the population it is meant to describe.</b></p>
  <p>The population here is the students <i>at this school</i> &mdash; about ' . $school . ' people. A random sample of ' . $n . ' is a substantial share of that, and it was drawn from exactly the group the conclusion is about.</p>
  <p>That second point is the one that matters. A sample is large enough only relative to a stated population; the same ' . $n . ' students are ample for one question and hopeless for another. The next problem asks the same thing about a far wider population and gets the opposite answer with the identical sample.</p>
  <p><b>Answer:</b> yes &mdash; large enough for a conclusion about the students in this school.</p>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0;">Two researchers each surveyed <b>$n</b> randomly chosen students at the same school, which enrolls about <b>$school</b> students, asking how many hours per week they play video games.</p>
    <p style="margin:12px 0 0 0;">Would the sample size be large enough if the population is <b>the students in the school</b>?</p>
  </div>
</div>

// === ANSWER ===

$solutionguide
