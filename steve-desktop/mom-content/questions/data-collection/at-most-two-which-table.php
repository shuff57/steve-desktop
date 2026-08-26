// === NAME - DESCRIPTION: At Most Two Which Table - the proportion at most 2, then which table shows it and why ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc", "choices")

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
$g = array($f[0] + $f[1], $f[2] + $f[3], $f[4] + $f[5], $f[6] + $f[7])
$gr = array($g[0] / 60, $g[1] / 60, $g[2] / 60, $g[3] / 60)
$gl = array("0-1", "2-3", "4-5", "6-7+")

$c2 = ($k0 + $k1 + $k2) / 20
$c3 = ($k0 + $k1 + $k2 + $k3) / 20
$val = $c2
$vald = round($val, 4)
$c2d = round($c2, 4)
$c3d = round($c3, 4)

$answer[0] = $val
$abstolerance[0] = 0.00011

$questions[1] = array(
  "The ungrouped table. The cumulative entry on the 2 movies row is $c2d, which is exactly the share of the data at most 2.",
  "The grouped table. The 2-3 row holds every count of 2 and 3 together, and its cumulative entry gives the answer.",
  "Either table. Both tables show the same 60 counts, so both give the same answer here.",
  "Neither table. Only the raw 60 values can answer a question phrased this way."
)
$answer[1] = 0
$noshuffle[1] = "all"

$uHtml = '<table style="border-collapse:collapse; margin:10px 0; font-size:15px;">'
$uHtml = $uHtml . '<tr style="background:#e8f0fe;"><th style="border:1px solid #c8d4ea; padding:6px 12px;">Movies</th><th style="border:1px solid #c8d4ea; padding:6px 12px;">Freq</th><th style="border:1px solid #c8d4ea; padding:6px 12px;">Rel freq</th><th style="border:1px solid #c8d4ea; padding:6px 12px;">Cum rel</th></tr>'
$crun = 0
for ($i = 0..7) {
  $crun = $crun + $r[$i]
  $uHtml = $uHtml . '<tr><td style="border:1px solid #d9dee8; padding:6px 12px; text-align:center;">' . $lbl[$i] . '</td><td style="border:1px solid #d9dee8; padding:6px 12px; text-align:center;">' . $f[$i] . '</td><td style="border:1px solid #d9dee8; padding:6px 12px; text-align:center;">' . $r[$i] . '</td><td style="border:1px solid #d9dee8; padding:6px 12px; text-align:center;">' . $crun . '</td></tr>'
}
$uHtml = $uHtml . '</table>'

$gHtml = '<table style="border-collapse:collapse; margin:10px 0; font-size:15px;">'
$gHtml = $gHtml . '<tr style="background:#e8f0fe;"><th style="border:1px solid #c8d4ea; padding:6px 12px;">Movies</th><th style="border:1px solid #c8d4ea; padding:6px 12px;">Freq</th><th style="border:1px solid #c8d4ea; padding:6px 12px;">Rel freq</th><th style="border:1px solid #c8d4ea; padding:6px 12px;">Cum rel</th></tr>'
$gcr = 0
for ($i = 0..3) {
  $gcr = $gcr + $gr[$i]
  $gHtml = $gHtml . '<tr><td style="border:1px solid #d9dee8; padding:6px 12px; text-align:center;">' . $gl[$i] . '</td><td style="border:1px solid #d9dee8; padding:6px 12px; text-align:center;">' . $g[$i] . '</td><td style="border:1px solid #d9dee8; padding:6px 12px; text-align:center;">' . $gr[$i] . '</td><td style="border:1px solid #d9dee8; padding:6px 12px; text-align:center;">' . $gcr . '</td></tr>'
}
$gHtml = $gHtml . '</table>'

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
      <p><span class="term-label">Step 1 &mdash; translate the question.</span> &quot;At most two&quot; means 0, 1, or 2 movies &mdash; the 0, 1, and 2 rows of the ungrouped table.</p>
      <p><span class="term-label">Step 2 &mdash; read the cumulative column.</span> The cumulative entry on the 2 row is <b>' . $vald . '</b> &mdash; the cumulative column has already done the adding for you. That is exactly why the lab asks you to build it.</p>
      <p><span class="term-label">Step 3 &mdash; why the ungrouped table is the one.</span> In the grouped table, 2 and 3 are locked together in one 2-3 row, so the count of 2 cannot be separated from the count of 3. Only the ungrouped table can answer the question.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A class collected 60 movie counts and built both tables below from them.</p>
    <p style="margin:0.5em 0 0 0;"><b>Ungrouped table.</b></p>
    $uHtml
    <p style="margin:0;"><b>Grouped table.</b></p>
    $gHtml
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">a.</span> Using the tables, find the <b>percent of the data that is at most two</b>. Enter your answer as a <b>decimal</b>, e.g. 0.55. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">b.</span> <b>Which table did you use and why?</b> $answerbox[1]
  </div>
</div>


// === ANSWER ===

$solutionguide