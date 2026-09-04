// === NAME - DESCRIPTION: Regression Output: Interpret slope, r-squared, and slope test p-value (new scenarios) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");
$anstypes = array("essay");
$displayformat[0] = 'editornopaste';

/* ---- Randomized scenarios ---- */
$i = rand(0, 2);

$xnames  = array("altitude (feet above sea level)", "hours of sunlight per day", "age of vehicle (years)");
$ynames  = array("average temperature (°F)", "daily vitamin D level (ng/mL)", "fuel efficiency (mpg)");
$xshorts = array("altitude", "hours of sunlight", "vehicle age");
$yshorts = array("temperature", "vitamin D level", "fuel efficiency");
$units_x = array("feet", "hours", "years");
$units_y = array("°F", "ng/mL", "mpg");

$slopes   = array("-0.0035", "1.82", "-2.14");
$intercepts = array("72.4", "18.6", "38.7");
$r2vals   = array("0.81", "0.67", "0.74");
$pvals    = array("< 0.001", "0.003", "< 0.001");
$r2pcts   = array("81", "67", "74");

$xname    = $xnames[$i];
$yname    = $ynames[$i];
$xshort   = $xshorts[$i];
$yshort   = $yshorts[$i];
$unit_x   = $units_x[$i];
$unit_y   = $units_y[$i];
$slope    = $slopes[$i];
$intercept = $intercepts[$i];
$r2       = $r2vals[$i];
$pval     = $pvals[$i];
$r2pct    = $r2pcts[$i];

/* Slope direction for interpretation */
$slope_directions = array("decreases by 0.0035°F", "increases by 1.82 ng/mL", "decreases by 2.14 mpg");
$slope_dir = $slope_directions[$i];

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
      <li><span class="badge">a</span><b>Slope interpretation (2 pts):</b> For each additional one ' . $unit_x . ' of ' . $xshort . ', the predicted ' . $yshort . ' ' . $slope_dir . '. Must include direction, magnitude (' . $slope . '), units for both variables, and the word "predicted" (or "on average"). Must NOT say "causes."</li>
      <li><span class="badge">b</span><b>r² interpretation (2 pts):</b> ' . $r2pct . '% of the variation in ' . $yshort . ' is explained by the linear relationship with ' . $xshort . '. Must name the response variable, the explanatory variable, and the percentage. Must NOT confuse r² with r or with the slope.</li>
      <li><span class="badge">c</span><b>Slope test p-value (2 pts):</b> The p-value (' . $pval . ') provides strong evidence against H<sub>0</sub>: &beta;<sub>1</sub> = 0. We conclude there is a statistically significant linear relationship between ' . $xshort . ' and ' . $yshort . ' in the population. Must reference &beta;<sub>1</sub> = 0, the population (not just sample), and frame it as a conclusion about evidence: not a claim of causation.</li>
    </ul>
  </div>
</div>';


// Precomputed for the question text: substitution takes scalars only, and a variable
// followed by letters would read as a different variable name.
$__qt1 = ucfirst($xshort)


// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif;font-size:16px;line-height:1.7;color:#21242c;max-width:720px;">
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:18px 22px;margin:10px 0;box-shadow:0 2px 6px rgba(0,0,0,0.05);">
    <p style="margin:0 0 12px 0;">A researcher regressed <strong>$yname</strong> on <strong>$xname</strong>. The regression output is shown below.</p>
    <table style="border-collapse:collapse;width:100%;font-size:14px;">
      <tr style="background:#f3f4f6;">
        <th style="border:1px solid #d1d5db;padding:8px 12px;text-align:left;">Term</th>
        <th style="border:1px solid #d1d5db;padding:8px 12px;">Estimate</th>
        <th style="border:1px solid #d1d5db;padding:8px 12px;">p-value (slope)</th>
        <th style="border:1px solid #d1d5db;padding:8px 12px;">r²</th>
      </tr>
      <tr>
        <td style="border:1px solid #d1d5db;padding:8px 12px;">Intercept</td>
        <td style="border:1px solid #d1d5db;padding:8px 12px;text-align:center;">$intercept</td>
        <td rowspan="2" style="border:1px solid #d1d5db;padding:8px 12px;text-align:center;">$pval</td>
        <td rowspan="2" style="border:1px solid #d1d5db;padding:8px 12px;text-align:center;">$r2</td>
      </tr>
      <tr>
        <td style="border:1px solid #d1d5db;padding:8px 12px;">$__qt1</td>
        <td style="border:1px solid #d1d5db;padding:8px 12px;text-align:center;">$slope</td>
      </tr>
    </table>
    <p style="margin:10px 0 0 0;font-size:13px;color:#6b7280;">Test: H<sub>0</sub>: &beta;<sub>1</sub> = 0 vs. H<sub>a</sub>: &beta;<sub>1</sub> &ne; 0, &alpha; = 0.05</p>
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:14px 22px;margin:6px 0;">
    <p style="margin:0;"><span style="background:#e8f0fe;color:#1865f2;border-radius:6px;padding:2px 8px;font-size:13px;font-weight:700;margin-right:8px;">a.</span> Interpret the slope in the context of this study. Include direction, units, and the word "predicted."</p>
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:14px 22px;margin:6px 0;">
    <p style="margin:0;"><span style="background:#e8f0fe;color:#1865f2;border-radius:6px;padding:2px 8px;font-size:13px;font-weight:700;margin-right:8px;">b.</span> Interpret r² = $r2 in the context of this study.</p>
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:14px 22px;margin:6px 0;">
    <p style="margin:0;"><span style="background:#e8f0fe;color:#1865f2;border-radius:6px;padding:2px 8px;font-size:13px;font-weight:700;margin-right:8px;">c.</span> The p-value for the slope test is $pval. What does this tell us about the relationship between $xshort and $yshort <em>in the population</em>?</p>
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:14px 22px;margin:6px 0;">
    $answerbox[0]
  </div>
</div>$rubric
