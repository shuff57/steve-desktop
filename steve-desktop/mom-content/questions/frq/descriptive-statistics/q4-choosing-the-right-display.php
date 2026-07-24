// === NAME - DESCRIPTION: Choosing the Right Display - Students explain which graphical display (histogram or boxplot) is more appropriate for identifying the shape of a distribution, and describe what unique information each display provides. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$i = rand(0, 2);

// Three contexts: teacher/test scores, health researcher/blood pressure, business analyst/sales
$contexts = array(
  "a teacher",
  "a health researcher",
  "a business analyst"
);
$analyst = $contexts[$i];

$data_labels = array("test score", "blood pressure reading", "daily sales figure");
$data_label = $data_labels[$i];

$data_labels_plural = array("test scores", "blood pressure readings", "daily sales figures");
$data_label_plural = $data_labels_plural[$i];

$subject_labels = array("students", "patients", "business days");
$subject_label = $subject_labels[$i];

$unit_labels = array("points", "mmHg", "dollars");
$unit_label = $unit_labels[$i];

// Randomized sample size and data range per context
$n = rand(40, 80);

$range_lows = array(rand(40, 55), rand(95, 115), rand(120, 200));
$range_highs = array(rand(88, 100), rand(155, 185), rand(850, 1200));
$range_low = $range_lows[$i];
$range_high = $range_highs[$i];

// Scenario sentence
$scenario = $analyst . " has collected " . $data_label_plural . " for " . $n . " " . $subject_label . " ranging from " . $range_low . " to " . $range_high . " " . $unit_label . ". She is trying to decide between creating a histogram or a boxplot to display the data";

// Narrative variables for the model answer
$r_histogram = "A histogram divides data into intervals (bins) and shows the frequency of values in each interval, making it easy to see the overall shape of the distribution, including whether it is symmetric, skewed, or bimodal.";

$r_boxplot = "A boxplot displays the five-number summary (minimum, Q1, median, Q3, maximum) and uses the 1.5 times IQR rule to flag potential outliers. Boxplots are also very effective for comparing distributions across groups side by side.";

$r_recommendation = "For the goal of identifying the shape of the distribution, a histogram is the more appropriate choice because it shows the complete frequency pattern across all intervals. A boxplot summarizes center and spread effectively but does not reveal details about the shape, such as peaks, gaps, or clusters in the data.";

$sample_narrative = "A histogram groups the " . $data_label_plural . " into intervals and displays how many " . $subject_label . " fall into each interval. This makes it easy to see <b>the overall shape of the distribution, including whether it is symmetric, skewed, or bimodal</b>. A boxplot, on the other hand, <b>displays the five-number summary (minimum, Q1, median, Q3, maximum) and highlights potential outliers using the 1.5 times IQR rule</b>. Boxplots are also useful for comparing distributions side by side. For the task of identifying the shape of the distribution, <b>a histogram is the better choice because it reveals the full frequency pattern, while a boxplot hides shape details like peaks, gaps, and clusters</b>.";

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
            <td style="text-align:center;"><b>Histogram</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Describe what a histogram displays about the data.</label></li>
                <li><label><input type="checkbox"> Explain what aspect of the distribution a histogram is especially good at showing.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Boxplot</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Describe what a boxplot displays about the data.</label></li>
                <li><label><input type="checkbox"> Identify a strength of boxplots that histograms do not provide.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Recommendation</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State which display is more appropriate for the given goal and justify your choice.</label></li>
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
            <td style="text-align:center;"><b>Histogram<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Describe what a histogram displays about the data.
                    <span class="ideal-ans">Target: "A histogram divides data into intervals (bins) and shows the frequency of values in each interval."</span></li>
                <li>Explain what aspect of the distribution a histogram is especially good at showing.
                    <span class="ideal-ans">Target: "A histogram makes it easy to see the overall shape of the distribution, including whether it is symmetric, skewed, or bimodal."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Boxplot<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Describe what a boxplot displays about the data.
                    <span class="ideal-ans">Target: "A boxplot displays the five-number summary (minimum, Q1, median, Q3, maximum)."</span></li>
                <li>Identify a strength of boxplots that histograms do not provide.
                    <span class="ideal-ans">Target: "Boxplots highlight potential outliers using the 1.5 times IQR rule and are effective for comparing distributions across groups side by side."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Recommendation<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>State which display is more appropriate for the given goal and justify your choice.
                    <span class="ideal-ans">Target: "For identifying the shape of the distribution, a histogram is the more appropriate choice because it shows the complete frequency pattern across all intervals. A boxplot summarizes center and spread effectively but does not reveal shape details like peaks, gaps, or clusters."</span></li>
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
  <p>Suppose '.$scenario.'.</p>
  <p><b>Essay Prompt:</b><br>
  Which graphical display would be more appropriate for identifying the shape of the distribution and describing the center and spread? Explain what information each display provides differently, and justify your recommendation.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>What a histogram displays and what it is especially good at showing.</li>
    <li>What a boxplot displays and what unique strengths it offers.</li>
    <li>Which display you recommend for the given goal and why.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton