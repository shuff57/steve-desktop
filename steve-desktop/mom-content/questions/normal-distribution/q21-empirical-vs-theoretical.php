// === NAME - DESCRIPTION: Empirical vs Theoretical - the counted probability against the model's probability ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's opening distinction (Try It Now 5.1 shape) on the fixed 12-value pilot dataset:
// 125.9, 126.4, 127.1, 127.8, 128.3, 128.9, 129.4, 130.2, 130.8, 131.5, 132.6, 134.1.
// Parts: (a) numfunc - empirical P(X > 130) = 5/12 = 0.4167
// (b) numfunc - theoretical P(X > 130) from N(129.42, 2.52) ~ 0.4085
// (c) choices - which is which and why they differ.
// Invariant: (a) = 0.4167, (b) ~ 0.4085, (c) is constant on every seed.

loadlibrary("stats");

$anstypes = array("numfunc", "numfunc", "choices")

$answer[0] = 0.4167
$abstolerance[0] = 0.005

$z = (130 - 129.4167) / 2.5207
$probB = 1 - normalcdf($z)
$answer[1] = $probB
$reltolerance[1] = 0.02
$abstolerance[1] = 0.003

$questions[2] = array(
  "0.4167 is empirical (counted from the 12 sampled times) and 0.4085 is theoretical (from the normal model) &mdash; they differ because the empirical one is a fact about the sample while the theoretical one is a claim about every lap the model describes",
  "0.4085 is empirical (counted from the 12 sampled times) and 0.4167 is theoretical (from the normal model) &mdash; they differ because the model is approximate",
  "Both are empirical &mdash; they differ because the sample was too small",
  "Both are theoretical &mdash; they differ because the model was fitted to different data"
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
      <p><span class="term-label">Part (a) &mdash; the empirical probability.</span> Five of the twelve pilot times are above 130 seconds: 130.2, 130.8, 131.5, 132.6, and 134.1. So `P(X > 130) = 5/12 = 0.4167`. That number is a fact about the 12 values on the worksheet.</p>
      <p><span class="term-label">Part (b) &mdash; the theoretical probability.</span> The pilot model is `N(129.42, 2.52)`. Standardize: `z = (130 - 129.4167)/2.5207 ~= 0.2314`, so `P(X > 130) = 1 - P(Z < 0.2314) ~= 0.4085`. No lap time was consulted to produce it.</p>
      <p><span class="term-label">Part (c) &mdash; why they differ.</span> They are answers to different questions. The empirical number describes one sample of 12; draw a different 12 and it will move. The theoretical number describes every lap the model claims the racer will ever run, and it will not move unless you change mu or sigma. They land within a hundredth of each other, which is good news for the normal model.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A group ran a short pilot before the lap-time lab and sampled twelve lap times, already sorted: 125.9, 126.4, 127.1, 127.8, 128.3, 128.9, 129.4, 130.2, 130.8, 131.5, 132.6, 134.1. Their theoretical model is `N(129.42, 2.52)`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The empirical probability that a randomly chosen pilot lap time is more than 130 seconds. (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The theoretical probability that a lap time is more than 130 seconds, from `N(129.42, 2.52)`. (Round to 4 decimal places.)
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which is which, and why are they not equal?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
