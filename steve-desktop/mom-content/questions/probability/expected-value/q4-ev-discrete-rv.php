// === NAME - DESCRIPTION: Expected Value - Verify valid probability distribution, compute E(X) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "numfunc")

// Four discrete-distribution scenarios. Each uses 4 outcomes with probabilities summing exactly to 1.
// E(X) is a clean decimal so students can verify by hand.
//
// Scenario 0: Raffle tickets. x={0,1,2,3}, p={2/8,3/8,2/8,1/8}  E(X)=1.25
// Scenario 1: Spinner points. x={1,2,4,8}, p={4/10,3/10,2/10,1/10}  E(X)=2.6
// Scenario 2: Pond game fish. x={0,1,2,3}, p={5/10,2/10,2/10,1/10}  E(X)=0.9
// Scenario 3: Free throws. x={0,1,2,3}, p={2/8,1/8,3/8,2/8}  E(X)=1.625

$x0s = array("0", "1", "0", "0")
$x1s = array("1", "2", "1", "1")
$x2s = array("2", "4", "2", "2")
$x3s = array("3", "8", "3", "3")

$p0s = array("2/8", "4/10", "5/10", "2/8")
$p1s = array("3/8", "3/10", "2/10", "1/8")
$p2s = array("2/8", "2/10", "2/10", "3/8")
$p3s = array("1/8", "1/10", "1/10", "2/8")

$exs  = array(1.25, 2.6, 0.9, 1.625)

$ctxs = array(
  "A school raffle awards tickets according to the probability distribution below. Let `X` be the number of raffle tickets won.",
  "A spinner at a carnival awards points according to the probability distribution below. Let `X` be the number of points from one spin.",
  "A pond game awards fish caught per play according to the probability distribution below. Let `X` be the number of fish caught in one play.",
  "A basketball player shoots three free throws. Let `X` be the number of free throws made, following the probability distribution below."
)

$picked = jointrandfrom($x0s, $x1s, $x2s, $x3s, $p0s, $p1s, $p2s, $p3s, $exs, $ctxs)
$x0 = $picked[0]
$x1 = $picked[1]
$x2 = $picked[2]
$x3 = $picked[3]
$p0 = $picked[4]
$p1 = $picked[5]
$p2 = $picked[6]
$p3 = $picked[7]
$ex  = $picked[8]
$ctx = $picked[9]

$answer[0] = 0
$answer[1] = $ex
$abstolerance[1] = 0.005

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
      <p>This is the long-run average if the process were repeated many times.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
    <div style="margin:1em 0; display:inline-block; border-radius:12px; overflow:hidden; box-shadow:0 4px 6px -1px rgba(0,0,0,0.08),0 2px 4px -2px rgba(0,0,0,0.05); border:1px solid #e5e7eb;">
      <table style="border-collapse:collapse;">
        <tr><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 18px; font-weight:600;">x</th><td style="border:1px solid #dee1e3; padding:8px 18px; text-align:center;">$x0</td><td style="border:1px solid #dee1e3; padding:8px 18px; text-align:center;">$x1</td><td style="border:1px solid #dee1e3; padding:8px 18px; text-align:center;">$x2</td><td style="border:1px solid #dee1e3; padding:8px 18px; text-align:center;">$x3</td></tr>
        <tr><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 18px; font-weight:600;">P(X = x)</th><td style="border:1px solid #dee1e3; padding:8px 18px; text-align:center;">$p0</td><td style="border:1px solid #dee1e3; padding:8px 18px; text-align:center;">$p1</td><td style="border:1px solid #dee1e3; padding:8px 18px; text-align:center;">$p2</td><td style="border:1px solid #dee1e3; padding:8px 18px; text-align:center;">$p3</td></tr>
      </table>
    </div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Do the probabilities in this table form a valid probability distribution?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Compute <b>E(X)</b>, the expected value of X.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>


// === ANSWER ===

$solutionguide
