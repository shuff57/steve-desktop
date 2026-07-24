// === NAME - DESCRIPTION: CI for a Mean - Interpret + Critique Conditions - Students interpret a one-mean t-CI in context, then critique whether the conditions for the t-procedure are reasonable ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

$scenarios = array(
  array(
    "the mean daily screen time (in hours) of middle-school students at a particular district",
    "daily screen time", "hours", 320, 4.8, 6.2, 0.95,
    "the sample was a convenience sample of students whose parents responded to a school newsletter",
    "the sampling method may not produce a representative sample; a simple random sample of all middle-schoolers would be stronger"
  ),
  array(
    "the mean weekly grocery spending (in dollars) for households in a town",
    "weekly grocery spending", "dollars", 84, 142.30, 178.95, 0.95,
    "the data are right-skewed and one household reported $620, far above the rest",
    "with `n = 84` the CLT typically protects the t-procedure even under moderate skew, but the outlier should be checked for accuracy before relying on the interval"
  ),
  array(
    "the mean recovery time (in days) after a particular knee surgery",
    "recovery time", "days", 28, 36.4, 44.1, 0.90,
    "the sample is small (`n = 28`) and the histogram looks roughly symmetric with no large outliers",
    "with `n = 28` the t-procedure is reasonable IF the data are approximately normal; the symmetric histogram supports this assumption"
  ),
  array(
    "the mean daily caffeine intake (mg) of professional drivers",
    "daily caffeine intake", "mg", 64, 388, 442, 0.95,
    "the sample was drawn from a single trucking company's drivers",
    "the interval only generalizes to drivers like those at this company; sampling multiple companies would broaden the inference"
  )
);

$i = rand(0, count($scenarios)-1);
$s = $scenarios[$i];
$ctx       = $s[0];
$varName   = $s[1];
$units     = $s[2];
$n         = $s[3];
$lo        = $s[4];
$hi        = $s[5];
$conf      = $s[6];
$concern   = $s[7];
$resolution = $s[8];
$confPct = $conf * 100;

$model_response = "<b>Interpretation:</b> We are " . $confPct . "% confident that the true mean " . $varName . " (population parameter `mu`) is between " . $lo . " and " . $hi . " " . $units . ".<br><br><b>What confidence means:</b> If we drew many random samples of size `n = " . $n . "` and built a " . $confPct . "% interval from each, about " . $confPct . "% of those intervals would contain `mu`.<br><br><b>Critique of conditions:</b> The concern here is that " . $concern . ". In this case, " . $resolution . ".";

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
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Interpret the Interval<br>(4 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li><label><input type="checkbox"> Reference the <b>population mean</b> (`mu`), not the sample mean.</label></li>
              <li><label><input type="checkbox"> Include the units and the real variable being measured.</label></li>
            </ul></td></tr>
          <tr><td style="text-align:center;"><b>Meaning of Confidence<br>(3 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li><label><input type="checkbox"> Describe what 95% (or stated) confidence means in terms of repeated sampling.</label></li>
              <li><label><input type="checkbox"> Avoid the misinterpretation "95% chance `mu` is in this interval".</label></li>
            </ul></td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Critique Conditions<br>(3 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li><label><input type="checkbox"> Name a condition issue (sampling, skew/outliers, or independence).</label></li>
              <li><label><input type="checkbox"> Say whether the t-procedure is reasonable here, and why.</label></li>
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
      <p>Look for: a population-mean interpretation in context, an accurate description of the long-run-frequency meaning of confidence, and a substantive critique of conditions (sampling, outliers, normality with n).</p>
      <div class="full-response-box">
        <span style="color:#2E7D32; font-weight:bold;">Model Narrative Response:</span><br><br>
        '.$model_response.'
      </div>
    </div>
  </details>
</div>';

$questiontext = '
<div style="font-family:Arial; font-size:medium; line-height:1.6;">
  <p>A researcher is estimating '.$ctx.'. From a random sample of n = '.$n.' subjects, the resulting <b>'.$confPct.'% t-confidence interval</b> for the population mean is <b>('.$lo.', '.$hi.') '.$units.'</b>.</p>
  <p>Additional information: '.$concern.'.</p>
  <p><b>Essay Prompt:</b><br>
  Write a paragraph that addresses:</p>
  <ul>
    <li>Interpret the interval in context (units and variable).</li>
    <li>Explain what '.$confPct.'% confidence means.</li>
    <li>Critique whether the conditions for the t-procedure are reasonable here.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
