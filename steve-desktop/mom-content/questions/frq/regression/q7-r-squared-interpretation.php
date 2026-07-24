// === NAME - DESCRIPTION: R-Squared Interpretation - Students explain what R-squared represents in a linear regression model, interpret a randomized R-squared value in a randomized real-world context, and explain what the unexplained portion (1 - R-squared) tells us. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
  "a college statistics professor records the number of hours each student studied for an exam and the score each student earned",
  "a regional retail chain tracks its monthly advertising spend and its monthly sales revenue across many stores",
  "an ice cream shop owner records the daily high outdoor temperature and the daily ice cream sales total",
  "a real estate analyst studies the relationship between the square footage of a home and the home's selling price for homes in a single city"
);

$x_vars = array(
  "hours studied",
  "monthly ad spend (in thousands of dollars)",
  "daily high outdoor temperature (&deg;F)",
  "square footage of the home"
);

$y_vars = array(
  "exam score",
  "monthly sales revenue (in thousands of dollars)",
  "daily ice cream sales (in dollars)",
  "selling price of the home"
);

$other_factors_list = array(
  "innate ability, sleep quality, prior knowledge, test anxiety, and the difficulty of the specific exam",
  "store location, local competition, seasonality, product mix, and broader economic conditions",
  "day of the week, school being in session, weekend tourism, special events, and local promotions",
  "neighborhood, age and condition of the home, lot size, school district, and current market conditions"
);

$i = rand(0, count($contexts)-1);
$topic = $contexts[$i];
$x_var = $x_vars[$i];
$y_var = $y_vars[$i];
$other_factors = $other_factors_list[$i];

// Randomize R-squared in a meaningful range
$r_squared = rrand(0.30, 0.90, 0.01);
$r_sq_pct = round($r_squared * 100, 0);
$unexplained_pct = 100 - $r_sq_pct;

// Narrative variables for the model answer
$r_meaning = "R&#178; (the coefficient of determination) is the proportion of the variation in the response variable y that is explained by the linear regression model using the explanatory variable x. It is always between 0 and 1, where 0 means the linear model explains none of the variation in y, and 1 means the linear model explains all of it.";
$r_context = "Approximately $r_sq_pct% of the variation in $y_var is explained by the linear regression model using $x_var as the predictor.";
$r_unexplained = "The remaining $unexplained_pct% of the variation in $y_var is NOT explained by the linear model with $x_var. This unexplained variation is due to other factors not captured by the model, such as $other_factors, along with random individual variation and measurement error. It can also reflect any non-linear pattern that a straight-line model cannot capture.";

$sample_narrative = "<b>$r_meaning</b> In this scenario, $topic, and the regression yields R&#178; = $r_squared. <b>$r_context</b> <b>$r_unexplained</b>";

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
            <td style="text-align:center;"><b>General Meaning of R&#178;</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State that R&#178; is the proportion (or percentage) of variation in the response variable y that is explained by the linear regression model with x.</label></li>
                <li><label><input type="checkbox"> Note that R&#178; is bounded between 0 and 1.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Interpret the Specific Value in Context</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Convert R&#178; to a percentage and write a contextual sentence using the actual response and explanatory variable names from the scenario.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Unexplained Variation</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Compute 1 &minus; R&#178; as a percentage and state what it represents in context.</label></li>
                <li><label><input type="checkbox"> Identify plausible reasons for the unexplained variation (other variables, individual variation, measurement error, or non-linear effects).</label></li>
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
            <td style="text-align:center;"><b>General Meaning of R&#178;<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Define R&#178; in general terms and state its bounds.
                    <span class="ideal-ans">Target: "R&#178; is the proportion of the variation in the response variable y that is explained by the linear regression model with the explanatory variable x. It always falls between 0 and 1, where 0 means the linear model explains none of the variation in y and 1 means the linear model explains all of it."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Interpret the Specific Value in Context<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Convert R&#178; to a percent and write a context sentence using the actual variables.
                    <span class="ideal-ans">Target: "Approximately '.$r_sq_pct.'% of the variation in '.$y_var.' is explained by the linear regression model using '.$x_var.' as the predictor."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Unexplained Variation<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Compute 1 &minus; R&#178; as a percent and explain what it represents.
                    <span class="ideal-ans">Target: "The remaining '.$unexplained_pct.'% of the variation in '.$y_var.' is NOT explained by the linear model with '.$x_var.'."</span></li>
                <li>List plausible sources for the unexplained variation.
                    <span class="ideal-ans">Target: "This unexplained variation comes from other factors not in the model, such as '.$other_factors.', along with random individual variation, measurement error, and any non-linear pattern a straight line cannot capture."</span></li>
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
  <p>Suppose '.$topic.'. After fitting a least-squares regression line predicting '.$y_var.' from '.$x_var.', the analysis reports `R^2 = '.$r_squared.'`.</p>
  <p><b>Essay Prompt:</b><br>
  Explain what R&#178; represents in a linear regression model in general, then interpret the specific value `R^2 = '.$r_squared.'` in the context of this scenario. Finally, explain what the unexplained portion (1 &minus; R&#178;) tells us about this situation.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>What R&#178; measures in general, including the range of possible values.</li>
    <li>A contextual interpretation of the specific R&#178; value, written as a percentage and using the actual variables in the scenario.</li>
    <li>What 1 &minus; R&#178; represents as a percentage, and why some variation in '.$y_var.' is not explained by '.$x_var.' alone.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
