// === NAME - DESCRIPTION: Missing Value to Cumulative Table - Fill the missing chance from sum-to-1, then read F(2) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Four four-row tables with one cell missing. Parts: (a) numfunc - the missing chance;
// (b) numfunc - F(2) from the completed table.
// Invariant: the completed table sums to exactly 1 and F(2) is the sum of the first three rows.

$anstypes = array("numfunc", "numfunc")

$q0s = array("0.20", "0.10", "0.15", "0.25")
$q1s = array("0.35", "0.30", "?", "0.20")
$q2s = array("0.25", "?", "0.30", "0.30")
$q3s = array("?", "0.25", "0.25", "?")
$missings = array(0.20, 0.35, 0.30, 0.25)
$f2s = array(0.80, 0.75, 0.75, 0.75)
$knownSums = array("0.80", "0.65", "0.70", "0.75")
$f2Texts = array(
  "0.20 + 0.35 + 0.25 = 0.80",
  "0.10 + 0.30 + 0.35 = 0.75",
  "0.15 + 0.30 + 0.30 = 0.75",
  "0.25 + 0.20 + 0.30 = 0.75"
)

$contexts = array(
  "A shop records the number of visits a customer makes in a month. `X` runs from 0 to 3. One chance is missing, and the total is 1.",
  "A cafe records the number of specials sold at lunch. `X` runs from 0 to 3. One chance is missing, and the total is 1.",
  "A clinic records the number of patients who arrive in a ten-minute window. `X` runs from 0 to 3. One chance is missing, and the total is 1.",
  "A student records the number of questions answered correctly on a short quiz. `X` runs from 0 to 3. One chance is missing, and the total is 1."
)

$picked = jointrandfrom($q0s, $q1s, $q2s, $q3s, $missings, $f2s, $contexts, $knownSums, $f2Texts)
$pd0 = $picked[0]
$pd1 = $picked[1]
$pd2 = $picked[2]
$pd3 = $picked[3]
$answer[0] = $picked[4]
$answer[1] = $picked[5]
$ctx = $picked[6]
$knownSum = $picked[7]
$f2Text = $picked[8]
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005
$abstolerance[2] = 0.005

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
      <p><span class="term-label">Part (a): the missing chance.</span> The chances must add to 1. The three known rows add to ' . $knownSum . ', so the missing chance is `1 - ' . $knownSum . ' = ` <b>' . $answer[0] . '</b>.</p>
      <p><span class="term-label">Part (b): F(2).</span> With the table filled, add the rows for 0, 1 and 2: ' . $f2Text . ', so `F(2) = ` <b>' . $answer[1] . '</b>.</p>
      <p><span class="term-label">The order of work.</span> Fill the table first. A cumulative entry read from an incomplete table is wrong even when the later arithmetic is right.</p>
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
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">3</td>
      </tr>
      <tr>
        <th style="border:1px solid #21242c; padding:6px 14px; background:#f0f4ff;">`P(X = x)`</th>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$pd0</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$pd1</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$pd2</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$pd3</td>
      </tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the missing chance (the cell marked ?).
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Using the completed table, find `F(2) = P(X <= 2)`.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
