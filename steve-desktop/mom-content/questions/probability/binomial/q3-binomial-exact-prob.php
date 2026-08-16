// === NAME - DESCRIPTION: Binomial Exact Probability - P(X = k) by the formula ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Four binomial scenarios; P(X = k) precomputed via C(n,k) p^k q^(n-k) (verified):
//   S0: n=8,  p=0.5,  k=3   P = 56*0.125*0.03125 = 0.2188
//   S1: n=10, p=0.25, k=2   P = 45*0.0625*0.100113 = 0.2816
//   S2: n=12, p=0.6,  k=7   P = 792*0.027994*0.01024 = 0.2270
//   S3: n=5,  p=0.8,  k=4   P = 5*0.4096*0.2 = 0.4096
// Invariant: answer = C(n,k) p^k q^(n-k) to 4dp on every seed.

$anstypes = array("numfunc")

$ctxs = array(
  "A fair coin is flipped 8 times. Let `X` be the number of heads.",
  "A biased coin lands heads with probability 0.25. It is flipped 10 times. Let `X` be the number of heads.",
  "A basketball player makes 60% of her free throws, independently. She shoots 12 free throws. Let `X` be the number she makes.",
  "A student answers 5 true-false questions by guessing. She has probability 0.8 of getting each question right, independently. Let `X` be the number correct."
)

$ns = array(8, 10, 12, 5)
$ps = array(0.5, 0.25, 0.6, 0.8)
$ks = array(3, 2, 7, 4)
$probs = array(0.2188, 0.2816, 0.2270, 0.4096)
$qdisps = array("0.5", "0.75", "0.4", "0.2")

$i = rand(0, 3)
$ctx = $ctxs[$i]
$n = $ns[$i]
$p = $ps[$i]
$k = $ks[$i]
$prob = $probs[$i]
$qdisp = $qdisps[$i]

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
      <p><span class="term-label">The formula.</span> `P(X = x) = C(n,x) p^x q^(n-x)`, where `C(n,x)` counts the ways the successes can be arranged and `p^x q^(n-x)` is the probability of any one such arrangement.</p>
      <p><span class="term-label">This question.</span> `X ~ B(' . $n . ', ' . $p . ')` and we want `P(X = ' . $k . ')`:</p>
      <p>`P(X = ' . $k . ') = C(' . $n . ', ' . $k . ') (' . $p . ')^' . $k . ' (' . $qdisp . ')^(' . $n . ' - ' . $k . ') = ` <b>' . $prob . '</b></p>
      <p>The coefficient stops you from counting one pattern when several would do: getting ' . $k . ' successes in ' . $n . ' trials can happen in `C(' . $n . ', ' . $k . ')` different orders, and each order carries the same probability.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx Find the probability of exactly $k successes, `P(X = $k)`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> `P(X = $k) =`
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
