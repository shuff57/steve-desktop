// === NAME - DESCRIPTION: Ski Lesson Age Key Terms - Match population, sample, parameter, statistic, variable, and data to their descriptions in a study of the mean age at a first ski or snowboard lesson ===
// === SET QUESTION TYPE TO: matching ===

// === COMMON CONTROL ===

// Randomize the venue, the activity named, the sample size, and the two example ages.
$venues = array("Ski resorts", "Snowboard parks", "Mountain lodges", "Winter sports schools")
$activities = array("ski", "snowboard", "ski or snowboard")

$venue = $venues[rand(0, count($venues)-1)]
$activity = $activities[rand(0, count($activities)-1)]
$n = 5*rand(4, 12)
$a1 = rand(3, 6)
$a2 = rand(8, 12)

// Precompute the scalars the prompt and the answer key both need.
$agelist = "$a1 and $a2"

// The six terms stay in teaching order — population before sample, parameter before statistic —
// so the list reads as the definition sequence rather than a scramble.
$questions = array("Population", "Sample", "Parameter", "Statistic", "Variable", "Data")

// One description per term, in the same order, then two distractors. With six terms and exactly
// six descriptions the last answer is free by elimination; the extras take that away.
$answers = array(
  "all children who take {$activity} lessons",
  "the {$n} children whose ages at their first lesson were recorded",
  "the mean age at a first lesson for ALL such children",
  "the mean age at a first lesson for the {$n} children RECORDED",
  "the age of one child at their first {$activity} lesson",
  "the recorded ages, such as {$agelist}",
  "the number of children who took a first lesson",
  "all children, whether or not they ever take a lesson"
)

// Spelled out rather than left to the default, because the two distractors make the answer list
// longer than the question list and the mapping must stay pinned to the first six.
$matchlist = "0,1,2,3,4,5"

$questiontitle = "Key term"
$answertitle = "Description"
// Keep the terms in order and shuffle only the descriptions: the point is recognising which
// description fits, not remembering where it sat last time.
$noshuffle = "questions"

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
  .term-row { margin:0.6em 0; }
  .term-label { font-weight:700; color:#1865f2; }
  .sol-note { margin-top:0.9em; padding:0.6em 0.75em; background:#e8f0fe; border-radius:8px; font-size:15px; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><b>Step 1 &mdash; Name the group.</b> The study is about children who take a first ' . $activity . ' lesson, so that whole group is the population. The ' . $n . ' children whose ages actually get written down are the sample.</p>
      <p><b>Step 2 &mdash; Sort the two mean ages.</b> A mean over the whole population is a parameter; the same mean computed from the recorded group is a statistic.</p>
      <p><b>Step 3 &mdash; Name the measurement and its values.</b> What is measured on one child is the variable; the numbers written down are the data.</p>
      <div class="term-row"><span class="term-label">Population:</span> all children who take ' . $activity . ' lessons</div>
      <div class="term-row"><span class="term-label">Sample:</span> the group of ' . $n . ' children whose ages are recorded</div>
      <div class="term-row"><span class="term-label">Parameter:</span> the mean age at a first lesson for <em>all</em> such children</div>
      <div class="term-row"><span class="term-label">Statistic:</span> the mean age at a first lesson for the ' . $n . ' children in the sample</div>
      <div class="term-row"><span class="term-label">Variable:</span> <em>X</em> = the age of one child at their first ' . $activity . ' lesson</div>
      <div class="term-row"><span class="term-label">Data:</span> the recorded ages, such as ' . $agelist . '</div>
      <div class="sol-note">Two of the descriptions are not used. <b>The number</b> of children who took a lesson is a count, not a mean age. And <b>all children</b>, including those who never take a lesson, is a wider group than the one this study is about.</div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;"><b>$venue</b> are interested in the <b>mean age</b> at which children take their first <b>$activity</b> lesson, so they can plan their classes. Instructors write down the age of $n children at their first lesson; the recorded ages include $agelist.</p>
    <p style="margin:12px 0 0 0;">Match each key term to the description that fits this study. <b>Two descriptions are not used.</b></p>
  </div>
</div>


// === ANSWER ===

$solutionguide
