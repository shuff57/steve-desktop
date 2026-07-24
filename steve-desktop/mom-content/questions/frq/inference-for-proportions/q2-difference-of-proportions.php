// === NAME - DESCRIPTION: Difference of Proportions - Students explain the main difference between testing a single proportion and comparing two proportions, and give a real-world example that requires a two-proportion comparison. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
  "a hospital wants to know whether a new drug leads to a higher recovery rate than the standard treatment",
  "a university wants to know whether students in online sections pass at a different rate than students in face-to-face sections",
  "a marketing team wants to know whether email campaign A gets a higher click-through rate than email campaign B"
);
$i = rand(0, count($contexts)-1);
$topic = $contexts[$i];

$group1s = array("patients receiving the new drug", "students in online sections", "recipients of campaign A");
$group2s = array("patients receiving the standard treatment", "students in face-to-face sections", "recipients of campaign B");
$outcomes = array("recovery rate", "pass rate", "click-through rate");

$group1 = $group1s[$i];
$group2 = $group2s[$i];
$outcome = $outcomes[$i];

// Narrative variables for the model answer
$r_single = "a single proportion test checks whether one group's proportion matches a specific known or claimed value";
$r_two = "a two-proportion test compares the proportions of two separate groups to each other to see if they differ";
$r_example = "comparing the $outcome between $group1 and $group2 requires a two-proportion test because there are two independent groups, and we want to know if there is a difference between them -- not whether either one matches some fixed number";

$sample_narrative = "The main difference is that <b>$r_single</b>, while <b>$r_two</b>. For example, suppose $topic. This would involve <b>$r_example</b>. There is no single claimed value here. The whole point is to compare two groups head to head.";

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
            <td style="text-align:center;"><b>Key Difference</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Describe what a single proportion test examines.</label></li>
                <li><label><input type="checkbox"> Describe what a two-proportion test examines.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Two-Proportion Example</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Provide a real-world example that involves two distinct groups.</label></li>
                <li><label><input type="checkbox"> Explain why your example requires comparing two groups rather than testing just one.</label></li>
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
            <td style="text-align:center;"><b>Key Difference<br>(5 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Describe what a single proportion test examines.
                    <span class="ideal-ans">Target: "A single proportion test checks whether one group\'s proportion matches a specific known or claimed value (e.g., is the pass rate really 70%?)."</span></li>
                <li>Describe what a two-proportion test examines.
                    <span class="ideal-ans">Target: "A two-proportion test compares the proportions from two separate groups to each other to see if they differ."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Two-Proportion Example<br>(5 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Provide a real-world example that involves two distinct groups.
                    <span class="ideal-ans">Target: "Comparing the '.$outcome.' between '.$group1.' and '.$group2.' -- two independent groups whose proportions we want to compare."</span></li>
                <li>Explain why the example requires comparing two groups rather than testing just one.
                    <span class="ideal-ans">Target: "There is no single claimed value to test against. The research question is about whether the two groups differ from each other, which is fundamentally a two-group comparison."</span></li>
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
  <p>Suppose '.$topic.'. Think about how you would set up a statistical test to investigate this.</p>
  <p><b>Essay Prompt:</b><br>
  What is the main difference between testing a single proportion and comparing two proportions? Give an example of a question that would require comparing two proportions instead of just looking at one group.</p>
  <p>In your response, be sure to address:</p>
  <ul>
    <li>What a single proportion test examines versus what a two-proportion test examines.</li>
    <li>A real-world example involving two groups, and why that example requires a two-proportion comparison.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton