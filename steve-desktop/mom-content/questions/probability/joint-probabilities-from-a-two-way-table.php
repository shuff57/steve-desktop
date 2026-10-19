// === NAME - DESCRIPTION: Joint Probabilities from a Two-Way Table - Divide cell counts by the grand total to get three joint probabilities, P(A and B) for chosen cells ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 3.4. A count table becomes a joint probability table: every cell divided by the grand total.
// Three of the four cells are asked, in a random choice.
// Each cell is 8 to 30, so the grand total is at most 120 and no probability is below 8/120.
$anstypes = array("numfunc", "numfunc", "numfunc")

$i = rand(0, 3)
$whos = array("workers", "students", "players", "library visitors")
$descs = array("their shift and whether they arrived on time", "their year and whether they joined a club", "their hand and whether they play an instrument", "their age group and whether they borrowed a book")
$rowA = array("Morning shift", "Seniors", "Left-handed", "Adults")
$rowB = array("Evening shift", "Juniors", "Right-handed", "Children")
$colA = array("On time", "Joined a club", "Plays an instrument", "Borrowed a book")
$colB = array("Late", "Did not join", "Does not play", "Did not borrow")
$who = $whos[$i]
$desc = $descs[$i]
$r1 = $rowA[$i]
$r2 = $rowB[$i]
$c1 = $colA[$i]
$c2 = $colB[$i]

$n11 = rand(8, 30)
$n12 = rand(8, 30)
$n21 = rand(8, 30)
$n22 = rand(8, 30)
$R1 = $n11 + $n12
$R2 = $n21 + $n22
$C1 = $n11 + $n21
$C2 = $n12 + $n22
$N = $R1 + $R2

$labels = array($r1 . " and " . $c1, $r1 . " and " . $c2, $r2 . " and " . $c1, $r2 . " and " . $c2)
$counts = array($n11, $n12, $n21, $n22)
$orders = array(array(1, 2, 3), array(0, 2, 3), array(0, 1, 3), array(0, 1, 2))
$skip = rand(0, 3)
$o = $orders[$skip]
$lab0 = $labels[$o[0]]
$lab1 = $labels[$o[1]]
$lab2 = $labels[$o[2]]
$k0 = $counts[$o[0]]
$k1 = $counts[$o[1]]
$k2 = $counts[$o[2]]
$labS = $labels[$skip]
$kS = $counts[$skip]

$p0 = $k0 / $N
$p1 = $k1 / $N
$p2 = $k2 / $N
$pS = $kS / $N

$answer[0] = $p0
$answer[1] = $p1
$answer[2] = $p2

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
      <p><span class="term-label">Each cell is a joint probability.</span> The count in a cell, divided by the grand total ' . $N . ', is the probability of both things at once: `P(A and B) = count / ' . $N . '`.</p>
      <ul>
        <li><b>(a)</b> `' . $k0 . ' -: ' . $N . ' = ' . $p0 . '`</li>
        <li><b>(b)</b> `' . $k1 . ' -: ' . $N . ' = ' . $p1 . '`</li>
        <li><b>(c)</b> `' . $k2 . ' -: ' . $N . ' = ' . $p2 . '`</li>
      </ul>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A survey of $N $who, sorted by $desc.</p>
    <table style="border-collapse:collapse; margin:10px 0; font-size:15px;">
      <tr><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff;"></th><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff;">$c1</th><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff;">$c2</th><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff;">Total</th></tr>
      <tr><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff;">$r1</th><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$n11</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$n12</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$R1</td></tr>
      <tr><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff;">$r2</th><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$n21</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$n22</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$R2</td></tr>
      <tr><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff;">Total</th><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$C1</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$C2</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$N</td></tr>
    </table>
    <p style="margin:6px 0 0 0; color:#374151; font-size:14px;">Divide by the grand total. Enter each probability as a fraction or a decimal rounded to 4 places.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> <b>P($lab0)</b> $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> <b>P($lab1)</b> $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> <b>P($lab2)</b> $answerbox[2]
  </div>
</div>

// === ANSWER ===

$sol
