// === NAME - DESCRIPTION: Five-Number Summary and Outliers - Students use the 1.5(IQR) rule to determine whether a maximum value is an outlier, show their calculations, and interpret the result in context. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$i = rand(0, 2);

// Three scenarios for what the dataset represents
$contexts = array(
  "the ages (in years) of customers at a retail store",
  "the daily step counts (in hundreds) of fitness app users",
  "the monthly electricity bills (in dollars) for households in a neighborhood"
);
$context = $contexts[$i];

// Short labels for referencing the data type
$data_labels = array("customer ages", "step counts", "electricity bills");
$data_label = $data_labels[$i];

// Context-specific outlier interpretations (lowercase start for sentence embedding)
$interpretations = array(
  "this could represent an unusually elderly customer whose age is far beyond the typical range, possibly suggesting the store occasionally attracts a very different demographic than its usual shoppers.",
  "this could represent a user with an exceptionally active lifestyle, such as a competitive athlete or someone with a physically demanding job, whose activity level is far above the typical user.",
  "this could represent a household with unusually high energy consumption, possibly due to a much larger home, extreme climate control needs, or an inefficient appliance driving up costs."
);
$interpretation = $interpretations[$i];

// Randomized five-number summary (guaranteed: max IS an outlier)
$q1 = rand(15, 25);
$med = $q1 + rand(6, 10);
$q3 = $med + rand(6, 10);
$iqr = $q3 - $q1;
$upper_fence = $q3 + 1.5 * $iqr;
$multiplier = rand(3, 5);
$max_val = $q3 + $multiplier * $iqr;
$min_val = rand(5, 12);

// Narrative building blocks for model answer
$r_iqr = "IQR = Q3 - Q1 = $q3 - $q1 = $iqr";
$r_fence = "Upper fence = Q3 + 1.5(IQR) = $q3 + 1.5($iqr) = $upper_fence";
$r_outlier = "Since the maximum value ($max_val) is greater than the upper fence ($upper_fence), the maximum IS an outlier by the 1.5(IQR) rule";
$r_context = "In the context of $data_label, $interpretation";

$sample_narrative = "First, <b>$r_iqr</b>. Next, <b>$r_fence</b>. <b>$r_outlier</b>. $r_context An outlier this extreme should be investigated to determine whether it reflects a data entry error or a genuinely unusual observation.";

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
            <td style="text-align:center;"><b>IQR &amp; Upper Fence</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Calculate the IQR from the given Q1 and Q3 values.</label></li>
                <li><label><input type="checkbox"> Calculate the upper fence using the 1.5(IQR) rule.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Outlier Classification</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Compare the maximum value to the upper fence.</label></li>
                <li><label><input type="checkbox"> State whether the maximum qualifies as an outlier.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Contextual Interpretation</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain what the outlier might suggest about the data in context.</label></li>
                <li><label><input type="checkbox"> Discuss whether this extreme value should be investigated further.</label></li>
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
            <td style="text-align:center;"><b>IQR &amp; Upper Fence<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Calculate the IQR from Q1 and Q3.
                    <span class="ideal-ans">Target: "IQR = Q3 - Q1 = '.$q3.' - '.$q1.' = '.$iqr.'"</span></li>
                <li>Calculate the upper fence using the 1.5(IQR) rule.
                    <span class="ideal-ans">Target: "Upper fence = Q3 + 1.5(IQR) = '.$q3.' + 1.5('.$iqr.') = '.$upper_fence.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Outlier Classification<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Compare the maximum value to the upper fence.
                    <span class="ideal-ans">Target: "Max = '.$max_val.', which is greater than the upper fence of '.$upper_fence.'."</span></li>
                <li>State whether the maximum qualifies as an outlier.
                    <span class="ideal-ans">Target: "Because '.$max_val.' &gt; '.$upper_fence.', the maximum IS an outlier by the 1.5(IQR) rule."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Contextual Interpretation<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain what the outlier might suggest about the data.
                    <span class="ideal-ans">Target: "'.$r_context.'"</span></li>
                <li>Discuss whether the extreme value should be investigated.
                    <span class="ideal-ans">Target: "An outlier this extreme should be investigated to determine whether it reflects a data entry error or a genuinely unusual observation."</span></li>
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
  <p>A dataset representing '.$context.' has the following five-number summary:</p>
  <p style="text-align:center; font-size:1.05em; margin:0.5em 0;">
    <b>Min</b> = '.$min_val.' &nbsp;&nbsp;&nbsp; <b>Q1</b> = '.$q1.' &nbsp;&nbsp;&nbsp; <b>Median</b> = '.$med.' &nbsp;&nbsp;&nbsp; <b>Q3</b> = '.$q3.' &nbsp;&nbsp;&nbsp; <b>Max</b> = '.$max_val.'
  </p>
  <p><b>Essay Prompt:</b><br>
  Using the 1.5(IQR) rule, determine whether the maximum value ('.$max_val.') is an outlier. Show your calculations and explain what this outlier might suggest about the data.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>The IQR and upper fence calculations, showing your work.</li>
    <li>Whether the maximum value qualifies as an outlier, with a clear comparison.</li>
    <li>What this outlier might suggest in the context of the dataset.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
