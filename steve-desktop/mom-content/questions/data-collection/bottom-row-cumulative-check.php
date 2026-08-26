// === NAME - DESCRIPTION: Bottom-Row Cumulative Check - a miscounted table whose bottom cumulative is not 1; find the drop, name the row, fix the bottom ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "choices", "numfunc")

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

$m = rand(0, 7)
$d = rand(1, 2)
$short = $f[$m] - $d
$badBottom = 1 - $d / 60
$badBottomd = round($badBottom, 4)
$mL = $lbl[$m]
$fM = $f[$m]

$answer[0] = $d
$answerformat[0] = "integer"

$questions[1] = array(
  "The $mL movies row, its count is shown as $short, which is $d short of the correct $fM.",
  "The 0 movies row, because the miscount happened at the very top of the table.",
  "The 7+ movies row, because that row always holds whatever is left over.",
  "No single row is wrong; the total of 60 is what was misstated."
)
$answer[1] = 0
$noshuffle[1] = "all"

$answer[2] = 1
$abstolerance[2] = 0.00011

$tableHtml = '<table style="border-collapse:collapse; margin:10px 0; font-size:15px;">'
$tableHtml = $tableHtml . '<tr style="background:#e8f0fe;"><th style="border:1px solid #c8d4ea; padding:6px 14px;">Movies seen</th><th style="border:1px solid #c8d4ea; padding:6px 14px;">Frequency</th><th style="border:1px solid #c8d4ea; padding:6px 14px;">Relative frequency</th><th style="border:1px solid #c8d4ea; padding:6px 14px;">Cumulative relative frequency</th></tr>'
$crun = 0
for ($i = 0..7) {
  $fcell = $f[$i]
  if ($i == $m) { $fcell = $short }
  $crun = $crun + $r[$i]
  $ccell = $crun
  if ($i == 7) { $ccell = $badBottomd }
  $tableHtml = $tableHtml . '<tr><td style="border:1px solid #d9dee8; padding:6px 14px; text-align:center;">' . $lbl[$i] . '</td><td style="border:1px solid #d9dee8; padding:6px 14px; text-align:center;">' . $fcell . '</td><td style="border:1px solid #d9dee8; padding:6px 14px; text-align:center;">' . $r[$i] . '</td><td style="border:1px solid #d9dee8; padding:6px 14px; text-align:center;">' . $ccell . '</td></tr>'
}
$tableHtml = $tableHtml . '</table>'

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
      <p><span class="term-label">Step 1 &mdash; the not-1 rule.</span> The lab&rsquo;s own check: the cumulative column adds up from the top row down, so the bottom row should come out to exactly 1. This bottom reads ' . $badBottomd . ', so a value has been miscounted or dropped somewhere.</p>
      <p><span class="term-label">Step 2 &mdash; find the gap.</span> The bottom is 1 &minus; ' . $badBottomd . ' = ' . $d . '/60 short of 1, and the only row whose count does not fit the rest is the ' . $mL . ' row. Its count is listed as ' . $short . ', where the table and the total imply ' . $f[$m] . '.</p>
      <p><span class="term-label">Step 3 &mdash; fix the bottom.</span> Once the true count is restored, the frequencies add to 60 and the cumulative column finishes at exactly <b>1</b>.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A student built the table below from 60 movie counts, but the bottom cumulative entry did not come out to 1.</p>
    $tableHtml
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">a.</span> How many of the 60 values are missing from the student&rsquo;s table? Enter a whole number. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">b.</span> Which row holds the miscount? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">c.</span> What is the <b>correct</b> bottom cumulative entry? Round to <b>four decimal places</b>. $answerbox[2]
  </div>
</div>


// === ANSWER ===

$solutionguide