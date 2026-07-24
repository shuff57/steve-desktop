// === NAME - DESCRIPTION: Normal Distribution and Percentiles - Students calculate the score needed to be in the top 10% of a normal distribution using z = 1.28, then determine how many individuals out of a population would be expected to score that high. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
  "A standardized aptitude test has scores that are normally distributed",
  "Annual household incomes (in thousands of dollars) in a metropolitan area are normally distributed",
  "Employee performance review scores (on a 0-500 scale) at a large corporation are normally distributed"
);
$i = rand(0, count($contexts)-1);
$topic = $contexts[$i];

$score_names = array("test score", "income level", "performance score");
$unit_names = array("points", "thousand dollars", "points");
$group_names = array("test-takers", "households", "employees");

$score_name = $score_names[$i];
$unit_name = $unit_names[$i];
$group_name = $group_names[$i];

// Randomized numerical values per context
$mu_vals = array(rand(900,1100), rand(55,75), rand(300,380));
$sigma_vals = array(rand(150,250), rand(12,20), rand(40,70));
$pop_vals = array(rand(4,8)*1000, rand(2,6)*1000, rand(3,7)*1000);

$mu = $mu_vals[$i];
$sigma = $sigma_vals[$i];
$pop = $pop_vals[$i];

// Computed values
$threshold = round($mu + 1.28*$sigma);
$top_count = round($pop * 0.10);

// Narrative variables for the model answer
$r_formula = "To find the $score_name needed for the top 10%, we use the formula x = &#956; + z &#215; &#963;. Plugging in: x = $mu + 1.28 &#215; $sigma = $threshold $unit_name";

$r_interpret = "A $score_name of $threshold $unit_name is the cutoff to place in the top 10% of all $group_name";

$r_count = "Out of $pop $group_name, 10% comes to $top_count. So roughly $top_count $group_name would be expected to reach or exceed this threshold";

$sample_narrative = "<b>$r_formula</b>. <b>$r_interpret</b>. <b>$r_count</b>.";

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
            <td style="text-align:center;"><b>Formula Application<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Identify the correct formula for converting a z-score to a raw score.</label></li>
                <li><label><input type="checkbox"> Substitute the given values and show the calculation.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Threshold Interpretation<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain what the calculated score represents in the context of the problem.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;" class="col-cat-bot"><b>Population Count<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Calculate how many individuals out of the total would reach or exceed the threshold.</label></li>
                <li><label><input type="checkbox"> Interpret this count in the context of the scenario.</label></li>
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
            <td style="text-align:center;"><b>Formula Application<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Identify the correct formula for converting a z-score to a raw score.
                    <span class="ideal-ans">Target: "x = &#956; + z &#215; &#963; (this converts a z-score back to a raw score)."</span></li>
                <li>Substitute the given values and show the calculation.
                    <span class="ideal-ans">Target: "x = '.$mu.' + 1.28 &#215; '.$sigma.' = '.$threshold.' '.$unit_name.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Threshold Interpretation<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain what the calculated score represents in context.
                    <span class="ideal-ans">Target: "A '.$score_name.' of '.$threshold.' '.$unit_name.' is the minimum needed to be in the top 10% of all '.$group_name.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;" class="col-cat-bot"><b>Population Count<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Calculate how many individuals out of the total would reach or exceed the threshold.
                    <span class="ideal-ans">Target: "10% of '.$pop.' = '.$top_count.' '.$group_name.'."</span></li>
                <li>Interpret this count in context.
                    <span class="ideal-ans">Target: "About '.$top_count.' '.$group_name.' out of '.$pop.' would be expected to reach or exceed '.$threshold.' '.$unit_name.'."</span></li>
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
  <p>'.$topic.' with a mean of '.$mu.' '.$unit_name.' and a standard deviation of '.$sigma.' '.$unit_name.'. There are '.$pop.' '.$group_name.' in this group.</p>
  <p>Someone wants to know what '.$score_name.' is needed to be in the <b>top 10%</b>. For a normal distribution, the top 10% corresponds to a z-score of approximately <b>1.28</b>.</p>
  <p><b>Essay Prompt:</b><br>
  Calculate the '.$score_name.' needed to be in the top 10% and write a conclusion statement explaining what this score represents. Then determine how many '.$group_name.' out of '.$pop.' would be expected to reach or exceed this value.</p>
  <p>In your response, be sure to address:</p>
  <ul>
    <li>The formula for converting a z-score to a raw score, and your calculation.</li>
    <li>What the threshold '.$score_name.' means in the context of the scenario.</li>
    <li>The expected number of '.$group_name.' who would meet or exceed the threshold, and what that tells us.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
