// === NAME - DESCRIPTION: Name the Four Pieces - population, sample, parameter, and statistic ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's opening naming exercise (Try It Now 7.1). Parts: (a) choices - the population
// (b) choices - the sample (c) choices - the parameter (d) choices - the statistic.
// Invariant: all four answers are constant on every seed.

$anstypes = array("choices", "choices", "choices", "choices")

$questions[0] = array(
  "Every home recently listed for sale in Butte County",
  "The 35 prices in Table 7.4.1",
  "The mean of the 35 prices"
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  "Every home recently listed for sale in Butte County",
  "The 35 prices in Table 7.4.1",
  "The mean of the 35 prices"
)
$answer[1] = 1
$noshuffle[1] = "all"

$questions[2] = array(
  "mu, the true mean sale price over that entire population of listings",
  "bar(x), the mean of the 35 prices",
  "The 35 prices in Table 7.4.1"
)
$answer[2] = 0
$noshuffle[2] = "all"

$questions[3] = array(
  "mu, the true mean sale price over that entire population of listings",
  "bar(x), the mean of the 35 prices",
  "The 35 prices in Table 7.4.1"
)
$answer[3] = 1
$noshuffle[3] = "all"

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
      <p><span class="term-label">Part (a): the population.</span> Every home recently listed for sale in Butte County. That is the whole collection the question is about.</p>
      <p><span class="term-label">Part (b): the sample.</span> The 35 prices in Table 7.4.1: the subset actually examined.</p>
      <p><span class="term-label">Part (c): the parameter.</span> `mu`, the true mean sale price over that entire population of listings. It is a single fixed number.</p>
      <p><span class="term-label">Part (d): the statistic.</span> `bar(x)`, the mean of the 35 prices. It is the estimate that stands in for `mu`.</p>
      <p>The parameter cannot be looked up because computing it would mean recording the price of every listing in the county on the same day and averaging them: the census that sampling exists to avoid. The gap between the `bar(x)` you can compute and the `mu` you cannot is exactly the gap the confidence interval is built to measure.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">For the demonstration data in Table 7.4.1 (35 home sale prices in Butte County), name the population, the sample, the parameter you are after, and the statistic you will use to estimate it.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The population.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The sample.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> The parameter.
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> The statistic.
    <span style="margin-left:8px;">$answerbox[3]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
