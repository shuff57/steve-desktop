// === NAME - DESCRIPTION: Frequency Distribution Analysis - Students create a frequency distribution with 4 classes from a dataset of quiz scores and write a conclusion describing the shape of the distribution and what it reveals about student performance. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats")

$anstypes = array("essay")
$displayformat[0]='editornopaste'

/* ---------- 1. Dynamic Context Generation ---------- */
$i = rand(0, 2)

// Three contexts: statistics quiz (right-skewed), history midterm (symmetric), biology lab exam (left-skewed)
$contexts = array(
  "an Introduction to Statistics class",
  "a World History class",
  "a Biology lab section"
)
$subject = $contexts[$i]

$quiz_names = array("a statistics quiz", "a history midterm", "a biology lab exam")
$quiz_name = $quiz_names[$i]

// Pre-constructed datasets with distinct shapes
$datasets = array(
  "35, 38, 41, 43, 45, 47, 48, 50, 52, 53, 55, 57, 58, 60, 62, 68, 74, 82",
  "52, 55, 58, 65, 68, 70, 71, 73, 74, 76, 78, 79, 81, 83, 88, 92, 95",
  "55, 62, 70, 78, 80, 82, 84, 85, 86, 87, 88, 89, 90, 91, 93, 95, 97, 98"
)
$dataset = $datasets[$i]

$n_values = array(18, 17, 18)
$n = $n_values[$i]

$min_values = array(35, 52, 55)
$max_values = array(82, 95, 98)
$min_val = $min_values[$i]
$max_val = $max_values[$i]

$range_values = array(47, 43, 43)
$range_val = $range_values[$i]

$class_widths = array(12, 11, 11)
$class_width = $class_widths[$i]

// Class boundaries for 4 classes
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

// Shape descriptions
$shapes = array(
  "right-skewed (skewed to the right)",
  "approximately symmetric (roughly bell-shaped)",
  "left-skewed (skewed to the left)"
)
$shape = $shapes[$i]

// Pre-built ideal answer target for interpretation
$target_interps = array(
  "Most students scored below 59, with only a few scoring above 70. The quiz was quite challenging for the class overall.",
  "Scores are spread fairly evenly across the middle range. The exam was appropriately challenging, producing a typical distribution of grades.",
  "The vast majority of students scored 77 or above, with very few in the lower ranges. Students were well-prepared, or the exam was relatively straightforward."
)
$target_interp = $target_interps[$i]

// Narrative variables for model answer (context-dependent)
$r_dists = array(
  "Using a class width of 12, the four classes are 35-46 (frequency 5), 47-58 (frequency 8), 59-70 (frequency 3), and 71-82 (frequency 2).",
  "Using a class width of 11, the four classes are 52-62 (frequency 3), 63-73 (frequency 5), 74-84 (frequency 6), and 85-95 (frequency 3).",
  "Using a class width of 11, the four classes are 55-65 (frequency 2), 66-76 (frequency 1), 77-87 (frequency 7), and 88-98 (frequency 8)."
)
$r_dist = $r_dists[$i]

$r_shapes = array(
  "This distribution is <b>right-skewed</b>, meaning the data is concentrated in the lower classes with a tail extending toward the higher scores.",
  "This distribution is <b>approximately symmetric</b>, meaning the frequencies build toward the middle classes and taper off similarly on both sides.",
  "This distribution is <b>left-skewed</b>, meaning the data is concentrated in the higher classes with a tail extending toward the lower scores."
)
$r_shape_desc = $r_shapes[$i]

$r_interps_full = array(
  "This tells us that <b>most students scored below 59 on the statistics quiz</b>, and only a few scored above 70. The quiz appears to have been quite challenging for the class, and students may benefit from additional review before moving forward.",
  "This tells us that <b>student performance on the history midterm was fairly balanced</b>, with the bulk of scores falling in the middle range (63-84). The exam appears to have been appropriately challenging, producing a typical spread of grades.",
  "This tells us that <b>the vast majority of students scored 77 or above on the biology lab exam</b>, with very few scoring below that. Students appear to have been well-prepared for this material, or the exam may have been relatively straightforward."
)
$r_interp_full = $r_interps_full[$i]

$sample_narrative = $r_dist . " " . $r_shape_desc . " " . $r_interp_full

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
            <td style="text-align:center;"><b>Frequency Distribution</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Show how you determined the class width and class boundaries.</label></li>
                <li><label><input type="checkbox"> List each class with its corresponding frequency.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Shape Identification</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Identify and describe the overall shape of the distribution.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Performance Interpretation</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain what the distribution shape reveals about how students performed.</label></li>
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
            <td style="text-align:center;"><b>Frequency Distribution<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Show how you determined the class width and class boundaries.
                    <span class="ideal-ans">Target: "Range = ' . $range_val . ' (' . $max_val . ' - ' . $min_val . '). Class width = ' . $class_width . ' (range &#247; 4, rounded up). Start from the minimum value ' . $min_val . '."</span></li>
                <li>List each class with its corresponding frequency.
                    <span class="ideal-ans">Target: "' . $c1 . ' (freq ' . $f1 . '), ' . $c2 . ' (freq ' . $f2 . '), ' . $c3 . ' (freq ' . $f3 . '), ' . $c4 . ' (freq ' . $f4 . ')."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Shape Identification<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Identify and describe the overall shape of the distribution.
                    <span class="ideal-ans">Target: "The distribution is ' . $shape . '."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Performance Interpretation<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain what the distribution shape reveals about how students performed.
                    <span class="ideal-ans">Target: "' . $target_interp . '"</span></li>
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
  <p>The following data represents ' . $n . ' scores on ' . $quiz_name . ' given to students in ' . $subject . '. The quiz was scored out of 100 points.</p>
  <p style="text-align:center; font-size:1.05em; background:#f5f5f5; padding:12px; border-radius:6px; font-family:monospace; line-height:1.8;">
  ' . $dataset . '
  </p>
  <p><b>Essay Prompt:</b><br>
  Create a frequency distribution using 4 classes for this dataset. Then write a conclusion statement describing the shape of the distribution and what it tells you about student performance on this quiz.</p>
  <p>In your response, be sure to cover:</p>
  <ul>
    <li>How you determined the class width and what class boundaries you used.</li>
    <li>The frequency for each class.</li>
    <li>The overall shape of the distribution and what it reveals about student performance.</li>
  </ul>
  ' . $rubricbutton . '
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
