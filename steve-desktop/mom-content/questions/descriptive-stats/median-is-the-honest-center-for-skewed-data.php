// === NAME - DESCRIPTION: Why the Median Is the Honest Center for Skewed Data - Compute the mean and median of a right-skewed data set, count how many values actually fall below the mean, and decide which number a report should quote ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// "Use the median for skewed data" is usually taught as a rule to remember. Part (c) turns it into
// something the student measures: they count how many of the nine values sit BELOW the mean, and find
// it is most of them. A center that the majority of the data falls below is not a typical value, and
// that fact is far more convincing than the instruction.
//
// The data is eight tightly clustered values plus one far larger, so the mean is always dragged above
// the eighth value and the count below the mean is always 8 of 9: guaranteed by construction, not by
// the luck of the draw. n = 9 keeps the median a single value.
$anstypes = array("number", "number", "number", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "A small company lists the annual salary, in thousands of dollars, of each of its nine staff."
  $unitWord = "thousand dollars"
  $bigWho = "the owner"
  $thing = "salaries"
}
else {
  $intro = "A street of nine houses lists the sale price, in thousands of dollars, of each home sold last year."
  $unitWord = "thousand dollars"
  $bigWho = "one large corner property"
  $thing = "sale prices"
}

// Eight clustered values, ascending and distinct.
$e0 = rand(31, 36)
$e1 = $e0 + rand(1, 3)
$e2 = $e1 + rand(1, 3)
$e3 = $e2 + rand(1, 3)
$e4 = $e3 + rand(1, 3)
$e5 = $e4 + rand(1, 3)
$e6 = $e5 + rand(1, 3)
$e7 = $e6 + rand(1, 3)

$clusterSum = $e0 + $e1 + $e2 + $e3 + $e4 + $e5 + $e6 + $e7

// The ninth value is far above the cluster. Its size is chosen so the total is divisible by 9, so the
// mean is exact, and so the mean always clears $e7: which is what makes the count in part (c) fixed.
$big = $e7 + 9 * rand(20, 30)
$total = $clusterSum + $big
$rem = $total % 9
if ($rem > 0) {
  $big = $big + 9 - $rem
  $total = $clusterSum + $big
}
$mean = $total / 9
$median = $e4
$gap = $mean - $median

$answer[0] = $mean
$abstolerance[0] = 0.005
$answer[1] = $median
$answer[2] = 8
$answerboxsize = 6

$questions[3] = array(
  "The median. Eight of the nine values sit below the mean, so the mean describes almost nobody; the median has four on each side by definition.",
  "The mean. It uses every value, so it is always the more complete summary.",
  "The mean, because it is the larger of the two and reporting the larger number is more informative.",
  "Neither. When a data set is skewed, no single number may be reported as its center."
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
  .srt { font-family:ui-monospace,Menlo,Consolas,monospace; background:#f8fafc; border:1px solid #e5e7eb; border-radius:6px; padding:8px 10px; display:block; margin:6px 0; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><span class="term-label">Step 1: the mean.</span> The nine values total ' . $total . ', so `bar x = ' . $total . ' -: 9 = ` <b>' . $mean . '</b> ' . $unitWord . '.</p>
      <p><span class="term-label">Step 2: the median.</span> The values are already in order, so with nine of them the median is the 5th: <b>' . $median . '</b> ' . $unitWord . '.</p>
      <span class="srt">' . $e0 . ', ' . $e1 . ', ' . $e2 . ', ' . $e3 . ', <b>' . $median . '</b>, ' . $e5 . ', ' . $e6 . ', ' . $e7 . ', ' . $big . '</span>
      <p>The mean sits ' . $gap . ' ' . $unitWord . ' above the median. That gap is the single large value doing its work.</p>
      <p><span class="term-label">Step 3: the part that settles the argument.</span> Count how many of the nine values fall BELOW the mean of ' . $mean . ': every one of the first eight does. Only ' . $bigWho . ' is above it. So a report quoting ' . $mean . ' as the typical figure would be quoting a number that <b>8 of the 9</b> fall short of.</p>
      <p><span class="term-label">Why the median does not have this problem.</span> The median is defined by POSITION: four values below, four above, always. It cannot be pulled away from the middle by one extreme value, because moving that value further out does not change which value is in the middle.</p>
      <p><span class="term-label">The habit worth keeping.</span> Whenever a mean and a median disagree by a lot, ask how many observations actually sit near the mean. For ' . $thing . ', house prices, and anything else with a long high tail, the answer is usually "not many": which is why the median is the figure normally published for them.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$intro The nine values, already in order, are:</p>
    <p style="margin:12px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; font-family:ui-monospace,Menlo,Consolas,monospace; font-size:15px; line-height:1.8;">$e0, $e1, $e2, $e3, $e4, $e5, $e6, $e7, $big</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the <b>mean</b>. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find the <b>median</b>. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> How many of the nine values are <b>below the mean</b> you found in part (a)? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> A report needs one number for the typical figure. Which should it quote, and why? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
