// === NAME - DESCRIPTION: Binomial Mean and Standard Deviation - mu = np and sigma = sqrt(npq) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Four scenarios; mu = np clean, sigma = sqrt(npq) to 4dp (verified):
//   S0: n=50, p=0.70  mu=35.0  sigma=sqrt(10.5)=3.2404
//   S1: n=30, p=0.32  mu=9.6   sigma=sqrt(6.528)=2.5550
//   S2: n=100,p=0.015 mu=1.5   sigma=sqrt(1.4775)=1.2155
//   S3: n=20, p=0.41  mu=8.2   sigma=sqrt(4.838)=2.1995
// Parts: (a) mu, (b) sigma.

$anstypes = array("numfunc", "numfunc")

$ctxs = array(
  "In a statistics class of 50 students, 70% do their homework on time. Let `X` be the number who do homework on time.",
  "In a random sample of 30 students, 32% participate in a community volunteer program. Let `X` be the number who participate.",
  "The lifetime risk of developing a certain condition is about 1.5%. A random sample of 100 people is chosen. Let `X` be the number who develop it.",
  "About 41% of adult workers have only a high school diploma. A random sample of 20 workers is chosen. Let `X` be the number who have only a high school diploma."
)

$ns = array(50, 30, 100, 20)
$ps = array(0.7, 0.32, 0.015, 0.41)
$qs = array(0.3, 0.68, 0.985, 0.59)
$mus = array(35.0, 9.6, 1.5, 8.2)
$sigmas = array(3.2404, 2.5550, 1.2155, 2.1995)

$i = rand(0, 3)
$ctx = $ctxs[$i]
$n = $ns[$i]
$p = $ps[$i]
$q = $qs[$i]
$mu = $mus[$i]
$sigma = $sigmas[$i]

$answer[0] = $mu
$answer[1] = $sigma
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005

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
      <p><span class="term-label">The shortcuts.</span> For a binomial `X ~ B(n, p)`, the whole `x * P(x)` column collapses into one product for the mean, and the variance is `npq`:</p>
      <p><span class="term-label">Part (a) &mdash; mu.</span> `mu = np = ' . $n . ' * ' . $p . ' = ` <b>' . $mu . '</b>.</p>
      <p><span class="term-label">Part (b) &mdash; sigma.</span> `sigma = sqrt(npq) = sqrt(' . $n . ' * ' . $p . ' * ' . $q . ') = ` <b>' . $sigma . '</b>.</p>
      <p>The mean can be a fraction and that is fine &mdash; it is the long-run average over many samples, and averages land between whole numbers all the time.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx Use the binomial shortcuts to find the mean and standard deviation.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the mean `mu = np`.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find the standard deviation `sigma = sqrt(npq)`.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
