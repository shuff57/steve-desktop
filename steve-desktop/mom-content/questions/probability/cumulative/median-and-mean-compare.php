// === NAME - DESCRIPTION: Mean and the Largest Chance - Compute the mean from a three-row table, then test the claim that the mean is the value with the largest chance ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Four three-row tables. Parts: (a) numfunc - the mean; (b) choices - is the mean the value with the largest chance?
// Invariant: the mean is sum x P(x); the answer index matches whether the mean and the most likely
// value coincide on this seed.

$anstypes = array("numfunc", "choices")

$p0s = array("0.25", "0.30", "0.10", "0.20")
$p1s = array("0.35", "0.40", "0.60", "0.30")
$p2s = array("0.40", "0.30", "0.30", "0.50")
$means = array(1.15, 1.00, 1.20, 1.30)
$answers = array(0, 2, 0, 0)

$contexts = array(
  "A student records the number of hours spent on homework in a night, rounded to whole hours. `X` runs from 0 to 2.",
  "A cafe records the number of refills a customer asks for. `X` runs from 0 to 2.",
  "A shop records the number of returns it handles in a morning. `X` runs from 0 to 2.",
  "A clinic records the number of patients who arrive in a ten-minute window. `X` runs from 0 to 2."
)

$picked = jointrandfrom($p0s, $p1s, $p2s, $means, $answers, $contexts)
$pd0 = $picked[0]
$pd1 = $picked[1]
$pd2 = $picked[2]
$answer[0] = $picked[3]
$answer[1] = $picked[4]
$ctx = $picked[5]
$abstolerance[0] = 0.005

$questions[1] = array(
  "No. The mean can fall between values, and the value with the largest chance is a different value.",
  "Yes. The mean is always the value with the largest chance.",
  "Yes, here the mean and the value with the largest chance are the same, but that is not a rule.",
  "No. The mean is always larger than the value with the largest chance."
)
$noshuffle[1] = "all"

$largestText = "The largest chance is at 2, which is not the mean."
if ($answer[1] == 2) { $largestText = "The largest chance is at 1, which happens to equal the mean here." }

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
      <p><span class="term-label">Part (a): the mean.</span> Multiply each value by its chance and add: `0(' . $pd0 . ') + 1(' . $pd1 . ') + 2(' . $pd2 . ') = ` <b>' . $answer[0] . '</b>.</p>
      <p><span class="term-label">Part (b): the claim.</span> ' . $largestText . ' The mean is a balance point, so it can land between values or away from the tallest row. The correct choice is <b>the one that matches this table</b>.</p>
      <p><span class="term-label">The distinction.</span> The mean need not be a value that `X` can take. A table with half its chance at 1 and half at 2 has mean 1.5, which is not a row.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">$ctx</p>
    <table style="border-collapse:collapse; margin:0 auto; font-size:16px;">
      <tr>
        <th style="border:1px solid #21242c; padding:6px 14px; background:#f0f4ff;">`x`</th>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">0</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">1</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">2</td>
      </tr>
      <tr>
        <th style="border:1px solid #21242c; padding:6px 14px; background:#f0f4ff;">`P(X = x)`</th>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$pd0</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$pd1</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$pd2</td>
      </tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the mean (Definition 4.2.1).
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> A student says the mean is the value with the largest chance. Is that true here?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
