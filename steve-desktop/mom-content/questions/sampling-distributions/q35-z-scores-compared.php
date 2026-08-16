// === NAME - DESCRIPTION: The Two z-Scores Compared - the $2.10 individual vs the $0.824 average ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's z-score comparison (Try It Now 6.4 step 3).
// Parts: (a) numfunc - z of the individual $2.10 against sigma: (2.10 - 0.70)/0.5122 ~ 2.73
// (b) numfunc - z of the group average $0.824 against the SE: (0.824 - 0.70)/0.2291 ~ 0.54
// (c) choices - which number you divide by says which question you asked.
// Invariant: ~ 2.73 and ~ 0.54, (c) constant on every seed.

$anstypes = array("numfunc", "numfunc", "choices")

$answer[0] = 2.73
$answer[1] = 0.54
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005

$questions[2] = array(
  "Which number you divide by says which question you asked &mdash; an individual is measured against sigma, an average against the standard error",
  "The z-score is the same for both because they come from the same data",
  "The individual is always the one that is more unusual",
  "The average is always the one that is more unusual"
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
      <p><span class="term-label">Part (a) &mdash; the individual, measured against sigma.</span> The $2.10 is an individual value, so it is measured against `sigma ~= 0.5122`:</p>
      <p>`(2.10 - 0.70)/0.5122 ~= 2.73`</p>
      <p><span class="term-label">Part (b) &mdash; the average, measured against the standard error.</span> The $0.824 is an average of five, so it is measured against `sigma_bar(x) ~= 0.2291`:</p>
      <p>`(0.824 - 0.70)/0.2291 ~= 0.54`</p>
      <p><span class="term-label">Part (c) &mdash; the point.</span> The same group of people is a genuine outlier when you look at its largest member and thoroughly ordinary when you look at its average. Neither reading is wrong &mdash; they answer different questions, and the number you divide by is what says which question you asked. Using `sigma` where `sigma_bar(x)` belongs is the single most common error in the rest of this course, and it always makes a result look less surprising than it really is.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">In the pocket-change lab, the class data has `x-bar = 0.70` and `s ~= 0.5122`. One group of five averaged $0.824, and its largest member carried $2.10. The standard error for averages of five is ~ 0.2291.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The z-score of the individual $2.10, measured against `sigma`. (Round to 2 decimal places.)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The z-score of the group average $0.824, measured against the standard error. (Round to 2 decimal places.)
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Why is the individual an outlier while the average is ordinary?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
