// === NAME - DESCRIPTION: Grouped Frequency Table Blanks - back-solve a blanked group frequency and two group relative frequencies from the same 60 movie counts ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "numfunc", "numfunc")

$k0 = rand(1, 3)
$k1 = rand(1, 3)
$k2 = rand(1, 3)
$k3 = rand(1, 3)
$k4 = rand(1, 3)
$k5 = rand(1, 3)
$k6 = rand(1, 8) where ($k0 + $k1 + $k2 + $k3 + $k4 + $k5 + $k6 >= 14 && $k0 + $k1 + $k2 + $k3 + $k4 + $k5 + $k6 <= 19)
$k7 = 20 - $k0 - $k1 - $k2 - $k3 - $k4 - $k5 - $k6

$f = array(3 * $k0, 3 * $k1, 3 * $k2, 3 * $k3, 3 * $k4, 3 * $k5, 3 * $k6, 3 * $k7)
$g = array($f[0] + $f[1], $f[2] + $f[3], $f[4] + $f[5], $f[6] + $f[7])
$gr = array($g[0] / 60, $g[1] / 60, $g[2] / 60, $g[3] / 60)
$gl = array("0-1", "2-3", "4-5", "6-7+")

$m = rand(0, 3)
$j = rand(0, 3) where ($j != $m)
$missing = $g[$m]
$knownSum = 60 - $missing
$relM = $gr[$m]
$relJ = $gr[$j]
$mL = $gl[$m]
$jL = $gl[$j]

$answer[0] = $missing
$answerformat[0] = "integer"
$answer[1] = $relM
$abstolerance[1] = 0.00011
$answer[2] = $relJ
$abstolerance[2] = 0.00011

$tableHtml = '<table style="border-collapse:collapse; margin:10px 0; font-size:15px;">'
$tableHtml = $tableHtml . '<tr style="background:#e8f0fe;"><th style="border:1px solid #c8d4ea; padding:6px 14px;">Movies seen</th><th style="border:1px solid #c8d4ea; padding:6px 14px;">Frequency</th><th style="border:1px solid #c8d4ea; padding:6px 14px;">Relative frequency</th></tr>'
for ($i = 0..3) {
  $fcell = $g[$i]
  $rcell = $gr[$i]
  if ($i == $m) {
    $fcell = '<b style="color:#1865f2;">?</b>'
    $rcell = '<b style="color:#1865f2;">?</b>'
  }
  if ($i == $j) { $rcell = '<b style="color:#1865f2;">?</b>' }
  $tableHtml = $tableHtml . '<tr><td style="border:1px solid #d9dee8; padding:6px 14px; text-align:center;">' . $gl[$i] . '</td><td style="border:1px solid #d9dee8; padding:6px 14px; text-align:center;">' . $fcell . '</td><td style="border:1px solid #d9dee8; padding:6px 14px; text-align:center;">' . $rcell . '</td></tr>'
}
$tableHtml = $tableHtml . '<tr style="background:#f6f8fc;"><td style="border:1px solid #d9dee8; padding:6px 14px;"><b>Total</b></td><td style="border:1px solid #d9dee8; padding:6px 14px; text-align:center;"><b>60</b></td><td style="border:1px solid #d9dee8; padding:6px 14px; text-align:center;"><b>1</b></td></tr></table>'

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
  .term-label { font-weight:700; color:#1865f2; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><span class="term-label">Step 1 &mdash; the missing frequency.</span> The Frequency column must add to the total of 60. The three known rows add to ' . $knownSum . ', so the ' . $mL . ' movies row holds 60 &minus; ' . $knownSum . ' = <b>' . $missing . '</b>.</p>
      <p><span class="term-label">Step 2 &mdash; relative frequency of the ' . $mL . ' row.</span> Divide that count by the total: ' . $missing . ' &divide; 60 = <b>' . $relM . '</b>.</p>
      <p><span class="term-label">Step 3 &mdash; relative frequency of the ' . $jL . ' row.</span> Same move: ' . $g[$j] . ' &divide; 60 = <b>' . $relJ . '</b>.</p>
      <p><b>Check.</b> The four group counts must add back to 60, and the relative frequencies to 1. The grouping does not lose or add any values &mdash; it only re-buckets the same 60 counts.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">The same class of 60 movie counts, this time grouped into intervals. One frequency and two relative frequencies are missing. The total row shows that all 60 values are accounted for.</p>
    $tableHtml
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">a.</span> Find the <b>missing frequency</b> for the $mL movies row. Enter a whole number. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">b.</span> Find the <b>relative frequency</b> of the $mL movies row. Round to <b>four decimal places</b>. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">c.</span> Find the <b>relative frequency</b> of the $jL movies row. Round to <b>four decimal places</b>. $answerbox[2]
  </div>
</div>


// === ANSWER ===

$solutionguide