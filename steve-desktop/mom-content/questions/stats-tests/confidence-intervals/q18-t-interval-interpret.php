// === NAME - DESCRIPTION: Interpret the t-Interval - the sentence, the level's meaning, and the wrong reading ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// An interval (lo, hi) at CL for a context. Parts: (a) choices - the correct interpretation
// sentence (b) choices - what the confidence level means (c) choices - the wrong reading.
// Invariant: all three answers are constant across seeds.

$anstypes = array("choices", "choices", "choices")

$contexts = array(
  array("the true mean number of hours slept per night for this population", 8.16, 9.80, 95),
  array("the true mean sensory rate for the population", 7.30, 9.15, 95),
  array("the mean number of hours statistics students watch television per week", 2.4, 9.9, 98)
)
// [ctx, lo, hi, clPct]

$i = rand(0, 2)
$ctx = $contexts[$i][0]
$lo = $contexts[$i][1]
$hi = $contexts[$i][2]
$clPct = $contexts[$i][3]

$questions[0] = array(
  "We estimate with " . $clPct . "% confidence that " . $ctx . " is between " . $lo . " and " . $hi,
  "There is a " . $clPct . "% chance that " . $ctx . " is between " . $lo . " and " . $hi,
  $clPct . "% of individual values in the population are between " . $lo . " and " . $hi
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  "The method, not the result: about " . $clPct . "% of intervals built this way contain the true mean in repeated sampling",
  "The particular interval has a " . $clPct . "% chance of containing the true mean",
  "The sample mean has a " . $clPct . "% chance of being the true mean"
)
$answer[1] = 0
$noshuffle[1] = "all"

$questions[2] = array(
  "There is a " . $clPct . "% chance the true mean is between " . $lo . " and " . $hi,
  "We estimate with " . $clPct . "% confidence that " . $ctx . " is between " . $lo . " and " . $hi,
  "About " . $clPct . "% of intervals built this way contain the true mean"
)
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
      <p><span class="term-label">Part (a) &mdash; the interpretation sentence.</span> The interval is a statement about the population mean, not about individual values. The template: "We estimate with [CL]% confidence that the true population mean [context] is between [lower] and [upper] [units]."</p>
      <p><span class="term-label">Part (b) &mdash; what the level means.</span> The confidence level describes the method: if you repeated the whole study many times, each sample producing its own interval, about ' . $clPct . '% of those intervals would contain the true population value. Any single interval either contains it or misses it, and you never find out which.</p>
      <p><span class="term-label">Part (c) &mdash; the wrong reading.</span> "There is a ' . $clPct . '% chance the true mean is between the endpoints" treats mu as a random quantity that might fall in a fixed range. mu is a constant, and once the endpoints are computed they are constants too, so the statement is either entirely true or entirely false &mdash; there is no ' . $clPct . '% about it.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A $clPct% confidence interval for $ctx is `($lo, $hi)`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which sentence correctly interprets the interval?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What does the $clPct% confidence level mean?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which sentence is the WRONG reading to avoid?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
