// === NAME - DESCRIPTION: Percentile Is Not a Percent Score - Given a student's percent score and percentile on one test, tell the two numbers apart, count how many students scored at or below, and see why a percentile alone can't reveal another student's score ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "number", "choices")

$testArr = array(
  "statistics final exam",
  "chemistry midterm",
  "biology unit test",
  "history final exam",
  "algebra placement test"
)
$ti = rand(0, 4)
$testDesc = $testArr[$ti]

$nameArr = array("Maria", "Andre", "Priya", "Liam", "Sofia", "Malik")
$ni = rand(0, 5)
$name = $nameArr[$ni]
$oi = rand(0, 5)
if ($oi == $ni) {
  $oi = ($oi + 1) % 6
}
$otherName = $nameArr[$oi]

// Class size, a multiple of 20, paired with a percentile that is a multiple of 5 -- together this
// guarantees percentile/100 * n lands on a whole number of students in part (b), for every draw.
$n = 20 * rand(2, 6)

// Randomize which way the score and percentile pull, so a student can't just learn "high score,
// low percentile" as the pattern and coast -- the misconception has to be caught both ways round.
$dir = rand(0, 1)
if ($dir == 0) {
  $pctScore = rand(85, 98)
  $p = 5 * rand(2, 7)
}
else {
  $pctScore = rand(40, 60)
  $p = 5 * rand(14, 18)
}

// p is always a multiple of 5, so its last digit is always 0 or 5 -- the ordinal suffix is always
// "th" (5th, 15th, ... 90th), so no st/nd/rd branch is needed here.
$pLabel = $p . "th"

// Guaranteed a whole number: p is a multiple of 5 and n a multiple of 20, so p*n is always a
// multiple of 100 and this division has no remainder.
$count = $p * $n / 100

$otherP = 5 * rand(2, 18)
$otherPLabel = $otherP . "th"

$questions[0] = array(
  "About " . $p . "% of the class scored at or below " . $name . " on the " . $testDesc . ".",
  $name . " answered about " . $p . "% of the questions on the " . $testDesc . " correctly.",
  "About " . $p . "% of the class scored at or above " . $name . " on the " . $testDesc . ".",
  "The score " . $name . " earned was " . $p . " percentage points above the class average."
)
$answer[0] = 0

$answer[1] = $count
$answerformat[1] = "integer"

$questions[2] = array(
  "No -- a percentile only fixes where " . $otherName . " ranks in the class. Depending on how everyone else did, almost any percent score could sit at that same percentile.",
  "Yes -- the percentile IS the percent score, so " . $otherName . " scored about " . $otherP . "% on the test.",
  "Yes -- subtract the percentile from 100 to get the percent score of " . $otherName . ".",
  "No -- nothing at all can be known about how " . $otherName . " performed from a percentile."
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
      <p><span class="term-label">Two different numbers.</span> A percent score is how much of the test ' . $name . ' got right. A percentile is where ' . $name . ' stands compared to everyone else who took the ' . $testDesc . '. Nothing forces those two numbers to be close, or even to move the same direction, because they answer different questions.</p>
      <p><span class="term-label">Part (a).</span> The ' . $pLabel . ' percentile means about ' . $p . '% of the class scored at or below ' . $name . '. It says nothing about how many questions ' . $name . ' got right -- that is the score, a separate number reported above. Reading the percentile as "' . $name . ' got ' . $p . '% of the questions right" mixes up the two.</p>
      <p><span class="term-label">Part (b).</span> With ' . $n . ' students in the class, about ' . $p . '% of them scored at or below ' . $name . ': ' . $p . '/100 &times; ' . $n . ' = <b>' . $count . '</b> students.</p>
      <p><span class="term-label">Part (c).</span> Knowing ' . $otherName . ' is at the ' . $otherPLabel . ' percentile only fixes a position in the class, not a score. If the ' . $testDesc . ' was easy, the whole class scored high and even the ' . $otherPLabel . ' percentile could be a strong percent score. If it was hard, the whole class scored low and that same ' . $otherPLabel . ' percentile could be a weak one. The percentile alone cannot tell them apart.</p>
      <p><b>Answer:</b> (a) about ' . $p . '% at or below &nbsp;&nbsp; (b) ' . $count . ' students &nbsp;&nbsp; (c) no, a percentile alone does not determine a percent score</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">On the $testDesc, taken by $n students, $name scored $pctScore% correct and sits at the <b>$pLabel percentile</b>.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What does that percentile mean for $name? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Based on that percentile, roughly how many of the $n students in the class scored at or below $name? Give a whole number of students. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> A different student in the same class, $otherName, is at the $otherPLabel percentile on the same test. Can you work out the percent score of $otherName from that percentile alone? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
