// === NAME - DESCRIPTION: Rebuild the Chances from the Cumulative Table - Work backward from F(x): find P(X = 3) as a jump, P(X > 3) as the leftover, and the median as the first total to reach 0.5 ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Five cumulative tables (x = 1..5, F(5) = 1). Parts: (a) numfunc - P(X = 3) = F(3) - F(2); (b) numfunc - P(X > 3) = 1 - F(3);
// (c) numfunc - median, the first x with F(x) >= 0.5. Invariant: no F(x) equals 0.5, so the median is never a tie (checked in the generator).

$anstypes = array("numfunc", "numfunc", "numfunc")

$f1s = array("0.10", "0.20", "0.35", "0.05", "0.10")
$f2s = array("0.25", "0.45", "0.60", "0.15", "0.30")
$f3s = array("0.55", "0.70", "0.75", "0.40", "0.45")
$f4s = array("0.85", "0.90", "0.95", "0.70", "0.65")
$p3s = array(0.3, 0.25, 0.15, 0.25, 0.15)
$gts = array(0.45, 0.3, 0.25, 0.6, 0.55)
$meds = array(3, 3, 2, 4, 4)
$ctxs = array("A call center counts the rings before a call is answered. `X` is the number of rings, from 1 to 5.", "A bus stop counts the buses that pass in a ten-minute window. `X` is the number of buses, from 1 to 5.", "A library counts the books a visitor borrows. `X` is the number of books, from 1 to 5.", "A game awards a level from 1 to 5 to each player. `X` is the level awarded.", "A survey asks households how many pets they own, from 1 to 5. `X` is the number of pets.")

$picked = jointrandfrom($f1s, $f2s, $f3s, $f4s, $p3s, $gts, $meds, $ctxs)
$F1 = $picked[0]
$F2 = $picked[1]
$F3 = $picked[2]
$F4 = $picked[3]
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
      <p><span class="term-label">Part (a).</span> A running total jumps by the chance of the value it reaches. `P(X = 3) = F(3) - F(2) = ' . $F3 . ' - ' . $F2 . ' = ` <b>' . $answer[0] . '</b>.</p>
      <p><span class="term-label">Part (b).</span> More than 3 is everything the running total has not yet counted at `x = 3`: `P(X > 3) = 1 - F(3) = 1 - ' . $F3 . ' = ` <b>' . $answer[1] . '</b>.</p>
      <p><span class="term-label">Part (c).</span> The median is the first value whose running total reaches 0.5. The running totals are ' . $F1 . ', ' . $F2 . ', ' . $F3 . ', ' . $F4 . ', 1. The first at least 0.5 is at `x = ` <b>' . $answer[2] . '</b>.</p>
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
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">1</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">2</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">3</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">4</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">5</td>
      </tr>
      <tr>
        <th style="border:1px solid #21242c; padding:6px 14px; background:#f0f4ff;">`F(x)`</th>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$F1</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$F2</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$F3</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$F4</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">1</td>
      </tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is `P(X = 3)`?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is `P(X > 3)`?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is the median of `X`? It is the first value whose running total reaches 0.5.
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
