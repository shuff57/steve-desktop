// === NAME - DESCRIPTION: The Plus Four Method - the adjusted proportion, the adjusted EBP, and why it works ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A small survey with x successes out of n (n >= 10, CL >= 90%). Parts: (a) numfunc - the
// adjusted proportion (x + 2)/(n + 4) (b) numfunc - the error bound from the adjusted values
// (c) choices - why the adjustment works.
// Invariant: (a) = (x+2)/(n+4) exactly, (b) = z*sqrt(p'q'/(n+4)) exactly, (c) is constant.

$anstypes = array("numfunc", "numfunc", "choices")

$contexts = array(
  array("first-year students who have declared a major", 31, 65, 0.96, 2.054),
  array("statistics students who smoke", 6, 25, 0.95, 1.96),
  array("adults aged 18-29 who would consider an electric vehicle", 13, 50, 0.90, 1.645)
)
// [ctx, x, n, cl, z]

$i = rand(0, 2)
$ctx = $contexts[$i][0]
$x = $contexts[$i][1]
$n = $contexts[$i][2]
$cl = $contexts[$i][3]
$z = $contexts[$i][4]

$xp = $x + 2
$np = $n + 4
$p = $xp / $np
$q = 1 - $p
$ebp = $z * sqrt($p * $q / $np)

$answer[0] = $p
$abstolerance[0] = 0.005
$answer[1] = $ebp
$abstolerance[1] = 0.005

$questions[2] = array(
  "Adding two fake yeses and two fake noes drags any extreme sample proportion back toward 0.5, where the normal approximation behaves best",
  "Adding four observations makes the sample look bigger than it is",
  "The adjustment changes which confidence level is used"
)
$answer[2] = 0
$noshuffle[2] = "all"

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
      <p><span class="term-label">Part (a) &mdash; the adjusted proportion.</span> We pretend that we have four additional observations &mdash; two successes and two failures. The new sample size is `n + 4 = ' . $np . '` and the new count of successes is `x + 2 = ' . $xp . '`:</p>
      <p>`p\' = (x + 2)/(n + 4) = ' . $xp . '/' . $np . ' = ' . round($p, 4) . '`</p>
      <p><span class="term-label">Part (b) &mdash; the adjusted error bound.</span> Proceed exactly as before, with the adjusted values inside the square root:</p>
      <p>`EBP = z_(alpha/2) * sqrt(p\'q\'/(n + 4)) = ' . $z . ' * sqrt((' . round($p, 4) . ')(' . round($q, 4) . ')/' . $np . ') = ' . round($ebp, 4) . '`</p>
      <p><span class="term-label">Part (c) &mdash; why it works.</span> Adding two fake yeses and two fake noes drags any extreme sample proportion back toward 0.5, where the normal approximation behaves best. It is a nudge, not a fix, and it fades as n grows.</p>
      <p>The plus-four adjustment is easy to half-apply: students will change x to x + 2, compute a new p\', and then use the original n inside the square root, which produces an interval that is neither the standard one nor the corrected one. Both substitutions or neither.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Out of a random sample of `n = $n` people, `x = $x` are $ctx. Use the plus four method to find a ' . round($cl * 100) . '% confidence interval for the true proportion of $ctx.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The adjusted sample proportion `p\' = (x + 2)/(n + 4)`. (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The error bound `EBP` from the adjusted values. (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Why does the plus four adjustment work?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
