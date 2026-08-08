// === NAME - DESCRIPTION: Test Two Events for Independence - compute the product of the two unconditional probabilities, compare it with the joint probability, and state the conclusion ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "choices")

$ci = rand(0, 3)

$places = array("A local restaurant", "A movie theater concession stand", "A campus coffee bar", "A hardware store")
$itemAs = array("a pizza", "a bucket of popcorn", "an espresso drink", "a can of paint")
$itemBs = array("a salad", "a soda", "a pastry", "a paint brush")
$ordersAs = array("order pizzas", "buy popcorn", "order an espresso drink", "buy a can of paint")
$alsoBs = array("also order a salad", "also buy a soda", "also order a pastry", "also buy a paint brush")
$verbs = array("order", "buy", "order", "buy")
$evAs = array("Z", "K", "E", "C")
$evBs = array("S", "D", "P", "B")

$place = $places[$ci]
$itemA = $itemAs[$ci]
$itemB = $itemBs[$ci]
$ordersA = $ordersAs[$ci]
$alsoB = $alsoBs[$ci]
$verb = $verbs[$ci]
$evA = $evAs[$ci]
$evB = $evBs[$ci]

// The `where` clause keeps P(B|A) and P(B) at least 5 points apart, so the answer is
// always "not independent" and the conclusion below can never contradict the numbers.
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
$prod = round($pa * $pb, 4)

$answer[0] = $prod
$abstolerance[0] = 0.00011

$answer[1] = $pand
$abstolerance[1] = 0.00011

$questions[2] = array(
  "No. The events are not independent, because `P(" . $evA . " and " . $evB . ")` and `P(" . $evA . ")P(" . $evB . ")` are not equal.",
  "Yes. The events are independent, because `P(" . $evA . " and " . $evB . ")` and `P(" . $evA . ")P(" . $evB . ")` are equal.",
  "No. The events are not independent, because `P(" . $evA . " and " . $evB . ")` is not zero.",
  "Yes. The events are independent, because both probabilities are greater than zero."
)
$answer[2] = 0

$solutionguide = '
<div style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <p><b>Run the independence test. Either form settles it.</b></p>
  <p><b>a. The product of the two unconditional probabilities.</b> `P(' . $evA . ')P(' . $evB . ') = (' . $pa . ')(' . $pb . ') = ' . $prod . '`</p>
  <p><b>b. The joint probability, from the multiplication rule.</b> `P(' . $evA . ' and ' . $evB . ') = P(' . $evA . ')P(' . $evB . '|' . $evA . ') = (' . $pa . ')(' . $pcond . ') = ' . $pand . '`</p>
  <p><b>c. Compare them.</b> `' . $prod . '` and `' . $pand . '` are not equal, so the events are <b>not independent</b>.</p>
  <p>The other form of the same test: `P(' . $evB . '|' . $evA . ') = ' . $pcond . '` while `P(' . $evB . ') = ' . $pb . '`. Knowing a customer will ' . $verb . ' ' . $itemA . ' changes the chance of ' . $itemB . ', so the two events carry information about each other.</p>
  <p><b>Do not confuse this with mutually exclusive.</b> Independence asks whether one event changes the odds of the other. Mutual exclusivity asks whether they can happen together at all. They are different questions, and a nonzero `P(' . $evA . ' and ' . $evB . ')` answers only the second one.</p>
  <p><b>Answer:</b> no &mdash; not independent, because `P(' . $evA . ')P(' . $evB . ') = ' . $prod . '` but `P(' . $evA . ' and ' . $evB . ') = ' . $pand . '`.</p>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0;">$place knows that the probability that a customer will $verb $itemA is <b>$paPct%</b>. It also knows that the probability that a customer will $verb $itemB is <b>$pbPct%</b>. Of the customers who $ordersA, <b>$pcondPct%</b> of them $alsoB.</p>
    <p style="margin:12px 0 0 0;">Let `$evA` be the event that a customer will $verb $itemA, and `$evB` the event that a customer will $verb $itemB. Suppose one customer is selected at random.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">a.</span> Find `P($evA)P($evB)`. Round to <b>four decimal places</b>. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">b.</span> Find `P($evA and $evB)`. Round to <b>four decimal places</b>. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">c.</span> Are `$evA` and `$evB` independent events? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
