// === NAME - DESCRIPTION: Uniform Mean and Standard Deviation - mu = (a+b)/2 and sigma = sqrt((b-a)^2/12) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Four U(a,b) distributions; mu clean, sigma to 4dp (verified):
//   S0: U(0, 20)  mu=10,    sigma=sqrt(400/12)=5.7735
//   S1: U(0, 10)  mu=5,     sigma=sqrt(100/12)=2.8868
//   S2: U(2, 14)  mu=8,     sigma=sqrt(144/12)=3.4641
//   S3: U(1, 9)   mu=5,     sigma=sqrt(64/12)=2.3094
// Parts: (a) mu, (b) sigma.

$anstypes = array("numfunc", "numfunc")

$as = array(0, 0, 2, 1)
$bs = array(20, 10, 14, 9)
$mus = array(10, 5, 8, 5)
$sigmas = array(5.7735, 2.8868, 3.4641, 2.3094)

$i = rand(0, 3)
$a = $as[$i]
$b = $bs[$i]

$answer[0] = $mus[$i]
$answer[1] = $sigmas[$i]
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
      <p><span class="term-label">The endpoints are the whole story.</span> Every number for a uniform distribution comes out of `a = ' . $a . '` and `b = ' . $b . '`, and nothing else.</p>
      <p><span class="term-label">Part (a) &mdash; the mean.</span> `mu = (a + b)/2 = (' . $a . ' + ' . $b . ')/2 = ` <b>' . $answer[0] . '</b></p>
      <p><span class="term-label">Part (b) &mdash; the standard deviation.</span> `sigma = sqrt((b - a)^2 / 12) = sqrt((' . ($b - $a) . ')^2 / 12) = ` <b>' . $answer[1] . '</b></p>
      <p>Notice `sigma` is a good deal smaller than the half-width of the interval. That is the uniform shape showing up in a number: the values are spread evenly rather than piled at the two ends, so a typical value sits closer to the center than the interval width suggests.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Suppose `X ~ U($a, $b)` — a continuous uniform distribution over the interval `[$a, $b]`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the mean `mu`.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find the standard deviation `sigma`.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
