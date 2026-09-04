// === NAME - DESCRIPTION: Pre-FRQ Grade a Consistency Comparison - The scenario and grading checklist of the comparing-means-and-standard-deviations FRQ, but the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The pre-FRQ for 2.7. The SAME scenario and the SAME grading checklist as
// frq/descriptive-statistics/q7-comparing-means-and-standard-deviations, with the writing replaced
// by grading. The three response sentences are the FRQ's own target strings, so a student who
// studies this one is reading the exact prose the FRQ rewards.
//
// The dropped category here is PRACTICAL CONCLUSION: students narrate both standard deviations
// accurately and never commit to which person they would rely on. Describing the spread feels like
// the analysis, so the decision the rubric actually asks for goes unmade. Distinct from 2.4
// (Practical Interpretation of an outlier) and 2.5 (Outlier Impact).
$anstypes = array("choices", "multans", "choices")

$i = rand(0, 2)

$contexts = array(
  "Two fitness instructors are comparing their weekly class attendance over the past 10 weeks",
  "Two baristas at a busy coffee shop are comparing their daily sales numbers over the past month",
  "Two delivery drivers at a shipping company are comparing their daily package counts over the past month"
)
$topic = $contexts[$i]

$group_a_names = array("Instructor A", "Barista A", "Driver A")
$group_b_names = array("Instructor B", "Barista B", "Driver B")
$units = array("students per class", "drinks sold per day", "packages delivered per day")
$measured_things = array("class attendance", "daily sales numbers", "daily delivery counts")
$time_units = array("week", "day", "day")
$time_periods = array("10-week period", "month", "month")

$person_a = $group_a_names[$i]
$person_b = $group_b_names[$i]
$unit = $units[$i]
$measured_thing = $measured_things[$i]
$time_unit = $time_units[$i]
$time_period = $time_periods[$i]

$shared_mean = rand(28, 45)
$sd_small = rand(2, 4)
$sd_large = rand(7, 12)
// Keep the contrast unambiguous, exactly as the FRQ does, so the verdict is never a judgement call.
if ($sd_large <= 2 * $sd_small) {
  $sd_large = 2 * $sd_small + 1
}

// The FRQ's own target sentences, one per rubric category.
$sMean = 'Both ' . $person_a . ' and ' . $person_b . ' have the same mean of ' . $shared_mean . ' ' . $unit . ', which tells us they perform at the same level on average over the ' . $time_period . '.'
$sSD = $person_a . ' has a standard deviation of only ' . $sd_small . ', meaning their ' . $measured_thing . ' stays tightly clustered around the average, while ' . $person_b . ' has a standard deviation of ' . $sd_large . ', so their numbers vary much more widely from ' . $time_unit . ' to ' . $time_unit . '.'
// Each sentence must be CATEGORY-PURE: it earns its own rubric line and no other. The FRQ's target
// strings cross-reference each other because they are written to flow as one essay, so this one is
// trimmed: it used to explain what the smaller and larger standard deviations show, which is the
// SD & Consistency requirement, and made the response that drops that category still earn it.
$sConclusion = 'If you need to count on a particular ' . $time_unit . '&#39;s number, ' . $person_a . ' is the one to rely on and ' . $person_b . ' is not, despite the identical average.'

$rFull = $sMean . ' ' . $sSD . ' ' . $sConclusion
$rNoConclusion = $sMean . ' ' . $sSD
$rNoSD = $sMean . ' ' . $sConclusion
$rMeanOnly = $sMean . ' Their averages are identical, so the two are performing the same.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoConclusion
$rC = $rNoSD
$rD = $rMeanOnly
if ($pos == 1) {
  $rA = $rNoConclusion
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoSD
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rMeanOnly
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noConclusionLabel = "B"
if ($pos == 1) { $noConclusionLabel = "A" }

$questions[1] = array(
  "Interpreting the Mean (3 pts)",
  "Standard Deviation & Consistency (4 pts)",
  "Practical Conclusion (3 pts)"
)
$answer[1] = "0,1"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. Reporting each standard deviation describes the spread; the rubric also asks which of the two you would rely on and why, which is a decision rather than a description.",
  "Yes. Once both standard deviations are stated, the conclusion about reliability follows automatically.",
  "No, but only because the response did not restate the mean a second time.",
  "Yes, as long as both standard deviations are correct."
)
$answer[2] = 0

$css = '
<style>
  .qscope7 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope7 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope7 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope7 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope7 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope7 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope7 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope7 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope7 .row-colored { background:#fff9ea; }
  .qscope7 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope7 .resp b { color:#1865f2; }
  .qscope7 .statbox { border-collapse:collapse; margin:10px 0; font-size:15px; }
  .qscope7 .statbox td, .qscope7 .statbox th { border:1px solid #d1d5db; padding:6px 16px; text-align:center; }
  .qscope7 .statbox th { background:#f0f4ff; }
</style>'

$rubric = $css . '
<div class="qscope7">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Interpreting the Mean<br>(3 pts)</b></td>
            <td>Explain what the identical means tell us about both performers on average.</td></tr>
          <tr><td style="text-align:center;"><b>Standard Deviation &amp; Consistency<br>(4 pts)</b></td>
            <td>Say what the smaller standard deviation shows about one performer&#39;s consistency, and what the larger one shows about the other&#39;s variability.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Practical Conclusion<br>(3 pts)</b></td>
            <td>State which of the two is more reliable, and explain why.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$statTable = '
<div class="qscope7">
  <table class="statbox">
    <tr><th></th><th>Mean</th><th>Standard Deviation</th></tr>
    <tr><td><b>' . $person_a . '</b></td><td>' . $shared_mean . '</td><td>' . $sd_small . '</td></tr>
    <tr><td><b>' . $person_b . '</b></td><td>' . $shared_mean . '</td><td>' . $sd_large . '</td></tr>
  </table>
</div>'

$responses = '
<div class="qscope7">
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
      <p><span class="term-label">Part (a): only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> says what the equal means show, contrasts the two standard deviations, and then names who is more reliable and why. Each of the other three drops a whole category.</p>
      <p><span class="term-label">The numbers.</span> Both average ' . $shared_mean . ' ' . $unit . ', so the means alone separate nobody. ' . $person_a . ' carries a standard deviation of ' . $sd_small . ' against ' . $person_b . '&#39;s ' . $sd_large . ': more than double the spread around the same centre. That contrast is the entire story here, and it is invisible if you stop at the mean.</p>
      <p><span class="term-label">Part (b): grading Response ' . $noConclusionLabel . ' line by line.</span></p>
      <ul>
        <li><b>Interpreting the Mean: earned.</b> It states that the identical means put both at the same average level.</li>
        <li><b>Standard Deviation &amp; Consistency: earned.</b> It reports both standard deviations and says what each one means for spread.</li>
        <li><b>Practical Conclusion: NOT earned.</b> It never says which of the two it would rely on. Everything it says is true and nothing is decided. This is the only category it misses.</li>
      </ul>
      <p><span class="term-label">Part (c): why the conclusion is separate from the description.</span> Reporting that one spread is ' . $sd_small . ' and the other ' . $sd_large . ' describes the data. The rubric also asks the question someone would actually act on: which performer can be counted on for a result near ' . $shared_mean . ' on a given ' . $time_unit . '. A description that stops short of the verdict leaves the reader to do the statistics themselves, which is the job that was being asked for.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the lab and on the test this scenario comes with a blank box and this same checklist. Practical Conclusion is the category most often missing, because narrating both standard deviations correctly feels like the analysis is finished.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> $topic.</p>
    $statTable
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> Interpret these two summaries and say what they show about consistency and reliability.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noConclusionLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is describing both standard deviations enough on its own to cover the practical conclusion? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
