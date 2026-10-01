// === NAME - DESCRIPTION: Comparing Means and Standard Deviations - Students interpret identical means with different standard deviations to draw conclusions about consistency and spread across two groups. ===
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
// Singular noun phrases: these drop into "X's <thing> averaged", "the <thing> stays close" and "their <thing> varies".
$measured_things = array("class attendance", "sales count", "delivery count");
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
$r_mean = "Both $person_a and $person_b have the same mean of $shared_mean $unit, so on average they perform at the same level over the $time_period";

$r_sd = "$person_a has a standard deviation of only $sd_small, meaning their $measured_thing stays tightly clustered around the average. $person_b has a standard deviation of $sd_large, so their numbers vary much more widely from $time_unit to $time_unit";

$r_conclusion = "$person_a is the more consistent of the two because the smaller standard deviation means you can expect results close to $shared_mean from $time_unit to $time_unit. $person_b has the same average but is much less predictable";

// Targets shown to the grader (double-quoted so apostrophes need no escaping)
$t_mean = "Both have the same average of $shared_mean $unit, so on average $person_a and $person_b come out the same over the $time_period.";
$t_sd_each = "$person_a has a small SD of $sd_small, so the $measured_thing stays close to the average of $shared_mean. $person_b has a large SD of $sd_large, so the $measured_thing is spread widely around $shared_mean.";
$t_sd_compare = "$person_a is the more consistent of the two (SD of $sd_small compared with $sd_large for $person_b). The smaller the standard deviation, the more consistent the values.";
$t_concl = "$person_a is the one to plan around: the smaller standard deviation keeps the $measured_thing close to the average from $time_unit to $time_unit, while $person_b's results can land far from it.";
$t_context = "From $time_unit to $time_unit, expect $person_a to land near $shared_mean $unit, while $person_b could come in well above or well below that.";

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
      <table class="rubric-table">
        <tbody>
          <tr>
            <th class="col-header">Category</th>
            <th class="col-check">What to include</th>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Interpreting the Mean<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Compare the two means and say what they tell you about a typical '.$time_unit.' for each person.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Standard Deviation and Consistency<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> For each person, explain what their standard deviation tells you about their '.$measured_thing.'.</label></li>
                <li><label><input type="checkbox"> Say which person is more consistent and which is less consistent, and point to the numbers that show it.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;" class="col-cat-bot"><b>Practical Conclusion<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Write a conclusion comparing the two people, and say which statistic you based it on.</label></li>
                <li><label><input type="checkbox"> Say what your conclusion means in this situation: what would someone expect from each person from '.$time_unit.' to '.$time_unit.'?</label></li>
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
                <li>Compare the two means and explain what they say about a typical '.$time_unit.' for each person.
                    <span class="ideal-ans">Target: "'.$t_mean.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Standard Deviation and Consistency<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain what each standard deviation says about the '.$measured_thing.'.
                    <span class="ideal-ans">Target: "'.$t_sd_each.'"</span></li>
                <li>Identify which person is more consistent and which is less, citing the standard deviations.
                    <span class="ideal-ans">Target: "'.$t_sd_compare.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;" class="col-cat-bot"><b>Practical Conclusion<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>State a conclusion comparing the two people and name the statistic your conclusion rests on.
                    <span class="ideal-ans">Target: "'.$t_concl.'"</span></li>
                <li>Explain what the conclusion means for what to expect in this situation.
                    <span class="ideal-ans">Target: "'.$t_context.'"</span></li>
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
  Use the means and standard deviations to write a conclusion comparing '.$person_a.' and '.$person_b.'.</p>
  <p>In your response, be sure to address:</p>
  <ul>
    <li>How the two means compare, and what that says about a typical '.$time_unit.'.</li>
    <li>How the two standard deviations compare, what each one says about the '.$measured_thing.', and which person is more consistent.</li>
    <li>What your conclusion means for what to expect from each person from '.$time_unit.' to '.$time_unit.'.</li>
    <li>How the two standard deviations compare, and what each one says about the '.$measured_thing.'.</li>
    <li>Which person is more consistent, and why.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
