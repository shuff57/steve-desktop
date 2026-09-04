// === NAME - DESCRIPTION: Empirical Rule Percentages - 34%, 13.5%, and 4.7% between the bands ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// N(mu, sigma). Parts: (a) choices - percent between the mean and 1 SD (34%)
// (b) choices - percent between the 1st and 2nd SDs, both sides (13.5%)
// (c) choices - percent between the 2nd and 3rd SDs, both sides (4.7%).
// Invariant: the three answers are constant across seeds (only the context varies).

$anstypes = array("choices", "choices", "choices")

$contexts = array(
  "the scores on a college entrance exam, in points",
  "the heights of adult men, in inches",
  "the daily commute time, in minutes"
)
$mus = array(52, 70, 28)
$sigmas = array(11, 3, 6)

$i = rand(0, 2)
$ctx = $contexts[$i]
$mu = $mus[$i]
$sigma = $sigmas[$i]

$questions[0] = array("about 34%", "about 47.5%", "about 13.5%", "about 2.5%")
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array("about 13.5%", "about 34%", "about 2.5%", "about 4.7%")
$answer[1] = 0
$noshuffle[1] = "all"

$questions[2] = array("about 4.7%", "about 13.5%", "about 0.15%", "about 2.5%")
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
      <p><span class="term-label">Start from the bands.</span> 68% within 1 SD, 95% within 2 SDs, 99.7% within 3 SDs. The curve is symmetric, so each band splits evenly around the mean.</p>
      <p><span class="term-label">Part (a): mean to 1 SD.</span> Half of the 68% band lies between the mean and one standard deviation: `68/2 = 34%`.</p>
      <p><span class="term-label">Part (b): 1st to 2nd SD, both sides.</span> The 95% band minus the 68% band leaves `95 - 68 = 27%` in the two outer strips, so each side carries `27/2 = 13.5%`.</p>
      <p><span class="term-label">Part (c): 2nd to 3rd SD, both sides.</span> The 99.7% band minus the 95% band leaves `99.7 - 95 = 4.7%` in the two outer strips, so each side carries `4.7/2 = 2.35%`: and the question asks for both sides together: `4.7%`.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">The distribution of $ctx is approximately normal with mean `mu = $mu` and standard deviation `sigma = $sigma`. Use the empirical rule (68-95-99.7).</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> About what percent of the values lie between the mean and one standard deviation above it?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> About what percent of the values lie between the first and second standard deviations from the mean (both sides)?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> About what percent of the values lie between the second and third standard deviations from the mean (both sides)?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
