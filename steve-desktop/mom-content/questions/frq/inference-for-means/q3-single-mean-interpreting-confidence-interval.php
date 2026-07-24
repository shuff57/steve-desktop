// === NAME - DESCRIPTION: Single Mean - Interpreting Confidence Interval - Students interpret a confidence interval for a population mean in context, explain what the confidence level means, and assess whether a claimed mean is plausible based on whether it falls inside the interval. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$i = rand(0, 2);

$roles = array("a school counselor", "a transportation analyst", "a family doctor");
$measurements = array("weekly study time", "one-way commute time", "resting heart rate");
$populations = array("all students at the school", "all employees at the company", "all adult patients at the clinic");
$units = array("hours per week", "minutes", "beats per minute");

$sample_sizes = array("50", "40", "60");
$sample_means = array("13.2", "26.3", "68.4");
$confidence_levels = array("95", "90", "99");
$alphas = array("0.05", "0.10", "0.01");
$lower_bounds = array("11.8", "23.1", "65.1");
$upper_bounds = array("14.6", "29.5", "71.7");
$claims_mu0 = array("15", "25", "72");

$role = $roles[$i];
$measurement = $measurements[$i];
$population = $populations[$i];
$unit = $units[$i];
$n = $sample_sizes[$i];
$xbar = $sample_means[$i];
$conf_level = $confidence_levels[$i];
$alpha = $alphas[$i];
$lower = $lower_bounds[$i];
$upper = $upper_bounds[$i];
$mu0 = $claims_mu0[$i];

$interpretations = array(
  "We are 95% confident that the true mean weekly study time for all students at the school is between 11.8 and 14.6 hours per week.",
  "We are 90% confident that the true mean one-way commute time for all employees at the company is between 23.1 and 29.5 minutes.",
  "We are 99% confident that the true mean resting heart rate for all adult patients at the clinic is between 65.1 and 71.7 beats per minute."
);

$confidence_meanings = array(
  "The 95% confidence level describes the long-run performance of this method. If we repeatedly took random samples of 50 students and built confidence intervals the same way, about 95% of those intervals would capture the true population mean.",
  "The 90% confidence level describes the method, not the single fixed mean. If we repeatedly sampled 40 employees and built intervals this way, about 90% of those intervals would contain the true population mean.",
  "The 99% confidence level means this interval-building process is very reliable in repeated sampling. Over many random samples of 60 patients, about 99% of intervals made this way would include the true population mean."
);

$claim_assessments = array(
  "The claimed value &#956;&#8320; = 15 is outside the interval (11.8, 14.6), so the claim is not plausible based on this sample. The data suggest the true mean weekly study time is lower than 15 hours per week.",
  "The claimed value &#956;&#8320; = 25 is inside the interval (23.1, 29.5), so the claim is plausible based on this sample. The interval does not provide evidence that the true mean differs from 25 minutes.",
  "The claimed value &#956;&#8320; = 72 is outside the interval (65.1, 71.7), so the claim is not plausible based on this sample. The data suggest the true mean resting heart rate is lower than 72 beats per minute."
);

$ci_interpretation_target = $interpretations[$i];
$confidence_meaning_target = $confidence_meanings[$i];
$claim_assessment_target = $claim_assessments[$i];

$sample_narrative = "A random sample of n = $n from $population produced x&#772; = $xbar $unit, and the $conf_level% confidence interval for &#956; is ($lower, $upper). <b>$ci_interpretation_target</b> <b>$confidence_meaning_target</b> <b>$claim_assessment_target</b>";

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
            <td style="text-align:center;"><b>CI Interpretation</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Interpret the confidence interval in context, identifying the true population mean, the population, and the interval bounds with units.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Confidence Level Meaning</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain what the confidence level means in terms of the long-run behavior of the interval method.</label></li>
                <li><label><input type="checkbox"> Avoid treating the true mean as random in this specific interval.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Assessing the Claim</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Use whether the claimed value falls inside or outside the interval to decide if the claim is plausible.</label></li>
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
            <td style="text-align:center;"><b>CI Interpretation<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Interpret the confidence interval in context.
                    <span class="ideal-ans">Target: "'.$ci_interpretation_target.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Confidence Level Meaning<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain what "$conf_level% confident" means using repeated sampling language.
                    <span class="ideal-ans">Target: "'.$confidence_meaning_target.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Assessing the Claim<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Use whether &#956;&#8320; is inside or outside the interval to evaluate the claim.
                    <span class="ideal-ans">Target: "'.$claim_assessment_target.'"</span></li>
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
  <p>'.$role.' is studying '.$measurement.' for '.$population.'. A random sample of <b>n = '.$n.'</b> gives a sample mean of <b>x&#772; = '.$xbar.' '.$unit.'</b>.</p>
  <p>A <b>'.$conf_level.'% confidence interval</b> for the true population mean <b>&#956;</b> is <b>('.$lower.', '.$upper.')</b> '.$unit.'.</p>
  <p>The claim being evaluated is <b>&#956;&#8320; = '.$mu0.' '.$unit.'</b>, with significance level <b>&#945; = '.$alpha.'</b>.</p>
  <p><b>Essay Prompt:</b><br>
  Interpret this confidence interval in context. Then explain what "'.$conf_level.'% confident" means, and use the interval to evaluate whether the claim about the population mean is plausible.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>A correct in-context interpretation of the interval for &#956;.</li>
    <li>The meaning of the confidence level as a property of the method in repeated sampling.</li>
    <li>Whether the claim value '.$mu0.' is inside or outside the interval, and what that says about the claim.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
