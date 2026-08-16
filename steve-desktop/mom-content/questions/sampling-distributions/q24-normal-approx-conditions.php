// === NAME - DESCRIPTION: Normal Approximation Conditions - np and nq, and whether the approximation is allowed ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A binomial scenario. Parts: (a) numfunc - np (b) numfunc - nq (c) choices - can the normal
// approximation be used (both > 5)?
// Invariant: (a) = n*p, (b) = n*(1-p) exactly, and (c) matches the actual check on every seed —
// include scenarios that FAIL the condition so the answer varies by scenario.

$anstypes = array("numfunc", "numfunc", "choices")

$contexts = array(
  array("favor a charter school", 300, 0.53),
  array("favor the incumbent for mayor", 500, 0.46),
  array("a certain coin lands heads", 12, 0.20),
  array("a shipment of parts is defective", 200, 0.98)
)
// [ctx, n, p]

$i = rand(0, 3)
$ctx = $contexts[$i][0]
$n = $contexts[$i][1]
$p = $contexts[$i][2]

$np = $n * $p
$nq = $n * (1 - $p)

$answer[0] = $np
$abstolerance[0] = 0.005
$answer[1] = $nq
$abstolerance[1] = 0.005

$canUse = ($np > 5 && $nq > 5) ? 0 : 1

$questions[2] = array(
  "Yes, both `np` and `nq` are greater than 5",
  "No, at least one of `np` or `nq` is not greater than 5"
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
      <p><span class="term-label">Part (a) &mdash; np.</span> `np = ' . $n . '(' . $p . ') = ' . $np . '`.</p>
      <p><span class="term-label">Part (b) &mdash; nq.</span> `q = 1 - p = ' . (1 - $p) . '`, so `nq = ' . $n . '(' . (1 - $p) . ') = ' . $nq . '`.</p>
      <p><span class="term-label">Part (c) &mdash; the conditions.</span> The shape of the binomial distribution needs to be similar to the shape of the normal distribution. To ensure this, the quantities `np` and `nq` must both be greater than five (`np > 5` and `nq > 5`; the approximation is better if they are both greater than or equal to 10). Here ' . ($canUse == 0 ? "both are greater than 5, so the normal approximation is allowed." : "at least one is not greater than 5, so the normal approximation is NOT allowed.") . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">In a survey of `n = $n` people, ' . ($p * 100) . '% are expected to $ctx. Let `X` = the number who do, so `X ~ B($n, $p)`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Compute `np`.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Compute `nq`.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> May the normal approximation to the binomial be used?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
