// === NAME - DESCRIPTION: Chi-Square Independence - Interpreting Results - Students write a conclusion statement for a chi-square test of independence, deciding whether to reject or fail to reject H0, explaining the result in context, and recognizing that association does not prove causation. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$i = rand(0, 2);

// Scenario descriptions
$contexts = array(
  "a researcher performs a chi-square test of independence examining whether there is a relationship between students' sleep quality (good or poor) and their academic performance (passing or failing)",
  "a school counselor performs a chi-square test of independence examining whether there is a relationship between students' exercise frequency (regular or irregular) and their reported stress level (high or low)",
  "a principal performs a chi-square test of independence examining whether there is a relationship between students' breakfast habits (eats breakfast or skips breakfast) and their tardiness record (frequently tardy or rarely tardy)"
);
$context = $contexts[$i];

// Variable names
$var1s = array("sleep quality", "exercise frequency", "breakfast habits");
$var2s = array("academic performance", "stress level", "tardiness");
$var1 = $var1s[$i];
$var2 = $var2s[$i];

// P-values and alpha levels
$pvalues = array("0.02", "0.12", "0.006");
$pval = $pvalues[$i];

$alphas = array("0.05", "0.05", "0.01");
$alpha_val = $alphas[$i];

// Whether the result is significant
$is_sig = array("yes", "no", "yes");
$sig = $is_sig[$i];

// Decision text for the model answer
$decisions = array(
  "Since the p-value (0.02) is less than &#945; = 0.05, we reject the null hypothesis. There is sufficient evidence to conclude that there is a statistically significant association between sleep quality and academic performance.",
  "Since the p-value (0.12) is greater than &#945; = 0.05, we fail to reject the null hypothesis. There is not sufficient evidence to conclude that there is a significant association between exercise frequency and stress level.",
  "Since the p-value (0.006) is less than &#945; = 0.01, we reject the null hypothesis. There is sufficient evidence to conclude that there is a statistically significant association between breakfast habits and tardiness."
);
$decision = $decisions[$i];

// Contextual meaning of the result
$context_meanings = array(
  "The data suggest that sleep quality and academic performance are not independent. Students with good sleep quality may be more likely to pass, or students with poor sleep quality may be more likely to fail, based on this sample.",
  "The data do not provide enough evidence to say that exercise frequency and stress level are related. The observed differences between regular and irregular exercisers could reasonably be due to chance.",
  "The data suggest that breakfast habits and tardiness are not independent. Students who eat breakfast may be less likely to be frequently tardy, or students who skip breakfast may be more likely to be frequently tardy, based on this sample."
);
$context_meaning = $context_meanings[$i];

// Limitation and caution statements
$cautions = array(
  "Even though the test found a significant association, this does not prove that poor sleep causes students to fail. A chi-square test of independence can only detect an association between two variables. There could be other factors, like part-time jobs or family responsibilities, that affect both sleep quality and academic performance at the same time.",
  "Failing to reject the null hypothesis does not prove that exercise and stress are completely unrelated. It only means this study did not find strong enough evidence to rule out chance. The sample may have been too small to detect a real but modest relationship, or other factors could be masking the connection.",
  "Even though the test found a significant association, this does not prove that skipping breakfast causes tardiness. The chi-square test only tells us the two variables appear to be related in this sample. There may be other factors, like transportation issues or staying up late, that influence both breakfast habits and tardiness at the same time."
);
$caution = $cautions[$i];

// Build conditional phrasing
if ($sig == "yes") {
  $compare_word = "less";
  $reject_phrase = "reject the null hypothesis";
  $sig_phrase = "statistically significant";
} else {
  $compare_word = "greater";
  $reject_phrase = "fail to reject the null hypothesis";
  $sig_phrase = "not statistically significant";
}

$sample_narrative = "Based on the results, <b>$decision</b> In the context of this study, <b>$context_meaning</b> However, it is important to recognize the limitations of this conclusion. <b>$caution</b>";

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
            <td style="text-align:center;"><b>Hypothesis Decision</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Compare the p-value to the significance level and explain how that comparison leads to a decision.</label></li>
                <li><label><input type="checkbox"> State whether the null hypothesis is rejected or not rejected.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Contextual Interpretation</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain what the decision means about the relationship between the two variables in the context of this study.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Limitations and Caution</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Address whether the result proves that one variable causes changes in the other.</label></li>
                <li><label><input type="checkbox"> Mention what other factors or explanations could be involved.</label></li>
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
                <li>Compare the p-value to the significance level and explain the comparison.
                    <span class="ideal-ans">Target: "The p-value ('.$pval.') is '.$compare_word.' than &#945; = '.$alpha_val.', so the result is '.$sig_phrase.'."</span></li>
                <li>State whether the null hypothesis is rejected or not rejected.
                    <span class="ideal-ans">Target: "We '.$reject_phrase.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Contextual Interpretation<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain what the decision means about the relationship between the two variables in context.
                    <span class="ideal-ans">Target: "'.$context_meaning.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Limitations and Caution<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Address whether the result proves causation.
                    <span class="ideal-ans">Target: "A chi-square test of independence can only detect an association. It does not prove that one variable causes changes in the other."</span></li>
                <li>Mention other factors or explanations that could be involved.
                    <span class="ideal-ans">Target: "'.$caution.'"</span></li>
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
  <p>Suppose '.$context.'. The test results in a <b>p-value of '.$pval.'</b> with a significance level of <b>&#945; = '.$alpha_val.'</b>.</p>
  <p><b>Essay Prompt:</b><br>
  Write a final conclusion statement explaining what should be concluded about the relationship between '.$var1.' and '.$var2.'. Make sure to address whether the null hypothesis is rejected, what this means in the context of the study, and whether the result proves that one variable causes changes in the other.</p>
  <p>In your conclusion, be sure to cover:</p>
  <ul>
    <li>Whether the p-value leads you to reject or fail to reject the null hypothesis, and why.</li>
    <li>What the decision tells us about the relationship between '.$var1.' and '.$var2.' in the context of this study.</li>
    <li>Whether the result proves causation, and what other explanations might exist.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
