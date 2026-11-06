// === NAME - DESCRIPTION: Build the Expected Value Table for a Game - Enter the net profit for each outcome (two wins and a loss) and the probability of each, then compute E(X) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Six games, each with three outcomes: a big win, a small win and a loss. The student fills BOTH rows of the table.
// The table has no outcome labels (they led the student); the prompt fixes the order, largest net profit to smallest.
// Parts: (a)-(c) numfunc - x for each outcome (net profit, the loss is negative); (d)-(f) numfunc - P(X = x) for each outcome; (g) numfunc - E(X).
// Invariant: x = prize - cost; the three P values add to 1; E(X) = sum of x*P(x), precomputed, and may be positive, zero or negative.
// The die game carries a figure of the six faces; the other games have none.

$anstypes = array("numfunc", "numfunc", "numfunc", "numfunc", "numfunc", "numfunc", "numfunc")

$ctxs = array("A carnival spinner has 8 equal sections. It costs $2 to spin. One section pays $10, two sections pay $4, and the other five sections pay nothing. Let `X` be your net profit on one play, which is the prize minus the cost to play.", "A raffle sells 100 tickets at $5 each, and you buy one ticket. One ticket wins $200, four tickets win $25, and the other 95 tickets win nothing. Let `X` be your net profit on one play, which is the prize minus the cost to play.", "It costs $1 to roll a fair six-sided die. You win $5 if you roll a 6, you win $2 if you roll a 4 or a 5, and you win nothing if you roll a 1, 2 or 3. Let `X` be your net profit on one play, which is the prize minus the cost to play.", "A bag holds 20 marbles: 1 gold, 4 silver and 15 plain. It costs $3 to draw one marble. A gold marble pays $15, a silver marble pays $4, and a plain marble pays nothing. Let `X` be your net profit on one play, which is the prize minus the cost to play.", "A game spinner has 10 equal sections. It costs $4 to spin. One section pays $20, two sections pay $10, and the other seven sections pay nothing. Let `X` be your net profit on one play, which is the prize minus the cost to play.", "A ring toss costs $1 to play. You win $8 with probability 0.05, you win $2 with probability 0.25, and you win nothing otherwise. Let `X` be your net profit on one play, which is the prize minus the cost to play.")
$xa = array(8, 195, 4, 12, 16, 7)
$xb = array(2, 20, 1, 1, 6, 1)
$xc = array(-2, -5, -1, -3, -4, -1)
$pa = array(0.125, 0.01, 0.1667, 0.05, 0.1, 0.05)
$pb = array(0.25, 0.04, 0.3333, 0.2, 0.2, 0.25)
$pc = array(0.625, 0.95, 0.5, 0.75, 0.7, 0.7)
$evs = array(0.25, -2.0, 0.5, -1.45, 0.0, -0.1)
$hows = array("Net profit is the prize minus the $2 cost: 10 - 2 = 8, 4 - 2 = 2, and 0 - 2 = -2. The chances are 1/8 = 0.125, 2/8 = 0.25 and 5/8 = 0.625, and they add to 1. The products are 1, 0.5 and -1.25. `E(X) = 8(0.125) + 2(0.25) + (-2)(0.625) = 0.25` dollars. This is positive, so on average you gain money on each play.", "Net profit is the prize minus the $5 ticket: 200 - 5 = 195, 25 - 5 = 20, and 0 - 5 = -5. The chances are 1/100 = 0.01, 4/100 = 0.04 and 95/100 = 0.95, and they add to 1. The products are 1.95, 0.8 and -4.75. `E(X) = 195(0.01) + 20(0.04) + (-5)(0.95) = -2` dollars. This is negative, so on average you lose money on each play.", "Net profit is the prize minus the $1 cost: 5 - 1 = 4, 2 - 1 = 1, and 0 - 1 = -1. The chances are 1/6 = 0.1667, 2/6 = 0.3333 and 3/6 = 0.5, and they add to 1. The products are 0.6667, 0.3333 and -0.5. `E(X) = 4(0.1667) + 1(0.3333) + (-1)(0.5) = 0.5` dollars. This is positive, so on average you gain money on each play.", "Net profit is the prize minus the $3 cost: 15 - 3 = 12, 4 - 3 = 1, and 0 - 3 = -3. The chances are 1/20 = 0.05, 4/20 = 0.2 and 15/20 = 0.75, and they add to 1. The products are 0.6, 0.2 and -2.25. `E(X) = 12(0.05) + 1(0.2) + (-3)(0.75) = -1.45` dollars. This is negative, so on average you lose money on each play.", "Net profit is the prize minus the $4 cost: 20 - 4 = 16, 10 - 4 = 6, and 0 - 4 = -4. The chances are 1/10 = 0.1, 2/10 = 0.2 and 7/10 = 0.7, and they add to 1. The products are 1.6, 1.2 and -2.8. `E(X) = 16(0.1) + 6(0.2) + (-4)(0.7) = 0` dollars. This is zero, so the game is fair: on average you break even.", "Net profit is the prize minus the $1 cost: 8 - 1 = 7, 2 - 1 = 1, and 0 - 1 = -1. The chances are 0.05, 0.25 and 1 - 0.05 - 0.25 = 0.7, and they add to 1. The products are 0.35, 0.25 and -0.7. `E(X) = 7(0.05) + 1(0.25) + (-1)(0.7) = -0.1` dollars. This is negative, so on average you lose money on each play.")
$figDie = '<svg viewBox="0 0 420 96" width="100%" style="max-width:420px; display:block; margin:12px auto 0;" xmlns="http://www.w3.org/2000/svg" role="img"><rect x="10" y="8" width="56" height="56" rx="9" fill="#fff" stroke="#374151" stroke-width="2"/><circle cx="38" cy="36" r="5.5" fill="#21242c"/><rect x="76" y="8" width="56" height="56" rx="9" fill="#fff" stroke="#374151" stroke-width="2"/><circle cx="90" cy="22" r="5.5" fill="#21242c"/><circle cx="118" cy="50" r="5.5" fill="#21242c"/><rect x="142" y="8" width="56" height="56" rx="9" fill="#fff" stroke="#374151" stroke-width="2"/><circle cx="156" cy="22" r="5.5" fill="#21242c"/><circle cx="170" cy="36" r="5.5" fill="#21242c"/><circle cx="184" cy="50" r="5.5" fill="#21242c"/><rect x="208" y="8" width="56" height="56" rx="9" fill="#fff" stroke="#374151" stroke-width="2"/><circle cx="222" cy="22" r="5.5" fill="#21242c"/><circle cx="250" cy="22" r="5.5" fill="#21242c"/><circle cx="222" cy="50" r="5.5" fill="#21242c"/><circle cx="250" cy="50" r="5.5" fill="#21242c"/><rect x="274" y="8" width="56" height="56" rx="9" fill="#fff" stroke="#374151" stroke-width="2"/><circle cx="288" cy="22" r="5.5" fill="#21242c"/><circle cx="316" cy="22" r="5.5" fill="#21242c"/><circle cx="302" cy="36" r="5.5" fill="#21242c"/><circle cx="288" cy="50" r="5.5" fill="#21242c"/><circle cx="316" cy="50" r="5.5" fill="#21242c"/><rect x="340" y="8" width="56" height="56" rx="9" fill="#fff" stroke="#374151" stroke-width="2"/><circle cx="354" cy="22" r="5.5" fill="#21242c"/><circle cx="382" cy="22" r="5.5" fill="#21242c"/><circle cx="354" cy="36" r="5.5" fill="#21242c"/><circle cx="382" cy="36" r="5.5" fill="#21242c"/><circle cx="354" cy="50" r="5.5" fill="#21242c"/><circle cx="382" cy="50" r="5.5" fill="#21242c"/><text x="210" y="88" font-size="13" fill="#374151" text-anchor="middle">The six equally likely faces of the die</text></svg>'
$figs = array("", "", $figDie, "", "", "")

$picked = jointrandfrom($ctxs, $xa, $xb, $xc, $pa, $pb, $pc, $evs, $hows, $figs)
$ctx = $picked[0]
$answer[0] = $picked[1]
$answer[1] = $picked[2]
$answer[2] = $picked[3]
$answer[3] = $picked[4]
$answer[4] = $picked[5]
$answer[5] = $picked[6]
$answer[6] = $picked[7]
$how = $picked[8]
$fig = $picked[9]

$answeights = array(1, 1, 1, 1, 1, 1, 2)
$answerboxsize = array(6, 6, 6, 7, 7, 7, 8)
$abstolerance = array(0.005, 0.005, 0.005, 0.005, 0.005, 0.005, 0.005)

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
  .term-label { font-weight:700; color:#1865f2; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><span class="term-label">Build the table.</span> Each x is what you end up with after paying to play, so a loss is a negative number. Each probability is that outcome out of all the equally likely outcomes. The probabilities must add to 1.</p>
      <p><span class="term-label">Fill and compute.</span> ' . $how . '</p>
      <p><span class="term-label">The pattern.</span> Multiply each x by its probability, then add the products. That sum is E(X), the average net profit per play over many plays.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
    $fig
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:16px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a-f.</span> Complete the expected value table. List the three outcomes from the largest net profit `x` to the smallest. Enter each `x`, using a negative number for a loss, and the probability of each outcome as a decimal.</p>
    <table style="border-collapse:collapse; margin:0 auto; font-size:16px;">
      <tr>
        <th style="border:1px solid #21242c; padding:6px 14px; background:#f0f4ff;">`x` in dollars</th>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$answerbox[0]</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$answerbox[1]</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$answerbox[2]</td>
      </tr>
      <tr>
        <th style="border:1px solid #21242c; padding:6px 14px; background:#f0f4ff;">`P(X = x)`</th>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$answerbox[3]</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$answerbox[4]</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$answerbox[5]</td>
      </tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">g.</span> Compute `E(X)` in dollars. Include the sign.
    <span style="margin-left:8px;">$answerbox[6]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
