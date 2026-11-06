// === NAME - DESCRIPTION: Build the Cumulative Column - Add the chances in order and give the running total at x = 1, 2 and 3 ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Five four-row chance tables. Parts: (a) numfunc - F(1); (b) numfunc - F(2); (c) numfunc - F(3).
// Invariant: F(k) is the sum of rows 0 through k on every seed, and F(3) = 1.

$anstypes = array("numfunc", "numfunc", "numfunc")

$p0s = array("0.10", "0.15", "0.05", "0.20", "0.12")
$p1s = array("0.20", "0.35", "0.15", "0.25", "0.18")
$p2s = array("0.40", "0.30", "0.45", "0.15", "0.30")
$p3s = array("0.30", "0.20", "0.35", "0.40", "0.40")
$f1s = array(0.30, 0.50, 0.20, 0.45, 0.30)
$f2s = array(0.70, 0.80, 0.65, 0.60, 0.60)
$f3s = array(1, 1, 1, 1, 1)

$contexts = array(
  "A small shop counts the number of customers waiting when it opens. `X` is the number waiting, from 0 to 3.",
  "A student answers three short questions. `X` is the number answered correctly, from 0 to 3.",
  "A bakery counts the number of loaves left on a shelf at closing. `X` is the number left, from 0 to 3.",
  "A cyclist counts the number of red lights hit on a short route. `X` is the number of red lights, from 0 to 3.",
  "A clinic counts the number of patients who arrive in the first ten minutes. `X` is the number of arrivals, from 0 to 3."
)

$picked = jointrandfrom($p0s, $p1s, $p2s, $p3s, $f1s, $f2s, $f3s, $contexts)
$pd0 = $picked[0]
$pd1 = $picked[1]
$pd2 = $picked[2]
$pd3 = $picked[3]
$answer[0] = $picked[4]
$answer[1] = $picked[5]
$answer[2] = $picked[6]
$ctx = $picked[7]
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
      <p><span class="term-label">The cumulative column.</span> Start at 0 and add one row at a time. The first entry is the row for 0, the next entry adds the row for 1, and so on.</p>
      <p>`F(1) = ' . $pd0 . ' + ' . $pd1 . ' = ` <b>' . $answer[0] . '</b></p>
      <p>`F(2) = ' . $pd0 . ' + ' . $pd1 . ' + ' . $pd2 . ' = ` <b>' . $answer[1] . '</b></p>
      <p>`F(3) = ' . $pd0 . ' + ' . $pd1 . ' + ' . $pd2 . ' + ' . $pd3 . ' = ` <b>' . $answer[2] . '</b>. The last running total is always 1, because it adds every row.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Build the cumulative column. What is `F(1) = P(X <= 1)`?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is `F(2) = P(X <= 2)`?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is `F(3)`, the last running total?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
