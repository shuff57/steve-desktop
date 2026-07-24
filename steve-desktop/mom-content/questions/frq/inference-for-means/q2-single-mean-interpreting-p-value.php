// === NAME - DESCRIPTION: Single Mean Inference - Interpreting P-Value - Students interpret a completed one-sample mean hypothesis test by comparing p-value to significance level, making a decision on H&#8320;, and writing a context-based conclusion about the evidence. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$i = rand(0, 2);

$roles = array("a school principal", "a factory manager", "a nutritionist");
$role = $roles[$i];

$claims = array(
  "the average nightly sleep time for students at the school is less than 7.0 hours",
  "the average fill weight of the cereal boxes from a production line is greater than 500 grams",
  "the average daily sugar intake for clients in a wellness program is less than 35 grams"
);
$claim = $claims[$i];

$sample_sizes = array("64", "40", "55");
$n = $sample_sizes[$i];

$sample_means = array("x&#772; = 6.8", "x&#772; = 501.2", "x&#772; = 33.9");
$xbar = $sample_means[$i];

$claimed_values = array("7.0", "500", "35");
$mu0 = $claimed_values[$i];

$null_hyps = array("H&#8320;: &#956; = 7.0", "H&#8320;: &#956; = 500", "H&#8320;: &#956; = 35");
$null_hyp = $null_hyps[$i];

$alt_hyps = array("H&#8321;: &#956; &lt; 7.0", "H&#8321;: &#956; &gt; 500", "H&#8321;: &#956; &lt; 35");
$alt_hyp = $alt_hyps[$i];

$pvalues = array("0.12", "0.018", "0.041");
$pval = $pvalues[$i];

$alphas = array("0.05", "0.05", "0.01");
$alpha_val = $alphas[$i];

$is_reject = array("no", "yes", "no");
$reject = $is_reject[$i];

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
  "Since the p-value (0.12) is greater than &#945; = 0.05, the result is not statistically significant and we fail to reject the null hypothesis.",
  "Since the p-value (0.018) is less than &#945; = 0.05, the result is statistically significant and we reject the null hypothesis.",
  "Since the p-value (0.041) is greater than &#945; = 0.01, the result is not statistically significant and we fail to reject the null hypothesis."
);
$decision_text = $decisions[$i];

$context_meanings = array(
  "In context, the sample does not provide strong enough evidence that students at this school average less than 7.0 hours of sleep per night.",
  "In context, the sample provides convincing evidence that the true average fill weight is above 500 grams on this production line.",
  "In context, the sample does not provide strong enough evidence that the true average daily sugar intake is below 35 grams for clients in this program."
);
$context_meaning = $context_meanings[$i];

$conclusion_framings = array(
  "The principal should treat this result as inconclusive for the claim and avoid saying the average sleep time is below 7.0 hours based on this test alone.",
  "The manager should conclude that the process appears to be overfilling on average and should review calibration targets and production controls.",
  "The nutritionist should conclude that the data are inconclusive for the below-35-gram claim at the 0.01 level and should gather more evidence before making that claim."
);
$conclusion_framing = $conclusion_framings[$i];

$sample_narrative = "The hypothesis test uses $null_hyp with the alternative $alt_hyp. The sample of $n produced a sample mean of $xbar, giving a p-value of $pval at &#945; = $alpha_val. <b>$decision_text</b> <b>$context_meaning</b> <b>$conclusion_framing</b>";

/* ---------- 2. SHARED CSS & JS ---------- */
$css_block = '
<style>
    /* Container & Details */
    .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
    .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
    
    /* Summary styling */
    .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; border:none; }
    .rubric-container details[open] summary { box-shadow: inset 0 -1px 0 #ccc; }
    .rubric-container summary::-webkit-details-marker { display:none; }
    
    /* Arrows */
    .arrow-open { display:none; }
    .rubric-container details[open] .arrow-closed { display:none; }
    .rubric-container details[open] .arrow-open { display:inline; }

    /* Content Animation Wrapper */
    .rubric-content { overflow:hidden; max-height:0; opacity:0; transition:max-height 300ms ease-out, opacity 300ms ease-out, padding 200ms ease-out; margin-top:0; background:#fafafa; box-sizing:border-box; padding:0 0.75em; }
    .rubric-container details[open] .rubric-content { max-height:2000px; opacity:1; padding:0.75em; }

    /* Table Styling */
    .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
    .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
    .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; user-select:text; }
    
    /* Theme Colors */
    .row-colored { background:#fff9ea; }
    .col-header { width:25%; border-top-left-radius:8px; }
    .col-check { border-top-right-radius:8px; }
    .col-cat-bot { border-bottom-left-radius:8px; }
    .col-check-bot { border-bottom-right-radius:8px; }

    /* Answer Key Specifics */
    .ideal-ans { display: block; background-color: #e8f5e9; font-style: italic; font-weight: bold; font-size: 0.95em; margin: 5px 0 10px 0; border-left: 3px solid #4CAF50; padding-left: 8px; }
    .full-response-box { margin-top: 15px; border: 2px solid #4CAF50; background-color: #e8f5e9; padding: 15px; border-radius: 5px; }
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
      <p style="margin:0 0 0.5em 0;"><b>Grading Criteria</b> -- ensure your conclusion covers these points:</p>
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
                <li><label><input type="checkbox"> Compare the p-value to &#945; and state whether you reject or fail to reject H&#8320;.</label></li>
                <li><label><input type="checkbox"> State whether the result is statistically significant.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Conclusion in Context</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain what the test decision means for the specific claim in this real-world situation.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Interpretation of Evidence</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Describe what the evidence tells us and what the researcher should take away from the result.</label></li>
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
                <li>Compare p-value to &#945; and state the test decision on H&#8320;.
                    <span class="ideal-ans">Target: "The p-value of '.$pval.' is '.$compare_word.' than &#945; = '.$alpha_val.', so '.$decision_phrase.'."</span></li>
                <li>State whether the result is statistically significant.
                    <span class="ideal-ans">Target: "The result '.$sig_phrase.' at the &#945; = '.$alpha_val.' level."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Conclusion in Context<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain what that decision means for the claim in this scenario.
                    <span class="ideal-ans">Target: "'.$context_meaning.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Interpretation of Evidence<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Describe what the evidence suggests and what the researcher should conclude.
                    <span class="ideal-ans">Target: "'.$conclusion_framing.'"</span></li>
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
  <p>Suppose '.$role.' wants to test whether '.$claim.'. A random sample of size <b>n = '.$n.'</b> is collected, and the sample mean is <b>'.$xbar.'</b>. The claimed population mean is <b>&#956;&#8320; = '.$mu0.'</b>.</p>
  <p>The hypotheses are:</p>
  <p style="margin-left:2em;">'.$null_hyp.'<br>'.$alt_hyp.'</p>
  <p>The test produces a <b>p-value of '.$pval.'</b> with a significance level of <b>&#945; = '.$alpha_val.'</b>.</p>
  <p><b>Essay Prompt:</b><br>
  Write a clear conclusion statement for this hypothesis test. Your response should interpret the p-value decision and explain what it means for the researcher in this context.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>How the p-value compares to &#945;, and whether to reject or fail to reject H&#8320;.</li>
    <li>What that decision means for the claim about the population mean &#956;.</li>
    <li>What the evidence tells us and what the researcher should take away from this result.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
