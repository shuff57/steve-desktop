// === NAME - DESCRIPTION: Build a PDF Table from a Description - recover the buried probability, then compute E(X) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Three prose scenarios (mirrors the section's building-a-table move: name the variable, list the
// values, attach the probabilities). Each buries the smallest case in the last clause, so part (a)
// = 1 - sum(others). Parts:
//   (a) number  - the buried probability
//   (b) numfunc - E(X) of the completed table (precomputed)
//   (c) choices - what the P(x) column must sum to (always 1)
// Invariant: on every seed, the given percentages plus the buried one sum to exactly 1, and (b)
// is the precomputed E(X) of the completed table.

$anstypes = array("numfunc", "numfunc", "choices")

// Context prose (x values and percentages; the LAST clause carries the smallest case)
$contexts = array(
  "A student rides the city bus to school. In a given week, she rides the bus all three days 70% of the time, two days 18% of the time, one day 9% of the time, and no days 3% of the time.",
  "A library tracks how many books a visitor checks out on a visit. A visitor checks out four books 55% of the time, three books 25% of the time, two books 15% of the time, one book 4% of the time, and no books 1% of the time.",
  "A volunteer coordinator records how many shifts a volunteer works in a week. A volunteer works all three shifts 62% of the time, two shifts 20% of the time, one shift 12% of the time, and no shifts 6% of the time."
)

$vars = array(
  "Let `X` = the number of days the student rides the bus in a week.",
  "Let `X` = the number of books a visitor checks out on a visit.",
  "Let `X` = the number of shifts a volunteer works in a week."
)

// Known probabilities as display strings, in ascending x order, the buried slot last but shown as "?"
$knowns = array(
  "0.70, 0.18, 0.09",
  "0.55, 0.25, 0.15, 0.04",
  "0.62, 0.20, 0.12"
)

$knownSums = array("0.97", "0.99", "0.94")
$burieds = array(0.03, 0.01, 0.06)
$evs     = array(2.55, 3.29, 2.38)
$maxXs   = array(3, 4, 3)

$i = rand(0, 2)
$ctx = $contexts[$i]
$var = $vars[$i]
$known = $knowns[$i]
$knownSum = $knownSums[$i]
$buried = $burieds[$i]
$answer[0] = $buried
$answer[1] = $evs[$i]
$maxX = $maxXs[$i]
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005

$questions[2] = array(
  "1",
  "0",
  "0.5",
  "Whatever the percentages happen to add to"
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
      <p><span class="term-label">Step 1: name the variable.</span> ' . $var . '</p>
      <p><span class="term-label">Step 2: list the values.</span> The description covers every case from `x = 0` up to `x = ' . $maxX . '`, including the smallest one in the last clause. A value you forget to list is probability you will fail to account for.</p>
      <p><span class="term-label">Step 3: attach the probabilities.</span> Convert each percentage to a decimal. The known probabilities are ' . $known . '; the one buried in the last clause is found by the check in part (a).</p>
      <p><span class="term-label">Part (a): the buried probability.</span> The column must sum to 1, so the missing entry is <b>1 &minus; (' . $known . ') = 1 &minus; ' . $knownSum . ' = ' . $answer[0] . '</b>.</p>
      <p><span class="term-label">Part (b): E(X).</span> With the completed table, `E(X) = sum x cdot P(X = x) = ` <b>' . $answer[1] . '</b>.</p>
      <p><span class="term-label">Part (c): the column sum.</span> It must sum to exactly 1. If your built table does not, you dropped a value in step 2.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> $ctx</p>
    <p style="margin:0;">Build the probability distribution table for this situation, then use it.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the probability of the smallest case, `P(X = 0)`?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Compute `E(X)` for the completed table.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What must the `P(X = x)` column sum to?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
