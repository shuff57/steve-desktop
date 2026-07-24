// === NAME - DESCRIPTION: Classification and Justification - Students classify three variables as categorical or quantitative (discrete or continuous), justify each classification, and explain why proper variable classification matters when choosing statistical methods. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
  "a hospital records data on each patient admitted to the emergency room",
  "a retail store tracks information about each customer transaction",
  "a sports league collects data on each player during a season"
);
$i = rand(0, count($contexts)-1);
$topic = $contexts[$i];

// Variable names for each context (one categorical, one discrete, one continuous)
$var1_names = array("blood type", "payment method", "team jersey color");
$var2_names = array("number of hospital visits in the past year", "number of items purchased", "number of goals scored per game");
$var3_names = array("patient weight in pounds", "total transaction amount in dollars", "player height in inches");

$var1_name = $var1_names[$i];
$var2_name = $var2_names[$i];
$var3_name = $var3_names[$i];

// Classifications
$var1_type = "categorical";
$var2_type = "quantitative (discrete)";
$var3_type = "quantitative (continuous)";

// Justifications for each variable
$var1_reasons = array(
  "it places patients into groups (A, B, AB, O) rather than measuring a numerical quantity",
  "it places transactions into groups (cash, credit, debit, etc.) rather than measuring a numerical quantity",
  "it places players into groups based on color (red, blue, green, etc.) rather than measuring a numerical quantity"
);
$var2_reasons = array(
  "it counts whole visits, so the possible values are 0, 1, 2, 3, and so on, with no values in between",
  "it counts whole items, so the possible values are 1, 2, 3, and so on, with no values in between",
  "it counts whole goals, so the possible values are 0, 1, 2, 3, and so on, with no values in between"
);
$var3_reasons = array(
  "weight can take any value within a range (for example, 154.3 pounds) and is not limited to whole numbers",
  "dollar amounts can take any value within a range (for example, $47.83) and are measured rather than counted",
  "height can take any value within a range (for example, 72.5 inches) and is not limited to whole numbers"
);

$var1_reason = $var1_reasons[$i];
$var2_reason = $var2_reasons[$i];
$var3_reason = $var3_reasons[$i];

// Narrative variables for the model answer
$r_classifications = "$var1_name is $var1_type because $var1_reason. $var2_name is $var2_type because $var2_reason. $var3_name is $var3_type because $var3_reason";

$r_importance = "correctly classifying variables matters because different types of variables require different statistical methods. For categorical variables, we use tools like bar charts and proportions, while quantitative variables call for histograms, means, and standard deviations. Using the wrong method for a variable type can lead to misleading results or meaningless calculations";

$sample_narrative = "In this scenario, <b>{$var1_name}</b> is <b>$var1_type</b> because $var1_reason. <b>{$var2_name}</b> is <b>$var2_type</b> because $var2_reason. Finally, <b>{$var3_name}</b> is <b>$var3_type</b> because $var3_reason. Proper variable classification matters because <b>$r_importance</b>.";

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
            <td style="text-align:center;"><b>Variable Classification</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Classify each of the three variables as categorical or quantitative.</label></li>
                <li><label><input type="checkbox"> For any quantitative variables, identify whether each is discrete or continuous.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Justification</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain the reasoning behind each classification.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Why Classification Matters</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain why correctly classifying variables is important when choosing statistical methods.</label></li>
                <li><label><input type="checkbox"> Give at least one example of how the method differs for categorical vs. quantitative data.</label></li>
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
            <td style="text-align:center;"><b>Variable Classification<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Classify each of the three variables as categorical or quantitative.
                    <span class="ideal-ans">Target: "'.$var1_name.' is categorical. '.$var2_name.' is quantitative (discrete). '.$var3_name.' is quantitative (continuous)."</span></li>
                <li>For quantitative variables, identify whether each is discrete or continuous.
                    <span class="ideal-ans">Target: "Discrete variables take on countable whole-number values, while continuous variables can take any value within a range, including decimals."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Justification<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain the reasoning behind each classification.
                    <span class="ideal-ans">Target: "'.$var1_name.' is categorical because '.$var1_reason.'. '.$var2_name.' is discrete because '.$var2_reason.'. '.$var3_name.' is continuous because '.$var3_reason.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Why Classification Matters<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain why correctly classifying variables is important when choosing statistical methods.
                    <span class="ideal-ans">Target: "Variable classification matters because different types of variables require different statistical methods. Categorical data is summarized with proportions, frequencies, and bar charts, while quantitative data uses means, standard deviations, and histograms."</span></li>
                <li>Give at least one example of how the method differs for categorical vs. quantitative data.
                    <span class="ideal-ans">Target: "For example, computing a mean of '.$var1_name.' would be meaningless, while computing a mean of '.$var3_name.' gives useful information about the typical value. Using the wrong method for a variable type can produce misleading or nonsensical results."</span></li>
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
  <p>Suppose '.$topic.'. Three variables are recorded for each individual: <b>'.$var1_name.'</b>, <b>'.$var2_name.'</b>, and <b>'.$var3_name.'</b>.</p>
  <p><b>Essay Prompt:</b><br>
  Classify each of the three variables as categorical or quantitative. If a variable is quantitative, also identify whether it is discrete or continuous. Justify each classification, and then explain why proper variable classification matters when choosing statistical methods.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>The classification of each variable (categorical, quantitative discrete, or quantitative continuous).</li>
    <li>A clear reason why each variable fits its classification.</li>
    <li>Why it matters to correctly classify variables before selecting a statistical method, with at least one specific example.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton