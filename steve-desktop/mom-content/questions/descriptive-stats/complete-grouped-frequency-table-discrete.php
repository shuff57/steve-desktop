// === NAME - DESCRIPTION: Complete a Grouped Frequency Table with Discrete Classes - given a grouped table of whole-number counts with one frequency missing, find that frequency and the relative and cumulative relative frequencies of a named class ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number")

// Six classes, width 6, all whole-number counts. Every class starts with a floor of 1 so no class
// (including the one left blank) ever shows a frequency of 0, then the rest of n is dealt out at
// random: this guarantees the total is always exactly n, on every seed.
$n = 42
$cnt = array(1, 1, 1, 1, 1, 1)
for ($k = 0..35) {
  $cls = rand(0, 5)
  $cnt[$cls] = $cnt[$cls] + 1
}

$lo = array(0, 6, 12, 18, 24, 30)
$hi = array(5, 11, 17, 23, 29, 35)

$mi = rand(0, 5)
$missing = $cnt[$mi]
$missLo = $lo[$mi]
$missHi = $hi[$mi]

// The class whose relative and cumulative relative frequency are asked for.
$ti = rand(0, 5) where ($ti != $mi)
$tLo = $lo[$ti]
$tHi = $hi[$ti]
$tFreq = $cnt[$ti]

$cum = 0
for ($i = 0..5) {
  if ($i <= $ti) { $cum = $cum + $cnt[$i] }
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
$tableHtml = $tableHtml . '<tr style="background:#e8f0fe;"><th style="border:1px solid #c8d4ea; padding:6px 14px;">Text Messages Sent</th><th style="border:1px solid #c8d4ea; padding:6px 14px;">Frequency</th></tr>'
for ($i = 0..5) {
  $cell = $cnt[$i]
  if ($i == $mi) { $cell = '<b style="color:#1865f2;">?</b>' }
  $tableHtml = $tableHtml . '<tr><td style="border:1px solid #d9dee8; padding:6px 14px;">' . $lo[$i] . '&ndash;' . $hi[$i] . '</td><td style="border:1px solid #d9dee8; padding:6px 14px; text-align:center;">' . $cell . '</td></tr>'
}
$tableHtml = $tableHtml . '<tr style="background:#f6f8fc;"><td style="border:1px solid #d9dee8; padding:6px 14px;"><b>Total</b></td><td style="border:1px solid #d9dee8; padding:6px 14px; text-align:center;"><b>' . $n . '</b></td></tr></table>'

$solutionguide = '<div style="font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <p><b>a: the missing frequency.</b> The Frequency column must add to the total of ' . $n . ' teens. The five known classes add to ' . $knownSum . ', so the ' . $missLo . '&ndash;' . $missHi . ' class holds ' . $n . ' &minus; ' . $knownSum . ' = <b>' . $missing . '</b>.</p>
  <p><b>b: relative frequency of ' . $tLo . '&ndash;' . $tHi . '.</b> Divide that class&rsquo;s frequency by the total: ' . $tFreq . ' &divide; ' . $n . ' = <b>' . $relT . '</b>. A relative frequency is always a proportion of the whole, never of a neighboring class.</p>
  <p><b>c: cumulative relative frequency through ' . $tLo . '&ndash;' . $tHi . '.</b> Add every frequency up to and including that class: ' . $cum . ' teens: then divide by ' . $n . ': <b>' . $cumT . '</b>.</p>
  <p><b>Two checks worth running.</b> The cumulative column must finish at exactly 1.000 on the last class, and the frequencies must add back to ' . $n . '. If either misses, a class was miscounted or a division used the wrong total.</p>
  <p><b>Why these boundaries are whole numbers:</b> a text count is already a whole number, so there is no value that could sit between two classes the way a continuous measurement can. The 5 at the top of the first row and the 6 at the bottom of the second row are two different, adjacent counts, not one shared cutoff: nobody sends 5.5 texts.</p>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0;">A researcher recorded, for each of <b>$n</b> teens in a one-hour study period, the number of text messages sent. The results are grouped below, with one frequency missing.</p>
    $tableHtml
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">a.</span> Find the <b>missing frequency</b> for the $missLo&ndash;$missHi class. Enter a whole number. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">b.</span> Find the <b>relative frequency</b> of the $tLo&ndash;$tHi class. Round to <b>four decimal places</b>. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">c.</span> Find the <b>cumulative relative frequency</b> through the $tLo&ndash;$tHi class. Round to <b>four decimal places</b>. $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
