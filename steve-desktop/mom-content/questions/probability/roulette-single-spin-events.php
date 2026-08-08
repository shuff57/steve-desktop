// === NAME - DESCRIPTION: Roulette Single Spin - Sample Space, Simple Events, Complements and Independence - count pockets to find three probabilities on one spin, then decide whether odd is the complement of even, name a mutually exclusive pair, and test a colour-or-range pairing for independence ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "number", "number", "number", "choices", "choices", "choices")

// The wheel is fixed at 38 pockets, so the randomization is in WHICH bets are asked.
// The range in parts c and g varies between a dozen (12 pockets, 6 of them even) and a
// half (18 pockets, 9 of them even), which changes both answers rather than just wording.
$colorPick = rand(0, 1)
$colors = array("red", "black")
$color = $colors[$colorPick]
$otherColor = $colors[1 - $colorPick]

$rt = rand(0, 4)
$rangeNames = array("1st 12 (1st Dozen)", "2nd 12 (2nd Dozen)", "3rd 12 (3rd Dozen)", "Low (1 through 18)", "High (19 through 36)")
$rangeSpans = array("1 through 12", "13 through 24", "25 through 36", "1 through 18", "19 through 36")
$rangeName = $rangeNames[$rt]
$rangeSpan = $rangeSpans[$rt]

$rangeCount = 12
$evenInRange = 6
if ($rt >= 3) {
  $rangeCount = 18
  $evenInRange = 9
}

$total = 38
$colorCount = 18
$evenCount = 18
$oddCount = 18

$pColor = round($colorCount / $total, 4)
$pRange = round($rangeCount / $total, 4)
$pEven = round($evenCount / $total, 4)
$pEvenAndRange = round($evenInRange / $total, 4)
$pEvenTimesRange = round(($evenCount / $total) * ($rangeCount / $total), 4)
$oddPlusEven = round(($oddCount + $evenCount) / $total, 4)

$questions[0] = array(
  "&#123;0, 00, 1, 2, 3, &hellip;, 36&#125;, which is 38 outcomes",
  "&#123;1, 2, 3, &hellip;, 36&#125;, which is 36 outcomes",
  "&#123;0, 1, 2, 3, &hellip;, 36&#125;, which is 37 outcomes",
  "&#123;red, black, green&#125;, which is 3 outcomes"
)
$answer[0] = 0

$answer[1] = $pColor
$abstolerance[1] = 0.00011
$answer[2] = $pRange
$abstolerance[2] = 0.00011
$answer[3] = $pEven
$abstolerance[3] = 0.00011

$questions[4] = array(
  "No. The two green pockets are neither odd nor even, so the two probabilities sum to less than 1.",
  "Yes. Every number is either odd or even, so the two events are complements.",
  "No. There are more even pockets than odd pockets, so they cannot be complements.",
  "Yes. The two probabilities are equal, which is what makes them complements."
)
$answer[4] = 0

$questions[5] = array(
  "&#123;" . $color . "&#125; and &#123;green&#125;",
  "&#123;even&#125; and &#123;" . $rangeName . "&#125;",
  "&#123;" . $color . "&#125; and &#123;even&#125;",
  "&#123;" . $color . "&#125; and &#123;" . $rangeName . "&#125;"
)
$answer[5] = 0

$questions[6] = array(
  "No. `P(even and R)` and `P(even)P(R)` are not equal.",
  "Yes. `P(even and R)` equals `P(even)P(R)`.",
  "No. The two events cannot happen on the same spin.",
  "Yes. Each spin is a fresh spin, so every pair of events on a wheel is independent."
)
$answer[6] = 0

$solutionguide = '
<div style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <p><b>Every part here is the same move: count the pockets the event covers, divide by 38.</b> The pockets are equally likely, so an event covering `k` of them has probability `k/38`.</p>

  <p><b>a &mdash; list the sample space.</b> All 38 pockets: the two green ones, 0 and 00, plus the numbers 1 through 36. So `n(S) = 38`. Dropping 0 and 00 (36 outcomes) or keeping only a single zero (37, the European wheel) are the two usual miscounts.</p>

  <p><b>b &mdash; count the ' . $color . ' pockets.</b> Eighteen of the 38 pockets are ' . $color . ':</p>
  <p style="margin-left:1em;"><b>`P(' . $color . ') = 18/38 = 9/19 ~~ ' . $pColor . '`</b></p>

  <p><b>c &mdash; count the ' . $rangeName . ' pockets.</b> That range holds the numbers ' . $rangeSpan . ', which is ' . $rangeCount . ' pockets:</p>
  <p style="margin-left:1em;"><b>`P(R) = ' . $rangeCount . '/38 ~~ ' . $pRange . '`</b></p>

  <p><b>d &mdash; count the even numbers.</b> Among 1 through 36 the even numbers are 2, 4, 6, &hellip;, 36, which is 18 pockets:</p>
  <p style="margin-left:1em;"><b>`P(even) = 18/38 = 9/19 ~~ ' . $pEven . '`</b></p>

  <p><b>e &mdash; check whether odd and even fill the whole sample space.</b> There are 18 odd pockets and 18 even pockets, but 0 and 00 are <i>neither</i>, so</p>
  <p style="margin-left:1em;"><b>`P(odd) + P(even) = 18/38 + 18/38 = 36/38 ~~ ' . $oddPlusEven . ' != 1`</b></p>
  <p style="margin-left:1em;">Complements must add to exactly 1, so odd is <b>not</b> the complement of even. The complement of "even" is "odd or green".</p>

  <p><b>f &mdash; a mutually exclusive pair cannot co-occur.</b> ' . $color . ' and green work: a pocket has exactly one colour, so `P(' . $color . ' and green) = 0`. The other three pairings all overlap &mdash; there are even ' . $color . ' pockets, ' . $color . ' pockets inside ' . $rangeName . ', and even pockets inside ' . $rangeName . '.</p>

  <p><b>g &mdash; run the independence test.</b> The even numbers inside ' . $rangeName . ' number ' . $evenInRange . ', so</p>
  <p style="margin-left:1em;">`P(even and R) = ' . $evenInRange . '/38 ~~ ' . $pEvenAndRange . '`</p>
  <p style="margin-left:1em;">If the events were independent this would equal the product of the separate probabilities:</p>
  <p style="margin-left:1em;">`P(even)P(R) = (18/38)(' . $rangeCount . '/38) ~~ ' . $pEvenTimesRange . '`</p>
  <p style="margin-left:1em;">These are not equal, so the two events are <b>not independent</b>. The two green pockets are the culprit: they sit outside both events and tilt the totals just enough to break the product rule.</p>

  <p><b>Answer:</b> a. 38 outcomes; b. ' . $pColor . '; c. ' . $pRange . '; d. ' . $pEven . '; e. no &mdash; 0 and 00 are neither odd nor even; f. ' . $color . ' and green; g. no &mdash; ' . $pEvenAndRange . ' is not ' . $pEvenTimesRange . '.</p>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0;">The casino game roulette allows a gambler to bet on a ball landing on a particular colour, number, or range of numbers. The wheel has <b>38</b> pockets: 0 and 00, which are <b>green</b>, together with 1 through 36, of which eighteen are <b>red</b> and eighteen are <b>black</b>. The numbered pockets are also grouped into three ranges of twelve &mdash; 1st 12 (1 through 12), 2nd 12 (13 through 24) and 3rd 12 (25 through 36) &mdash; and into Low (1 through 18) and High (19 through 36).</p>
    <p style="margin:12px 0 0 0;">Let `R` be the event that the ball lands in <b>$rangeName</b>. Answer the following about a <b>single spin</b> of the wheel.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">a.</span> Which of these is the <b>sample space</b> for one spin? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">b.</span> You bet on <b>$color</b>. Find `P($color)`. Round to <b>four decimal places</b>. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">c.</span> You bet on <b>$rangeName</b>. Find `P(R)`. Round to <b>four decimal places</b>. $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">d.</span> You bet on an <b>even number</b>. Find `P(even)`. Round to <b>four decimal places</b>. $answerbox[3]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">e.</span> Is getting an <b>odd</b> number the <b>complement</b> of getting an <b>even</b> number? $answerbox[4]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">f.</span> Which pair of events is <b>mutually exclusive</b>? $answerbox[5]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">g.</span> Are the events <b>even</b> and <b>$rangeName</b> independent? $answerbox[6]
  </div>
</div>

// === ANSWER ===

$solutionguide
