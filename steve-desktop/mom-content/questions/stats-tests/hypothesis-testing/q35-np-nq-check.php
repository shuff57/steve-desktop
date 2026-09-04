// === NAME - DESCRIPTION: The np and nq Check - n*p0 and n*(1-p0) under the null, and whether the normal approximation is allowed ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A proportion test with n and p0. Parts: (a) numfunc - n*p0 (b) numfunc - n*(1-p0)
// (c) choices - may the normal approximation be used (both > 5)?
// Invariant: (a) = n*p0 and (b) = n*(1-p0) exactly, (c) matches the actual check on every seed.

$anstypes = array("numfunc", "numfunc", "choices")

$cases = array(
  array("A survey tests whether the proportion of voters who support a measure differs from 0.30. A sample of n = 200 voters is taken.",
        200, 0.30),
  array("A survey tests whether the proportion of adults who exercise regularly is greater than 0.40. A sample of n = 150 adults is taken.",
        150, 0.40),
  array("A survey tests whether the proportion of students who prefer online classes is less than 0.50. A sample of n = 80 students is taken.",
        80, 0.50),
  array("A survey tests whether the proportion of customers who are satisfied differs from 0.60. A sample of n = 60 customers is taken.",
        60, 0.60),
  array("A survey tests whether the proportion of people who own cats is greater than 0.20. A sample of n = 30 people is taken.",
        30, 0.20)
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$n = $cases[$i][1]
$p0 = $cases[$i][2]

$np = $n * $p0
$nq = $n * (1 - $p0)
$ok = 0
$ok = 1 if ($np > 5 && $nq > 5)

$answer[0] = $np
$answer[1] = $nq
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005

$questions[2] = array(
  "Yes: both are above 5, so the normal approximation is legitimate.",
  "No: at least one is at or below 5, so the normal approximation is not legal."
)
$answer[2] = $ok
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
      <p><span class="term-label">Parts (a) and (b): the check.</span> The condition is computed under the null: `np_0 = ' . $n . ' * ' . $p0 . ' = ' . $np . '` and `n(1 - p_0) = ' . $n . ' * ' . round(1 - $p0, 2) . ' = ' . $nq . '`.</p>
      <p><span class="term-label">Part (c): the verdict.</span> ' . ($ok == 1 ? "Both clear 5, so the normal approximation is legitimate." : "At least one is at or below 5, so the normal approximation is not legal: the binomial is too lopsided for a normal curve to trace.") . '</p>
      <p>The condition uses the null proportion `p_0`, not the sample proportion, because the normal curve is standing in for the binomial distribution the null hypothesis claims.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Compute `n * p_0`.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Compute `n * (1 - p_0)`.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> May the normal approximation be used?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
