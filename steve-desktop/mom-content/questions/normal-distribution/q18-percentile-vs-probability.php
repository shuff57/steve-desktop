// === NAME - DESCRIPTION: Percentile or Probability - which question is normalcdf and which is invNorm ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Conceptual, on one N(mu, sigma). Parts: (a) choices - which question is a normalcdf question
// (b) choices - which is an invNorm question (c) numfunc - the answer to the probability question.
// Invariant: (a) and (b) are constant across seeds; (c) is the precomputed normalcdf value.

loadlibrary("stats");

$anstypes = array("choices", "choices", "numfunc")

$contexts = array(
  array("the scores on a college entrance exam, in points", 52, 11, 63, 0.8413),
  array("the golf scores for a school team", 68, 3, 65, 0.1587),
  array("the time to find a parking space, in minutes", 5, 2, 8, 0.0668)
)
// [ctx, mu, sigma, cutoff, P(X < cutoff)]

$i = rand(0, 2)
$ctx = $contexts[$i][0]
$mu = $contexts[$i][1]
$sigma = $contexts[$i][2]
$cut = $contexts[$i][3]
$prob = $contexts[$i][4]

$questions[0] = array(
  "What is the probability that a randomly selected value is less than " . $cut . "?",
  "What value has 80% of the values below it?",
  "What is the 90th percentile?"
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  "What is the probability that a randomly selected value is less than " . $cut . "?",
  "What value has 80% of the values below it?",
  "What is the 90th percentile?"
)
$answer[1] = 1
$noshuffle[1] = "all"

$answer[2] = $prob
$reltolerance[2] = 0.02
$abstolerance[2] = 0.003

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
      <p><span class="term-label">Decide which direction the question runs.</span> If the problem ends with "what is the probability" or "what percent", the answer is a number between 0 and 1 and you want normalcdf: value in, probability out. If the problem ends with a unit, points, hours, years, the answer is a value on the x-axis and you want invNorm: probability in, value out.</p>
      <p><span class="term-label">Part (a): the probability question.</span> "What is the probability that a randomly selected value is less than ' . $cut . '?" ends in a probability, so it is a normalcdf question.</p>
      <p><span class="term-label">Part (b): the value question.</span> "What value has 80% of the values below it?" ends in a value, so it is an invNorm question.</p>
      <p><span class="term-label">Part (c): the probability.</span> Standardize: `z = (' . $cut . ' - ' . $mu . ')/' . $sigma . ' ~= ' . round(($cut - $mu) / $sigma, 3) . '`, so `P(X < ' . $cut . ') ~= ' . round($prob, 4) . '`.</p>
      <p>Most errors in this section are not arithmetic errors at all: they are answering the wrong one of the two questions. Sketch the curve first: shade what the problem describes, then look at whether the thing you do not know is the shaded amount or the boundary of the shading.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx are normally distributed with mean `mu = $mu` and standard deviation `sigma = $sigma`. Two questions are asked about this distribution.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which of these is a normalcdf question (value in, probability out)?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which of these is an invNorm question (probability in, value out)?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Answer the probability question: find `P(X < $cut)`. (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
