// === NAME - DESCRIPTION: Proportion Condition Failure - why the normal approximation cannot be used, and what the correct approach is ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A proportion test that FAILS the condition. Parts: (a) choices - why the normal
// approximation cannot be used (b) choices - what the correct approach is.
// Invariant: both answers are constant across seeds.

$anstypes = array("choices", "choices")

$cases = array(
  array("A survey tests whether the proportion of people who own cats is greater than 0.20. A sample of n = 30 people is taken, so np0 = 6 and n(1 - p0) = 24.",
        "The condition passes: both are above 5, so the normal approximation is legitimate.",
        "The normal approximation is legitimate."),
  array("A survey tests whether the proportion of students who prefer online classes is less than 0.10. A sample of n = 40 students is taken, so np0 = 4 and n(1 - p0) = 36.",
        "The condition fails: np0 = 4 is below 5, so the binomial is too lopsided for a normal curve to trace.",
        "The test has to run on the binomial distribution: the normal shortcut is simply not available."),
  array("A survey tests whether the proportion of defective parts is less than 0.05. A sample of n = 60 parts is taken, so np0 = 3 and n(1 - p0) = 57.",
        "The condition fails: np0 = 3 is below 5, so the binomial is too lopsided for a normal curve to trace.",
        "The test has to run on the binomial distribution: the normal shortcut is simply not available."),
  array("A survey tests whether the proportion of adults who exercise regularly is greater than 0.40. A sample of n = 150 adults is taken, so np0 = 60 and n(1 - p0) = 90.",
        "The condition passes: both are above 5, so the normal approximation is legitimate.",
        "The normal approximation is legitimate.")
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$why = $cases[$i][1]
$approach = $cases[$i][2]

$questions[0] = array(
  $why,
  "The sample was not random.",
  "The claimed value was not a whole number."
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  $approach,
  "The test has to run on the binomial distribution: the normal shortcut is simply not available.",
  "The test cannot be run at all."
)
$answer[1] = 0
$noshuffle[1] = "all"

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
      <p><span class="term-label">Part (a): why the approximation fails.</span> ' . $why . '</p>
      <p><span class="term-label">Part (b): the correct approach.</span> ' . $approach . '</p>
      <p>`np < 5` means you expect fewer than five successes, so the binomial distribution is too skewed for the normal curve to be a fair stand-in: the test will not have the coverage it claims. Whenever a p-value based on a normal approximation is close to the level of significance, the exact p-value based on binomial probabilities should be calculated whenever possible.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Can the normal approximation be used here?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the correct approach?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
