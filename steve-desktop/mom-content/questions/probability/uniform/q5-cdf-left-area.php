// === NAME - DESCRIPTION: The Cumulative Distribution Function - P(X <= x) = (x - a) / (b - a) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Four flat densities; CDF at two points and the strip between them. Parts:
// (a) numfunc - P(X <= x1), (b) numfunc - P(X <= x2), (c) numfunc - P(x1 < X < x2) = (b) - (a).
// Invariant: (c) = (b) - (a) exactly; all three in [0,1] on every seed.

$anstypes = array("numfunc", "numfunc", "numfunc")

$as = array(0, 1, 3, 2)
$bs = array(20, 9, 11, 14)
$x1s = array(5, 3, 6, 6)
$x2s = array(12, 6, 9, 10)

$c1s = array(0.25, 0.25, 0.375, 0.3333)
$c2s = array(0.6, 0.625, 0.75, 0.6667)
$strips = array(0.35, 0.375, 0.375, 0.3333)

$i = rand(0, 3)
$a = $as[$i]
$b = $bs[$i]
$x1 = $x1s[$i]
$x2 = $x2s[$i]

$answer[0] = $c1s[$i]
$answer[1] = $c2s[$i]
$answer[2] = $strips[$i]
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
      <p><span class="term-label">The CDF.</span> The cumulative distribution function is the area to the left: `P(X <= x) = (x - a)/(b - a) = (x - ' . $a . ')/(' . ($b - $a) . ')`.</p>
      <p><span class="term-label">Part (a).</span> `P(X <= ' . $x1 . ') = (' . ($x1 - $a) . ')/(' . ($b - $a) . ') = ` <b>' . $answer[0] . '</b></p>
      <p><span class="term-label">Part (b).</span> `P(X <= ' . $x2 . ') = (' . ($x2 - $a) . ')/(' . ($b - $a) . ') = ` <b>' . $answer[1] . '</b></p>
      <p><span class="term-label">Part (c) &mdash; the strip.</span> Every "between" question is a subtraction on the left areas you already have:</p>
      <p>`P(' . $x1 . ' < X < ' . $x2 . ') = P(X <= ' . $x2 . ') - P(X <= ' . $x1 . ') = ' . $answer[1] . ' - ' . $answer[0] . ' = ` <b>' . $answer[2] . '</b></p>
      <p>Rather than re-measuring a rectangle each time, tabulate the area to the left of each point once; the between-questions are then subtraction on numbers you already have.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Consider `f(x) = 1/(' . ($b - $a) . ')` for `' . $a . ' <= x <= ' . $b . '`, and `0` everywhere else.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find `P(X <= $x1)`.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find `P(X <= $x2)`.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find `P($x1 < X < $x2)`.
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
