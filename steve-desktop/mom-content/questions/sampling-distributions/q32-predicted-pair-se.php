// === NAME - DESCRIPTION: The Predicted Pair Standard Deviation - sigma/sqrt(2) ~ 0.3622 ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's prediction (Try It Now 6.3 step 3): the distribution of pair averages should have
// standard deviation sigma/sqrt(2) = 0.5122/sqrt(2) ~ 0.3622.
// Part: (a) numfunc - ~ 0.3622.
// Invariant: ~ 0.3622 on every seed.

$anstypes = array("numfunc")

$answer[0] = 0.3622
$abstolerance[0] = 0.005

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
      <p><span class="term-label">The prediction.</span> With `sigma ~= 0.5122` and `n = 2`, the central limit theorem for sample means says the pair averages should have standard deviation</p>
      <p>`sigma/sqrt(n) = 0.5122/sqrt(2) ~= 0.5122/1.414 ~= 0.3622`</p>
      <p><span class="term-label">What the theorem does and does not change.</span> The center does not move: the mean of the averages is the same `mu` you started with, so the pair x-bar should land close to the individual x-bar. The spread shrinks by a factor of `sqrt(2) ~= 1.41`, so the pair histogram should be about 1.41 times narrower than the first one. And the shape moves toward a bell, even though the population it came from was nowhere near one. Doing it with two people already recovers about 29% of the spread.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">In the pocket-change lab, the class data has `x-bar = 0.70` and `s ~= 0.5122`. Use the population figures to predict the standard deviation of the whole distribution of pair averages (n = 2).</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The predicted standard deviation of the pair averages. (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
