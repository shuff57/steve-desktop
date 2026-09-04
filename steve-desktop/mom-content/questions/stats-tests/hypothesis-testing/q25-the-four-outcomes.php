// === NAME - DESCRIPTION: The Four Outcomes - the 2x2 table of decisions crossed with the truth, and which two are the errors ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A scenario with a stated H0. Parts: (a) choices - do not reject + H0 true (correct)
// (b) choices - reject + H0 true (Type I) (c) choices - do not reject + H0 false (Type II)
// (d) choices - reject + H0 false (correct, power).
// Invariant: all four answers are constant per scenario and match the table.

$anstypes = array("choices", "choices", "choices", "choices")

$contexts = array(
  "A medical test screens for a disease. The null hypothesis is `H_0`: the patient does NOT have the disease; the alternative is `H_a`: the patient HAS the disease.",
  "A quality-control team tests whether bolts coming off a production line have the target mean diameter. The null hypothesis is `H_0: mu = 10` mm; the alternative is `H_a: mu != 10` mm.",
  "A smoke-detection system makes a decision each minute. The null hypothesis is `H_0`: there is NO fire; the alternative is `H_a`: there IS a fire."
)

$i = rand(0, count($contexts)-1)
$ctx = $contexts[$i]

$questions[0] = array(
  "Correct outcome: the decision matches the truth.",
  "Type I error: a true null got rejected anyway.",
  "Type II error: a real effect was missed.",
  "The power of the test."
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  "Correct outcome: the decision matches the truth.",
  "Type I error: a true null got rejected anyway.",
  "Type II error: a real effect was missed.",
  "The power of the test."
)
$answer[1] = 1
$noshuffle[1] = "all"

$questions[2] = array(
  "Correct outcome: the decision matches the truth.",
  "Type I error: a true null got rejected anyway.",
  "Type II error: a real effect was missed.",
  "The power of the test."
)
$answer[2] = 2
$noshuffle[2] = "all"

$questions[3] = array(
  "Correct outcome: the decision matches the truth.",
  "Type I error: a true null got rejected anyway.",
  "Type II error: a real effect was missed.",
  "The power of the test."
)
$answer[3] = 3
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
      <p><span class="term-label">The 2x2 table.</span> Cross the two truths with the two decisions and you get four combinations. Two of them are the ones you want; the other two are the errors this section is named after.</p>
      <table style="border-collapse:collapse; margin:0.5em 0; font-size:14px;">
        <tr><td style="border:1px solid #ccc; padding:6px 10px;"></td><td style="border:1px solid #ccc; padding:6px 10px;"><b>`H_0` is actually true</b></td><td style="border:1px solid #ccc; padding:6px 10px;"><b>`H_0` is actually false</b></td></tr>
        <tr><td style="border:1px solid #ccc; padding:6px 10px;"><b>Do not reject `H_0`</b></td><td style="border:1px solid #ccc; padding:6px 10px;">Correct outcome</td><td style="border:1px solid #ccc; padding:6px 10px;">Type II error</td></tr>
        <tr><td style="border:1px solid #ccc; padding:6px 10px;"><b>Reject `H_0`</b></td><td style="border:1px solid #ccc; padding:6px 10px;">Type I error</td><td style="border:1px solid #ccc; padding:6px 10px;">Correct outcome (power)</td></tr>
      </table>
      <p><span class="term-label">The diagonal fact.</span> The two errors sit on opposite diagonals, and each one is tied to a specific decision: a Type I error can only happen on a run where you rejected, and a Type II error can only happen on a run where you did not.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> You do not reject `H_0` and `H_0` is true. What is this outcome?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> You reject `H_0` and `H_0` is true. What is this outcome?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> You do not reject `H_0` and `H_0` is false. What is this outcome?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> You reject `H_0` and `H_0` is false. What is this outcome?
    <span style="margin-left:8px;">$answerbox[3]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
