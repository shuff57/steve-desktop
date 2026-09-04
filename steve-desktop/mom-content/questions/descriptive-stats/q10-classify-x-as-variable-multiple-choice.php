// === NAME - DESCRIPTION: Classify X as the Variable - Name what X is when X is defined as a measurement on one member of the population ===
// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL ===

// Randomize instructor, college, subject studied, and the term word.
$instructors = array("Professor Alvarez", "Professor Chen", "Professor Okafor", "Professor Nguyen", "Professor Brennan", "Professor Silva")
$colleges = array("Riverside Community College", "Lakeshore Community College", "Pine Valley Community College", "Grand Mesa Community College", "Harbor Point Community College")
$subjects = array("math", "biology", "history", "English")
$periods = array("quarter", "semester")

$instructor = $instructors[rand(0, count($instructors)-1)]
$college = $colleges[rand(0, count($colleges)-1)]
$subject = $subjects[rand(0, count($subjects)-1)]
$period = $periods[rand(0, 1)]

// Options are the four vocabulary terms; MyOpenMath shuffles them.
$questions = array("A variable", "A population", "A statistic", "Data")
$answer = 0

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
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p>A <b>variable</b> is a characteristic or measurement that can be determined for each member of the population. It is usually written with a capital letter such as `X`.</p>
      <p>Here `X` is the number of days <em>one</em> ' . $subject . ' student at ' . $college . ' is absent during the ' . $period . '. That is a measurement made on each member of the group, so `X` is a variable.</p>
      <div class="term-row"><span class="term-label">Answer:</span> A variable</div>
      <p style="margin-top:1em;"><b>Why the others are wrong:</b></p>
      <div class="term-row"><span class="term-label">A population:</span> the population is the <em>group</em> itself: all ' . $subject . ' students at ' . $college . ': not the measurement taken on them.</div>
      <div class="term-row"><span class="term-label">A statistic:</span> a statistic is a single summary number computed from a sample, such as the mean number of days absent for the students surveyed. `X` is not a summary number.</div>
      <div class="term-row"><span class="term-label">Data:</span> the data are the recorded <em>values</em> of `X`, such as 0, 3, and 7 days. The variable is the characteristic; the data are what it turns out to be.</div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;"><b>$instructor</b>, an instructor at <b>$college</b>, is studying the <b>mean number of days</b> that the college's <b>$subject</b> students are absent from class during a <b>$period</b>.</p>
    <p style="margin:12px 0 0 0;">Let `X` = the number of days that <b>one</b> such student is absent during the $period.</p>
    <p style="margin:12px 0 0 0;">What is `X`?</p>
  </div>
  $answerbox
</div>

// === ANSWER ===

$solutionguide
