// === NAME - DESCRIPTION: Stratified vs. Simple Random Sampling - Students evaluate whether simple random sampling or stratified random sampling is more appropriate for a survey with distinct subgroups, and justify their choice using the group composition. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$i = rand(0, 2);

// Three scenarios: university/class year, company/department, hospital/ward
$contexts = array(
  "a university wants to survey students about campus facilities",
  "a company wants to survey employees about workplace satisfaction",
  "a hospital wants to survey patients about the quality of care they received"
);
$context = $contexts[$i];

// Organization names
$orgs = array("The university", "The company", "The hospital");
$org = $orgs[$i];

// What the population is called
$pop_labels = array("students", "employees", "patients");
$pop_label = $pop_labels[$i];

// Group category label (what divides the population)
$group_types = array("class year", "department", "ward");
$group_type = $group_types[$i];

// Group names (4 groups per scenario)
$g1_names = array("freshmen", "Marketing department employees", "Emergency ward patients");
$g2_names = array("sophomores", "Engineering department employees", "Surgery ward patients");
$g3_names = array("juniors", "Sales department employees", "Maternity ward patients");
$g4_names = array("seniors", "HR department employees", "General ward patients");
$g1_name = $g1_names[$i];
$g2_name = $g2_names[$i];
$g3_name = $g3_names[$i];
$g4_name = $g4_names[$i];

// Short group labels for the proportional calculation
$g1_shorts = array("freshmen", "Marketing", "Emergency");
$g2_shorts = array("sophomores", "Engineering", "Surgery");
$g3_shorts = array("juniors", "Sales", "Maternity");
$g4_shorts = array("seniors", "HR", "General");
$g1_short = $g1_shorts[$i];
$g2_short = $g2_shorts[$i];
$g3_short = $g3_shorts[$i];
$g4_short = $g4_shorts[$i];

// Randomized group sizes (multiplied by 10 for realistic counts)
$g1 = rand(200, 300) * 10;
$g2 = rand(180, 280) * 10;
$g3 = rand(150, 250) * 10;
$g4 = rand(150, 250) * 10;
$total = $g1 + $g2 + $g3 + $g4;

// Sample size
$sample = rand(20, 40) * 10;

// Proportional sample counts (rounded)
$s1 = round($sample * $g1 / $total);
$s2 = round($sample * $g2 / $total);
$s3 = round($sample * $g3 / $total);
$s4 = round($sample * $g4 / $total);

// Percentages for each group
$p1 = round(100 * $g1 / $total, 1);
$p2 = round(100 * $g2 / $total, 1);
$p3 = round(100 * $g3 / $total, 1);
$p4 = round(100 * $g4 / $total, 1);

// Survey purpose
$purposes = array(
  "campus facilities",
  "workplace satisfaction",
  "quality of care"
);
$purpose = $purposes[$i];

// Narrative variables for model answer
$r_recommendation = "Stratified random sampling is the more appropriate method for this situation";

$r_justification = "the population is made up of distinct subgroups by $group_type ($g1_short, $g2_short, $g3_short, and $g4_short), and these groups are not all the same size. $g1_short make up about $p1% of the population ($g1 out of $total), while $g4_short make up about $p4% ($g4 out of $total). Stratified sampling divides the population into these subgroups (strata) and then randomly selects from each group in proportion to its size. This guarantees that each $group_type is represented in the sample at a rate that matches the overall population";

$r_srs_limitation = "with simple random sampling, every individual has an equal chance of being selected, but there is no guarantee that each $group_type will be represented proportionally. In a population where group sizes are unequal, SRS could easily over-represent one group and under-represent another just by chance. For a survey about $purpose, it matters that all subgroups have a voice, so stratified sampling is the better choice";

$sample_narrative = "<b>$r_recommendation</b>. This is because $r_justification. For example, a proportional stratified sample of $sample would include roughly $s1 $g1_short, $s2 $g2_short, $s3 $g3_short, and $s4 $g4_short. By contrast, <b>$r_srs_limitation</b>.";

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
            <td style="text-align:center;"><b>Sampling Recommendation</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State which sampling method is more appropriate for this situation.</label></li>
                <li><label><input type="checkbox"> Briefly describe how that sampling method works in this context.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Group Composition</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Reference the specific subgroups and their sizes from the scenario.</label></li>
                <li><label><input type="checkbox"> Explain how proportional representation would be achieved in the sample.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Why Not Simple Random Sampling</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain what could go wrong if simple random sampling were used instead.</label></li>
                <li><label><input type="checkbox"> Connect this risk to the specific group composition in the scenario.</label></li>
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
            <td style="text-align:center;"><b>Sampling Recommendation<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>State which sampling method is more appropriate.
                    <span class="ideal-ans">Target: "Stratified random sampling is the more appropriate method for this situation."</span></li>
                <li>Describe how that method works in this context.
                    <span class="ideal-ans">Target: "Stratified sampling divides the population into subgroups (strata) by '.$group_type.' and then randomly selects from each group in proportion to its size."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Group Composition<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Reference the specific subgroups and their sizes.
                    <span class="ideal-ans">Target: "The population has '.$g1_name.' ('.$g1.'), '.$g2_name.' ('.$g2.'), '.$g3_name.' ('.$g3.'), and '.$g4_name.' ('.$g4.'), totaling '.$total.' '.$pop_label.'."</span></li>
                <li>Explain how proportional representation is achieved.
                    <span class="ideal-ans">Target: "A proportional stratified sample of '.$sample.' would include roughly '.$s1.' '.$g1_short.', '.$s2.' '.$g2_short.', '.$s3.' '.$g3_short.', and '.$s4.' '.$g4_short.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Why Not Simple Random Sampling<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain what could go wrong with SRS.
                    <span class="ideal-ans">Target: "With simple random sampling, there is no guarantee that each '.$group_type.' will be represented proportionally. SRS could over-represent one group and under-represent another just by chance."</span></li>
                <li>Connect this risk to the scenario.
                    <span class="ideal-ans">Target: "Because the group sizes are unequal and the survey is about '.$purpose.', it is important that every subgroup has a voice. Stratified sampling ensures this; SRS does not."</span></li>
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
  <p>Suppose '.$context.'. The population consists of '.$g1.' '.$g1_name.', '.$g2.' '.$g2_name.', '.$g3.' '.$g3_name.', and '.$g4.' '.$g4_name.', for a total of '.$total.' '.$pop_label.'. '.$org.' plans to survey '.$sample.' '.$pop_label.' about '.$purpose.' and is deciding between simple random sampling and stratified random sampling.</p>
  <p><b>Essay Prompt:</b><br>
  Which sampling method would be more appropriate for this situation? Write a conclusion statement explaining your recommendation and justify your answer with specific reference to the group composition.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>Which sampling method you recommend and how it works in this context.</li>
    <li>The specific subgroups and how proportional representation would be achieved.</li>
    <li>Why simple random sampling might not work as well here, given the group sizes.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton