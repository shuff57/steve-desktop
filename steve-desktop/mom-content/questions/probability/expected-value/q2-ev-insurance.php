// === NAME - DESCRIPTION: Expected Value of an Insurance Policy - Compute customer's net expected value of a one-year policy ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc")

// Customer perspective: X = payout - premium if claim, -premium if no claim.
// E(X) = payout * p - premium  (always negative for a profitable insurer).
//
// Scenario 0: term life, p=0.002, payout 100000, premium 250, E = 200 - 250 = -50
// Scenario 1: term life, p=0.005, payout  50000, premium 300, E = 250 - 300 = -50
// Scenario 2: auto coll, p=0.01,  payout  80000, premium 850, E = 800 - 850 = -50
// Scenario 3: home flood,p=0.003, payout 200000, premium 720, E = 600 - 720 = -120
// Scenario 4: travel,    p=0.001, payout 300000, premium 400, E = 300 - 400 = -100

$kinds = array(
  "a one-year <b>term life</b>",
  "a one-year <b>term life</b>",
  "a one-year <b>auto collision</b>",
  "a one-year <b>home flood</b>",
  "a one-year <b>travel</b>"
)
$persons = array(
  "30-year-old non-smoker",
  "45-year-old non-smoker",
  "driver in a high-traffic area",
  "homeowner in a moderate-risk zone",
  "traveler on a single trip"
)
$ps       = array(0.002, 0.005, 0.01,  0.003, 0.001)
$payouts  = array(100000, 50000, 80000, 200000, 300000)
$premiums = array(250,    300,   850,   720,    400)
$ans      = array(-50,    -50,   -50,   -120,   -100)
$prods    = array("100000(0.002) = 200", "50000(0.005) = 250", "80000(0.01) = 800", "200000(0.003) = 600", "300000(0.001) = 300")

$picked = jointrandfrom($kinds, $persons, $ps, $payouts, $premiums, $ans, $prods)
$kind     = $picked[0]
$person   = $picked[1]
$p        = $picked[2]
$payout   = $picked[3]
$premium  = $picked[4]
$answer   = $picked[5]
$prod     = $picked[6]
$abstolerance[0] = 0.5

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><b>Set up `X` from the customer\'s side.</b></p>
      <ul>
        <li>If the event happens (probability `$p`): the customer collects `$payout` but already paid `$premium`, so net `X = $payout - $premium`.</li>
        <li>If the event does not happen (probability `1 - $p`): the customer paid the premium and got nothing back, so `X = -$premium`.</li>
      </ul>
      <p><b>Compute `E(X)`.</b></p>
      <p>`E(X) = ($payout - $premium)($p) + (-$premium)(1 - $p)`</p>
      <p>This simplifies to `E(X) = $payout cdot $p - $premium = $prod - $premium = $answer`.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Interpretation:</b> the expected net value to the customer is `$$answer` per year. The policy has negative expected value because the company builds in a profit margin. People still buy it to protect against the rare large loss.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">A $person buys $kind policy.</p>
    <ul style="margin:0 0 8px 18px;">
      <li>The policy pays out <b>`$$payout`</b> if the covered event occurs during the year.</li>
      <li>The probability the covered event occurs is <b>`$p`</b>.</li>
      <li>The premium is <b>`$$premium`</b>, paid up front.</li>
    </ul>
    <p style="margin:0;">Let `X` be the customer's <b>net</b> value at year's end (claim minus premium, or just the premium loss if no claim).</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Compute `E(X)` in dollars. Include the sign. $answerbox[0]
  </div>
</div>


// === ANSWER ===

$solutionguide
