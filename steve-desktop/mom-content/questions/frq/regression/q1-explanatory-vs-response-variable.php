// === NAME - DESCRIPTION: Explanatory vs Response Variable - Justify the Choice - Students identify which variable is the explanatory (predictor) and which is the response (outcome) in a randomized real-world scenario, justify the role assignment in context, and explain why this choice matters for a regression analysis. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
  "a high school teacher records the number of hours each student studied per week and that student's final exam score (out of 100)",
  "a nutrition researcher records each participant's average daily calorie intake and that participant's body weight (in pounds)",
  "an economist records each worker's years of full-time work experience and that worker's annual salary (in thousands of dollars)"
);
$i = rand(0, count($contexts)-1);
$topic = $contexts[$i];

$x_vars = array(
  "hours studied per week",
  "average daily calorie intake",
  "years of full-time work experience"
);
$y_vars = array(
  "final exam score (out of 100)",
  "body weight (in pounds)",
  "annual salary (in thousands of dollars)"
);
$x_units = array("hours per week", "calories per day", "years");
$y_units = array("points", "pounds", "thousand dollars");
$reasoning_phrases = array(
  "the amount of time a student spends studying is something we believe influences how well they perform on the exam, not the other way around",
  "what a person eats over time is something we believe influences their body weight, not the other way around",
  "the amount of work experience a person has accumulated is something we believe influences their salary, not the other way around"
);
$prediction_phrases = array(
  "predict a student's exam score from the number of hours they studied",
  "predict a person's body weight from their daily calorie intake",
  "predict a worker's annual salary from their years of experience"
);
$reverse_phrases = array(
  "predicting hours studied from a student's exam score",
  "predicting daily calorie intake from a person's body weight",
  "predicting years of experience from a worker's salary"
);

$x_var = $x_vars[$i];
$y_var = $y_vars[$i];
$x_unit = $x_units[$i];
$y_unit = $y_units[$i];
$reasoning_phrase = $reasoning_phrases[$i];
$prediction_phrase = $prediction_phrases[$i];
$reverse_phrase = $reverse_phrases[$i];

// Narrative variables for the model answer
$r_identify = "In this scenario, the explanatory variable (x) is $x_var, and the response variable (y) is $y_var";
$r_justify = "We assign these roles because $reasoning_phrase. The explanatory variable is the one we believe predicts or influences changes in the response variable, so it makes sense to use $x_var as x and $y_var as y";
$r_matters = "This choice matters because a regression equation predicts the response variable from the explanatory variable. Here, the line lets us $prediction_phrase. If we swapped the roles and tried $reverse_phrase, we would get a different equation, a different slope interpretation, and a prediction that does not match the real-world cause-and-effect relationship we are trying to study";

$sample_narrative = "<b>$r_identify</b>. <b>$r_justify</b>. <b>$r_matters</b>.";

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
            <td style="text-align:center;"><b>Identifying the Variables</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Clearly state which variable in the scenario is the explanatory variable (x) and which is the response variable (y).</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Justification in Context</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain WHY this role assignment fits the scenario, using the context to argue which variable is doing the predicting.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Why It Matters</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain why this choice matters for a regression analysis (the equation predicts y from x).</label></li>
                <li><label><input type="checkbox"> Note that swapping the roles would give a different line and a different interpretation.</label></li>
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
            <td style="text-align:center;"><b>Identifying the Variables<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Name which variable is explanatory (x) and which is response (y) for this scenario.
                    <span class="ideal-ans">Target: "The explanatory variable (x) is '.$x_var.', and the response variable (y) is '.$y_var.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Justification in Context<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain why this role assignment fits the scenario, applied to the context.
                    <span class="ideal-ans">Target: "The explanatory variable is the one we believe predicts or influences the response variable. Here, '.$reasoning_phrase.', so '.$x_var.' is x and '.$y_var.' is y."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Why It Matters<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain that regression predicts the response from the explanatory variable, and that swapping roles changes the equation and the interpretation.
                    <span class="ideal-ans">Target: "A regression equation predicts y from x, so it lets us '.$prediction_phrase.'. If we reversed the roles and tried '.$reverse_phrase.', we would get a different line, a different slope meaning, and a prediction that does not match the real predictor-outcome relationship."</span></li>
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
  <p>Suppose '.$topic.'. The researcher wants to use this data to fit a linear regression model.</p>
  <p><b>Essay Prompt:</b><br>
  Identify which of the two variables in this scenario should be treated as the explanatory variable (x) and which should be treated as the response variable (y). Justify your choice using the context, then explain why getting this assignment right matters for a regression analysis.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>Which variable is explanatory (x) and which is response (y).</li>
    <li>Why that role assignment fits this specific scenario.</li>
    <li>Why the choice matters for the regression equation and its interpretation.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
