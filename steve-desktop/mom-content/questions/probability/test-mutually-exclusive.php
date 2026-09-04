// === NAME - DESCRIPTION: Test Two Events for Mutual Exclusivity - compute the joint probability, compare it against zero, and distinguish the mutually-exclusive test from the independence test ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$ci = rand(0, 3)

$places = array("A local restaurant", "A movie theater concession stand", "A campus coffee bar", "A hardware store")
$itemAs = array("a pizza", "a bucket of popcorn", "an espresso drink", "a can of paint")
$itemBs = array("a salad", "a soda", "a pastry", "a paint brush")
$ordersAs = array("order pizzas", "buy popcorn", "order an espresso drink", "buy a can of paint")
$alsoBs = array("also order a salad", "also buy a soda", "also order a pastry", "also buy a paint brush")
$verbs = array("order", "buy", "order", "buy")
$pasts = array("ordered", "bought", "ordered", "bought")
$evAs = array("Z", "K", "E", "C")
$evBs = array("S", "D", "P", "B")

$place = $places[$ci]
$itemA = $itemAs[$ci]
$itemB = $itemBs[$ci]
$ordersA = $ordersAs[$ci]
$alsoB = $alsoBs[$ci]
$verb = $verbs[$ci]
$past = $pasts[$ci]
$evA = $evAs[$ci]
$evB = $evBs[$ci]

// Bounds keep the scenario CONSISTENT. OpenStax's own numbers for this exercise
// (0.87 / 0.32 / 0.55) force P(A AND B) > P(B), which is impossible.
//   loPct keeps P(A AND B) <= P(B);  hiPct keeps P(A OR B) <= 1;
//   the where clause keeps P(B|A) away from P(B), so "not independent" is always true.
$paPct = rand(35, 80)
$pcondPct = rand(25, 70)
$andPct = $paPct * $pcondPct / 100
$loPct = ceil($andPct) + 4
$hiPct = floor(98 - $paPct + $andPct)
$pbPct = rand($loPct, $hiPct) where (abs($pbPct - $pcondPct) >= 5)

$pa = $paPct / 100
$pb = $pbPct / 100
$pcond = $pcondPct / 100
$pand = round($pa * $pcond, 4)
$por = round($pa + $pb - $pand, 4)
$prod = round($pa * $pb, 4)

$anstypes = array("number", "choices")

$answer[0] = $pand
$abstolerance[0] = 0.00011

$pctBoth = round($pand * 100, 2)

$questions[1] = array(
  "No. They are not mutually exclusive, because `P(" . $evA . " and " . $evB . ")` is not zero.",
  "Yes. They are mutually exclusive, because `P(" . $evA . " and " . $evB . ")` equals zero.",
  "No. They are not mutually exclusive, because `P(" . $evB . "|" . $evA . ")` and `P(" . $evB . ")` are not equal.",
  "Yes. They are mutually exclusive, because a customer cannot " . $verb . " " . $itemA . " and " . $itemB . " on the same visit."
)
$answer[1] = 0

$solutionguide = '
<div style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <p><b>Mutually exclusive means the AND is impossible. So check whether it is zero.</b></p>
  <p><b>a.</b> `P(' . $evA . ' and ' . $evB . ') = P(' . $evA . ')P(' . $evB . '|' . $evA . ') = (' . $pa . ')(' . $pcond . ') = ' . $pand . '`</p>
  <p><b>b.</b> ' . $pand . ' is not 0: about ' . $pctBoth . '% of all customers do both: so the two events plainly co-occur. They are <b>not mutually exclusive</b>.</p>
  <p><b>This is a different question from the independence test.</b> Independence asks whether one event changes the <i>odds</i> of the other. Mutual exclusivity asks whether they can happen <i>together at all</i>. Here the answer is "no" to both, for different reasons: and option 3 above is the independence reason attached to the mutually-exclusive question, which is the confusion worth catching.</p>
  <p><b>Answer:</b> no: not mutually exclusive, because `P(' . $evA . ' and ' . $evB . ') = ' . $pand . '`, which is not 0.</p>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0;">$place knows that the probability that a customer will $verb $itemA is <b>$paPct%</b>. It also knows that the probability that a customer will $verb $itemB is <b>$pbPct%</b>. Of the customers who $ordersA, <b>$pcondPct%</b> of them $alsoB.</p>
    <p style="margin:12px 0 0 0;">Let `$evA` be the event that a customer will $verb $itemA, and `$evB` the event that a customer will $verb $itemB. Suppose one customer is selected at random.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">a.</span> Find `P($evA and $evB)`. Round to <b>four decimal places</b>. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">b.</span> Are `$evA` and `$evB` mutually exclusive events? $answerbox[1]
  </div>
</div>

// === ANSWER ===

$solutionguide
