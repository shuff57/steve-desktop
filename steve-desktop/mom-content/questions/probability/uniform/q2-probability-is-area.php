// === NAME - DESCRIPTION: Probability Is Area - P(c < x < d) as base times height under a flat density ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Four flat densities with interior cut points. Parts: (a) numfunc - P(c < x < d) = base*height
// (b) choices - what the height represents (a density, not a probability).
// Invariant: answer = (d-c)/(b-a) in (0,1) on every seed; (b) constant.

$anstypes = array("numfunc", "choices")

$as = array(0, 1, 2, 5)
$bs = array(20, 7, 14, 15)
$cs = array(0, 1, 2, 6)
$ds = array(2, 5, 8, 12)

$probs = array(0.1, 0.6667, 0.5, 0.6)

$i = rand(0, 3)
$a = $as[$i]
$b = $bs[$i]
$c = $cs[$i]
$d = $ds[$i]
$prob = $probs[$i]
$width = $b - $a

$answer[0] = $prob
$abstolerance[0] = 0.005

$questions[1] = array(
  "It is a density — a rate of probability per unit of x, which becomes a probability only once multiplied by a width",
  "It is the probability of landing exactly on that value of x",
  "It is the probability of landing anywhere under the curve",
  "It is the total area, which is 1"
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
      <p><span class="term-label">The one rule.</span> For a continuous random variable, probability is area. When the density is a flat horizontal line, the region under it is a rectangle, so the area is (base)(height).</p>
      <p><span class="term-label">The height.</span> `f(x) = 1/(' . $b . ' - ' . $a . ') = 1/' . ($b - $a) . '`.</p>
      <p><span class="term-label">The base.</span> The strip runs from `x = ' . $c . '` to `x = ' . $d . '`, so the base is `' . $d . ' - ' . $c . ' = ' . ($d - $c) . '`.</p>
      <p><span class="term-label">The probability.</span> `P(' . $c . ' < x < ' . $d . ') = (base)(height) = ' . ($d - $c) . ' * (1/' . ($b - $a) . ') = ` <b>' . $prob . '</b></p>
      <p>The height of the curve at a point is not the probability of that point — it only becomes a probability once you multiply it by a width. That distinction is the most common place students get stuck in this chapter.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Consider `f(x) = 1/$width` for `$a <= x <= $b`, and `0` everywhere else.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find `P($c < x < $d)`.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What does the height of the curve at a point represent?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
