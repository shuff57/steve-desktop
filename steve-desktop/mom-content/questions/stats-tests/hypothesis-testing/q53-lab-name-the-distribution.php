// === NAME - DESCRIPTION: Lab: Name the Distribution - normal, normal-for-a-proportion, and Student t with df = 7 for the three surveys ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's opening distribution decision (Try It Now 8.6.1). Parts: (a) choices - the
// Television Survey's distribution (b) choices - the Language Survey's distribution
// (c) choices - the Jeans Survey's distribution.
// Invariant: all three answers are constant on every seed.

$anstypes = array("choices", "choices", "choices")

$questions[0] = array(
  "The normal distribution &mdash; the problem hands you `sigma = 2`, so the sample mean follows a normal curve.",
  "Student's t distribution &mdash; sigma is unknown.",
  "The binomial distribution &mdash; the data are counts."
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  "The normal distribution for a proportion &mdash; the parameter is p, and the claim is about a percentage rather than an average.",
  "Student's t distribution &mdash; the sample is small.",
  "The chi-square distribution &mdash; the data are categorical."
)
$answer[1] = 0
$noshuffle[1] = "all"

$questions[2] = array(
  "Student's t distribution with df = 7 &mdash; sigma is withheld and n = 8 is small.",
  "The normal distribution &mdash; sigma is known.",
  "The binomial distribution &mdash; the data are counts."
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
      <p><span class="term-label">Part (a) &mdash; Television Survey.</span> The problem says "Assume that `sigma = 2`." You are handed the population standard deviation, so the test uses the normal distribution.</p>
      <p><span class="term-label">Part (b) &mdash; Language Survey.</span> The claim is about a percentage of people, not an average: 42.3% of Californians speak a language other than English at home. The parameter is a population proportion, so the test uses the normal distribution built from a proportion.</p>
      <p><span class="term-label">Part (c) &mdash; Jeans Survey.</span> You are told the population is normal, you are told to survey eight people, and you are told nothing at all about `sigma`. With `sigma` unknown you estimate it with the sample standard deviation, and that substitution is exactly what the Student t distribution exists to account for. Degrees of freedom are `n - 1 = 7`.</p>
      <p>The deciding facts, in order: `sigma` was given; the parameter is a proportion; `sigma` was withheld and `n` is small.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">The lab runs three surveys: the <b>Television Survey</b> (Americans watch 4 hours per day, `sigma = 2` known), the <b>Language Survey</b> (42.3% of Californians speak a language other than English at home), and the <b>Jeans Survey</b> (young adults own 3 pairs of jeans on average, population normal, survey 8 people, `sigma` unknown).</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which distribution does the Television Survey use?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which distribution does the Language Survey use?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which distribution does the Jeans Survey use?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
