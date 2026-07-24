// === NAME - DESCRIPTION: Single Mean - Concept and Hypotheses - Students explain what a hypothesis test for a single population mean is trying to answer, apply the idea to a randomized real-world scenario, and state the null and alternative hypotheses in context. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
  "a fitness magazine claims adults sleep an average of 7.0 hours per night",
  "a university reports first-year students study an average of 15 hours per week",
  "a health organization claims teenagers average 5.0 hours of daily screen time"
);
$i = rand(0, count($contexts)-1);
$topic = $contexts[$i];

$populations = array("all adults", "all first-year students at the university", "all teenagers");
$characteristics = array("hours of sleep per night", "hours studied per week", "hours of daily screen time");
$claimed_values = array("7.0", "15", "5.0");
$units = array("hours per night", "hours per week", "hours per day");
$sample_sizes = array("45", "60", "35");
$sample_actions = array(
  "randomly select 45 adults and record each person's sleep time",
  "randomly select 60 first-year students and record each student's weekly study time",
  "randomly select 35 teenagers and record each teen's daily screen time"
);

$population = $populations[$i];
$characteristic = $characteristics[$i];
$claimed_value = $claimed_values[$i];
$unit = $units[$i];
$sample_size = $sample_sizes[$i];
$sample_action = $sample_actions[$i];

// Narrative variables for the model answer
$r_purpose = "A hypothesis test for a single mean determines whether sample evidence is strong enough to conclude the true population mean &#956; differs from a specific claimed value";
$r_example = "For this scenario, we would $sample_action. The population is $population, the variable is $characteristic, and the sample mean x&#772; from n = $sample_size can be compared to the claim of $claimed_value $unit";
$r_h0 = "H&#8320;: &#956; = $claimed_value";
$r_h1 = "H&#8321;: &#956; &#8800; $claimed_value";
$r_h0_meaning = "This means we start by assuming the claimed mean is correct for $population, then use sample data to decide whether that assumption is reasonable";

$sample_narrative = "<b>$r_purpose</b>. In this case, suppose $topic. <b>$r_example</b>. The hypotheses are <b>$r_h0</b> and <b>$r_h1</b>. Under <b>$r_h0</b>, <b>$r_h0_meaning</b>.";

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
                <li><label><input type="checkbox"> Explain what question a hypothesis test for a single population mean is designed to answer.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Real-World Example</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Use the given scenario as an example, identifying the population, the variable, and the sample action.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Hypotheses</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State both H&#8320; and H&#8321; correctly using &#956; notation.</label></li>
                <li><label><input type="checkbox"> Explain in plain language what H&#8320; assumes.</label></li>
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
                <li>Explain what a single-mean hypothesis test determines.
                    <span class="ideal-ans">Target: "A hypothesis test for a single mean determines whether sample evidence is strong enough to conclude the true population mean &#956; differs from a specific claimed value."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Real-World Example<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Apply the concept to the randomized scenario with population, variable, and sample action.
                    <span class="ideal-ans">Target: "For this scenario, we would '.$sample_action.'. The population is '.$population.', the variable is '.$characteristic.', and we would compare x&#772; from n = '.$sample_size.' to the claim that &#956; = '.$claimed_value.' '.$unit.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Hypotheses<br>(4 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>State the null and alternative hypotheses using &#956;.
                    <span class="ideal-ans">Target: "H&#8320;: &#956; = '.$claimed_value.' and H&#8321;: &#956; &#8800; '.$claimed_value.'"</span></li>
                <li>Explain what H&#8320; means in plain language.
                    <span class="ideal-ans">Target: "H&#8320; assumes the claimed population mean is correct for '.$population.', and any difference in x&#772; from the claim is due to random sample variation."</span></li>
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
  <p>Suppose '.$topic.'. A researcher takes a random sample of size n = '.$sample_size.' to evaluate this claim.</p>
  <p><b>Essay Prompt:</b><br>
  Explain what a hypothesis test for a single population mean is trying to answer. Then use this scenario as your example to identify the population, the variable being measured, and what sample data would be collected. Finally, state the null and alternative hypotheses using &#956; notation and explain what H&#8320; assumes in plain language.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>The purpose of a single-mean hypothesis test.</li>
    <li>A real-world example from the given scenario including population, variable, and sample action.</li>
    <li>H&#8320; and H&#8321; for the claimed mean, and what H&#8320; means.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
