// === NAME - DESCRIPTION: Full Workup of a Survey with Two Conditionals - sort four survey figures into two unconditional and two conditional probabilities, interpret each conditional in words, then apply the multiplication and addition rules and test for mutual exclusivity ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number", "number", "choices", "choices", "number", "choices", "number", "choices")

$ci = rand(0, 3)

// The source exercise attaches its figures to a real, named 2015 survey. Randomizing those
// numbers would invent statistics about a real event, so the context is generic here while
// the probability structure — two unconditional rates and two conditionals, one of them
// conditioned on an age band — is kept exactly as the original.
$measures = array(
  "a proposed transit funding measure",
  "a proposed park and open-space bond",
  "a proposed school facilities levy",
  "a proposed municipal broadband measure"
)
$rulings = array(
  "the upcoming council vote on the measure",
  "the upcoming county decision on the bond",
  "the upcoming board decision on the levy",
  "the upcoming council vote on the measure"
)
$measure = $measures[$ci]
$ruling = $rulings[$ci]

$ageLow = 18
$ageHigh = 39

// loPct keeps P(C AND B) <= P(B); hiPct keeps P(C OR B) <= 1.
$pcPct = rand(45, 72)
$bcondPct = rand(55, 85)
$andPct = $pcPct * $bcondPct / 100
$loPct = ceil($andPct) + 4
$hiPct = floor(98 - $pcPct + $andPct)
$pbPct = rand($loPct, $hiPct)

// Support among the younger band runs above the overall rate, as in the original.
$caPct = rand($pcPct + 8, 92)

$pc = $pcPct / 100
$pb = $pbPct / 100
$bcond = $bcondPct / 100
$ca = $caPct / 100

$pand = round($pc * $bcond, 4)
$por = round($pc + $pb - $pand, 4)

$answer[0] = $pc
$abstolerance[0] = 0.0011
$answer[1] = $pb
$abstolerance[1] = 0.0011
$answer[2] = $ca
$abstolerance[2] = 0.0011
$answer[3] = $bcond
$abstolerance[3] = 0.0011

$questions[4] = array(
  "the event that a registered voter supports " . $measure . ", given that the voter is " . $ageLow . " to " . $ageHigh . " years old",
  "the event that a registered voter is " . $ageLow . " to " . $ageHigh . " years old, given that the voter supports " . $measure,
  "the event that a registered voter both supports " . $measure . " and is " . $ageLow . " to " . $ageHigh . " years old",
  "the event that a registered voter supports " . $measure . ", or is " . $ageLow . " to " . $ageHigh . " years old, or both"
)
$answer[4] = 0

$questions[5] = array(
  "the event that a voter says " . $ruling . " is important to them, given that the voter supports " . $measure,
  "the event that a voter supports " . $measure . ", given that the voter says " . $ruling . " is important to them",
  "the event that a voter both supports " . $measure . " and says " . $ruling . " is important to them",
  "the event that a voter says " . $ruling . " is important to them, given that the voter is " . $ageLow . " to " . $ageHigh . " years old"
)
$answer[5] = 0

$answer[6] = $pand
$abstolerance[6] = 0.00011

$questions[7] = array(
  "the event that a voter both supports " . $measure . " and says " . $ruling . " is important to them",
  "the event that a voter says " . $ruling . " is important to them, given that the voter supports " . $measure,
  "the event that a voter supports " . $measure . ", or says " . $ruling . " is important to them, or both",
  "the event that a voter supports " . $measure . " but does not say " . $ruling . " is important to them"
)
$answer[7] = 0

$answer[8] = $por
$abstolerance[8] = 0.00011

$questions[9] = array(
  "No. They are not mutually exclusive, because `P(C and B)` is not zero.",
  "Yes. They are mutually exclusive, because `P(C and B)` equals zero.",
  "No. They are not mutually exclusive, because `P(B|C)` and `P(B)` are not equal.",
  "Yes. They are mutually exclusive, because a voter cannot both support the measure and call the decision important."
)
$answer[9] = 0

$naiveSum = round($pc + $pb, 4)

$solutionguide = '
<div style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <p><b>Set up your symbol list before computing anything.</b> The survey gives four numbers, and two of them are conditionals. Half the errors in this section come from computing `P(A|B)` when the problem handed you `P(B|A)`.</p>
  <ul>
    <li>' . $pcPct . '% of <i>all</i> registered voters support the measure &rarr; `P(C) = ' . $pc . '`</li>
    <li>' . $pbPct . '% say the decision is important &rarr; `P(B) = ' . $pb . '`</li>
    <li>among ' . $ageLow . '-to-' . $ageHigh . ' year olds, support is ' . $caPct . '% &rarr; `P(C|A) = ' . $ca . '`</li>
    <li>of those who support the measure, ' . $bcondPct . '% say it is important &rarr; `P(B|C) = ' . $bcond . '`</li>
  </ul>
  <p><b>a.</b> `P(C) = ' . $pc . '`</p>
  <p><b>b.</b> `P(B) = ' . $pb . '`</p>
  <p><b>c.</b> `P(C|A) = ' . $ca . '` &mdash; the ' . $caPct . '% figure is already restricted to the ' . $ageLow . '-to-' . $ageHigh . ' group, so it is a conditional, not a plain probability.</p>
  <p><b>d.</b> `P(B|C) = ' . $bcond . '` &mdash; "of those voters who support the measure" is the phrase that puts `C` on the right of the bar.</p>
  <p><b>e.</b> `C|A` in words: the event that a registered voter supports ' . $measure . ', <b>given</b> that the voter is ' . $ageLow . ' to ' . $ageHigh . ' years old.</p>
  <p><b>f.</b> `B|C` in words: the event that a voter says ' . $ruling . ' is important to them, <b>given</b> that the voter supports ' . $measure . '.</p>
  <p><b>g &mdash; multiplication rule, with the conditional from part d.</b></p>
  <p style="margin-left:1em;"><b>`P(C and B) = P(C)P(B|C) = (' . $pc . ')(' . $bcond . ') = ' . $pand . '`</b></p>
  <p style="margin-left:1em;">Pair the conditional with the rate it is conditioned on. `P(B|C)` lives inside the group who support the measure, so it multiplies `P(C)` &mdash; not `P(B)`.</p>
  <p><b>h.</b> `C and B` in words: the event that a voter <i>both</i> supports ' . $measure . ' and says ' . $ruling . ' is important to them.</p>
  <p><b>i &mdash; addition rule, subtracting the overlap from part g.</b></p>
  <p style="margin-left:1em;"><b>`P(C or B) = P(C) + P(B) - P(C and B) = ' . $pc . ' + ' . $pb . ' - ' . $pand . ' = ' . $por . '`</b></p>
  <p style="margin-left:1em;">Adding the two rates alone gives ' . $naiveSum . ', which double-counts every voter who does both.</p>
  <p><b>j &mdash; check whether the AND is zero.</b> `P(C and B) = ' . $pand . '`, which is not 0, so `C` and `B` are <b>not mutually exclusive</b>. A voter can easily do both &mdash; support the measure and consider the decision important.</p>
  <p><b>Answer:</b> a. ' . $pc . '; b. ' . $pb . '; c. ' . $ca . '; d. ' . $bcond . '; e. supports the measure given the voter is ' . $ageLow . '&ndash;' . $ageHigh . '; f. says the decision is important given the voter supports the measure; g. ' . $pand . '; h. supports the measure and says the decision is important; i. ' . $por . '; j. no, since `P(C and B) = ' . $pand . '` is not 0.</p>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0;">A survey of registered voters reported that <b>$pcPct%</b> supported $measure. Among $ageLow to $ageHigh year old registered voters, support was <b>$caPct%</b>. <b>$pbPct%</b> of registered voters said that $ruling was either very or somewhat important to them. Out of those registered voters who support the measure, <b>$bcondPct%</b> say the decision is important to them.</p>
    <p style="margin:12px 0 0 0;">Let `C` be the event that a registered voter supports $measure, `B` the event that a voter says $ruling is very or somewhat important to them, and `A` the event that a voter is $ageLow to $ageHigh years old.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">a.</span> Find `P(C)`. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">b.</span> Find `P(B)`. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">c.</span> Find `P(C|A)`. $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">d.</span> Find `P(B|C)`. $answerbox[3]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">e.</span> In words, what is `C|A`? $answerbox[4]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">f.</span> In words, what is `B|C`? $answerbox[5]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">g.</span> Find `P(C and B)`. Round to <b>four decimal places</b>. $answerbox[6]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">h.</span> In words, what is `C and B`? $answerbox[7]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">i.</span> Find `P(C or B)`. Round to <b>four decimal places</b>. $answerbox[8]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">j.</span> Are `C` and `B` mutually exclusive events? $answerbox[9]
  </div>
</div>

// === ANSWER ===

$solutionguide
