// === NAME - DESCRIPTION: Z-Scores and Normal Probability - Students calculate a z-score for a normally distributed scenario, interpret what it means in context, and determine whether the score would be considered unusual. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$i = rand(0, 2);

$contexts = array(
  "a standardized academic aptitude test",
  "a physical fitness assessment",
  "an employee performance evaluation"
);
$topic = $contexts[$i];

$score_labels = array("test score", "fitness score", "performance rating");
$score_label = $score_labels[$i];

$subject_labels = array("student", "participant", "employee");
$subject_label = $subject_labels[$i];

$setting_labels = array("test-takers", "assessment participants", "evaluated employees");
$setting_label = $setting_labels[$i];

$name = randname();

// Randomized numerical values
// z always equals exactly $k (either 2 or 3), ensuring a clean, clearly unusual result
$mu = rand(90, 110);
$sigma = rand(10, 20);
$k = rand(2, 3);
$xval = $mu + $k * $sigma;
$zscore = $k;

// Percentile context varies by z-score (k=2 maps to index 0, k=3 maps to index 1)
$p_index = $k - 2;

$percentile_notes = array(
  "above approximately 97.5% of all " . $setting_label . " (roughly the top 2.5%)",
  "above approximately 99.85% of all " . $setting_label . " (roughly the top 0.15%)"
);
$percentile_note = $percentile_notes[$p_index];

$unusual_phrases = array(
  "A z-score of 2 meets the common threshold for unusual values (|z| &#8805; 2), so this score is considered unusual.",
  "A z-score of 3 is well beyond the common threshold of 2, so this score is clearly unusual."
);
$unusual_phrase = $unusual_phrases[$p_index];

// Narrative variables for the model answer
$r_calculation = "The z-score formula is z = (x - &#956;) / &#963;. Plugging in the values: z = (" . $xval . " - " . $mu . ") / " . $sigma . " = " . $zscore . ".";

$r_interpretation = "A z-score of " . $zscore . " means that " . $name . " scored " . $zscore . " standard deviations above the mean " . $score_label . " for " . $setting_label . ". In practical terms, " . $name . " performed significantly better than the typical " . $subject_label . ".";

$r_conclusion = $unusual_phrase . " With a score of " . $xval . ", " . $name . " falls " . $percentile_note . ". By any standard, this is an unusually high result.";

$sample_narrative = "<b>" . $r_calculation . "</b> " . $r_interpretation . " <b>" . $r_conclusion . "</b>";

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
// NO answers shown - just checkbox criteria for students
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
            <td style="text-align:center;"><b>Z-Score Calculation</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Write the z-score formula and identify the correct values from the problem.</label></li>
                <li><label><input type="checkbox"> Perform the calculation and state the final z-score.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Z-Score Interpretation</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain what the z-score value means in the context of this scenario.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Unusual or Typical</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State whether the score is unusual or typical.</label></li>
                <li><label><input type="checkbox"> Provide reasoning using the z-score threshold or the empirical rule.</label></li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </details>
</div>';

/* ---------- 4. Instructor Rubric (With Answer Targets) ---------- */
// Shows ideal answers and full model response - only visible when grading
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
            <td style="text-align:center;"><b>Z-Score Calculation<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Write the z-score formula and identify the correct values from the problem.
                    <span class="ideal-ans">Target: "z = (x - &#956;) / &#963;, where x = '.$xval.', &#956; = '.$mu.', and &#963; = '.$sigma.'"</span></li>
                <li>Perform the calculation and state the final z-score.
                    <span class="ideal-ans">Target: "z = ('.$xval.' - '.$mu.') / '.$sigma.' = '.$zscore.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Z-Score Interpretation<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain what the z-score value means in the context of this scenario.
                    <span class="ideal-ans">Target: "A z-score of '.$zscore.' means '.$name.' scored '.$zscore.' standard deviations above the mean '.$score_label.' for '.$setting_label.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Unusual or Typical<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>State whether the score is unusual or typical.
                    <span class="ideal-ans">Target: "'.$unusual_phrase.'"</span></li>
                <li>Provide reasoning using the z-score threshold or the empirical rule.
                    <span class="ideal-ans">Target: "This score falls '.$percentile_note.', confirming it is an unusually high result."</span></li>
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
  <p>Scores on '.$topic.' are normally distributed with a mean of '.$mu.' and a standard deviation of '.$sigma.'. One '.$subject_label.', '.$name.', receives a '.$score_label.' of '.$xval.'.</p>
  <p><b>Essay Prompt:</b><br>
  Calculate the z-score for '.$name.' and write a conclusion statement interpreting what this z-score means in context. Then explain whether this score would be considered unusual or typical.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>The z-score formula and the calculation showing your work.</li>
    <li>An interpretation of what the z-score tells us about how '.$name.' performed compared to other '.$setting_label.'.</li>
    <li>Whether this score is unusual or typical, with reasoning to support your conclusion.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
