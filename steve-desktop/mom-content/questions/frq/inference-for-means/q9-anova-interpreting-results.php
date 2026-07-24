// === NAME - DESCRIPTION: ANOVA - Interpreting Results - Students explain why ANOVA is appropriate for comparing three group means, interpret the F-test output by comparing p to alpha, and write a contextual conclusion about whether at least one group mean differs. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats")

$anstypes = array("essay")
$displayformat[0]='editornopaste'

/* ---------- 1. Dynamic Context Generation ---------- */
$i = rand(0, 2)

$roles = array("a fitness researcher", "a school administrator", "a plant biologist")
$contexts = array(
  "three independent groups of participants following different diet programs",
  "three independent groups of students taught with different teaching methods",
  "three independent groups of seedlings grown under different light conditions"
)

$group1_labels = array("keto diet", "lecture-based instruction", "full sun")
$group2_labels = array("Mediterranean diet", "flipped classroom", "partial shade")
$group3_labels = array("low-fat diet", "project-based learning", "full shade")

$measures = array("weight loss", "standardized test scores", "growth height")
$units = array("lbs", "points", "cm")

$n1_values = array("28", "35", "20")
$n2_values = array("32", "33", "22")
$n3_values = array("30", "31", "19")

$xbar1_values = array("14.2", "72.4", "18.6")
$xbar2_values = array("11.8", "75.1", "14.3")
$xbar3_values = array("12.1", "74.8", "10.1")

$f_values = array("4.87", "1.24", "12.35")
$p_values = array("0.01", "0.29", "0.001")
$alphas = array("0.05", "0.05", "0.01")
$rejects = array("yes", "no", "yes")

$role = $roles[$i]
$topic = $contexts[$i]
$group1_label = $group1_labels[$i]
$group2_label = $group2_labels[$i]
$group3_label = $group3_labels[$i]
$measure = $measures[$i]
$unit = $units[$i]

$n1 = $n1_values[$i]
$n2 = $n2_values[$i]
$n3 = $n3_values[$i]
$xbar1 = $xbar1_values[$i]
$xbar2 = $xbar2_values[$i]
$xbar3 = $xbar3_values[$i]
$fstat = $f_values[$i]
$pvalue = $p_values[$i]
$alpha = $alphas[$i]
$reject = $rejects[$i]

if ($reject == "yes") {
  $compare_word = "less"
  $decision_phrase = "we reject the null hypothesis"
  $sig_phrase = "is statistically significant"
  $evidence_phrase = "is convincing evidence"
} else {
  $compare_word = "greater"
  $decision_phrase = "we fail to reject the null hypothesis"
  $sig_phrase = "is not statistically significant"
  $evidence_phrase = "is not convincing evidence"
}

$purpose_targets = array(
  "ANOVA lets us compare all three diet groups at once. Running separate t-tests for each pair would inflate the chance of a false positive, so ANOVA keeps the overall Type I error rate under control.",
  "ANOVA lets us compare all three teaching methods at once. Running separate t-tests for each pair would inflate the chance of a false positive, so ANOVA keeps the overall Type I error rate under control.",
  "ANOVA lets us compare all three light conditions at once. Running separate t-tests for each pair would inflate the chance of a false positive, so ANOVA keeps the overall Type I error rate under control."
)

$decision_targets = array(
  "Because p = 0.01 is less than &#945; = 0.05, we reject H&#8320;. The result is statistically significant.",
  "Because p = 0.29 is greater than &#945; = 0.05, we fail to reject H&#8320;. The result is not statistically significant.",
  "Because p = 0.001 is less than &#945; = 0.01, we reject H&#8320;. The result is statistically significant."
)

$context_meanings = array(
  "There is convincing evidence that average weight loss differs across at least one of the three diet programs.",
  "There is not convincing evidence that average test scores differ across the three teaching methods.",
  "There is convincing evidence that average growth height differs across at least one of the three light conditions."
)

$conclusion_framings = array(
  "The fitness researcher should interpret this as evidence that not all three diets produce the same average weight loss, and follow-up comparisons may help identify which diets differ.",
  "The administrator should interpret this as insufficient evidence that the teaching methods produce different average scores, and should not conclude one method outperforms the others based on this study.",
  "The biologist should interpret this as evidence that light exposure affects average seedling growth, and follow-up comparisons may help identify which conditions produce different results."
)

$purpose_target = $purpose_targets[$i]
$decision_target = $decision_targets[$i]
$context_meaning_target = $context_meanings[$i]
$conclusion_framing_target = $conclusion_framings[$i]

// Narrative variables for the model answer
$r_purpose = $purpose_target
$r_decision = $decision_target
$r_context = $context_meaning_target
$r_evidence = $conclusion_framing_target

$sample_narrative = "<b>$r_purpose</b> The hypotheses are H&#8320;: &#956;&#8321; = &#956;&#8322; = &#956;&#8323; (all group means are equal) and H&#8337;: at least one group mean is different. <b>$r_decision</b> <b>$r_context</b> <b>$r_evidence</b>"

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
</script>'

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
            <td style="text-align:center;"><b>Purpose of ANOVA</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain why ANOVA is used instead of running multiple two-sample t-tests.</label></li>
                <li><label><input type="checkbox"> Identify the null and alternative hypotheses for this test.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Statistical Decision</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Compare the p-value to &#945; and state whether the result is statistically significant.</label></li>
                <li><label><input type="checkbox"> State whether to reject H&#8320; or fail to reject H&#8320;.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Conclusion in Context</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain what the decision means for the groups being compared.</label></li>
                <li><label><input type="checkbox"> Describe a practical takeaway for the researcher.</label></li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </details>
</div>'

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
            <td style="text-align:center;"><b>Purpose of ANOVA<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain why ANOVA is used instead of multiple t-tests.
                    <span class="ideal-ans">Target: "'.$purpose_target.'"</span></li>
                <li>State the null and alternative hypotheses.
                    <span class="ideal-ans">Target: "H&#8320;: &#956;&#8321; = &#956;&#8322; = &#956;&#8323; (all group means are equal). H&#8337;: At least one group mean is different."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Statistical Decision<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Compare p-value to &#945;, determine significance, and state reject or fail-to-reject decision.
                    <span class="ideal-ans">Target: "'.$decision_target.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Conclusion in Context<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Translate the decision into a real-world statement about the groups and measured outcome.
                    <span class="ideal-ans">Target: "'.$context_meaning_target.'"</span></li>
                <li>State an appropriate practical takeaway for the researcher.
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
</div>'

/* ---------- 5. Question Text ---------- */
$questiontext = '
<div style="font-family:Arial; font-size:medium; line-height:1.6;">
  <p>Suppose '.$role.' is studying '.$topic.'. The three groups are:</p>
  <ul>
    <li><b>Group 1:</b> '.$group1_label.' (n&#8321; = '.$n1.', x&#772;<sub>1</sub> = '.$xbar1.' '.$unit.')</li>
    <li><b>Group 2:</b> '.$group2_label.' (n&#8322; = '.$n2.', x&#772;<sub>2</sub> = '.$xbar2.' '.$unit.')</li>
    <li><b>Group 3:</b> '.$group3_label.' (n&#8323; = '.$n3.', x&#772;<sub>3</sub> = '.$xbar3.' '.$unit.')</li>
  </ul>
  <p>A one-way ANOVA was performed to test whether average <b>'.$measure.'</b> differs across the three groups. The test output reports <b>F = '.$fstat.'</b> with <b>p = '.$pvalue.'</b> at significance level <b>&#945; = '.$alpha.'</b>.</p>
  <p><b>Essay Prompt:</b><br>
  Interpret this ANOVA result. Explain why ANOVA is the right approach here (instead of multiple t-tests), state the hypotheses, compare p to &#945;, make the correct decision about H&#8320;, and explain what the result means in this context.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>Why ANOVA is used instead of multiple two-sample t-tests.</li>
    <li>The null and alternative hypotheses.</li>
    <li>How p compares to &#945; and whether to reject or fail to reject H&#8320;.</li>
    <li>A practical conclusion about whether the group means differ.</li>
  </ul>
  '.$rubricbutton.'
</div>'

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
