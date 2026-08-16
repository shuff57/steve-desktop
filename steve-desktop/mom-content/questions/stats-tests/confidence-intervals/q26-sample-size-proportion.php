// === NAME - DESCRIPTION: The Sample Size for a Proportion - n = z^2(0.25)/EBP^2, rounded up ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// EBP, CL. Parts: (a) numfunc - the raw n = z^2(0.25)/EBP^2 using the worst-case p' = q' = 0.5
// (b) numfunc - the sample size rounded UP.
// Invariant: (a) is the precomputed raw value, (b) = ceil(a) exactly on every seed.

$anstypes = array("numfunc", "numfunc")

$contexts = array(
  array("customers who click on ads on their smartphones", 0.05, 0.90, 1.645),
  array("customers aged 50+ who use text messaging", 0.03, 0.90, 1.645),
  array("drivers who always buckle up", 0.03, 0.95, 1.96),
  array("college students who voted in the 2020 presidential election", 0.05, 0.95, 1.96)
)
// [ctx, ebp, cl, z]

$i = rand(0, 3)
$ctx = $contexts[$i][0]
$ebp = $contexts[$i][1]
$cl = $contexts[$i][2]
$z = $contexts[$i][3]

$raw = $z * $z * 0.25 / ($ebp * $ebp)
$n = ceil($raw)

$answer[0] = $raw
$abstolerance[0] = 0.005
$answer[1] = $n
$abstolerance[1] = 0.005

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
      <p><span class="term-label">Part (a) &mdash; the raw sample size.</span> The formula needs `p\'`, but `p\'` comes from a sample we have not collected yet. The standard move is to use `p\' = q\' = 0.5`, because `p\'q\' = 0.25` is the largest the product can ever be:</p>
      <p>`n = z^2 p\'q\' / EBP^2 = (' . $z . ')^2 (0.25) / (' . $ebp . ')^2 = ' . round($raw, 2) . '`</p>
      <p><span class="term-label">Part (b) &mdash; round UP.</span> Always round the answer up to the next whole number &mdash; rounding down would leave you short of the precision you asked for:</p>
      <p>`n = ' . $n . '`</p>
      <p>Choosing `p\' = 0.5` is not a guess about the population. It is a decision to buy enough sample to hit your margin of error even if the population is as awkward as it could possibly be. And EBP is squared in the denominator: halving your target margin of error multiplies the required sample size by four.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A company wants to determine the current percentage of $ctx. How many people should the company survey in order to be ' . round($cl * 100) . '% confident that the estimated proportion is within ' . round($ebp * 100) . ' percentage points of the true population proportion? (Use the worst-case `p\' = q\' = 0.5`.)</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The raw value of `n` before rounding. (Round to 2 decimal places.)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The sample size, rounded UP to the next whole number.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
