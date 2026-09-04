// === NAME - DESCRIPTION: Read the Sign and Size of a z-Score - how far, which direction, and the mean ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A value x and its z-score are given. Parts: (a) numfunc - |z| (how many SDs)
// (b) choices - right or left of the mean (c) numfunc - the mean.
// Invariant: (a) = |z|, (b) matches the sign of z, (c) = mu on every seed.

$anstypes = array("numfunc", "choices", "numfunc")

$contexts = array(
  "a normal distribution",
  "a normal distribution",
  "a normal distribution"
)
$mus = array(12, 16, 5)
$sigmas = array(3, 4, 6)
$zs = array(-3.67, -1.5, 2)
$xs = array(1, 10, 17)

$i = rand(0, 2)
$mu = $mus[$i]
$sigma = $sigmas[$i]
$z = $zs[$i]
$x = $xs[$i]

$answer[0] = abs($z)
$abstolerance[0] = 0.005

$questions[1] = array(
  "to the RIGHT of the mean",
  "to the LEFT of the mean"
)
$answer[1] = ($z > 0) ? 0 : 1
$noshuffle[1] = "all"

$answer[2] = $mu
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
      <p><span class="term-label">The z-score is a position.</span> `z = ' . $z . '` means the value `x = ' . $x . '` sits ' . abs($z) . ' standard deviations ' . (($z > 0) ? "to the right of" : "to the left of") . ' the mean.</p>
      <p><span class="term-label">Part (a): the size.</span> The size of the z-score is the distance in standard deviations: `|z| = ' . abs($z) . '`.</p>
      <p><span class="term-label">Part (b): the direction.</span> The sign of the z-score is the direction: positive is right of the mean, negative is left. Here `z = ' . $z . '` is ' . (($z > 0) ? "positive, so the value is to the right" : "negative, so the value is to the left") . '.</p>
      <p><span class="term-label">Part (c): the mean.</span> The z-score is measured from the mean, so `mu = ' . $mu . '`.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">In a normal distribution, the value `x = $x` has a z-score of `z = $z`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> How many standard deviations is `x = $x` from the mean?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Is `x = $x` to the right or to the left of the mean?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is the mean of the distribution?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
