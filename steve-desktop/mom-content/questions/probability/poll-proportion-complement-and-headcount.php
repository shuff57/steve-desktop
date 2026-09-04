// === NAME - DESCRIPTION: Poll Proportions, a Complement, and a Headcount - read a sample size and three yearly approval rates off a poll summary, take the complement of one rate, and convert another rate back into a whole number of respondents ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number", "number", "number")

$ci = rand(0, 3)

$offices = array("mayor", "city council president", "county sheriff", "school board president")
$cities = array("a large city", "a mid-sized city", "a large county", "a suburban school district")
// Bare noun for the possessive: "that a large city's mayor" is what happens without it.
$cityNouns = array("city", "city", "county", "district")
$office = $offices[$ci]
$city = $cities[$ci]
$cityNoun = $cityNouns[$ci]

$n = rand(720, 1580)

// Three yearly approval rates. The third is forced well below the first two so that the
// complement in part b and the decline the poll shows are both worth remarking on.
$yr1Pct = rand(52, 68)
$yr2Pct = rand(45, 62) where (abs($yr2Pct - $yr1Pct) >= 2)
$yr3Pct = rand(28, 44)

$yr1 = $yr1Pct / 100
$yr2 = $yr2Pct / 100
$yr3 = $yr3Pct / 100

$disapprove3 = round(1 - $yr3, 2)
$approveCountExact = $yr3 * $n
$approveCount = round($approveCountExact, 0)
$approveCountShown = round($approveCountExact, 2)

$answer[0] = $n
$answerformat[0] = "integer"

$answer[1] = $disapprove3
$abstolerance[1] = 0.0011

$answer[2] = $approveCount
$answerformat[2] = "integer"
$abstolerance[2] = 0.51

$answer[3] = $yr2
$abstolerance[3] = 0.0011

$answer[4] = $yr1
$abstolerance[4] = 0.0011

$solutionguide = '
<div style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <p><b>a: the sample size is the number of people polled.</b></p>
  <p style="margin-left:1em;">`n = ' . $n . '`. It is stated in the setup; nothing needs computing. The three yearly rates are all measured on this same group.</p>

  <p><b>b: disapproval is the complement of approval.</b></p>
  <p style="margin-left:1em;">Everyone polled expressed either approval or disapproval, so the two rates must add to 1. In Year 3 the approval rate was ' . $yr3Pct . '%, so</p>
  <p style="margin-left:1em;"><b>`P(disapprove) = 1 - ' . $yr3 . ' = ' . $disapprove3 . '`</b></p>
  <p style="margin-left:1em;">The complement only works here <i>because</i> the poll allowed no third answer. If "undecided" had been an option, the two rates would not add to 1 and this subtraction would be wrong.</p>

  <p><b>c: turn a proportion back into a headcount.</b></p>
  <p style="margin-left:1em;">Multiply the Year 3 approval rate by the sample size:</p>
  <p style="margin-left:1em;"><b>`(' . $yr3 . ')(' . $n . ') = ' . $approveCountShown . ' ~~ ' . $approveCount . '` people</b></p>
  <p style="margin-left:1em;">You cannot poll a fraction of a person, so round to the nearest whole respondent.</p>

  <p><b>d: Year 2 approval, read as a probability.</b></p>
  <p style="margin-left:1em;">A respondent drawn at random from the Year 2 poll approved with probability <b>`' . $yr2 . '`</b>. A percentage of a group <i>is</i> the probability for a randomly chosen member of it: that is the whole move being practised here.</p>

  <p><b>e: Year 1 approval.</b></p>
  <p style="margin-left:1em;"><b>`P(approve in Year 1) = ' . $yr1 . '`</b></p>

  <p><b>Answer:</b> a. ' . $n . '; b. ' . $disapprove3 . '; c. about ' . $approveCount . ' people; d. ' . $yr2 . '; e. ' . $yr1 . '.</p>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0;">A survey was conducted in $city to measure the popularity of that $cityNoun's $office. The survey was repeated every year for three years. The survey polled <b>$n</b> people. Everyone polled expressed either approval or disapproval. These are the results the poll produced:</p>
    <ul style="margin:12px 0 0 0;">
      <li>In Year 1, <b>$yr1Pct%</b> of the population approved of the $office's actions in office.</li>
      <li>In Year 2, <b>$yr2Pct%</b> of the population approved of those actions.</li>
      <li>In Year 3, the percentage of popular approval was measured at <b>$yr3Pct%</b>.</li>
    </ul>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">a.</span> What is the <b>sample size</b> for this study? Enter a whole number. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">b.</span> What <b>proportion</b> in the poll disapproved of the $office, according to the Year 3 results? Enter a decimal. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">c.</span> <b>How many people</b> polled responded that they approved of the $office, based on the Year 3 results? Round to the nearest whole person. $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">d.</span> What is the <b>probability</b> that a person supported the $office, based on the data collected in Year 2? Enter a decimal. $answerbox[3]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">e.</span> What is the <b>probability</b> that a person supported the $office, based on the data collected in Year 1? Enter a decimal. $answerbox[4]
  </div>
</div>

// === ANSWER ===

$solutionguide
