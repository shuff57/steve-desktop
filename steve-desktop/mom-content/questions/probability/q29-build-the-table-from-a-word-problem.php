// === NAME - DESCRIPTION: Build a Two-Way Table from a Word Problem - Turn four sentences of counts into a complete 2x2 with margins, then read a conditional probability off what you built ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 3.4, and deliberately harder than q27. There the table exists and cells are missing;
// here there is NO table -- the student is given prose and has to construct the whole thing. That
// is the step the book's exercises actually ask for, and a student who can only fill gaps in a
// printed grid cannot do it.
//
// The prose deliberately supplies a total, one row total, one joint count and one column total:
// enough to determine the table, and not laid out in table order.
$anstypes = array("number", "number", "number", "number", "numfunc")

$i = rand(0, 2)

$settings = array(
  "a coach recording whether each athlete stretched before a session and whether they reported soreness after",
  "a librarian recording whether each book was borrowed by a student and whether it was returned late",
  "a baker recording whether each loaf used the slow rise and whether it sold out before noon"
)
$setting = $settings[$i]

$rowY = array("Stretched", "Borrowed by a student", "Slow rise")
$rowN = array("Did not stretch", "Borrowed by staff", "Quick rise")
$colY = array("Reported soreness", "Returned late", "Sold out")
$colN = array("No soreness", "Returned on time", "Did not sell out")

$rY = $rowY[$i]
$rN = $rowN[$i]
$cY = $colY[$i]
$cN = $colN[$i]

$unit = array("athletes", "books", "loaves")
$who = $unit[$i]

$grand = 20 * rand(6, 10)
$r1 = 10 * rand(4, 8)
$r2 = $grand - $r1
$a = 5 * rand(2, 6)
$b = $r1 - $a
$c1 = $a + 5 * rand(2, 7)
$c = $c1 - $a
$d = $r2 - $c
$c2 = $grand - $c1

$pCond = $a / $r1

$answer[0] = $b
$answer[1] = $c
$answer[2] = $d
$answer[3] = $c2
$answer[4] = $pCond

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
  .built { border-collapse:collapse; margin:10px 0; font-size:15px; }
  .built td, .built th { border:1px solid #d1d5db; padding:6px 14px; text-align:center; }
  .built th { background:#f0f4ff; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary><span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span> Step-by-Step Solution</summary>
    <div class="sol-body">
      <p><span class="term-label">Place the given numbers first, then subtract.</span> The prose gives a grand total, one row total, one joint cell and one column total. That is exactly enough &mdash; everything else follows.</p>
      <table class="built">
        <tr><th></th><th>' . $cY . '</th><th>' . $cN . '</th><th>Total</th></tr>
        <tr><th>' . $rY . '</th><td><b>' . $a . '</b> given</td><td>' . $b . '</td><td><b>' . $r1 . '</b> given</td></tr>
        <tr><th>' . $rN . '</th><td>' . $c . '</td><td>' . $d . '</td><td>' . $r2 . '</td></tr>
        <tr><th>Total</th><td><b>' . $c1 . '</b> given</td><td>' . $c2 . '</td><td><b>' . $grand . '</b> given</td></tr>
      </table>
      <ul>
        <li>`' . $r1 . ' - ' . $a . ' = ' . $b . '` finishes the ' . $rY . ' row.</li>
        <li>`' . $c1 . ' - ' . $a . ' = ' . $c . '` finishes the ' . $cY . ' column.</li>
        <li>`' . $grand . ' - ' . $r1 . ' = ' . $r2 . '`, then `' . $r2 . ' - ' . $c . ' = ' . $d . '`.</li>
        <li>`' . $grand . ' - ' . $c1 . ' = ' . $c2 . '`, and it must equal `' . $b . ' + ' . $d . '`. It does.</li>
      </ul>
      <p><span class="term-label">Part (e).</span> "Among the ' . $rY . '" restricts to that row, so the denominator is ' . $r1 . ', not ' . $grand . ': `' . $a . ' -: ' . $r1 . ' = ' . $pCond . '`.</p>
      <p><span class="term-label">Why building it beats being given it.</span> Every count in the prose is a different KIND of number &mdash; a joint, a margin, a total &mdash; and they only sort themselves out once they are in position. Reading a finished table hides that step; this is where it lives.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The situation.</b> Consider $setting.</p>
    <ul style="margin:8px 0 0 0;">
      <li>There were <b>$grand</b> $who in total.</li>
      <li><b>$r1</b> of them were <b>$rY</b>.</li>
      <li><b>$a</b> were <b>$rY</b> <i>and</i> <b>$cY</b>.</li>
      <li>Altogether <b>$c1</b> were <b>$cY</b>.</li>
    </ul>
    <p style="margin:10px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;">Build the two-way table. There is no grid printed here on purpose &mdash; sketch one, place the four numbers above, and subtract to fill the rest.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> How many were <b>$rY</b> and <b>$cN</b>? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> How many were <b>$rN</b> and <b>$cY</b>? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> How many were <b>$rN</b> and <b>$cN</b>? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> How many were <b>$cN</b> altogether? $answerbox[3]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">e.</span> Among the <b>$rY</b> only, find the probability of <b>$cY</b>. Fraction or decimal to 4 places. $answerbox[4]
  </div>
</div>

// === ANSWER ===

$sol
