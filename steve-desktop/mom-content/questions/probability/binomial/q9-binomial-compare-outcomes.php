// === NAME - DESCRIPTION: Which Outcome Is More Likely - The value nearer the mean wins ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Four scenarios; two k values on the same side of the mean, k1 nearer. Precomputed (verified):
//   S0: n=10, p=0.6, mu=6   k1=5: P=0.2007, k2=7: P=0.2150 -> k2 wins; flip order: use k1=7, k2=5
//       (k=7 is 0.2150, k=5 is 0.2007) so k1=7, k2=5 with k1 more likely
//   S1: n=8, p=0.5, mu=4    k1=5: P=0.2188, k2=3: P=0.2188 -> EQUAL. Bad pair; use k1=4, k2=6:
//       k=4 P=0.2734, k=6 P=0.1094 -> k1 more likely
//   S2: n=12, p=0.25, mu=3  k1=3: P=0.2581, k2=5: P=0.1032 -> k1 more likely
//   S3: n=20, p=0.3, mu=6   k1=7: P=0.1643, k2=9: P=0.0654 -> k1 more likely
// Parts: (a) P(X=k1), (b) P(X=k2). Invariant: P(X=k1) > P(X=k2) on every seed.

$anstypes = array("numfunc", "numfunc")

$ctxs = array(
  "A basketball player makes 60% of her free throws, independently. She shoots 10 free throws. Let `X` be the number she makes.",
  "A fair coin is flipped 8 times. Let `X` be the number of heads.",
  "A biased coin lands heads with probability 0.25. It is flipped 12 times. Let `X` be the number of heads.",
  "In a random sample of 20 adults, 30% prefer saving over spending. Let `X` be the number who prefer saving."
)

$ns = array(10, 8, 12, 20)
$ps = array(0.6, 0.5, 0.25, 0.3)
$k1s = array(6, 4, 3, 7)
$k2s = array(8, 6, 5, 9)
$p1s = array(0.2508, 0.2734, 0.2581, 0.1643)
$p2s = array(0.1209, 0.1094, 0.1032, 0.0654)

$i = rand(0, 3)
$ctx = $ctxs[$i]
$n = $ns[$i]
$p = $ps[$i]
$k1 = $k1s[$i]
$k2 = $k2s[$i]
$prob1 = $p1s[$i]
$prob2 = $p2s[$i]

$answer[0] = $prob1
$answer[1] = $prob2
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
      <p><span class="term-label">Part (a).</span> `P(X = ' . $k1 . ') = C(' . $n . ', ' . $k1 . ') (' . $p . ')^' . $k1 . ' (1 - ' . $p . ')^(' . $n . ' - ' . $k1 . ') = ` <b>' . $prob1 . '</b></p>
      <p><span class="term-label">Part (b).</span> `P(X = ' . $k2 . ') = C(' . $n . ', ' . $k2 . ') (' . $p . ')^' . $k2 . ' (1 - ' . $p . ')^(' . $n . ' - ' . $k2 . ') = ` <b>' . $prob2 . '</b></p>
      <p><span class="term-label">Which is more likely.</span> `' . $prob1 . ' > ' . $prob2 . '`, so `X = ' . $k1 . '` is more likely. The mean is `mu = np = ' . $n . ' * ' . $p . ' = ' . ($n * $p) . '`; ' . $k1 . ' sits nearer the centre of the distribution than ' . $k2 . ', and on any binomial distribution the values near the mean carry the most probability. Every step further out carries less than the one before it.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx Compute both probabilities and compare.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find `P(X = $k1)`.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find `P(X = $k2)`.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
