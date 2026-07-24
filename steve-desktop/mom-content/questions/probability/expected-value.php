// === COMMON CONTROL ===
// Section 8.5/9.2 — Expected Value of a Discrete Random Variable (1 number answer)
// Fixed scenarios with precomputed outcomes to avoid C-style for loops.

// Each scenario is fully self-contained:
//   ctx_intro  — problem setup text
//   ctx_xdesc  — what X represents
//   ctx_q      — question stem
//   vals       — outcome values (net gain in dollars, may be negative)
//   probs      — exact probabilities as decimals (sum to 1)
//   ev         — E(X) = sum of x*P(x), precomputed and rounded to 2 decimal places
//   ev_sign    — "lose" or "gain" for interpretation
//   ev_abs     — abs(ev) as string

// Scenario 0: Carnival wheel — $2 to play, 4 outcomes
//   P(-2) = 10/20, P(0) = 6/20, P(3) = 3/20, P(8) = 1/20
//   E(X) = -2*(0.5) + 0*(0.3) + 3*(0.15) + 8*(0.05) = -1 + 0 + 0.45 + 0.40 = -0.15

// Scenario 1: Dice game — $3 to play, roll 1d6
//   Roll 1,2,3 → lose $3 (net -3); Roll 4 → win $3 (net 0 after cost? — use net payout)
//   Payout: 1-3 = $0, 4 = $6, 5 = $9, 6 = $15; net = payout - 3
//   P(net -3) = 3/6, P(net 3) = 1/6, P(net 6) = 1/6, P(net 12) = 1/6
//   E(X) = -3*(0.5) + 3*(1/6) + 6*(1/6) + 12*(1/6) = -1.5 + 0.5 + 1 + 2 = 2.0 — too favorable
//   Revise: P(net -3) = 4/6, P(net 3) = 1/6, P(net 9) = 1/6
//   E(X) = -3*(4/6) + 3*(1/6) + 9*(1/6) = -2 + 0.5 + 1.5 = 0. Use:
//   Payouts: roll 1–4 = lose $3 (net -3), roll 5 = win $3 (net 0), roll 6 = win $9 (net 6)
//   Wait — keep simple: 4 outcomes with round fractions.

// Scenario 1: Raffle — 50 tickets at $2 each
//   42 lose (-$2), 5 win $0 net (prize=$2, break even=0), 2 win $8 net, 1 win $48 net
//   E = -2*(42/50) + 0*(5/50) + 8*(2/50) + 48*(1/50) = -84/50 + 0 + 16/50 + 48/50 = -20/50 = -0.40

// Scenario 2: Slot machine — $1 to play
//   P(net -1) = 0.65, P(net 1) = 0.20, P(net 4) = 0.10, P(net 9) = 0.05
//   E = -1*0.65 + 1*0.20 + 4*0.10 + 9*0.05 = -0.65+0.20+0.40+0.45 = 0.40
//   Adjust so house wins: P(-1)=0.70, P(1)=0.20, P(4)=0.08, P(14)=0.02
//   E = -0.70+0.20+0.32+0.28 = 0.10 — slight gain. Use P(-1)=0.75:
//   E = -0.75+0.20+0.32+0.28 = 0.05. Still positive. Use:
//   P(net -1)=0.60, P(net 0)=0.25, P(net 3)=0.10, P(net 9)=0.05
//   E = -0.60+0+0.30+0.45 = 0.15. Use net +values only to keep clear signs.
//   Settle on scenario: cost $1, outcomes: lose $1, break even (win $1 back), win $4, win $14
//   net: -1, 0, 3, 13 with P=0.60, 0.25, 0.10, 0.05
//   E = -0.60+0+0.30+0.65 = 0.35 (positive — player-friendly game)

// Scenario 3: Insurance-style — expected payout
//   A small company self-insures a machine. Possible annual repair costs:
//   $0 (prob 0.70), $500 (prob 0.20), $2000 (prob 0.08), $8000 (prob 0.02)
//   E = 0*0.70 + 500*0.20 + 2000*0.08 + 8000*0.02 = 0+100+160+160 = 420

$ci = rand(0, 3)

// Scenario 0: Carnival wheel
$s0_intro = 'A carnival game costs <b>$2.00</b> to play. A wheel is spun and the net gain X (winnings minus the $2 entry fee) has the distribution below:'
$s0_xdesc = 'net gain per spin (in dollars)'
$s0_q     = 'What is the expected net gain per spin?'
$s0_x0 = -2
$s0_x1 = 0
$s0_x2 = 3
$s0_x3 = 8
$s0_p0 = 0.5
$s0_p1 = 0.30
$s0_p2 = 0.15
$s0_p3 = 0.05
$s0_ev = round($s0_x0*$s0_p0 + $s0_x1*$s0_p1 + $s0_x2*$s0_p2 + $s0_x3*$s0_p3, 2)

// Scenario 1: Raffle (50 tickets at $2)
$s1_intro = 'A raffle sells <b>50 tickets</b> at <b>$2.00</b> each. The prize distribution (net gain after cost) is shown below:'
$s1_xdesc = 'net gain per ticket (in dollars)'
$s1_q     = 'What is the expected net gain per ticket?'
$s1_x0 = -2
$s1_x1 = 0
$s1_x2 = 8
$s1_x3 = 48
$s1_p0 = 0.84
$s1_p1 = 0.10
$s1_p2 = 0.04
$s1_p3 = 0.02
$s1_ev = round($s1_x0*$s1_p0 + $s1_x1*$s1_p1 + $s1_x2*$s1_p2 + $s1_x3*$s1_p3, 2)

// Scenario 2: Slot machine ($1 to play)
$s2_intro = 'A slot machine costs <b>$1.00</b> to play. The net gain X (prize minus $1 cost) has the following probability distribution:'
$s2_xdesc = 'net gain per play (in dollars)'
$s2_q     = 'What is the expected net gain per play?'
$s2_x0 = -1
$s2_x1 = 0
$s2_x2 = 3
$s2_x3 = 13
$s2_p0 = 0.60
$s2_p1 = 0.25
$s2_p2 = 0.10
$s2_p3 = 0.05
$s2_ev = round($s2_x0*$s2_p0 + $s2_x1*$s2_p1 + $s2_x2*$s2_p2 + $s2_x3*$s2_p3, 2)

// Scenario 3: Equipment repair costs
$s3_intro = 'A small business estimates its annual repair costs for a key machine. Let X = repair cost (in dollars) for the year. The probability distribution is shown below:'
$s3_xdesc = 'annual repair cost (in dollars)'
$s3_q     = 'What is the expected annual repair cost?'
$s3_x0 = 0
$s3_x1 = 500
$s3_x2 = 2000
$s3_x3 = 8000
$s3_p0 = 0.70
$s3_p1 = 0.20
$s3_p2 = 0.08
$s3_p3 = 0.02
$s3_ev = round($s3_x0*$s3_p0 + $s3_x1*$s3_p1 + $s3_x2*$s3_p2 + $s3_x3*$s3_p3, 2)

// Select scenario
$ctx_intro = $s0_intro if ($ci == 0)
$ctx_intro = $s1_intro if ($ci == 1)
$ctx_intro = $s2_intro if ($ci == 2)
$ctx_intro = $s3_intro if ($ci == 3)

$ctx_xdesc = $s0_xdesc if ($ci == 0)
$ctx_xdesc = $s1_xdesc if ($ci == 1)
$ctx_xdesc = $s2_xdesc if ($ci == 2)
$ctx_xdesc = $s3_xdesc if ($ci == 3)

$ctx_q = $s0_q if ($ci == 0)
$ctx_q = $s1_q if ($ci == 1)
$ctx_q = $s2_q if ($ci == 2)
$ctx_q = $s3_q if ($ci == 3)

$x0 = $s0_x0 if ($ci == 0)
$x0 = $s1_x0 if ($ci == 1)
$x0 = $s2_x0 if ($ci == 2)
$x0 = $s3_x0 if ($ci == 3)

$x1 = $s0_x1 if ($ci == 0)
$x1 = $s1_x1 if ($ci == 1)
$x1 = $s2_x1 if ($ci == 2)
$x1 = $s3_x1 if ($ci == 3)

$x2 = $s0_x2 if ($ci == 0)
$x2 = $s1_x2 if ($ci == 1)
$x2 = $s2_x2 if ($ci == 2)
$x2 = $s3_x2 if ($ci == 3)

$x3 = $s0_x3 if ($ci == 0)
$x3 = $s1_x3 if ($ci == 1)
$x3 = $s2_x3 if ($ci == 2)
$x3 = $s3_x3 if ($ci == 3)

$p0 = $s0_p0 if ($ci == 0)
$p0 = $s1_p0 if ($ci == 1)
$p0 = $s2_p0 if ($ci == 2)
$p0 = $s3_p0 if ($ci == 3)

$p1 = $s0_p1 if ($ci == 0)
$p1 = $s1_p1 if ($ci == 1)
$p1 = $s2_p1 if ($ci == 2)
$p1 = $s3_p1 if ($ci == 3)

$p2 = $s0_p2 if ($ci == 0)
$p2 = $s1_p2 if ($ci == 1)
$p2 = $s2_p2 if ($ci == 2)
$p2 = $s3_p2 if ($ci == 3)

$p3 = $s0_p3 if ($ci == 0)
$p3 = $s1_p3 if ($ci == 1)
$p3 = $s2_p3 if ($ci == 2)
$p3 = $s3_p3 if ($ci == 3)

$ev = $s0_ev if ($ci == 0)
$ev = $s1_ev if ($ci == 1)
$ev = $s2_ev if ($ci == 2)
$ev = $s3_ev if ($ci == 3)

// x*P(x) products for solution table
$xp0 = round($x0 * $p0, 4)
$xp1 = round($x1 * $p1, 4)
$xp2 = round($x2 * $p2, 4)
$xp3 = round($x3 * $p3, 4)

$answer[0] = $ev
$anstypes = array("number")
$abstolerance = 0.02
$reqdecimals = "r2"
$ansprompt[0] = "E(X) = $"

$css_block = '
<style>
    .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
    .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
    .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; border:none; }
    .rubric-container details[open] summary { box-shadow: inset 0 -1px 0 #ccc; }
    .rubric-container summary::-webkit-details-marker { display:none; }
    .arrow-open { display:none; }
    .rubric-container details[open] .arrow-closed { display:none; }
    .rubric-container details[open] .arrow-open { display:inline; }
    .rubric-content { overflow:hidden; max-height:0; opacity:0; transition:max-height 300ms ease-out, opacity 300ms ease-out, padding 200ms ease-out; margin-top:0; background:#fafafa; box-sizing:border-box; padding:0 0.75em; }
    .rubric-container details[open] .rubric-content { max-height:2000px; opacity:1; padding:0.75em; }
    .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
    .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
    .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; user-select:text; }
    .row-colored { background:#fff9ea; }
    .col-header { width:25%; border-top-left-radius:8px; }
    .col-check { border-top-right-radius:8px; }
    .col-cat-bot { border-bottom-left-radius:8px; }
    .col-check-bot { border-bottom-right-radius:8px; }
</style>
<script>
document.addEventListener("DOMContentLoaded", function() {
  var details = document.querySelectorAll(".rubric-container details");
  details.forEach(function(det) {
    var content = det.querySelector(".rubric-content");
    det.addEventListener("toggle", function() {
      if (det.open) {
        content.style.maxHeight = content.scrollHeight + "px";
        content.style.opacity = "1";
      } else {
        content.style.maxHeight = content.scrollHeight + "px";
        content.offsetHeight;
        content.style.maxHeight = "0";
        content.style.opacity = "0";
      }
    });
    content.addEventListener("transitionend", function() {
      if (!det.open) content.style.maxHeight = null;
    });
  });
});
</script>'

$solutionguide = $css_block . '
<div class="rubric-container">
  <details>
    <summary>
      <span class="arrow-closed">▸</span><span class="arrow-open">▾</span>
      Step-by-Step Solution
    </summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr>
            <th class="col-header">Step</th>
            <th class="col-check">Work</th>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Formula</b></td>
            <td>
              E(X) = &sum; x &middot; P(x)<br>
              Multiply each value of X by its probability, then add all products.
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Products</b></td>
            <td>
              <table style="border-collapse:collapse; width:100%; margin:6px 0; font-size:small;">
                <tr>
                  <th style="border:1px solid #ccc; padding:5px 10px; background:#f2f2f2;">x</th>
                  <th style="border:1px solid #ccc; padding:5px 10px; background:#f2f2f2;">P(x)</th>
                  <th style="border:1px solid #ccc; padding:5px 10px; background:#f2f2f2;">x &middot; P(x)</th>
                </tr>
                <tr><td style="border:1px solid #ccc;padding:5px 10px;text-align:center;">$' . $x0 . '</td><td style="border:1px solid #ccc;padding:5px 10px;text-align:center;">' . $p0 . '</td><td style="border:1px solid #ccc;padding:5px 10px;text-align:center;">' . $xp0 . '</td></tr>
                <tr class="row-colored"><td style="border:1px solid #ccc;padding:5px 10px;text-align:center;">$' . $x1 . '</td><td style="border:1px solid #ccc;padding:5px 10px;text-align:center;">' . $p1 . '</td><td style="border:1px solid #ccc;padding:5px 10px;text-align:center;">' . $xp1 . '</td></tr>
                <tr><td style="border:1px solid #ccc;padding:5px 10px;text-align:center;">$' . $x2 . '</td><td style="border:1px solid #ccc;padding:5px 10px;text-align:center;">' . $p2 . '</td><td style="border:1px solid #ccc;padding:5px 10px;text-align:center;">' . $xp2 . '</td></tr>
                <tr class="row-colored"><td style="border:1px solid #ccc;padding:5px 10px;text-align:center;">$' . $x3 . '</td><td style="border:1px solid #ccc;padding:5px 10px;text-align:center;">' . $p3 . '</td><td style="border:1px solid #ccc;padding:5px 10px;text-align:center;">' . $xp3 . '</td></tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Answer</b></td>
            <td class="col-check-bot">
              E(X) = ' . $xp0 . ' + ' . $xp1 . ' + ' . $xp2 . ' + ' . $xp3 . '
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>E(X) = $' . $ev . '</b>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:Arial; font-size:medium; line-height:1.6;">

<p><b>Expected Value</b></p>
<p>$ctx_intro</p>

<table style="border-collapse:collapse; margin:10px 0; font-size:medium;">
  <tr>
    <th style="border:1px solid #ccc; padding:8px 14px; background:#f2f2f2;">X ($ctx_xdesc)</th>
    <th style="border:1px solid #ccc; padding:8px 14px; background:#f2f2f2;">P(X = x)</th>
  </tr>
  <tr><td style="border:1px solid #ccc; padding:8px 14px; text-align:center;">$$x0</td><td style="border:1px solid #ccc; padding:8px 14px; text-align:center;">$p0</td></tr>
  <tr style="background:#fff9ea;"><td style="border:1px solid #ccc; padding:8px 14px; text-align:center;">$$x1</td><td style="border:1px solid #ccc; padding:8px 14px; text-align:center;">$p1</td></tr>
  <tr><td style="border:1px solid #ccc; padding:8px 14px; text-align:center;">$$x2</td><td style="border:1px solid #ccc; padding:8px 14px; text-align:center;">$p2</td></tr>
  <tr style="background:#fff9ea;"><td style="border:1px solid #ccc; padding:8px 14px; text-align:center;">$$x3</td><td style="border:1px solid #ccc; padding:8px 14px; text-align:center;">$p3</td></tr>
</table>

<p>$ctx_q Round your answer to 2 decimal places.</p>
<p>$ansprompt[0] $answerbox[0]</p>

</div>

// === ANSWER ===

$solutionguide
