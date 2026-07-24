// === NAME - DESCRIPTION: Paired vs Two-Sample T-Tests - Students explain the key design difference between paired and independent samples, classify two randomized scenarios, and justify why test choice affects conclusions. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
  "A teacher and a school data team are deciding whether to use a paired t-test or a two-sample t-test for quiz score studies",
  "A clinic analyst is deciding whether to use a paired t-test or a two-sample t-test for blood pressure studies",
  "A human performance lab is deciding whether to use a paired t-test or a two-sample t-test for reaction-time studies"
);
$i = rand(0, count($contexts)-1);
$topic = $contexts[$i];

$paired_counts = array(rand(22, 28), rand(32, 40), rand(26, 34));
$independent_count1 = array(rand(20, 27), rand(24, 32), rand(18, 26));
$independent_count2 = array(rand(20, 27), rand(24, 32), rand(18, 26));

$paired_scenarios = array(
  $paired_counts[0]." students take the same math quiz before and after a review session",
  $paired_counts[1]." patients have blood pressure measured before and after taking a medication",
  $paired_counts[2]." participants complete a reaction-time task with caffeine and without caffeine"
);

$paired_why = array(
  "Each student contributes two linked scores, one before and one after, so the data are matched within the same person",
  "Each patient is measured twice, before medication and after medication, so observations are naturally paired within each patient",
  "Each participant is measured in both conditions, with caffeine and without caffeine, so each pair of scores comes from one person"
);

$independent_scenarios = array(
  $independent_count1[0]." male students and ".$independent_count2[0]." female students have their quiz scores compared",
  $independent_count1[1]." patients in a treatment group and ".$independent_count2[1]." patients in a placebo group, all different people, have blood pressure compared",
  $independent_count1[2]." regular coffee drinkers and ".$independent_count2[2]." non-coffee drinkers, all different people, have reaction times compared"
);

$independent_why = array(
  "The two groups are separate sets of students, and no one appears in both groups",
  "The treatment and placebo groups are made of different people, so there is no one-to-one matching",
  "Coffee drinkers and non-coffee drinkers are separate groups with no paired observations"
);

$scenario_a = $paired_scenarios[$i];
$scenario_a_why = $paired_why[$i];
$scenario_b = $independent_scenarios[$i];
$scenario_b_why = $independent_why[$i];

// Narrative variables for the model answer
$r_key_distinction = "A paired t-test is for two measurements on the same subjects, or matched pairs, while a two-sample t-test is for two separate independent groups with no connection between observations";
$r_scenario_classification = "Scenario A is paired because $scenario_a_why. Scenario B is independent because $scenario_b_why";
$r_why_it_matters = "Paired analysis focuses on within-subject differences, which controls for person-to-person variability and often increases power, but using an independent-samples test on paired data ignores that structure and can hide a real effect";

$sample_narrative = "The core distinction is that <b>$r_key_distinction</b>. In this version of the question, Scenario A is: $scenario_a, and Scenario B is: $scenario_b. A correct classification is <b>$r_scenario_classification</b>. This matters because <b>$r_why_it_matters</b>.";

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
            <td style="text-align:center;"><b>Key Distinction</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain the fundamental difference between paired and independent designs.</label></li>
                <li><label><input type="checkbox"> Describe what kind of subject relationship each design uses.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Scenario Classification</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Classify Scenario A and Scenario B as paired or independent.</label></li>
                <li><label><input type="checkbox"> Justify each classification using details from the scenarios.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Why It Matters</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain why the correct test depends on the data structure.</label></li>
                <li><label><input type="checkbox"> Describe what can go wrong when the wrong test is used.</label></li>
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
            <td style="text-align:center;"><b>Key Distinction<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>State the fundamental structural difference.
                    <span class="ideal-ans">Target: "Paired means two measurements on the same subjects, or matched pairs. Independent means two separate groups with no connection between observations."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Scenario Classification<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Classify Scenario A correctly with justification.
                    <span class="ideal-ans">Target: "Scenario A is paired because '.$scenario_a_why.'."</span></li>
                <li>Classify Scenario B correctly with justification.
                    <span class="ideal-ans">Target: "Scenario B is independent because '.$scenario_b_why.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Why It Matters<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain why test choice changes what the analysis captures.
                    <span class="ideal-ans">Target: "Paired tests analyze within-pair differences, which controls for subject variability and often provides more power. Using an independent two-sample test on paired data ignores the pairing structure and can miss a real effect."</span></li>
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
  <p>'.$topic.'.</p>
  <p><b>Scenario A:</b> '.$scenario_a.'.</p>
  <p><b>Scenario B:</b> '.$scenario_b.'.</p>
  <p><b>Essay Prompt:</b><br>
  Compare paired and two-sample t-tests, then use the two scenarios above to justify the right design choice in each case.</p>
  <p>In your explanation, be sure to cover:</p>
  <ol>
    <li>The key difference between paired and independent designs.</li>
    <li>Which test design fits Scenario A and which fits Scenario B, with clear justification for each.</li>
    <li>Why choosing the correct test matters for detecting real effects.</li>
  </ol>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
