// === NAME - DESCRIPTION: Expected Value of a Game of Chance - Compute E(X) for the profit, predict 100-play total, decide if fair ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc", "numfunc", "choices")

// Each scenario gives a game context, the profit X values, the probabilities (as fractions for display),
// and the precomputed E(X). "Fair" is decided by whether E(X) = 0.

// Scenario 0: Spin wheel, pay $5. Win $20 (p=0.10), $5 (p=0.30), $0 (p=0.60). X = 15, 0, -5. E(X) = -1.5
// Scenario 1: Roll die. Win $4 on a 6, win $1 on 4-5, lose $1 on 1-3. X = 4, 1, -1. P = 1/6, 2/6, 3/6. E(X) = 0.5
// Scenario 2: Flip two coins. Win $4 on HH, lose $1 otherwise. X = 4, -1. P = 1/4, 3/4. E(X) = 0.25
// Scenario 3: Draw a card. Win $3 if heart, lose $1 otherwise. X = 3, -1. P = 1/4, 3/4. E(X) = 0
// "Fair" index: 0 = Yes (E=0), 1 = No (E != 0)

$ctxs = array(
  "You pay <b>$5</b> to spin a wheel. You win <b>$20</b> with probability <b>0.10</b>, win <b>$5</b> with probability <b>0.30</b>, and win <b>$0</b> with probability <b>0.60</b>. Let `X` be your net profit (winnings minus cost).",
  "You roll a fair six-sided die for free. You win <b>$4</b> if you roll a 6, you win <b>$1</b> if you roll a 4 or 5, and you lose <b>$1</b> if you roll a 1, 2, or 3. Let `X` be your profit on one roll.",
  "You flip two fair coins for free. You win <b>$4</b> if both land heads, otherwise you lose <b>$1</b>. Let `X` be your profit on one play.",
  "You draw one card from a standard 52-card deck for free. You win <b>$3</b> if it is a heart and lose <b>$1</b> otherwise. Let `X` be your profit on one draw."
)
$evs   = array(-1.5, 0.5, 0.25, 0)
$ev100 = array(-150, 50,  25,   0)
$fair  = array(1,    1,   1,    0)
$verbal = array(
  "On average, each play loses about $1.50. Over 100 plays you expect to lose about $150.",
  "On average, each roll gains $0.50. Over 100 rolls you expect to gain $50.",
  "On average, each play gains $0.25. Over 100 plays you expect to gain $25.",
  "The wins and losses balance exactly, so on average each draw is worth $0."
)
$evcalc = array(
  "(15)(0.10) + (0)(0.30) + (-5)(0.60) = 1.5 + 0 - 3 = -1.5",
  "(4)(1/6) + (1)(2/6) + (-1)(3/6) = 4/6 + 2/6 - 3/6 = 3/6 = 0.5",
  "(4)(1/4) + (-1)(3/4) = 1 - 0.75 = 0.25",
  "(3)(1/4) + (-1)(3/4) = 0.75 - 0.75 = 0"
)

$picked = jointrandfrom($ctxs, $evs, $ev100, $fair, $verbal, $evcalc)
$ctx       = $picked[0]
$answer[0] = $picked[1]
$answer[1] = $picked[2]
$answer[2] = $picked[3]
$why       = $picked[4]
$calc      = $picked[5]
$reltolerance[0] = 0.02
$abstolerance[0] = 0.01
$abstolerance[1] = 0.5

$choices[2] = array(
  "Yes. The game is fair because the expected profit is $0.",
  "No. The game is not fair because the expected profit is not $0."
)

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
      <p><b>Part a:</b> Use `E(X) = sum x_i cdot P(X = x_i)` with the profit values.</p>
      <p>$calc</p>
      <p>So `E(X) = ' . $answer[0] . '` dollars per play.</p>
      <p><b>Part b:</b> Expected total over 100 independent plays is `100 cdot E(X) = ' . $answer[1] . '` dollars.</p>
      <p><b>Part c:</b> $why A game is fair only when `E(X) = 0`.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Compute `E(X)`, the expected profit per play (in dollars). $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> If you play this game <b>100</b> times, what is your expected total profit (in dollars)? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is this game fair?
    <div style="margin-top:12px;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
