// === NAME - DESCRIPTION: Three Denominators from One Table - Compute a marginal, a joint and a conditional probability off the same 2x2 and identify which total each one divides by ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 3.4. The single most useful thing a student can learn from a two-way table is that the
// three probabilities differ ONLY in what goes underneath. Same numerator in (b) and (c); different
// denominator; different answer. Part (d) makes them name the denominator rather than infer it.
$anstypes = array("numfunc", "numfunc", "numfunc", "choices")

$i = rand(0, 2)

$contexts = array(
  "240 concert tickets, sorted by whether they were bought online and whether the buyer was a member",
  "240 support calls, sorted by whether the caller used the app and whether the issue was resolved",
  "240 seedlings, sorted by whether they were grown under lights and whether they flowered"
)
$context = $contexts[$i]

$rY = array("Bought online", "Used the app", "Under lights")
$rN = array("Bought at the door", "Called in", "Natural light")
$cY = array("Member", "Resolved", "Flowered")
$cN = array("Non-member", "Unresolved", "Did not flower")

$rowY = $rY[$i]
$rowN = $rN[$i]
$colY = $cY[$i]
$colN = $cN[$i]

$grand = 240
$r1 = 12 * rand(6, 12)
$r2 = $grand - $r1
$a = 6 * rand(4, 10)
$b = $r1 - $a
$c = 6 * rand(3, 9)
$d = $r2 - $c
$c1 = $a + $c
$c2 = $grand - $c1

$pMarginal = $r1 / $grand
$pJoint = $a / $grand
$pCond = $a / $r1

$questions[3] = array(
  "the " . $rowY . " row total, " . $r1 . ", because the question restricts attention to that row",
  "the grand total, " . $grand . ", because every probability divides by the whole group",
  "the " . $colY . " column total, " . $c1 . ", because that is the event being asked about",
  "the joint count, " . $a . ", because that is where the calculation starts"
)
$answer[0] = $pMarginal
$answer[1] = $pJoint
$answer[2] = $pCond
$answer[3] = 0

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
      <p><span class="term-label">Look at what changes.</span> Parts (b) and (c) have the SAME numerator, ' . $a . '. Only the denominator moves, and the answers are different because of it.</p>
      <ul>
        <li><b>(a) Marginal.</b> `' . $r1 . ' -: ' . $grand . ' = ' . $pMarginal . '`. How much of the whole group is ' . $rowY . '.</li>
        <li><b>(b) Joint.</b> `' . $a . ' -: ' . $grand . ' = ' . $pJoint . '`. How much of the whole group is BOTH.</li>
        <li><b>(c) Conditional.</b> `' . $a . ' -: ' . $r1 . ' = ' . $pCond . '`. Of the ' . $rowY . ' group only, how much is ' . $colY . '.</li>
      </ul>
      <p><span class="term-label">Part (d).</span> The conditional divides by the ' . $rowY . ' row total, ' . $r1 . '. The word "of" in "of those who ' . $rowY . '" is the instruction to throw away the other row: the grand total ' . $grand . ' answers a question nobody asked.</p>
      <p><span class="term-label">The check worth carrying.</span> A conditional is always at least as large as the joint with the same numerator, because its denominator is smaller. Here `' . $pCond . ' >= ' . $pJoint . '`. If your conditional comes out below your joint, you divided by the wrong thing.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A record of $context.</p>
    <table style="border-collapse:collapse; margin:10px 0; font-size:15px;">
      <tr><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff;"></th><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff;">$colY</th><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff;">$colN</th><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff;">Total</th></tr>
      <tr><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff;">$rowY</th><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$a</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$b</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$r1</td></tr>
      <tr><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff;">$rowN</th><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$c</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$d</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$r2</td></tr>
      <tr><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff;">Total</th><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$c1</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$c2</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$grand</td></tr>
    </table>
    <p style="margin:6px 0 0 0; color:#374151; font-size:14px;">Enter each probability as a fraction or a decimal rounded to 4 places.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> <b>P($rowY)</b> $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> <b>P($rowY and $colY)</b> $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Of those who $rowY, the probability of <b>$colY</b>. $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> In part (c), what went underneath? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$sol
