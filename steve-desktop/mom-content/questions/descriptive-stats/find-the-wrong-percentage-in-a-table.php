// === NAME - DESCRIPTION: Find the Wrong Percentage in a Category Table - A published table gives counts beside percentages, but one percentage does not match its count; find the row, compute what it should be, and state what the column must total ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "number", "number")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "A community centre published this breakdown of how its members travel there."
  $colLabel = "Method of travel"
  $unitWord = "members"
  $unitOne = "member"
  $t0 = "Walk"
  $t1 = "Cycle"
  $t2 = "Bus"
  $t3 = "Drive"
  $t4 = "Other"
}
else {
  $intro = "A bookshop published this breakdown of what its customers bought last weekend."
  $colLabel = "Section"
  $unitWord = "customers"
  $unitOne = "customer"
  $t0 = "Fiction"
  $t1 = "Children"
  $t2 = "Cookery"
  $t3 = "History"
  $t4 = "Other"
}
$labels = array($t0, $t1, $t2, $t3, $t4)

$cts = array(0, 0, 0, 0, 0)
$n = 0
for ($k=0..4) {
  $cts[$k] = rand(14, 46)
  $n = $n + $cts[$k]
}

// One published percentage is wrong. The error is at least 4 points so it survives rounding and
// is genuinely findable, and the direction varies so students cannot learn "the big one is wrong".
$bad = rand(0, 4)
$err = rand(4, 9)
if (rand(0, 1) == 0) { $err = 0 - $err }

$trueP = array(0, 0, 0, 0, 0)
$shownP = array(0, 0, 0, 0, 0)
$shownTotal = 0
$tableRows = ""
for ($k=0..4) {
  $trueP[$k] = round(100 * $cts[$k] / $n, 1)
  $shownP[$k] = $trueP[$k]
  if ($k == $bad) { $shownP[$k] = round($trueP[$k] + $err, 1) }
  $shownTotal = round($shownTotal + $shownP[$k], 1)
  $tableRows = $tableRows . '<tr><td style="border:1px solid #d1d5db; padding:6px 16px;">' . $labels[$k] . '</td><td style="border:1px solid #d1d5db; padding:6px 16px; text-align:center;">' . $cts[$k] . '</td><td style="border:1px solid #d1d5db; padding:6px 16px; text-align:center;">' . $shownP[$k] . '%</td></tr>'
}

$badLabel = $labels[$bad]
$badCount = $cts[$bad]
$badShown = $shownP[$bad]
$badTrue = $trueP[$bad]

$questions[0] = array($t0, $t1, $t2, $t3, $t4)
$answer[0] = $bad

$answer[1] = $badTrue
$reltolerance[1] = 0.02
$abstolerance[1] = 0.15

$answer[2] = 100
$answerformat[2] = "integer"

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
      <p><span class="term-label">Step 1 &mdash; add the percentage column first.</span> It comes to ' . $shownTotal . '%. Every ' . $unitWord . ' falls in exactly one row, so the column has to come to 100% apart from a little rounding. It does not, so at least one row is wrong &mdash; and this one check finds the problem before any arithmetic.</p>
      <p><span class="term-label">Step 2 &mdash; find the total count.</span> ' . $cts[0] . ' + ' . $cts[1] . ' + ' . $cts[2] . ' + ' . $cts[3] . ' + ' . $cts[4] . ' = <b>' . $n . '</b> ' . $unitWord . '. This is the whole that every percentage is a part of.</p>
      <p><span class="term-label">Step 3 &mdash; recompute each row.</span> Work out 100 &times; count / ' . $n . ' for each row and compare. Every row matches its published figure except <b>' . $badLabel . '</b>, printed as ' . $badShown . '%.</p>
      <p><span class="term-label">Step 4 &mdash; the correct figure.</span> 100 &times; ' . $badCount . ' / ' . $n . ' &approx; <b>' . $badTrue . '%</b>. Notice the count itself was never in doubt &mdash; only the percentage derived from it. A published percentage is somebody\'s arithmetic, and this is how you check it.</p>
      <p><b>Answer:</b> (a) ' . $badLabel . ' &nbsp;&nbsp; (b) ' . $badTrue . '% &nbsp;&nbsp; (c) 100%</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">$intro Each of the $n $unitWord is counted in exactly one row. The counts are correct, but <b>one of the published percentages is wrong</b>.</p>
    <table style="border-collapse:collapse; margin:0;">
      <thead>
        <tr style="background:#f0f4ff;">
          <th style="border:1px solid #d1d5db; padding:7px 16px; text-align:left;">$colLabel</th>
          <th style="border:1px solid #d1d5db; padding:7px 16px;">Number of $unitWord</th>
          <th style="border:1px solid #d1d5db; padding:7px 16px;">Published percent</th>
        </tr>
      </thead>
      <tbody>
        $tableRows
      </tbody>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which row has the wrong percentage? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What should that percentage be? (Give your answer to one decimal place, without the percent sign.) $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> When every $unitOne falls in exactly one row, what should the percent column add up to? (Give a number, without the percent sign.) $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
