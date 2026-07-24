// === NAME - DESCRIPTION: What Inference Really Says - Students interpret a confidence interval or significance result in plain context and name a common misinterpretation to avoid ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

$scenarios = array(
  array(
    "A 95% confidence interval for the mean daily commute time of a city's workers is (24, 31) minutes.",
    "We are 95% confident that the true mean commute time for all of the city's workers (the population mean) is between 24 and 31 minutes. The word confidence refers to the method: if we repeated the sampling many times, about 95% of the intervals we built would contain the true mean.",
    "It is wrong to say 95% of workers commute between 24 and 31 minutes, because the interval is about the mean and not individual workers; it is also wrong to say there is a 95% probability the true mean lies in this one interval, since the true mean is fixed and the interval either captures it or it does not."
  ),
  array(
    "A study testing whether a new fertilizer changes mean crop yield reports a p-value of 0.03, which is significant at alpha = 0.05.",
    "Because the p-value of 0.03 is below 0.05, there is statistically significant evidence that the mean yield differs from before, so we reject the null hypothesis. If the fertilizer truly had no effect, we would see results this extreme only about 3% of the time.",
    "The p-value is not the probability that the null hypothesis is true, and statistically significant does not mean the effect is large or practically important."
  ),
  array(
    "A 90% confidence interval for the proportion of voters who support a ballot measure is (0.46, 0.54).",
    "We are 90% confident that the true proportion of all voters who support the measure is between 46% and 54%. Because the interval includes 0.50, the data do not clearly show majority support one way or the other.",
    "It is wrong to say 90% of voters support the measure, and wrong to say there is a 90% chance the true proportion falls in this specific interval."
  ),
  array(
    "A study comparing two teaching methods finds no significant difference in mean test scores (p-value = 0.21).",
    "Because the p-value of 0.21 is above common significance levels, there is not enough evidence to conclude the two methods differ in mean score, so we fail to reject the null hypothesis.",
    "Failing to find a significant difference does not prove the two methods are equally effective; absence of evidence is not the same as evidence of absence."
  )
);

$i = rand(0, count($scenarios)-1);
$s = $scenarios[$i];
$result     = $s[0];
$correctText = $s[1];
$misText     = $s[2];

$model_text = "<b>Correct interpretation:</b> " . $correctText . "<br><br><b>Misinterpretation to avoid:</b> " . $misText;

$css_block = '
<style>
  .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .rubric-container summary::-webkit-details-marker { display:none; }
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
      <p style="margin:0 0 0.5em 0;"><b>Grading Criteria</b> -- your answer should address:</p>
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Correct Interpretation<br>(6 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li><label><input type="checkbox"> Interpret the result correctly in the context of the problem.</label></li>
              <li><label><input type="checkbox"> Refer to the population parameter (mean or proportion), with units or context.</label></li>
            </ul></td></tr>
          <tr><td style="text-align:center;"><b>Misinterpretation to Avoid<br>(4 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li><label><input type="checkbox"> Name one common wrong interpretation and explain why it is wrong.</label></li>
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
      <p>Look for: a correct, context-anchored interpretation that names the population parameter, plus one clearly stated misinterpretation (such as confusing the interval with individuals, treating the p-value as P(H0 true), or reading "not significant" as proof of no effect). Plain language is fine.</p>
      <div class="full-response-box">
        <span style="color:#2E7D32; font-weight:bold;">Model Narrative Response:</span><br><br>
        '.$model_text.'
      </div>
    </div>
  </details>
</div>';

$questiontext = '
<div style="font-family:Arial; font-size:medium; line-height:1.6;">
  <p><b>Reported result:</b> '.$result.'</p>
  <p><b>Essay Prompt:</b><br>
  In a short paragraph, explain what this result really tells us. Be sure to cover:</p>
  <ul>
    <li>A correct interpretation of the result in context (refer to the population mean or proportion).</li>
    <li>One common misinterpretation people make here, and why it is wrong.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
