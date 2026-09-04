// === NAME - DESCRIPTION: Height Is a Density, Not a Probability - multiply by the width ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Four flat densities. Parts: (a) choices - is the height f(x) itself a probability?
// (b) choices - what must you multiply the height by to get a probability?
// (c) numfunc - the actual probability of a named strip.
// Invariant: (a) and (b) are constant across seeds; (c) = base*height in (0,1).
//   S0: U(0,20) strip (4,12): height 0.05, base 8, P = 0.4
//   S1: U(0,10) strip (2,6):  height 0.1,  base 4, P = 0.4
//   S2: U(0,25) strip (5,15): height 0.04, base 10, P = 0.4
//   S3: U(0,40) strip (0,30): height 0.025, base 30, P = 0.75

$anstypes = array("choices", "choices", "numfunc")

$bs = array(20, 10, 25, 40)
$cs = array(4, 2, 5, 0)
$ds = array(12, 6, 15, 30)
$probs = array(0.4, 0.4, 0.4, 0.75)

$i = rand(0, 3)
$b = $bs[$i]
$c = $cs[$i]
$d = $ds[$i]

$questions[0] = array(
  "No: the height is a density, a rate of probability per unit of x, not a probability itself",
  "Yes: the height of the curve at a point is the probability of that point",
  "Yes: the height is the total probability of the interval it sits over",
  "No: the height is the probability of landing outside the interval"
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  "The width of the strip",
  "The total area under the whole curve",
  "The number of points in the interval",
  "Nothing: the height alone is the probability"
)
$answer[1] = 0
$noshuffle[1] = "all"

$answer[2] = $probs[$i]
$abstolerance[2] = 0.005

$height = 1.0 / $b
$base = $d - $c

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
      <p><span class="term-label">The height is a density.</span> The curve sits at `f(x) = 1/(' . $b . ') = ' . $height . '`. That number is not a probability: it is a density, a rate of probability per unit of `x`. Left alone it says nothing about any region; it only becomes a probability once you multiply it by a width.</p>
      <p><span class="term-label">Part (c): multiply by the width.</span> The strip runs from `x = ' . $c . '` to `x = ' . $d . '`, so it is a rectangle with base `' . $d . ' - ' . $c . ' = ' . $base . '` and height `' . $height . '`:</p>
      <p>`P(' . $c . ' < x < ' . $d . ') = (base)(height) = ' . $base . ' * ' . $height . ' = ` <b>' . $answer[2] . '</b></p>
      <p>That is the whole lesson of the section in one sentence: for a continuous random variable, probability is area, and area needs both a height and a width.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Consider `f(x) = 1/$b` for `0 <= x <= $b`, and `0` everywhere else.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Is the height of the curve at a point a probability?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What must you multiply the height by to get a probability?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find `P($c < x < $d)`.
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
