// === NAME - DESCRIPTION: Row Versus Running Total - Diagnose a student who reads P(X = 2) as P(X <= 2), then give both values and the jump at x = 2 ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Four five-row tables. Parts: (a) choices - what the student confused; (b) numfunc - the correct
// P(X <= 2); (c) numfunc - the jump at x = 2, which is P(X = 2).
// Invariant: the row value and the running total are different on every seed; (b) uses F(2) and
// (c) uses the row for 2.

$anstypes = array("choices", "numfunc", "numfunc")

$p0s = array("0.05", "0.10", "0.08", "0.15")
$p1s = array("0.15", "0.20", "0.12", "0.10")
$p2s = array("0.30", "0.25", "0.30", "0.25")
$p3s = array("0.35", "0.30", "0.35", "0.30")
$p4s = array("0.15", "0.15", "0.15", "0.20")
$f2s = array(0.50, 0.55, 0.50, 0.50)
$row2s = array(0.30, 0.25, 0.30, 0.25)

$contexts = array(
  "A student records the number of questions answered correctly on a five-question quiz. `X` runs from 0 to 4.",
  "A cafe records the number of slices of pie sold in an hour. `X` runs from 0 to 4.",
  "A machine records the number of defective parts in a batch. `X` runs from 0 to 4.",
  "A clinic records the number of patients who arrive in a ten-minute window. `X` runs from 0 to 4."
)

$picked = jointrandfrom($p0s, $p1s, $p2s, $p3s, $p4s, $f2s, $row2s, $contexts)
$pd0 = $picked[0]
$pd1 = $picked[1]
$pd2 = $picked[2]
$pd3 = $picked[3]
$pd4 = $picked[4]
$f2 = $picked[5]
$row2 = $picked[6]
$ctx = $picked[7]
$answer[0] = 0
$answer[1] = $f2
$answer[2] = $row2
$abstolerance[1] = 0.005
$abstolerance[2] = 0.005

$questions[0] = array(
  "The student read the row for 2 as the running total at 2.",
  "The student added the rows in the wrong order.",
  "The student used the complement rule for the wrong event.",
  "The student found the mean instead of the total."
)
$noshuffle[0] = "all"

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
      <p><span class="term-label">Part (a): the confusion.</span> The row for 2 is the chance of exactly 2. The total at 2 adds the rows for 0, 1 and 2. The student read the row as the total. The answer is <b>' . $questions[0][0] . '</b>.</p>
      <p><span class="term-label">Part (b): the correct total.</span> `F(2) = ' . $pd0 . ' + ' . $pd1 . ' + ' . $pd2 . ' = ` <b>' . $answer[1] . '</b>.</p>
      <p><span class="term-label">Part (c): the jump at 2.</span> On the step graph the jump at a value has height `P(X = x)`, which is the row, not the total. The jump at 2 has height <b>' . $answer[2] . '</b>.</p>
      <p><span class="term-label">Why this matters.</span> A row is one jump. A running total is the height after the jump. Reading a total as a row is the most common mistake in this section.</p>
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
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">4</td>
      </tr>
      <tr>
        <th style="border:1px solid #21242c; padding:6px 14px; background:#f0f4ff;">`P(X = x)`</th>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$pd0</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$pd1</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$pd2</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$pd3</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$pd4</td>
      </tr>
    </table>
    <p style="margin:10px 0 0 0;">A student says: `P(X <= 2) = P(X = 2) = $row2`. The total at 2 is `$f2`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What did the student confuse?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find the correct `P(X <= 2)`.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> On the step graph, how tall is the jump at `x = 2`?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
