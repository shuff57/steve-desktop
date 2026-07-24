// === NAME - DESCRIPTION: Expected Value of a Game of Chance - Compute E(X) for profit, predict expected total over many plays, decide if the game is fair ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc", "numfunc", "choices")

// Scenario 0: Scratch-off ticket. Cost $2. Win $10 (p=0.10), $2 (p=0.20), $0 (p=0.70). X = 8, 0, -2. E(X) = -0.6
// Scenario 1: Draw marble. Free. Win $4 on red (2/10), lose $1 (8/10). X = 4, -1. E(X) = 0. Fair!
// Scenario 2: Coin toss game. Pay $3. Win $8 on tails, $0 on heads. X = 5, -3. E(X) = 1.0
// Scenario 3: Spinner. Free spin. Win $6 (1/8), $3 (3/8), lose $2 (4/8). X = 6, 3, -2. E(X) = 0.875
// Fair: 0 = Yes (E=0), 1 = No

$ctxs = array(
  "You pay <b>$2</b> for a scratch-off ticket. You win <b>$10</b> with probability <b>0.10</b>, win <b>$2</b> with probability <b>0.20</b>, and win <b>$0</b> with probability <b>0.70</b>. Let `X` be your net profit (winnings minus cost).",
  "You draw one marble from a bag for free. The bag has 2 red marbles and 8 blue marbles. You win <b>$4</b> if it is red and lose <b>$1</b> if it is blue. Let `X` be your profit on one draw.",
  "You pay <b>$3</b> to toss a fair coin. You win <b>$8</b> if it lands tails, and win <b>$0</b> if it lands heads. Let `X` be your net profit on one toss.",
  "You spin a spinner with 8 equal sections for free. Win <b>$6</b> on 1 section, win <b>$3</b> on 3 sections, and lose <b>$2</b> on 4 sections. Let `X` be your profit on one spin."
)
$evs   = array(-0.6, 0, 1.0, 0.875)
$ev100 = array(-60,  0, 100, 87.5)
$fair  = array(1,    0, 1,   1)
$verbal = array(
  "On average, each ticket loses about $0.60. Over 100 tickets you expect to lose about $60.",
  "The wins and losses balance exactly, so on average each draw is worth $0.",
  "On average, each toss gains $1.00. Over 100 tosses you expect to gain $100.",
  "On average, each spin gains about $0.88. Over 100 spins you expect to gain about $87.50."
)
$evcalc = array(
  "(8)(0.10) + (0)(0.20) + (-2)(0.70) = 0.80 + 0 - 1.40 = -0.60",
  "(4)(2/10) + (-1)(8/10) = 0.80 - 0.80 = 0",
  "(5)(1/2) + (-3)(1/2) = 2.50 - 1.50 = 1.00",
  "(6)(1/8) + (3)(3/8) + (-2)(4/8) = 0.75 + 1.125 - 1.00 = 0.875"
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
$noshuffle[2] = "all"

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
