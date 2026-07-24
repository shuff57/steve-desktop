// === NAME - DESCRIPTION: Sampling Distribution Reasoning - Students identify the population parameter and the sample statistic, verify CLT conditions for the sample mean, compute and interpret the standard error in context. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
  "commute times for adults in a metro area have mean 28 minutes and standard deviation 8 minutes (right-skewed). A transportation study takes a random sample of 50 commuters.",
  "weekly grocery spending in a region has mean \$120 and standard deviation \$35 (unknown shape). A market researcher takes a random sample of 64 households.",
  "delivery times for a national pizza chain have mean 22 minutes and standard deviation 5 minutes (right-skewed). The chain takes a random sample of 40 deliveries."
);
$populations = array(
  "all adults in the metro area",
  "all households in the region",
  "all deliveries by the chain"
);
$measurements = array("commute time (minutes)", "weekly grocery spending (dollars)", "delivery time (minutes)");
$mus    = array(28, 120, 22);
$sigmas = array(8,  35,  5);
$ns     = array(50, 64,  40);
$shapes = array("right-skewed", "unknown", "right-skewed");

$i = rand(0, 2);
$topic        = $contexts[$i];
$population   = $populations[$i];
$measurement  = $measurements[$i];
$mu           = $mus[$i];
$sigma        = $sigmas[$i];
$n            = $ns[$i];
$shape        = $shapes[$i];

$se = round($sigma / sqrt($n), 3);

$r_mu_explain  = "the population mean (`mu` = $mu) describes the average $measurement for $population";
$r_xbar_explain = "the sample mean (`bar(x)`) is a statistic computed from the random sample of $n; it varies from sample to sample";
$r_conditions  = "random sample (stated); population at least 10 times n (so independence is reasonable); and `n = $n` is greater than 30, so the Central Limit Theorem says the sampling distribution of `bar(x)` is approximately normal even though the population is $shape";
$r_se_compute  = "`SE = sigma/sqrt(n) = $sigma/sqrt($n) ~~ $se`";
$r_se_interpret = "the standard error $se measures the typical distance between a sample mean from a random sample of $n and the population mean. Different samples produce different sample means, and on average they fall about $se units from `mu = $mu`.";

$sample_narrative = "<b>Population parameter</b>: `mu`, $r_mu_explain. <b>Sample statistic</b>: `bar(x)`, $r_xbar_explain. <b>Conditions for CLT</b>: $r_conditions. Therefore the sampling distribution of `bar(x)` is approximately normal with mean `mu = $mu` and standard error $r_se_compute. <b>Interpretation</b>: $r_se_interpret";

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

/* ---------- 3. Student Rubric ---------- */
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
            <td style="text-align:center;"><b>Parameter vs Statistic</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Identify the population parameter (`mu`) in context.</label></li>
                <li><label><input type="checkbox"> Identify the sample statistic (`bar(x)`) and explain that it varies from sample to sample.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Conditions for CLT</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State that the sample is random.</label></li>
                <li><label><input type="checkbox"> Address independence (population at least 10n).</label></li>
                <li><label><input type="checkbox"> Argue why the sampling distribution of `bar(x)` is approximately normal (population shape OR `n >= 30`).</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Standard Error</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Compute `SE = sigma/sqrt(n)`.</label></li>
                <li><label><input type="checkbox"> Interpret the standard error in the context of the problem (typical distance of `bar(x)` from `mu`).</label></li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </details>
</div>';

/* ---------- 4. Instructor Rubric ---------- */
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
            <td style="text-align:center;"><b>Parameter vs Statistic<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Name the parameter and statistic.
                    <span class="ideal-ans">Target: "`mu = '.$mu.'` is the population mean '.$measurement.' for '.$population.'. `bar(x)` is the mean of a random sample of '.$n.'; it varies sample to sample."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Conditions for CLT<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Random, independent, and approximately normal sampling distribution.
                    <span class="ideal-ans">Target: "The sample is random. The population is at least `10 cdot '.$n.'` so observations are approximately independent. The population is '.$shape.', but `n = '.$n.' >= 30`, so the CLT says the sampling distribution of `bar(x)` is approximately normal."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Standard Error<br>(4 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Compute SE.
                    <span class="ideal-ans">Target: "`SE = sigma/sqrt(n) = '.$sigma.'/sqrt('.$n.') ~~ '.$se.'`"</span></li>
                <li>Interpret SE in context.
                    <span class="ideal-ans">Target: "On average, the sample mean from a random sample of '.$n.' falls about '.$se.' units from the population mean `mu = '.$mu.'`."</span></li>
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
  <p>Suppose '.$topic.'</p>
  <p><b>Essay Prompt:</b><br>
  Describe the sampling distribution of the sample mean `bar(x)`. In your response, be sure to:</p>
  <ul>
    <li>Identify the population parameter and the sample statistic, and explain how they differ.</li>
    <li>Verify the conditions for the sampling distribution of `bar(x)` to be approximately normal (random, independent, and large enough `n` OR a normal population).</li>
    <li>Compute the standard error and interpret what it means in the context of the problem.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
