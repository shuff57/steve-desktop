// === NAME - DESCRIPTION: Pre-FRQ Grade a Built Distribution - The scenario and grading checklist of the build-a-distribution FRQ, but the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The pre-FRQ for 4.1. No FRQ in questions/frq/probability covers building a discrete
// distribution, so this one is authored and DEFINES the scenario and rubric a later FRQ should match.
// See reference/pre-frq-template.md.
//
// Categories: Define the Random Variable (2 pts) / Build the Table (6 pts) / Verify It Is
// Legitimate (4 pts) = 12, matching the slot.
//
// The dropped category is VERIFY IT IS LEGITIMATE. A table can be built from the percentages with
// every value listed, and still never be checked against the two characteristics, so nothing shows
// whether a value was dropped. Not reused from an earlier assignment: 2.3 Percentile, 2.4
// Contextual Interpretation, 2.5 Outlier Impact, 2.6 Further Investigation, 2.7 Practical
// Conclusion, 3.1 Sample Space, 3.2 Distinguish the Two, 3.3 Second Factor, 3.4 State the
// Direction, 3.5 Draw the Structure: none is this.
//
// CATEGORY PURITY: $sDefine names the variable and lists no values or probabilities; $sTable pairs
// values with probabilities and never names the variable in a sentence or tests anything; $sCheck
// states the two characteristics hold and never restates a value or probability. Each sentence
// earns exactly one rubric line and no other.
$anstypes = array("choices", "multans", "choices")

$scenarios = array(
  "Dara has volleyball practice three days a week. She attends all three days 75% of the time, two days 15% of the time, one day 6% of the time, and no days 4% of the time. One week is selected at random.",
  "Imani volunteers at a food pantry up to four shifts a month. She works all four shifts 40% of the time, three shifts 30% of the time, two shifts 20% of the time, one shift 7% of the time, and no shifts 3% of the time. One month is selected at random.",
  "Leo bikes to work at most two days a week. He bikes both days 60% of the time, one day 35% of the time, and neither day 5% of the time. One week is selected at random."
)

$i = rand(0, 2)
$scenario = $scenarios[$i]

$sDefineS = array(
  "Let `X` = the number of practices Dara attends in a week.",
  "Let `X` = the number of shifts Imani works in a month.",
  "Let `X` = the number of days Leo bikes to work in a week."
)

$sTableS = array(
  "The values are 0, 1, 2 and 3, with `P(0) = 0.04`, `P(1) = 0.06`, `P(2) = 0.15` and `P(3) = 0.75`.",
  "The values are 0, 1, 2, 3 and 4, with `P(0) = 0.03`, `P(1) = 0.07`, `P(2) = 0.20`, `P(3) = 0.30` and `P(4) = 0.40`.",
  "The values are 0, 1 and 2, with `P(0) = 0.05`, `P(1) = 0.35` and `P(2) = 0.60`."
)

$sCheckS = array(
  "Every probability is between 0 and 1 and the column adds to 1, so the table is a legitimate probability distribution.",
  "Every probability is between 0 and 1 and the column adds to 1, so the table is a legitimate probability distribution.",
  "Every probability is between 0 and 1 and the column adds to 1, so the table is a legitimate probability distribution."
)

$sDef = $sDefineS[$i]
$sTab = $sTableS[$i]
$sChk = $sCheckS[$i]

$rFull = $sDef . ' ' . $sTab . ' ' . $sChk
$rNoCheck = $sDef . ' ' . $sTab
$rNoDefine = $sTab . ' ' . $sChk
$rMinimal = $sDef . ' The percentages from the description go in the table.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoCheck
$rC = $rNoDefine
$rD = $rMinimal
if ($pos == 1) {
  $rA = $rNoCheck
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoDefine
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rMinimal
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noCheckLabel = "B"
if ($pos == 1) { $noCheckLabel = "A" }

$questions[1] = array(
  "Define the Random Variable (2 pts)",
  "Build the Table (6 pts)",
  "Verify It Is Legitimate (4 pts)"
)
$answer[1] = "0,1"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. A table can be built from the percentages with every number looking reasonable and still be missing a value or carrying an entry that is out of range, so the only thing that shows the table is a legitimate distribution is the check itself: it has to be judged on its own.",
  "Yes. If each percentage was converted to a decimal correctly, the column must add to 1 automatically, so there is nothing separate to award.",
  "No, but only because building the table is the hard part.",
  "Yes, as long as the table has a row for every value, the check adds nothing."
)
$answer[2] = 0

$css = '
<style>
  .qscope41 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope41 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope41 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope41 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope41 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope41 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope41 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope41 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope41 .row-colored { background:#fff9ea; }
  .qscope41 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope41 .resp b { color:#1865f2; }
</style>'

$rubric = $css . '
<div class="qscope41">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Define the Random Variable<br>(2 pts)</b></td>
            <td>Name the random variable `X` in a full sentence that says what is being counted.</td></tr>
          <tr><td style="text-align:center;"><b>Build the Table<br>(6 pts)</b></td>
            <td>List every value `X` can take, including the smallest case, and pair each with its probability as a decimal.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Verify It Is Legitimate<br>(4 pts)</b></td>
            <td>Check both characteristics: every probability is between 0 and 1, and the column adds to 1.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$responses = '
<div class="qscope41">
  <div class="resp"><b>Response A.</b> ' . $rA . '</div>
  <div class="resp"><b>Response B.</b> ' . $rB . '</div>
  <div class="resp"><b>Response C.</b> ' . $rC . '</div>
  <div class="resp"><b>Response D.</b> ' . $rD . '</div>
</div>'

$fullLabel = "A"
if ($pos == 1) { $fullLabel = "B" }
if ($pos == 2) { $fullLabel = "C" }
if ($pos == 3) { $fullLabel = "D" }

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
      <p><span class="term-label">Part (a): only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> names the variable, pairs each value of `X` with its probability, and checks the two characteristics. Each of the other three misses a whole category.</p>
      <p><span class="term-label">The setup, and the answer.</span> ' . $sDef . ' ' . $sTab . ' ' . $sChk . '</p>
      <p><span class="term-label">Part (b): grading Response ' . $noCheckLabel . ' line by line.</span></p>
      <ul>
        <li><b>Define the Random Variable: earned.</b> The response says in a full sentence what `X` counts.</li>
        <li><b>Build the Table: earned.</b> Every value is listed with its probability.</li>
        <li><b>Verify It Is Legitimate: NOT earned.</b> The table is there, but nowhere does the response check the two characteristics, so nothing shows a value was not dropped.</li>
      </ul>
      <p><span class="term-label">Part (c): why the check is its own category.</span> A table can look complete and every number can look plausible while a small case was dropped. Only adding the column and checking each entry shows it, so the check is judged separately.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the lab and on the test this scenario comes with a blank box and this same checklist. The check is the category most often skipped, because once the table is written it feels finished.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> $scenario</p>
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> Define the random variable, build the probability distribution table from the description, and verify that it is a legitimate distribution.</p>
  </div>
  $rubric
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 4px 0;"><b>Four students answered.</b></p>
    $responses
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which response earns <b>full credit</b> on all three categories? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noCheckLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is building the table correctly enough on its own to cover verifying that it is legitimate? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
