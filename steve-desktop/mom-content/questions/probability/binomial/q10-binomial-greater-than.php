// === NAME - DESCRIPTION: Binomial Greater Than - P(X > k) as the complement of P(X <= k) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Four binomial scenarios; P(X > k) = 1 - P(X <= k), precomputed (verified):
//   S0: n=10, p=0.25, k=3   P = 1 - 0.7759 = 0.2241
//   S1: n=8,  p=0.5,  k=5   P = 1 - 0.8555 = 0.1445
//   S2: n=20, p=0.3,  k=6   P = 1 - 0.6080 = 0.3920
//   S3: n=12, p=0.6,  k=7   P = 1 - 0.5618 = 0.4382
// Invariant: answer = 1 - P(X <= k) to 4dp on every seed.

$anstypes = array("numfunc")

$ctxs = array(
  "A biased coin lands heads with probability 0.25. It is flipped 10 times. Let `X` be the number of heads.",
  "A fair coin is flipped 8 times. Let `X` be the number of heads.",
  "In a random sample of 20 adults, 30% prefer saving over spending. Let `X` be the number who prefer saving.",
  "A basketball player makes 60% of her free throws, independently. She shoots 12 free throws. Let `X` be the number she makes."
)

$ns = array(10, 8, 20, 12)
$ps = array(0.25, 0.5, 0.3, 0.6)
$ks = array(3, 5, 6, 7)
$probs = array(0.2241, 0.1445, 0.3920, 0.4382)
$cumUptoK = array(0.7759, 0.8555, 0.6080, 0.5618)

$i = rand(0, 3)
$ctx = $ctxs[$i]
$n = $ns[$i]
$p = $ps[$i]
$k = $ks[$i]
$prob = $probs[$i]
$cum = $cumUptoK[$i]

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
      <p><span class="term-label">Translate first.</span> "More than ' . $k . '" means `X > ' . $k . '`: strictly greater, so the row `x = ' . $k . '` is NOT included.</p>
      <p><span class="term-label">The complement.</span> "More than ' . $k . '" is everything except "at most ' . $k . '":</p>
      <p>`P(X > ' . $k . ') = 1 - P(X <= ' . $k . ') = 1 - ' . $cum . ' = ` <b>' . $prob . '</b></p>
      <p>This is the same complement habit as "at least": the tail is never added directly; it is whatever is left over after the cumulative total.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx Find the probability of more than $k successes, `P(X > $k)`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> `P(X > $k) =`
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
