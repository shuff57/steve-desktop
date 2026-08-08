// === NAME - DESCRIPTION: Complete a Grouped Frequency Table - given a grouped table of survival lengths with one frequency missing, find that frequency and the relative and cumulative relative frequencies of a named interval ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number")

$n = 40

// Six intervals of width 6, matching the section's grouping.
$f1 = rand(3, 7)
$f2 = rand(5, 9)
$f3 = rand(7, 11)
$f4 = rand(5, 9)
$f5 = rand(3, 6)
$f6 = $n - $f1 - $f2 - $f3 - $f4 - $f5

$mi = rand(0, 4)
$freqs = array($f1, $f2, $f3, $f4, $f5, $f6)
$missing = $freqs[$mi]

$lo = array("0.5", "6.5", "12.5", "18.5", "24.5", "30.5")
$hi = array("6.5", "12.5", "18.5", "24.5", "30.5", "36.5")
$missLo = $lo[$mi]
$missHi = $hi[$mi]

// The interval whose relative and cumulative relative frequency are asked for.
$ti = rand(1, 5) where ($ti != $mi)
$tLo = $lo[$ti]
$tHi = $hi[$ti]
$tFreq = $freqs[$ti]

$cum = 0
for ($i = 0..5) {
  if ($i <= $ti) { $cum = $cum + $freqs[$i] }
}
$relT = round($tFreq / $n, 4)
$cumT = round($cum / $n, 4)

$knownSum = $n - $missing

$answer[0] = $missing
$answerformat[0] = "integer"
$answer[1] = $relT
$abstolerance[1] = 0.00011
$answer[2] = $cumT
$abstolerance[2] = 0.00011

$tableHtml = '<table style="border-collapse:collapse; margin:10px 0; font-size:15px;">'
$tableHtml = $tableHtml . '<tr style="background:#e8f0fe;"><th style="border:1px solid #c8d4ea; padding:6px 14px;">Survival Length (days)</th><th style="border:1px solid #c8d4ea; padding:6px 14px;">Frequency</th></tr>'
for ($i = 0..5) {
  $cell = $freqs[$i]
  if ($i == $mi) { $cell = '<b style="color:#1865f2;">?</b>' }
  $tableHtml = $tableHtml . '<tr><td style="border:1px solid #d9dee8; padding:6px 14px;">' . $lo[$i] . '&ndash;' . $hi[$i] . '</td><td style="border:1px solid #d9dee8; padding:6px 14px; text-align:center;">' . $cell . '</td></tr>'
}
$tableHtml = $tableHtml . '<tr style="background:#f6f8fc;"><td style="border:1px solid #d9dee8; padding:6px 14px;"><b>Total</b></td><td style="border:1px solid #d9dee8; padding:6px 14px; text-align:center;"><b>' . $n . '</b></td></tr></table>'

$solutionguide = '<div style="font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <p><b>a &mdash; the missing frequency.</b> The Frequency column must add to the total of ' . $n . ' patients. The five known intervals add to ' . $knownSum . ', so the ' . $missLo . '&ndash;' . $missHi . ' interval holds ' . $n . ' &minus; ' . $knownSum . ' = <b>' . $missing . '</b>.</p>
  <p><b>b &mdash; relative frequency of ' . $tLo . '&ndash;' . $tHi . '.</b> Divide that interval&rsquo;s frequency by the total: ' . $tFreq . ' &divide; ' . $n . ' = <b>' . $relT . '</b>. A relative frequency is always a proportion of the whole, never of a neighbouring interval.</p>
  <p><b>c &mdash; cumulative relative frequency through ' . $tLo . '&ndash;' . $tHi . '.</b> Add every frequency up to and <i>including</i> that interval &mdash; ' . $cum . ' patients &mdash; then divide by ' . $n . ': <b>' . $cumT . '</b>.</p>
  <p><b>Two checks worth running.</b> The cumulative column must finish at exactly 1.000 on the last interval, and the frequencies must add back to ' . $n . '. If either misses, an interval was miscounted or a division used the wrong total.</p>
  <p><b>Why the boundaries end in .5:</b> survival length is recorded in whole days, so a cut at 6.5 cannot land on an actual value. No patient can sit on a boundary, and no reader has to guess which interval a 6 belongs to.</p>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0;">A physician recorded, for each of <b>$n</b> patients treated for a respiratory virus, the number of days from the start of treatment until symptoms were relieved. The results are grouped below, with one frequency missing.</p>
    $tableHtml
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">a.</span> Find the <b>missing frequency</b> for the $missLo&ndash;$missHi interval. Enter a whole number. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">b.</span> Find the <b>relative frequency</b> of the $tLo&ndash;$tHi interval. Round to <b>four decimal places</b>. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">c.</span> Find the <b>cumulative relative frequency</b> through the $tLo&ndash;$tHi interval. Round to <b>four decimal places</b>. $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
