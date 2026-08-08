// === NAME - DESCRIPTION: Read an Unconditional Probability from a Setup - pick the plain rate for the first event out of a setup that also states a second rate and a conditional rate ===
// === SET QUESTION TYPE TO: number ===

// === COMMON CONTROL ===

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

// Rates chosen so the scenario is CONSISTENT. The source exercise is not: it pairs
// P(Z)=0.87, P(S)=0.32, P(S|Z)=0.55, which forces P(Z AND S)=0.4785 > P(S) and
// P(Z OR S)=0.7115 < P(Z). Both are impossible. The bounds below rule that out:
//   lo keeps P(A AND B) <= P(B);  hi keeps P(A OR B) <= 1.
$paPct = rand(35, 80)
$pcondPct = rand(25, 70)
$andPct = $paPct * $pcondPct / 100
$loPct = ceil($andPct) + 4
$hiPct = floor(98 - $paPct + $andPct)
$pbPct = rand($loPct, $hiPct) where (abs($pbPct - $pcondPct) >= 5)

$pa = $paPct / 100
$pb = $pbPct / 100
$pcond = $pcondPct / 100

$answer = $pa
$abstolerance = 0.0011

$solutionguide = '
<div style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <p><b>Read the unconditional rate straight off the setup.</b></p>
  <p>"The probability that a customer will ' . $verb . ' ' . $itemA . ' is ' . $paPct . '%" is a plain probability about <i>every</i> customer, with no condition attached.</p>
  <p style="margin-left:1em;"><b>`P(' . $evA . ') = ' . $pa . '`</b></p>
  <p>The other two numbers in the setup are there to be rejected: ' . $pbPct . '% is about ' . $itemB . ', and ' . $pcondPct . '% applies only inside the group who ' . $ordersA . '.</p>
  <p><b>Answer:</b> `P(' . $evA . ') = ' . $pa . '`.</p>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0;">$place knows that the probability that a customer will $verb $itemA is <b>$paPct%</b>. It also knows that the probability that a customer will $verb $itemB is <b>$pbPct%</b>. Of the customers who $ordersA, <b>$pcondPct%</b> of them $alsoB.</p>
    <p style="margin:12px 0 0 0;">Let `$evA` be the event that a customer will $verb $itemA, and `$evB` the event that a customer will $verb $itemB. Suppose one customer is selected at random.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    Find `P($evA)`. Enter your answer as a <b>decimal</b>. $answerbox
  </div>
</div>

// === ANSWER ===

$solutionguide
