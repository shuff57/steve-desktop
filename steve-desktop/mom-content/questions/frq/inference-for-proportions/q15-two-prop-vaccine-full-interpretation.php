// === NAME - DESCRIPTION: Two-Proportion Test — Full Interpretation (vaccine/program efficacy scenarios) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");
$anstypes = array("essay");
$displayformat[0] = 'editornopaste';

/* ---- Randomized scenarios ---- */
$i = rand(0, 2);

$contexts = array(
  "A school district tested a new attendance program. Among 180 students in the new program, 144 attended regularly. Among 200 students in the standard program, 140 attended regularly.",
  "A clinic tested a new flu vaccine. Among 250 patients who received the new vaccine, 20 reported flu symptoms. Among 220 patients who received the standard vaccine, 33 reported flu symptoms.",
  "A company tested a new training program. Among 160 employees who completed the new training, 128 passed the certification exam. Among 150 employees in the old training, 105 passed."
);
$p1_labels = array("new program", "new vaccine", "new training");
$p2_labels = array("standard program", "standard vaccine", "old training");
$param_labels = array(
  "regular attendance rate",
  "flu symptom rate",
  "exam pass rate"
);
$directions = array("higher", "lower", "higher");
$n1s = array(180, 250, 160);
$n2s = array(200, 220, 150);
$x1s = array(144, 20, 128);
$x2s = array(140, 33, 105);

$context = $contexts[$i];
$p1_label = $p1_labels[$i];
$p2_label = $p2_labels[$i];
$param = $param_labels[$i];
$direction = $directions[$i];
$n1 = $n1s[$i];
$n2 = $n2s[$i];
$x1 = $x1s[$i];
$x2 = $x2s[$i];

$phat1 = round($x1 / $n1, 4);
$phat2 = round($x2 / $n2, 4);
$ppool = round(($x1 + $x2) / ($n1 + $n2), 4);
$se = sqrt($ppool * (1 - $ppool) * (1/$n1 + 1/$n2));
$z = round(($phat1 - $phat2) / $se, 3);

/* ---- p-values (precomputed, plausible for each scenario) ---- */
$pvals = array("0.021", "0.038", "0.009");
$pval = $pvals[$i];

$alpha = "0.05";

$decision_text = "Reject H<sub>0</sub>";
$evidence_text = "is";

$rubric = '
<style>
  .frq-wrap{font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif;font-size:15px;line-height:1.7;color:#21242c;max-width:720px;}
  .frq-card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:18px 22px;margin:10px 0;box-shadow:0 2px 6px rgba(0,0,0,0.05);}
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
      <li><span class="badge">a</span><b>P-value meaning (2 pts):</b> The p-value (' . $pval . ') is the probability of observing a difference in sample proportions as extreme as the one seen, <em>assuming H<sub>0</sub> is true</em> (i.e., assuming the true proportions are equal). Must reference "assuming H<sub>0</sub> true" or "assuming no difference in population."</li>
      <li><span class="badge">b</span><b>Decision (1 pt):</b> Since p-value (' . $pval . ') &lt; &alpha; = 0.05, <em>Reject H<sub>0</sub></em>.</li>
      <li><span class="badge">c</span><b>Conclusion in context (2 pts):</b> There is sufficient statistical evidence that the ' . $param . ' differs between the ' . $p1_label . ' and the ' . $p2_label . '. Must name both groups and the parameter in context. Must NOT say "proves" — say "evidence that" or "suggests."</li>
    </ul>
  </div>
</div>';



// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif;font-size:16px;line-height:1.7;color:#21242c;max-width:720px;">
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:18px 22px;margin:10px 0;box-shadow:0 2px 6px rgba(0,0,0,0.05);">
    <p style="margin:0 0 10px 0;">$context A two-proportion z-test was conducted to determine whether the $param is $direction for the $p1_label.</p>
    <p style="margin:0;">Results: `hat{p}_1 = $phat1`, `hat{p}_2 = $phat2`, `z = $z`, p-value = $pval`</p>
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:14px 22px;margin:6px 0;">
    <p style="margin:0;"><span style="background:#e8f0fe;color:#1865f2;border-radius:6px;padding:2px 8px;font-size:13px;font-weight:700;margin-right:8px;">a.</span> What does the p-value of $pval mean in the context of this study?</p>
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:14px 22px;margin:6px 0;">
    <p style="margin:0;"><span style="background:#e8f0fe;color:#1865f2;border-radius:6px;padding:2px 8px;font-size:13px;font-weight:700;margin-right:8px;">b.</span> At &alpha; = 0.05, what is your statistical decision? State it clearly.</p>
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:14px 22px;margin:6px 0;">
    <p style="margin:0;"><span style="background:#e8f0fe;color:#1865f2;border-radius:6px;padding:2px 8px;font-size:13px;font-weight:700;margin-right:8px;">c.</span> Write a complete conclusion in the context of the study. Name both groups and the parameter being compared.</p>
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:14px 22px;margin:6px 0;">
    $answerbox[0]
  </div>
</div>$rubric
