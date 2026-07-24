// === NAME - DESCRIPTION: Interpret a Residual in Context - Students compute the predicted value from a randomized regression equation, calculate the residual for an observed data point, and interpret whether the model overpredicted or underpredicted in the context of the scenario with correct units. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
  "a teacher records the number of hours each student studied (x) and that student's score on a 100-point exam (y)",
  "a real estate analyst records each home's size in hundreds of square feet (x) and its sale price in thousands of dollars (y)",
  "a marketing team records each month's ad spend in thousands of dollars (x) and the resulting sales in thousands of dollars (y)"
);
$i = rand(0, count($contexts)-1);
$topic = $contexts[$i];

$x_units    = array("hours studied",       "hundreds of square feet",         "thousand dollars of ad spend");
$y_units    = array("points",              "thousand dollars",                "thousand dollars in sales");
$x_label    = array("hours",               "hundreds of sq ft",               "thousand dollars in ad spend");
$y_label    = array("exam points",         "thousand dollars in sale price",  "thousand dollars in sales");
$x_pretty   = array("hours of study",      "hundred square feet of size",     "thousand dollars spent on ads");
$y_pretty   = array("exam score",          "home sale price",                 "monthly sales");

// Slope, intercept, observed x ranges per context (kept tight so numbers stay clean)
if ($i == 0) {
  $a = rand(45, 55);
  $b = rand(3, 5);
  $x_obs = rand(8, 12);
} else if ($i == 1) {
  $a = rand(75, 85);
  $b = rand(1, 2) * 0.5 + 0.5; // 1.0 or 1.5
  // keep slope at 0.5 for clean math
  $b = 0.5;
  $a = rand(75, 85);
  $x_obs = rand(18, 24);
} else {
  $a = rand(20, 30);
  $b = rand(7, 9);
  $x_obs = rand(4, 7);
}

// Compute predicted y, then build a residual cleanly
$y_pred = $a + $b * $x_obs;

// Clean residual magnitude and sign so the residual is unambiguous and non-zero
$resid_mag = rand(2, 8);
$sign      = randfrom(array(-1, 1));
$residual  = $sign * $resid_mag;
$y_obs     = $y_pred + $residual;

// Words for over/under prediction
if ($residual > 0) {
  $over_under = "underpredicted";
  $direction  = "higher than";
  $by_note    = "actual minus predicted is positive, so the model's prediction was too low";
  $resid_sign_word = "positive";
} else {
  $over_under = "overpredicted";
  $direction  = "lower than";
  $by_note    = "actual minus predicted is negative, so the model's prediction was too high";
  $resid_sign_word = "negative";
}

$abs_resid = abs($residual);

// Pretty strings for display
$y_pred_str = $y_pred;
$y_obs_str  = $y_obs;
$resid_str  = $residual;

// Narrative variables for the model answer
$r_predicted = "Plug x = $x_obs into the regression equation: y&#770; = $a + $b($x_obs) = $y_pred_str {$y_label[$i]}";
$r_residual  = "The residual is observed minus predicted: residual = $y_obs_str &#8722; $y_pred_str = $resid_str {$y_label[$i]}";
$r_interpret = "Because the residual is $resid_str, the model $over_under the actual $y_pretty[$i] for this observation by $abs_resid {$y_label[$i]}. The actual value was $direction what the regression equation predicted ($by_note)";

$sample_narrative = "<b>$r_predicted</b>. <b>$r_residual</b>. <b>$r_interpret</b>.";

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
      <p style="margin:0 0 0.5em 0;"><b>Grading Criteria</b> -- ensure your response covers these points:</p>
      <table class="rubric-table">
        <tbody>
          <tr>
            <th class="col-header">Category</th>
            <th class="col-check">Requirement</th>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Predicted Value</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Substitute the observed x value into the regression equation and compute y&#770; correctly with units.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Residual Calculation</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Compute the residual as observed y minus predicted y&#770; with the correct sign and units.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Interpretation in Context</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State whether the model overpredicted or underpredicted the actual value.</label></li>
                <li><label><input type="checkbox"> State by how much (the magnitude of the residual) and include the units of y in a contextual sentence.</label></li>
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
            <td style="text-align:center;"><b>Predicted Value<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Substitute x = '.$x_obs.' into the regression equation and evaluate.
                    <span class="ideal-ans">Target: "y&#770; = '.$a.' + '.$b.'('.$x_obs.') = '.$y_pred_str.' '.$y_label[$i].'"</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Residual Calculation<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Compute residual = observed y minus predicted y&#770;, keeping the sign.
                    <span class="ideal-ans">Target: "residual = '.$y_obs_str.' &#8722; '.$y_pred_str.' = '.$resid_str.' '.$y_label[$i].'"</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Interpretation in Context<br>(4 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Identify whether the model overpredicted or underpredicted the actual y value, based on the sign of the residual.
                    <span class="ideal-ans">Target: "Because the residual is '.$resid_str.' (sign is '.$resid_sign_word.'), the model '.$over_under.' the actual '.$y_pretty[$i].'."</span></li>
                <li>State the magnitude with units and tie it back to the real-world context.
                    <span class="ideal-ans">Target: "The actual '.$y_pretty[$i].' was '.$direction.' the regression prediction by '.$abs_resid.' '.$y_label[$i].' for this observation."</span></li>
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
  <p>Suppose '.$topic.'. After fitting a least-squares regression line, the analyst reports the equation</p>
  <p style="text-align:center; font-size:large;">`\hat{y} = '.$a.' + '.$b.'x`</p>
  <p>where x is measured in '.$x_units[$i].' and y is measured in '.$y_units[$i].'. One observation in the data set is the point (x, y) = ('.$x_obs.', '.$y_obs_str.').</p>
  <p><b>Essay Prompt:</b><br>
  Use the regression equation to find the predicted value y&#770; for this observation, compute the residual, and then interpret the residual in the context of the scenario. Be explicit about the sign of the residual and what it tells you about how the model performed for this data point.</p>
  <p>In your response, be sure to cover:</p>
  <ul>
    <li>The predicted value y&#770; from substituting x = '.$x_obs.' into the regression equation, shown with units.</li>
    <li>The residual computed as observed y minus predicted y&#770;, with the correct sign and units.</li>
    <li>Whether the model overpredicted or underpredicted the actual value, by how much, and what that means in the context of the scenario.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
