// === NAME - DESCRIPTION: Cut-Offs from a Cumulative Table - Find the smallest value whose running total reaches 0.6 and 0.9 ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Five cumulative tables (x = 0..3). Parts: (a) numfunc - the cut-off for 0.6;
// (b) numfunc - the cut-off for 0.9.
// Invariant: each answer is the smallest x with F(x) >= p; the answer is a value of X, not a total.

$anstypes = array("numfunc", "numfunc")

$f0s = array("0.10", "0.20", "0.10", "0.25", "0.05")
$f1s = array("0.35", "0.65", "0.30", "0.55", "0.20")
$f2s = array("0.75", "0.85", "0.92", "0.85", "0.60")
$f3s = array("1.00", "1.00", "1.00", "1.00", "1.00")
$c6s = array(2, 1, 2, 2, 2)
$c9s = array(3, 3, 2, 3, 3)

$contexts = array(
  "A shop records the number of returns it handles in a morning. `X` runs from 0 to 3.",
  "A student records the number of questions answered correctly on a short quiz. `X` runs from 0 to 3.",
  "A machine records the number of defective parts in a batch. `X` runs from 0 to 3.",
  "A cafe records the number of specials sold at lunch. `X` runs from 0 to 3.",
  "A bus records the number of minutes it is late, rounded to whole minutes. `X` runs from 0 to 3."
)

$picked = jointrandfrom($f0s, $f1s, $f2s, $f3s, $c6s, $c9s, $contexts)
$f0 = $picked[0]
$f1 = $picked[1]
$f2 = $picked[2]
$f3 = $picked[3]
$answer[0] = $picked[4]
$answer[1] = $picked[5]
$ctx = $picked[6]

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
      <p><span class="term-label">The rule.</span> Read the cumulative row from 0 upward. The cut-off is the first value whose total reaches the target.</p>
      <p><span class="term-label">Part (a): the 0.6 cut-off.</span> The cumulative row is ' . $f0 . ', ' . $f1 . ', ' . $f2 . ', ' . $f3 . '. The first entry at least 0.6 is at `x = ` <b>' . $answer[0] . '</b>.</p>
      <p><span class="term-label">Part (b): the 0.9 cut-off.</span> The first entry at least 0.9 is at `x = ` <b>' . $answer[1] . '</b>.</p>
      <p><span class="term-label">Why the row is not enough.</span> The cut-off depends on the running total. A single row can be well below the target and still push the total past it.</p>
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
      </tr>
      <tr>
        <th style="border:1px solid #21242c; padding:6px 14px; background:#f0f4ff;">`F(x)`</th>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$f0</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$f1</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$f2</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$f3</td>
      </tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">The cut-off for a chance `p` is the smallest value `x` with `F(x) >= p`.</p>
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the cut-off for 0.6.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find the cut-off for 0.9.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
