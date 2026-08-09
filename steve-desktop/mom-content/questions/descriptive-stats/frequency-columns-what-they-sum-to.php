// === NAME - DESCRIPTION: What the Frequency Columns Sum To - State the totals of the frequency and relative frequency columns of a survey table, then separate what frequency, relative frequency and cumulative relative frequency each measure ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "choices", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "A dealership group asked a random sample of its car salespeople how many cars they generally sell in a week."
  $valueLabel = "Cars sold in a week"
  $unitWord = "salespeople"
  $unitOne = "salesperson"
  $v0 = 3
}
else {
  $intro = "A clinic asked a random sample of its patients how many appointments they had attended in the past year."
  $valueLabel = "Appointments in the past year"
  $unitWord = "patients"
  $unitOne = "patient"
  $v0 = 2
}

$f = array(0, 0, 0, 0, 0)
$n = 0
for ($k=0..4) {
  $f[$k] = rand(8, 22)
  $n = $n + $f[$k]
}

$running = 0
$tableRows = ""
for ($k=0..4) {
  $running = $running + $f[$k]
  $val = $v0 + $k
  $tableRows = $tableRows . '<tr><td style="border:1px solid #d1d5db; padding:6px 18px; text-align:center;">' . $val . '</td><td style="border:1px solid #d1d5db; padding:6px 18px; text-align:center;">' . $f[$k] . '</td><td style="border:1px solid #d1d5db; padding:6px 18px; text-align:center; color:#9ca3af;">?</td><td style="border:1px solid #d1d5db; padding:6px 18px; text-align:center; color:#9ca3af;">?</td></tr>'
}

// One row picked out, so the two concept parts are asked against a value the student can see.
$ai = rand(1, 3)
$askValue = $v0 + $ai
$askFreq = $f[$ai]
$askRel = round($f[$ai] / $n, 3)
$cumThrough = 0
for ($k=0..$ai) {
  $cumThrough = $cumThrough + $f[$k]
}
$askCum = round($cumThrough / $n, 3)
$lowValue = $v0
$askRelPct = round(100 * $f[$ai] / $n, 1)
$askCumPct = round(100 * $cumThrough / $n, 1)

$answer[0] = $n
$answerformat[0] = "integer"

$answer[1] = 1
$reltolerance[1] = 0.02
$abstolerance[1] = 0.01

$questions[2] = array(
  "Frequency counts how many " . $unitWord . " gave that answer. Relative frequency is that same count divided by the sample size, so it is the fraction of the sample rather than a headcount.",
  "Frequency and relative frequency are two names for the same number, written as a count and as a decimal.",
  "Frequency is the count for that row; relative frequency is the count divided by the number of rows in the table.",
  "Frequency counts the " . $unitWord . "; relative frequency counts the rows at or below that value."
)
$answer[2] = 0

$questions[3] = array(
  "Relative frequency covers that row alone. Cumulative relative frequency adds up every row from the first through that one, so it answers \"what fraction gave this answer or a smaller one\".",
  "They are the same thing, but cumulative relative frequency is rounded to more decimal places.",
  "Cumulative relative frequency covers that row alone; relative frequency adds up every row through it.",
  "Cumulative relative frequency is the relative frequency multiplied by the sample size."
)
$answer[3] = 0

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
      <p><span class="term-label">Part (a) &mdash; the frequency column.</span> ' . $f[0] . ' + ' . $f[1] . ' + ' . $f[2] . ' + ' . $f[3] . ' + ' . $f[4] . ' = <b>' . $n . '</b>. Every ' . $unitOne . ' surveyed gave exactly one answer and is counted in exactly one row, so the column has to come back to the sample size. If it does not, a response has been missed or counted twice.</p>
      <p><span class="term-label">Part (b) &mdash; the relative frequency column.</span> <b>1</b>. Each entry is that row\'s count over ' . $n . ', so adding them is the same as adding all the counts and then dividing once: ' . $n . ' / ' . $n . ' = 1. As percentages, 100%. Rounding each row separately can leave the printed column a tenth off, which is worth expecting rather than treating as an error.</p>
      <p><span class="term-label">Part (c) &mdash; frequency against relative frequency.</span> For the value ' . $askValue . ', the frequency is ' . $askFreq . ' &mdash; a headcount. The relative frequency is ' . $askFreq . ' / ' . $n . ' &approx; ' . $askRel . ', or ' . $askRelPct . '% &mdash; a share of the sample. The count answers "how many", the share answers "how much of the group", and only the share can be compared against a survey of a different size.</p>
      <p><span class="term-label">Part (d) &mdash; relative against cumulative relative.</span> Relative frequency is that row on its own. Cumulative relative frequency runs from the first row through that one: for ' . $askValue . ' it is (' . $cumThrough . ') / ' . $n . ' &approx; ' . $askCum . ', so about ' . $askCumPct . '% answered ' . $askValue . ' or fewer. The last row\'s cumulative figure is always 1, which is a free check on the whole column.</p>
      <p><b>Answer:</b> (a) ' . $n . ' &nbsp;&nbsp; (b) 1</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">$intro Each of the $unitWord gave exactly one answer. The last two columns have not been filled in.</p>
    <table style="border-collapse:collapse; margin:0;">
      <thead>
        <tr style="background:#f0f4ff;">
          <th style="border:1px solid #d1d5db; padding:7px 18px;">$valueLabel</th>
          <th style="border:1px solid #d1d5db; padding:7px 18px;">Frequency</th>
          <th style="border:1px solid #d1d5db; padding:7px 18px;">Relative frequency</th>
          <th style="border:1px solid #d1d5db; padding:7px 18px;">Cumulative relative frequency</th>
        </tr>
      </thead>
      <tbody>
        $tableRows
      </tbody>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What does the <b>frequency</b> column add up to? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Once it is filled in, what will the <b>relative frequency</b> column add up to? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> For the value $askValue, what is the difference between its <b>frequency</b> and its <b>relative frequency</b>? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> For the value $askValue, what is the difference between its <b>relative frequency</b> and its <b>cumulative relative frequency</b>? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
