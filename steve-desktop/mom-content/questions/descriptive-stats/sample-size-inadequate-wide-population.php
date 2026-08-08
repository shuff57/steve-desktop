// === NAME - DESCRIPTION: Is the Sample Large Enough for the Country - recognise that a sample drawn from one school cannot describe a national population, because of coverage rather than size ===
// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL ===

$n = rand(2, 4) * 50

$questions = array(
  "No. The students all come from one school, so the sample does not cover the wider population no matter how many were asked.",
  "No. " . $n . " is a large enough number, but the survey should have been repeated in a second year.",
  "Yes. " . $n . " students is a large sample, so it can describe the whole country.",
  "Yes, provided the " . $n . " students were selected at random, which they were."
)
$answer = 0

$solutionguide = '<div style="font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <p><b>Same sample, wider population, opposite answer.</b></p>
  <p>Every one of the ' . $n . ' students attends a single school. School-aged children and young adults across the country differ by region, income, school type and home internet access &mdash; and none of that variation can appear in a sample drawn from one building.</p>
  <p><b>This is a coverage failure, not a size failure.</b> Surveying ' . $n . ' more students at the same school would not help; the extra responses come from the same narrow slice. What is needed is a sample drawn from the whole population &mdash; many schools, many places &mdash; and that is a different study, not a bigger one.</p>
  <p>Randomness within the school does not rescue it either. A perfectly random sample <i>of the wrong population</i> describes that wrong population perfectly.</p>
  <p><b>Answer:</b> no &mdash; not large enough, and more importantly not drawn from the right population.</p>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0;">Two researchers each surveyed <b>$n</b> randomly chosen students at the same school, asking how many hours per week they play video games.</p>
    <p style="margin:12px 0 0 0;">Would the sample size be large enough if the population is <b>school-aged children and young adults in the United States</b>?</p>
  </div>
</div>

// === ANSWER ===

$solutionguide
