// === NAME - DESCRIPTION: Identify a Conditional Probability from Its Wording - pick out the rate that is restricted to one group, rather than the unconditional rate about the same event ===
// === SET QUESTION TYPE TO: number ===

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

$answer = $pcond
$abstolerance = 0.0011

$solutionguide = '
<div style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <p><b>Spot the phrase that signals a condition.</b></p>
  <p>"Of the customers who ' . $ordersA . ', ' . $pcondPct . '% of them ' . $alsoB . '" restricts attention to the customers who ' . $ordersA . ' first, and then asks about ' . $itemB . '. That is a conditional probability with `' . $evA . '` on the right of the bar:</p>
  <p style="margin-left:1em;"><b>`P(' . $evB . '|' . $evA . ') = ' . $pcond . '`</b></p>
  <p>Notice it is not ' . $pb . '. The filter changed the answer, which is the first hint that `' . $evA . '` and `' . $evB . '` are not independent.</p>
  <p><b>Answer:</b> `P(' . $evB . '|' . $evA . ') = ' . $pcond . '`.</p>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0;">$place knows that the probability that a customer will $verb $itemA is <b>$paPct%</b>. It also knows that the probability that a customer will $verb $itemB is <b>$pbPct%</b>. Of the customers who $ordersA, <b>$pcondPct%</b> of them $alsoB.</p>
    <p style="margin:12px 0 0 0;">Let `$evA` be the event that a customer will $verb $itemA, and `$evB` the event that a customer will $verb $itemB. Suppose one customer is selected at random.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    Find `P($evB|$evA)`. Enter your answer as a <b>decimal</b>. $answerbox
  </div>
</div>

// === ANSWER ===

$solutionguide
