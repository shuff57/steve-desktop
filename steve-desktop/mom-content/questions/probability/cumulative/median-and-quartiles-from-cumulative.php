// === NAME - DESCRIPTION: Quartiles from a Cumulative Table - Use the cut-off rule to find the first quartile and the third quartile ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Five cumulative tables (x = 0..4). Parts: (a) numfunc - Q1; (b) numfunc - Q3.
// Invariant: each answer is the smallest x with F(x) >= 0.25 and 0.75, and Q1 < Q3.

$anstypes = array("numfunc", "numfunc")

$f0s = array("0.10", "0.10", "0.20", "0.15", "0.30")
$f1s = array("0.30", "0.35", "0.45", "0.40", "0.55")
$f2s = array("0.55", "0.60", "0.70", "0.65", "0.75")
$f3s = array("0.85", "0.80", "0.90", "0.85", "0.90")
$f4s = array("1.00", "1.00", "1.00", "1.00", "1.00")
$q1s = array(1, 1, 1, 1, 0)
$q3s = array(3, 3, 3, 3, 2)

$contexts = array(
  "A library records the number of books a visitor checks out. `X` runs from 0 to 4.",
  "A cafe records the number of refills a customer asks for. `X` runs from 0 to 4.",
  "A nurse records the number of patients seen in a short shift. `X` runs from 0 to 4.",
  "A student records the number of hours spent on homework in a night, rounded to whole hours. `X` runs from 0 to 4.",
  "A shop records the number of returns it handles in a morning. `X` runs from 0 to 4."
)

$picked = jointrandfrom($f0s, $f1s, $f2s, $f3s, $f4s, $q1s, $q3s, $contexts)
$f0 = $picked[0]
$f1 = $picked[1]
$f2 = $picked[2]
$f3 = $picked[3]
$f4 = $picked[4]
$answer[0] = $picked[5]
$answer[1] = $picked[6]
$ctx = $picked[7]

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
      <p><span class="term-label">The rule.</span> A cut-off is the smallest value `x` whose running total reaches the target. Read the `F(x)` row from 0 upward and stop at the first entry that is at least the target.</p>
      <p><span class="term-label">Part (a): the first quartile.</span> The target is 0.25. The cumulative row is ' . $f0 . ', ' . $f1 . ', ' . $f2 . ', ' . $f3 . ', ' . $f4 . '. The first entry at least 0.25 is at `x = ` <b>' . $answer[0] . '</b>.</p>
      <p><span class="term-label">Part (b): the third quartile.</span> The target is 0.75. The first entry at least 0.75 is at `x = ` <b>' . $answer[1] . '</b>.</p>
      <p><span class="term-label">Why the answer is a value, not a total.</span> The cut-off is a value of `X`, read from the left column. The total that reaches the target is the evidence, not the answer.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">$ctx The table gives the cumulative chance `F(x) = P(X <= x)`.</p>
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
        <th style="border:1px solid #21242c; padding:6px 14px; background:#f0f4ff;">`F(x)`</th>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$f0</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$f1</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$f2</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$f3</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$f4</td>
      </tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">The cut-off for a chance `p` is the smallest value `x` with `F(x) >= p`.</p>
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the first quartile, the cut-off for 0.25.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find the third quartile, the cut-off for 0.75.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
