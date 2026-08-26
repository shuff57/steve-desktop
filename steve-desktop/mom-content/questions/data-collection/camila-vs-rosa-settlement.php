// === NAME - DESCRIPTION: Camila vs Rosa Settlement - more than three from both tables gives the same share, and the boundary explains why ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc", "numfunc", "choices")

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
$g = array($f[0] + $f[1], $f[2] + $f[3], $f[4] + $f[5], $f[6] + $f[7])
$gr = array($g[0] / 60, $g[1] / 60, $g[2] / 60, $g[3] / 60)
$lbl = array("0", "1", "2", "3", "4", "5", "6", "7+")
$gl = array("0-1", "2-3", "4-5", "6-7+")
$lbl = array("0", "1", "2", "3", "4", "5", "6", "7+")
$gl = array("0-1", "2-3", "4-5", "6-7+")

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
$c3 = ($k0 + $k1 + $k2 + $k3) / 20
$gc1 = ($k0 + $k1 + $k2 + $k3) / 20
$val = 1 - $c3
$vald = round($val, 4)
$c3d = round($c3, 4)
$gc1d = round($gc1, 4)

$answer[0] = $val
$abstolerance[0] = 0.00011
$answer[1] = $val
$abstolerance[1] = 0.00011

$questions[2] = array(
  "Camila is right that the two tables agree, but only because the cut at 3 lands exactly on the boundary between the 2-3 and 4-5 groups. A cut that falls inside a row, like 2, cannot be answered by the grouped table at all.",
  "Camila is right: the two tables always agree, so grouping can never change an answer.",
  "Rosa is right that grouping changes the data, and this question happened to land on the one cut where the change is invisible.",
  "Neither is right. The tables disagree here, and one of them must have been built from different data."
)
$answer[2] = 0
$noshuffle[2] = "all"

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
      <p><span class="term-label">Step 1 &mdash; from the grouped table.</span> &quot;More than three&quot; is everything above the 2-3 row. The grouped table&rsquo;s cumulative on the 2-3 row is ' . $gc1d . ', so the answer is 1 &minus; ' . $gc1d . ' = <b>' . $vald . '</b>.</p>
      <p><span class="term-label">Step 2 &mdash; from the ungrouped table.</span> The cumulative on the 3 row is ' . $c3d . ', so the answer is 1 &minus; ' . $c3d . ' = <b>' . $vald . '</b>. The two tables agree.</p>
      <p><span class="term-label">Step 3 &mdash; the settlement.</span> They agree because the cut point, &quot;more than three&quot;, happens to fall exactly on the boundary between the 2-3 and 4-5 groups. But ask instead for &quot;more than two&quot; and the grouped table cannot answer it: 2 and 3 are locked together in one row, so you cannot separate them without going back to the raw data. Grouping never changes the data, but it can hide the very question you wanted to ask.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Camila is sure that grouping the 60 movie counts cannot change any answer. Her wife Rosa, who teaches the other section, thinks it can. You settle it: answer &quot;what percent of the data is more than three?&quot; once from each table.</p>
    <p style="margin:0.5em 0 0 0;"><b>Ungrouped table.</b></p>
    $uHtml
    <p style="margin:0;"><b>Grouped table.</b></p>
    $gHtml
    <p style="margin:0.5em 0 0 0;"><b>Ungrouped table.</b></p>
    $uHtml
    <p style="margin:0;"><b>Grouped table.</b></p>
    $gHtml
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">a.</span> From the <b>grouped</b> table with intervals 0-1, 2-3, 4-5, 6-7+, find the percent of the data that is more than three. Enter as a decimal. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">b.</span> From the <b>ungrouped</b> table, one row per count, find the same percent. Enter as a decimal. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">c.</span> Do the two answers agree, and what does that prove in general? $answerbox[2]
  </div>
</div>


// === ANSWER ===

$solutionguide