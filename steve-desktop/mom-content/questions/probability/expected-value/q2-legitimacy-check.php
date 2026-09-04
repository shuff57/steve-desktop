// === NAME - DESCRIPTION: Legitimate Distribution Check - Apply the two characteristics to a candidate table ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Five candidate tables. Three shapes:
//   legit     - every entry in [0,1], column sums to exactly 1          -> (a) Yes, (c) Both hold
//   sum-fail  - entries in [0,1], column sums to 0.95 or 1.10           -> (a) No,  (c) do not sum to 1
//   entry-fail- one entry negative, column still sums to exactly 1      -> (a) No,  (c) negative or > 1
// Parts: (a) choices valid?, (b) number column sum, (c) choices which characteristic fails.
// Invariant: on every seed, (a) is Yes iff the sum is exactly 1 and no entry is out of [0,1],
// and (c) names the failure that is actually present.

$anstypes = array("choices", "numfunc", "choices")

$x0s = array(0, 0, 0, 0, 0, 0)
$x1s = array(1, 1, 1, 1, 1, 1)
$x2s = array(2, 2, 2, 2, 2, 2)
$x3s = array(3, 3, 3, 3, 3, 3)
$x4s = array(4, 4, 4, 4, 4, 4)

$p0s = array("0.10", "0.20", "0.35", "-0.10", "0.15", "0.20")
$p1s = array("0.20", "0.25", "0.25", "0.40", "0.20", "0.15")
$p2s = array("0.30", "0.30", "0.15", "0.30", "0.30", "0.30")
$p3s = array("0.25", "0.10", "0.15", "0.25", "0.25", "0.20")
$p4s = array("0.15", "0.10", "0.10", "0.15", "0.20", "0.15")

$sums = array(1.00, 0.95, 1.00, 1.00, 1.10, 1.00)
$sumDisps = array("1.00", "0.95", "1.00", "1.00", "1.10", "1.00")

$validChoices = array(0, 1, 0, 1, 1, 0)
$failWhich   = array(2, 1, 2, 0, 1, 2)

$contexts = array(
  "A store tracks the number of items a customer returns in a visit. Let `X` be the number of returns, with the distribution below.",
  "A mechanic records the number of cars arriving in an hour. Let `X` be the number of arrivals, with the distribution below.",
  "A bakery counts the batches of muffins sold in a day. Let `X` be the number of batches sold, with the distribution below.",
  "A study records the number of times a patient rings the nurse during a shift. Let `X` be the number of calls, with the distribution below.",
  "A coffee shop tracks the number of specialty drinks sold per hour. Let `X` be the number of drinks, with the distribution below.",
  "A gym counts the number of machines in use at a random moment. Let `X` be the number of machines in use, with the distribution below."
)

$picked = jointrandfrom($x0s, $x1s, $x2s, $x3s, $x4s, $p0s, $p1s, $p2s, $p3s, $p4s, $sums, $sumDisps, $validChoices, $failWhich, $contexts)
$x0 = $picked[0]
$x1 = $picked[1]
$x2 = $picked[2]
$x3 = $picked[3]
$x4 = $picked[4]
$pd0 = $picked[5]
$pd1 = $picked[6]
$pd2 = $picked[7]
$pd3 = $picked[8]
$pd4 = $picked[9]
$sum = $picked[10]
$sumDisplay = $picked[11]
$answer[0] = $picked[12]
$answer[1] = $sum
$answer[2] = $picked[13]
$ctx = $picked[14]
$abstolerance[1] = 0.005

$questions[0] = array(
  "Yes, both characteristics hold",
  "No, at least one characteristic fails"
)
$noshuffle[0] = "all"

$questions[2] = array(
  "A probability is negative or greater than 1",
  "The probabilities do not sum to 1",
  "Both characteristics hold"
)
$noshuffle[2] = "all"

$sumDisplay = $picked[11]

$verdict = "Both characteristics hold, so the table is a legitimate probability distribution function."
if ($answer[0] == 0 && $answer[2] == 1) { $verdict = "Every entry sits between 0 and 1, but the column sums to " . $sumDisplay . ", not 1: so the table is not a legitimate distribution." }
if ($answer[0] == 0 && $answer[2] == 0) { $verdict = "The column happens to sum to 1, but one entry is negative, and a probability can never be negative: so the table is not a legitimate distribution." }

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
      <p><span class="term-label">The two characteristics.</span> Every probability must be between zero and one inclusive, and the column must sum to exactly one. Both must hold.</p>
      <p><span class="term-label">Part (b): the column sum.</span> Adding the entries gives <b>' . $sumDisplay . '</b>.</p>
      <p><span class="term-label">Part (a) and (c): the verdict.</span> ' . $verdict . '</p>
      <p>A distribution is a budget: you have exactly one unit of probability to spend, and every value gets a share that is neither negative nor more than the whole.</p>
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
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$x0</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$x1</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$x2</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$x3</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$x4</td>
      </tr>
      <tr>
        <th style="border:1px solid #21242c; padding:6px 16px; background:#f0f4ff;">`P(X = x)`</th>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$pd0</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$pd1</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$pd2</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$pd3</td>
        <td style="border:1px solid #21242c; padding:6px 16px; text-align:center;">$pd4</td>
      </tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Is this a legitimate probability distribution function?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What does the `P(X = x)` column sum to?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which characteristic fails, if any?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
