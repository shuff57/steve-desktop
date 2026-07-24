// === NAME - DESCRIPTION: Chi-Square Independence - Students explain the difference between two variables being connected and one causing the other, then give an example with a third factor that explains the pattern. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
  "cities with more swimming pools per person also tend to have higher drowning rates",
  "elementary students with bigger shoe sizes tend to score higher on reading tests",
  "fires where more firefighters are sent tend to result in more property damage"
);
$i = rand(0, count($contexts)-1);
$topic = $contexts[$i];

$var1 = array("swimming pools per person", "shoe size", "number of firefighters sent");
$var2 = array("drowning rates", "reading test scores", "property damage");
$third_factors = array("hot weather and larger populations", "the age of the child", "the severity of the fire");
$explanations = array(
  "Warmer, bigger cities build more pools and also have more people swimming, which leads to more drownings. The pools themselves don't cause the drownings.",
  "Older kids have bigger feet and are also further along in school, so they read better. Having big feet doesn't make you a better reader.",
  "Bigger fires cause more damage and also require more firefighters. The firefighters are responding to the severity, not making the damage worse."
);

$v1 = $var1[$i];
$v2 = $var2[$i];
$third = $third_factors[$i];
$explain = $explanations[$i];

// Narrative variables for the model answer
$r_connection = "they tend to change together in a pattern, so knowing one tells you something about the other. But that does not mean one is causing the other to change";
$r_example = "$v1 and $v2 look connected because $topic";
$r_third = "$third is driving both of them. $explain To show that one actually causes the other, you would need a controlled experiment, not just a pattern in the data";

$sample_narrative = "When two variables are connected, it means <b>$r_connection</b>. For example, <b>$r_example</b>. The real explanation is that <b>$r_third</b>.";

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
            <td style="text-align:center;"><b>Connection vs. Cause</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Describe what it means for two variables to be connected.</label></li>
                <li><label><input type="checkbox"> Describe what it would mean for one variable to actually cause changes in the other.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Example With a Third Factor</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Give a specific example of two variables that look connected but where one does not cause the other.</label></li>
                <li><label><input type="checkbox"> Identify what else could explain the pattern.</label></li>
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
            <td style="text-align:center;"><b>Connection vs. Cause<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Describe what it means for two variables to be connected.
                    <span class="ideal-ans">Target: "Two variables are connected when they tend to change together in a predictable pattern, so knowing one tells you something about the other."</span></li>
                <li>Describe what it would mean for one variable to actually cause changes in the other.
                    <span class="ideal-ans">Target: "For one variable to cause changes in the other, changing it would have to directly make the other one change. That requires more than just seeing a pattern in data."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Example With a Third Factor<br>(6 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Give a specific example of two connected variables where one does not cause the other.
                    <span class="ideal-ans">Target: "'.$v1.' and '.$v2.' look connected because '.$topic.'."</span></li>
                <li>Identify what else could explain the pattern.
                    <span class="ideal-ans">Target: "'.$third.' explains both. '.$explain.'"</span></li>
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
  <p>A researcher notices that '.$topic.'. They wonder whether this pattern means one variable is actually causing changes in the other.</p>
  <p><b>Essay Prompt:</b><br>
  What is the difference between two variables being connected and one variable actually causing changes in the other? Using the example above, explain why we cannot say one causes the other, and identify what else might explain the pattern.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>What it means for two variables to be connected, and how that differs from one causing the other.</li>
    <li>Using the scenario above, what third factor could explain the pattern instead.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
