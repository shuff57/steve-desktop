// === NAME - DESCRIPTION: Boxplot - Five-Number Summary, IQR, and Outlier Check - Read Min, Q1, Median, Q3, Max from a boxplot description; compute IQR and the 1.5*IQR outlier fences ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc", "numfunc", "numfunc", "choices")

// Scenarios give the 5-number summary directly; student computes IQR, lower fence, upper fence, and decides if an extra value is an outlier.
$cases = array(
  array(12, 28, 35, 44, 60, 65, 1),   // min 12, Q1 28, median 35, Q3 44, max 60. Extra = 65. IQR=16. UF = 44+1.5*16 = 68. So 65 NOT outlier
  array(5,  18, 24, 30, 42, 60, 0),   // IQR=12. UF = 30+18 = 48. 60 IS outlier
  array(20, 35, 42, 50, 70, 8,  0),   // IQR=15. LF = 35-22.5 = 12.5. 8 IS outlier (below LF)
  array(50, 62, 68, 75, 90, 95, 1),   // IQR=13. UF = 75+19.5 = 94.5. 95 IS outlier? 95 > 94.5 so YES outlier
  array(100, 120, 135, 150, 180, 200, 0) // IQR=30. UF=150+45=195. 200 > 195 → IS outlier
)
// Format: [min, Q1, median, Q3, max, extra_value, expected_outlier_choice] where 0=outlier, 1=not outlier

$pick = $cases[rand(0, count($cases)-1)]
$min = $pick[0]
$q1  = $pick[1]
$med = $pick[2]
$q3  = $pick[3]
$max = $pick[4]
$extra = $pick[5]
$expected = $pick[6]

$iqr = $q3 - $q1
$lf  = $q1 - 1.5 * $iqr
$uf  = $q3 + 1.5 * $iqr

// Correct outlier decision from computed fences (re-derive in case scenario data has rounding edge)
$isOutlier = 1
if ($extra < $lf || $extra > $uf) { $isOutlier = 0 }
$answer[0] = $iqr
$answer[1] = $lf
$answer[2] = $uf
$answer[3] = $isOutlier

$reltolerance[0] = 0.02
$abstolerance[0] = 0.5
$abstolerance[1] = 0.5
$abstolerance[2] = 0.5

$choices[3] = array(
  'Yes &mdash; the value is an outlier by the `1.5*"IQR"` rule',
  'No &mdash; the value is not an outlier by the `1.5*"IQR"` rule'
)
$noshuffle[3] = "all"

$whichFence = "above the upper fence " . $uf
if ($extra < $lf) { $whichFence = "below the lower fence " . $lf }
$outlierWhy = "Because " . $extra . " falls between the lower fence " . $lf . " and the upper fence " . $uf . ", it is NOT flagged as an outlier."
if ($isOutlier == 0) { $outlierWhy = "Because " . $extra . " is " . $whichFence . ", it is flagged as an outlier." }

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><b>Part a:</b> `"IQR" = Q_3 - Q_1 = ' . $q3 . ' - ' . $q1 . ' = ' . $iqr . '`.</p>
      <p><b>Part b:</b> Lower fence `= Q_1 - 1.5 cdot "IQR" = ' . $q1 . ' - ' . (1.5 * $iqr) . ' = ' . $lf . '`.</p>
      <p><b>Part c:</b> Upper fence `= Q_3 + 1.5 cdot "IQR" = ' . $q3 . ' + ' . (1.5 * $iqr) . ' = ' . $uf . '`.</p>
      <p><b>Part d:</b> ' . $outlierWhy . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 6px 0;">A boxplot summarizes a data set with the following five-number summary:</p>
    <ul style="margin:0 0 0 1.2em;">
      <li>Minimum: <b>$min</b></li>
      <li>`Q_1`: <b>$q1</b></li>
      <li>Median: <b>$med</b></li>
      <li>`Q_3`: <b>$q3</b></li>
      <li>Maximum: <b>$max</b></li>
    </ul>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Compute the <b>`"IQR"`</b>. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Compute the <b>lower fence</b> `Q_1 - 1.5 cdot "IQR"`. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Compute the <b>upper fence</b> `Q_3 + 1.5 cdot "IQR"`. $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> A new observation of <b>$extra</b> is added. Is it an outlier by the `1.5*"IQR"` rule? $answerbox[3]
  </div>
</div>


// === ANSWER ===

$solutionguide
