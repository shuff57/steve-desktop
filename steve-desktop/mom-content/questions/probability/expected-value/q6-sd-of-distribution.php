// === NAME - DESCRIPTION: Standard Deviation of a Distribution - Compute mu, Var(X) and sigma from the deviation column ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Four legitimate distributions with clean mu and Var (sigma = sqrt(Var) to 4dp):
//   A: x=1,2,3,4  p=0.2,0.3,0.4,0.1  mu=2.4   Var=0.84  sigma=0.9165
//   B: x=0,1,2,3  p=0.25 x4          mu=1.5   Var=1.25  sigma=1.1180
//   C: x=3,5,7,9  p=0.4,0.3,0.2,0.1  mu=5.0   Var=4.0   sigma=2.0000
//   D: x=0,2,4,6  p=0.2,0.3,0.3,0.2  mu=3.0   Var=4.2   sigma=2.0494
// Parts: (a) mu, (b) Var(X), (c) sigma = sqrt(Var).
// Invariant: mu and Var are the precomputed weighted sums; sigma = sqrt(Var) on every seed.

$anstypes = array("numfunc", "numfunc", "numfunc")

$x0s = array(1, 0, 3, 0)
$x1s = array(2, 1, 5, 2)
$x2s = array(3, 2, 7, 4)
$x3s = array(4, 3, 9, 6)

$p0s = array("0.2", "0.25", "0.4", "0.2")
$p1s = array("0.3", "0.25", "0.3", "0.3")
$p2s = array("0.4", "0.25", "0.2", "0.3")
$p3s = array("0.1", "0.25", "0.1", "0.2")

$mus = array(2.4, 1.5, 5.0, 3.0)
$vars = array(0.84, 1.25, 4.0, 4.2)
$sigmas = array(0.9165, 1.1180, 2.0000, 2.0494)

$muCalcs = array(
  "(1)(0.2) + (2)(0.3) + (3)(0.4) + (4)(0.1) = 0.2 + 0.6 + 1.2 + 0.4",
  "(0)(0.25) + (1)(0.25) + (2)(0.25) + (3)(0.25) = 0 + 0.25 + 0.5 + 0.75",
  "(3)(0.4) + (5)(0.3) + (7)(0.2) + (9)(0.1) = 1.2 + 1.5 + 1.4 + 0.9",
  "(0)(0.2) + (2)(0.3) + (4)(0.3) + (6)(0.2) = 0 + 0.6 + 1.2 + 1.2"
)

$contexts = array(
  "A bowling alley awards tickets per game according to the distribution below. Let `X` be the number of tickets from one game.",
  "A fair spinner awards points per spin according to the distribution below. Let `X` be the number of points from one spin.",
  "A vending machine's daily snack sales follow the distribution below. Let `X` be the number of snacks sold in a day.",
  "A pond game awards fish per play according to the distribution below. Let `X` be the number of fish caught in one play."
)

$picked = jointrandfrom($x0s, $x1s, $x2s, $x3s, $p0s, $p1s, $p2s, $p3s, $mus, $vars, $sigmas, $muCalcs, $contexts)
$x0 = $picked[0]
$x1 = $picked[1]
$x2 = $picked[2]
$x3 = $picked[3]
$pd0 = $picked[4]
$pd1 = $picked[5]
$pd2 = $picked[6]
$pd3 = $picked[7]
$mu = $picked[8]
$var = $picked[9]
$sigma = $picked[10]
$muCalc = $picked[11]
$ctx = $picked[12]

$answer[0] = $mu
$answer[1] = $var
$answer[2] = $sigma
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005
$abstolerance[2] = 0.005

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
      <p><span class="term-label">Part (a): the expected value.</span> `E(X) = sum x cdot P(X = x)`, one row at a time:</p>
      <p>`mu = ` ' . $muCalc . ' = <b>' . $mu . '</b></p>
      <p><span class="term-label">Part (b): the variance.</span> Square each deviation from `mu`, weight it by its probability, and add:</p>
      <p>`Var(X) = sum (x - mu)^2 cdot P(X = x) = ` <b>' . $var . '</b></p>
      <p><span class="term-label">Part (c): the standard deviation.</span> Take the square root of the variance:</p>
      <p>`sigma = sqrt(' . $var . ') = ` <b>' . $sigma . '</b></p>
      <p>Values above the mean give positive deviations and values below give negative ones, and if you just added them they would cancel to zero every time. Squaring makes every deviation count as distance, regardless of direction; the square root at the end puts the answer back into the original units.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">$ctx</p>
    <table style="border-collapse:collapse; margin:0 auto; font-size:16px;">
      <tr>
        <th style="border:1px solid #21242c; padding:6px 16px; background:#f0f4ff;">`x`</th>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$x0</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$x1</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$x2</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$x3</td>
      </tr>
      <tr>
        <th style="border:1px solid #21242c; padding:6px 16px; background:#f0f4ff;">`P(X = x)`</th>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$pd0</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$pd1</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$pd2</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$pd3</td>
      </tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Compute the expected value `mu = E(X)`.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Compute the variance `Var(X) = sum (x - mu)^2 cdot P(X = x)`.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Compute the standard deviation `sigma = sqrt(Var(X))`.
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
