// === NAME - DESCRIPTION: Binomial Notation - Read n, p and q from a scenario and write X ~ B(n, p) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Four scenarios stating n and p in prose (clean p). Parts: (a) n, (b) p, (c) q = 1 - p.
// Invariant: q = 1 - p exactly on every seed; the guide writes X ~ B(n, p).

$anstypes = array("number", "numfunc", "numfunc")

$ctxs = array(
  "In a statistics class of 50 students, 70% do their homework on time. Let `X` be the number who do homework on time.",
  "A biased coin lands heads with probability 0.25. It is flipped 20 times. Let `X` be the number of heads.",
  "In a random sample of 30 students, 32% participate in a community volunteer program. Let `X` be the number who participate.",
  "A basketball player makes 60% of her free throws. She shoots 12 free throws. Let `X` be the number she makes."
)

$ns = array(50, 20, 30, 12)
$ps = array(0.7, 0.25, 0.32, 0.6)
$qs = array(0.3, 0.75, 0.68, 0.4)

$i = rand(0, 3)
$ctx = $ctxs[$i]
$n = $ns[$i]
$p = $ps[$i]
$q = $qs[$i]

$answer[0] = $n
$answer[1] = $p
$answer[2] = $q
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
      <p><span class="term-label">The parameters.</span> `n` is the number of trials, `p` is the probability of a success on one trial, and `q = 1 - p` is the probability of a failure.</p>
      <p><span class="term-label">Part (a) &mdash; n.</span> The scenario gives <b>' . $n . '</b> trials.</p>
      <p><span class="term-label">Part (b) &mdash; p.</span> The success probability is <b>' . $p . '</b>.</p>
      <p><span class="term-label">Part (c) &mdash; q.</span> `q = 1 - p = 1 - ' . $p . ' = ` <b>' . $q . '</b>.</p>
      <p>So `X ~ B(' . $n . ', ' . $p . ')` &mdash; read "`X` is a random variable with a binomial distribution", which tells a reader everything they need to compute any probability about `X`.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx Find the parameters of the binomial distribution.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> How many trials are there, `n`?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the probability of a success on one trial, `p`?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is the probability of a failure on one trial, `q = 1 - p`?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
