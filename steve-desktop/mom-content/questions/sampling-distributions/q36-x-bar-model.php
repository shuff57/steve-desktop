// === NAME - DESCRIPTION: The Model for the Averages - N(0.70, 0.23) and the population's shape ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's discussion questions 2 and 3 (Try It Now 6.5).
// Parts: (a) choices - the population distribution (right-skewed, no named family)
// (b) numfunc - the standard error of the averages: 0.5122/sqrt(5) ~ 0.2291.
// Invariant: (a) constant, (b) ~ 0.2291 on every seed.

$anstypes = array("choices", "numfunc")

$questions[0] = array(
  "Right-skewed with mean 0.70 and standard deviation 0.51 &mdash; no named family (not normal, not uniform, not exponential)",
  "Approximately normal with mean 0.70 and standard deviation 0.51",
  "Uniform with mean 0.70 and standard deviation 0.51"
)
$answer[0] = 0
$noshuffle[0] = "all"

$answer[1] = 0.2291
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
      <p><span class="term-label">Part (a) &mdash; the population.</span> The mean is $0.70, the standard deviation is about $0.51, and the mean sits above the median, so the distribution leans right. There is no named family here &mdash; it is not normal, not uniform, not exponential. Saying plainly that the population does not belong to any distribution you have a name for is a correct and complete answer.</p>
      <p><span class="term-label">Part (b) &mdash; the averages.</span> The central limit theorem supplies the family, and the two parameters come from the population with `n = 5`:</p>
      <p>`bar(X) ~ N(0.70, 0.5122/sqrt(5)) = N(0.70, 0.23)`</p>
      <p><span class="term-label">The finding.</span> Same center, different family, and a spread cut by a factor of `sqrt(5)` &mdash; averaging changed the shape and the spread without moving the middle, which is the finding the lab was built to produce. One caution belongs in your write-up: `n = 5` is a small sample to lean the central limit theorem on when the population is this lopsided, so expect your third histogram to be more bell-shaped than the first two without being convincingly bell-shaped on its own.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">In the pocket-change lab, the class data has `x-bar = 0.70` and `s ~= 0.5122`. The discussion questions ask about the population distribution and the distribution of the averages of groups of five.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the approximate distribution of the population (the individual pocket-change amounts)?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The central limit theorem says the averages of groups of five are approximately `N(0.70, ?)`. What is the standard deviation of the averages? (Round to 2 decimal places.)
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
