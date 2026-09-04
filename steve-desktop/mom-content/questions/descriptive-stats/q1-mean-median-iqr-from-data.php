// === NAME - DESCRIPTION: Mean, Median, IQR, and Range from a Small Data Set - Compute four summary statistics from a randomized list of 9 values ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc", "numfunc", "numfunc", "numfunc")

// 9-value scenarios (n=9 keeps median = middle value, Q1 = avg of 2nd+3rd, Q3 = avg of 7th+8th: easy parity).
// Format: [data list (sorted), mean (rounded), median, Q1, Q3, IQR, range]

$cases = array(
  array("3, 5, 8, 12, 15, 17, 20, 22, 25", 14.11, 15, 6.5, 21, 14.5, 22),
  array("2, 4, 4, 6, 8, 10, 11, 13, 18", 8.44, 8, 4, 12, 8, 16),
  array("10, 14, 18, 22, 25, 28, 30, 34, 40", 24.56, 25, 16, 32, 16, 30),
  array("1, 3, 6, 9, 12, 15, 18, 22, 28", 12.67, 12, 4.5, 20, 15.5, 27),
  array("5, 8, 11, 15, 19, 23, 27, 32, 36", 19.56, 19, 9.5, 29.5, 20, 31),
  array("40, 45, 52, 58, 65, 70, 76, 82, 90", 64.22, 65, 48.5, 79, 30.5, 50)
)

$i = rand(0, count($cases)-1)
$dataStr = $cases[$i][0]
$answer[0] = $cases[$i][1]   // mean
$answer[1] = $cases[$i][2]   // median
$answer[2] = $cases[$i][5]   // IQR
$answer[3] = $cases[$i][6]   // range
$q1 = $cases[$i][3]
$q3 = $cases[$i][4]
$mean = $cases[$i][1]
$median = $cases[$i][2]
$iqr = $cases[$i][5]
$range = $cases[$i][6]

$reltolerance[0] = 0.02
$abstolerance[0] = 0.05
$abstolerance[1] = 0.5
$abstolerance[2] = 0.5
$abstolerance[3] = 0.5

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
      <p>Sorted data: ' . $dataStr . ' (`n = 9`).</p>
      <p><b>Mean:</b> sum / 9 &approx; ' . $mean . '.</p>
      <p><b>Median:</b> middle (5th) value = ' . $median . '.</p>
      <p><b>Q_1, Q_3:</b> medians of the lower and upper halves of 4 values each: `Q_1 = ' . $q1 . ', Q_3 = ' . $q3 . '`.</p>
      <p><b>IQR:</b> `Q_3 - Q_1 = ' . $q3 . ' - ' . $q1 . ' = ' . $iqr . '`.</p>
      <p><b>Range:</b> max - min = ' . $range . '.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Here is a sorted data set with `n = 9`: <b>$dataStr</b>.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the <b>mean</b>. (Round to two decimal places.) $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find the <b>median</b>. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find the <b>`"IQR"`</b>. $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Find the <b>range</b>. $answerbox[3]
  </div>
</div>


// === ANSWER ===

$solutionguide
