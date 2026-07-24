// === NAME - DESCRIPTION: Interpret a Correlation Coefficient - Students describe the strength and direction of a linear relationship from a randomized r value, explain what r does and does not measure, and explain why correlation alone does not establish causation. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
  "the number of hours adults spend exercising each week (x) and their resting heart rate in beats per minute (y)",
  "monthly ice cream sales at a beachside town (x) and the number of swimming pool drownings reported that month (y)",
  "the average number of hours middle school students watch TV per day (x) and their reading comprehension test scores (y)",
  "city population in thousands (x) and the number of fast food restaurants in that city (y)"
);
$x_vars = array(
  "weekly exercise hours",
  "monthly ice cream sales",
  "daily TV hours",
  "city population (in thousands)"
);
$y_vars = array(
  "resting heart rate",
  "monthly pool drownings",
  "reading comprehension score",
  "number of fast food restaurants"
);
$natural_signs = array(-1, 1, -1, 1);
$lurking_examples = array(
  "a person's overall fitness level or age could influence both how much they exercise and their resting heart rate",
  "warm summer weather drives both ice cream sales and swimming activity (and therefore drownings), so temperature is a lurking variable affecting both",
  "family environment, parental involvement, or socioeconomic status could affect both how much TV a child watches and how well they read",
  "larger cities simply have more of everything (more people means more demand for restaurants), so population size itself is the underlying driver rather than one variable causing the other"
);

$i = rand(0, count($contexts)-1);
$context = $contexts[$i];
$x_var = $x_vars[$i];
$y_var = $y_vars[$i];
$lurking = $lurking_examples[$i];

// Pick magnitude and apply natural sign so the scenario stays realistic
$r_mag = rrand(0.30, 0.95, 0.05);
$r = $natural_signs[$i] * $r_mag;

if ($r > 0) { $direction = "positive"; } else { $direction = "negative"; }
if ($r_mag < 0.5) { $strength = "weak"; } else if ($r_mag < 0.75) { $strength = "moderate"; } else { $strength = "strong"; }

if ($r > 0) {
  $direction_meaning = "as $x_var increases, $y_var also tends to increase";
} else {
  $direction_meaning = "as $x_var increases, $y_var tends to decrease";
}

// Narrative variables for the model answer
$r_strength_dir = "The correlation `r = $r` indicates a $strength $direction linear relationship between $x_var and $y_var. Because |r| = $r_mag, the linear association is classified as $strength, and the $direction sign tells us $direction_meaning";
$r_what_r_measures = "The correlation coefficient r measures only the strength and direction of a LINEAR relationship between two quantitative variables. It is unitless and always lies between -1 and 1. A small value of r does not mean the variables are unrelated, only that they are not LINEARLY related; they could still follow a curved pattern. Likewise, r is not appropriate for categorical variables";
$r_causation = "Even though the value `r = $r` looks like a $strength association, this does not prove that $x_var causes a change in $y_var. Correlation only shows that the two variables move together; a third lurking variable could be driving both. For example, $lurking. To establish causation we would need a controlled experiment that randomly assigns the explanatory variable, not just observational data";

$sample_narrative = "<b>$r_strength_dir</b>. <b>$r_what_r_measures</b>. <b>$r_causation</b>.";

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
            <td style="text-align:center;"><b>Strength and Direction</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Identify the direction of the relationship (positive or negative) based on the sign of r.</label></li>
                <li><label><input type="checkbox"> Classify the strength of the linear association (weak, moderate, or strong) based on |r|, and tie it back to the context.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>What r Measures</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State that r measures only the strength and direction of a LINEAR relationship between two quantitative variables.</label></li>
                <li><label><input type="checkbox"> Note that r is unitless and is always between -1 and 1, and that a small r does not rule out a non-linear relationship.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Correlation vs Causation</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain that a strong correlation does NOT prove that one variable causes the other.</label></li>
                <li><label><input type="checkbox"> Mention a possible lurking or confounding variable that could explain both variables in this scenario.</label></li>
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
            <td style="text-align:center;"><b>Strength and Direction<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Name the direction (positive/negative) and classify the strength (weak/moderate/strong) using rough cutoffs (|r| &lt; 0.3 weak, 0.3-0.7 moderate, &gt; 0.7 strong), and connect it to the context.
                    <span class="ideal-ans">Target: "Since r = '.$r.', the linear relationship between '.$x_var.' and '.$y_var.' is '.$strength.' and '.$direction.'. The '.$direction.' sign means '.$direction_meaning.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>What r Measures<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>State that r captures linear association only between two quantitative variables; r is unitless and lies between -1 and 1; a small r does not rule out a non-linear relationship.
                    <span class="ideal-ans">Target: "r measures the strength and direction of a LINEAR relationship between two quantitative variables. It is unitless, always falls between -1 and 1, and a small value of r only rules out a linear pattern, not a curved one."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Correlation vs Causation<br>(3 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain that correlation does not imply causation; a third (lurking/confounding) variable could explain both, and only a controlled experiment can establish causation.
                    <span class="ideal-ans">Target: "Even though r = '.$r.' shows the variables move together, this does not prove '.$x_var.' causes a change in '.$y_var.'. For example, '.$lurking.'."</span></li>
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
  <p>A researcher collects data on '.$context.' and computes the correlation coefficient `r = '.$r.'`.</p>
  <p><b>Essay Prompt:</b><br>
  Interpret this correlation coefficient in context. Describe both the strength and the direction of the linear relationship, explain what r does and does not measure, and explain why a correlation like this does not by itself prove that one variable causes the other.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>The strength and direction of the linear relationship between '.$x_var.' and '.$y_var.', based on `r = '.$r.'`.</li>
    <li>What the correlation coefficient r actually measures (and what it does NOT measure), including its range and that it only describes LINEAR association.</li>
    <li>Why correlation alone does not establish causation, with at least one possible lurking or confounding variable for this scenario.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
