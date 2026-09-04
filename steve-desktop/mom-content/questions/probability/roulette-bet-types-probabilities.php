// === NAME - DESCRIPTION: Probability of Seven Roulette Bets - count the pockets each bet covers and divide by 38, across seven bets of different sizes ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number", "number", "number", "number", "number")

// The wheel is fixed, so randomization rotates WHICH bet lands in which part. Every student
// answers all seven bets, but part (a) is a different bet, and a different number, each time.
// Rotation by a random offset rather than a shuffle: shuffle() is rejected by the parser.
$bi = rand(0, 6)

$betNames = array(
  "two adjoining lines (a double street), as in 1-2-3-4-5-6",
  "three numbers in a line (a street), as in 1-2-3",
  "a single number",
  "four numbers meeting at a corner (a square), as in 10-11-13-14",
  "two adjoining numbers (a split), as in 10-11",
  "the top line, 0-00-1-2-3",
  "a three-number bet that includes a green pocket, as in 0-1-2"
)
$betK = array(6, 3, 1, 4, 2, 5, 3)

$i0 = ($bi + 0) % 7
$i1 = ($bi + 1) % 7
$i2 = ($bi + 2) % 7
$i3 = ($bi + 3) % 7
$i4 = ($bi + 4) % 7
$i5 = ($bi + 5) % 7
$i6 = ($bi + 6) % 7

$n0 = $betNames[$i0]
$n1 = $betNames[$i1]
$n2 = $betNames[$i2]
$n3 = $betNames[$i3]
$n4 = $betNames[$i4]
$n5 = $betNames[$i5]
$n6 = $betNames[$i6]

$k0 = $betK[$i0]
$k1 = $betK[$i1]
$k2 = $betK[$i2]
$k3 = $betK[$i3]
$k4 = $betK[$i4]
$k5 = $betK[$i5]
$k6 = $betK[$i6]

$answer[0] = round($k0 / 38, 4)
$abstolerance[0] = 0.00011
$answer[1] = round($k1 / 38, 4)
$abstolerance[1] = 0.00011
$answer[2] = round($k2 / 38, 4)
$abstolerance[2] = 0.00011
$answer[3] = round($k3 / 38, 4)
$abstolerance[3] = 0.00011
$answer[4] = round($k4 / 38, 4)
$abstolerance[4] = 0.00011
$answer[5] = round($k5 / 38, 4)
$abstolerance[5] = 0.00011
$answer[6] = round($k6 / 38, 4)
$abstolerance[6] = 0.00011

$a0 = $answer[0]
$a1 = $answer[1]
$a2 = $answer[2]
$a3 = $answer[3]
$a4 = $answer[4]
$a5 = $answer[5]
$a6 = $answer[6]

$solutionguide = '
<div style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <p><b>Every bet is the same computation: count the pockets it covers, then divide by 38.</b> The pockets are equally likely, so a bet covering `k` of them wins with probability `k/38`. Nothing here needs the multiplication or addition rule: the whole exercise is careful counting.</p>
  <table style="border-collapse:collapse; margin:12px 0; font-size:15px;">
    <tr style="background:#e8f0fe;">
      <th style="border:1px solid #c8d4ea; padding:7px 12px; text-align:left;">Bet</th>
      <th style="border:1px solid #c8d4ea; padding:7px 12px;">Pockets covered</th>
      <th style="border:1px solid #c8d4ea; padding:7px 12px;">Probability</th>
    </tr>
    <tr><td style="border:1px solid #d9dee8; padding:7px 12px;">a. ' . $n0 . '</td><td style="border:1px solid #d9dee8; padding:7px 12px; text-align:center;">' . $k0 . '</td><td style="border:1px solid #d9dee8; padding:7px 12px; text-align:center;">' . $k0 . '/38 &approx; <b>' . $a0 . '</b></td></tr>
    <tr><td style="border:1px solid #d9dee8; padding:7px 12px;">b. ' . $n1 . '</td><td style="border:1px solid #d9dee8; padding:7px 12px; text-align:center;">' . $k1 . '</td><td style="border:1px solid #d9dee8; padding:7px 12px; text-align:center;">' . $k1 . '/38 &approx; <b>' . $a1 . '</b></td></tr>
    <tr><td style="border:1px solid #d9dee8; padding:7px 12px;">c. ' . $n2 . '</td><td style="border:1px solid #d9dee8; padding:7px 12px; text-align:center;">' . $k2 . '</td><td style="border:1px solid #d9dee8; padding:7px 12px; text-align:center;">' . $k2 . '/38 &approx; <b>' . $a2 . '</b></td></tr>
    <tr><td style="border:1px solid #d9dee8; padding:7px 12px;">d. ' . $n3 . '</td><td style="border:1px solid #d9dee8; padding:7px 12px; text-align:center;">' . $k3 . '</td><td style="border:1px solid #d9dee8; padding:7px 12px; text-align:center;">' . $k3 . '/38 &approx; <b>' . $a3 . '</b></td></tr>
    <tr><td style="border:1px solid #d9dee8; padding:7px 12px;">e. ' . $n4 . '</td><td style="border:1px solid #d9dee8; padding:7px 12px; text-align:center;">' . $k4 . '</td><td style="border:1px solid #d9dee8; padding:7px 12px; text-align:center;">' . $k4 . '/38 &approx; <b>' . $a4 . '</b></td></tr>
    <tr><td style="border:1px solid #d9dee8; padding:7px 12px;">f. ' . $n5 . '</td><td style="border:1px solid #d9dee8; padding:7px 12px; text-align:center;">' . $k5 . '</td><td style="border:1px solid #d9dee8; padding:7px 12px; text-align:center;">' . $k5 . '/38 &approx; <b>' . $a5 . '</b></td></tr>
    <tr><td style="border:1px solid #d9dee8; padding:7px 12px;">g. ' . $n6 . '</td><td style="border:1px solid #d9dee8; padding:7px 12px; text-align:center;">' . $k6 . '</td><td style="border:1px solid #d9dee8; padding:7px 12px; text-align:center;">' . $k6 . '/38 &approx; <b>' . $a6 . '</b></td></tr>
  </table>
  <p><b>Two counting traps.</b> The top line covers <i>five</i> pockets, not four: 0 and 00 are two separate pockets. And a three-number bet containing a green pocket is still a three-pocket bet: green pockets count exactly like numbered ones, because every pocket is equally likely.</p>
  <p><b>Notice the pattern.</b> The probability rises in exact proportion to how many pockets you cover, which is why the casino adjusts the <i>payout</i> rather than the odds to keep its edge. A single-number bet wins about 1 spin in 38 and pays 35 to 1: the gap between 37 and 35 is the house.</p>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0;">The roulette table has <b>38</b> pockets: 0 and 00, which are green, together with 1 through 36, of which eighteen are red and eighteen are black. Every pocket is equally likely on a single spin.</p>
    <p style="margin:12px 0 0 0;">Compute the probability of <b>winning</b> each of the following bets. Round each answer to <b>four decimal places</b>.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">a.</span> Betting on $n0. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">b.</span> Betting on $n1. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">c.</span> Betting on $n2. $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">d.</span> Betting on $n3. $answerbox[3]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">e.</span> Betting on $n4. $answerbox[4]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">f.</span> Betting on $n5. $answerbox[5]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">g.</span> Betting on $n6. $answerbox[6]
  </div>
</div>

// === ANSWER ===

$solutionguide
