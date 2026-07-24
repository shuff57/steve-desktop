// === NAME - DESCRIPTION: Interpreting Bimodal Data - Students interpret a bimodal distribution, explain what the two peaks suggest about subgroups in the data, and recommend further investigation to confirm their hypothesis. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$i = rand(0, 2);

// Three contexts: reaction times, commute times, exam scores
$contexts = array(
  "reaction times (in milliseconds) for a computer-based attention task",
  "daily commute times (in minutes) for employees at a large company",
  "exam scores (out of 100) for a statistics class"
);
$topic = $contexts[$i];

$data_labels = array("reaction time", "commute time", "exam score");
$data_label = $data_labels[$i];

$data_labels_plural = array("reaction times", "commute times", "exam scores");
$data_label_plural = $data_labels_plural[$i];

$subject_labels = array("participants", "employees", "students");
$subject_label = $subject_labels[$i];

$unit_labels = array("milliseconds", "minutes", "points");
$unit_label = $unit_labels[$i];

// Randomized peak values for each context
$peak1_vals = array(rand(28, 32) * 10, rand(12, 18), rand(58, 68));
$peak2_vals = array(rand(58, 65) * 10, rand(40, 50), rand(85, 93));
$peak1 = $peak1_vals[$i];
$peak2 = $peak2_vals[$i];

// Randomized sample sizes
$n = rand(80, 200);

// Subgroup explanations per context
$subgroup_explanations = array(
  "experienced users who respond quickly and novice users who take longer to process the task",
  "employees who live nearby and have short commutes versus those who commute from farther away",
  "students who mastered the material and scored highly versus those who struggled with the concepts"
);
$subgroup_explanation = $subgroup_explanations[$i];

// Investigation recommendations per context
$investigation_recs = array(
  "collect data on each participant's prior experience with similar computer tasks and create separate histograms for experienced and novice users",
  "ask employees about their home location or commute method and create separate histograms for each group",
  "look at study habits or prior coursework and create separate histograms for each subgroup"
);
$investigation_rec = $investigation_recs[$i];

// Scenario sentence
$scenario = "A histogram of " . $topic . " collected from " . $n . " " . $subject_label . " shows a clear bimodal distribution with two distinct peaks, one around " . $peak1 . " " . $unit_label . " and another around " . $peak2 . " " . $unit_label;

// Narrative variables for the model answer
$r_pattern = "The histogram shows a bimodal distribution, meaning there are two distinct peaks (modes) in the data. This shape tells us that the " . $data_label_plural . " are not clustered around a single typical value but instead group around two separate centers: approximately " . $peak1 . " " . $unit_label . " and " . $peak2 . " " . $unit_label . ".";

$r_explanation = "This pattern strongly suggests that the " . $subject_label . " are not one uniform group. Instead, there are likely two subpopulations in the data: " . $subgroup_explanation . ". The gap between the two peaks supports the idea that different underlying factors are driving the " . $data_label_plural . " for each group.";

$r_investigation = "To investigate further, researchers should " . $investigation_rec . " to see if the bimodal pattern separates into two unimodal distributions. This would confirm whether two distinct subgroups exist and help explain the underlying cause of the two peaks.";

$sample_narrative = "<b>" . $r_pattern . "</b> " . $r_explanation . " <b>" . $r_investigation . "</b>";

/* ---------- 2. SHARED CSS & JS ---------- */
$css_block = '<style>
    .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
    .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
    .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; border:none; }
    .rubric-container details[open] summary { box-shadow: inset 0 -1px 0 #ccc; }
    .rubric-container summary::-webkit-details-marker { display:none; }
    .arrow-open { display:none; }
    .rubric-container details[open] .arrow-closed { display:none; }
    .rubric-container details[open] .arrow-open { display:inline; }
    .rubric-content { overflow:hidden; max-height:0; opacity:0; transition:max-height 300ms ease-out, opacity 300ms ease-out, padding 200ms ease-out; margin-top:0; background:#fafafa; box-sizing:border-box; padding:0 0.75em; }
    .rubric-container details[open] .rubric-content { max-height:2000px; opacity:1; padding:0.75em; }
    .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
    .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
    .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; user-select:text; }
    .row-colored { background:#fff9ea; }
    .col-header { width:25%; border-top-left-radius:8px; }
    .col-check { border-top-right-radius:8px; }
    .col-cat-bot { border-bottom-left-radius:8px; }
    .col-check-bot { border-bottom-right-radius:8px; }
    .ideal-ans { display:block; background-color:#e8f5e9; font-style:italic; font-weight:bold; font-size:0.95em; margin:5px 0 10px 0; border-left:3px solid #4CAF50; padding-left:8px; }
    .full-response-box { margin-top:15px; border:2px solid #4CAF50; background-color:#e8f5e9; padding:15px; border-radius:5px; }
</style>
<script>
document.addEventListener("DOMContentLoaded", function() {
  var details = document.querySelectorAll(".rubric-container details");
  details.forEach(function(det) {
    var content = det.querySelector(".rubric-content");
    det.addEventListener("toggle", function() {
      if (det.open) {
        content.style.maxHeight = content.scrollHeight + "px";
        content.style.opacity = "1";
      } else {
        content.style.maxHeight = content.scrollHeight + "px";
        content.offsetHeight;
        content.style.maxHeight = "0";
        content.style.opacity = "0";
      }
    });
    content.addEventListener("transitionend", function() {
      if (!det.open) content.style.maxHeight = null;
    });
  });
});
</script>';

/* ---------- 3. Student Rubric (Neutral Checklist) ---------- */
$rubricbutton = $css_block . '
<div class="rubric-container">
  <details>
    <summary>
      <span class="arrow-closed">&#9656;</span><span class="arrow-open">&#9662;</span>
      Click to View Grading Checklist
    </summary>
    <div class="rubric-content">
      <p style="margin:0 0 0.5em 0;"><b>Grading Criteria</b> -- ensure your explanation covers these points:</p>
      <table class="rubric-table">
        <tbody>
          <tr>
            <th class="col-header">Category</th>
            <th class="col-check">Requirement</th>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Pattern Recognition</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Identify the shape of the distribution and describe what it looks like.</label></li>
                <li><label><input type="checkbox"> Explain what this shape indicates about the data in general terms.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Subgroup Explanation</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Suggest a plausible reason why two distinct groups might exist in the data.</label></li>
                <li><label><input type="checkbox"> Connect your explanation to the specific context of the scenario.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Further Investigation</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Recommend a specific next step to explore whether subgroups exist.</label></li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </details>
</div>';

/* ---------- 4. Instructor Rubric (With Answer Targets) ---------- */
$rubricanswerbutton = $css_block . '
<div class="rubric-container">
  <details>
    <summary>
      <span class="arrow-closed">&#9656;</span><span class="arrow-open">&#9662;</span>
      Rubric &amp; Model Response
    </summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr>
            <th class="col-header">Category</th>
            <th class="col-check">Checklist &amp; Ideal Targets</th>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Pattern Recognition<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Identify the shape of the distribution and describe what it looks like.
                    <span class="ideal-ans">Target: "The histogram shows a bimodal distribution, meaning there are two distinct peaks (modes) in the data, centered around '.$peak1.' '.$unit_label.' and '.$peak2.' '.$unit_label.'."</span></li>
                <li>Explain what this shape indicates about the data in general terms.
                    <span class="ideal-ans">Target: "A bimodal shape tells us the data is not clustered around a single typical value but instead groups around two separate centers, suggesting the data may contain two distinct subpopulations."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Subgroup Explanation<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Suggest a plausible reason why two distinct groups might exist in the data.
                    <span class="ideal-ans">Target: "The two peaks suggest the '.$subject_label.' are not one uniform group but likely include two subpopulations: '.$subgroup_explanation.'."</span></li>
                <li>Connect your explanation to the specific context of the scenario.
                    <span class="ideal-ans">Target: "The gap between the peaks at '.$peak1.' and '.$peak2.' '.$unit_label.' supports the idea that different underlying factors are driving the '.$data_label_plural.' for each group."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Further Investigation<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Recommend a specific next step to explore whether subgroups exist.
                    <span class="ideal-ans">Target: "To investigate further, researchers should '.$investigation_rec.' to see if the bimodal pattern separates into two unimodal distributions, confirming the existence of two distinct subgroups."</span></li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="full-response-box">
        <span style="color:#2E7D32; font-weight:bold;">Model Narrative Response:</span><br><br>
        '.$sample_narrative.'
      </div>
    </div>
  </details>
</div>';

/* ---------- 5. Question Text ---------- */
$questiontext = '
<div style="font-family:Arial; font-size:medium; line-height:1.6;">
  <p>'.$scenario.'.</p>
  <p><b>Essay Prompt:</b><br>
  Write a conclusion statement about what this bimodal pattern might suggest about the '.$subject_label.' or the data collection process, and explain what further investigation you would recommend.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>What a bimodal distribution looks like and what it indicates about the data.</li>
    <li>A plausible explanation for why two distinct groups might appear in this scenario.</li>
    <li>A specific recommendation for how to investigate further.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton