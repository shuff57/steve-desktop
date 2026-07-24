// === NAME - DESCRIPTION: Single Proportion Inference - Students explain what a hypothesis test for a single proportion is trying to answer, provide a real-world example from a randomized context, and describe what the null hypothesis would claim. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
  "a local news station reports that 60% of adults in the city support a new recycling program",
  "a company claims that 85% of its customers are satisfied with their product",
  "a school administrator states that 40% of students walk to school each day"
);
$i = rand(0, count($contexts)-1);
$topic = $contexts[$i];

$populations = array("all adults in the city", "all of the company's customers", "all students at the school");
$characteristics = array("support for the recycling program", "satisfaction with the product", "walking to school");
$claimed_values = array("0.60", "0.85", "0.40");
$claimed_percents = array("60%", "85%", "40%");
$sample_actions = array("survey a random sample of adults in the city", "survey a random sample of the company's customers", "survey a random sample of students at the school");

$population = $populations[$i];
$characteristic = $characteristics[$i];
$claimed_value = $claimed_values[$i];
$claimed_percent = $claimed_percents[$i];
$sample_action = $sample_actions[$i];

// Narrative variables for the model answer
$r_purpose = "whether the true population proportion differs from a specific claimed value, based on evidence from a sample";
$r_example = "we could $sample_action and test whether the true proportion of $characteristic is really $claimed_percent";
$r_null_statement = "H&#8320;: p = $claimed_value";
$r_null_meaning = "the null hypothesis assumes the claim is correct -- that the proportion of $population who have $characteristic is exactly $claimed_value. We collect sample data and ask whether there is strong enough evidence to reject that assumption";

$sample_narrative = "A hypothesis test for a single proportion asks <b>$r_purpose</b>. For example, suppose $topic. To check this, <b>$r_example</b>. The null hypothesis would be <b>$r_null_statement</b>, meaning <b>$r_null_meaning</b>.";

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
                <li><label><input type="checkbox"> Explain what question a hypothesis test for a single proportion is designed to answer.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Real-World Example</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Provide a specific example using the scenario, identifying the population and what is being measured.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Null Hypothesis</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State the null hypothesis for your example.</label></li>
                <li><label><input type="checkbox"> Explain in plain language what the null hypothesis assumes.</label></li>
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
                <li>Explain what question the test answers.
                    <span class="ideal-ans">Target: "A hypothesis test for a single proportion determines whether there is enough evidence from a sample to conclude that the true population proportion differs from a specific claimed value."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Real-World Example<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Provide a specific example identifying the population and measurement.
                    <span class="ideal-ans">Target: "We could '.$sample_action.' and test whether the true proportion of '.$characteristic.' is really '.$claimed_percent.' as claimed. The population is '.$population.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Null Hypothesis<br>(4 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>State the null hypothesis.
                    <span class="ideal-ans">Target: "H&#8320;: p = '.$claimed_value.'"</span></li>
                <li>Explain what the null hypothesis assumes.
                    <span class="ideal-ans">Target: "The null hypothesis assumes the claim is correct -- that the proportion of '.$population.' who have '.$characteristic.' is exactly '.$claimed_value.'. We then look at the sample data to see if there is strong enough evidence to reject that assumption."</span></li>
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
  <p>Suppose '.$topic.'. A researcher wants to investigate whether this claim is accurate.</p>
  <p><b>Essay Prompt:</b><br>
  Explain in your own words what a hypothesis test for a single proportion is trying to answer. Using the scenario above as your example, describe what the null hypothesis would claim and what it represents.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>What question a hypothesis test for a single proportion is designed to answer.</li>
    <li>A specific real-world example using the scenario above, identifying the population and what is being measured.</li>
    <li>The null hypothesis for your example and what it assumes in plain language.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
