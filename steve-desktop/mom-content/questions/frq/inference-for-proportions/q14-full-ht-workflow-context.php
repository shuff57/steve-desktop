// === NAME - DESCRIPTION: Full Hypothesis Test Workflow in Context - Students set up hypotheses, verify conditions, compute the test statistic and p-value, make a decision at a stated significance level, and write a conclusion that connects the result back to the original claim. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
// Each scenario: (context phrase, parameter, claim value p0, sample x successes, sample n, alpha, direction).
// direction: 0 = right-tailed (Ha: p > p0), 1 = left-tailed (Ha: p < p0), 2 = two-tailed (Ha: p != p0)

$contexts = array(
  "A coffee shop manager claims that more than 30% of customers buy the seasonal drink. In a random sample of 200 customers, 72 bought the seasonal drink.",
  "A teacher claims that less than 60% of students complete homework on time. In a random sample of 150 students, 78 completed it on time.",
  "A polling firm wants to test whether the proportion of registered voters who support a new bill is different from 50%. In a random sample of 250 voters, 138 said they support it."
);

$populations  = array("all customers at the coffee shop", "all students in the class", "all registered voters");
$measurements = array("buying the seasonal drink",         "completing homework on time",  "supporting the new bill");
$p0s          = array(0.30, 0.60, 0.50);
$p0_pcts      = array("30%","60%", "50%");
$xs           = array(72,   78,    138);
$ns           = array(200,  150,   250);
$alphas       = array(0.05, 0.05,  0.05);
$dirs         = array(0,    1,     2);

$i = rand(0, 2);
$topic         = $contexts[$i];
$population    = $populations[$i];
$measurement   = $measurements[$i];
$p0            = $p0s[$i];
$p0_pct        = $p0_pcts[$i];
$x             = $xs[$i];
$n             = $ns[$i];
$alpha_v       = $alphas[$i];
$dir           = $dirs[$i];

$phat = round($x / $n, 4);
$se   = round(sqrt($p0 * (1 - $p0) / $n), 4);
$z    = round(($phat - $p0) / $se, 3);

if ($dir == 0) {
  $h0_text = "p = $p0";
  $ha_text = "p &gt; $p0";
  $direction_word = "right-tailed";
  $p_value = round(1 - normalcdf($z), 4);
}
if ($dir == 1) {
  $h0_text = "p = $p0";
  $ha_text = "p &lt; $p0";
  $direction_word = "left-tailed";
  $p_value = round(normalcdf($z), 4);
}
if ($dir == 2) {
  $h0_text = "p = $p0";
  $ha_text = "p &ne; $p0";
  $direction_word = "two-tailed";
  $p_value = round(2 * (1 - normalcdf(abs($z))), 4);
}

if ($p_value <= $alpha_v) {
  $decision = "reject `H_0`";
  $conclusion = "there is statistically significant evidence at the `alpha = $alpha_v` level to support the claim that the proportion of $population $measurement is different from $p0_pct in the direction stated";
} else {
  $decision = "fail to reject `H_0`";
  $conclusion = "there is NOT enough evidence at the `alpha = $alpha_v` level to support the claim about the proportion of $population $measurement; we cannot conclude the alternative is true";
}

$np0  = round($n * $p0, 1);
$nq0  = round($n * (1 - $p0), 1);

$sample_narrative = "<b>Hypotheses</b>: `H_0: $h0_text` vs. `H_a: $ha_text` (a $direction_word test). <b>Conditions</b>: the sample is random; the population is at least `10 cdot $n`; and the large-counts conditions hold since `n cdot p_0 = $np0 >= 10` and `n cdot (1 - p_0) = $nq0 >= 10`. <b>Test statistic</b>: `\hat{p} = $x/$n = $phat`, `SE = sqrt(p_0 (1-p_0)/n) = $se`, so `z = (\hat{p} - p_0)/SE = $z`. <b>P-value</b>: `$p_value`. <b>Decision</b>: $decision because the p-value $p_value " . (($p_value <= $alpha_v) ? "is less than or equal to" : "is greater than") . " `alpha = $alpha_v`. <b>Conclusion in context</b>: $conclusion.";

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

/* ---------- 3. Student Rubric ---------- */
$rubricbutton = $css_block . '
<div class="rubric-container">
  <details>
    <summary>
      <span class="arrow-closed">&#9656;</span><span class="arrow-open">&#9662;</span>
      Click to View Grading Checklist
    </summary>
    <div class="rubric-content">
      <p style="margin:0 0 0.5em 0;"><b>Grading Criteria</b> -- ensure your response covers each step of the test:</p>
      <table class="rubric-table">
        <tbody>
          <tr>
            <th class="col-header">Category</th>
            <th class="col-check">Requirement</th>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Hypotheses</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State `H_0` and `H_a` using symbols, in the right direction (one- or two-tailed).</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Conditions</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Random sample.</label></li>
                <li><label><input type="checkbox"> Independence (population at least `10n`).</label></li>
                <li><label><input type="checkbox"> Large-counts: `n cdot p_0 >= 10` and `n cdot (1 - p_0) >= 10`, with values shown.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Test statistic + p-value</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Compute `\hat{p}`, `SE`, and the z-statistic.</label></li>
                <li><label><input type="checkbox"> Compute the p-value matching the alternative direction.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Decision + Conclusion</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Compare p-value to `alpha`; state reject / fail to reject.</label></li>
                <li><label><input type="checkbox"> Write a conclusion <i>in context</i>, using evidence language (avoid "proven" or "accept null").</label></li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </details>
</div>';

/* ---------- 4. Instructor Rubric ---------- */
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
            <td style="text-align:center;"><b>Hypotheses<br>(2 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>State `H_0` and `H_a`.
                    <span class="ideal-ans">Target: "`H_0`: '.$h0_text.' vs `H_a`: '.$ha_text.' ('.$direction_word.')."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Conditions<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Verify all three.
                    <span class="ideal-ans">Target: "Random sample (stated); population at least `10 cdot '.$n.'`; large-counts pass because `n cdot p_0 = '.$np0.' >= 10` and `n cdot (1-p_0) = '.$nq0.' >= 10`."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Test stat + p-value<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Compute statistics.
                    <span class="ideal-ans">Target: "`\hat{p} = '.$x.'/'.$n.' = '.$phat.'`, `SE = '.$se.'`, `z = '.$z.'`."</span></li>
                <li>Compute the p-value matching the direction.
                    <span class="ideal-ans">Target: "p-value `~~ '.$p_value.'` ('.$direction_word.')."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Decision + Conclusion<br>(2 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Decision at `alpha = '.$alpha_v.'`.
                    <span class="ideal-ans">Target: "Since p-value `~~ '.$p_value.'` '.(($p_value <= $alpha_v) ? "is less than or equal to" : "is greater than").' `alpha = '.$alpha_v.'`, we '.$decision.'."</span></li>
                <li>Conclusion in context.
                    <span class="ideal-ans">Target: "'.$conclusion.'."</span></li>
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
  <p>'.$topic.' Use a significance level of `alpha = '.$alpha_v.'`.</p>
  <p><b>Essay Prompt:</b><br>
  Carry out a full hypothesis test for the population proportion. In your response, be sure to:</p>
  <ul>
    <li>State the null and alternative hypotheses in symbols, matching the claim&apos;s direction.</li>
    <li>Verify the three conditions for a one-proportion z-test (random, independent, large-counts).</li>
    <li>Compute `\hat{p}`, the standard error, the test statistic `z`, and the p-value.</li>
    <li>Compare the p-value to `alpha` and state your decision.</li>
    <li>Write a conclusion <b>in context</b> about the original claim, using evidence language.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
