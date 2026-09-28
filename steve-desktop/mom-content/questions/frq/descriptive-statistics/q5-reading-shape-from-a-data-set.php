// === NAME - DESCRIPTION: Reading Shape From a Data Set - Students work from twenty recorded values, decide whether the set is skewed left, skewed right, or roughly symmetric, say what that implies for the mean against the median, and recommend one thing to check next ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$i = rand(0, 2);

// Each context carries a real, pre-built data set of 20 recorded values, in the order they
// were recorded, and each set is built to one shape: symmetric, skewed left, skewed right.
// Those are the three shapes chapter 2 actually teaches (book sections 2.5.5 to 2.5.7), so
// the student is asked to recognise something the course has covered rather than a shape that
// was never taught here.
//
// An earlier version of this question described a histogram in words and asked the student to
// name its shape. That was a vocabulary trap: a student could reason correctly and still score
// zero for never writing the word, and a 0/14 over three retries is what forced the rewrite.
// A second attempt handed over two tight clusters with a gap, which turned out to be the wrong
// call: two equal clusters leave the mean and the median a fraction of a unit apart, so the
// comparison that matters here cannot be graded. The three sets below were each tuned until
// the mean-to-median relationship is unambiguous in the intended direction.
//
// Every figure quoted in the grading targets (mean, median, min, max) was computed from the
// printed list and checked, not estimated. No set produces an outlier under the 1.5 x IQR rule.
$contexts = array(
  "reaction times (in milliseconds) for a computer-based attention task",
  "daily commute times (in minutes) for employees at a large company",
  "exam scores (out of 100) for a statistics class"
);
$topic = $contexts[$i];

$subject_labels = array("participants", "employees", "students");
$subject_label = $subject_labels[$i];

$data_labels_plural = array("reaction times", "commute times", "exam scores");
$data_label_plural = $data_labels_plural[$i];

$unit_labels = array("milliseconds", "minutes", "points");
$unit_label = $unit_labels[$i];

$scenario = "Twenty " . $topic . " were recorded, listed below in the order they were recorded.";

// The 20 values exactly as recorded.
$data_vals = array(
  "352, 361, 375, 388, 395, 402, 408, 415, 429, 440, 447, 456, 362, 379, 391, 399, 405, 412, 421, 436",
  "26, 31, 24, 34, 28, 36, 30, 22, 33, 27, 38, 35, 39, 32, 37, 6, 9, 12, 17, 21",
  "58, 62, 55, 66, 60, 64, 57, 61, 63, 59, 71, 68, 74, 65, 88, 95, 79, 84, 92, 69"
);
$valueList = $data_vals[$i];

// The shape of each set, and the mean and median computed from the printed list above.
$shape_names = array("roughly symmetric", "skewed left", "skewed right");
$shape = $shape_names[$i];

$means = array("403.65", "26.85", "69.5");
$medians = array("403.5", "29", "65.5");
$mean_val = $means[$i];
$median_val = $medians[$i];

$mins = array("352", "6", "55");
$maxs = array("456", "39", "95");
$min_val = $mins[$i];
$max_val = $maxs[$i];

// How the shape reads in this context, in plain words, for the model response.
$shape_notes = array(
  "the values thin out at both ends by about the same amount, with roughly as much spread below the middle as above it",
  "most of the values sit in the upper part of the range, with a thin stretch of much lower values hanging off the low end",
  "most of the values sit in the lower part of the range, with a thin stretch of much higher values running off the high end"
);
$shape_note = $shape_notes[$i];

// Which way the mean sits against the median, in words the model response can use.
$compare_notes = array(
  "the mean and the median land on top of each other, which is what a symmetric shape should do",
  "the mean comes out below the median, because the low values in the tail drag an average down while the median barely moves",
  "the mean comes out above the median, because the high values in the tail drag an average up while the median barely moves"
);
$compare_note = $compare_notes[$i];

$investigation_recs = array(
  "check whether the fastest and slowest responders differ in some way the timing did not capture, such as familiarity with the task",
  "check whether the short commutes belong to people who travel a different way or work different hours",
  "check whether the high scores belong to students who prepared differently or were assessed on different material"
);
$investigation_rec = $investigation_recs[$i];

/* Narrative for the model response. Everything in it is something a student can earn by
   looking at the numbers; no sentence depends on guessing the grader's vocabulary. */
$r_pattern = "Sorted from smallest to largest, the " . $data_label_plural . " run from " . $min_val . " to " . $max_val . " " . $unit_label . ", and the way they are spread makes the set " . $shape . ": " . $shape_note . ".";

$r_compare = "Working from the list, the mean is " . $mean_val . " and the median is " . $median_val . ", so " . $compare_note . ". A single typical value is a poor summary of this set, because the tail is part of the data and not a few odd measurements to be set aside.";

$r_investigation = "Before reporting a single typical figure for these " . $data_label_plural . ", I would " . $investigation_rec . ". If the people behind the tail turn out to be different in some way that was not recorded, then the set is really two populations mixed together and the " . $subject_label . " should be summarised separately rather than pooled into one number.";

$sample_narrative = "<b>" . $r_pattern . "</b> " . $r_compare . " <b>" . $r_investigation . "</b>";

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
// Phrased as things the student can do with the numbers in front of them. No item turns on
// producing one particular word: naming the shape is one way to earn the first two points, and
// describing the spread and the mean-to-median comparison earns them just as well.
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
            <td style="text-align:center;"><b>Shape of the Data</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Say which way the values are spread, or that they are spread evenly both ways, and point to the values that show it.</label></li>
                <li><label><input type="checkbox"> Give the smallest and largest values, and describe which end of the range is thinner.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Mean and Median</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Work out the mean and the median of the data set.</label></li>
                <li><label><input type="checkbox"> Say which of the two is larger, and explain what about the spread produces that.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>What It Means</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain why one typical value is a poor summary of this set.</label></li>
                <li><label><input type="checkbox"> Recommend one specific thing to check next, tied to this scenario.</label></li>
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
            <td style="text-align:center;"><b>Shape of the Data<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Say which way the values are spread, or that they are spread evenly both ways, and point to the values that show it.
                    <span class="ideal-ans">Target: "This set is '.$shape.'. The values run from '.$min_val.' to '.$max_val.' '.$unit_label.', and '.$shape_note.'."</span></li>
                <li>Give the smallest and largest values, and describe which end of the range is thinner.
                    <span class="ideal-ans">Target: "Smallest '.$min_val.', largest '.$max_val.'; the thinning end is where a handful of values sit well away from the rest."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Mean and Median<br>(5 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Work out the mean and the median of the data set.
                    <span class="ideal-ans">Target: "mean = '.$mean_val.', median = '.$median_val.' (accept either rounding of the mean)."</span></li>
                <li>Say which of the two is larger, and explain what about the spread produces that.
                    <span class="ideal-ans">Target: "For this set '.$compare_note.' A correct answer that reaches the same comparison by describing the thin end of the range earns full credit even without the word skew."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>What It Means<br>(4 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain why one typical value is a poor summary of this set.
                    <span class="ideal-ans">Target: "The tail is part of the data rather than a few stray measurements, so reporting only a mean or only a median hides how far the '.$subject_label.' really differ."</span></li>
                <li>Recommend one specific thing to check next, tied to this scenario.
                    <span class="ideal-ans">Target: "'.$investigation_rec.'."</span></li>
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
  <p>'.$scenario.'</p>
  <p style="margin:0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; font-family:ui-monospace,Menlo,Consolas,monospace; font-size:15px; line-height:1.8;">'.$valueList.'</p>
  <p style="margin:12px 0 0 0; font-size:15px; color:#444;">The values are in '.$unit_label.' and are listed exactly as they were recorded, so they are not in order.</p>
  <p><b>Essay Prompt:</b><br>
  Write a conclusion statement about the shape of these '.$data_label_plural.' and what that shape means for anyone trying to summarise them with a single typical value.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>Which way the values are spread, or whether they are spread evenly both ways, and which end of the range is thinner.</li>
    <li>The mean and the median of this data set, which of the two is larger, and what about the spread produces that.</li>
    <li>Why one typical value is a poor summary of this set, and one specific thing you would check next.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
