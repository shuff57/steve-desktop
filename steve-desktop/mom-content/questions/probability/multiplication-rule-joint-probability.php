// === NAME - DESCRIPTION: Multiplication Rule for a Joint Probability - compute P(A AND B) from an unconditional rate and the conditional rate given in the same setup ===
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

// See unconditional-probability-first-event.php for why these bounds exist: the source
// exercise's own numbers make P(A AND B) exceed P(B), which is impossible.
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

$answer = $pand
$abstolerance = 0.00011

$solutionguide = '
<div style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <p><b>Use the multiplication rule in the order the conditional was given.</b></p>
  <p>You were handed `P(' . $evB . '|' . $evA . ') = ' . $pcond . '`, so pair it with `P(' . $evA . ') = ' . $pa . '`:</p>
  <p style="margin-left:1em;"><b>`P(' . $evA . ' and ' . $evB . ') = P(' . $evA . ') * P(' . $evB . '|' . $evA . ') = (' . $pa . ')(' . $pcond . ') = ' . $pand . '`</b></p>
  <p>Pairing the conditional with the wrong unconditional rate is the usual slip here. `P(' . $evB . '|' . $evA . ')` already lives inside the group who ' . $ordersA . ', so the rate it must be multiplied by is `P(' . $evA . ')`: not `P(' . $evB . ')`.</p>
  <p><b>Check:</b> a joint probability can never exceed either single probability. Here ' . $pand . ' is below both ' . $pa . ' and ' . $pb . ', as it must be.</p>
  <p><b>Answer:</b> `P(' . $evA . ' and ' . $evB . ') = ' . $pand . '`.</p>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0;">$place knows that the probability that a customer will $verb $itemA is <b>$paPct%</b>. It also knows that the probability that a customer will $verb $itemB is <b>$pbPct%</b>. Of the customers who $ordersA, <b>$pcondPct%</b> of them $alsoB.</p>
    <p style="margin:12px 0 0 0;">Let `$evA` be the event that a customer will $verb $itemA, and `$evB` the event that a customer will $verb $itemB. Suppose one customer is selected at random.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    Find `P($evA and $evB)`. Enter your answer as a <b>decimal rounded to four decimal places</b>. $answerbox
  </div>
</div>

// === ANSWER ===

$solutionguide
