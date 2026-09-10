// === NAME - DESCRIPTION: Find the Class Width - Compute a data set's range and the whole-number class width needed to cover it in a stated number of classes, then say why the width is rounded up ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "A podcast producer recorded the running time of each episode in a season."
  $unitWord = "minutes"
}
else {
  $intro = "A librarian recorded the number of pages in each book on a classroom shelf."
  $unitWord = "pages"
}

// Pick the CORRECT class width first, then build a range that forces genuine rounding up: with
// r strictly between 0 and k, range/k lands strictly between width-1 and width, so ceil(range/k)
// is always width and never width-1. A range that divides evenly would let a student round either
// way and still land on the right answer by accident.
$k = rand(5, 8)
$width = rand(4, 14)
$r = rand(1, $k - 1)
$range = $k * $width - $r

$dmin = rand(3, 25)
$dmax = $dmin + $range

$rawWidth = round($range / $k, 2)

$answer[0] = $range
$answerformat[0] = "integer"

$answer[1] = $width
$answerformat[1] = "integer"

$questions[2] = array(
  "Rounding down could leave the largest value with no class to fall into. Rounding up guarantees every value, including the maximum, lands inside some class.",
  "Rounding up makes the class width a nicer-looking number than a decimal would be.",
  "Rounding down would create more classes than were asked for, so rounding up is the only way to hit the target count exactly.",
  "It does not matter which way the width is rounded, since either one produces a valid grouping."
)
$answer[2] = 0

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
      <p><span class="term-label">Part (a): the range.</span> Subtract the smallest value from the largest: ' . $dmax . ' &minus; ' . $dmin . ' = <b>' . $range . '</b> ' . $unitWord . '.</p>
      <p><span class="term-label">Part (b): the class width.</span> Divide the range by the number of classes you want: ' . $range . ' &divide; ' . $k . ' &approx; ' . $rawWidth . '. A width of ' . $rawWidth . ' ' . $unitWord . ' is not a whole number, so round UP to the next whole number: <b>' . $width . '</b>. Rounding down to ' . ($width - 1) . ' would make the ' . $k . ' classes together too narrow to reach ' . $dmax . '.</p>
      <p><span class="term-label">Part (c): why round up.</span> A class width that is too small leaves the largest values with nowhere to go. Rounding up costs nothing: the classes may run a little past the data at the top end, and that is always safe. Rounding down is never safe, because it can leave the maximum outside every class.</p>
      <p><b>Answer:</b> (a) ' . $range . ' &nbsp;&nbsp; (b) ' . $width . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$intro The smallest value recorded was <b>$dmin</b> $unitWord and the largest was <b>$dmax</b> $unitWord. You want to group the data into about <b>$k</b> classes.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">a.</span> What is the <b>range</b> of the data? Enter a whole number. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">b.</span> What <b>class width</b> should you use to fit the data into $k classes? Divide the range by $k, then round UP to the next whole number. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">c.</span> Why is the class width always rounded UP rather than down or to the nearest whole number? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
