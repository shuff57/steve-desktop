// === NAME - DESCRIPTION: Draw a Line Graph from a Frequency Table - Plot the five points of a randomized frequency table on a grid and join them, using the drawing tool, so the student builds the display instead of reading a finished one ===
// === SET QUESTION TYPE TO: draw ===

// === COMMON CONTROL ===

// The rest of the bank's line-graph questions hand the student a finished graph to read. This one
// makes them place the points. `polygon` joins the plotted points with edges in the order given and
// does NOT close the shape, which is exactly a line graph; `closedpolygon` would join the last point
// back to the first and draw a filled region.
$ci = rand(0, 1)
$contexts = array(
  "A coach asked each player how many training sessions they attended last week.",
  "A librarian asked each visitor how many books they borrowed on one afternoon."
)
$xNames = array("Training sessions attended", "Books borrowed")
$intro = $contexts[$ci]
$xName = $xNames[$ci]

// Frequencies are kept between 2 and 12 so every point sits inside the grid with room above the
// tallest one, and no category is empty -- a zero would put a point on the axis where the student
// cannot tell a plotted dot from the axis itself.
$f1 = rand(2, 12)
$f2 = rand(2, 12)
$f3 = rand(2, 12)
$f4 = rand(2, 12)
$f5 = rand(2, 12)

$total = $f1 + $f2 + $f3 + $f4 + $f5

$answers = array("1,$f1", "2,$f2", "3,$f3", "4,$f4", "5,$f5")
$answerformat = "polygon"
$snaptogrid = 1
$grid = "0,6,0,14,1,2,430,330"
$readerlabel = "Grid for plotting the line graph, horizontal axis 0 to 6, vertical axis 0 to 14"

$tableRows = ""
$fs = array($f1, $f2, $f3, $f4, $f5)
for ($k=0..4) {
  $x = $k + 1
  $tableRows = $tableRows . '<tr><td style="border:1px solid #d1d5db; padding:6px 18px; text-align:center;">' . $x . '</td><td style="border:1px solid #d1d5db; padding:6px 18px; text-align:center;">' . $fs[$k] . '</td></tr>'
}

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
      <p><span class="term-label">Step 1 &mdash; one point per row of the table.</span> The value goes across, the frequency goes up. So the five points are (1,&nbsp;' . $f1 . '), (2,&nbsp;' . $f2 . '), (3,&nbsp;' . $f3 . '), (4,&nbsp;' . $f4 . ') and (5,&nbsp;' . $f5 . ').</p>
      <p><span class="term-label">Step 2 &mdash; plot, then join in order.</span> Work left to right and join each point to the next. The height of a point is the frequency; the segment between two points is only there to lead the eye from one value to the next. Nothing on the line between them is real data &mdash; there is no such thing as ' . '2.5 of these.</p>
      <p><span class="term-label">Step 3 &mdash; check the total.</span> The frequencies add to ' . $total . ', which is the number of people surveyed. If the heights you plotted do not add to that, one point is at the wrong height.</p>
      <p><span class="term-label">The usual slip.</span> Plotting a running total instead of the frequency, which makes the line climb and never fall. Each point is that row\'s own count, not the count so far.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 12px 0;">$intro The results are in the table below.</p>
    <table style="border-collapse:collapse; margin:4px 0 12px 0; background:#fff;">
      <tr style="background:#f0f4ff;">
        <th style="border:1px solid #d1d5db; padding:6px 18px;">$xName</th>
        <th style="border:1px solid #d1d5db; padding:6px 18px;">Frequency</th>
      </tr>
      $tableRows
    </table>
    <p style="margin:0;"><b>Draw the line graph for this table.</b> Put the $xName along the horizontal axis and the frequency up the vertical axis, plot one point for each row, and join the points in order from left to right.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    $answerbox
  </div>
</div>

// === ANSWER ===

$solutionguide
