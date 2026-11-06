// === NAME - DESCRIPTION: Find p from the Mean - Given n and the mean, work backward to p, then find sigma and P(X = 1) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Five binomial scenarios where n and mu are given. Parts: (a) numfunc - p = mu / n; (b) numfunc - sigma = sqrt(npq);
// (c) numfunc - P(X = 1) = n p q^(n-1). Invariant: p = mu / n and values rounded to 4 places in the generator.

$anstypes = array("numfunc", "numfunc", "numfunc")

$ns = array(5, 8, 6, 10, 4)
$mus = array(2, 2, 1.8, 3, 1.6)
$ps = array(0.4, 0.25, 0.3, 0.3, 0.4)
$sigmas = array(1.0954, 1.2247, 1.1225, 1.4491, 0.9798)
$ones = array(0.2592, 0.267, 0.3025, 0.1211, 0.3456)
$ctxs = array("A player takes 5 shots, and the number she makes follows a binomial model. On average she makes 2 of the 5.", "A machine tests 8 parts, and the number that fail follows a binomial model. On average 2 of the 8 fail.", "A student answers 6 true-or-false style questions, and the number answered correctly follows a binomial model. On average she gets 1.8 right.", "A clinic sees 10 patients in a morning, and the number who need a follow-up visit follows a binomial model. On average 3 of the 10 need one.", "A group of 4 friends each tries a game, and the number who win follows a binomial model. On average 1.6 of the 4 win.")

$picked = jointrandfrom($ns, $mus, $ps, $sigmas, $ones, $ctxs)
$n = $picked[0]
$mu = $picked[1]
$answer[0] = $picked[2]
$answer[1] = $picked[3]
$answer[2] = $picked[4]
$ctx = $picked[5]
$q = 1 - $answer[0]

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
      <p><span class="term-label">Part (a).</span> The mean of a binomial is `mu = np`, so `p = mu / n = ' . $mu . ' / ' . $n . ' = ` <b>' . $answer[0] . '</b>.</p>
      <p><span class="term-label">Part (b).</span> `q = 1 - p = ' . $q . '`, so `sigma = sqrt(npq) = sqrt(' . $n . ' x ' . $answer[0] . ' x ' . $q . ') = ` <b>' . $answer[1] . '</b>.</p>
      <p><span class="term-label">Part (c).</span> `P(X = 1) = n p q^(n - 1) = ' . $n . ' x ' . $answer[0] . ' x ' . $q . '^' . ($n - 1) . ' = ` <b>' . $answer[2] . '</b>.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx This is a binomial experiment with `n = $n` and mean `mu = $mu`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is `p`?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the standard deviation `sigma`? Round to 4 decimals.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is `P(X = 1)`? Round to 4 decimals.
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
