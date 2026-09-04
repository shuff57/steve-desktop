// === NAME - DESCRIPTION: Find the Mistake in an EV Table - One wrong cell in the P(x) or x*P(x) column ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Four expected-value tables, each with EXACTLY ONE wrong cell. Two shapes:
//   sum-fail  - the printed P(x) column does not sum to 1 (one probability wrong)
//   prod-fail - P(x) sums to 1, but one x*P(x) product is misfilled
// Scenarios (verified):
//   S0 sum-fail: x=1,2,3,4 P=0.10,0.20,0.35,0.30 (sums 0.95) wrong row 3 P -> 0.35
//               products match printed P: 0.10,0.40,1.05,1.20
//   S1 prod-fail: x=1,2,3,4 P=0.10,0.20,0.40,0.30 (sums 1.00) wrong row 2 product -> 1.20
//               printed products: 0.10,0.40,1.60,1.20
//   S2 sum-fail: x=2,4,6,8 P=0.20,0.30,0.35,0.25 (sums 1.10) wrong row 2 P -> 0.25
//               products match printed P: 0.40,1.20,2.10,2.00
//   S3 prod-fail: x=2,4,6,8 P=0.20,0.30,0.25,0.25 (sums 1.00) wrong row 2 product -> 1.50
//               printed products: 0.40,1.20,2.60,2.00
// Parts: (a) choices - which column holds the mistake; (b) number - printed P sum;
//        (c) number - the correct value for the wrong cell.
// Invariant: exactly one cell is wrong; (b) is the printed P sum; (c) is the corrected value.

$anstypes = array("choices", "numfunc", "numfunc")

$x0s = array(1, 1, 2, 2)
$x1s = array(2, 2, 4, 4)
$x2s = array(3, 3, 6, 6)
$x3s = array(4, 4, 8, 8)

$p0s = array("0.10", "0.10", "0.20", "0.20")
$p1s = array("0.20", "0.20", "0.30", "0.30")
$p2s = array("0.35", "0.40", "0.35", "0.25")
$p3s = array("0.30", "0.30", "0.25", "0.25")
$pSum = array(0.95, 1.00, 1.10, 1.00)

$pr0s = array("0.10", "0.10", "0.40", "0.40")
$pr1s = array("0.40", "0.40", "1.20", "1.20")
$pr2s = array("1.05", "1.60", "2.10", "2.60")
$pr3s = array("1.20", "1.20", "2.00", "2.00")

// shape: 0 = P-column wrong, 1 = product-column wrong
$shapes = array(0, 1, 0, 1)
$wrongRow = array(3, 2, 2, 2)
$correctVals = array(0.35, 1.20, 0.25, 1.50)

$contexts = array(
  "A game awards points according to the expected value table below. Let `X` be the points from one play.",
  "A store tracks the number of items returned per visit. Let `X` be the number of returns, with the expected value table below.",
  "A bakery counts batches sold per day. Let `X` be the number of batches, with the expected value table below.",
  "A clinic records the number of appointments missed per year. Let `X` be the number missed, with the expected value table below."
)

$picked = jointrandfrom($x0s, $x1s, $x2s, $x3s, $p0s, $p1s, $p2s, $p3s, $pSum, $pr0s, $pr1s, $pr2s, $pr3s, $shapes, $wrongRow, $correctVals, $contexts)
$x0 = $picked[0]
$x1 = $picked[1]
$x2 = $picked[2]
$x3 = $picked[3]
$pd0 = $picked[4]
$pd1 = $picked[5]
$pd2 = $picked[6]
$pd3 = $picked[7]
$sumP = $picked[8]
$pr0 = $picked[9]
$pr1 = $picked[10]
$pr2 = $picked[11]
$pr3 = $picked[12]
$shape = $picked[13]
$wRow = $picked[14]
$correctVal = $picked[15]
$ctx = $picked[16]

$answer[0] = $shape
$answer[1] = $sumP
$answer[2] = $correctVal
$abstolerance[1] = 0.005
$abstolerance[2] = 0.005

$questions[0] = array(
  "The P(X = x) column",
  "The x*P(x) column"
)
$noshuffle[0] = "all"

$xs = array($x0, $x1, $x2, $x3)
$ps = array($pd0, $pd1, $pd2, $pd3)
$prs = array($pr0, $pr1, $pr2, $pr3)
$wrongRowOne = $wRow + 1

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
    <div class="sol-body">';

$sg = '<p><span class="term-label">Part (b): the P(x) column sum.</span> Adding the printed probabilities gives <b>' . $sumP . '</b>.</p>'
if ($shape == 0) {
  $sg = $sg . '<p><span class="term-label">The mistake is in the P(X = x) column.</span> A valid distribution must sum to exactly 1, and this one sums to ' . $sumP . '. The wrong cell is row ' . $wrongRowOne . ' (x = ' . $xs[$wRow] . '), which prints ' . $ps[$wRow] . ' but should be <b>' . $correctVal . '</b>. With that correction the column sums to 1.00.</p>'
} else {
  $sg = $sg . '<p><span class="term-label">The mistake is in the x*P(x) column.</span> The P(X = x) column sums to exactly 1.00, so the probabilities are fine: but row ' . $wrongRowOne . ' (x = ' . $xs[$wRow] . ') prints ' . $prs[$wRow] . ' for the product, and ' . $xs[$wRow] . ' * ' . $ps[$wRow] . ' is <b>' . $correctVal . '</b>, not ' . $prs[$wRow] . '.</p>'
}
$sg = $sg . '<p><span class="term-label">The two habits.</span> First, always check that the P(x) column sums to 1; if it does not, either a probability is missing or one is wrong. Second, keep the arithmetic honest: each product is x * P(x), and a single misfilled product flips the whole expected value.</p>'

$solutionguide = $solutionguide . $sg . '
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">$ctx Exactly one cell of the table below is filled in incorrectly.</p>
    <table style="border-collapse:collapse; margin:0 auto; font-size:16px;">
      <tr>
        <th style="border:1px solid #21242c; padding:6px 16px; background:#f0f4ff;">Outcome</th>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$x0</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$x1</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$x2</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$x3</td>
      </tr>
      <tr>
        <th style="border:1px solid #21242c; padding:6px 16px; background:#f0f4ff;">P(X = x)</th>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$pd0</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$pd1</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$pd2</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$pd3</td>
      </tr>
      <tr>
        <th style="border:1px solid #21242c; padding:6px 16px; background:#f0f4ff;">x*P(x)</th>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$pr0</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$pr1</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$pr2</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$pr3</td>
      </tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which column contains the mistake?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What does the P(X = x) column sum to as printed?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is the correct value for the wrong cell?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
