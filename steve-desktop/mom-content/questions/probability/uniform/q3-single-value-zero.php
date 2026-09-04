// === NAME - DESCRIPTION: P(x = c) Is Zero - A single point has no width, so no area ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Four flat densities. Parts: (a) numfunc - P(x = c) (always 0)
// (b) choices - why a single value has probability 0 for a continuous variable.
// Invariant: (a) = 0 on every seed; (b) constant.

$anstypes = array("numfunc", "choices")

$as = array(0, 1, 2, 0)
$bs = array(15, 6, 10, 12)
$cs = array(7, 4, 5, 8)

$i = rand(0, 3)
$a = $as[$i]
$b = $bs[$i]
$c = $cs[$i]
$width = $b - $a

$answer[0] = 0

$questions[1] = array(
  "A single point has no width, so the rectangle under it has base 0 and area 0: for ANY continuous random variable, P(x = c) = 0",
  "The value c falls outside the range of the distribution",
  "The curve is not defined at exactly x = c",
  "The height of the curve at c is 0"
)
$answer[1] = 0
$noshuffle[1] = "all"

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
      <p><span class="term-label">The geometry.</span> On an x-y graph, `x = ' . $c . '` is a vertical line. A vertical line has no width: its base is zero. So</p>
      <p>`P(x = ' . $c . ') = (base)(height) = (0)(1/(' . ($b - $a) . ')) = 0`</p>
      <p><span class="term-label">The general fact.</span> For ANY continuous random variable the probability of landing on one exact value is zero, because a single point has no width to give it area. That is exactly why `P(x < ' . $c . ')` and `P(x <= ' . $c . ')` are the same number here: the endpoint contributes nothing.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Consider `f(x) = 1/$width` for `$a <= x <= $b`, and `0` everywhere else.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find `P(x = $c)`.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Why is the probability of a single exact value zero for a continuous variable?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
