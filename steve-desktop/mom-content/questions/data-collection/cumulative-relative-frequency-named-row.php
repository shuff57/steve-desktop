// === NAME - DESCRIPTION: Cumulative Relative Frequency at a Named Row - read one cumulative cell off the completed ungrouped table instead of adding the column by hand ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc")

$k0 = rand(1, 3)
$k1 = rand(1, 3)
$k2 = rand(1, 3)
$k3 = rand(1, 3)
$k4 = rand(1, 3)
$k5 = rand(1, 3)
$k6 = rand(1, 8) where ($k0 + $k1 + $k2 + $k3 + $k4 + $k5 + $k6 >= 14 && $k0 + $k1 + $k2 + $k3 + $k4 + $k5 + $k6 <= 19)
$k7 = 20 - $k0 - $k1 - $k2 - $k3 - $k4 - $k5 - $k6

$f = array(3 * $k0, 3 * $k1, 3 * $k2, 3 * $k3, 3 * $k4, 3 * $k5, 3 * $k6, 3 * $k7)
$r = array($k0 / 20, $k1 / 20, $k2 / 20, $k3 / 20, $k4 / 20, $k5 / 20, $k6 / 20, $k7 / 20)
$lbl = array("0", "1", "2", "3", "4", "5", "6", "7+")

$t = rand(1, 7)
$cum = 0
for ($i = 0..$t) { $cum = $cum + $r[$i] }
$cumT = $cum
$tL = $lbl[$t]

$answer[0] = $cumT
$abstolerance[0] = 0.00011

$tableHtml = '<table style="border-collapse:collapse; margin:10px 0; font-size:15px;">'
$tableHtml = $tableHtml . '<tr style="background:#e8f0fe;"><th style="border:1px solid #c8d4ea; padding:6px 14px;">Movies seen</th><th style="border:1px solid #c8d4ea; padding:6px 14px;">Frequency</th><th style="border:1px solid #c8d4ea; padding:6px 14px;">Relative frequency</th><th style="border:1px solid #c8d4ea; padding:6px 14px;">Cumulative relative frequency</th></tr>'
$crun = 0
for ($i = 0..7) {
  $crun = $crun + $r[$i]
  $ccell = $crun
  if ($i == $t) { $ccell = '<b style="color:#1865f2;">?</b>' }
  $tableHtml = $tableHtml . '<tr><td style="border:1px solid #d9dee8; padding:6px 14px; text-align:center;">' . $lbl[$i] . '</td><td style="border:1px solid #d9dee8; padding:6px 14px; text-align:center;">' . $f[$i] . '</td><td style="border:1px solid #d9dee8; padding:6px 14px; text-align:center;">' . $r[$i] . '</td><td style="border:1px solid #d9dee8; padding:6px 14px; text-align:center;">' . $ccell . '</td></tr>'
}
$tableHtml = $tableHtml . '<tr style="background:#f6f8fc;"><td style="border:1px solid #d9dee8; padding:6px 14px;"><b>Total</b></td><td style="border:1px solid #d9dee8; padding:6px 14px; text-align:center;"><b>60</b></td><td style="border:1px solid #d9dee8; padding:6px 14px; text-align:center;"><b>1</b></td><td style="border:1px solid #d9dee8; padding:6px 14px; text-align:center;"><b>1</b></td></tr></table>'

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
      <p><span class="term-label">Step 1 &mdash; translate the question.</span> &quot;At most ' . $tL . '&quot; means every row from 0 up to and including the ' . $tL . ' row. The cumulative column was built exactly for this: it adds the relative frequencies from the top row down.</p>
      <p><span class="term-label">Step 2 &mdash; read the column, do not add.</span> The cumulative entry on the ' . $tL . ' row already holds the running total, so the answer is <b>' . $cumT . '</b>.</p>
      <p><b>Check.</b> The bottom cumulative entry must be exactly 1. If it is not, a value was miscounted or dropped somewhere &mdash; go back and find it.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A class collected 60 movie counts. The completed table below shows the frequency, relative frequency, and cumulative relative frequency of the counts, with one cumulative cell missing.</p>
    $tableHtml
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0;">Find the <b>cumulative relative frequency</b> of the $tL movies row. Round to <b>four decimal places</b>. $answerbox[0]</p>
  </div>
</div>


// === ANSWER ===

$solutionguide