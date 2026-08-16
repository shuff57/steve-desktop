// === NAME - DESCRIPTION: Define the Random Variable - Name X and list the values it can take in a described situation ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Three scenarios, each a prose description of a discrete random variable (mirrors the section's
// Nancy/Jeremiah/Javier examples: days attended, practices, events). Parts:
//   (a) choices - which sentence correctly defines X
//   (b) choices - which set lists every value X can take
//   (c) number  - how many possible values X has (count of the list in (b))
// Invariant: on every seed, the values listed in (b) match the scenario, and (c) equals the
// count of that list. The correct choices are always index 0; options never shuffle ($noshuffle).

$anstypes = array("choices", "choices", "number")
$answerformat[2] = "integer"

$scenarios = array(
  "Nancy has classes three days a week. She attends all three days 80% of the time, two days 15% of the time, one day 4% of the time, and no days 1% of the time.",
  "Jeremiah has basketball practice two days a week. Ninety percent of the time he attends both practices, eight percent of the time he attends one practice, and two percent of the time he does not attend either practice.",
  "Javier volunteers at community events each month, never more than five. He attends exactly five events 35% of the time, four events 25% of the time, three events 20% of the time, two events 10% of the time, one event 5% of the time, and no events 5% of the time."
)

$defs = array(
  "Let `X` = the number of days Nancy attends class per week.",
  "Let `X` = the number of practices Jeremiah attends in a week.",
  "Let `X` = the number of events Javier volunteers at in a month."
)

$defDistractors = array(
  "Let `X` = the number of weeks Nancy has classes.",
  "Let `X` = the day of the week on which Nancy attends class.",
  "Let `X` = the percentage of weeks Nancy attends all three days."
)

$valsGood = array(
  "0, 1, 2, and 3",
  "0, 1, and 2",
  "0, 1, 2, 3, 4, and 5"
)

$valsBadA = array(
  "1, 2, and 3",
  "1 and 2",
  "1, 2, 3, 4, and 5"
)

$valsBadB = array(
  "0, 1, 2, and 3, plus any week she has no classes at all",
  "0, 1, and 2, including half-practices",
  "0, 1, 2, 3, 4, and 5, and any number up to 10"
)

$valsBadC = array(
  "The percentages 80%, 15%, 4%, and 1%",
  "The percentages 90%, 8%, and 2%",
  "The percentages 35%, 25%, 20%, 10%, 5%, and 5%"
)

$counts = array(4, 3, 6)

$i = rand(0, 2)
$scenario = $scenarios[$i]
$defGood = $defs[$i]
$defBad = $defDistractors[$i]
$good = $valsGood[$i]
$badA = $valsBadA[$i]
$badB = $valsBadB[$i]
$badC = $valsBadC[$i]
$count = $counts[$i]

$questions[0] = array($defGood, $defBad)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  $good,
  $badA,
  $badB,
  $badC
)
$answer[1] = 0
$noshuffle[1] = "all"

$answer[2] = $count

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
  .term-label { font-weight:700; color:#1865f2; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><span class="term-label">Part (a) &mdash; define the variable.</span> A random variable is a quantity whose value is determined by chance, written with a capital letter. The sentence has to name the quantity the scenario is about and the unit that varies: <b>' . $defGood . '</b></p>
      <p><span class="term-label">Part (b) &mdash; list the values.</span> Every value the variable can take must be listed, including the smallest one &mdash; the description often buries it in the last clause. Here the possible values are <b>' . $good . '</b>.</p>
      <p><span class="term-label">Part (c) &mdash; count them.</span> That list has <b>' . $count . '</b> values.</p>
      <p>A variable you cannot describe in words is a variable you will misread two steps later; writing the sentence out first keeps the value list honest.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> $scenario</p>
    <p style="margin:0;">One week is selected at random. Define a discrete random variable for this situation.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which sentence correctly defines the random variable `X`?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which list gives every value `X` can take?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> How many possible values does `X` have?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
