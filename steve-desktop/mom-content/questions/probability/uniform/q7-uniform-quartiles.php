// === NAME - DESCRIPTION: Uniform Quartiles and Median - The percentiles of X ~ U(a, b) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Four U(a,b) distributions with clean quartiles; Q1 = a + 0.25(b-a), median = a + 0.5(b-a),
// Q3 = a + 0.75(b-a). Parts: (a) Q1, (b) median, (c) Q3.
// Invariant: Q1 < median < Q3 and each is the precomputed percentile value on every seed.
//   S0: U(0, 20)  Q1=5,   median=10, Q3=15
//   S1: U(0, 40)  Q1=10,  median=20, Q3=30
//   S2: U(4, 12)  Q1=6,   median=8,  Q3=10
//   S3: U(0, 8)   Q1=2,   median=4,  Q3=6

$anstypes = array("numfunc", "numfunc", "numfunc")

$as = array(0, 0, 4, 0)
$bs = array(20, 40, 12, 8)
$q1s = array(5, 10, 6, 2)
$meds = array(10, 20, 8, 4)
$q3s = array(15, 30, 10, 6)

$i = rand(0, 3)
$a = $as[$i]
$b = $bs[$i]

$answer[0] = $q1s[$i]
$answer[1] = $meds[$i]
$answer[2] = $q3s[$i]
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
      <p><span class="term-label">Percentiles on the uniform distribution.</span> For `X ~ U(' . $a . ', ' . $b . ')`, the `p`-th percentile is the value `x` with `P(X <= x) = p`, and because the area is a rectangle you can solve it directly: `x = a + p(b - a)`.</p>
      <p><span class="term-label">Part (a) &mdash; the first quartile.</span> `p = 0.25`: `Q1 = ' . $a . ' + 0.25(' . $b . ' - ' . $a . ') = ` <b>' . $answer[0] . '</b></p>
      <p><span class="term-label">Part (b) &mdash; the median.</span> `p = 0.5`: `median = ' . $a . ' + 0.5(' . $b . ' - ' . $a . ') = ` <b>' . $answer[1] . '</b></p>
      <p><span class="term-label">Part (c) &mdash; the third quartile.</span> `p = 0.75`: `Q3 = ' . $a . ' + 0.75(' . $b . ' - ' . $a . ') = ` <b>' . $answer[2] . '</b></p>
      <p>Notice the quartiles split the interval into four equal pieces: for the uniform distribution the value and the percentile line up exactly, so each quartile is one quarter of the way across. That is a coincidence of the flat shape, not a general rule &mdash; on a bell-shaped curve the 25th percentile sits well to the left of a quarter of the range.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Suppose `X ~ U($a, $b)` — a continuous uniform distribution over the interval `[$a, $b]`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the first quartile `Q1`.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find the median.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find the third quartile `Q3`.
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
