// === NAME - DESCRIPTION: Paired Means - Interpreting Results - Students interpret a completed paired t-test by comparing p-value to alpha, making the statistical decision, and writing a practical conclusion about intervention effects in context. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$i = rand(0, 2);

$roles = array("a reading coach", "a sleep researcher", "a fitness trainer");
$contexts = array(
  "paired reading scores before and after a support program for the same students",
  "paired nightly sleep hours before and after a sleep workshop for the same participants",
  "paired bench press results before and after an 8-week training plan for the same clients"
);
$pair_labels = array("students", "participants", "clients");
$interventions = array("the reading support program", "the sleep workshop", "the 8-week training plan");
$measures = array("reading score", "hours of sleep per night", "bench press weight");
$units = array("points", "hours", "lbs");
$directions = array("greater than 0", "not equal to 0", "greater than 0");

$sample_sizes = array("30", "25", "40");
$dbar_values = array("4.2", "0.3", "12.5");
$sd_values = array("8.1", "1.5", "15.3");
$p_values = array("0.006", "0.31", "0.0001");
$alphas = array("0.05", "0.05", "0.01");
$rejects = array("yes", "no", "yes");

$role = $roles[$i];
$paired_context = $contexts[$i];
$pair_label = $pair_labels[$i];
$intervention = $interventions[$i];
$measure = $measures[$i];
$unit = $units[$i];
$direction = $directions[$i];

$n = $sample_sizes[$i];
$dbar = $dbar_values[$i];
$sd = $sd_values[$i];
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
  "Because p = 0.006 is less than &#945; = 0.05, we reject H&#8320;. The paired result is statistically significant.",
  "Because p = 0.31 is greater than &#945; = 0.05, we fail to reject H&#8320;. The paired result is not statistically significant.",
  "Because p = 0.0001 is less than &#945; = 0.01, we reject H&#8320;. The paired result is statistically significant."
);

$context_meanings = array(
  "In context, there is convincing evidence that the reading support program changed average reading scores for these students, with post-program scores tending to be higher.",
  "In context, the data do not provide convincing evidence that the sleep workshop changed average nightly sleep hours for these participants.",
  "In context, there is convincing evidence that the 8-week training plan increased average bench press performance for these clients."
);

$conclusion_framings = array(
  "The evidence supports a real intervention effect rather than random sample-to-sample variation alone, so the coach can reasonably conclude the program helped improve reading scores on average.",
  "The evidence is not strong enough to claim a real intervention effect, so the researcher should treat any observed change as possibly due to random variation and consider collecting more data.",
  "The evidence supports a real intervention effect, so the trainer can conclude the plan likely improved strength on average in this paired group."
);

$decision_target = $decisions[$i];
$context_meaning_target = $context_meanings[$i];
$conclusion_framing_target = $conclusion_framings[$i];

$r_decision = $decision_target;
$r_context = $context_meaning_target;
$r_evidence = $conclusion_framing_target;

$sample_narrative = "For this paired t-test, p = $pvalue is $compare_word than &#945; = $alpha, so $decision_phrase and the result $sig_phrase. <b>$r_decision</b> <b>$r_context</b> <b>$r_evidence</b>";

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
                <li><label><input type="checkbox"> State the decision about H&#8320; (reject or fail to reject).</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Conclusion in Context</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain what the statistical decision means for this paired before-after setting.</label></li>
                <li><label><input type="checkbox"> Describe the likely direction of change using the context and measured variable.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Interpretation of Evidence</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Describe what the evidence does or does not support about intervention effect.</label></li>
                <li><label><input type="checkbox"> State a practical takeaway the researcher can reasonably make from this test.</label></li>
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
                <li>Compare p-value to &#945;, decide significance, and state reject/fail-to-reject decision.
                    <span class="ideal-ans">Target: "'.$decision_target.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Conclusion in Context<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Translate the test decision into a real-world conclusion for the paired intervention context.
                    <span class="ideal-ans">Target: "'.$context_meaning_target.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Interpretation of Evidence<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>State what the evidence suggests about intervention effect and a reasonable researcher takeaway.
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
  <p>'.$role.' is analyzing '.$paired_context.'. The analysis uses differences defined as <b>(after - before)</b> for each pair of '.$pair_label.'.</p>
  <p>From the sample, <b>n = '.$n.'</b>, <b>d&#772; = '.$dbar.' '.$unit.'</b>, and <b>s<sub>d</sub> = '.$sd.' '.$unit.'</b>.</p>
  <p>The hypotheses are provided:<br>
  <b>H&#8320;: &#956;<sub>d</sub> = 0</b><br>
  <b>H&#8321;: &#956;<sub>d</sub> '.$direction.'</b></p>
  <p>The test output gives <b>p = '.$pvalue.'</b> and uses <b>&#945; = '.$alpha.'</b>.</p>
  <p><b>Essay Prompt:</b><br>
  Interpret the paired t-test results. Compare p to &#945;, make the correct decision about H&#8320;, explain what that decision means in this before-after context, and describe what the evidence suggests about whether '.$intervention.' had an effect on '.$measure.'.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>How p compares to &#945; and whether the result is statistically significant.</li>
    <li>The corresponding reject or fail-to-reject conclusion for H&#8320;.</li>
    <li>A practical interpretation of what the evidence says about intervention effect in context.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
