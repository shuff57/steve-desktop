// === NAME - DESCRIPTION: The Error Bound and the Interval for a Proportion - EBP, lower and upper endpoints ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// x, n, CL with np' > 5 and nq' > 5. Parts: (a) numfunc - EBP = z_(alpha/2) * sqrt(p'q'/n)
// (b) numfunc - the lower endpoint p' - EBP (c) numfunc - the upper endpoint p' + EBP.
// Invariant: (a) = z*SE exactly, (b) = p' - EBP, (c) = p' + EBP exactly on every seed.

$anstypes = array("numfunc", "numfunc", "numfunc")

$contexts = array(
  array("people who own a tablet", 98, 250, 0.95, 1.96),
  array("adult residents who have smartphones", 421, 500, 0.95, 1.96),
  array("students who are against the new legislation", 480, 600, 0.90, 1.645),
  array("students who are registered voters", 300, 500, 0.90, 1.645)
)
// [ctx, x, n, cl, z]

$i = rand(0, 3)
$ctx = $contexts[$i][0]
$x = $contexts[$i][1]
$n = $contexts[$i][2]
$cl = $contexts[$i][3]
$z = $contexts[$i][4]

$clPct = round($cl * 100)

$p = $x / $n
$q = 1 - $p
$se = sqrt($p * $q / $n)
$ebp = $z * $se
$lo = $p - $ebp
$hi = $p + $ebp

$answer[0] = $ebp
$abstolerance[0] = 0.005
$answer[1] = $lo
$abstolerance[1] = 0.005
$answer[2] = $hi
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
      <p><span class="term-label">The four numbers, in order.</span> `p\' = ' . $x . '/' . $n . ' = ' . round($p, 4) . '`, `q\' = ' . round($q, 4) . '`, `z_(alpha/2) = ' . $z . '` for ' . round($cl * 100) . '% confidence.</p>
      <p><span class="term-label">Part (a): the error bound.</span> `EBP = z_(alpha/2) * sqrt(p\'q\'/n) = ' . $z . ' * sqrt((' . round($p, 4) . ')(' . round($q, 4) . ')/' . $n . ') = ' . round($ebp, 4) . '`</p>
      <p><span class="term-label">Parts (b) and (c): the interval.</span> `(p\' - EBP, p\' + EBP) = (' . round($lo, 4) . ', ' . round($hi, 4) . ')`</p>
      <p>There is one honest problem with the formula: it wants `p` and `q`, the population proportions: the very numbers we are trying to estimate. So we substitute the sample proportions, and the plus-four method later in this section exists precisely because that substitution introduces error.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A survey of `n = $n` randomly selected people found that `x = $x` of them are $ctx. Construct a $clPct% confidence interval for the true population proportion of $ctx.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The error bound `EBP`. (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The LOWER endpoint of the interval. (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> The UPPER endpoint of the interval. (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
