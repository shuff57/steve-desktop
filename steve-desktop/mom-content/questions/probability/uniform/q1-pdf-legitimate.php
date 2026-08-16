// === NAME - DESCRIPTION: Is f(x) a Legitimate Density - The two properties of a probability density function ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Four (a, b) pairs for f(x) = 1/(b-a) on [a,b]. Parts: (a) choices - both properties hold?
// (b) numfunc - total area under the curve.
// Invariant: total area = 1 and (a) = Yes on every seed.

$anstypes = array("choices", "numfunc")

$as = array(0, 1, 2, 0)
$bs = array(8, 5, 14, 20)
$areas = array(1, 1, 1, 1)

$i = rand(0, 3)
$a = $as[$i]
$b = $bs[$i]
$answer[0] = 0
$answer[1] = $areas[$i]
$abstolerance[1] = 0.005

$questions[0] = array(
  "Yes, both properties hold",
  "No, the total area is not 1",
  "No, the function is negative somewhere"
)
$noshuffle[0] = "all"

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
      <p><span class="term-label">Property 1 &mdash; never negative.</span> On `' . $a . ' <= x <= ' . $b . '` the function is the constant `1/(' . $b . ' - ' . $a . ') = 1/' . ($b - $a) . '`, which is positive; everywhere else it is 0. So `f(x) >= 0` for every `x`.</p>
      <p><span class="term-label">Property 2 &mdash; total area is 1.</span> The graph is a horizontal segment at height `1/' . ($b - $a) . '` running from `x = ' . $a . '` to `x = ' . $b . '`, so the region under it is a rectangle with base ' . ($b - $a) . ' and height `1/' . ($b - $a) . '`:</p>
      <p>Area = ' . ($b - $a) . ' * (1/' . ($b - $a) . ') = <b>1</b></p>
      <p>Both properties hold, so `f(x)` is a probability density function. The height is not itself a probability &mdash; it is a density, a rate of probability per unit of `x`, and it only becomes a probability once you multiply it by a width.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Consider the function `f(x) = 1/(b - a)` for `a <= x <= b`, and `f(x) = 0` everywhere else, with `a = $a` and `b = $b`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Is `f(x)` a legitimate probability density function?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the total area under `f(x)`?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
