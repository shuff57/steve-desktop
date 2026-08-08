// === NAME - DESCRIPTION: Interpret a Conditional Event in Words - translate the notation B|A back into a sentence, against distractors that reverse the condition or swap it for AND or OR ===
// === SET QUESTION TYPE TO: choices ===

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

$paPct = rand(35, 80)
$pcondPct = rand(25, 70)
$andPct = $paPct * $pcondPct / 100
$loPct = ceil($andPct) + 4
$hiPct = floor(98 - $paPct + $andPct)
$pbPct = rand($loPct, $hiPct) where (abs($pbPct - $pcondPct) >= 5)

$pcond = $pcondPct / 100

// Distractors are the three mistakes this notation actually produces: reading the bar
// backwards, collapsing it to AND, and collapsing it to OR.
$questions = array(
  "the event that a customer will " . $verb . " " . $itemB . ", given that the customer has already " . $past . " " . $itemA,
  "the event that a customer will " . $verb . " " . $itemA . ", given that the customer has already " . $past . " " . $itemB,
  "the event that a customer will " . $verb . " both " . $itemA . " and " . $itemB . " on the same visit",
  "the event that a customer will " . $verb . " " . $itemA . ", or " . $itemB . ", or both"
)
$answer = 0

$solutionguide = '
<div style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <p><b>Translate the bar back into a sentence.</b></p>
  <p>`' . $evB . '|' . $evA . '` means the event `' . $evB . '` evaluated inside the shrunken world where `' . $evA . '` has already happened. The event on the <i>right</i> of the bar is the one assumed to have occurred; the event on the <i>left</i> is the one whose chance you are asking about.</p>
  <p>In words: `' . $evB . '|' . $evA . '` is the event that a customer will ' . $verb . ' ' . $itemB . ', <b>given</b> that the customer has already ' . $past . ' ' . $itemA . '. Its probability, `P(' . $evB . '|' . $evA . ') = ' . $pcond . '`, is the proportion of the customers who ' . $ordersA . ' that ' . $alsoB . '.</p>
  <p><b>Why the others are wrong.</b> Reversing the bar asks a different question about a different group. Both "and" and "or" drop the condition entirely &mdash; they are about all customers, not just the ones who ' . $ordersA . '.</p>
  <p><b>Answer:</b> the event that a customer will ' . $verb . ' ' . $itemB . ' given that the customer has already ' . $past . ' ' . $itemA . '.</p>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0;">$place knows that the probability that a customer will $verb $itemA is <b>$paPct%</b>. It also knows that the probability that a customer will $verb $itemB is <b>$pbPct%</b>. Of the customers who $ordersA, <b>$pcondPct%</b> of them $alsoB.</p>
    <p style="margin:12px 0 0 0;">Let `$evA` be the event that a customer will $verb $itemA, and `$evB` the event that a customer will $verb $itemB. Suppose one customer is selected at random.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    In words, what is `$evB|$evA`?
  </div>
</div>

// === ANSWER ===

$solutionguide
