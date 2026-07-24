// === NAME - DESCRIPTION: Identifying Sampling Bias - Students identify convenience sampling bias in a research scenario, explain how the biased sample could skew results, and evaluate whether findings can be generalized to the full population. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
  "stands outside the library at 8 PM on a Tuesday and surveys the first 50 students who walk out",
  "sets up a table outside the campus gym at noon on a Monday and surveys the first 50 students leaving after a workout",
  "waits in the main cafeteria at 7:30 AM on a Wednesday and surveys the first 50 students who sit down for breakfast"
);
$i = rand(0, count($contexts)-1);
$topic = $contexts[$i];

$research_goals = array(
  "the average number of hours college students spend studying per week",
  "the average number of hours college students spend exercising per week",
  "the average number of meals college students eat on campus each day"
);
$researchers = array("She", "He", "She");
$locations = array("the library late in the evening", "the campus gym around lunchtime", "the cafeteria early in the morning");
$biased_groups = array(
  "students who study frequently or for long hours",
  "students who already exercise regularly",
  "students who eat on campus regularly and tend to be early risers"
);
$skew_descriptions = array(
  "overestimate how many hours the typical college student studies",
  "overestimate how much the typical college student exercises",
  "overestimate how many on-campus meals the typical college student eats"
);
$missing_groups = array(
  "study at home, in their dorms, or not at all",
  "prefer other forms of exercise or don't work out regularly",
  "skip breakfast, eat off campus, or sleep in"
);

$research_goal = $research_goals[$i];
$researcher = $researchers[$i];
$location = $locations[$i];
$biased_group = $biased_groups[$i];
$skew_description = $skew_descriptions[$i];
$missing_group = $missing_groups[$i];

// Narrative variables for the model answer
$r_bias_type = "convenience sampling bias (also called selection bias), because the researcher only surveys people who happen to be at one specific location at a particular time, rather than randomly selecting from the entire student population";
$r_impact = "the sample will likely $skew_description, since students found at $location are more likely to be $biased_group. Meanwhile, students who $missing_group are completely left out of the sample";
$r_validity = "the results cannot be generalized to all college students because the sample is not representative of the full population. A stronger study design would use random sampling from the complete student enrollment list, giving every student an equal chance of being selected";

$sample_narrative = "This study design shows <b>$r_bias_type</b>. As a result, <b>$r_impact</b>. Ultimately, <b>$r_validity</b>.";

/* ---------- 2. SHARED CSS & JS ---------- */
$css_block = '
<style>
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
            <td style="text-align:center;"><b>Identify the Bias<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Name or describe the type of sampling bias present in this study.</label></li>
                <li><label><input type="checkbox"> Explain why the choice of location and timing creates a non-representative sample.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Impact on Results<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Describe how this bias could push the study\'s findings in a particular direction.</label></li>
                <li><label><input type="checkbox"> Identify which types of students are left out of this sample.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;" class="col-cat-bot"><b>Validity &amp; Improvement<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Discuss whether these results can be generalized to all college students.</label></li>
                <li><label><input type="checkbox"> Suggest a better sampling method for this study.</label></li>
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
            <td style="text-align:center;"><b>Identify the Bias<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Name or describe the type of sampling bias present in this study.
                    <span class="ideal-ans">Target: "Convenience sampling bias (also called selection bias)."</span></li>
                <li>Explain why the choice of location and timing creates a non-representative sample.
                    <span class="ideal-ans">Target: "The researcher only surveys people at one specific place and time, which captures a narrow subgroup rather than a random cross-section of all college students."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Impact on Results<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Describe how this bias could push the study\'s findings in a particular direction.
                    <span class="ideal-ans">Target: "The results will likely '.$skew_description.' because students at '.$location.' tend to be '.$biased_group.'."</span></li>
                <li>Identify which types of students are left out of this sample.
                    <span class="ideal-ans">Target: "Students who '.$missing_group.' are completely excluded from the sample."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;" class="col-cat-bot"><b>Validity &amp; Improvement<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Discuss whether these results can be generalized to all college students.
                    <span class="ideal-ans">Target: "The results cannot be reliably generalized to the full student population because the sample is not representative."</span></li>
                <li>Suggest a better sampling method for this study.
                    <span class="ideal-ans">Target: "Use random sampling from the full student enrollment list so every student has an equal chance of being selected."</span></li>
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
  <p>A researcher wants to study '.$research_goal.'. '.$researcher.' '.$topic.'.</p>
  <p><b>Essay Prompt:</b><br>
  Explain what type of sampling bias might be present in this study design and how it could affect the validity of the results.</p>
  <p>In your response, be sure to address:</p>
  <ul>
    <li>What type of bias is involved and why it applies here.</li>
    <li>How this bias could skew the findings in a particular direction.</li>
    <li>Whether the results can be generalized, and how the study design could be improved.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
