// === NAME - DESCRIPTION: Identifying Non-Normal Distributions - Interpret a Q-Q plot with tail deviations to assess normality and explain what heavy tails indicate. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

$anstypes = array("essay")
$displayformat[0] = 'editornopaste'

/* ---------- 1. Dynamic Context Generation ---------- */

$contexts = array("hours of sleep before an exam", "daily step counts from a fitness tracker", "weekly study hours for college students")
$subjects = array("sleep", "step count", "study hours")
$units = array("hours", "steps", "hours")
$who = array("students", "users", "college students")
$collected_by = array("a psychology researcher surveying students before finals week", "a health researcher analyzing data from fitness tracker users", "an education researcher studying the habits of college students")

$i = rand(0, count($contexts)-1)

$topic = $contexts[$i]
$subj = $subjects[$i]
$unit = $units[$i]
$group = $who[$i]
$researcher = $collected_by[$i]

// Narrative variables for the model answer
$r_middle = "The points in the middle of the Q-Q plot closely follow the diagonal reference line, which tells us that the central portion of the $subj data behaves much like a normal distribution."
$r_conclusion = "Overall, the data is approximately normal but not perfectly normal. The bulk of the distribution fits the bell curve well, but the tails show meaningful departures."
$r_tails = "The deviations at both tails indicate heavy tails, meaning the data contains more extreme values (both high and low) than we would expect from a perfectly normal distribution. In practical terms, there are more $group with unusually high or unusually low $topic than a normal model would predict."

$sample_narrative = "<b>{$r_middle}</b> <b>{$r_conclusion}</b> <b>{$r_tails}</b>"

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
</script>'

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
            <td style="text-align:center;"><b>Reading the Q-Q Plot<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Describe what the middle section of the Q-Q plot shows.</label></li>
                <li><label><input type="checkbox"> Describe what happens at both tails of the Q-Q plot.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Normality Conclusion<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State whether the data is normal, approximately normal, or not normal.</label></li>
                <li><label><input type="checkbox"> Justify your conclusion by connecting it to the Q-Q plot pattern.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;" class="col-cat-bot"><b>Interpreting Tail Deviations<br>(4 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain what tail deviations indicate about the distribution.</label></li>
                <li><label><input type="checkbox"> Connect the tail pattern to what it means in practical terms for the data.</label></li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </details>
</div>'

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
            <td style="text-align:center;"><b>Reading the Q-Q Plot<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Describe what the middle section of the Q-Q plot shows.
                    <span class="ideal-ans">Target: "The points in the middle of the Q-Q plot closely follow the diagonal reference line, indicating that the central portion of the data aligns well with a normal distribution."</span></li>
                <li>Describe what happens at both tails of the Q-Q plot.
                    <span class="ideal-ans">Target: "The points deviate noticeably from the diagonal line at both the lower and upper tails."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Normality Conclusion<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>State whether the data is normal, approximately normal, or not normal.
                    <span class="ideal-ans">Target: "The data is approximately normal but not perfectly normal."</span></li>
                <li>Justify your conclusion by connecting it to the Q-Q plot pattern.
                    <span class="ideal-ans">Target: "The close fit in the middle shows the bulk of the data follows a bell curve, but the tail departures mean a strict normal model does not capture the full picture."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;" class="col-cat-bot"><b>Interpreting Tail Deviations<br>(4 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain what tail deviations indicate about the distribution.
                    <span class="ideal-ans">Target: "Deviations at both tails indicate heavy tails, meaning the distribution has more extreme values than a normal distribution would predict."</span></li>
                <li>Connect the tail pattern to what it means in practical terms for the data.
                    <span class="ideal-ans">Target: "In context, there are more ' . $group . ' with unusually high or unusually low ' . $topic . ' than a normal model would suggest."</span></li>
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
</div>'

/* ---------- 5. Question Text ---------- */

$questiontext = '
<div style="font-family:Arial; font-size:medium; line-height:1.6;">
  <p>Imagine that ' . $researcher . ' collected data on <b>' . $topic . '</b>. To check whether the data is normally distributed, the researcher creates a <b>Q-Q (quantile-quantile) plot</b>.</p>
  <p>In the Q-Q plot, the points closely follow the diagonal reference line through the middle of the plot, but they deviate noticeably from the line at both the lower and upper tails.</p>
  <p><b>Essay Prompt:</b><br>
  Write a conclusion statement describing what this Q-Q plot pattern suggests about whether the ' . $subj . ' data is normally distributed. Then explain what the deviations at both tails specifically indicate about the data.</p>
  <p>In your response, be sure to address:</p>
  <ul>
    <li>What the middle section of the Q-Q plot tells you about the data.</li>
    <li>Whether you would consider this data normal, approximately normal, or not normal, and why.</li>
    <li>What the tail deviations reveal about the distribution and what that means in practical terms.</li>
  </ul>
  ' . $rubricbutton . '
</div>'

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
