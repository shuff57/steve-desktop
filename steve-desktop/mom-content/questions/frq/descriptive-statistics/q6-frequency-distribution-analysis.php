// === NAME - DESCRIPTION: Frequency Distribution Analysis - Students sort a data set of quiz scores into four given classes, then report relative frequency, cumulative relative frequency, and read a percentage off their own table ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats")

$anstypes = array("essay")
$displayformat[0]='editornopaste'

/* ---------- 1. Dynamic Context Generation ---------- */
$i = rand(0, 2)

// Three contexts: statistics quiz, history midterm, biology lab exam
$quiz_names = array("a statistics quiz", "a history midterm", "a biology lab exam")
$quiz_name = $quiz_names[$i]

$datasets = array(
  "35, 38, 41, 43, 45, 47, 48, 50, 52, 53, 55, 57, 58, 60, 62, 68, 74, 82",
  "52, 55, 58, 65, 68, 70, 71, 73, 74, 76, 78, 79, 81, 83, 88, 92, 95",
  "55, 62, 70, 78, 80, 82, 84, 85, 86, 87, 88, 89, 90, 91, 93, 95, 97, 98"
)
$dataset = $datasets[$i]

$n_values = array(18, 17, 18)
$n = $n_values[$i]

// The four classes students are given (they do not choose these: class width is 2.2)
$c1_labels = array("35 - 46", "52 - 62", "55 - 65")
$c2_labels = array("47 - 58", "63 - 73", "66 - 76")
$c3_labels = array("59 - 70", "74 - 84", "77 - 87")
$c4_labels = array("71 - 82", "85 - 95", "88 - 98")
$c1 = $c1_labels[$i]
$c2 = $c2_labels[$i]
$c3 = $c3_labels[$i]
$c4 = $c4_labels[$i]

// Frequencies for each class
$f1_values = array(5, 3, 2)
$f2_values = array(8, 5, 1)
$f3_values = array(3, 6, 7)
$f4_values = array(2, 3, 8)
$f1 = $f1_values[$i]
$f2 = $f2_values[$i]
$f3 = $f3_values[$i]
$f4 = $f4_values[$i]

// Relative frequency of each class, to three decimal places
$rf1_values = array("0.278", "0.176", "0.111")
$rf2_values = array("0.444", "0.294", "0.056")
$rf3_values = array("0.167", "0.353", "0.389")
$rf4_values = array("0.111", "0.176", "0.444")
$rf1 = $rf1_values[$i]
$rf2 = $rf2_values[$i]
$rf3 = $rf3_values[$i]
$rf4 = $rf4_values[$i]

// Cumulative relative frequency THROUGH THE THIRD CLASS
$crf3_counts = array(16, 14, 10)
$crf3_decs = array("0.889", "0.824", "0.556")
$crf3_pcts = array("88.9%", "82.4%", "55.6%")
$crf3_count = $crf3_counts[$i]
$crf3_dec = $crf3_decs[$i]
$crf3_pct = $crf3_pcts[$i]

// Reading the table: percent scoring at or below the top of the SECOND class
$cut_values = array(58, 73, 76)
$crf2_counts = array(13, 8, 3)
$crf2_pcts = array("72.2%", "47.1%", "16.7%")
$cut_val = $cut_values[$i]
$crf2_count = $crf2_counts[$i]
$crf2_pct = $crf2_pcts[$i]

// Narrative pieces for the model answer
$r_dist = "Counting the scores into the four given classes gives " . $c1 . ": " . $f1 . ", " . $c2 . ": " . $f2 . ", " . $c3 . ": " . $f3 . ", and " . $c4 . ": " . $f4 . ", which adds to " . $n . " scores in all."

$r_rels = array(
  "Dividing each frequency by n = 18 gives relative frequencies of 0.278, 0.444, 0.167, and 0.111. Adding the first three gives a cumulative relative frequency of 16 &#247; 18 = 0.889 through the class 59-70.",
  "Dividing each frequency by n = 17 gives relative frequencies of 0.176, 0.294, 0.353, and 0.176. Adding the first three gives a cumulative relative frequency of 14 &#247; 17 = 0.824 through the class 74-84.",
  "Dividing each frequency by n = 18 gives relative frequencies of 0.111, 0.056, 0.389, and 0.444. Adding the first three gives a cumulative relative frequency of 10 &#247; 18 = 0.556 through the class 77-87."
)
$r_rel = $r_rels[$i]

$r_reads = array(
  "13 of the 18 scores fall in the first two classes, so 72.2% of the students scored 58 or lower.",
  "8 of the 17 scores fall in the first two classes, so 47.1% of the students scored 73 or lower.",
  "3 of the 18 scores fall in the first two classes, so 16.7% of the students scored 76 or lower."
)
$r_read = $r_reads[$i]

$sample_narrative = $r_dist . " " . $r_rel . " " . $r_read

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
      <p style="margin:0 0 0.5em 0;"><b>Grading Criteria</b> -- ensure your response covers these points:</p>
      <table class="rubric-table">
        <tbody>
          <tr>
            <th class="col-header">Category</th>
            <th class="col-check">Requirement</th>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Frequency Table<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> List each of the four classes with its frequency.</label></li>
                <li><label><input type="checkbox"> Check that your four frequencies add up to ' . $n . '.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Relative &amp; Cumulative<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Give the relative frequency of each class, rounded to three decimal places.</label></li>
                <li><label><input type="checkbox"> Give the cumulative relative frequency through the third class, and show the sum you used.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Reading the Table<br>(2 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State what percent of the students scored ' . $cut_val . ' or lower, and name the classes you added to get it.</label></li>
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
            <td style="text-align:center;"><b>Frequency Table<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>List each of the four given classes with its frequency.
                    <span class="ideal-ans">Target: "' . $c1 . ' (freq ' . $f1 . '), ' . $c2 . ' (freq ' . $f2 . '), ' . $c3 . ' (freq ' . $f3 . '), ' . $c4 . ' (freq ' . $f4 . ')."</span></li>
                <li>The four frequencies account for every value in the data set.
                    <span class="ideal-ans">Target: "' . $f1 . ' + ' . $f2 . ' + ' . $f3 . ' + ' . $f4 . ' = ' . $n . '."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Relative &amp; Cumulative<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Relative frequency of each class (frequency &#247; ' . $n . ').
                    <span class="ideal-ans">Target: "' . $rf1 . ', ' . $rf2 . ', ' . $rf3 . ', ' . $rf4 . '"</span></li>
                <li>Cumulative relative frequency through the third class.
                    <span class="ideal-ans">Target: "' . $crf3_count . ' &#247; ' . $n . ' = ' . $crf3_dec . ', or ' . $crf3_pct . ', through the class ' . $c3 . '."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Reading the Table<br>(2 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Percent scoring ' . $cut_val . ' or lower, with the classes added named.
                    <span class="ideal-ans">Target: "' . $c1 . ' plus ' . $c2 . ' is ' . $crf2_count . ' of ' . $n . ' scores, so ' . $crf2_pct . '."</span></li>
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
  <p>The data set below records ' . $n . ' scores on ' . $quiz_name . '. It was scored out of 100 points.</p>
  <p style="text-align:center; font-size:1.05em; background:#f5f5f5; padding:12px; border-radius:6px; font-family:monospace; line-height:1.8;">
  ' . $dataset . '
  </p>
  <p><b>Essay Prompt:</b><br>
  Use the four classes below to build a frequency table for this data set, then extend it into a relative frequency and cumulative relative frequency table.</p>
  <p style="text-align:center; font-size:1.05em; background:#eef4fb; padding:12px; border-radius:6px; font-family:monospace; line-height:1.8;">
  ' . $c1 . ' &nbsp;&nbsp;&nbsp; ' . $c2 . ' &nbsp;&nbsp;&nbsp; ' . $c3 . ' &nbsp;&nbsp;&nbsp; ' . $c4 . '
  </p>
  <p>In your response, be sure to cover:</p>
  <ul>
    <li>The frequency for each of the four classes.</li>
    <li>The relative frequency of each class, rounded to three decimal places.</li>
    <li>The cumulative relative frequency through the third class, and the sum you used to get it.</li>
    <li>What percent of the students scored ' . $cut_val . ' or lower, and which classes you added to find it.</li>
  </ul>
  ' . $rubricbutton . '
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
