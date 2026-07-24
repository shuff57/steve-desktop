// === NAME - DESCRIPTION: Inference for the Slope - Concept and Hypotheses (Variant 2) - Students explain what a hypothesis test for the slope of a regression line is trying to determine, apply the idea to a randomized real-world bivariate scenario, and state the null and alternative hypotheses using &#946;&#8321; notation. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$context_phrases = array(
  "a real estate agent wonders whether the number of bedrooms in a house is linearly related to its selling price",
  "a used car manager wants to know whether the age of a car has a real linear relationship with its resale value",
  "a music teacher wonders whether daily practice hours are linearly related to performance scores at a recital"
);

$populations = array(
  "all houses sold in the county last year",
  "all used cars of that model sold in the region",
  "all students who take lessons from the teacher"
);

$x_vars = array(
  "number of bedrooms",
  "age of the car (years)",
  "daily practice hours"
);

$y_vars = array(
  "selling price (thousands of dollars)",
  "resale value (thousands of dollars)",
  "performance score (out of 100)"
);

$x_units = array("bedrooms", "years", "hours per day");
$y_units = array("thousands of dollars", "thousands of dollars", "points");

$sample_actions = array(
  "randomly select houses from the county records, record how many bedrooms each one has, and record its selling price",
  "randomly select used cars of that model, record each car's age, and record its resale value",
  "randomly select students, record how many hours each one practices per day, and record their recital performance score"
);

$sample_sizes = array(rand(40, 80), rand(25, 50), rand(30, 70));

$i = rand(0, count($context_phrases)-1);

$context_phrase = $context_phrases[$i];
$population = $populations[$i];
$x_var = $x_vars[$i];
$y_var = $y_vars[$i];
$x_unit = $x_units[$i];
$y_unit = $y_units[$i];
$sample_action = $sample_actions[$i];
$n = $sample_sizes[$i];

// Narrative variables for the model answer
$r_purpose = "A hypothesis test for the slope of a regression line determines whether the sample evidence is strong enough to conclude that the population slope &#946;&#8321; is not zero, which would mean there is a real linear relationship between the explanatory variable x and the response variable y in the population";
$r_example = "For this scenario, we would $sample_action for n = $n cases. The population is $population, the explanatory variable x is $x_var, and the response variable y is $y_var. We would then fit a least-squares regression line to the sample and use the sample slope b&#8321; to test what the population slope &#946;&#8321; is likely to be";
$r_h0 = "H&#8320;: &#946;&#8321; = 0";
$r_h1 = "H&#8321;: &#946;&#8321; &#8800; 0";
$r_h0_meaning = "Under H&#8320;, the true population slope is zero, meaning $x_var has no linear relationship with $y_var in $population. Any nonzero slope we see in the sample would just be due to random sampling variability";

$sample_narrative = "<b>$r_purpose</b>. In this case, suppose $context_phrase. <b>$r_example</b>. The hypotheses are <b>$r_h0</b> and <b>$r_h1</b>. <b>$r_h0_meaning</b>.";

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
            <td style="text-align:center;"><b>Purpose of the Test</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain what question a hypothesis test for the slope of a regression line is designed to answer (whether there is a real linear relationship between x and y in the population).</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Real-World Example</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Use the given scenario as an example, identifying the population, the explanatory variable x, the response variable y, and the data that would be collected.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Hypotheses</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State both H&#8320; and H&#8321; correctly using &#946;&#8321; notation.</label></li>
                <li><label><input type="checkbox"> Explain in plain language what H&#8320; assumes about the relationship between x and y.</label></li>
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
            <td style="text-align:center;"><b>Purpose of the Test<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain what a slope hypothesis test determines.
                    <span class="ideal-ans">Target: "A test for the slope determines whether the sample evidence is strong enough to conclude that the population slope &#946;&#8321; is not zero, which would mean there is a real linear relationship between the explanatory variable and the response variable in the population. If &#946;&#8321; = 0, then x and y have no linear relationship."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Real-World Example<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Apply the concept to the randomized scenario with population, x and y variables, and data collection.
                    <span class="ideal-ans">Target: "For this scenario, we would '.$sample_action.' for n = '.$n.' cases. The population is '.$population.', the explanatory variable x is '.$x_var.', and the response variable y is '.$y_var.'. We would then fit a regression line and use the sample slope b&#8321; to make inferences about &#946;&#8321;."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Hypotheses<br>(4 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>State the null and alternative hypotheses using &#946;&#8321;.
                    <span class="ideal-ans">Target: "H&#8320;: &#946;&#8321; = 0 and H&#8321;: &#946;&#8321; &#8800; 0"</span></li>
                <li>Explain what H&#8320; means in plain language.
                    <span class="ideal-ans">Target: "Under H&#8320;, the true population slope is zero, meaning '.$x_var.' has no linear relationship with '.$y_var.' in '.$population.'. Any apparent slope in the sample would just be due to random sampling variability."</span></li>
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
  <p>Suppose '.$context_phrase.'. To investigate, a researcher plans to take a random sample of size n = '.$n.' and fit a least-squares regression line.</p>
  <p><b>Essay Prompt:</b><br>
  Explain what a hypothesis test for the slope of a regression line is trying to determine. Then use this scenario as your example to identify the population, the explanatory variable x, the response variable y, and the data that would be collected. Finally, state the null and alternative hypotheses using &#946;&#8321; notation and explain in plain language what H&#8320; assumes about the relationship between x and y.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>The purpose of a slope hypothesis test, including what it would mean if &#946;&#8321; = 0.</li>
    <li>A real-world example from the given scenario including population, x and y variables, and the data that would be collected.</li>
    <li>H&#8320; and H&#8321; using &#946;&#8321; notation, and a plain-language explanation of what H&#8320; assumes.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
