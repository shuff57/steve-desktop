// === NAME - DESCRIPTION: Understanding Statistical Significance - Students interpret a p-value relative to a significance level, make the correct reject or fail-to-reject decision, explain why statistical significance does not prove a hypothesis is true, and describe what the result actually tells us about the evidence. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$i = rand(0, 2);

// Three scenarios with varied p-values, alpha levels, and significance outcomes
$contexts = array(
  "a researcher is testing whether a new tutoring program improves student pass rates",
  "a health department is testing whether a public awareness campaign has changed the vaccination rate in a county",
  "a quality control team is testing whether a manufacturing change has affected the defect rate at a factory"
);
$context = $contexts[$i];

$pvalues = array("0.03", "0.08", "0.004");
$pval = $pvalues[$i];

$alphas = array("0.05", "0.05", "0.01");
$alpha_val = $alphas[$i];

// Whether result is significant: 0 = yes, 1 = no, 2 = yes
$is_sig = array("yes", "no", "yes");
$sig = $is_sig[$i];

// Plain-language p-value explanations
$p_meanings = array(
  "If the tutoring program had no real effect on pass rates, there would be only a 3% chance of seeing a difference at least as large as what the study found.",
  "If the awareness campaign had no real effect on vaccination rates, there would be an 8% chance of seeing a difference at least as large as what the study found.",
  "If the manufacturing change had no real effect on the defect rate, there would be only a 0.4% chance of seeing a difference at least as large as what the study found."
);
$p_meaning = $p_meanings[$i];

// Decision statements
$decisions = array(
  "Since the p-value (0.03) is less than &#945; = 0.05, the result is statistically significant and we reject the null hypothesis. The data provide enough evidence to conclude that the pass rate has changed since the tutoring program was introduced.",
  "Since the p-value (0.08) is greater than &#945; = 0.05, the result is not statistically significant and we fail to reject the null hypothesis. The data do not provide enough evidence to conclude that the vaccination rate has changed since the campaign.",
  "Since the p-value (0.004) is less than &#945; = 0.01, the result is statistically significant and we reject the null hypothesis. The data provide enough evidence to conclude that the defect rate has changed since the manufacturing adjustment."
);
$decision = $decisions[$i];

// Why the result is not proof
$not_proof = array(
  "Rejecting the null hypothesis does not prove the tutoring program works. It only means the observed difference would be unlikely if the program had no effect at all. There could be other explanations: maybe a different instructor taught the class, class sizes shrank, or motivated students chose to join the program. The p-value is not the probability that the hypothesis is true; it is a measure of how surprising the data would be under the null hypothesis.",
  "Failing to reject the null hypothesis does not prove the campaign had no effect. It only means the observed data were not unusual enough to rule out chance at this significance level. The campaign might have a real but small effect that this particular study was not large enough to detect. A non-significant result is not the same as evidence of no effect.",
  "Rejecting the null hypothesis does not prove the manufacturing change caused a shift in defect rates. It only means the observed difference would be unlikely if nothing had actually changed. Other factors, such as new raw materials, different workers on the line, or seasonal variation, could also explain the result. A p-value tells us about the data under the null hypothesis, not about whether the alternative hypothesis is true."
);
$not_proof_text = $not_proof[$i];

// What the result actually tells us
$what_tells = array(
  "The result tells us there is sufficient evidence to suggest the tutoring program may have a real effect on pass rates. The observed difference is unlikely to be due to random chance alone. This is a statement about the strength of evidence, not a guarantee. Further research, replication, and consideration of alternative explanations would be needed before drawing strong conclusions.",
  "The result tells us the study did not find convincing evidence that the campaign changed the vaccination rate. The observed data are consistent with what we might see by chance alone. This does not mean the campaign definitely has no effect; it means this study could not detect one. A larger sample or different design might yield different results.",
  "The result tells us there is strong evidence that something changed in the defect rate. The data are very unlikely under the assumption of no change. This gives the quality control team reason to investigate further, but one significant test alone does not establish exactly what caused the change or guarantee the effect will persist."
);
$what_tells_text = $what_tells[$i];

if ($sig == "yes") {
  $sig_phrase = "is statistically significant";
  $compare_word = "less";
} else {
  $sig_phrase = "is not statistically significant";
  $compare_word = "greater";
}

$sample_narrative = "The p-value of $pval tells us this: <b>$p_meaning</b> We compare this p-value to our significance level of &#945; = $alpha_val. Since $pval is $compare_word than $alpha_val, the result $sig_phrase. <b>$decision</b> However, this does not prove anything with certainty. <b>$not_proof_text</b> So what does the result actually tell us? <b>$what_tells_text</b>";

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
      <p style="margin:0 0 0.5em 0;"><b>Grading Criteria</b> -- ensure your explanation covers these points:</p>
      <table class="rubric-table">
        <tbody>
          <tr>
            <th class="col-header">Category</th>
            <th class="col-check">Requirement</th>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Interpreting the P-value and Decision</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain in plain language what the p-value tells us, using the context of this study.</label></li>
                <li><label><input type="checkbox"> Compare the p-value to the significance level and state the decision about the null hypothesis.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Significance Is Not Proof</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Address whether this result proves the hypothesis is true (or false), and explain your reasoning.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>What the Result Actually Tells You</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Describe what the test result does and does not tell us about the evidence in this study.</label></li>
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
            <td style="text-align:center;"><b>Interpreting the P-value and Decision<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain in plain language what the p-value tells us.
                    <span class="ideal-ans">Target: "'.$p_meaning.'"</span></li>
                <li>Compare the p-value to &#945; and state the decision.
                    <span class="ideal-ans">Target: "'.$decision.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Significance Is Not Proof<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Address whether this result proves the hypothesis is true or false, and explain why not.
                    <span class="ideal-ans">Target: "'.$not_proof_text.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>What the Result Actually Tells You<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Describe what the test result does and does not tell us.
                    <span class="ideal-ans">Target: "'.$what_tells_text.'"</span></li>
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
  <p>Suppose '.$context.' and obtains a <b>p-value of '.$pval.'</b> using a significance level of <b>&#945; = '.$alpha_val.'</b>.</p>
  <p><b>Essay Prompt:</b><br>
  Explain what this p-value means, whether the result is statistically significant, and what the researcher should conclude. Does this result prove that the hypothesis is true? What does it actually tell you?</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>What the p-value means in plain language for this study, and the decision about the null hypothesis based on comparing the p-value to &#945;.</li>
    <li>Why a statistically significant (or non-significant) result does not prove a hypothesis is true or false.</li>
    <li>What this test result actually tells us, and what it does not tell us, about the evidence in the study.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton