// === NAME - DESCRIPTION: Hypothesis Test - Sleep Hours - Students identify the correct test, build hypotheses from dropdowns, compute sample means, the test statistic, find the p-value, and state a conclusion. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("choices", "choices", "number", "choices", "choices", "number", "number", "number", "number", "number", "choices");

/* ---------- 1. Scenario ---------- */
$topic = "A researcher wants to know whether college students with part-time jobs get different amounts of sleep than students without jobs";
$group1_label = "students with part-time jobs";
$group2_label = "students without jobs";
$measurement = "hours of sleep per night";
$unit = "hours";

$n1 = rand(8, 10);
$n2 = rand(8, 10);

$data1 = rrands(5.0, 7.5, 0.1, $n1);
$data2 = rrands(6.5, 9.0, 0.1, $n2);

// Display data as comma-separated strings
$data1_display = joinarray($data1, ", ");
$data2_display = joinarray($data2, ", ");

// Compute sample statistics
$xbar1 = mean($data1);
$xbar2 = mean($data2);

// stdev() returns sample SD (divides by n-1)
$df1 = $n1 - 1;
$df2 = $n2 - 1;
$s1 = stdev($data1);
$s2 = stdev($data2);

// Welch's two-sample t-statistic (unequal variances)
$se = sqrt($s1*$s1/$n1 + $s2*$s2/$n2);
$t_stat = ($xbar1 - $xbar2) / $se;

// Conservative degrees of freedom
$df = min($df1, $df2);

// Two-tailed p-value: tcdf(X, df) returns P(T < X)
$t_abs = abs($t_stat);
$p_value = 2 * tcdf(-$t_abs, $df);

// Alpha level
$alpha = randfrom("0.01,0.05,0.10");

// --- Parts 0-2: H0 hypothesis ---
$choices[0] = array("&#956;&#8321; &#8722; &#956;&#8322;", "&#956;<sub>d</sub>", "&#956;", "p&#8321; &#8722; p&#8322;");
$answer[0] = 0;
$noshuffle[0] = "all";
$displayformat[0] = "select";

$choices[1] = array("=", "&#8800;", "&lt;", "&gt;");
$answer[1] = 0;
$noshuffle[1] = "all";
$displayformat[1] = "select";

$answer[2] = 0;
$abstolerance[2] = 0.001;
$answerboxsize[2] = 5;

// --- Parts 3-5: Ha hypothesis ---
$choices[3] = array("&#956;&#8321; &#8722; &#956;&#8322;", "&#956;<sub>d</sub>", "&#956;", "p&#8321; &#8722; p&#8322;");
$answer[3] = 0;
$noshuffle[3] = "all";
$displayformat[3] = "select";

$choices[4] = array("&#8800;", "=", "&lt;", "&gt;");
$answer[4] = 0;
$noshuffle[4] = "all";
$displayformat[4] = "select";

$answer[5] = 0;
$abstolerance[5] = 0.001;
$answerboxsize[5] = 5;

// --- Parts 6-7: Sample means ---
$answer[6] = $xbar1;
$answer[7] = $xbar2;
$abstolerance[6] = 0.015;
$abstolerance[7] = 0.015;
$reqdecimals[6] = 2;
$reqdecimals[7] = 2;
$ansprompt[6] = "x&#772;&#8321; = ";
$ansprompt[7] = "x&#772;&#8322; = ";

// --- Part 8: Test statistic ---
$answer[8] = $t_stat;
$abstolerance[8] = 0.05;
$reqdecimals[8] = 3;
$ansprompt[8] = "t = ";

// --- Part 9: P-value ---
$answer[9] = $p_value;
$abstolerance[9] = 0.005;
$reqdecimals[9] = 4;
$ansprompt[9] = "p-value = ";

// --- Part 10: Conclusion ---
$choices[10] = array("Reject H&#8320;", "Fail to reject H&#8320;");
$noshuffle[10] = "all";
if ($p_value < $alpha) {
  $answer[10] = 0;
  $decision_text = "reject H&#8320;";
  $conclusion = "there is sufficient evidence at the &#945; = $alpha level to conclude that the population mean $measurement differs between $group1_label and $group2_label";
} else {
  $answer[10] = 1;
  $decision_text = "fail to reject H&#8320;";
  $conclusion = "there is not sufficient evidence at the &#945; = $alpha level to conclude that the population mean $measurement differs between $group1_label and $group2_label";
}

// Round display values
$xbar1_show = round($xbar1, 2);
$xbar2_show = round($xbar2, 2);
$s1_show = round($s1, 2);
$s2_show = round($s2, 2);
$t_show = round($t_stat, 3);
$df_show = $df;
$p_show = round($p_value, 4);

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

/* ---------- 3. Solution Guide ---------- */
$solutionguide = $css_block . '
<div class="rubric-container">
  <details>
    <summary>
      <span class="arrow-closed">&#9656;</span><span class="arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr>
            <th class="col-header">Step</th>
            <th class="col-check">Work</th>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Hypotheses</b></td>
            <td>This is a <b>two-sample t-test for independent means</b>. The parameter is <b>&#956;&#8321; &#8722; &#956;&#8322;</b> (not &#956;<sub>d</sub>, which is for paired data).<br><br>
            H&#8320;: &#956;&#8321; &#8722; &#956;&#8322; = 0 &nbsp;&nbsp; H&#8337;: &#956;&#8321; &#8722; &#956;&#8322; &#8800; 0<br>
            &#945; = '.$alpha.'</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Step 1:<br>Sample Means</b></td>
            <td>
              x&#772;&#8321; = mean of Group 1 data = <b>'.$xbar1_show.'</b> '.$unit.'<br>
              x&#772;&#8322; = mean of Group 2 data = <b>'.$xbar2_show.'</b> '.$unit.'
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Step 2:<br>Sample SDs</b></td>
            <td>
              s&#8321; = '.$s1_show.' &nbsp;&nbsp; s&#8322; = '.$s2_show.'<br>
              (Use 1-Var Stats or the sample standard deviation formula with n &#8722; 1 in the denominator.)
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Step 3:<br>Test Statistic</b></td>
            <td>
              <div style="text-align:center; margin:8px 0;">
                t = (x&#772;&#8321; &#8722; x&#772;&#8322;) / &#8730;(s&#8321;&sup2;/n&#8321; + s&#8322;&sup2;/n&#8322;)
              </div>
              t = ('.$xbar1_show.' &#8722; '.$xbar2_show.') / &#8730;('.$s1_show.'&sup2;/'.$n1.' + '.$s2_show.'&sup2;/'.$n2.') = <b>'.$t_show.'</b>
              <div style="margin-top:6px;">df = min('.$n1.' &#8722; 1, '.$n2.' &#8722; 1) = '.$df_show.' (conservative method)</div>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Step 4:<br>P-Value</b></td>
            <td>
              Two-tailed p-value = <b>'.$p_show.'</b><br>
              (Use 2-SampTTest on your calculator or tcdf with df = '.$df_show.'.)
            </td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Step 5:<br>Conclusion</b></td>
            <td class="col-check-bot">
              Since p = '.$p_show.' and &#945; = '.$alpha.', we <b>'.$decision_text.'</b>.<br><br>
              <div style="padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Conclusion:</b> We '.$conclusion.'.
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </details>
</div>';

/* ---------- 4. Question Text ---------- */
$questiontext = '
<div style="font-family:Arial; font-size:medium; line-height:1.6;">
  <p>'.$topic.'. Data from two groups are shown below:</p>

  <table style="border-collapse:collapse; border:1px solid #ccc; margin:10px 0; font-family:Arial; font-size:small;">
    <tr style="background:#f2f2f2;">
      <th style="border:1px solid #ccc; padding:8px; text-align:left;">Group</th>
      <th style="border:1px solid #ccc; padding:8px; text-align:left;">Data ('.$unit.')</th>
      <th style="border:1px solid #ccc; padding:8px; text-align:center;">n</th>
    </tr>
    <tr>
      <td style="border:1px solid #ccc; padding:8px;"><b>Group 1</b> &mdash; '.$group1_label.'</td>
      <td style="border:1px solid #ccc; padding:8px; user-select:text;">'.$data1_display.'</td>
      <td style="border:1px solid #ccc; padding:8px; text-align:center;">'.$n1.'</td>
    </tr>
    <tr class="row-colored">
      <td style="border:1px solid #ccc; padding:8px;"><b>Group 2</b> &mdash; '.$group2_label.'</td>
      <td style="border:1px solid #ccc; padding:8px; user-select:text;">'.$data2_display.'</td>
      <td style="border:1px solid #ccc; padding:8px; text-align:center;">'.$n2.'</td>
    </tr>
  </table>

  <p>Using the data above, set up and carry out an appropriate hypothesis test at the <b>&#945; = '.$alpha.'</b> significance level.</p>
  <p>Use the <b>conservative degrees of freedom</b> (the smaller of n&#8321;&minus;1 and n&#8322;&minus;1) when computing the p-value.</p>
</div>';

// === QUESTION TEXT ===

$questiontext

<p><b>(a)</b> State the hypotheses:</p>
<div style="margin-left:20px; font-family:Arial; font-size:medium; line-height:2.4;">
  H&#8320;: $answerbox[0] $answerbox[1] $answerbox[2]<br>
  H&#8337;: $answerbox[3] $answerbox[4] $answerbox[5]
</div>

<p><b>(b)</b> Compute the sample means:</p>
$answerbox[6] &nbsp;&nbsp; $answerbox[7]

<p><b>(c)</b> Compute the test statistic:</p>
$answerbox[8]

<p><b>(d)</b> Find the p-value (round to 4 decimal places):</p>
$answerbox[9]

<p><b>(e)</b> At &#945; = $alpha, what is your conclusion?</p>
$answerbox[10]

///

$solutionguide
