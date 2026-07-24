// === NAME - DESCRIPTION: Expected Value - Confirm probabilities sum to 1, compute E(X) and E(X^2) for a discrete random variable ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "numfunc", "numfunc")

// Four dice-game payout scenarios. Each uses 4 outcomes with probabilities summing exactly to 1.
// E(X) and E(X^2) are clean decimals so students can verify by hand.
//
// Scenario 0: x={0,1,2,3}, p={1/4,1/4,1/4,1/4}  E(X)=1.5  E(X^2)=3.5
// Scenario 1: x={1,2,3,4}, p={2/10,3/10,3/10,2/10}  E(X)=2.5  E(X^2)=7.3
// Scenario 2: x={0,2,4,6}, p={3/10,3/10,2/10,2/10}  E(X)=2.6  E(X^2)=11.6
// Scenario 3: x={1,3,5,7}, p={4/10,3/10,2/10,1/10}  E(X)=3.0  E(X^2)=13.0

// Outcome values (display strings for the table)
$x0s = array("0", "1", "0", "1")
$x1s = array("1", "2", "2", "3")
$x2s = array("2", "3", "4", "5")
$x3s = array("3", "4", "6", "7")

// Probability display strings (shown in the question table)
$p0s = array("1/4", "2/10", "3/10", "4/10")
$p1s = array("1/4", "3/10", "3/10", "3/10")
$p2s = array("1/4", "3/10", "2/10", "2/10")
$p3s = array("1/4", "2/10", "2/10", "1/10")

// Precomputed answers
$exs  = array(1.5, 2.5, 2.6, 3.0)
$ex2s = array(3.5, 7.3, 11.6, 13.0)

$picked = jointrandfrom($x0s, $x1s, $x2s, $x3s, $p0s, $p1s, $p2s, $p3s, $exs, $ex2s)
$x0 = $picked[0]
$x1 = $picked[1]
$x2 = $picked[2]
$x3 = $picked[3]
$p0 = $picked[4]
$p1 = $picked[5]
$p2 = $picked[6]
$p3 = $picked[7]
$ex  = $picked[8]
$ex2 = $picked[9]

$answer[0] = 0
$answer[1] = $ex
$answer[2] = $ex2
$abstolerance[1] = 0.005
$abstolerance[2] = 0.005

$choices[0] = array("Yes, the probabilities sum to 1", "No, the probabilities do not sum to 1")
$displayformat[0] = "select"
$noshuffle[0] = "all"

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
      <p><b>(a) Probability sum check:</b></p>
      <p>'.$p0.' + '.$p1.' + '.$p2.' + '.$p3.' = 1 &nbsp;&check;</p>
      <p>A valid probability distribution requires all P(X = x) &ge; 0 and the sum equal to exactly 1. Both conditions hold here.</p>
      <p><b>(b) E(X) = &Sigma; x &middot; P(x):</b></p>
      <p>E(X) = '.$x0.'&middot;('.$p0.') + '.$x1.'&middot;('.$p1.') + '.$x2.'&middot;('.$p2.') + '.$x3.'&middot;('.$p3.')</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        E(X) = <b>'.$ex.'</b>
      </div>
      <p>This is the long-run average payout per game if the game were played many times.</p>
      <p><b>(c) E(X&sup2;) = &Sigma; x&sup2; &middot; P(x):</b></p>
      <p>E(X&sup2;) = ('.$x0.')<sup>2</sup>&middot;('.$p0.') + ('.$x1.')<sup>2</sup>&middot;('.$p1.') + ('.$x2.')<sup>2</sup>&middot;('.$p2.') + ('.$x3.')<sup>2</sup>&middot;('.$p3.')</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        E(X&sup2;) = <b>'.$ex2.'</b>
      </div>
      <p>Variance can then be found as Var(X) = E(X&sup2;) &minus; [E(X)]&sup2; = '.$ex2.' &minus; ('.$ex.')<sup>2</sup>.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A carnival game awards a random number of tickets, X, according to the probability distribution below.</p>
    <div style="margin:1em 0; display:inline-block; border-radius:12px; overflow:hidden; box-shadow:0 4px 6px -1px rgba(0,0,0,0.08),0 2px 4px -2px rgba(0,0,0,0.05); border:1px solid #e5e7eb;">
      <table style="border-collapse:collapse;">
        <tr><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 18px; font-weight:600;">x (tickets)</th><td style="border:1px solid #dee1e3; padding:8px 18px; text-align:center;">$x0</td><td style="border:1px solid #dee1e3; padding:8px 18px; text-align:center;">$x1</td><td style="border:1px solid #dee1e3; padding:8px 18px; text-align:center;">$x2</td><td style="border:1px solid #dee1e3; padding:8px 18px; text-align:center;">$x3</td></tr>
        <tr><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 18px; font-weight:600;">P(X = x)</th><td style="border:1px solid #dee1e3; padding:8px 18px; text-align:center;">$p0</td><td style="border:1px solid #dee1e3; padding:8px 18px; text-align:center;">$p1</td><td style="border:1px solid #dee1e3; padding:8px 18px; text-align:center;">$p2</td><td style="border:1px solid #dee1e3; padding:8px 18px; text-align:center;">$p3</td></tr>
      </table>
    </div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Do the probabilities in this table form a valid probability distribution?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Compute <b>E(X)</b>, the expected number of tickets per game.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Compute <b>E(X&sup2;)</b> = &Sigma; x&sup2; &middot; P(x). (This is the first step toward finding the variance.)
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>


// === ANSWER ===

$solutionguide
