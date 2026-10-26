// === NAME - DESCRIPTION: Complete the PDF Table - Use sum-to-1 to find the missing probability, then read P(X >= k) off the finished table ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Five tables with six rows (x = 0..5), one probability replaced by "?". Parts:
//   (a) numfunc - the missing probability = 1 - (sum of the other five)
//   (b) numfunc - P(X >= k), a sum of the rows x = k..5 of the COMPLETED table
//   (c) choices - which characteristic of a PDF finds the missing entry (always: they sum to 1)
// Invariant: on every seed the five known entries plus the answer to (a) sum to exactly 1, every
// entry is in [0,1], and (b) is the precomputed sum of rows k..5.
// No expected value anywhere: that is section 4.2.

$anstypes = array("numfunc", "numfunc", "choices")

$p0s = array("0.08", "0.10", "?",    "0.06", "0.15")
$p1s = array("0.17", "?",    "0.15", "0.14", "0.20")
$p2s = array("0.30", "0.25", "0.30", "0.22", "?")
$p3s = array("?",    "0.20", "0.25", "0.28", "0.20")
$p4s = array("0.15", "0.15", "0.15", "0.20", "0.10")
$p5s = array("0.05", "0.10", "0.10", "?",    "0.05")

$missing = array(0.25, 0.20, 0.05, 0.10, 0.30)
$knownSums = array("0.75", "0.80", "0.95", "0.90", "0.70")
$ks = array(3, 2, 2, 3, 4)
$tails = array(0.45, 0.70, 0.80, 0.58, 0.15)
$tailSums = array("0.25 + 0.15 + 0.05", "0.25 + 0.20 + 0.15 + 0.10", "0.30 + 0.25 + 0.15 + 0.10", "0.28 + 0.20 + 0.10", "0.10 + 0.05")

$contexts = array(
  "A clinic counts how many times a patient rings the nurse during a shift. Let `X` be the number of calls. One entry of the distribution is missing.",
  "A mechanic records how many cars arrive for an oil change in an hour. Let `X` be the number of arrivals. One entry of the distribution is missing.",
  "A bakery counts the batches of muffins it sells in a day. Let `X` be the number of batches sold. One entry of the distribution is missing.",
  "A library tracks the number of books a visitor checks out. Let `X` be the number of books. One entry of the distribution is missing.",
  "A coffee shop counts the specialty drinks sold per hour. Let `X` be the number of drinks. One entry of the distribution is missing."
)

$picked = jointrandfrom($p0s, $p1s, $p2s, $p3s, $p4s, $p5s, $missing, $knownSums, $ks, $tails, $tailSums, $contexts)
$pd0 = $picked[0]
$pd1 = $picked[1]
$pd2 = $picked[2]
$pd3 = $picked[3]
$pd4 = $picked[4]
$pd5 = $picked[5]
$answer[0] = $picked[6]
$knownSum = $picked[7]
$k = $picked[8]
$answer[1] = $picked[9]
$tailSum = $picked[10]
$ctx = $picked[11]
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005

$questions[2] = array(
  "The probabilities must sum to 1",
  "Every probability must be between 0 and 1",
  "Each probability must be larger than the one before it"
)
$answer[2] = 0

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
      <p><span class="term-label">Part (a): the missing entry.</span> A distribution is a budget: the probabilities must add to exactly 1. The five known entries add to ' . $knownSum . ', so the missing one is <b>1 &minus; ' . $knownSum . ' = ' . $answer[0] . '</b>.</p>
      <p><span class="term-label">Part (b): P(X &ge; ' . $k . ').</span> Cover the table, uncover only the rows where `x` is ' . $k . ' or more, and add them: ' . $tailSum . ' = <b>' . $answer[1] . '</b>. Use the completed table, so if the missing entry is in those rows it counts.</p>
      <p><span class="term-label">Part (c): which characteristic.</span> Only the sum-to-1 rule can tell you the size of a missing entry. The between-0-and-1 rule just tells you whether an entry is allowed.</p>
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
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">5</td>
      </tr>
      <tr>
        <th style="border:1px solid #21242c; padding:6px 14px; background:#f0f4ff;">`P(X = x)`</th>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$pd0</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$pd1</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$pd2</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$pd3</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$pd4</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$pd5</td>
      </tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the missing probability (the cell marked ?)
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Using the completed table, find `P(X >= $k)`.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which characteristic of a probability distribution lets you find the missing entry?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
