// === NAME - DESCRIPTION: Cumulative Relative Frequency "At Most" from Hurricane Table - Given a frequency table of hurricane direct hits by category (1-5), compute the cumulative relative frequency of storms at most category 3 ===
// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL ===

$c1 = rand(90, 120)
$c2 = rand(55, 85)
$c5 = rand(1, 8)
// The where must sit on the LAST free draw, and on one with room to move. It was on $c5: eight
// possible values: so when $c1..$c3 already summed outside 215-264 NO $c5 could satisfy it and the
// student got "not met in 200 iterations". $c3 is now drawn last over a range wide enough that a
// legal value always exists for any $c1 + $c2 + $c5.
$c3 = rand(30, 90) where (273 - $c1 - $c2 - $c3 - $c5 >= 8 && 273 - $c1 - $c2 - $c3 - $c5 <= 50)
$c4 = 273 - $c1 - $c2 - $c3 - $c5

$cum3 = ($c1 + $c2 + $c3) / 273
$rel3 = $c3 / 273
$comp = 1 - $cum3

$cum3disp = round($cum3, 4)
$rel3disp = round($rel3, 4)
$compdisp = round($comp, 4)

$questions = array($cum3disp, $rel3disp, $compdisp, "Not enough information to calculate")
$answer = 0
$noshuffle = "last"

$tableHtml = '<table style="border-collapse:collapse; margin:10px 0; font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:15px;">'
$tableHtml = $tableHtml . '<tr style="background:#e8f0fe;"><th style="border:1px solid #c8d4ea; padding:7px 16px; text-align:left;">Category</th><th style="border:1px solid #c8d4ea; padding:7px 16px;">Number of Direct Hits</th></tr>'
$tableHtml = $tableHtml . '<tr><td style="border:1px solid #d9dee8; padding:7px 16px;">1</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $c1 . '</td></tr>'
$tableHtml = $tableHtml . '<tr><td style="border:1px solid #d9dee8; padding:7px 16px;">2</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $c2 . '</td></tr>'
$tableHtml = $tableHtml . '<tr><td style="border:1px solid #d9dee8; padding:7px 16px;">3</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $c3 . '</td></tr>'
$tableHtml = $tableHtml . '<tr><td style="border:1px solid #d9dee8; padding:7px 16px;">4</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $c4 . '</td></tr>'
$tableHtml = $tableHtml . '<tr><td style="border:1px solid #d9dee8; padding:7px 16px;">5</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $c5 . '</td></tr>'
$tableHtml = $tableHtml . '<tr style="background:#f6f8fc;"><td style="border:1px solid #d9dee8; padding:7px 16px;"><b>Total</b></td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;"><b>273</b></td></tr>'
$tableHtml = $tableHtml . '</table>'

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
      <p><span class="term-label">Step 1: Translate "at most a category 3."</span> That means category 1, 2, or 3: every storm up to and including category 3.</p>
      <p><span class="term-label">Step 2: Add those direct hits:</span> ' . $c1 . ' + ' . $c2 . ' + ' . $c3 . ' = ' . ($c1 + $c2 + $c3) . '.</p>
      <p><span class="term-label">Step 3: Divide by the total number of direct hits:</span> ' . ($c1 + $c2 + $c3) . ' &divide; 273 &approx; <b>' . $cum3disp . '</b>.</p>
      <p>Alternatively, subtract the categories above 3 from the whole: 1 &minus; ' . $compdisp . ' = ' . $cum3disp . '.</p>
      <p><b>Answer:</b> ' . $cum3disp . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">The table below contains data on hurricanes that have made direct hits on the U.S. between 1851 and 2004. A hurricane is given a strength category rating based on the minimum wind speed generated by the storm.</p>
    $tableHtml
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">What is the relative frequency of direct hits that were <b>at most</b> a category 3 storm? (That is, the cumulative relative frequency through category 3.)</p>
    $answerbox
  </div>
</div>

// === ANSWER ===

$solutionguide
