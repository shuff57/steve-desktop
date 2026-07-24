// === NAME - DESCRIPTION: Two-Sample Means - Interpreting Results - Students interpret a completed two-sample t-test by comparing p-value to alpha, making the correct decision about H&#8320;, and writing a practical conclusion about whether two groups differ. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$i = rand(0, 2);

$roles = array("a principal", "a marketing analyst", "a doctor");
$contexts = array(
  "two independent groups of students in honors and regular classes",
  "two independent groups of users who saw ad version A and ad version B",
  "two independent groups of patients treated with Drug A and Drug B"
);

$group1_labels = array("honors students", "ad version A users", "Drug A patients");
$group2_labels = array("regular students", "ad version B users", "Drug B patients");
$measures = array("test scores", "click-through rates", "blood pressure reduction");
$units = array("points", "percent", "mmHg");

$n1_values = array("52", "200", "45");
$n2_values = array("48", "180", "50");
$xbar1_values = array("82.3", "3.2", "12.1");
$xbar2_values = array("78.9", "3.5", "8.4");

$h0_values = array(
  "H&#8320;: &#956;&#8321; - &#956;&#8322; = 0",
  "H&#8320;: &#956;&#8321; = &#956;&#8322;",
  "H&#8320;: &#956;&#8321; - &#956;&#8322; = 0"
);
$ha_values = array(
  "H&#8337;: &#956;&#8321; - &#956;&#8322; &#8800; 0",
  "H&#8337;: &#956;&#8321; - &#956;&#8322; &#8800; 0",
  "H&#8337;: &#956;&#8321; - &#956;&#8322; &#8800; 0"
);

$p_values = array("0.04", "0.18", "0.003");
$alphas = array("0.05", "0.05", "0.01");
$rejects = array("yes", "no", "yes");

$role = $roles[$i];
$topic = $contexts[$i];
$group1_label = $group1_labels[$i];
$group2_label = $group2_labels[$i];
$measure = $measures[$i];
$unit = $units[$i];

$n1 = $n1_values[$i];
$n2 = $n2_values[$i];
$xbar1 = $xbar1_values[$i];
$xbar2 = $xbar2_values[$i];
$h0 = $h0_values[$i];
$ha = $ha_values[$i];
$pvalue = $p_values[$i];
$alpha = $alphas[$i];
$reject = $rejects[$i];

if ($reject == "yes") {
  $compare_word = "less";
  $decision_phrase = "we reject the null hypothesis";
  $sig_phrase = "is statistically significant";
} else {
  $compare_word = "greater";
  $decision_phrase = "we fail to reject the null hypothesis";
  $sig_phrase = "is not statistically significant";
}

$decisions = array(
  "Because p = 0.04 is less than &#945; = 0.05, we reject H&#8320;. The difference in mean test scores is statistically significant.",
  "Because p = 0.18 is greater than &#945; = 0.05, we fail to reject H&#8320;. The difference in mean click-through rates is not statistically significant.",
  "Because p = 0.003 is less than &#945; = 0.01, we reject H&#8320;. The difference in mean blood pressure reduction is statistically significant."
);

$context_meanings = array(
  "In context, the sample provides convincing evidence that average test scores differ between honors students and regular students.",
  "In context, the sample does not provide convincing evidence that average click-through rates differ between users who saw ad version A and ad version B.",
  "In context, the sample provides convincing evidence that average blood pressure reduction differs between Drug A patients and Drug B patients."
);

$conclusion_framings = array(
  "The principal should interpret this as evidence of a real difference between the two programs, not just random sample-to-sample variation.",
  "The marketing team should interpret this as insufficient evidence of a real ad-performance difference and avoid claiming one version is truly better from this study alone.",
  "The doctor should interpret this as evidence of a real treatment difference in mean blood pressure reduction between the two drugs."
);

$decision_target = $decisions[$i];
$context_meaning_target = $context_meanings[$i];
$conclusion_framing_target = $conclusion_framings[$i];

// Narrative variables for the model answer
$r_decision = $decision_target;
$r_context = $context_meaning_target;
$r_evidence = $conclusion_framing_target;

$sample_narrative = "For this two-sample t-test, p = $pvalue is $compare_word than &#945; = $alpha, so $decision_phrase and the result $sig_phrase. <b>$r_decision</b> <b>$r_context</b> <b>$r_evidence</b>";

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
            <td style="text-align:center;"><b>Statistical Decision</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Compare the p-value to &#945; and state whether the test result is statistically significant.</label></li>
                <li><label><input type="checkbox"> State whether to reject H&#8320; or fail to reject H&#8320;.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Conclusion in Context</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain what the statistical decision means in this two-group real-world setting.</label></li>
                <li><label><input type="checkbox"> Clearly reference both groups and the measured outcome.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Interpretation of Evidence</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Describe what the evidence does or does not support about a real difference between the groups.</label></li>
                <li><label><input type="checkbox"> State a reasonable practical takeaway for the researcher.</label></li>
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
            <td style="text-align:center;"><b>Statistical Decision<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Compare p-value to &#945;, determine significance, and state reject or fail-to-reject decision.
                    <span class="ideal-ans">Target: "'.$decision_target.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Conclusion in Context<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Translate the statistical decision into a real-world statement about the two groups and measured outcome.
                    <span class="ideal-ans">Target: "'.$context_meaning_target.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Interpretation of Evidence<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>State what the evidence implies about a real difference and give an appropriate researcher takeaway.
                    <span class="ideal-ans">Target: "'.$conclusion_framing_target.'"</span></li>
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
  <p>The hypotheses are already set as:<br>
  <b>'.$h0.'</b><br>
  <b>'.$ha.'</b></p>
  <p>The test output reports <b>p = '.$pvalue.'</b> with significance level <b>&#945; = '.$alpha.'</b>.</p>
  <p><b>Essay Prompt:</b><br>
  Interpret this two-sample t-test result. Compare p to &#945;, make the correct decision about H&#8320;, explain what that decision means in this two-group context, and describe what the evidence says about whether there is a real difference between the groups.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>How p compares to &#945; and whether the result is statistically significant.</li>
    <li>Whether to reject H&#8320; or fail to reject H&#8320;.</li>
    <li>A practical conclusion about whether the population means are different.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
