// === NAME - DESCRIPTION: Two-Sample Means - Concept and Hypotheses - Students explain when a two-sample t-test for independent means is appropriate, apply the design to a randomized context, and state correct hypotheses in symbols and words. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
  "A school district compares average math scores between students in two different instructional programs",
  "A gym compares average weight loss between clients in two different training formats",
  "A hospital compares average recovery time between patients on two treatment plans"
);
$i = rand(0, count($contexts)-1);
$topic = $contexts[$i];

$group1_labels = array("new online curriculum", "group fitness program", "new medication");
$group2_labels = array("traditional textbook program", "personal training", "standard treatment");
$measurements = array("math scores", "weight loss", "recovery time");
$units = array("points", "pounds", "days");

$n1_mins = array(40, 34, 50);
$n1_maxs = array(52, 44, 62);
$n2_mins = array(45, 38, 55);
$n2_maxs = array(58, 48, 68);

$population1_descriptions = array(
  "all students in the district who use the new online curriculum",
  "all gym clients who follow the group fitness program",
  "all hospital patients who receive the new medication"
);
$population2_descriptions = array(
  "all students in the district who use the traditional textbook program",
  "all gym clients who use personal training",
  "all hospital patients who receive the standard treatment"
);
$sample_actions = array(
  "randomly select students from each program and record each student\'s math score",
  "randomly select clients from each format and record each client\'s weight loss after eight weeks",
  "randomly select patients from each treatment group and record each patient\'s recovery time"
);

$group1_label = $group1_labels[$i];
$group2_label = $group2_labels[$i];
$measurement = $measurements[$i];
$unit = $units[$i];
$n1 = rand($n1_mins[$i], $n1_maxs[$i]);
$n2 = rand($n2_mins[$i], $n2_maxs[$i]);
$population1_description = $population1_descriptions[$i];
$population2_description = $population2_descriptions[$i];
$sample_action = $sample_actions[$i];

// Narrative variables for the model answer
$r_independent_design = "A two-sample t-test for independent means is appropriate when we compare two separate groups, with no natural pairing, and each person belongs to only one group";
$r_real_world_example = "In this scenario, the two groups are $group1_label and $group2_label, the variable is $measurement measured in $unit, and we would $sample_action with sample sizes n&#8321; = $n1 and n&#8322; = $n2";
$r_hypotheses = "A correct setup is H&#8320;: &#956;&#8321; - &#956;&#8322; = 0 (equivalently &#956;&#8321; = &#956;&#8322;) and H&#8337;: &#956;&#8321; - &#956;&#8322; &#8800; 0";
$r_h0_meaning = "Under H&#8320;, we assume there is no true difference between the two population means, so any observed sample difference could be due to random sampling variability";

$sample_narrative = "<b>$r_independent_design</b>. Here, $topic. <b>$r_real_world_example</b>. <b>$r_hypotheses</b>. In words, <b>$r_h0_meaning</b>.";

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
            <td style="text-align:center;"><b>Independent Samples Design</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain what feature of the design makes these two samples independent.</label></li>
                <li><label><input type="checkbox"> Clarify that individuals belong to only one group and are not naturally paired.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Real-World Example</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Use the randomized scenario to identify both groups, what is measured, and how the samples are collected.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Hypotheses</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State H&#8320; and H&#8337; correctly for a two-sample mean comparison using &#956;&#8321; and &#956;&#8322;.</label></li>
                <li><label><input type="checkbox"> Explain in plain language what H&#8320; means in this context.</label></li>
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
            <td style="text-align:center;"><b>Independent Samples Design<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain what makes the design independent.
                    <span class="ideal-ans">Target: "The two samples come from separate groups with no natural pairing, and each individual appears in only one group."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Real-World Example<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Apply the concept to the randomized context.
                    <span class="ideal-ans">Target: "Group 1 is '.$group1_label.' and Group 2 is '.$group2_label.'. The measured variable is '.$measurement.' in '.$unit.'. We '.$sample_action.' with n&#8321; = '.$n1.' and n&#8322; = '.$n2.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Hypotheses<br>(4 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>State the null and alternative hypotheses.
                    <span class="ideal-ans">Target: "H&#8320;: &#956;&#8321; - &#956;&#8322; = 0 (or &#956;&#8321; = &#956;&#8322;) and H&#8337;: &#956;&#8321; - &#956;&#8322; &#8800; 0."</span></li>
                <li>Interpret H&#8320; in context.
                    <span class="ideal-ans">Target: "H&#8320; means there is no true difference between the two population means, so any observed difference in sample means could be random sampling variation."</span></li>
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
  <p>'.$topic.'. In this run of the problem, Group 1 is <b>'.$group1_label.'</b> (n&#8321; = '.$n1.') and Group 2 is <b>'.$group2_label.'</b> (n&#8322; = '.$n2.'). The outcome variable is <b>'.$measurement.'</b> measured in <b>'.$unit.'</b>.</p>
  <p>The two populations are '.$population1_description.' and '.$population2_description.'. A researcher plans to '.$sample_action.'.</p>
  <p><b>Essay Prompt:</b><br>
  Explain when a two-sample t-test for independent means is appropriate. In your response, describe the key feature of independent samples, apply that idea to this scenario, and state the hypotheses for comparing the two population means.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>Why this is an independent-samples design rather than a paired design.</li>
    <li>The two groups, what is measured, and the sample action in this specific context.</li>
    <li>H&#8320; and H&#8337; using &#956;&#8321; and &#956;&#8322;, plus what H&#8320; means.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
