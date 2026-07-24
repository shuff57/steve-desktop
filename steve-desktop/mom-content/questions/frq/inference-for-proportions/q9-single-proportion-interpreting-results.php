// === NAME - DESCRIPTION: Single Proportion Inference - Interpreting Results - Students write a conclusion statement for a single-proportion hypothesis test given the sample data, p-value, and significance level, stating whether the null hypothesis is rejected and explaining what the result means in context. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$i = rand(0, 2);

// Three single-proportion scenarios: two fail to reject, one rejects
$roles = array("a high school counselor", "a restaurant manager", "a city planner");
$role = $roles[$i];

$claims = array(
  "more than 50% of students plan to attend college",
  "at least 70% of customers are satisfied with the new menu",
  "fewer than 30% of residents bike to work"
);
$claim = $claims[$i];

$sample_sizes = array("150", "200", "180");
$n = $sample_sizes[$i];

$successes = array("82", "133", "39");
$x = $successes[$i];

$sample_props = array("82/150 = 0.547", "133/200 = 0.665", "39/180 = 0.217");
$phat_display = $sample_props[$i];

$null_hyps = array("H&#8320;: p = 0.50", "H&#8320;: p = 0.70", "H&#8320;: p = 0.30");
$null_hyp = $null_hyps[$i];

$alt_hyps = array("H&#8321;: p &gt; 0.50", "H&#8321;: p &lt; 0.70", "H&#8321;: p &lt; 0.30");
$alt_hyp = $alt_hyps[$i];

$alt_directions = array("more than 50%", "less than 70%", "less than 30%");
$alt_dir = $alt_directions[$i];

$pvalues = array("0.08", "0.03", "0.006");
$pval = $pvalues[$i];

$alphas = array("0.05", "0.05", "0.01");
$alpha_val = $alphas[$i];

// Context 0: p=0.08 > alpha=0.05 => fail to reject
// Context 1: p=0.03 < alpha=0.05 => reject
// Context 2: p=0.006 < alpha=0.01 => reject
$is_reject = array("no", "yes", "yes");
$reject = $is_reject[$i];

$populations = array("all students at the school", "all of the restaurant's customers", "all residents in the city");
$population = $populations[$i];

$characteristics = array("planning to attend college", "satisfaction with the new menu", "biking to work");
$characteristic = $characteristics[$i];

$test_descriptions = array(
  "surveyed $n randomly selected students and found that $x plan to attend college",
  "surveyed $n randomly selected customers and found that $x are satisfied with the new menu",
  "surveyed $n randomly selected residents and found that $x bike to work"
);
$test_desc = $test_descriptions[$i];

// Build conditional narrative variables
if ($reject == "yes") {
  $compare_word = "less";
  $decision_phrase = "we reject the null hypothesis";
  $sig_phrase = "is statistically significant";
} else {
  $compare_word = "greater";
  $decision_phrase = "we fail to reject the null hypothesis";
  $sig_phrase = "is not statistically significant";
}

// --- Decision target text (per context) ---
$decisions = array(
  "Since the p-value (0.08) is greater than &#945; = 0.05, the result is not statistically significant and we fail to reject the null hypothesis. There is not enough evidence to conclude that more than 50% of students plan to attend college.",
  "Since the p-value (0.03) is less than &#945; = 0.05, the result is statistically significant and we reject the null hypothesis. There is sufficient evidence to conclude that fewer than 70% of customers are satisfied with the new menu.",
  "Since the p-value (0.006) is less than &#945; = 0.01, the result is statistically significant and we reject the null hypothesis. There is sufficient evidence to conclude that fewer than 30% of residents bike to work."
);
$decision_text = $decisions[$i];

// --- Context meaning target text (per context) ---
$context_meanings = array(
  "In practical terms, even though the sample showed 54.7% of students planning to attend college, this is not far enough above 50% to rule out chance. The counselor cannot conclude that a majority of all students plan to attend college. The data are consistent with the proportion being 50% or even lower.",
  "In practical terms, the sample showed only 66.5% customer satisfaction, and the data provide convincing evidence that satisfaction has dropped below the claimed 70%. The manager should take the lower satisfaction rate seriously and consider what changes to the menu may be driving the decline.",
  "In practical terms, the sample showed only 21.7% of residents biking to work, and the data provide convincing evidence that the true rate is below 30%. The city planner can use this finding to support the argument that biking infrastructure may be underutilized or that fewer residents commute by bike than previously believed."
);
$context_meaning = $context_meanings[$i];

// --- Conclusion framing target text (per context) ---
$conclusion_framings = array(
  "The counselor should conclude that the evidence from this sample is not strong enough to support the claim that more than half of students plan to attend college. This does not mean the claim is false; it means the test did not find convincing evidence in its favor at the 0.05 significance level.",
  "The manager should conclude that the evidence supports the concern that customer satisfaction is below the claimed 70%. The low p-value indicates the observed drop in satisfaction is unlikely to be due to chance alone.",
  "The city planner should conclude that the evidence supports the claim that fewer than 30% of residents bike to work. The very low p-value indicates the observed rate is unlikely to be due to random sampling variation alone."
);
$conclusion_framing = $conclusion_framings[$i];

$sample_narrative = "The hypothesis test uses $null_hyp with the alternative $alt_hyp. The sample of $n found $x with the characteristic ($phat_display), producing a p-value of $pval at a significance level of &#945; = $alpha_val. <b>$decision_text</b> <b>$context_meaning</b> <b>$conclusion_framing</b>";

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
            <td style="text-align:center;"><b>Statistical Decision</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Compare the p-value to the significance level and state whether the null hypothesis is rejected or not rejected.</label></li>
                <li><label><input type="checkbox"> State whether the result is statistically significant based on that comparison.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Conclusion in Context</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain what the decision means for the specific claim being tested, using the real-world context of the problem.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Interpretation of Evidence</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Describe what the result tells us about the strength of the evidence and what the researcher should take away from the test.</label></li>
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
                <li>Compare the p-value to &#945; and state whether the null hypothesis is rejected.
                    <span class="ideal-ans">Target: "The p-value of '.$pval.' is '.$compare_word.' than &#945; = '.$alpha_val.', so '.$decision_phrase.'."</span></li>
                <li>State whether the result is statistically significant.
                    <span class="ideal-ans">Target: "The result '.$sig_phrase.' at the '.$alpha_val.' significance level."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Conclusion in Context<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain what the decision means for the specific claim, using the real-world context.
                    <span class="ideal-ans">Target: "'.$context_meaning.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Interpretation of Evidence<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Describe what the result tells us about the evidence and what the researcher should take away.
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
  <p>Suppose '.$role.' wants to test whether '.$claim.'. They '.$test_desc.'. The hypothesis test produces a <b>p-value of '.$pval.'</b> with a significance level of <b>&#945; = '.$alpha_val.'</b>.</p>
  <p>The hypotheses are:</p>
  <p style="margin-left:2em;">'.$null_hyp.'<br>'.$alt_hyp.'</p>
  <p><b>Essay Prompt:</b><br>
  Write a final conclusion statement explaining what '.$role.' should conclude from this test. Make sure your conclusion addresses whether the null hypothesis is rejected or not, and explain what the result means in the context of the problem.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>Whether you reject or fail to reject the null hypothesis, based on comparing the p-value to the significance level.</li>
    <li>What this decision means for the specific claim being tested, using the real-world context.</li>
    <li>What the evidence from the test tells us and what the researcher should take away from the result.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton