// === NAME - DESCRIPTION: Slope-Inference Full Interpretation - From a regression output, students interpret the slope, the slope test (t, p-value), and discuss linearity assumption ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

$scenarios = array(
  array(
    "hours of weekly exercise", "resting heart rate (bpm)",
    78.4, -1.34, 0.42, 25, 0.05,
    "A scatterplot of the data shows a clear negative linear pattern with constant scatter and no obvious curvature; the residual plot looks like random noise.",
    "linearity is reasonable based on the scatterplot and residuals"
  ),
  array(
    "monthly advertising spend (\$1000s)", "monthly sales (\$1000s)",
    12.4, 2.07, 0.31, 22, 0.05,
    "The scatterplot suggests the relationship may curve at high spend, with sales leveling off. The residual plot has a slight U-shape.",
    "linearity is questionable: the residual plot's U-shape and the leveling-off in the scatterplot suggest a transformation or a curved model may be needed"
  ),
  array(
    "years of teaching experience", "student survey rating (1-5)",
    3.8, 0.018, 0.022, 40, 0.05,
    "The scatterplot is a noisy cloud with no obvious linear trend; residuals look random but the slope estimate is small.",
    "the linear model is technically fit-able but the relationship is so weak that practical interpretation is limited"
  ),
  array(
    "study time (hr/week)", "exam score",
    52.1, 4.85, 0.92, 30, 0.05,
    "The scatterplot is roughly linear with constant scatter and no large outliers; residuals look random.",
    "linearity is reasonable based on the scatterplot and residuals"
  )
);

$i = rand(0, count($scenarios)-1);
$s = $scenarios[$i];
$xname = $s[0];
$yname = $s[1];
$b0    = $s[2];
$b1    = $s[3];
$sb1   = $s[4];
$n     = $s[5];
$alpha = $s[6];
$plot_note = $s[7];
$lin_verdict = $s[8];

$df = $n - 2;
$t = $b1 / $sb1;
$pval = 2 * (1 - tcdf(abs($t), $df));
$reject = $pval < $alpha;

$decision = $reject ? "Reject `H_0: beta_1 = 0`" : "Fail to reject `H_0: beta_1 = 0`";
$slope_word = $b1 > 0 ? "increases" : "decreases";
$slope_abs = abs($b1);

$model_response = "<b>Equation:</b> `hat(y) = " . $b0 . " + " . $b1 . " * x` (where `x` = " . $xname . ").<br><br><b>Slope in context:</b> for each one-unit increase in " . $xname . ", the predicted " . $yname . " " . $slope_word . " by " . $slope_abs . " on average.<br><br><b>Slope test:</b> `t = b_1 / \"SE\"(b_1) = " . round($t, 3) . "` with `\"df\" = " . $df . "`. Two-tailed p-value `~~ " . round($pval, 4) . "`.<br><br><b>Decision:</b> " . $decision . " at `alpha = " . $alpha . "`. " . ($reject ? "There is significant evidence that the true slope is not zero, i.e., there is a meaningful linear relationship between " . $xname . " and " . $yname . " in the population." : "There is not significant evidence of a linear relationship in the population.") . "<br><br><b>Linearity check:</b> " . $lin_verdict . ".";

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
  .reg-table { border-collapse:collapse; margin:0.5em 0; font-family:Arial; font-size:14px; }
  .reg-table th, .reg-table td { border:1px solid #ccc; padding:6px 10px; }
  .reg-table th { background:#f2f2f2; }
</style>';

$rubricbutton = $css_block . '
<div class="rubric-container">
  <details>
    <summary>Click to View Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Slope in Context<br>(3 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li><label><input type="checkbox"> Interpret the slope as a per-unit change in y for a one-unit change in x.</label></li>
              <li><label><input type="checkbox"> Use the units of both variables.</label></li>
            </ul></td></tr>
          <tr><td style="text-align:center;"><b>Slope Test<br>(4 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li><label><input type="checkbox"> Compute `t = b_1 / "SE"(b_1)` with `"df" = n - 2`.</label></li>
              <li><label><input type="checkbox"> Report the two-tailed p-value and a reject / fail-to-reject decision at `alpha`.</label></li>
            </ul></td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Linearity Discussion<br>(3 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li><label><input type="checkbox"> Reference the scatterplot / residual plot description.</label></li>
              <li><label><input type="checkbox"> State whether the linear model is appropriate, and why.</label></li>
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
      <p>Expected: t &approx; '.round($t, 3).', p &approx; '.round($pval, 4).', '.$decision.'.</p>
      <div class="full-response-box">
        <span style="color:#2E7D32; font-weight:bold;">Model Narrative Response:</span><br><br>
        '.$model_response.'
      </div>
    </div>
  </details>
</div>';

$outputTable = '
<table class="reg-table">
<tr><th>Predictor</th><th>Coefficient</th><th>Std. Error</th></tr>
<tr><td>(Intercept)</td><td style="text-align:right;">'.$b0.'</td><td style="text-align:right;">-</td></tr>
<tr><td>'.$xname.'</td><td style="text-align:right;">'.$b1.'</td><td style="text-align:right;">'.$sb1.'</td></tr>
</table>
<p style="margin:0.2em 0;">`n = '.$n.'`, so `"df" = '.$df.'`.</p>';

$questiontext = '
<div style="font-family:Arial; font-size:medium; line-height:1.6;">
  <p>A least-squares regression of <b>'.$yname.'</b> on <b>'.$xname.'</b> produced this partial output:</p>
  '.$outputTable.'
  <p><b>Plot evidence:</b> '.$plot_note.'</p>
  <p><b>Essay Prompt:</b><br>
  Write up a full slope-inference analysis. Address:</p>
  <ul>
    <li>Interpret the slope in context (with units).</li>
    <li>Test `H_0: beta_1 = 0` vs `H_a: beta_1 != 0` at `alpha = '.$alpha.'` (state `t`, `"df"`, p-value, decision).</li>
    <li>Comment on whether the linear model is appropriate, citing the plot evidence above.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
