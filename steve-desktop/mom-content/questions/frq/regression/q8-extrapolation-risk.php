// === NAME - DESCRIPTION: Extrapolation - Why It's Risky - Students compute a prediction from a regression equation at an x-value far outside the observed data range, identify the prediction as extrapolation, and explain why predictions outside the observed range are unreliable. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
  "a running coach studied how the number of weeks of training relates to a runner's 5K race time",
  "a used-car dealership studied how the age of a used car relates to its resale value",
  "a study tracked how the number of hours studied per week relates to a student's final exam score"
);
$i = rand(0, count($contexts)-1);
$topic = $contexts[$i];

/* Parallel arrays */
$x_descriptions = array(
  "weeks of training",
  "age of the car (in years)",
  "hours studied per week"
);
$y_descriptions = array(
  "5K race time (in minutes)",
  "resale value (in thousands of dollars)",
  "final exam score (out of 100)"
);
$y_units = array("minutes", "thousand dollars", "points");
$x_range_lows = array(4, 1, 2);
$x_range_highs = array(16, 12, 15);

$x_description = $x_descriptions[$i];
$y_description = $y_descriptions[$i];
$y_unit = $y_units[$i];
$x_range_low = $x_range_lows[$i];
$x_range_high = $x_range_highs[$i];

/* Slope/intercept and out-of-range query value per context */
if ($i == 0) {
  // Context A: weeks of training vs 5K time (decreasing)
  $a = rand(28, 32);
  $b = -1 * rrand(0.5, 1.0, 0.1);
  $x_query = rand(40, 60);
  $absurd_phrase = "this would predict an impossibly low (or even negative) race time, which is physically impossible since a 5K cannot be run in zero or negative minutes";
} elseif ($i == 1) {
  // Context B: age of used car vs resale value (decreasing)
  $a = rand(22, 28);
  $b = -1 * rrand(1.5, 2.5, 0.1);
  $x_query = rand(20, 30);
  $absurd_phrase = "this would predict a negative resale value, which is impossible since a car cannot be worth less than zero dollars";
} else {
  // Context C: hours studied vs exam score (increasing)
  $a = rand(40, 55);
  $b = rrand(3.0, 5.0, 0.1);
  $x_query = rand(40, 60);
  $absurd_phrase = "this would predict a score above 100, which exceeds the maximum possible exam score";
}

/* Build the regression equation display with proper sign handling */
if ($b < 0) {
  $b_display = abs($b);
  $equation_display = "hat(y) = $a - {$b_display}x";
} else {
  $equation_display = "hat(y) = $a + {$b}x";
}

/* Compute prediction */
$y_pred = round($a + $b * $x_query, 2);

/* Narrative variables for the model answer */
$r_compute = "Plug x = $x_query into the regression equation: y&#770; = $a + ($b)($x_query) = $y_pred $y_unit";
$r_extrapolation = "The observed data range was from x = $x_range_low to x = $x_range_high, but the query value x = $x_query lies far outside that range. Making a prediction outside the observed range of x is called <b>extrapolation</b>";
$r_risk = "Extrapolation is risky because the regression model was fit only to data within the observed x-range, so we have no evidence the linear pattern continues outside it. Real-world relationships often change shape (curve, plateau, or even reverse direction) outside the observed data, and extrapolation can produce predictions that are physically impossible or wildly wrong. In this case, $absurd_phrase, which makes the prediction meaningless";

$sample_narrative = "<b>Step 1 (Compute the Prediction):</b> $r_compute. <br><br><b>Step 2 (Identify as Extrapolation):</b> $r_extrapolation. <br><br><b>Step 3 (Why It's Risky):</b> $r_risk.";

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
                <li><label><input type="checkbox"> Plug the given x-value into the regression equation and report the numeric prediction with units.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Identify as Extrapolation</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State the observed data range and note that the query x-value falls outside it.</label></li>
                <li><label><input type="checkbox"> Identify this kind of prediction as <b>extrapolation</b>.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Why Extrapolation is Risky</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Give at least two reasons predictions outside the observed range may be unreliable (the linear pattern may not continue, the relationship may bend/plateau/reverse, no evidence the model holds outside the data, or the prediction may be impossible/absurd).</label></li>
                <li><label><input type="checkbox"> Bonus: tie one of the reasons back to the specific scenario (e.g., explain why the predicted value is nonsensical for this context).</label></li>
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
            <td style="text-align:center;"><b>Compute the Prediction<br>(2 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Substitute x = '.$x_query.' into the regression equation and report the numeric prediction with units.
                    <span class="ideal-ans">Target: "y&#770; = '.$a.' + ('.$b.')('.$x_query.') = '.$y_pred.' '.$y_unit.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Identify as Extrapolation<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>State the observed range and note that the query value lies outside it, then name this kind of prediction.
                    <span class="ideal-ans">Target: "The observed data range was from x = '.$x_range_low.' to x = '.$x_range_high.', but x = '.$x_query.' lies well outside that range, so this prediction is an <b>extrapolation</b> rather than an interpolation."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Why Extrapolation is Risky<br>(5 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Give at least two reasons that predictions outside the observed x-range are unreliable.
                    <span class="ideal-ans">Target: "The regression model was fit only to data within the observed x-range, so we have no evidence the linear pattern continues outside it. Relationships often change shape (curve, plateau, or reverse) outside the data, and extrapolation can produce predictions that are physically impossible or wildly wrong."</span></li>
                <li>Bonus: connect the warning to the specific scenario.
                    <span class="ideal-ans">Target: "In this case, '.$absurd_phrase.', which makes the predicted value meaningless even though the model fits well within the observed range."</span></li>
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
  <p>Suppose '.$topic.'. Using the collected data, the following least-squares regression equation was found:</p>
  <p style="text-align:center; font-size:large;">`'.$equation_display.'`</p>
  <p>where x = '.$x_description.' and y&#770; = predicted '.$y_description.'. The observed data ranged from x = '.$x_range_low.' to x = '.$x_range_high.'.</p>
  <p>A user of this model wants to predict the value of y when <b>x = '.$x_query.'</b>.</p>
  <p><b>Essay Prompt:</b><br>
  Compute the predicted value of y when x = '.$x_query.', identify whether this prediction is an extrapolation or an interpolation, and explain why predictions made outside the observed range of x are considered unreliable even when the model fits the observed data well.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>The numeric prediction y&#770; for x = '.$x_query.', shown with the substitution and units.</li>
    <li>Whether x = '.$x_query.' is inside or outside the observed range x = '.$x_range_low.' to x = '.$x_range_high.', and the name for this kind of prediction.</li>
    <li>At least two reasons extrapolated predictions are risky, ideally connected to this specific scenario.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
