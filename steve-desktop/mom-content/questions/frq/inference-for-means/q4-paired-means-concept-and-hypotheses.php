// === NAME - DESCRIPTION: Paired Means - Concept and Hypotheses - Students explain why paired data are analyzed with within-subject differences, apply the idea to a randomized before-after scenario, and state appropriate paired t-test hypotheses in context. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$i = rand(0, 2);

$subject_groups = array(
  "students",
  "patients",
  "participants"
);

$before_measures = array(
  "test scores",
  "pain levels on a 1 to 10 scale",
  "total cholesterol levels"
);

$after_measures = array(
  "test scores",
  "pain levels on a 1 to 10 scale",
  "total cholesterol levels"
);

$interventions = array(
  "a 6-week tutoring program",
  "a new physical therapy treatment",
  "a 3-month diet change"
);

$difference_rules = array(
  "after score - before score",
  "after pain level - before pain level",
  "after cholesterol - before cholesterol"
);

$sample_sizes = array(
  rand(26, 30),
  rand(33, 37),
  rand(40, 44)
);

$before_values = array(
  rrand(66, 73, 0.1),
  rrand(6.8, 8.5, 0.1),
  rrand(215, 238, 0.1)
);

$after_values = array(
  rrand(72, 80, 0.1),
  rrand(4.6, 6.9, 0.1),
  rrand(190, 214, 0.1)
);

$subject_group = $subject_groups[$i];
$before_measure = $before_measures[$i];
$after_measure = $after_measures[$i];
$intervention = $interventions[$i];
$difference_rule = $difference_rules[$i];
$n = $sample_sizes[$i];
$before_value = $before_values[$i];
$after_value = $after_values[$i];

$contexts = array(
  "A tutoring center measures $n $subject_group before and after $intervention.",
  "A physical therapist records $n $subject_group before and after $intervention.",
  "A nutritionist tracks $n $subject_group before and after $intervention."
);

$topic = $contexts[$i];

// Narrative variables for the model answer
$r_paired = "Paired data means each subject contributes two linked measurements, one before and one after, so each pair belongs to the same person";
$r_differences = "For each subject we compute a difference using d = $difference_rule, then analyze the set of d values with d&#772; as the sample mean difference";
$r_parameter = "The parameter for a paired t-test is &#956;<sub>d</sub>, the population mean of the within-subject differences, not two separate population means";
$r_hypotheses = "A standard setup is H&#8320;: &#956;<sub>d</sub> = 0 and H&#8321;: &#956;<sub>d</sub> &#8800; 0, where H&#8320; means there is no average change from before to after";

$sample_narrative = "In this scenario, <b>$r_paired</b>. <b>$r_differences</b>. <b>$r_parameter</b>. The hypotheses are <b>$r_hypotheses</b>.";

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
            <td style="text-align:center;"><b>What Makes Data Paired</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Describe why this design uses two linked measurements for each subject.</label></li>
                <li><label><input type="checkbox"> Explain how the two values in each pair are naturally connected.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Computing and Analyzing Differences</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State how to compute the difference for each subject using this scenario.</label></li>
                <li><label><input type="checkbox"> Describe what summary of the differences is analyzed in a paired t-test.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Hypotheses</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State H&#8320; and H&#8321; for the paired t-test using &#956;<sub>d</sub>.</label></li>
                <li><label><input type="checkbox"> Explain in context what H&#8320; means about average change.</label></li>
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
            <td style="text-align:center;"><b>What Makes Data Paired<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain why this is paired and what links the two values.
                    <span class="ideal-ans">Target: "Each subject is measured twice, once before and once after the intervention, and the two values in each pair come from the same person."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Computing and Analyzing Differences<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Define differences and describe what is analyzed.
                    <span class="ideal-ans">Target: "Compute d = '.$difference_rule.' for each subject, analyze d&#772; from those differences, and treat &#956;<sub>d</sub> as the parameter of interest."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Hypotheses<br>(4 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>State hypotheses and interpret H&#8320;.
                    <span class="ideal-ans">Target: "H&#8320;: &#956;<sub>d</sub> = 0 and H&#8321;: &#956;<sub>d</sub> &#8800; 0. Under H&#8320;, the mean difference is zero, so there is no average before-after change in the population."</span></li>
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
  <p>'.$topic.'</p>
  <p>In this sample, the average before value is <b>'.$before_value.'</b> and the average after value is <b>'.$after_value.'</b> for the same '.$subject_group.'.</p>
  <p>The paired analysis defines differences as <b>d = '.$difference_rule.'</b>, with <b>n = '.$n.'</b> pairs.</p>
  <p><b>Essay Prompt:</b><br>
  Explain why this is a paired design and why paired data are analyzed through differences instead of treating before and after as independent groups. Then use this scenario to describe how differences are computed and summarized, and state the null and alternative hypotheses for a paired t-test.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>What makes the observations paired in this setting.</li>
    <li>How to compute d for each pair and why d&#772; and &#956;<sub>d</sub> are central to the analysis.</li>
    <li>The hypotheses H&#8320; and H&#8321;, and what H&#8320; means in plain language.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
