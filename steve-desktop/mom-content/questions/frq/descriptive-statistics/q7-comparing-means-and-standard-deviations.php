// === NAME - DESCRIPTION: Comparing Means and Standard Deviations - Students interpret identical means with different standard deviations to draw conclusions about consistency, variability, and reliability across two groups. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
  "Two fitness instructors are comparing their weekly class attendance over the past 10 weeks",
  "Two baristas at a busy coffee shop are comparing their daily sales numbers over the past month",
  "Two delivery drivers at a shipping company are comparing their daily package counts over the past month"
);
$i = rand(0, count($contexts)-1);
$topic = $contexts[$i];

$group_a_names = array("Instructor A", "Barista A", "Driver A");
$group_b_names = array("Instructor B", "Barista B", "Driver B");
$units = array("students per class", "drinks sold per day", "packages delivered per day");
$measured_things = array("class attendance", "daily sales numbers", "daily delivery counts");
$time_units = array("week", "day", "day");
$time_periods = array("10-week period", "month", "month");

$person_a = $group_a_names[$i];
$person_b = $group_b_names[$i];
$unit = $units[$i];
$measured_thing = $measured_things[$i];
$time_unit = $time_units[$i];
$time_period = $time_periods[$i];

// Randomized numerical values
$shared_mean = rand(28, 45);
$sd_small = rand(2, 4);
$sd_large = rand(7, 12);
// Ensure sd_large is more than double sd_small for a clear contrast
if ($sd_large <= 2*$sd_small) {
  $sd_large = 2*$sd_small + 1;
}

// Narrative variables for the model answer
$r_mean = "Both $person_a and $person_b have the same mean of $shared_mean $unit, which tells us they perform at the same level on average over the $time_period";

$r_sd = "$person_a has a standard deviation of only $sd_small, meaning their $measured_thing stays tightly clustered around the average. $person_b has a standard deviation of $sd_large, so their numbers vary much more widely from $time_unit to $time_unit";

$r_conclusion = "$person_a is the more consistent and reliable of the two because their smaller standard deviation means you can expect results close to $shared_mean on any given $time_unit. $person_b, despite the same average, is much less predictable";

$sample_narrative = "<b>$r_mean</b>. However, <b>$r_sd</b>. In practical terms, <b>$r_conclusion</b>.";

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
            <td style="text-align:center;"><b>Interpreting the Mean<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain what the identical means tell us about both groups\' average performance.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Standard Deviation &amp; Consistency<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Describe what a smaller standard deviation tells us about one group\'s consistency.</label></li>
                <li><label><input type="checkbox"> Describe what a larger standard deviation tells us about the other group\'s variability.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;" class="col-cat-bot"><b>Practical Conclusion<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Draw a conclusion about which group is more reliable and explain why.</label></li>
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
            <td style="text-align:center;"><b>Interpreting the Mean<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain what the identical means tell us about both groups\' average performance.
                    <span class="ideal-ans">Target: "Both groups have the same average of '.$shared_mean.' '.$unit.', so on average they perform at the same level over the '.$time_period.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Standard Deviation &amp; Consistency<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Describe what a smaller standard deviation tells us about one group\'s consistency.
                    <span class="ideal-ans">Target: "'.$person_a.'\'s SD of '.$sd_small.' means their '.$measured_thing.' stays closely clustered around the average, showing high consistency."</span></li>
                <li>Describe what a larger standard deviation tells us about the other group\'s variability.
                    <span class="ideal-ans">Target: "'.$person_b.'\'s SD of '.$sd_large.' means their '.$measured_thing.' varies much more widely, indicating far less consistency."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;" class="col-cat-bot"><b>Practical Conclusion<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Draw a conclusion about which group is more reliable and explain why.
                    <span class="ideal-ans">Target: "'.$person_a.' is more reliable because the smaller standard deviation means their '.$measured_thing.' is more predictable from '.$time_unit.' to '.$time_unit.'."</span></li>
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
  <p>'.$topic.'. '.$person_a.'\'s '.$measured_thing.' averaged '.$shared_mean.' '.$unit.' (SD = '.$sd_small.'), while '.$person_b.'\'s '.$measured_thing.' also averaged '.$shared_mean.' '.$unit.' (SD = '.$sd_large.').</p>
  <p><b>Essay Prompt:</b><br>
  Write a conclusion statement explaining what these statistics tell us about the consistency and reliability of '.$person_a.' and '.$person_b.', even though their means are identical.</p>
  <p>In your response, be sure to address:</p>
  <ul>
    <li>What the identical means tell us about both groups.</li>
    <li>What the difference in standard deviations reveals about consistency and variability.</li>
    <li>Which group is more reliable, and why.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
