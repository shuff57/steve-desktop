// === NAME - DESCRIPTION: Empirical Rule - Use the 68-95-99.7 rule to determine what percentage of normally distributed values fall within a given range and interpret the result in context. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$i = rand(0, 2);

$contexts = array(
  "daily water consumption",
  "daily commute time",
  "plant height"
);
$topic = $contexts[$i];

$units = array("liters", "minutes", "centimeters");
$unit = $units[$i];

$items = array("adults in the study", "commuters in the area", "plants in the experiment");
$item = $items[$i];

$descriptors = array(
  "A researcher studying hydration habits measured the daily water consumption of adults in a health study.",
  "A transportation analyst collected data on the daily commute times of workers in a metropolitan area.",
  "A botanist recorded the heights of plants grown in a controlled greenhouse experiment."
);
$desc = $descriptors[$i];

// Per-context randomized mean and standard deviation
if ($i == 0) {
  $mu = rrand(2.0, 3.5, 0.1);
  $sigma = rrand(0.5, 1.0, 0.1);
} elseif ($i == 1) {
  $mu = rand(25, 45);
  $sigma = rand(5, 10);
} else {
  $mu = rand(30, 60);
  $sigma = rand(5, 12);
}

// Compute the 2-SD bounds (always a 95% interval)
$lower = $mu - 2*$sigma;
$upper = $mu + 2*$sigma;

// Narrative variables for model answer
$r_range = "The range from " . $lower . " to " . $upper . " " . $unit . " spans exactly 2 standard deviations from the mean. We can verify: " . $mu . " - 2(" . $sigma . ") = " . $lower . " and " . $mu . " + 2(" . $sigma . ") = " . $upper . ".";

$r_rule = "Since the data is normally distributed, we apply the empirical rule (the 68-95-99.7 rule). This rule tells us that approximately 68% of values fall within 1 SD of the mean, 95% within 2 SDs, and 99.7% within 3 SDs. Because our range covers exactly 2 standard deviations in each direction, approximately 95% of values fall in this interval.";

$r_interpret = "Therefore, approximately 95% of " . $item . " have " . $topic . " between " . $lower . " and " . $upper . " " . $unit . ".";

$sample_narrative = "<b>" . $r_range . "</b> " . $r_rule . " <b>" . $r_interpret . "</b>";

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
            <td style="text-align:center;"><b>Identifying the Range</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Determine how many standard deviations the given range extends from the mean.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Applying the Empirical Rule</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State the relevant percentage from the 68-95-99.7 rule.</label></li>
                <li><label><input type="checkbox"> Show the arithmetic that connects the mean, standard deviation, and the given bounds.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Interpreting the Result</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain what the percentage means in the context of the scenario.</label></li>
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
            <td style="text-align:center;"><b>Identifying the Range<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Determine how many standard deviations the given range extends from the mean.
                    <span class="ideal-ans">Target: "The lower bound is '.$mu.' - 2('.$sigma.') = '.$lower.' and the upper bound is '.$mu.' + 2('.$sigma.') = '.$upper.', so the range covers exactly 2 standard deviations in each direction from the mean."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Applying the Empirical Rule<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>State the relevant percentage from the 68-95-99.7 rule.
                    <span class="ideal-ans">Target: "The empirical rule states that approximately 95% of values in a normal distribution fall within 2 standard deviations of the mean."</span></li>
                <li>Show the arithmetic connecting the mean, standard deviation, and bounds.
                    <span class="ideal-ans">Target: "'.$mu.' - 2('.$sigma.') = '.$lower.' and '.$mu.' + 2('.$sigma.') = '.$upper.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Interpreting the Result<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain what the percentage means in context.
                    <span class="ideal-ans">Target: "Approximately 95% of '.$item.' have '.$topic.' between '.$lower.' and '.$upper.' '.$unit.'."</span></li>
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
  <p>'.$desc.' The data is approximately normally distributed with a mean of <b>'.$mu.' '.$unit.'</b> and a standard deviation of <b>'.$sigma.' '.$unit.'</b>.</p>
  <p><b>Essay Prompt:</b><br>
  Using the empirical rule (also known as the 68-95-99.7 rule), determine what percentage of '.$item.' have '.$topic.' between <b>'.$lower.'</b> and <b>'.$upper.' '.$unit.'</b>. Show your work and explain your reasoning.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>How the given range relates to the mean and standard deviation.</li>
    <li>Which part of the empirical rule applies and why.</li>
    <li>What the result means in the context of this scenario.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
