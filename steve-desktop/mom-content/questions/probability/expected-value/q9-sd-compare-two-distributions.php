// === NAME - DESCRIPTION: Same Mean, Different Spread - Two distributions with equal mu but different sigma ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Three pairs of distributions sharing the SAME mean but different standard deviations.
// Scenario pairs (verified):
//   A: x=1,2,3,4,5 p=0.2,0.2,0.2,0.2,0.2  mu=3.0  sigma=1.4142  (tight)
//   B: x=1,3,5     p=0.25,0.5,0.25         mu=3.0  sigma=1.4142  -> same, NOT valid pair; use:
//   Pair 0: A x=1,2,3,4 p=0.1,0.4,0.4,0.1  mu=2.5  sigma=0.8062 | B x=0,5 p=0.5,0.5 mu=2.5 sigma=2.5
//   Pair 1: A x=0,2,4,6 p=0.25,0.25,0.25,0.25 mu=3 sigma=2.2361 | B x=1,2,3,4,5 p=0.2 each mu=3 sigma=1.4142
//   Pair 2: A x=0,1,2,3 p=0.25 each        mu=1.5 sigma=1.1180 | B x=0,3 p=0.5,0.5 mu=1.5 sigma=1.5
// Parts: (a) sigma_A, (b) sigma_B, (c) choices - which clusters more tightly (smaller sigma).
// Invariant: A and B share mu to 4dp; sigmas differ; (c) names the smaller sigma.

$anstypes = array("numfunc", "numfunc", "choices")

$x0a = array(1, 0, 0)
$x1a = array(2, 2, 1)
$x2a = array(3, 4, 2)
$x3a = array(4, 6, 3)

$p0a = array("0.1", "0.25", "0.25")
$p1a = array("0.4", "0.25", "0.25")
$p2a = array("0.4", "0.25", "0.25")
$p3a = array("0.1", "0.25", "0.25")

$x0b = array(0, 1, 0)
$x1b = array(1, 2, 1)
$x2b = array(4, 4, 2)
$x3b = array(5, 5, 3)

$p0b = array("0.25", "0.2", "0.1")
$p1b = array("0.25", "0.3", "0.4")
$p2b = array("0.25", "0.3", "0.4")
$p3b = array("0.25", "0.2", "0.1")

$musA = array(2.5, 3.0, 1.5)
$sigA = array(0.8062, 2.2361, 1.1180)
$musB = array(2.5, 3.0, 1.5)
$sigB = array(2.0616, 1.4832, 0.8062)

// choices answer: 0 = Distribution A is tighter, 1 = Distribution B is tighter
$tighter = array(0, 1, 1)

$ctxsA = array(
  "Distribution A: a spinner that lands on 1, 2, 3 or 4 with probabilities 0.1, 0.4, 0.4, 0.1. Let `X` be the number from one spin.",
  "Distribution A: four equally likely outcomes 0, 2, 4, 6. Let `X` be the value from one draw.",
  "Distribution A: four equally likely outcomes 0, 1, 2, 3. Let `X` be the value from one draw."
)

$ctxsB = array(
  "Distribution B: four equally likely outcomes 0, 1, 4, 5. Let `X` be the value from one draw.",
  "Distribution B: five equally likely outcomes 1, 2, 3, 4, 5. Let `X` be the value from one draw.",
  "Distribution B: a spinner that lands on 0, 1, 2 or 3 with probabilities 0.1, 0.4, 0.4, 0.1. Let `X` be the number from one spin."
)

$tighterLabels = array("A", "B", "B")
$muDisps = array("2.5", "3.0", "1.5")

$picked = jointrandfrom($x0a, $x1a, $x2a, $x3a, $p0a, $p1a, $p2a, $p3a, $x0b, $x1b, $x2b, $x3b, $p0b, $p1b, $p2b, $p3b, $sigA, $sigB, $tighter, $ctxsA, $ctxsB, $tighterLabels, $muDisps)
$a0 = $picked[0]
$a1 = $picked[1]
$a2 = $picked[2]
$a3 = $picked[3]
$ap0 = $picked[4]
$ap1 = $picked[5]
$ap2 = $picked[6]
$ap3 = $picked[7]
$b0 = $picked[8]
$b1 = $picked[9]
$b2 = $picked[10]
$b3 = $picked[11]
$bp0 = $picked[12]
$bp1 = $picked[13]
$bp2 = $picked[14]
$bp3 = $picked[15]
$answer[0] = $picked[16]
$answer[1] = $picked[17]
$answer[2] = $picked[18]
$ctxA = $picked[19]
$ctxB = $picked[20]
$tighterLabel = $picked[21]
$muDisp = $picked[22]
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005

$questions[2] = array(
  "Distribution A, because it has the smaller standard deviation",
  "Distribution B, because it has the smaller standard deviation",
  "They are equally spread out, because they have the same mean",
  "Distribution A, because it has the larger standard deviation"
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
  .term-label { font-weight:700; color:#1865f2; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><span class="term-label">The point.</span> Both distributions have mean <b>' . $muDisp . '</b>: the expected value says where the distribution balances. It says nothing about how spread out it is.</p>
      <p><span class="term-label">Part (a): Distribution A.</span> Computing the deviation column and taking the square root gives <b>sigma = ' . $answer[0] . '</b>.</p>
      <p><span class="term-label">Part (b): Distribution B.</span> Same recipe, different spread: <b>sigma = ' . $answer[1] . '</b>.</p>
      <p><span class="term-label">Part (c): which is tighter.</span> The smaller standard deviation belongs to the distribution whose outcomes cluster more tightly around the mean. Here that is Distribution ' . $tighterLabel . '.</p>
      <p>Two distributions can share a mean and behave completely differently: one clustered tightly around it, one throwing outcomes far to either side. The standard deviation is the number that measures the spread.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;"><b>Distribution A.</b> $ctxA</p>
    <table style="border-collapse:collapse; margin:0 auto; font-size:16px;">
      <tr>
        <th style="border:1px solid #21242c; padding:6px 16px; background:#f0f4ff;">`x`</th>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$a0</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$a1</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$a2</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$a3</td>
      </tr>
      <tr>
        <th style="border:1px solid #21242c; padding:6px 16px; background:#f0f4ff;">`P(X = x)`</th>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$ap0</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$ap1</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$ap2</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$ap3</td>
      </tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;"><b>Distribution B.</b> $ctxB</p>
    <table style="border-collapse:collapse; margin:0 auto; font-size:16px;">
      <tr>
        <th style="border:1px solid #21242c; padding:6px 16px; background:#f0f4ff;">`x`</th>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$b0</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$b1</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$b2</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$b3</td>
      </tr>
      <tr>
        <th style="border:1px solid #21242c; padding:6px 16px; background:#f0f4ff;">`P(X = x)`</th>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$bp0</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$bp1</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$bp2</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$bp3</td>
      </tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Compute the standard deviation of Distribution A.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Compute the standard deviation of Distribution B.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which distribution has outcomes clustered more tightly around the mean?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
