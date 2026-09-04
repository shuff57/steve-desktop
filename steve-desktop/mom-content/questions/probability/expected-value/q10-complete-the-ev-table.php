// === NAME - DESCRIPTION: Complete the Expected Value Table - Fill the x*P(x) column and read off mu ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Four EV tables with the x*P(x) column left blank (mirrors 4.2.1/4.2.2). Parts:
//   (a) numfunc - the missing product for a named row
//   (b) numfunc - the sum of the x*P(x) column (= mu)
//   (c) choices - what the x*P(x) column sums to (constant: the expected value)
// Invariant: (a) = x_k * P(x_k) for the named row; (b) = precomputed mu; (c) constant.

$anstypes = array("numfunc", "numfunc", "choices")

$x0s = array(0, 1, 2, 3)
$x1s = array(1, 2, 4, 6)
$x2s = array(2, 3, 6, 9)
$x3s = array(3, 4, 8, 12)

$p0s = array("0.2", "0.1", "0.3", "0.2")
$p1s = array("0.2", "0.2", "0.4", "0.3")
$p2s = array("0.4", "0.3", "0.2", "0.4")
$p3s = array("0.2", "0.4", "0.1", "0.1")

// which row (0..3) part (a) asks about, and that row's correct product
$askRow = array(2, 1, 3, 2)
$rowProds = array(0.8, 0.4, 0.8, 3.6)
$mus = array(1.6, 3.0, 4.2, 7.2)

$contexts = array(
  "A coffee shop tracks the number of specialty drinks a customer orders. Let `X` be the number of drinks, with the distribution below.",
  "A parking lot counts the number of cars per row. Let `X` be the number of cars, with the distribution below.",
  "A bookstore records the number of notebooks a customer buys. Let `X` be the number of notebooks, with the distribution below.",
  "A farmer counts the number of crates of apples sold per day. Let `X` be the number of crates, with the distribution below."
)

$picked = jointrandfrom($x0s, $x1s, $x2s, $x3s, $p0s, $p1s, $p2s, $p3s, $askRow, $rowProds, $mus, $contexts)
$x0 = $picked[0]
$x1 = $picked[1]
$x2 = $picked[2]
$x3 = $picked[3]
$pd0 = $picked[4]
$pd1 = $picked[5]
$pd2 = $picked[6]
$pd3 = $picked[7]
$ask = $picked[8]
$rowProd = $picked[9]
$mu = $picked[10]
$ctx = $picked[11]

$answer[0] = $rowProd
$answer[1] = $mu
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005

$questions[2] = array(
  "The expected value: the long-run average of X",
  "The probability that X takes its largest value",
  "1, because the probabilities sum to 1",
  "The largest value X can take"
)
$answer[2] = 0
$noshuffle[2] = "all"

$xs = array($x0, $x1, $x2, $x3)
$ps = array($pd0, $pd1, $pd2, $pd3)
$askOne = $ask + 1
$askX = $xs[$ask]

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
      <p><span class="term-label">The one move.</span> Each row of the `x * P(x)` column is the value times its probability. Multiply, then add the column.</p>
      <p><span class="term-label">Part (a): row ' . $askOne . '.</span> Row ' . $askOne . ' holds `x = ' . $xs[$ask] . '` and `P(X = x) = ' . $ps[$ask] . '`, so the product is `(' . $xs[$ask] . ')(' . $ps[$ask] . ') = ` <b>' . $rowProd . '</b>.</p>
      <p><span class="term-label">Part (b): the column sum.</span> Adding all four products gives `mu = E(X) = ` <b>' . $mu . '</b>. That is the long-term average: if the process were repeated many times, this is what the average would settle down to.</p>
      <p><span class="term-label">Part (c): what the column sums to.</span> The `x * P(x)` column sums to the expected value: the balance point of the distribution. It is not a probability, and it is not a value the variable actually takes (a family can have an expected 2.3 children, and no family has 2.3 children).</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">$ctx The `x * P(x)` column of the expected value table is blank.</p>
    <table style="border-collapse:collapse; margin:0 auto; font-size:16px;">
      <tr>
        <th style="border:1px solid #21242c; padding:6px 16px; background:#f0f4ff;">`x`</th>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$x0</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$x1</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$x2</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$x3</td>
      </tr>
      <tr>
        <th style="border:1px solid #21242c; padding:6px 16px; background:#f0f4ff;">`P(X = x)`</th>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$pd0</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$pd1</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$pd2</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$pd3</td>
      </tr>
      <tr>
        <th style="border:1px solid #21242c; padding:6px 16px; background:#f0f4ff;">`x * P(x)`</th>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">&nbsp;</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">&nbsp;</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">&nbsp;</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">&nbsp;</td>
      </tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Fill in the missing product for the row `x = $askX`.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What does the completed `x * P(x)` column sum to?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What does the `x * P(x)` column sum to, in words?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
