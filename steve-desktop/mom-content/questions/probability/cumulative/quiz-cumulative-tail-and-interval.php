// === NAME - DESCRIPTION: Quiz Cumulative Tail and Interval - Build F(2) from a five-row quiz table, then read at least 3 and between 2 and 3 inclusive ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Four five-row tables. Parts: (a) numfunc - F(2); (b) numfunc - P(X >= 3) = 1 - F(2);
// (c) numfunc - P(2 <= X <= 3) = F(3) - F(1).
// Invariant: all three answers are consistent with the printed rows and lie in [0, 1].

$anstypes = array("numfunc", "numfunc", "numfunc")

$p0s = array("0.10", "0.05", "0.15", "0.08")
$p1s = array("0.20", "0.15", "0.25", "0.22")
$p2s = array("0.35", "0.30", "0.25", "0.30")
$p3s = array("0.25", "0.35", "0.20", "0.25")
$p4s = array("0.10", "0.15", "0.15", "0.15")
$f1s = array(0.30, 0.20, 0.40, 0.30)
$f2s = array(0.65, 0.50, 0.65, 0.60)
$f3s = array(0.90, 0.85, 0.85, 0.85)
$ge3s = array(0.35, 0.50, 0.35, 0.40)
$betweens = array(0.60, 0.65, 0.45, 0.55)

$contexts = array(
  "A four-question quiz is guessed at random. `X` is the number of questions answered correctly.",
  "A student answers five short questions. `X` is the number answered correctly.",
  "A quality check finds 0 to 4 flaws in a printed page. `X` is the number of flaws found.",
  "A cafe sells 0 to 4 slices of pie in an hour. `X` is the number of slices sold."
)

$picked = jointrandfrom($p0s, $p1s, $p2s, $p3s, $p4s, $f1s, $f2s, $f3s, $ge3s, $betweens, $contexts)
$pd0 = $picked[0]
$pd1 = $picked[1]
$pd2 = $picked[2]
$pd3 = $picked[3]
$pd4 = $picked[4]
$f1 = $picked[5]
$answer[0] = $picked[6]
$f3 = $picked[7]
$answer[1] = $picked[8]
$answer[2] = $picked[9]
$ctx = $picked[10]
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
      <p><span class="term-label">Part (a): F(2).</span> Add the rows for 0, 1 and 2: `' . $pd0 . ' + ' . $pd1 . ' + ' . $pd2 . ' = ` <b>' . $answer[0] . '</b>.</p>
      <p><span class="term-label">Part (b): at least 3.</span> At least 3 means `X >= 3`, so take what is left after 2: `1 - F(2) = 1 - ' . $answer[0] . ' = ` <b>' . $answer[1] . '</b>.</p>
      <p><span class="term-label">Part (c): between 2 and 3, inclusive.</span> This means `2 <= X <= 3`. Subtract the total at 1 from the total at 3: `F(3) - F(1) = ' . $f3 . ' - ' . $f1 . ' = ` <b>' . $answer[2] . '</b>.</p>
      <p><span class="term-label">The habit.</span> Convert the words first, then pick the two cumulative entries. "At least 3" needs the complement of the total at 2; "between 2 and 3" needs the total at 3 minus the total at 1.</p>
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
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Build the cumulative table. What is `F(2) = P(X <= 2)`?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find the chance that `X` is at least 3.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find the chance that `X` is between 2 and 3, inclusive.
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
