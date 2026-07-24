// === NAME - DESCRIPTION: Predicting from a Regression Equation - Interpretation - Students plug a randomized x-value into a randomized least-squares regression equation, report the predicted y in context, and explain why the prediction is an estimate rather than an exact value. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
  "a real estate analyst studied the relationship between a home's size and its sale price",
  "a running coach studied the relationship between weeks of training and 5K race time",
  "a used car dealer studied the relationship between a car's age and its resale value"
);
$i = rand(0, count($contexts)-1);
$topic = $contexts[$i];

// Parallel arrays describing the regression scenario
$x_var_names = array("home size", "weeks of training", "age of car");
$y_var_names = array("sale price", "5K race time", "resale value");
$x_units_arr = array("hundreds of square feet", "weeks", "years");
$y_units_arr = array("thousand dollars", "minutes", "thousand dollars");
$x_range_lows = array(10, 4, 1);
$x_range_highs = array(30, 16, 10);

$x_var_name = $x_var_names[$i];
$y_var_name = $y_var_names[$i];
$x_units = $x_units_arr[$i];
$y_units = $y_units_arr[$i];
$x_range_low = $x_range_lows[$i];
$x_range_high = $x_range_highs[$i];

// Generate slope, intercept, and x_query per scenario
if ($i == 0) {
  // Home: y-hat in $1000s, x in hundreds of sqft
  $a = rand(50, 80);
  $b = rand(8, 12);
  $x_query = rand(15, 25);
} elseif ($i == 1) {
  // Training: y-hat in minutes, x in weeks (negative slope, one decimal)
  $a = rand(28, 32);
  $b = -1 * rrand(0.5, 1.0, 0.1);
  $x_query = rand(6, 14);
} else {
  // Car: y-hat in $1000s, x in years (negative integer slope)
  $a = rand(20, 28);
  $b = -1 * rand(2, 3);
  $x_query = rand(2, 8);
}

$y_pred = $a + $b * $x_query;
$y_pred = round($y_pred, 2);

// Build a clean equation display string for AsciiMath
if ($b >= 0) {
  $equation_display = "\\hat{y} = $a + {$b}x";
} else {
  $b_abs = abs($b);
  $equation_display = "\\hat{y} = $a - {$b_abs}x";
}

// Build a clean computation string showing the substitution
if ($b >= 0) {
  $computation_display = "\\hat{y} = $a + $b($x_query) = $y_pred";
} else {
  $b_abs = abs($b);
  $computation_display = "\\hat{y} = $a - $b_abs($x_query) = $y_pred";
}

// Narrative variables for the model answer
$r_compute = "Substituting x = $x_query into the regression equation gives `$computation_display`. So the predicted $y_var_name is $y_pred $y_units";
$r_interpret = "When the $x_var_name is $x_query $x_units, we predict the $y_var_name to be approximately $y_pred $y_units";
$r_estimate = "The regression equation is the best-fit line through scattered data, so $y_pred $y_units is the model's estimate of the average $y_var_name when $x_var_name = $x_query $x_units. Actual observed values vary around the line because of natural variability and other factors not captured by $x_var_name, so any individual case at x = $x_query is unlikely to land exactly on $y_pred";

$sample_narrative = "<b>$r_compute</b>. In context, <b>$r_interpret</b>. <b>$r_estimate</b>.";

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
            <td style="text-align:center;"><b>Compute the Prediction</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Substitute the given x-value into the regression equation and report the predicted y-value with correct units.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Interpret in Context</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Write a complete sentence that explains the predicted value in the words of the scenario, including both variables and their units.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Why It is an Estimate</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain that the regression line is a best-fit line through scattered data and the prediction represents the average y at that x value.</label></li>
                <li><label><input type="checkbox"> Note that individual observations vary around the line, so the prediction is unlikely to be exact for any single case.</label></li>
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
            <td style="text-align:center;"><b>Compute the Prediction<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Plug x = '.$x_query.' into the regression equation and report the numeric prediction with units.
                    <span class="ideal-ans">Target: "`'.$computation_display.'`, so the predicted '.$y_var_name.' is '.$y_pred.' '.$y_units.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Interpret in Context<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Write a full contextual sentence connecting the x-value, the predicted y-value, the variables, and their units.
                    <span class="ideal-ans">Target: "When the '.$x_var_name.' is '.$x_query.' '.$x_units.', we predict the '.$y_var_name.' to be approximately '.$y_pred.' '.$y_units.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Why It is an Estimate<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain that the regression line is the best-fit line through scattered data and the prediction is the model&#39;s estimate of the average y at that x.
                    <span class="ideal-ans">Target: "The regression equation gives the best-fit line through the data, so '.$y_pred.' '.$y_units.' is the predicted average '.$y_var_name.' when '.$x_var_name.' = '.$x_query.' '.$x_units.', not an exact value for any single case."</span></li>
                <li>Note that individual observations scatter around the line because of natural variability and other unmeasured factors.
                    <span class="ideal-ans">Target: "Actual observed '.$y_var_name.' values vary above and below the line because of natural variability and factors not captured by '.$x_var_name.', so any individual case at x = '.$x_query.' is unlikely to land exactly on '.$y_pred.'."</span></li>
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
  <p>Suppose '.$topic.'. Let x = '.$x_var_name.' (in '.$x_units.') and y = '.$y_var_name.' (in '.$y_units.'). The least-squares regression line based on the sample data is:</p>
  <p style="text-align:center; font-size:large;">`'.$equation_display.'`</p>
  <p>The observed x-values in the data ranged from '.$x_range_low.' to '.$x_range_high.' '.$x_units.'. Use this equation to predict the '.$y_var_name.' when the '.$x_var_name.' is <b>x = '.$x_query.' '.$x_units.'</b>.</p>
  <p><b>Essay Prompt:</b><br>
  Compute the predicted '.$y_var_name.' for x = '.$x_query.', then write a complete sentence interpreting that prediction in the context of this scenario. Finally, explain why this predicted value should be treated as an estimate rather than an exact value.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>The substitution and the numeric predicted value with correct units.</li>
    <li>A contextual sentence that ties the predicted y-value back to the x-value and the variables in the scenario.</li>
    <li>Why the prediction is the model&#39;s estimate of the average y at that x, and why individual observations are unlikely to match it exactly.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
