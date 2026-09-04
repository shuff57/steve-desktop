// === NAME - DESCRIPTION: The Sample Proportion - p', q', and the number of failures ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A survey with x successes out of n. Parts: (a) numfunc - p' = x/n (b) numfunc - q' = 1 - p'
// (c) numfunc - the number of failures nq'.
// Invariant: (a) = x/n exactly, (b) = 1 - p' exactly, (c) = n - x exactly on every seed.

$anstypes = array("numfunc", "numfunc", "numfunc")

$contexts = array(
  array("people who own a tablet", 98, 250),
  array("adult residents who have smartphones", 421, 500),
  array("students who are against the new legislation", 480, 600),
  array("students who are registered voters", 300, 500)
)
// [ctx, x, n]

$i = rand(0, 3)
$ctx = $contexts[$i][0]
$x = $contexts[$i][1]
$n = $contexts[$i][2]

$p = $x / $n
$q = 1 - $p
$failures = $n - $x

$answer[0] = $p
$abstolerance[0] = 0.005
$answer[1] = $q
$abstolerance[1] = 0.005
$answer[2] = $failures
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
      <p><span class="term-label">Part (a): the sample proportion.</span> `p\' = x/n = ' . $x . '/' . $n . ' = ' . round($p, 4) . '`</p>
      <p><span class="term-label">Part (b): the proportion of failures.</span> `q\' = 1 - p\' = 1 - ' . round($p, 4) . ' = ' . round($q, 4) . '`</p>
      <p><span class="term-label">Part (c): the number of failures.</span> `nq\' = n - x = ' . $n . ' - ' . $x . ' = ' . $failures . '`</p>
      <p>The sample proportion is the point estimate for the population proportion. Working an interval by hand takes four numbers and they always come in the same order: `p\'`, then `q\'`, then `z_(alpha/2)` from the confidence level, then EBP from the three of them together.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A survey of `n = $n` randomly selected people found that `x = $x` of them are $ctx. Let `X` = the number of successes, so `X ~ B($n, p)`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The sample proportion `p\' = x/n`. (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The sample proportion of failures `q\' = 1 - p\'`. (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> The number of failures `nq\'`.
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
