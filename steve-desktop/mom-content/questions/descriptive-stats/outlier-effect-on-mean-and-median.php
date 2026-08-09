// === NAME - DESCRIPTION: What One Extreme Value Does to the Mean and the Median - Compute both measures of center, replace one value with a far larger one, recompute both, and see which measure moved and which did not ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// "The median is resistant" is a sentence students can recite without believing. Here they compute
// the same two measures twice, before and after one value is replaced, and read the difference off
// their own four answers. The median is UNCHANGED by construction: the replaced value is the largest
// one and it is only made larger, so its position in the sorted order never moves, and with an odd
// count the middle value cannot shift.
//
// n = 7 so the median is a single value, not an average of two -- with an even count the median can
// creep even when the ordering holds, which would blunt the whole point.
$anstypes = array("number", "number", "number", "number", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "A small firm lists the annual salaries, in thousands of dollars, of its seven employees."
  $unitWord = "thousand dollars"
  $bigWord = "the founder's salary is added to the list in place of the highest one"
  $thing = "salaries"
}
else {
  $intro = "A rental agency lists the nightly price, in dollars, of its seven listings."
  $unitWord = "dollars"
  $bigWord = "the most expensive listing is re-priced"
  $thing = "prices"
}

// Seven ascending values, all distinct.
$a0 = rand(30, 38)
$a1 = $a0 + rand(1, 4)
$a2 = $a1 + rand(1, 4)
$a3 = $a2 + rand(1, 4)
$a4 = $a3 + rand(1, 4)
$a5 = $a4 + rand(1, 4)
$a6 = $a5 + rand(1, 4)

$n = 7
$sum1 = $a0 + $a1 + $a2 + $a3 + $a4 + $a5 + $a6
// Force the first mean onto two clean decimals by nudging the SMALLEST value, which cannot disturb
// the ordering or the median at position 4.
$rem = $sum1 % $n
if ($rem > 0) {
  $a0 = $a0 - $rem
  $sum1 = $sum1 - $rem
}
$mean1 = $sum1 / $n
$med1 = $a3

// The replacement is far above the old maximum, so it stays in last place and the median holds.
$big = $a6 + $n * rand(20, 40)
$sum2 = $sum1 - $a6 + $big
$mean2 = $sum2 / $n
$med2 = $a3
$meanJump = $mean2 - $mean1

$answer[0] = $mean1
$abstolerance[0] = 0.005
$answer[1] = $med1
$answer[2] = $mean2
$abstolerance[2] = 0.005
$answer[3] = $med2
$answerboxsize = 7

$questions[4] = array(
  "The mean uses every value, so one extreme number drags it. The median only depends on which value sits in the middle position, and that position did not change.",
  "The median did not change because the new value was not large enough to matter.",
  "Both measures should have changed by the same amount; a difference means an arithmetic error.",
  "The median never changes when any single value in a data set is changed."
)
$answer[4] = 0

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
  .srt { font-family:ui-monospace,Menlo,Consolas,monospace; background:#f8fafc; border:1px solid #e5e7eb; border-radius:6px; padding:8px 10px; display:block; margin:6px 0; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><span class="term-label">Before.</span> The seven values, in order:</p>
      <span class="srt">' . $a0 . ', ' . $a1 . ', ' . $a2 . ', <b>' . $a3 . '</b>, ' . $a4 . ', ' . $a5 . ', ' . $a6 . '</span>
      <p>They total ' . $sum1 . ', so the mean is `' . $sum1 . ' -: 7 = ` <b>' . $mean1 . '</b>. With seven values the median is the 4th, <b>' . $med1 . '</b>.</p>
      <p><span class="term-label">After.</span> The largest value becomes ' . $big . ':</p>
      <span class="srt">' . $a0 . ', ' . $a1 . ', ' . $a2 . ', <b>' . $a3 . '</b>, ' . $a4 . ', ' . $a5 . ', ' . $big . '</span>
      <p>The new total is ' . $sum2 . ', so the mean is `' . $sum2 . ' -: 7 = ` <b>' . $mean2 . '</b> &mdash; up by ' . $meanJump . '. The median is still the 4th value, <b>' . $med2 . '</b>: unchanged.</p>
      <p><span class="term-label">Why they behave so differently.</span> The mean adds every value, so a number far from the rest carries its full distance into the total. The median only asks WHICH value is in the middle. Making the largest value larger still leaves it largest, so the ordering &mdash; and therefore the middle &mdash; is untouched.</p>
      <p><span class="term-label">What that means when you report a number.</span> For ' . $thing . ' with a few extreme values, the mean describes no one: it sits above almost everybody. The median is the value with half the data on each side, which is why it is the one usually quoted for skewed data like ' . $thing . '.</p>
      <p><span class="term-label">A caution.</span> The median is resistant, not immovable. Change a value so much that it crosses the middle position &mdash; or change several &mdash; and the median moves too.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$intro The seven values, already in order, are:</p>
    <p style="margin:12px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; font-family:ui-monospace,Menlo,Consolas,monospace; font-size:15px; line-height:1.8;">$a0, $a1, $a2, $a3, $a4, $a5, $a6</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the <b>mean</b>. Round to two decimal places if needed. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find the <b>median</b>. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; border-left:4px solid #b91c1c;">
    <p style="margin:0 0 12px 0;">Now the largest value, <b>$a6</b>, is replaced by <b>$big $unitWord</b>. Every other value stays exactly the same.</p>
    <p style="margin:0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; font-family:ui-monospace,Menlo,Consolas,monospace; font-size:15px; line-height:1.8;">$a0, $a1, $a2, $a3, $a4, $a5, $big</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find the <b>new mean</b>. Round to two decimal places if needed. $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Find the <b>new median</b>. $answerbox[3]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">e.</span> One of the two measures moved a long way and the other did not move at all. Why? $answerbox[4]
  </div>
</div>

// === ANSWER ===

$solutionguide
