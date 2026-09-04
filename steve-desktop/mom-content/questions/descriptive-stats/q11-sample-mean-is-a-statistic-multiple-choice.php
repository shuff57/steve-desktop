// === NAME - DESCRIPTION: Sample Mean Is a Statistic - Classify a mean computed from a sample ===
// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL ===

// Randomize instructor, college, subject studied, and the term word.
$instructors = array("Professor Vasquez", "Professor Iyer", "Professor Duarte", "Professor Kowalski", "Professor Abebe", "Professor Lindqvist")
$colleges = array("Cedar Ridge Community College", "Bayview Community College", "Stonebrook Community College", "Maple Hollow Community College", "Silver Creek Community College")
$subjects = array("math", "biology", "history", "English")
$periods = array("quarter", "semester")

$instructor = $instructors[rand(0, count($instructors)-1)]
$college = $colleges[rand(0, count($colleges)-1)]
$subject = $subjects[rand(0, count($subjects)-1)]
$period = $periods[rand(0, 1)]

// Sample size, and a reported sample mean built as text so the tenths digit always shows.
$n = rand(3, 9) * 10
$meanwhole = rand(2, 5)
$meandec = rand(1, 9)
$mean = $meanwhole . "." . $meandec

// Options are the four vocabulary terms; MyOpenMath shuffles them.
$questions = array("A statistic", "A parameter", "Data", "A variable")
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
      <p><b>Step 1: Ask where the number came from.</b> The value ' . $mean . ' days was computed from ' . $instructor . '\'s <em>sample</em> of ' . $n . ' students, not from every ' . $subject . ' student at ' . $college . '.</p>
      <p><b>Step 2: Match that to the vocabulary.</b> A number that represents a property of the sample is a <b>statistic</b>.</p>
      <div class="term-row"><span class="term-label">Answer:</span> A statistic</div>
      <p style="margin-top:1em;"><b>Why the others are wrong:</b></p>
      <div class="term-row"><span class="term-label">A parameter:</span> a parameter describes the whole population. If the same mean number of days absent had been computed over <em>all</em> ' . $subject . ' students at ' . $college . ', that number would be a parameter. The statistic ' . $mean . ' is an estimate of it.</div>
      <div class="term-row"><span class="term-label">Data:</span> the data are the recorded absence counts themselves, such as 0, 3, and 7 days. One student\'s absence count is a datum: ' . $mean . ' is a summary of ' . $n . ' such values, not one of them.</div>
      <div class="term-row"><span class="term-label">A variable:</span> the variable is the measurement itself, `X` = the number of days one student is absent, not the summary number computed from it.</div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;"><b>$instructor</b>, an instructor at <b>$college</b>, is interested in the <b>mean number of days</b> that the college's <b>$subject</b> students are absent from class during a <b>$period</b>.</p>
    <p style="margin:12px 0 0 0;">A sample of <b>$n</b> of those students produces a mean number of days absent of <b>$mean days</b>.</p>
    <p style="margin:12px 0 0 0;">This value is an example of:</p>
  </div>
  $answerbox
</div>

// === ANSWER ===

$solutionguide
