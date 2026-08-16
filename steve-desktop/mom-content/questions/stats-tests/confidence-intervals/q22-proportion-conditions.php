// === NAME - DESCRIPTION: The Proportion Conditions - np' and nq', and whether the normal approximation is allowed ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A survey with x successes out of n. Parts: (a) numfunc - np' (b) numfunc - nq'
// (c) choices - may the normal approximation be used (both > 5)?
// Invariant: (a) = x, (b) = n - x exactly, (c) matches the actual check on every seed —
// include scenarios that FAIL the condition so the answer varies by scenario.

$anstypes = array("numfunc", "numfunc", "choices")

$contexts = array(
  array("people who own a tablet", 98, 250),
  array("adult residents who have smartphones", 421, 500),
  array("students who are against the new legislation", 480, 600),
  array("people who support the new policy", 2, 200)
)
// [ctx, x, n]

$i = rand(0, 3)
$ctx = $contexts[$i][0]
$x = $contexts[$i][1]
$n = $contexts[$i][2]

$np = $x
$nq = $n - $x

$answer[0] = $np
$abstolerance[0] = 0.005
$answer[1] = $nq
$abstolerance[1] = 0.005

$canUse = ($np > 5 && $nq > 5) ? 0 : 1

$questions[2] = array(
  "Yes, both `np\'` and `nq\'` are greater than 5",
  "No, at least one of `np\'` or `nq\'` is not greater than 5"
)
$answer[2] = $canUse
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
      <p><span class="term-label">Part (a) &mdash; np'.</span> `np\' = x = ' . $x . '`.</p>
      <p><span class="term-label">Part (b) &mdash; nq'.</span> `nq\' = n - x = ' . $nq . '`.</p>
      <p><span class="term-label">Part (c) &mdash; the conditions.</span> The interval can be used only if the number of successes `np\'` and the number of failures `nq\'` are both greater than five. That condition is not a formality &mdash; it is what makes the normal approximation legal in the first place. If you have 200 people and only two of them said yes, the binomial distribution is badly lopsided, a symmetric normal curve is a poor model of it, and the interval you compute will not have the coverage it claims. Check both counts before you compute anything.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A survey of `n = $n` randomly selected people found that `x = $x` of them are $ctx. We are interested in the population proportion of $ctx.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The number of successes `np\'`.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The number of failures `nq\'`.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> May the normal approximation be used to build the confidence interval?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
