// === NAME - DESCRIPTION: Interpret Slope and Y-Intercept in Context - Students interpret the slope and y-intercept of a randomized least-squares regression equation in plain language with correct units, and address whether the y-intercept makes practical sense given the data range. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
  "a tutor records the number of hours each student studies per week and that student's score on a 100-point exam",
  "a used-car dealer records the age (in years) of cars on the lot along with each car's resale value (in thousands of dollars)",
  "an ice cream shop records the daily high temperature (in &deg;F) and the day's ice cream sales (in thousands of dollars)"
);
$i = rand(0, count($contexts)-1);
$topic = $contexts[$i];

$x_vars       = array("hours studied per week", "age of the car (in years)", "daily high temperature (in &deg;F)");
$y_vars       = array("exam score", "resale value", "ice cream sales");
$x_units      = array("hour of study per week", "year of age", "&deg;F of temperature");
$y_units      = array("points", "thousand dollars", "thousand dollars");
$x_var_short  = array("study time", "age", "temperature");
$x_range_lows = array(2, 1, 50);
$x_range_highs= array(15, 12, 95);

// Slope and intercept ranges per context
if ($i == 0) {
  $b = rrand(3.0, 5.0, 0.1);
  $a = rand(40, 55);
} else if ($i == 1) {
  $b = -1 * rrand(1.5, 2.5, 0.1);
  $a = rand(22, 30);
} else {
  $b = rrand(0.10, 0.30, 0.05);
  $a = rand(-5, 5);
}

$x_var      = $x_vars[$i];
$y_var      = $y_vars[$i];
$x_unit     = $x_units[$i];
$y_unit     = $y_units[$i];
$x_short    = $x_var_short[$i];
$x_low      = $x_range_lows[$i];
$x_high     = $x_range_highs[$i];

// Direction word for the slope
if ($b >= 0) { $direction = "increases"; } else { $direction = "decreases"; }
$abs_b = abs($b);

// Build a clean equation string for AsciiMath: hat(y) = a + bx (handle negative slope)
if ($b >= 0) {
  $equation_display = "hat(y) = $a + ".$abs_b."x";
} else {
  $equation_display = "hat(y) = $a - ".$abs_b."x";
}

// Per-context narrative for whether the intercept is practically meaningful
$intercept_narratives = array(
  "Here, x = 0 means a student who does no studying at all. That value is outside the observed data range of $x_low to $x_high hours, so the predicted score of $a points is an extrapolation. It is mathematically required by the line but should be interpreted with caution",
  "Here, x = 0 means a brand-new car (zero years old). That is just outside the observed data range of $x_low to $x_high years, but it is a realistic value, so the intercept of $a thousand dollars is a reasonable estimate of the price of a new car",
  "Here, x = 0 means a daily high temperature of 0&deg;F. That is far outside the observed data range of $x_low&deg;F to $x_high&deg;F, so the intercept of $a thousand dollars is an extrapolation and is not practically meaningful in this context"
);
$intercept_narrative = $intercept_narratives[$i];

// Narrative variables for the model answer
$r_slope    = "For each additional 1 $x_unit, the predicted $y_var $direction by approximately $abs_b $y_unit";
$r_intercept = "When $x_short is 0, the predicted $y_var is $a $y_unit";
$r_practical = $intercept_narrative;

$sample_narrative = "<b>Slope: $r_slope.</b> <b>Y-Intercept: $r_intercept.</b> <b>Practical meaning of the intercept: $r_practical.</b>";

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
            <td style="text-align:center;"><b>Slope Interpretation</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Write a complete sentence interpreting the slope in the context of the problem.</label></li>
                <li><label><input type="checkbox"> Use the correct direction (increase or decrease) based on the sign of the slope.</label></li>
                <li><label><input type="checkbox"> Include the correct units for both the explanatory and response variables.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Y-Intercept Interpretation</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Write a complete sentence interpreting the y-intercept (the predicted response when x = 0).</label></li>
                <li><label><input type="checkbox"> Include the correct units for the response variable.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Practical Meaning of the Intercept</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Address whether x = 0 falls inside or outside the observed data range.</label></li>
                <li><label><input type="checkbox"> Explain whether the y-intercept is practically meaningful in this context.</label></li>
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
            <td style="text-align:center;"><b>Slope Interpretation<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Write a contextual sentence interpreting the slope, with the correct direction and units.
                    <span class="ideal-ans">Target: "For each additional 1 '.$x_unit.', the predicted '.$y_var.' '.$direction.' by approximately '.$abs_b.' '.$y_unit.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Y-Intercept Interpretation<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Write a contextual sentence interpreting the y-intercept (predicted y when x = 0), with units.
                    <span class="ideal-ans">Target: "When '.$x_short.' is 0, the predicted '.$y_var.' is '.$a.' '.$y_unit.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Practical Meaning of the Intercept<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>State whether x = 0 is inside the observed data range and whether the intercept is practically meaningful.
                    <span class="ideal-ans">Target: "'.$intercept_narrative.'."</span></li>
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
  <p>Suppose '.$topic.'. After fitting a least-squares regression line to the data, the resulting equation is:</p>
  <p style="text-align:center; font-size:large;">`'.$equation_display.'`</p>
  <p>where x represents '.$x_var.' and &#375; is the predicted '.$y_var.'. The observed data covered values of x from about '.$x_low.' to '.$x_high.'.</p>
  <p><b>Essay Prompt:</b><br>
  Interpret both the slope and the y-intercept of this regression equation in the context of the problem. Then address whether the y-intercept has a practical meaning here.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>What the slope tells us about the predicted change in '.$y_var.' for each additional 1 '.$x_unit.' (be sure to use the correct direction and units).</li>
    <li>What the y-intercept tells us about the predicted '.$y_var.' when '.$x_short.' equals 0 (with units).</li>
    <li>Whether x = 0 falls within the observed data range, and whether the y-intercept is practically meaningful in this scenario.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
