// === NAME - DESCRIPTION: Complete a Two-Way Table - Fill the missing cells and margins of a 2x2 from the counts that are given, then read one probability off the finished table ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 3.4. The section is about reading a two-way table, and a student who can only read a
// finished one has not understood that the margins are sums. So this is a BUILD question, not a
// read question: four blanks, each recoverable by one subtraction, and only then a probability.
//
// Every value is an integer by construction and every blank has exactly one right answer.
$anstypes = array("number", "number", "number", "number", "numfunc")

$i = rand(0, 2)

$contexts = array(
  "a campus survey of 200 students, sorted by whether they commute and whether they hold a job",
  "a clinic audit of 200 visits, sorted by whether the visit was booked online and whether it ran late",
  "a store's record of 200 orders, sorted by whether the order was delivered and whether it was returned"
)
$context = $contexts[$i]

$rowYes = array("Commutes", "Booked online", "Delivered")
$rowNo = array("Lives on campus", "Booked by phone", "Collected in store")
$colYes = array("Has a job", "Ran late", "Returned")
$colNo = array("No job", "On time", "Kept")

$rY = $rowYes[$i]
$rN = $rowNo[$i]
$cY = $colYes[$i]
$cN = $colNo[$i]

$grand = 200
$r1 = 10 * rand(8, 12)
$r2 = $grand - $r1
$a = 10 * rand(3, 6)
$b = $r1 - $a
$c1 = $a + 10 * rand(2, 5)
$c = $c1 - $a
$d = $r2 - $c
$c2 = $grand - $c1

// The answer key lives in COMMON CONTROL. The final marker section is the SOLUTION field, not the
// key: putting $answer[] down there leaves the question with no key and it still saves cleanly.
// (Do not write the marker text itself in a comment: the splitter matches it and cuts here.)
$answer[0] = $b
$answer[1] = $c
$answer[2] = $d
$answer[3] = $c2
$answer[4] = $a / $grand

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
      <p><span class="term-label">Every blank is one subtraction.</span> A margin is the sum of the two cells beside it, so any missing entry is its margin minus the cell you already have.</p>
      <ul>
        <li><b>(a)</b> The ' . $rY . ' row totals ' . $r1 . ' and holds ' . $a . ' in the ' . $cY . ' column, so the other cell is `' . $r1 . ' - ' . $a . ' = ' . $b . '`.</li>
        <li><b>(b)</b> The ' . $cY . ' column totals ' . $c1 . ', so the ' . $rN . ' entry is `' . $c1 . ' - ' . $a . ' = ' . $c . '`.</li>
        <li><b>(c)</b> The ' . $rN . ' row totals `' . $grand . ' - ' . $r1 . ' = ' . $r2 . '`, so its last cell is `' . $r2 . ' - ' . $c . ' = ' . $d . '`.</li>
        <li><b>(d)</b> The ' . $cN . ' column totals `' . $grand . ' - ' . $c1 . ' = ' . $c2 . '`. Check it against the two cells above it: `' . $b . ' + ' . $d . ' = ' . $c2 . '`.</li>
      </ul>
      <p><span class="term-label">Part (e).</span> The joint probability uses the GRAND total underneath, because nothing has been restricted: `' . $a . ' -: ' . $grand . '`. Dividing by the row total instead would answer a different question: that is the conditional, and it comes later in this section.</p>
      <p><span class="term-label">Why the check in (d) matters.</span> A two-way table is over-determined: the margins have to agree down the columns AND across the rows. If your last cell does not satisfy both, an earlier subtraction went wrong, and the table tells you so before any probability is computed.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">Here is $context. Some entries are missing.</p>
    <table style="border-collapse:collapse; margin:10px 0; font-size:15px;">
      <tr>
        <th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff;"></th>
        <th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff;">$cY</th>
        <th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff;">$cN</th>
        <th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff;">Total</th>
      </tr>
      <tr>
        <th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff;">$rY</th>
        <td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$a</td>
        <td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center; background:#fff9ea;"><b>(a)</b></td>
        <td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$r1</td>
      </tr>
      <tr>
        <th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff;">$rN</th>
        <td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center; background:#fff9ea;"><b>(b)</b></td>
        <td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center; background:#fff9ea;"><b>(c)</b></td>
        <td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$r2</td>
      </tr>
      <tr>
        <th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff;">Total</th>
        <td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$c1</td>
        <td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center; background:#fff9ea;"><b>(d)</b></td>
        <td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$grand</td>
      </tr>
    </table>
    <p style="margin:6px 0 0 0; color:#374151; font-size:14px;">Each missing entry can be recovered by one subtraction.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The <b>$rY</b> row, <b>$cN</b> column. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The <b>$rN</b> row, <b>$cY</b> column. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> The <b>$rN</b> row, <b>$cN</b> column. $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> The <b>$cN</b> column total. $answerbox[3]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">e.</span> Using the finished table, find <b>P($rY and $cY)</b>. Enter a fraction or a decimal rounded to 4 places. $answerbox[4]
  </div>
</div>

// === ANSWER ===

$sol
