// === NAME - DESCRIPTION: Two-Proportion HT - Full Workflow in Context - Students write H0/Ha, compute z + p-value, and write a context-anchored conclusion for a two-proportion comparison ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

$scenarios = array(
  array(
    "Two community-health clinics report flu-shot uptake during a campaign.",
    "Clinic A", 244, 600, "Clinic B", 188, 500,
    0.05,
    "Is the uptake rate different between the two clinics?",
    "two clinics", "uptake rate"
  ),
  array(
    "A SaaS company A/B-tested a redesigned onboarding flow.",
    "Variant A (current)", 312, 1000, "Variant B (new)", 366, 1000,
    0.05,
    "Does the new variant have a different completion rate from the current?",
    "two onboarding flows", "completion rate"
  ),
  array(
    "Two physical-therapy clinics report knee-rehab outcomes.",
    "Clinic Maple", 124, 180, "Clinic Oak", 96, 165,
    0.05,
    "Do the two clinics differ in full-recovery rate?",
    "two clinics", "full-recovery rate"
  ),
  array(
    "Two cities polled adults about a transit plan.",
    "City P", 156, 280, "City Q", 134, 260,
    0.05,
    "Do the two cities have different approval rates?",
    "two cities", "approval rate"
  )
);

$i = rand(0, count($scenarios)-1);
$s = $scenarios[$i];
$ctx_intro = $s[0];
$label1 = $s[1]; $x1 = $s[2]; $n1 = $s[3];
$label2 = $s[4]; $x2 = $s[5]; $n2 = $s[6];
$alpha  = $s[7];
$question = $s[8];
$units_compare = $s[9];
$ratename = $s[10];

$p1 = $x1 / $n1;
$p2 = $x2 / $n2;
$pPool = ($x1 + $x2) / ($n1 + $n2);
$se = sqrt($pPool * (1 - $pPool) * (1/$n1 + 1/$n2));
$z = ($p1 - $p2) / $se;
$pval = 2 * (1 - normalcdf(abs($z)));
$reject = 0
if ($pval < $alpha) { $reject = 1 }

$decisionText = "FAIL TO REJECT `H_0`"
$concText = "At `alpha = " . $alpha . "`, we do NOT have significant evidence that the " . $ratename . " differs between the two groups."
if ($reject == 1) {
  $decisionText = "REJECT `H_0`"
  $concText = "At `alpha = " . $alpha . "`, we have significant evidence that the " . $ratename . " differs between the two groups."
}

$model_response = "<b>Hypotheses:</b> `H_0: p_1 = p_2` (the two " . $units_compare . " have the same " . $ratename . "). `H_a: p_1 != p_2`.<br><br><b>Sample proportions:</b> `hat(p)_1 = " . round($p1, 4) . "`, `hat(p)_2 = " . round($p2, 4) . "`. Pooled `hat(p) = " . round($pPool, 4) . "`.<br><br><b>Test statistic:</b> `z = (hat(p)_1 - hat(p)_2) / \"SE\" = " . round($z, 3) . "`. <b>p-value (two-tailed):</b> `" . round($pval, 4) . "`.<br><br><b>Decision:</b> " . $decisionText . ".<br><br><b>Conclusion:</b> " . $concText;

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
          <tr class="row-colored"><td style="text-align:center;"><b>Hypotheses<br>(2 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li><label><input type="checkbox"> State `H_0` and `H_a` using `p_1` and `p_2` (population proportions, not sample proportions).</label></li>
              <li><label><input type="checkbox"> Choose two-tailed alternative if the prompt asks "different".</label></li>
            </ul></td></tr>
          <tr><td style="text-align:center;"><b>Computation<br>(4 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li><label><input type="checkbox"> Report `hat(p)_1`, `hat(p)_2`, pooled `hat(p)`.</label></li>
              <li><label><input type="checkbox"> Report z-statistic and p-value (two decimal places for `z`, four for `p`).</label></li>
            </ul></td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Conclusion in Context<br>(4 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li><label><input type="checkbox"> Compare `p` to `alpha` and write reject / fail-to-reject.</label></li>
              <li><label><input type="checkbox"> Write the conclusion in context (mention the variable and the two groups), not as "reject `H_0`".</label></li>
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
      <p>Expected: z &approx; '.round($z, 3).', p &approx; '.round($pval, 4).', '.$decisionText.'.</p>
      <div class="full-response-box">
        <span style="color:#2E7D32; font-weight:bold;">Model Narrative Response:</span><br><br>
        '.$model_response.'
      </div>
    </div>
  </details>
</div>';

$questiontext = '
<div style="font-family:Arial; font-size:medium; line-height:1.6;">
  <p>'.$ctx_intro.' <b>'.$label1.':</b> '.$x1.' of '.$n1.' qualified. <b>'.$label2.':</b> '.$x2.' of '.$n2.' qualified.</p>
  <p><b>Research question:</b> '.$question.' Use a two-tailed pooled two-proportion z-test at `alpha = '.$alpha.'`.</p>
  <p><b>Essay Prompt:</b><br>
  Write a full hypothesis-test write-up. Your answer should include:</p>
  <ul>
    <li>The hypotheses (using population parameters).</li>
    <li>Sample proportions and the pooled proportion.</li>
    <li>The z-statistic and the p-value.</li>
    <li>A decision (reject or fail to reject) and a conclusion in context.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
