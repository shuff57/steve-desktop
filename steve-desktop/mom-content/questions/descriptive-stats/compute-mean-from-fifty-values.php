// === NAME - DESCRIPTION: Compute Mean from Fifty Values - Given 50 state-level mean commute times, compute the overall mean ===
// === SET QUESTION TYPE TO: number ===

// === COMMON CONTROL ===

$sum = 0
for ($i=0..49) {
  $v[$i] = rand(160, 320) / 10
  $sum = $sum + $v[$i]
}
$mean = round($sum / 50, 2)

$tablehtml = '<table style="border-collapse:collapse; margin:10px 0; font-family:monospace;">'
for ($r=0..4) {
  $tablehtml = $tablehtml . '<tr>'
  for ($c=0..9) {
    $idx = $r * 10 + $c
    $tablehtml = $tablehtml . '<td style="border:1px solid #ccc; padding:4px 10px; text-align:center;">' . $v[$idx] . '</td>'
  }
  $tablehtml = $tablehtml . '</tr>'
}
$tablehtml = $tablehtml . '</table>'

$answer = $mean
$abstolerance = 0.005
$reqdecimals = "=2"

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><b>Step 1 — Add all 50 state commute times.</b> The sum of the 50 values is ' . $sum . '.</p>
      <p><b>Step 2 — Divide by the number of states to get the mean:</b></p>
      <p>`' . $sum . ' / 50 = ' . $mean . '`</p>
      <p><b>Step 3 — Round properly.</b> Each state\'s travel time was measured to the nearest tenth (one decimal place), so the final answer carries one more decimal place than the data — two decimal places.</p>
      <p><b>Answer:</b> The mean travel time is ' . $mean . ' minutes.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">The table below shows the mean commute time (in minutes) for workers in each of the 50 U.S. states, based on data from workers at least 16 years old who do not work from home.</p>
    <p style="margin:0 0 10px 0;">$tablehtml</p>
    <p style="margin:0;">Each value is reported to one decimal place. Compute the overall mean commute time across all 50 states, and round your answer to <b>two</b> decimal places.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    Mean commute time: $answerbox
  </div>
</div>

// === ANSWER ===

$solutionguide
