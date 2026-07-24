// === NAME - DESCRIPTION: Least Squares Method - Conceptual Explanation - Students explain what the least squares regression line is, why it is called "least squares" (it minimizes the sum of squared residuals), and why we square the residuals rather than just adding them or taking absolute values. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
  "the relationship between hours studied and exam score",
  "the relationship between monthly advertising spend and monthly sales",
  "the relationship between the age of a used car and its resale value"
);
$i = rand(0, count($contexts)-1);
$topic = $contexts[$i];

$sample_sizes = array(rand(20,30), rand(15,22), rand(25,35));
$x_vars = array("hours studied", "monthly ad spend (in thousands of dollars)", "age of the car (in years)");
$y_vars = array("exam score", "monthly sales (in thousands of dollars)", "resale value (in thousands of dollars)");
$sample_units = array("students", "months", "used cars");
$direction_words = array("positive", "positive", "negative");
$predict_examples = array(
  "predict an exam score from the number of hours a student studied",
  "predict monthly sales from the amount spent on advertising that month",
  "predict the resale value of a used car from its age"
);

$n = $sample_sizes[$i];
$x_var = $x_vars[$i];
$y_var = $y_vars[$i];
$sample_unit = $sample_units[$i];
$direction = $direction_words[$i];
$predict_example = $predict_examples[$i];

$context_phrase = "$topic for $n $sample_unit";

// Narrative variables for the model answer
$r_definition = "The least squares regression line is the unique straight line that best fits a scatterplot of bivariate data. It has the form &#375; = a + bx and is used to $predict_example. In short, it is the line we use to predict the response variable $y_var from the explanatory variable $x_var";
$r_criterion = "It is called the 'least squares' line because, out of every possible line we could draw through the scatterplot, it is the one that makes the sum of the squared vertical distances from each point to the line as small as possible. Each vertical distance y&#7522; &#8722; &#375;&#7522; is called a residual, and the least squares line minimizes &#8721;(y&#7522; &#8722; &#375;&#7522;)&#178; across all $n data points";
$r_why_squared = "We square the residuals for three big reasons. First, the raw residuals always sum to zero for any reasonable line, so just adding them up tells us nothing about fit. Second, squaring forces every residual to be positive while also penalizing large errors more heavily than small ones, so a line that misses one point badly is worse than one that misses two points by a little. Third, the squared error function is smooth and differentiable, which gives us clean closed-form formulas for the slope and intercept. Absolute values would also fix the sign problem, but they are not differentiable at zero and make the optimization much messier";

$sample_narrative = "<b>$r_definition</b>. <b>$r_criterion</b>. <b>$r_why_squared</b>.";

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
            <td style="text-align:center;"><b>Definition of the Line</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain what the least squares regression line is and what it is used for, anchored in the given scenario.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>The "Least Squares" Criterion</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State that the line minimizes the sum of squared residuals.</label></li>
                <li><label><input type="checkbox"> Reference the formula `\sum_{i=1}^n (y_i - \hat{y}_i)^2` or describe it in words.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Why Squared, Not Absolute or Raw</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Give at least two reasons why we square residuals instead of just adding them or taking absolute values.</label></li>
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
            <td style="text-align:center;"><b>Definition of the Line<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain that the least squares line is the line of best fit through bivariate data, used to predict y from x.
                    <span class="ideal-ans">Target: "The least squares regression line is the line of best fit through a scatterplot of bivariate data. It takes the form &#375; = a + bx and is used to '.$predict_example.' &#8212; that is, to predict the response variable '.$y_var.' from the explanatory variable '.$x_var.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>The "Least Squares" Criterion<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>State the minimization criterion and (bonus) reference the formula.
                    <span class="ideal-ans">Target: "Out of every possible line through the scatterplot, the least squares line is the one that makes the sum of squared vertical distances from each point to the line as small as possible. Each vertical distance y&#7522; &#8722; &#375;&#7522; is a residual, and the least squares line minimizes &#8721;(y&#7522; &#8722; &#375;&#7522;)&#178; across all '.$n.' data points."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Why Squared, Not Absolute or Raw<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Give at least two of: raw residuals cancel out, squaring forces positive values, squaring penalizes large errors more, squaring is differentiable and gives clean closed-form formulas.
                    <span class="ideal-ans">Target: "Raw residuals always sum to zero for any sensible line, so just adding them up tells us nothing about fit. Squaring forces every residual to be positive while also penalizing large errors more heavily than small ones. Squaring is also smooth and differentiable, which gives us clean closed-form formulas for slope and intercept &#8212; absolute values would fix the sign problem too, but they are not differentiable at zero and make the optimization messier."</span></li>
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
  <p>A researcher is studying '.$context_phrase.'. The explanatory variable is '.$x_var.' and the response variable is '.$y_var.', and the scatterplot shows a roughly '.$direction.' linear trend. The researcher wants to fit a least squares regression line to the data so they can '.$predict_example.'.</p>
  <p><b>Essay Prompt:</b><br>
  Explain in your own words what the least squares regression line is for this scenario, why it is called the <i>least squares</i> line, and why we square the residuals rather than just adding them up or taking absolute values. As part of your answer, reference the criterion `\sum_{i=1}^n (y_i - \hat{y}_i)^2` and describe what it represents.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>What the least squares regression line is and what it is used for in this scenario.</li>
    <li>The "least squares" criterion &#8212; that the line minimizes the sum of squared residuals `\sum_{i=1}^n (y_i - \hat{y}_i)^2`.</li>
    <li>At least two reasons why we square the residuals instead of just adding them or taking absolute values.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
