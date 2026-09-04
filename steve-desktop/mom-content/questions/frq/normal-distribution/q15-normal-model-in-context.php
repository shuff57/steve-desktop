// === NAME - DESCRIPTION: Normal Model in Context - Students apply a normal model to a real-world scenario: compute a probability, interpret it in context, address the bell-shape assumption ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

$scenarios = array(
  array(
    "the lifetimes of a particular brand of LED bulb",
    "lifetime", "hours", 18000, 1500, 16000, "below",
    "lifetimes of mass-produced bulbs often cluster symmetrically around a target, so a normal model is reasonable; the company should verify no large skew in QA test data",
    "burn-out rate"
  ),
  array(
    "the systolic blood pressure of adult women under 40",
    "systolic blood pressure", "mmHg", 115, 12, 130, "above",
    "blood pressure in healthy adults tends to follow a roughly symmetric distribution, so a normal model is reasonable; outliers from undiagnosed hypertension or hypotension would be worth investigating",
    "fraction needing a follow-up screening"
  ),
  array(
    "the daily caloric intake of college students at a large university",
    "daily caloric intake", "calories", 2200, 350, 1700, "below",
    "daily intake often shows mild right-skew (occasional very large eating days), so the normal model is a fair approximation but the company should check a histogram for symmetry",
    "share of students below a recommended threshold"
  ),
  array(
    "the diameters of a machined steel rod produced by a factory",
    "rod diameter", "mm", 10.05, 0.02, 10.08, "above",
    "machined parts from a tightly controlled process typically follow a near-normal distribution, so the model is reasonable; engineers should monitor SPC charts for sudden shifts",
    "fraction outside the spec"
  )
);

$i = rand(0, count($scenarios)-1);
$s = $scenarios[$i];
$ctx       = $s[0];
$varName   = $s[1];
$units     = $s[2];
$mu        = $s[3];
$sigma     = $s[4];
$threshold = $s[5];
$direction = $s[6]; // "above" or "below"
$assumption_note = $s[7];
$practical = $s[8];

$z = ($threshold - $mu) / $sigma;
$prob_below = normalcdf($z);
$prob_above = 1 - $prob_below;
$prob = $direction == "above" ? $prob_above : $prob_below;
$pct = round($prob * 100, 1);

$model_response = "<b>Set up:</b> Let `X` = $varName ($units). Model `X ~ N($mu, $sigma)`.<br><br><b>Compute:</b> `z = ($threshold - $mu) / $sigma = " . round($z, 3) . "`. So `P(X $direction $threshold) = " . round($prob, 4) . "`, or about $pct%.<br><br><b>Interpret:</b> About $pct% of units have $varName $direction $threshold $units. This is the company's $practical based on the normal model.<br><br><b>Assumption check:</b> The normal model assumes a bell-shaped, symmetric distribution. Here, $assumption_note.";

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
      <p><b>Grading Criteria</b> -- ensure your essay covers:</p>
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Setup &amp; Compute<br>(4 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li><label><input type="checkbox"> Define `X` with its units and state the normal model with `mu`, `sigma`.</label></li>
              <li><label><input type="checkbox"> Compute the relevant z-score and the probability (or percent).</label></li>
            </ul></td></tr>
          <tr><td style="text-align:center;"><b>Interpret in Context<br>(4 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li><label><input type="checkbox"> State what the probability means in plain English using the variable name.</label></li>
              <li><label><input type="checkbox"> Link the answer to a practical decision or quantity.</label></li>
            </ul></td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Assumption Check<br>(2 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li><label><input type="checkbox"> State that the normal model assumes symmetry / bell shape.</label></li>
              <li><label><input type="checkbox"> Comment on whether the assumption is reasonable for this scenario.</label></li>
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
      <p>Expected probability: <b>'.round($prob, 4).' (' . $pct . '%)</b>. Look for the explicit setup, the z-computation, an in-context interpretation, and a comment on the bell-shape assumption.</p>
      <div class="full-response-box">
        <span style="color:#2E7D32; font-weight:bold;">Model Narrative Response:</span><br><br>
        '.$model_response.'
      </div>
    </div>
  </details>
</div>';

$questiontext = '
<div style="font-family:Arial; font-size:medium; line-height:1.6;">
  <p>An analyst is studying '.$ctx.'. Assume the '.$varName.' follows a normal distribution with mean `mu = '.$mu.'` and standard deviation `sigma = '.$sigma.'` ('.$units.').</p>
  <p><b>Essay Prompt:</b><br>
  Estimate the proportion of values that fall '.$direction.' '.$threshold.' '.$units.', and write up your analysis. In your response, address:</p>
  <ul>
    <li>Setup: define the variable and state the normal model with its parameters.</li>
    <li>Compute the relevant z-score and probability, and report the percent.</li>
    <li>Interpret the percent in the context of the scenario (and the practical decision it informs).</li>
    <li>Discuss whether the normal-distribution assumption is reasonable here.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
