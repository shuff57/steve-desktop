// === NAME - DESCRIPTION: Difference of Proportions - Interpreting Results - Students write a conclusion statement for a two-proportion hypothesis test, stating whether to reject or fail to reject the null hypothesis and explaining what the result means about the difference between two groups. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$i = rand(0, 2);

// Scenario descriptions
$scenarios = array(
  "A researcher compares the graduation rates between two high schools",
  "A hospital administrator compares the patient satisfaction rates between two clinics",
  "A school district compares the pass rates on a state math exam between two middle schools"
);
$scenario = $scenarios[$i];

// Group labels
$group1s = array("School A", "Clinic A", "Riverside Middle School");
$group2s = array("School B", "Clinic B", "Lakewood Middle School");
$group1 = $group1s[$i];
$group2 = $group2s[$i];

// What is being measured
$measures = array("graduation rate", "patient satisfaction rate", "pass rate on the state math exam");
$measure = $measures[$i];

// Proportions for each group
$phat1s = array("88%", "76%", "71%");
$phat2s = array("84%", "81%", "62%");
$phat1 = $phat1s[$i];
$phat2 = $phat2s[$i];

// Sample sizes
$n1s = array(250, 320, 185);
$n2s = array(280, 290, 210);
$n1 = $n1s[$i];
$n2 = $n2s[$i];

// P-values and significance level
$pvalues = array("0.12", "0.18", "0.03");
$pval = $pvalues[$i];

$alpha_val = "0.05";

// Whether result is significant: contexts 0 and 1 = no, context 2 = yes
$is_sigs = array("no", "no", "yes");
$sig = $is_sigs[$i];

// Build decision and conclusion narrative pieces
if ($sig == "yes") {
  $compare_word = "less";
  $reject_statement = "we reject the null hypothesis";
  $sig_phrase = "is statistically significant";
} else {
  $compare_word = "greater";
  $reject_statement = "we fail to reject the null hypothesis";
  $sig_phrase = "is not statistically significant";
}

// Null hypothesis statement (same structure for all)
$r_null = "The null hypothesis states that there is no difference in the $measure between $group1 and $group2.";

// Decision statement
$r_decisions = array(
  "Since the p-value of 0.12 is greater than &#945; = 0.05, the result is not statistically significant. We fail to reject the null hypothesis.",
  "Since the p-value of 0.18 is greater than &#945; = 0.05, the result is not statistically significant. We fail to reject the null hypothesis.",
  "Since the p-value of 0.03 is less than &#945; = 0.05, the result is statistically significant. We reject the null hypothesis."
);
$r_decision = $r_decisions[$i];

// Context-specific conclusion
$r_conclusions = array(
  "There is not enough evidence to conclude that the graduation rates at the two high schools are different. Although School A's rate of 88% is higher than School B's rate of 84%, the difference is not large enough relative to the sample sizes to rule out random variation.",
  "There is not enough evidence to conclude that the patient satisfaction rates at the two clinics are different. Although Clinic B's rate of 81% is higher than Clinic A's rate of 76%, the difference could plausibly be due to chance.",
  "There is sufficient evidence to conclude that the pass rates on the state math exam are different between the two schools. Riverside Middle School's rate of 71% is meaningfully higher than Lakewood Middle School's rate of 62%, and this difference is unlikely to be explained by random variation alone."
);
$r_conclusion = $r_conclusions[$i];

// What failing to reject / rejecting does NOT mean
$r_caveats = array(
  "Failing to reject the null hypothesis does not prove the two schools have the same graduation rate. It simply means this study did not find strong enough evidence to declare a difference at the 0.05 significance level.",
  "Failing to reject the null hypothesis does not prove the two clinics have the same satisfaction rate. It simply means this study did not find strong enough evidence to declare a difference at the 0.05 significance level.",
  "Rejecting the null hypothesis does not prove the schools are fundamentally different or explain why the difference exists. It means the observed gap is unlikely to be due to chance alone, but other factors could still be involved."
);
$r_caveat = $r_caveats[$i];

// Build model narrative
$sample_narrative = "<b>$r_null</b> Comparing the p-value to our significance level: <b>$r_decision</b> In context, this means: <b>$r_conclusion</b> It is important to keep in mind that <b>$r_caveat</b>";

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
<script>document.addEventListener("DOMContentLoaded",function(){var d=document.querySelectorAll(".rubric-container details");d.forEach(function(det){var c=det.querySelector(".rubric-content");det.addEventListener("toggle",function(){if(det.open){c.style.maxHeight=c.scrollHeight+"px";c.style.opacity="1"}else{c.style.maxHeight=c.scrollHeight+"px";det.offsetHeight;c.style.maxHeight="0";c.style.opacity="0"}});c.addEventListener("transitionend",function(){if(!det.open)c.style.maxHeight=null})})});</script>';

/* ---------- 3. Student Rubric (Neutral Checklist) ---------- */
$rubricbutton = $css_block . '
<div class="rubric-container">
  <details>
    <summary>
      <span class="arrow-closed">&#9656;</span><span class="arrow-open">&#9662;</span>
      Click to View Grading Checklist
    </summary>
    <div class="rubric-content">
      <p style="margin:0 0 0.5em 0;"><b>Grading Criteria</b> -- ensure your conclusion covers these points:</p>
      <table class="rubric-table">
        <tbody>
          <tr>
            <th class="col-header">Category</th>
            <th class="col-check">Requirement</th>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Hypothesis Decision</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Compare the p-value to the significance level and state whether the result is statistically significant.</label></li>
                <li><label><input type="checkbox"> State the decision about the null hypothesis (reject or fail to reject).</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Conclusion in Context</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain what the decision means about the two groups being compared, using the specific context of the study.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Appropriate Caution</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Address what the result does not prove or what limitations should be kept in mind.</label></li>
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
            <td style="text-align:center;"><b>Hypothesis Decision<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Compare the p-value to the significance level and state whether the result is statistically significant.
                    <span class="ideal-ans">Target: "The p-value of '.$pval.' is '.$compare_word.' than &#945; = '.$alpha_val.', so the result '.$sig_phrase.'."</span></li>
                <li>State the decision about the null hypothesis.
                    <span class="ideal-ans">Target: "We '.$reject_statement.'. The null hypothesis states that there is no difference in the '.$measure.' between '.$group1.' and '.$group2.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Conclusion in Context<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain what the decision means about the two groups, using the context of the study.
                    <span class="ideal-ans">Target: "'.$r_conclusion.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Appropriate Caution<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Address what the result does not prove or what limitations should be kept in mind.
                    <span class="ideal-ans">Target: "'.$r_caveat.'"</span></li>
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
  <p>'.$scenario.'. '.$group1.' has a '.$measure.' of '.$phat1.' (out of '.$n1.' students), and '.$group2.' has a '.$measure.' of '.$phat2.' (out of '.$n2.' students). A hypothesis test for the difference in proportions produces a <b>p-value of '.$pval.'</b> with a significance level of <b>&#945; = '.$alpha_val.'</b>.</p>
  <p><b>Essay Prompt:</b><br>
  Write a final conclusion statement about whether there is a difference in the '.$measure.' between the two groups. Make sure to state whether the null hypothesis is rejected or not, and explain what this tells us about the groups being compared.</p>
  <p>In your conclusion, be sure to address:</p>
  <ul>
    <li>Whether the result is statistically significant based on comparing the p-value to &#945;, and your decision about the null hypothesis.</li>
    <li>What this decision means in the real-world context of the study.</li>
    <li>Any important limitations or cautions about what the result does or does not prove.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton