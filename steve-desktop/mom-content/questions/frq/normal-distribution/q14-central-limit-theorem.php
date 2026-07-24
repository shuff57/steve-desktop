// === NAME - DESCRIPTION: Central Limit Theorem - Students explain what the CLT predicts about the distribution of sample means from a skewed population, the conditions for it to apply, and why it matters for statistical inference. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$i = rand(0, 2);

$contexts = array(
  "household incomes in a large metropolitan area",
  "insurance claim amounts at a mid-size auto insurance company",
  "the number of customer service calls received per day at a busy call center"
);
$topic = $contexts[$i];

$pop_labels = array("households", "policyholders", "days");
$pop_label = $pop_labels[$i];

$sample_units = array("individuals", "claims", "days");
$sample_unit = $sample_units[$i];

$measure_labels = array("income", "claim amount", "number of calls");
$measure_label = $measure_labels[$i];

// Randomized numerical values per context
$mu_raw = array(rand(45,75)*1000, rand(8,15)*100, rand(25,45));
$sigma_raw = array(rand(15,25)*1000, rand(4,8)*100, rand(10,20));
$n_raw = array(rand(5,10)*10, rand(4,8)*10, rand(3,7)*10);

$mu = $mu_raw[$i];
$sigma = $sigma_raw[$i];
$n = $n_raw[$i];

$se = round($sigma / sqrt($n), 2);

// Display formatting (dollar prefix for income and claims, none for calls)
$dollar = array("&#36;", "&#36;", "");
$mu_display = $dollar[$i] . prettyint($mu);
$sigma_display = $dollar[$i] . prettyint($sigma);
$se_display = $dollar[$i] . $se;

// Narrative variables for the model answer
$r_shape = "The Central Limit Theorem tells us that when we take many random samples of size " . $n . " from this population and calculate the mean " . $measure_label . " for each sample, <b>the distribution of those sample means will be approximately normal</b>, even though the population of " . $topic . " is skewed to the right.";

$r_params = "The sampling distribution will be centered at the population mean of " . $mu_display . " with a <b>standard error of " . $se_display . "</b>, calculated as " . $sigma_display . " &#247; &#8730;" . $n . ".";

$r_conditions = "<b>The CLT applies here because the sample size of " . $n . " is at least 30</b>, which is generally large enough for the sampling distribution of the mean to be approximately normal regardless of the population shape.";

$r_importance = "This matters because <b>it allows researchers to use normal distribution methods, such as confidence intervals and hypothesis tests</b>, to make inferences about the true mean " . $measure_label . " in the population, even though the original distribution is not normal.";

$sample_narrative = $r_shape . " " . $r_params . " " . $r_conditions . " " . $r_importance;

/* ---------- 2. SHARED CSS & JS ---------- */
$css_block = '<style>
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
// NO answers shown - just checkbox criteria for students
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
            <td style="text-align:center;"><b>Shape of the Sampling Distribution</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Describe what the CLT predicts about the shape of the distribution of sample means.</label></li>
                <li><label><input type="checkbox"> State the mean and standard error of the sampling distribution.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Conditions for the CLT</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain the sample size condition needed for the CLT to apply and confirm it is met here.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Why the CLT Matters</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain why the CLT is important for making statistical inferences about the population.</label></li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </details>
</div>';

/* ---------- 4. Instructor Rubric (With Answer Targets) ---------- */
// Shows ideal answers and full model response - only visible when grading
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
            <td style="text-align:center;"><b>Shape of the Sampling Distribution<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Describe what the CLT predicts about the shape of the distribution of sample means.
                    <span class="ideal-ans">Target: "The CLT states that the distribution of sample means will be approximately normal, regardless of the shape of the original population."</span></li>
                <li>State the mean and standard error of the sampling distribution.
                    <span class="ideal-ans">Target: "The sampling distribution has mean &#956; = '.$mu_display.' and standard error SE = '.$se_display.', calculated as '.$sigma_display.' &#247; &#8730;'.$n.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Conditions for the CLT<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain the sample size condition needed for the CLT to apply and confirm it is met here.
                    <span class="ideal-ans">Target: "The CLT requires a sufficiently large sample size, generally n &#8805; 30. Since n = '.$n.', this condition is satisfied."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Why the CLT Matters<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain why the CLT is important for making statistical inferences about the population.
                    <span class="ideal-ans">Target: "The CLT allows us to use normal distribution techniques (confidence intervals, hypothesis tests) to make inferences about the population mean, even when the population is not normally distributed."</span></li>
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
  <p>A researcher is studying '.$topic.'. The distribution of '.$measure_label.' across all '.$pop_label.' is heavily skewed to the right, with a population mean (&#956;) of '.$mu_display.' and a population standard deviation (&#963;) of '.$sigma_display.'.</p>
  <p>The researcher plans to take many random samples, each consisting of n = '.$n.' '.$sample_unit.', and compute the sample mean '.$measure_label.' for each sample.</p>
  <p><b>Essay Prompt:</b><br>
  Explain what the Central Limit Theorem tells us about the distribution of these sample means. Why is this result significant, given that the original population is skewed?</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>What the CLT predicts about the shape of the sampling distribution, including the mean and standard error.</li>
    <li>The conditions required for the CLT to apply and whether they are met here.</li>
    <li>Why the CLT is important for real-world statistical analysis.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
