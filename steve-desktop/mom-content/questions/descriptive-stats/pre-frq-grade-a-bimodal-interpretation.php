// === NAME - DESCRIPTION: Pre-FRQ Grade a Bimodal Interpretation - The scenario and grading checklist of the interpreting-bimodal-data FRQ, but the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The pre-FRQ for 2.6. The SAME scenario and the SAME grading checklist as
// frq/descriptive-statistics/q5-interpreting-bimodal-data, with the writing replaced by grading.
// The three response sentences are the FRQ's own target strings, so a student who studies this one
// is reading the exact prose the FRQ rewards.
//
// The dropped category here is FURTHER INVESTIGATION: students name the shape and tell a story
// about the two groups, then stop. Explaining feels like the finish line, and the rubric's last
// three points are for proposing something that would actually TEST the story. Distinct from the
// dropped categories on 2.4, 2.5 and 2.7.
$anstypes = array("choices", "multans", "choices")

$i = rand(0, 2)

$contexts = array(
  "reaction times (in milliseconds) for a computer-based attention task",
  "daily commute times (in minutes) for employees at a large company",
  "exam scores (out of 100) for a statistics class"
)
$topic = $contexts[$i]

$data_labels_plural = array("reaction times", "commute times", "exam scores")
$data_label_plural = $data_labels_plural[$i]

$subject_labels = array("participants", "employees", "students")
$subject_label = $subject_labels[$i]

$unit_labels = array("milliseconds", "minutes", "points")
$unit_label = $unit_labels[$i]

$peak1_vals = array(rand(28, 32) * 10, rand(12, 18), rand(58, 68))
$peak2_vals = array(rand(58, 65) * 10, rand(40, 50), rand(85, 93))
$peak1 = $peak1_vals[$i]
$peak2 = $peak2_vals[$i]

$n = rand(80, 200)

$subgroup_explanations = array(
  "experienced users who respond quickly and novice users who take longer to process the task",
  "employees who live nearby and have short commutes versus those who commute from farther away",
  "students who mastered the material and scored highly versus those who struggled with the concepts"
)
$subgroup_explanation = $subgroup_explanations[$i]

$investigation_recs = array(
  "collect data on each participant&#39;s prior experience with similar computer tasks and create separate histograms for experienced and novice users",
  "ask employees about their home location or commute method and create separate histograms for each group",
  "look at study habits or prior coursework and create separate histograms for each subgroup"
)
$investigation_rec = $investigation_recs[$i]

$scenario = 'A histogram of ' . $topic . ' collected from ' . $n . ' ' . $subject_label . ' shows a clear bimodal distribution with two distinct peaks, one around ' . $peak1 . ' ' . $unit_label . ' and another around ' . $peak2 . ' ' . $unit_label . '.'

// The FRQ's own target sentences, one per rubric category.
$sPattern = 'The histogram shows a bimodal distribution, meaning there are two distinct peaks (modes) in the data. The ' . $data_label_plural . ' are not clustered around a single typical value but group around two separate centers: approximately ' . $peak1 . ' ' . $unit_label . ' and ' . $peak2 . ' ' . $unit_label . '.'
$sExplanation = 'This pattern suggests the ' . $subject_label . ' are not one uniform group. There are likely two subpopulations in the data: ' . $subgroup_explanation . '. The gap between the two peaks supports the idea that different underlying factors are driving the ' . $data_label_plural . ' for each group.'
$sInvestigation = 'To investigate further, researchers should ' . $investigation_rec . ' to see whether the bimodal pattern separates into two unimodal distributions. That would confirm whether two distinct subgroups really exist.'

$rFull = $sPattern . ' ' . $sExplanation . ' ' . $sInvestigation
$rNoInvestigation = $sPattern . ' ' . $sExplanation
$rNoExplanation = $sPattern . ' ' . $sInvestigation
$rPatternOnly = $sPattern . ' The shape of the distribution is therefore clear from the histogram.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoInvestigation
$rC = $rNoExplanation
$rD = $rPatternOnly
if ($pos == 1) {
  $rA = $rNoInvestigation
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoExplanation
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rPatternOnly
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noInvestigationLabel = "B"
if ($pos == 1) { $noInvestigationLabel = "A" }

$questions[1] = array(
  "Pattern Recognition (3 pts)",
  "Subgroup Explanation (4 pts)",
  "Further Investigation (3 pts)"
)
$answer[1] = "0,1"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. Explaining why two groups might exist is a hypothesis; the rubric also asks for a concrete step that would test whether that hypothesis is true.",
  "Yes. A convincing explanation of the two subgroups makes further investigation unnecessary.",
  "No, but only because the response did not name both peak values a second time.",
  "Yes, as long as the two peaks are described accurately."
)
$answer[2] = 0

$css = '
<style>
  .qscope5 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope5 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope5 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope5 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope5 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope5 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope5 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope5 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope5 .row-colored { background:#fff9ea; }
  .qscope5 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope5 .resp b { color:#1865f2; }
  .qscope5 .peakbox { border:1px solid #d1d5db; border-radius:8px; padding:10px 14px; margin:10px 0; background:#f8fafc; font-size:15px; }
</style>'

$rubric = $css . '
<div class="qscope5">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Pattern Recognition<br>(3 pts)</b></td>
            <td>Name the shape of the distribution and describe what it looks like.</td></tr>
          <tr><td style="text-align:center;"><b>Subgroup Explanation<br>(4 pts)</b></td>
            <td>Give a plausible reason two distinct groups might exist, tied to this specific context.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Further Investigation<br>(3 pts)</b></td>
            <td>Recommend a specific next step that would test whether the subgroups are real.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$peakBlock = '
<div class="qscope5">
  <div class="peakbox">' . $scenario . '</div>
</div>'

$responses = '
<div class="qscope5">
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
      <p><span class="term-label">Part (a): only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> names the shape, offers a reason two groups might exist in this context, and proposes a step that would test it. Each of the other three drops a whole category.</p>
      <p><span class="term-label">The picture.</span> Two peaks, around ' . $peak1 . ' and ' . $peak2 . ' ' . $unit_label . ', across ' . $n . ' ' . $subject_label . '. A single mean sitting between those peaks would describe almost nobody in the data: which is why the shape has to be named before any summary number is trusted.</p>
      <p><span class="term-label">Part (b): grading Response ' . $noInvestigationLabel . ' line by line.</span></p>
      <ul>
        <li><b>Pattern Recognition: earned.</b> It names the distribution bimodal and locates both centers.</li>
        <li><b>Subgroup Explanation: earned.</b> It proposes two subpopulations and ties them to this context rather than to statistics in general.</li>
        <li><b>Further Investigation: NOT earned.</b> It never says what anyone should DO next. The explanation is left as a story with nothing that would confirm or refute it. This is the only category it misses.</li>
      </ul>
      <p><span class="term-label">Part (c): why investigation is separate from explanation.</span> "There are probably two groups, and here is why" is a hypothesis. It is worth points, and it is not evidence. The rubric also asks for the move that would settle it: ' . $investigation_rec . '. If two unimodal distributions fall out, the story is supported; if the two peaks survive the split, it was the wrong story. A hypothesis nobody proposed to test is where a lot of bad statistics starts.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the lab and on the test this scenario comes with a blank box and this same checklist. Further Investigation is the category most often missing, because once you have explained the two peaks the thinking FEELS done.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b></p>
    $peakBlock
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> Interpret this distribution, explain what the two peaks suggest, and recommend how to confirm it.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noInvestigationLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is explaining why two groups might exist enough on its own to cover further investigation? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
