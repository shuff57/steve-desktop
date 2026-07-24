// === NAME - DESCRIPTION: Chi-Square Goodness of Fit - Interpreting Results - Students write a conclusion statement for a chi-square goodness of fit test by comparing the p-value to the significance level, stating whether the null hypothesis is rejected, and explaining what the results suggest about the observed distribution. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$i = rand(0, 2);

// Context 0: True/false quiz, p=0.16, fail to reject
// Context 1: Gummy bear colors, p=0.01, reject
// Context 2: Weekday absences, p=0.89, fail to reject

$scenarios = array(
  "A teacher suspects that students are not guessing randomly on a true/false quiz. On a 50-question quiz, if students were purely guessing, we would expect about 25 true answers and 25 false answers. Instead, the teacher observes 30 true answers and 20 false answers.",
  "A candy company claims that its bags of gummy bears contain equal proportions of five colors: red, orange, yellow, green, and white. A consumer opens a large bag of 200 gummy bears and counts 58 red, 47 orange, 28 yellow, 35 green, and 32 white.",
  "A school nurse believes that student absences are evenly distributed across the five weekdays. Over a semester, the nurse records 200 total absences and finds: 45 on Monday, 38 on Tuesday, 36 on Wednesday, 39 on Thursday, and 42 on Friday."
);
$scenario = $scenarios[$i];

$test_stats = array(
  "&#967;&#178; = 2.00, p-value = 0.16",
  "&#967;&#178; = 12.55, p-value = 0.01",
  "&#967;&#178; = 1.15, p-value = 0.89"
);
$test_stat = $test_stats[$i];

$pvalues = array("0.16", "0.01", "0.89");
$pval = $pvalues[$i];

$alpha_val = "0.05";

$rejects = array("no", "yes", "no");
$reject = $rejects[$i];

$test_subjects = array(
  "student guessing behavior on the quiz",
  "the company's claim about equal color distribution",
  "whether absences are evenly spread across weekdays"
);
$test_subject = $test_subjects[$i];

$decisions = array(
  "Since the p-value (0.16) is greater than &#945; = 0.05, we fail to reject the null hypothesis.",
  "Since the p-value (0.01) is less than &#945; = 0.05, we reject the null hypothesis.",
  "Since the p-value (0.89) is greater than &#945; = 0.05, we fail to reject the null hypothesis."
);
$decision = $decisions[$i];

$conclusions = array(
  "There is not sufficient evidence to conclude that students are guessing non-randomly on this quiz. The observed split of 30 true and 20 false answers is consistent with what could happen by chance alone if students were purely guessing.",
  "There is sufficient evidence to conclude that the distribution of gummy bear colors does not match the claimed equal proportions. The observed counts suggest the colors are not evenly distributed, with red appearing notably more often than expected.",
  "There is not sufficient evidence to conclude that student absences differ across the weekdays. The observed absence counts are very consistent with what we would expect if absences were evenly spread throughout the week."
);
$conclusion = $conclusions[$i];

$limitations = array(
  "This result does not prove that students are guessing randomly. It simply means the data did not provide strong enough evidence to rule out random guessing. The difference between 30 and 25 could easily be due to chance.",
  "This result does not prove that the company deliberately misrepresented its color distribution. It only tells us the observed colors in this sample deviate enough from equal proportions that chance alone is an unlikely explanation.",
  "This result does not prove that absences are perfectly equal every day. It only means the small differences observed are well within what we would expect from normal random variation."
);
$limitation = $limitations[$i];

if ($reject == "yes") {
  $compare_word = "less";
  $reject_phrase = "we reject the null hypothesis";
  $evidence_phrase = "sufficient evidence";
} else {
  $compare_word = "greater";
  $reject_phrase = "we fail to reject the null hypothesis";
  $evidence_phrase = "not sufficient evidence";
}

$sample_narrative = "<b>$decision</b> Because the p-value is $compare_word than our significance level, $reject_phrase. In context, this means there is <b>$evidence_phrase</b> to conclude that the observed distribution differs from what was expected. <b>$conclusion</b> It is important to note that <b>$limitation</b>";

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
        det.offsetHeight;
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
                <li><label><input type="checkbox"> Compare the p-value to the significance level.</label></li>
                <li><label><input type="checkbox"> State whether you reject or fail to reject the null hypothesis.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Conclusion in Context</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Use appropriate language about whether there is or is not sufficient evidence.</label></li>
                <li><label><input type="checkbox"> Connect the conclusion back to the specific scenario, explaining what the result suggests.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Evidence Strength</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Address what the test result does or does not prove about the claim.</label></li>
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
                <li>Compare the p-value to the significance level.
                    <span class="ideal-ans">Target: "The p-value of '.$pval.' is '.$compare_word.' than &#945; = '.$alpha_val.'."</span></li>
                <li>State whether you reject or fail to reject the null hypothesis.
                    <span class="ideal-ans">Target: "'.$decision.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Conclusion in Context<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Use appropriate evidence language (sufficient or not sufficient).
                    <span class="ideal-ans">Target: "There is '.$evidence_phrase.' to conclude that the observed distribution differs from the expected distribution."</span></li>
                <li>Connect the conclusion to the specific scenario.
                    <span class="ideal-ans">Target: "'.$conclusion.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Evidence Strength<br>(2 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Address what the test result does or does not prove.
                    <span class="ideal-ans">Target: "'.$limitation.'"</span></li>
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
  <p>'.$scenario.'</p>
  <p>A chi-square goodness of fit test is performed and produces the following results: <b>'.$test_stat.'</b>, with a significance level of <b>&#945; = '.$alpha_val.'</b>.</p>
  <p><b>Essay Prompt:</b><br>
  Write a final conclusion statement for this chi-square goodness of fit test. Be sure to state whether the null hypothesis is rejected or not, and explain what the results suggest about '.$test_subject.'.</p>
  <p>In your conclusion, be sure to cover:</p>
  <ul>
    <li>A comparison of the p-value to the significance level, and your decision about the null hypothesis (reject or fail to reject).</li>
    <li>A conclusion in context that uses appropriate language about whether there is or is not sufficient evidence, and what the result means for this specific scenario.</li>
    <li>What the test result does or does not prove about the original claim.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton