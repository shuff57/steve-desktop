// === NAME - DESCRIPTION: The t Critical Value - t_(alpha/2) for a stated confidence level, and the area to feed invT ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A sample size n and a confidence level. Parts: (a) numfunc - t_(alpha/2) for df = n - 1
// (b) choices - the area to feed invT (the area to the LEFT, 1 - alpha/2).
// Invariant: (a) is the precomputed t-value, (b) is constant on every seed.

$anstypes = array("numfunc", "choices")

$contexts = array(
  array("a sample of 12 hypnotherapy subjects", 12, 0.95, 2.201),
  array("a sample of 15 acupuncture subjects", 15, 0.95, 2.145),
  array("a sample of 15 statistics students", 15, 0.98, 2.624),
  array("a sample of 20 infants", 20, 0.90, 1.729)
)
// [ctx, n, cl, t]

$i = rand(0, 3)
$ctx = $contexts[$i][0]
$n = $contexts[$i][1]
$cl = $contexts[$i][2]
$t = $contexts[$i][3]

$alpha = 1 - $cl
$half = $alpha / 2
$leftArea = 1 - $half

$clPct = round($cl * 100)

$answer[0] = $t
$abstolerance[0] = 0.005

$questions[1] = array(
  "The area to the LEFT, " . round($leftArea, 3) . " &mdash; invT wants the area below the value, not the tail above it",
  "The tail area, " . round($half, 3) . " &mdash; the area to the right of the critical value"
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
      <p><span class="term-label">Part (a) &mdash; the t-critical value.</span> With `CL = ' . round($cl, 2) . '`, `alpha = 1 - CL = ' . round($alpha, 2) . '` and `alpha/2 = ' . round($half, 3) . '`. The t-score with that much area to its right, using `df = n - 1 = ' . ($n - 1) . '`, is</p>
      <p>`t_(alpha/2) = t_(' . round($half, 3) . ') = ' . $t . '`</p>
      <p><span class="term-label">Part (b) &mdash; what you feed invT.</span> `invT` works like invNorm: it wants the area BELOW the value. The area to the left is `1 - alpha/2 = ' . round($leftArea, 3) . '`, so you run `invT(' . round($leftArea, 3) . ', ' . ($n - 1) . ')`.</p>
      <p>The only place students reliably lose points is the left-versus-right tail flip: a table indexed by right-tail area wants `alpha/2`, and `invT` wants `1 - alpha/2`. Both give the same t-score; they just ask for it differently.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Consider $ctx. The population standard deviation is unknown. Find the t-critical value for a $clPct% confidence interval.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> `t_(alpha/2) =`
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which area must you feed to `invT`?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
