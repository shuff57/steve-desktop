// === NAME - DESCRIPTION: Read a Table of Probabilities - From four joint probabilities find P(A), P(A or B) and P(B given A), then test for independence ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 3.4. The table holds joint probabilities that add to 1, not counts. The row total is
// P(A), the three filled cells give P(A or B), and the conditional divides one cell by the row
// total. Part d compares P(B given A) with P(B). Cells are hundredths, so the totals are exact.
$anstypes = array("numfunc", "numfunc", "numfunc", "choices")

$i = rand(0, 2)
$stories = array("A store asked shoppers whether they bought a drink and whether they bought a snack.", "A school asked students whether they take the bus and whether they play a sport.", "A clinic asked patients whether they got a flu shot and whether they missed work.")
$story = $stories[$i]
$aYes = array("Bought a drink", "Takes the bus", "Got a flu shot")
$aNo = array("No drink", "Does not take the bus", "No flu shot")
$bYes = array("Bought a snack", "Plays a sport", "Missed work")
$bNo = array("No snack", "Does not play a sport", "Did not miss work")
$sA = array("a drink", "the bus", "a flu shot")
$sB = array("a snack", "a sport", "missed work")
$rA = $aYes[$i]
$rAn = $aNo[$i]
$cB = $bYes[$i]
$cBn = $bNo[$i]
$nameA = $sA[$i]
$nameB = $sB[$i]

$c11 = rand(10, 30) / 100
$c12 = rand(10, 30) / 100
$c21 = rand(10, 30) / 100
$c22 = round(1 - $c11 - $c12 - $c21, 2)
$pA = round($c11 + $c12, 2)
$pB = round($c11 + $c21, 2)
$pU = round($c11 + $c12 + $c21, 2)
$pBgA = $c11 / $pA
$gap = abs($pBgA - $pB)
$ind = 1
if ($gap < 0.0001) {
  $ind = 0
}

$questions[3] = array("Yes, P(B given A) equals P(B)", "No, P(B given A) is different from P(B)")
$dText = "They are not equal, so the events are not independent."
if ($ind == 0) {
  $dText = "They are equal, so the events are independent."
}
$answer[0] = $pA
$answer[1] = $pU
$answer[2] = $pBgA
$answer[3] = $ind
$displayformat[3] = "select"

$sol = '
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
    <summary><span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span> Step-by-Step Solution</summary>
    <div class="sol-body">
      <p><span class="term-label">(a) A row total.</span> `P(A)` is the whole top row: `' . $c11 . ' + ' . $c12 . ' = ' . $pA . '`.</p>
      <p><span class="term-label">(b) A or B.</span> Add the three cells that have A or B (or both). Leave out the cell with neither: `' . $c11 . ' + ' . $c12 . ' + ' . $c21 . ' = ' . $pU . '`. The other way: `1 - ' . $c22 . ' = ' . $pU . '`.</p>
      <p><span class="term-label">(c) Conditional.</span> Given A, look only at the top row. `P(B given A) = ' . $c11 . ' / ' . $pA . ' = ' . $pBgA . '`.</p>
      <p><span class="term-label">(d) Independence.</span> `P(B) = ' . $c11 . ' + ' . $c21 . ' = ' . $pB . '`. Compare it with `P(B given A) = ' . $pBgA . '`. ' . $dText . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$story The table shows the probability of each combination. Let A be "$rA" and B be "$cB".</p>
    <table style="border-collapse:collapse; margin:10px 0; font-size:15px;">
      <tr><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff;"></th><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff;">$cB</th><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff;">$cBn</th></tr>
      <tr><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff; text-align:left;">$rA</th><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$c11</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$c12</td></tr>
      <tr><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff; text-align:left;">$rAn</th><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$c21</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$c22</td></tr>
    </table>
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Enter each probability as a decimal rounded to 4 places.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> <b>P(A)</b> $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> <b>P(A or B)</b> $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> <b>P(B given A)</b> $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Are A and B independent?<br><br>$answerbox[3]
  </div>
</div>

// === ANSWER ===

$sol
