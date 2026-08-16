// === NAME - DESCRIPTION: Read Probabilities off a Distribution Table - P(x = k), P(x > k) and P(x >= k) as row sums ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Six scenarios, each a legitimate table over x = 0..5 with probabilities summing to 1.
// Parts:
//   (a) P(X = k1)
//   (b) P(X > k2)   - strict
//   (c) P(X >= k3)  - inclusive, k3 = k2 so the two differ (row k2 has positive probability)
// All precomputed row sums. Invariant: every answer equals the sum of the printed rows, and
// (b) < (c) on every seed because P(X = k2) > 0.

$anstypes = array("numfunc", "numfunc", "numfunc")

$p0s = array("0.10", "0.05", "0.25", "0.20", "0.15", "0.30")
$p1s = array("0.15", "0.10", "0.20", "0.25", "0.10", "0.15")
$p2s = array("0.20", "0.15", "0.15", "0.30", "0.25", "0.20")
$p3s = array("0.25", "0.30", "0.10", "0.15", "0.20", "0.25")
$p4s = array("0.20", "0.25", "0.20", "0.05", "0.15", "0.05")
$p5s = array("0.10", "0.15", "0.10", "0.05", "0.15", "0.05")

$k1s = array(3, 2, 4, 1, 3, 5)
$k2s = array(2, 3, 1, 3, 2, 3)
$k3s = array(2, 3, 1, 3, 2, 3)

$ansA = array(0.25, 0.15, 0.20, 0.25, 0.20, 0.05)
$ansB = array(0.55, 0.40, 0.55, 0.10, 0.50, 0.10)
$ansC = array(0.75, 0.70, 0.75, 0.25, 0.75, 0.35)

$contexts = array(
  "A clinic records the number of appointments a patient misses in a year. Let `X` be the number of missed appointments, with the distribution below.",
  "A parking garage counts the number of cars parked in a random stall row. Let `X` be the number of cars, with the distribution below.",
  "A library tracks the number of books a visitor checks out. Let `X` be the number of books, with the distribution below.",
  "A diner records the number of refills a customer asks for. Let `X` be the number of refills, with the distribution below.",
  "A classroom counts the number of students who raise their hand in a period. Let `X` be the number of students, with the distribution below.",
  "A vet records the number of pets brought in by a family in a year. Let `X` be the number of pets, with the distribution below."
)

$picked = jointrandfrom($p0s, $p1s, $p2s, $p3s, $p4s, $p5s, $k1s, $k2s, $k3s, $ansA, $ansB, $ansC, $contexts)
$pd0 = $picked[0]
$pd1 = $picked[1]
$pd2 = $picked[2]
$pd3 = $picked[3]
$pd4 = $picked[4]
$pd5 = $picked[5]
$k1 = $picked[6]
$k2 = $picked[7]
$k3 = $picked[8]
$answer[0] = $picked[9]
$answer[1] = $picked[10]
$answer[2] = $picked[11]
$ctx = $picked[12]
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
      <p><span class="term-label">The one move.</span> Every probability question about a table reduces to choosing which rows to add. Read the inequality, then point at the rows.</p>
      <p><span class="term-label">Part (a) &mdash; `P(X = ' . $k1 . ')`.</span> The row labelled `x = ' . $k1 . '` carries the probability directly: <b>' . $answer[0] . '</b>.</p>
      <p><span class="term-label">Part (b) &mdash; `P(X > ' . $k2 . ')`.</span> Strictly greater means the rows `x = ' . ($k2 + 1) . '` through `x = 5`, added together: <b>' . $answer[1] . '</b>.</p>
      <p><span class="term-label">Part (c) &mdash; `P(X &ge; ' . $k3 . ')`.</span> Greater or equal means part (b) plus the row `x = ' . $k3 . '` itself: <b>' . $answer[2] . '</b>. Since `P(X = ' . $k3 . ')` is positive, the inclusive probability is strictly larger than the strict one.</p>
      <p>There is no formula to memorize here; it is addition applied to the rows you selected.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">$ctx</p>
    <table style="border-collapse:collapse; margin:0 auto; font-size:16px;">
      <tr>
        <th style="border:1px solid #21242c; padding:6px 16px; background:#f0f4ff;">`x`</th>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">0</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">1</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">2</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">3</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">4</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">5</td>
      </tr>
      <tr>
        <th style="border:1px solid #21242c; padding:6px 16px; background:#f0f4ff;">`P(X = x)`</th>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$pd0</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$pd1</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$pd2</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$pd3</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$pd4</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$pd5</td>
      </tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find `P(X = $k1)`.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find `P(X > $k2)`.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find `P(X &ge; $k3)`.
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
