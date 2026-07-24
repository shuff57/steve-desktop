// === NAME - DESCRIPTION: Choosing the Right Measure of Center - Given a skewed dataset with one extreme outlier, recommend whether to use mean or median to describe a typical value and explain why. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats")

$anstypes = array("essay")
$displayformat[0]='editornopaste'

/* ---------- 1. Dynamic Context Generation ---------- */
$i = rand(0, 2)

// Three contexts: real estate, HR salaries, sports contracts
$contexts = array(
  "a real estate agent describing home prices in a neighborhood",
  "an HR manager reviewing employee salaries at a small company",
  "a sports analyst comparing athlete contracts for a professional team"
)
$topic = $contexts[$i]

$role_labels = array("real estate agent", "HR manager", "sports analyst")
$role = $role_labels[$i]

$value_labels = array("home prices", "annual salaries", "contract values")
$value_label = $value_labels[$i]

$singular_labels = array("home price", "salary", "contract value")
$val_singular = $singular_labels[$i]

$item_labels = array("home", "employee", "athlete")
$item_label = $item_labels[$i]

$subject_labels = array("homes in the neighborhood", "employees at the company", "athletes on the team")
$subject_label = $subject_labels[$i]

// Multiplier converts raw values into context-appropriate dollar amounts
// Real estate: raw * 1000 (e.g., 250 -> $250,000)
// HR salaries: raw * 100 (e.g., 250 -> $25,000)
// Sports: raw * 10000 (e.g., 250 -> $2,500,000)
$multipliers = array(1000, 100, 10000)
$mult = $multipliers[$i]

// Generate 6 typical values and 1 extreme outlier (raw, unitless)
$v1 = rand(24, 26) * 10
$v2 = rand(26, 28) * 10
$v3 = rand(27, 30) * 10
$v4 = rand(28, 31) * 10
$v5 = rand(29, 32) * 10
$v6 = rand(31, 34) * 10
$outlier = rand(110, 160) * 10

// Convert to display values (dollar amounts)
$d1 = $v1 * $mult
$d2 = $v2 * $mult
$d3 = $v3 * $mult
$d4 = $v4 * $mult
$d5 = $v5 * $mult
$d6 = $v6 * $mult
$d_out = $outlier * $mult

// Compute mean and median from raw values, then scale
$total = $v1 + $v2 + $v3 + $v4 + $v5 + $v6 + $outlier
$mean_raw = round($total / 7)
$median_raw = $v4

$mean_val = $mean_raw * $mult
$median_val = $median_raw * $mult

// Build display string listing all 7 values
$val_list = '&#36;' . prettyint($d1) . ', &#36;' . prettyint($d2) . ', &#36;' . prettyint($d3) . ', &#36;' . prettyint($d4) . ', &#36;' . prettyint($d5) . ', &#36;' . prettyint($d6) . ', and &#36;' . prettyint($d_out)

// Narrative variables for the model answer
$r_outlier_impact = 'The extreme value of &#36;' . prettyint($d_out) . ' pulls the mean up to approximately &#36;' . prettyint($mean_val) . ', which is significantly higher than what most ' . $item_label . 's actually show. This makes the mean misleading as a measure of center because a single extreme observation inflates the average.'

$r_recommend_median = 'The median of &#36;' . prettyint($median_val) . ' is a better measure of center for this dataset because it is resistant to outliers. The median reflects the middle value in the sorted data and is not dragged toward extreme observations, giving a more accurate picture of a typical ' . $val_singular . '.'

$r_practical = 'For this reason, the ' . $role . ' should report the median of &#36;' . prettyint($median_val) . ' as the typical ' . $val_singular . '. It better represents what someone would actually encounter, since six of the seven ' . $value_label . ' cluster near this value while only one extreme figure inflates the mean.'

$sample_narrative = '<b>' . $r_outlier_impact . '</b> ' . $r_recommend_median . ' <b>' . $r_practical . '</b>'

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
            <td style="text-align:center;"><b>Outlier Impact</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain how the extreme value in the dataset affects the mean.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Recommendation</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State whether mean or median is the better measure of center for this dataset.</label></li>
                <li><label><input type="checkbox"> Explain why the chosen measure is resistant to outliers.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Practical Interpretation</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Interpret what the recommended measure tells the audience in context.</label></li>
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
            <td style="text-align:center;"><b>Outlier Impact<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain how the extreme value affects the mean.
                    <span class="ideal-ans">Target: "The extreme value of &#36;' . prettyint($d_out) . ' pulls the mean up to approximately &#36;' . prettyint($mean_val) . ', making the mean misleading because one extreme observation inflates the average well above what most ' . $item_label . 's actually show."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Recommendation<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>State whether mean or median is the better measure of center.
                    <span class="ideal-ans">Target: "The median should be used because it is resistant to outliers and better represents a typical value in skewed data."</span></li>
                <li>Explain why the chosen measure resists outliers.
                    <span class="ideal-ans">Target: "The median of &#36;' . prettyint($median_val) . ' only considers the position of the middle value in sorted data, so it is not pulled toward the extreme outlier the way the mean is."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Practical Interpretation<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Interpret what the recommended measure tells the audience in context.
                    <span class="ideal-ans">Target: "The ' . $role . ' should report the median of &#36;' . prettyint($median_val) . ' as the typical ' . $val_singular . ', since six of the seven values cluster near this amount while only one extreme value inflates the mean."</span></li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="full-response-box">
        <span style="color:#2E7D32; font-weight:bold;">Model Narrative Response:</span><br><br>
        ' . $sample_narrative . '
      </div>
    </div>
  </details>
</div>';

/* ---------- 5. Question Text ---------- */
$questiontext = '
<div style="font-family:Arial; font-size:medium; line-height:1.6;">
  <p>Imagine you are ' . $topic . '. The following ' . $value_label . ' were recorded for seven ' . $subject_label . ':</p>
  <p style="text-align:center; font-size:1.1em; font-weight:bold;">' . $val_list . '</p>
  <p><b>Essay Prompt:</b><br>
  Should the ' . $role . ' use the mean or the median to describe a "typical" ' . $val_singular . '? Write a conclusion statement explaining your recommendation and why your chosen measure better represents the data.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>How the extreme value in the dataset affects the mean.</li>
    <li>Whether the mean or median is a better choice and why.</li>
    <li>What the recommended measure tells us about a typical ' . $val_singular . ' in this context.</li>
  </ul>
  ' . $rubricbutton . '
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
