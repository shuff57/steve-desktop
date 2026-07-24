// === NAME - DESCRIPTION: Two-Sample Means - Interpreting Confidence Interval - Students interpret a confidence interval for the difference between two population means in context, explain what the confidence level means, and assess whether the interval suggests a real difference between groups. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$i = rand(0, 2);

$roles = array("a fitness researcher", "an education specialist", "a plant biologist");
$contexts = array(
  "two independent groups following different exercise programs",
  "two independent groups of students using different study methods",
  "two independent groups of plants grown under different light conditions"
);

$group1_labels = array("Program A participants", "method A students", "full-spectrum light plants");
$group2_labels = array("Program B participants", "method B students", "red-light-only plants");
$measures = array("weight loss", "exam scores", "plant height");
$units = array("pounds", "points", "centimeters");
$populations = array("all adults following these programs", "all students using these methods", "all plants grown under these conditions");

$n1_values = array("35", "60", "40");
$n2_values = array("38", "55", "42");
$xbar1_values = array("8.4", "76.2", "24.8");
$xbar2_values = array("5.1", "72.8", "21.3");

$confidence_levels = array("95", "90", "99");
$alphas = array("0.05", "0.10", "0.01");
$lower_bounds = array("1.2", "0.6", "1.0");
$upper_bounds = array("5.4", "6.2", "6.0");

$role = $roles[$i];
$topic = $contexts[$i];
$group1_label = $group1_labels[$i];
$group2_label = $group2_labels[$i];
$measure = $measures[$i];
$unit = $units[$i];
$population = $populations[$i];

$n1 = $n1_values[$i];
$n2 = $n2_values[$i];
$xbar1 = $xbar1_values[$i];
$xbar2 = $xbar2_values[$i];
$conf_level = $confidence_levels[$i];
$alpha = $alphas[$i];
$lower = $lower_bounds[$i];
$upper = $upper_bounds[$i];

$interpretations = array(
  "We are 95% confident that the true difference in mean weight loss (Program A minus Program B) for all adults following these programs is between 1.2 and 5.4 pounds.",
  "We are 90% confident that the true difference in mean exam scores (method A minus method B) for all students using these methods is between 0.6 and 6.2 points.",
  "We are 99% confident that the true difference in mean plant height (full-spectrum minus red-light-only) for all plants grown under these conditions is between 1.0 and 6.0 centimeters."
);

$confidence_meanings = array(
  "The 95% confidence level describes the long-run performance of this method. If we repeatedly took independent random samples of 35 and 38 participants and built confidence intervals the same way, about 95% of those intervals would capture the true difference in population means.",
  "The 90% confidence level describes the method, not the single fixed difference. If we repeatedly sampled 60 and 55 students and built intervals this way, about 90% of those intervals would contain the true difference in population means.",
  "The 99% confidence level means this interval-building process is very reliable in repeated sampling. Over many independent random samples of 40 and 42 plants, about 99% of intervals made this way would include the true difference in population means."
);

$zero_assessments = array(
  "Zero is not inside the interval (1.2, 5.4), which means a difference of zero is not plausible. This provides evidence that Program A produces greater mean weight loss than Program B.",
  "Zero is not inside the interval (0.6, 6.2), which means a difference of zero is not plausible. This provides evidence that method A produces higher mean exam scores than method B.",
  "Zero is not inside the interval (1.0, 6.0), which means a difference of zero is not plausible. This provides evidence that full-spectrum light produces greater mean plant height than red-light-only conditions."
);

$ci_interpretation_target = $interpretations[$i];
$confidence_meaning_target = $confidence_meanings[$i];
$zero_assessment_target = $zero_assessments[$i];

$sample_narrative = "For this two-sample confidence interval, the $conf_level% CI for &#956;&#8321; &#8722; &#956;&#8322; is ($lower, $upper) $unit. <b>$ci_interpretation_target</b> <b>$confidence_meaning_target</b> <b>$zero_assessment_target</b>";

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
            <td style="text-align:center;"><b>CI Interpretation</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Interpret the confidence interval in context, identifying the true difference in population means, both groups, and the interval bounds with units.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Confidence Level Meaning</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain what the confidence level means in terms of the long-run behavior of the interval method.</label></li>
                <li><label><input type="checkbox"> Avoid treating the true difference as random in this specific interval.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Assessing Whether Groups Differ</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Use whether zero falls inside or outside the interval to decide if there is evidence of a real difference between the groups.</label></li>
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
            <td style="text-align:center;"><b>CI Interpretation<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Interpret the confidence interval for the difference in means in context.
                    <span class="ideal-ans">Target: "'.$ci_interpretation_target.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Confidence Level Meaning<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain what "'.$conf_level.'% confident" means using repeated sampling language.
                    <span class="ideal-ans">Target: "'.$confidence_meaning_target.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Assessing Whether Groups Differ<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Use whether zero is inside or outside the interval to evaluate if a real difference exists.
                    <span class="ideal-ans">Target: "'.$zero_assessment_target.'"</span></li>
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
  <p>'.$role.' is studying '.$topic.'. Group 1 is <b>'.$group1_label.'</b> and Group 2 is <b>'.$group2_label.'</b>.</p>
  <p>The measured outcome is <b>'.$measure.'</b> in <b>'.$unit.'</b>. The sample summaries are:<br>
  <b>n&#8321; = '.$n1.'</b>, <b>x&#772;<sub>1</sub> = '.$xbar1.'</b>; <b>n&#8322; = '.$n2.'</b>, <b>x&#772;<sub>2</sub> = '.$xbar2.'</b>.</p>
  <p>A <b>'.$conf_level.'% confidence interval</b> for the true difference in population means <b>&#956;&#8321; &#8722; &#956;&#8322;</b> is <b>('.$lower.', '.$upper.')</b> '.$unit.'.</p>
  <p><b>Essay Prompt:</b><br>
  Interpret this confidence interval in context. Then explain what "'.$conf_level.'% confident" means, and use the interval to evaluate whether there is evidence of a real difference between the two groups.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>A correct in-context interpretation of the interval for &#956;&#8321; &#8722; &#956;&#8322;, including both groups and units.</li>
    <li>The meaning of the confidence level as a property of the method in repeated sampling.</li>
    <li>Whether zero is inside or outside the interval, and what that tells us about whether the groups truly differ.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton