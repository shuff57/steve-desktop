// === NAME - DESCRIPTION: Reading a Study - Students classify a study as observational or experimental, name a plausible source of bias or confounding, and state what conclusion is and is not justified ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

$scenarios = array(
  array(
    "A health blog reports that people who drink green tea every day tend to weigh less than people who do not. The data came from an online survey in which readers reported their own tea habits and weight.",
    "This is an observational study, because researchers only recorded habits people already had and did not assign anyone to drink green tea.",
    "A likely problem is confounding (green-tea drinkers may also exercise more or eat better), along with self-selection, since only blog readers who chose to respond are included.",
    "Because there was no random assignment, we can only say green tea is associated with lower weight in this group; we cannot conclude that green tea causes weight loss."
  ),
  array(
    "A school tries a new math app with one class and compares its test scores to another class that used the old materials. Students were not randomly assigned, and the app class happened to be the honors section.",
    "This is a comparison without random assignment, so it behaves like an observational study rather than a true experiment.",
    "The two classes differ in ability (the app class was the honors section), which is a confounding variable.",
    "We cannot conclude the app caused the higher scores, because the honors students might have scored higher anyway."
  ),
  array(
    "A fitness company randomly assigns 200 volunteers to either a new workout plan or a standard plan, then measures their weight loss after 8 weeks.",
    "This is an experiment, because the volunteers were randomly assigned to the new plan or the standard plan.",
    "The main limitation is that the participants were volunteers, so the results may not generalize to people who would not volunteer.",
    "Random assignment lets us conclude the workout plan caused the difference in weight loss for these participants, although we should be cautious about generalizing beyond similar volunteers."
  ),
  array(
    "A researcher notices that towns with more ice cream shops tend to report higher crime rates, using data collected from town records.",
    "This is an observational study based on recorded town data, with no treatment assigned.",
    "A lurking variable such as town population size or warm weather likely drives both the number of ice cream shops and the amount of crime.",
    "We cannot conclude that ice cream causes crime; the relationship is almost certainly explained by a confounding variable."
  )
);

$i = rand(0, count($scenarios)-1);
$s = $scenarios[$i];
$study      = $s[0];
$typeText   = $s[1];
$biasText   = $s[2];
$conclText  = $s[3];

$model_text = "<b>Study type:</b> " . $typeText . "<br><br><b>Source of bias or confounding:</b> " . $biasText . "<br><br><b>Justified conclusion:</b> " . $conclText;

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
          <tr class="row-colored"><td style="text-align:center;"><b>Study Type<br>(3 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li><label><input type="checkbox"> Classify the study as observational or experimental.</label></li>
              <li><label><input type="checkbox"> Justify it by whether a treatment was randomly assigned.</label></li>
            </ul></td></tr>
          <tr><td style="text-align:center;"><b>Bias or Confounding<br>(4 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li><label><input type="checkbox"> Name one specific, plausible source of bias or a confounding variable.</label></li>
              <li><label><input type="checkbox"> Explain briefly why it is a problem here.</label></li>
            </ul></td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Justified Conclusion<br>(3 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li><label><input type="checkbox"> State what can and cannot be concluded (association vs causation, or limits on generalizing).</label></li>
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
      <p>Look for: a correct observational/experimental classification tied to random assignment, one specific bias or confounder explained in context, and a conclusion that respects association-vs-causation and the limits of generalizing. Full marks do not require technical jargon, just clear reasoning.</p>
      <div class="full-response-box">
        <span style="color:#2E7D32; font-weight:bold;">Model Narrative Response:</span><br><br>
        '.$model_text.'
      </div>
    </div>
  </details>
</div>';

$questiontext = '
<div style="font-family:Arial; font-size:medium; line-height:1.6;">
  <p><b>Scenario:</b> '.$study.'</p>
  <p><b>Essay Prompt:</b><br>
  In a short paragraph, react to this study. Be sure to cover:</p>
  <ul>
    <li>Is this an observational study or an experiment? How do you know?</li>
    <li>Name one plausible source of bias or a confounding variable, and say why it matters.</li>
    <li>What conclusion is, and is not, justified from this study?</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
