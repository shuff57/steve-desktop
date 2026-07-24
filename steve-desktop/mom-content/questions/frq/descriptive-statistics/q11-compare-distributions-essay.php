// === NAME - DESCRIPTION: Compare Two Distributions in Context - Students compare two groups using shape, center, spread, and outliers, write a context-anchored verbal comparison ===
// === SET QUESTION TYPE TO: essay ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

$scenarios = array(
  array(
    "two algebra classes' end-of-semester exam scores",
    "Class A", 78, 82, 6, "roughly symmetric, no outliers", 60, 90,
    "Class B", 75, 76, 11, "right-skewed with two unusually low outliers near 40", 40, 95,
    "Class A is centered slightly higher and is much more consistent. Class B has a similar typical performance but is more variable, with a few unusually low scores stretching the distribution to the left."
  ),
  array(
    "the wait times (in minutes) at the drive-through of two competing coffee shops",
    "Shop A", 3.2, 3.0, 0.8, "roughly symmetric, no outliers", 1.5, 5.0,
    "Shop B", 4.1, 3.5, 1.8, "right-skewed with several long-wait outliers above 9 min", 1.0, 11.5,
    "Shop A is faster on average and much more consistent than Shop B. Shop B not only has a higher typical wait but also produces a few very long waits, making it less reliable."
  ),
  array(
    "the number of weekly hours of sleep reported by two grade levels",
    "Sophomores", 7.4, 7.5, 0.9, "roughly symmetric, no outliers", 5.5, 9.5,
    "Seniors", 6.2, 6.0, 1.4, "left-skewed with a low outlier near 3 hrs", 3.0, 9.0,
    "Sophomores sleep more on average and more consistently. Seniors get less typical sleep and show more variability, including at least one student getting very little sleep."
  )
);

$i = rand(0, count($scenarios)-1);
$s = $scenarios[$i];
$ctx        = $s[0];
$labelA     = $s[1];
$meanA      = $s[2];
$medianA    = $s[3];
$sdA        = $s[4];
$shapeA     = $s[5];
$minA       = $s[6];
$maxA       = $s[7];
$labelB     = $s[8];
$meanB      = $s[9];
$medianB    = $s[10];
$sdB        = $s[11];
$shapeB     = $s[12];
$minB       = $s[13];
$maxB       = $s[14];
$model_text = $s[15];

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
  .summary-table { border-collapse:collapse; margin:0.6em 0; font-family:Arial; font-size:14px; }
  .summary-table th, .summary-table td { border:1px solid #ccc; padding:6px 12px; }
  .summary-table th { background:#f2f2f2; }
</style>';

$rubricbutton = $css_block . '
<div class="rubric-container">
  <details>
    <summary>Click to View Grading Checklist</summary>
    <div class="rubric-content">
      <p style="margin:0 0 0.5em 0;"><b>Grading Criteria</b> -- your comparison should address:</p>
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Shape<br>(2 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li><label><input type="checkbox"> Describe the shape of each distribution.</label></li>
              <li><label><input type="checkbox"> Note any obvious outliers.</label></li>
            </ul></td></tr>
          <tr><td style="text-align:center;"><b>Center<br>(3 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li><label><input type="checkbox"> Compare the typical value (mean OR median, justified by shape).</label></li>
              <li><label><input type="checkbox"> State which group has a higher center, in context.</label></li>
            </ul></td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Spread<br>(3 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li><label><input type="checkbox"> Compare the spread (SD or `"IQR"`).</label></li>
              <li><label><input type="checkbox"> State which group is more variable, in context.</label></li>
            </ul></td></tr>
          <tr><td style="text-align:center;"><b>In-Context Verdict<br>(2 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li><label><input type="checkbox"> Write a concluding sentence using the real-world variable, not just numbers.</label></li>
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
      <p>Look for: shape + outliers, center comparison (mean OR median, chosen by shape), spread comparison (SD or `"IQR"`), and an in-context conclusion. Penalize purely numeric answers with no real-world interpretation.</p>
      <div class="full-response-box">
        <span style="color:#2E7D32; font-weight:bold;">Model Narrative Response:</span><br><br>
        '.$model_text.'
      </div>
    </div>
  </details>
</div>';

$summaryTable = '
<table class="summary-table">
<tr><th></th><th>Mean</th><th>Median</th><th>s (SD)</th><th>Min</th><th>Max</th><th>Shape</th></tr>
<tr><td><b>'.$labelA.'</b></td><td>'.$meanA.'</td><td>'.$medianA.'</td><td>'.$sdA.'</td><td>'.$minA.'</td><td>'.$maxA.'</td><td>'.$shapeA.'</td></tr>
<tr><td><b>'.$labelB.'</b></td><td>'.$meanB.'</td><td>'.$medianB.'</td><td>'.$sdB.'</td><td>'.$minB.'</td><td>'.$maxB.'</td><td>'.$shapeB.'</td></tr>
</table>';

$questiontext = '
<div style="font-family:Arial; font-size:medium; line-height:1.6;">
  <p>The summary statistics below describe '.$ctx.'.</p>
  '.$summaryTable.'
  <p><b>Essay Prompt:</b><br>
  Write a paragraph comparing the two distributions. Address shape, center, spread, and any unusual observations, and finish with a one-sentence verdict in the context of the variable.</p>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
