// === NAME - DESCRIPTION: Sampling Design Critique - Students critique a flawed sampling plan, identify bias, predict the direction of skew, and propose a better randomized design ===
// === SET QUESTION TYPE TO: essay ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

$scenarios = array(
  array(
    "A nutritionist wants to estimate the <b>average daily sugar intake</b> of all 8,500 students at a university.",
    "She posts a survey link in two private dietitian-and-wellness Facebook groups on campus and uses the first 200 responses.",
    "<b>Voluntary-response (and selection) bias</b>, because only people interested in nutrition / wellness will click and respond",
    "underestimate average sugar intake, since wellness-focused students likely eat less sugar than the typical student",
    "select a simple random sample of 200 students from the full enrollment list and follow up by email to reach a high response rate"
  ),
  array(
    "A campus dean wants to estimate the <b>average weekly study hours</b> of all 12,000 students.",
    "He surveys the first 250 students he meets walking out of the library at 9 PM on a Wednesday.",
    "<b>Convenience-sampling (selection) bias</b>, because the late-library crowd is not a random cross-section of all students",
    "overestimate average study hours, since students at the library at 9 PM are likely the heaviest studiers",
    "draw a simple random sample of student ID numbers from the enrollment database, or use stratified sampling by year"
  ),
  array(
    "A city council wants to estimate the <b>proportion of registered voters who support a new bond measure</b>.",
    "Council staff phones a list of supporters of last year's ballot initiative and asks if they will vote yes.",
    "<b>Selection bias</b>, because the call list was filtered to people already known to vote yes on similar measures",
    "overestimate the percent of all registered voters who will support the measure",
    "obtain the full voter-registration list and take a simple random sample (or stratified sample by district), not a partisan sub-list"
  ),
  array(
    "An athletic director wants to estimate the <b>average number of weekly workouts</b> for all 14,500 students.",
    "She emails a survey to every member of the campus rec-sports mailing list and uses the responses she gets back.",
    "<b>Coverage / selection bias</b>, because the rec-sports mailing list already over-represents active exercisers",
    "overestimate weekly workouts, since non-exercisers are largely missing from the frame",
    "use the full student enrollment list as the sampling frame and take a random sample; offer a small incentive to keep response rates up"
  )
);

$i = rand(0, count($scenarios) - 1);
$ctx_goal   = $scenarios[$i][0];
$ctx_plan   = $scenarios[$i][1];
$ans_bias   = $scenarios[$i][2];
$ans_skew   = $scenarios[$i][3];
$ans_fix    = $scenarios[$i][4];

$model_response = "<b>Identify the bias:</b> This study shows $ans_bias. <br><br><b>Impact on results:</b> The sample is likely to $ans_skew. <br><br><b>Better design:</b> A stronger plan would $ans_fix, so every member of the target population has a known nonzero chance of being chosen.";

$css_block = '
<style>
  .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .rubric-container summary::-webkit-details-marker { display:none; }
  .rubric-container details[open] summary { box-shadow: inset 0 -1px 0 #ccc; }
  .rubric-content { padding:0.75em; background:#fafafa; }
  .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; user-select:text; }
  .row-colored { background:#fff9ea; }
  .ideal-ans { display:block; background-color:#e8f5e9; font-style:italic; font-weight:bold; font-size:0.95em; margin:5px 0 10px 0; border-left:3px solid #4CAF50; padding-left:8px; }
  .full-response-box { margin-top:15px; border:2px solid #4CAF50; background-color:#e8f5e9; padding:15px; border-radius:5px; }
</style>';

$rubricbutton = $css_block . '
<div class="rubric-container">
  <details>
    <summary>Click to View Grading Checklist</summary>
    <div class="rubric-content">
      <p style="margin:0 0 0.5em 0;"><b>Grading Criteria</b> -- ensure your explanation covers:</p>
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Identify the Bias<br>(4 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li><label><input type="checkbox"> Name the type of sampling bias.</label></li>
              <li><label><input type="checkbox"> Explain why the sampling plan creates that bias.</label></li>
            </ul></td></tr>
          <tr><td style="text-align:center;"><b>Predict the Direction<br>(3 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li><label><input type="checkbox"> State whether the result will be too high or too low.</label></li>
              <li><label><input type="checkbox"> Explain why, using a feature of the sampled vs. missed groups.</label></li>
            </ul></td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Improved Design<br>(3 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li><label><input type="checkbox"> Propose a randomized sampling method.</label></li>
              <li><label><input type="checkbox"> Name the sampling frame (the full population list).</label></li>
            </ul></td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>';

$rubricanswerbutton = $css_block . '
<div class="rubric-container">
  <details>
    <summary>Rubric &amp; Model Response</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Checklist &amp; Ideal Targets</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Identify the Bias<br>(4 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li>Name the bias.<span class="ideal-ans">Target: "'.$ans_bias.'"</span></li>
              <li>Explain why this plan produces the bias.</li>
            </ul></td></tr>
          <tr><td style="text-align:center;"><b>Predict Direction<br>(3 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li>State direction.<span class="ideal-ans">Target: "will likely '.$ans_skew.'"</span></li>
            </ul></td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Improved Design<br>(3 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li>Propose better sampling.<span class="ideal-ans">Target: "'.$ans_fix.'"</span></li>
            </ul></td></tr>
        </tbody>
      </table>
      <div class="full-response-box">
        <span style="color:#2E7D32; font-weight:bold;">Model Narrative Response:</span><br><br>
        '.$model_response.'
      </div>
    </div>
  </details>
</div>';

$questiontext = '
<div style="font-family:Arial; font-size:medium; line-height:1.6;">
  <p>'.$ctx_goal.'</p>
  <p><b>Sampling plan:</b> '.$ctx_plan.'</p>
  <p><b>Essay Prompt:</b><br>
  Critique this sampling plan. In your response, address:</p>
  <ul>
    <li>What type of sampling bias is present, and why this plan produces it.</li>
    <li>The likely direction of error in the result (too high or too low) and why.</li>
    <li>A better randomized sampling design, including the sampling frame.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
