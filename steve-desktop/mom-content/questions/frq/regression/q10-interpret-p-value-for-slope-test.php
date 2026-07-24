// === NAME - DESCRIPTION: Inference for the Slope - Interpret a P-Value for the Slope Test - Students interpret a p-value from a regression slope hypothesis test, compare it to alpha, decide on H&#8320;, and write a contextual conclusion about the linear relationship. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
  array(
    "x_var" => "hours studied per week",
    "y_var" => "final exam score",
    "x_units" => "hours",
    "y_units" => "points",
    "population" => "all college students at the university",
    "relationship" => "hours studied per week and final exam score",
    "scenario" => "A statistics instructor wants to know whether weekly study time is linearly related to final exam score for college students at the university.",
    "n_min" => 40,
    "n_max" => 80,
    "sign" => 1
  ),
  array(
    "x_var" => "monthly ad spend (in $1000s)",
    "y_var" => "monthly sales (in $1000s)",
    "x_units" => "thousand dollars",
    "y_units" => "thousand dollars",
    "population" => "all comparable retail stores",
    "relationship" => "monthly ad spend and monthly sales",
    "scenario" => "A regional marketing analyst is studying whether monthly advertising spending is linearly related to monthly sales across comparable retail stores.",
    "n_min" => 25,
    "n_max" => 50,
    "sign" => 1
  ),
  array(
    "x_var" => "weekly exercise hours",
    "y_var" => "resting heart rate (bpm)",
    "x_units" => "hours",
    "y_units" => "bpm",
    "population" => "all healthy adults aged 25 to 55",
    "relationship" => "weekly exercise hours and resting heart rate",
    "scenario" => "A health researcher wants to know whether weekly exercise hours are linearly related to resting heart rate among healthy adults aged 25 to 55.",
    "n_min" => 30,
    "n_max" => 70,
    "sign" => -1
  )
);

$i = rand(0, count($contexts)-1);
$ctx = $contexts[$i];

$x_var = $ctx["x_var"];
$y_var = $ctx["y_var"];
$x_units = $ctx["x_units"];
$y_units = $ctx["y_units"];
$population = $ctx["population"];
$relationship = $ctx["relationship"];
$scenario = $ctx["scenario"];
$sign = $ctx["sign"];

$n = rand($ctx["n_min"], $ctx["n_max"]);

// Slope magnitude per context
if ($i == 0) {
  $b1_mag = rrand(2.5, 5.0, 0.1);
} else if ($i == 1) {
  $b1_mag = rrand(3.0, 8.0, 0.1);
} else {
  $b1_mag = rrand(1.0, 3.0, 0.1);
}
$b1 = $sign * round($b1_mag, 1);
$b1_display = $b1;

// Choose alpha and decision direction
$alpha = randfrom(array(0.01, 0.05, 0.10));
$decision_type = rand(0, 1); // 0 = reject, 1 = fail to reject

if ($decision_type == 0) {
  // p-value clearly below alpha
  if ($alpha == 0.01) {
    $p_value = rrand(0.0005, 0.008, 0.0005);
  } else if ($alpha == 0.05) {
    $p_value = rrand(0.001, 0.030, 0.001);
  } else {
    $p_value = rrand(0.005, 0.070, 0.001);
  }
} else {
  // p-value clearly above alpha
  if ($alpha == 0.01) {
    $p_value = rrand(0.050, 0.300, 0.001);
  } else if ($alpha == 0.05) {
    $p_value = rrand(0.150, 0.500, 0.001);
  } else {
    $p_value = rrand(0.250, 0.600, 0.001);
  }
}

$alpha_display = $alpha;
if ($p_value < 0.001) { $p_display = "&lt; 0.001"; } else { $p_display = round($p_value, 3); }

// Decision-driven phrases (parallel arrays)
$compare_phrases = array("is less than", "is greater than");
$decision_verbs = array("reject", "fail to reject");
$conclusion_starts = array("There is convincing evidence", "There is not convincing evidence");
$evidence_phrases = array(
  "the sample provides strong evidence of a linear relationship",
  "the sample does not provide strong enough evidence of a linear relationship"
);

$compare_phrase = $compare_phrases[$decision_type];
$decision_verb = $decision_verbs[$decision_type];
$conclusion_start = $conclusion_starts[$decision_type];
$evidence_phrase = $evidence_phrases[$decision_type];

// Narrative pieces for the model response
$r_interpret = "The p-value of $p_display is the probability of observing a sample slope at least as extreme as the observed b&#8321; = $b1_display, assuming the population slope &#946;&#8321; = 0 (that is, assuming there is truly no linear relationship between $relationship in the population)";

$r_decision = "Since the p-value $p_display $compare_phrase &#945; = $alpha_display, we $decision_verb the null hypothesis H&#8320;: &#946;&#8321; = 0";

$r_conclusion = "$conclusion_start of a linear relationship between $relationship for $population";

$sample_narrative = "<b>$r_interpret</b>. <b>$r_decision</b>. In context, $evidence_phrase between $relationship. <b>$r_conclusion.</b>";

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
      <p style="margin:0 0 0.5em 0;"><b>Grading Criteria</b> -- ensure your response covers these points:</p>
      <table class="rubric-table">
        <tbody>
          <tr>
            <th class="col-header">Category</th>
            <th class="col-check">Requirement</th>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Interpret the P-Value</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State the p-value as a probability of observing a sample slope as extreme or more extreme than the observed b&#8321;.</label></li>
                <li><label><input type="checkbox"> Make clear that this probability is computed assuming H&#8320;: &#946;&#8321; = 0 (no linear relationship in the population).</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Decision About H&#8320;</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Compare the p-value directly to &#945;.</label></li>
                <li><label><input type="checkbox"> State whether you reject or fail to reject H&#8320;.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Conclusion in Context</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Write a complete sentence about the linear relationship between the explanatory and response variables in the population.</label></li>
                <li><label><input type="checkbox"> Use cautious language ("convincing evidence" or "not convincing evidence") rather than the word "proof".</label></li>
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
            <td style="text-align:center;"><b>Interpret the P-Value<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Interpret the p-value as a conditional probability assuming H&#8320; is true.
                    <span class="ideal-ans">Target: "The p-value of '.$p_display.' is the probability of observing a sample slope at least as extreme as the observed b&#8321; = '.$b1_display.', assuming there is truly no linear relationship between '.$relationship.' in the population (&#946;&#8321; = 0)."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Decision About H&#8320;<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Compare the p-value to &#945; and state the decision on H&#8320;.
                    <span class="ideal-ans">Target: "Since p-value '.$p_display.' '.$compare_phrase.' &#945; = '.$alpha_display.', we '.$decision_verb.' the null hypothesis H&#8320;: &#946;&#8321; = 0."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Conclusion in Context<br>(4 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Write a contextual conclusion using cautious language and naming the variables and population.
                    <span class="ideal-ans">Target: "'.$conclusion_start.' of a linear relationship between '.$relationship.' for '.$population.'."</span></li>
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
  <p>'.$scenario.' A random sample is collected and a least-squares regression line is fit. The hypotheses for the slope test are:</p>
  <p style="margin-left:2em;">H&#8320;: &#946;&#8321; = 0<br>H&#8321;: &#946;&#8321; &#8800; 0</p>
  <p>The output from the sample produces:</p>
  <ul>
    <li>Sample size: <b>n = '.$n.'</b></li>
    <li>Sample slope: <b>b&#8321; = '.$b1_display.'</b></li>
    <li>P-value: <b>'.$p_display.'</b></li>
    <li>Significance level: <b>&#945; = '.$alpha_display.'</b></li>
  </ul>
  <p><b>Essay Prompt:</b><br>
  Write a complete response that (1) interprets the p-value in the context of this slope test, (2) compares the p-value to &#945; and states a clear decision about H&#8320;, and (3) gives a contextual conclusion about whether there is convincing evidence of a linear relationship between '.$relationship.' for '.$population.'.</p>
  <p>In your response, be sure to cover:</p>
  <ul>
    <li>What the p-value of '.$p_display.' actually represents (the probability of what, assuming what).</li>
    <li>How '.$p_display.' compares to &#945; = '.$alpha_display.', and your decision on H&#8320;.</li>
    <li>A clear contextual conclusion about the linear relationship between '.$x_var.' and '.$y_var.' in '.$population.', using cautious language.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
