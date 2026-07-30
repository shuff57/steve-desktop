// === NAME - DESCRIPTION: Two-Sample Means — CI Interpretation, Decision, and Error Type (new scenarios) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");
$anstypes = array("essay");
$displayformat[0] = 'editornopaste';

/* ---- Randomized scenarios ---- */
$i = rand(0, 2);

$contexts = array(
  "Researchers studied daily screen time (hours) for middle-school students (Group 1) and high-school students (Group 2). A two-sample t-interval was computed.",
  "A nutritionist compared average daily sugar intake (grams) between adults who skipped breakfast (Group 1) and adults who ate breakfast (Group 2). A two-sample t-interval was computed.",
  "A researcher compared average nightly sleep (hours) between college athletes (Group 1) and non-athletes (Group 2). A two-sample t-interval was computed."
);
$g1_labels = array("middle-school students", "breakfast-skippers", "college athletes");
$g2_labels = array("high-school students", "breakfast-eaters", "non-athletes");
$params = array("mean daily screen time (hours)", "mean daily sugar intake (grams)", "mean nightly sleep (hours)");
$units = array("hours", "grams", "hours");

/* CI bounds — always negative lower, positive upper → no significant difference */
$ci_lows  = array("-0.42", "-8.3", "-0.31");
$ci_highs = array("1.18", "2.1", "0.89");
$xdiffs   = array("0.38", "-3.1", "0.29");

$context  = $contexts[$i];
$g1       = $g1_labels[$i];
$g2       = $g2_labels[$i];
$param    = $params[$i];
$unit     = $units[$i];
$ci_low   = $ci_lows[$i];
$ci_high  = $ci_highs[$i];
$xdiff    = $xdiffs[$i];
$alpha    = "0.05";

/* The CI contains 0 → fail to reject */
$decision_text = "Fail to reject H<sub>0</sub>";

$rubric = '
<style>
  .frq-wrap{font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif;font-size:15px;line-height:1.7;color:#21242c;max-width:720px;}
  .frq-rubric{background:#f0f4ff;border:1px solid #c7d7fc;border-radius:10px;padding:14px 18px;margin:10px 0;}
  .frq-rubric h4{margin:0 0 8px 0;color:#1865f2;font-size:14px;}
  .frq-rubric ul{margin:0;padding-left:18px;}
  .frq-rubric li{margin:4px 0;}
  .badge{display:inline-block;background:#e8f0fe;color:#1865f2;border-radius:6px;padding:2px 8px;font-size:12px;font-weight:700;margin-right:6px;}
</style>
<div class="frq-wrap">
  <div class="frq-rubric">
    <h4>Scoring Rubric (full credit requires all elements)</h4>
    <ul>
      <li><span class="badge">a</span><b>CI Interpretation (2 pts):</b> We are 95% confident that the true difference in ' . $param . ' (' . $g1 . ' minus ' . $g2 . ') is between ' . $ci_low . ' and ' . $ci_high . ' ' . $unit . '. Must name both groups, direction (Group 1 − Group 2), the parameter, units, and confidence level.</li>
      <li><span class="badge">b</span><b>Decision from CI (1 pt):</b> Since the CI (' . $ci_low . ', ' . $ci_high . ') contains 0, we <em>fail to reject H<sub>0</sub></em>. There is not sufficient evidence of a difference. Must reference "contains 0."</li>
      <li><span class="badge">c</span><b>Error type (2 pts):</b> Since we failed to reject H<sub>0</sub>, the possible error is a <em>Type II error</em> — failing to detect a real difference that actually exists in the population. Must name Type II and explain what it means in this context (not just the label).</li>
    </ul>
  </div>
</div>';



// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif;font-size:16px;line-height:1.7;color:#21242c;max-width:720px;">
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:18px 22px;margin:10px 0;box-shadow:0 2px 6px rgba(0,0,0,0.05);">
    <p style="margin:0 0 10px 0;">$context</p>
    <p style="margin:0;">The 95% two-sample t-interval for `mu_1 - mu_2` is <strong>($ci_low, $ci_high)</strong> $unit.</p>
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:14px 22px;margin:6px 0;">
    <p style="margin:0;"><span style="background:#e8f0fe;color:#1865f2;border-radius:6px;padding:2px 8px;font-size:13px;font-weight:700;margin-right:8px;">a.</span> Interpret the confidence interval in the context of the study. Include the confidence level, both groups, the parameter, and units.</p>
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:14px 22px;margin:6px 0;">
    <p style="margin:0;"><span style="background:#e8f0fe;color:#1865f2;border-radius:6px;padding:2px 8px;font-size:13px;font-weight:700;margin-right:8px;">b.</span> Based on the confidence interval, what decision can you make about H<sub>0</sub>: `mu_1 = mu_2`? Explain your reasoning.</p>
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:14px 22px;margin:6px 0;">
    <p style="margin:0;"><span style="background:#e8f0fe;color:#1865f2;border-radius:6px;padding:2px 8px;font-size:13px;font-weight:700;margin-right:8px;">c.</span> What type of error (Type I or Type II) could have been made in this decision? Explain what that error would mean in context.</p>
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:14px 22px;margin:6px 0;">
    $answerbox[0]
  </div>
</div>$rubric
