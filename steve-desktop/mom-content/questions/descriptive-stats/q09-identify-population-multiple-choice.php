// === NAME - DESCRIPTION: Identify the Population - Choose the group a study is about, distinguishing it from a broader group, a wrong-subject group, and a sample ===
// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL ===

// Randomize instructor, college, subject studied, and the time period.
$instructors = array("Professor Alvarez", "Professor Chen", "Professor Okafor", "Professor Nguyen", "Professor Brennan", "Professor Silva")
$colleges = array("Riverside Community College", "Lakeshore Community College", "Pine Valley Community College", "Grand Mesa Community College", "Harbor Point Community College")
$subjects = array("math", "biology", "history")
$periods = array("quarter", "semester")

$instructor = $instructors[rand(0, count($instructors)-1)]
$college = $colleges[rand(0, count($colleges)-1)]
$si = rand(0, 2)
$subject = $subjects[$si]
$period = $periods[rand(0, 1)]

// Wrong-subject distractor: pick from the 4-subject pool, skipping the subject actually studied.
$dsubjects = array("math", "biology", "history", "English")
$di = rand(0, 2)
if ($di >= $si) { $di = $di + 1 }
$wrongsubject = $dsubjects[$di]

// Randomize the wording of the "instructor's own classes" distractor (which is a sample, not the population).
$ownopts = array("All " . $subject . " students in " . $instructor . "'s own classes at " . $college, "The " . $subject . " students enrolled in " . $instructor . "'s classes this " . $period)
$ownclasses = $ownopts[rand(0, 1)]

// Options are shuffled automatically by MyOpenMath.
$questions = array("All " . $subject . " students at " . $college, "All students at " . $college, "All " . $wrongsubject . " students at " . $college, $ownclasses)
$answer = 0

// Scalars for the solution guide (question text and answer key cannot disagree).
$correcttext = $questions[0]
$broadtext = $questions[1]
$wrongsubjtext = $questions[2]

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
      <p>The <b>population</b> is the entire collection of persons, things, or objects <em>under study</em>. So ask: who is this study <em>about</em>?</p>
      <p>' . $instructor . ' wants the mean number of days absent for the ' . $subject . ' students at ' . $college . '. That whole group is the population.</p>
      <div class="term-row"><span class="term-label">Population:</span> ' . $correcttext . '</div>
      <p style="margin-top:1em;"><b>Why the others are wrong:</b></p>
      <div class="term-row"><span class="term-label">' . $broadtext . ':</span> too broad: the study is about ' . $subject . ' students, not every student on campus.</div>
      <div class="term-row"><span class="term-label">' . $wrongsubjtext . ':</span> wrong subject: these students are not the group under study.</div>
      <div class="term-row"><span class="term-label">' . $ownclasses . ':</span> this is a <b>sample</b>, not the population. A sample is a subset selected so that studying it gives information about the whole population.</div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;"><b>$instructor</b>, an instructor at <b>$college</b>, is interested in the <b>mean number of days</b> that the college's <b>$subject</b> students are absent from class during a <b>$period</b>.</p>
    <p style="margin:12px 0 0 0;">What is the <b>population</b> that $instructor is interested in?</p>
  </div>
  $answerbox
</div>

// === ANSWER ===

$solutionguide
