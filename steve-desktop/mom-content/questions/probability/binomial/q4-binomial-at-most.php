// === NAME - DESCRIPTION: Binomial At Most - P(X <= k) as a running total ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Four binomial scenarios; P(X <= k) precomputed by summing P(X = x) for x = 0..k (verified):
//   S0: n=10, p=0.25, k=3   P = 0.0563+0.1877+0.2816+0.2503 = 0.7759
//   S1: n=8,  p=0.5,  k=2   P = 0.0039+0.0313+0.1094 = 0.1446
//   S2: n=20, p=0.3,  k=4   P = 0.0008+0.0068+0.0278+0.0716+0.1304 = 0.2374
//   S3: n=12, p=0.6,  k=8   P = 0.0000+0.0003+0.0025+0.0125+0.0420+0.1009+0.1766+0.2270+0.2128 = 0.7746
// Invariant: answer = sum of P(X = x) for x = 0..k to 4dp on every seed.

$anstypes = array("numfunc")

$ctxs = array(
  "A biased coin lands heads with probability 0.25. It is flipped 10 times. Let `X` be the number of heads.",
  "A fair coin is flipped 8 times. Let `X` be the number of heads.",
  "In a random sample of 20 adults, 30% prefer saving over spending. Let `X` be the number who prefer saving.",
  "A basketball player makes 60% of her free throws, independently. She shoots 12 free throws. Let `X` be the number she makes."
)

$ns = array(10, 8, 20, 12)
$ps = array(0.25, 0.5, 0.3, 0.6)
$ks = array(3, 2, 4, 8)
$probs = array(0.7759, 0.1446, 0.2374, 0.7746)

$i = rand(0, 3)
$ctx = $ctxs[$i]
$n = $ns[$i]
$p = $ps[$i]
$k = $ks[$i]
$prob = $probs[$i]

$answer[0] = $prob
$abstolerance[0] = 0.005

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
      <p><span class="term-label">Translate first.</span> "At most ' . $k . '" means `X <= ' . $k . '`: every value from 0 up to ' . $k . '.</p>
      <p><span class="term-label">The running total.</span> Add the individual probabilities `P(X = x)` for `x = 0, 1, ..., ' . $k . '`:</p>
      <p><b>' . $prob . '</b></p>
      <p>This is what the calculator\'s cumulative binomial command does in one step: but the total is only as trustworthy as the individual probabilities you added, so the running total is the habit worth keeping.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx Find the probability of at most $k successes, `P(X <= $k)`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> `P(X <= $k) =`
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
