// === NAME - DESCRIPTION: Reading a Data Set With a Gap - Students work from twenty recorded values, describe how the values are arranged and where the gaps are, explain what that arrangement suggests about the subjects or the data collection, and recommend a specific next step ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$i = rand(0, 2);

// Each context carries a real, pre-built data set of 20 recorded values, in the order they
// were recorded. These replaced an earlier version of this question that described a histogram
// in words and asked the student to name the shape. That version was a vocabulary trap: a
// student could reason correctly and still score zero for never writing that one word,
// and a 0/14 with three retries on the group test is what forced the rewrite. Nothing here
// asks the student to name a distribution type, and the word does not appear in the prompt,
// the checklist, or the model response.
//
// The three data sets are built to the same shape on purpose: ten values in a low band, ten in
// a high band, and an empty stretch in between with nothing at all. The gap is the whole
// point of the question, and it is visible straight off the page, so noticing it is real
// analytical work rather than recall. The band figures below are the verified bounds of each
// set, not estimates, and they are what the grading targets quote.
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

// The 20 values exactly as recorded.
$data_vals = array(
  "312, 308, 319, 305, 321, 299, 315, 310, 318, 302, 587, 594, 579, 601, 588, 596, 583, 590, 577, 605",
  "8, 11, 7, 12, 9, 10, 6, 13, 8, 11, 34, 38, 31, 36, 33, 40, 29, 35, 37, 32",
  "58, 62, 55, 66, 60, 64, 57, 61, 63, 59, 84, 88, 81, 86, 83, 89, 79, 85, 87, 82"
);
$valueList = $data_vals[$i];

// Verified band bounds and the width of the empty stretch in the middle.
$low_desc = array("299 to 321", "6 to 13", "55 to 66");
$high_desc = array("577 to 605", "29 to 40", "79 to 89");
$gap_desc = array("322 to 576", "14 to 28", "67 to 78");
$low_band = $low_desc[$i];
$high_band = $high_desc[$i];
$empty_middle = $gap_desc[$i];

$subgroup_explanations = array(
  "participants who had used similar software before and processed the task quickly, alongside participants encountering it for the first time",
  "employees who live near the office and employees who travel in from farther away",
  "students who had prepared well and scored highly, alongside students who struggled with the material"
);
$subgroup_explanation = $subgroup_explanations[$i];

$investigation_recs = array(
  "record each participant's prior experience with this software and compare the two groups separately",
  "record where each employee lives and how they travel, then compare the two groups separately",
  "record which students attended the review sessions and compare the two groups separately"
);
$investigation_rec = $investigation_recs[$i];

$scenario = "Twenty " . $topic . " were recorded, listed below in the order they were recorded.";

/* Narrative for the model response. Every sentence is something a student can earn by looking
   at the numbers; nothing depends on naming a distribution type. */
$r_pattern = "Sorted from smallest to largest, the " . $data_label_plural . " do not spread out evenly across the range. They collect into two tight groups: ten of them fall between " . $low_band . " " . $unit_label . ", and the other ten fall between " . $high_band . " " . $unit_label . ". Between " . $empty_middle . " " . $unit_label . " there is not a single value at all, so the middle of the data is completely empty rather than thin.";

$r_explanation = "An empty stretch that wide in the middle is hard to explain if every " . $subject_label . " were behaving the same way. It points to the " . $subject_label . " not being one uniform group: the natural reading is two subpopulations mixed together in the same list, most likely " . $subgroup_explanation . ". Whatever separates them is strong enough to keep the two sets of values from overlapping at all.";

$r_investigation = "To test that reading, " . $investigation_rec . ". If the split is real, each group should produce its own tight cluster on its own, and the empty middle should disappear. If the full list still shows the same gap after the groups are separated, then the gap points at something in how the data were collected rather than at two kinds of " . $subject_label . ".";

$sample_narrative = "<b>" . $r_pattern . "</b> " . $r_explanation . " <b>" . $r_investigation . "</b>";

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
// Phrased as observations of the data. No item requires a particular label for the shape, so a
// student who describes the two groups and the gap in their own words can earn every point.
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
            <td style="text-align:center;"><b>Reading the Data</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Describe how the values are arranged: which values are low, which are high, and how many of each.</label></li>
                <li><label><input type="checkbox"> Point out that part of the range has no values in it at all, and say how wide that empty stretch is.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>What It Suggests</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain what an empty stretch in the middle implies about the people in the data.</label></li>
                <li><label><input type="checkbox"> Give a plausible reason tied to this specific scenario, not a generic one.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Further Investigation</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Recommend a specific next step that would confirm or rule out your explanation, and say what you expect to see if you are right.</label></li>
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
            <td style="text-align:center;"><b>Reading the Data<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Describe how the values are arranged: which values are low, which are high, and how many of each.
                    <span class="ideal-ans">Target: "Ten of the values fall between '.$low_band.' '.$unit_label.' and the other ten fall between '.$high_band.' '.$unit_label.'."</span></li>
                <li>Point out that part of the range has no values in it at all, and say how wide that empty stretch is.
                    <span class="ideal-ans">Target: "There are no values at all between '.$empty_middle.' '.$unit_label.', so the middle of the data is completely empty rather than just thin."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>What It Suggests<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain what an empty stretch in the middle implies about the people in the data.
                    <span class="ideal-ans">Target: "The '.$subject_label.' are probably not one uniform group, because a gap that wide would not appear if everyone were behaving the same way."</span></li>
                <li>Give a plausible reason tied to this specific scenario, not a generic one.
                    <span class="ideal-ans">Target: "Two subpopulations are mixed together in the same list, most likely '.$subgroup_explanation.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Further Investigation<br>(4 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Recommend a specific next step that would confirm or rule out your explanation, and say what you expect to see if you are right.
                    <span class="ideal-ans">Target: "'.$investigation_rec.'. Each group should then form its own tight cluster and the empty middle should disappear; if it does not, the gap points at the data collection rather than at two kinds of '.$subject_label.'."</span></li>
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
  Write a conclusion statement about what this pattern in the '.$data_label_plural.' suggests about the '.$subject_label.' or about the data collection process, and explain what further investigation you would recommend.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>How the values are arranged: where the low ones and the high ones fall, and whether any part of the range is empty.</li>
    <li>What that arrangement suggests about the '.$subject_label.', and a plausible reason for it in this specific scenario.</li>
    <li>A specific recommendation for how to investigate further, and what you would expect to find if your explanation is correct.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
