// === NAME - DESCRIPTION: Which Rows to Add - Range and at-least-one probabilities off a full distribution table, and which rows an inequality selects ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Five legitimate tables (x = 0..5, entries sum to 1.00). Parts:
//   (a) numfunc - P(lo <= X <= hi), the rows lo..hi added
//   (b) numfunc - P(X >= 1), the "at least one" probability = every row but x = 0
//   (c) choices - which rows P(X > kc) selects (always "x = kc+1 through 5"); options use the
//                 same three phrasings on every seed so the strict-vs-inclusive trap is the point
// Invariant: each table sums to exactly 1; (a) and (b) are precomputed sums of the named rows.
// No expected value anywhere: that is section 4.2.

$anstypes = array("numfunc", "numfunc", "choices")

$p0s = array("0.10", "0.05", "0.12", "0.20", "0.08")
$p1s = array("0.20", "0.15", "0.18", "0.30", "0.12")
$p2s = array("0.25", "0.30", "0.20", "0.20", "0.25")
$p3s = array("0.20", "0.25", "0.25", "0.15", "0.30")
$p4s = array("0.15", "0.15", "0.15", "0.10", "0.15")
$p5s = array("0.10", "0.10", "0.10", "0.05", "0.10")

$los = array(1, 2, 1, 2, 0)
$his = array(3, 4, 4, 5, 2)
$aAnswers = array(0.65, 0.70, 0.78, 0.50, 0.45)
$aSums = array("0.20 + 0.25 + 0.20", "0.30 + 0.25 + 0.15", "0.18 + 0.20 + 0.25 + 0.15", "0.20 + 0.15 + 0.10 + 0.05", "0.08 + 0.12 + 0.25")
$bAnswers = array(0.90, 0.95, 0.88, 0.80, 0.92)
$bZero = array("0.10", "0.05", "0.12", "0.20", "0.08")
$kcs = array(2, 3, 1, 3, 2)
$kcNexts = array(3, 4, 2, 4, 3)

$contexts = array(
  "A store tracks the number of items a customer returns in a visit. Let `X` be the number of returns.",
  "A mechanic records the number of cars arriving in an hour. Let `X` be the number of arrivals.",
  "A bakery counts the batches of muffins sold in a day. Let `X` be the number of batches sold.",
  "A clinic counts the times a patient rings the nurse during a shift. Let `X` be the number of calls.",
  "A coffee shop tracks the specialty drinks sold per hour. Let `X` be the number of drinks."
)

$picked = jointrandfrom($p0s, $p1s, $p2s, $p3s, $p4s, $p5s, $los, $his, $aAnswers, $aSums, $bAnswers, $bZero, $kcs, $kcNexts, $contexts)
$pd0 = $picked[0]
$pd1 = $picked[1]
$pd2 = $picked[2]
$pd3 = $picked[3]
$pd4 = $picked[4]
$pd5 = $picked[5]
$lo = $picked[6]
$hi = $picked[7]
$answer[0] = $picked[8]
$aSum = $picked[9]
$answer[1] = $picked[10]
$zero = $picked[11]
$kc = $picked[12]
$kcNext = $picked[13]
$ctx = $picked[14]
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005

$questions[2] = array(
  "`x = $kcNext` through `x = 5`",
  "`x = $kc` through `x = 5`",
  "`x = 0` through `x = $kc`"
)
$answer[2] = 0
$noshuffle[2] = "all"

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
      <p><span class="term-label">The method.</span> Every probability question here is the same move: pick the rows the inequality selects, then add them.</p>
      <p><span class="term-label">Part (a): P(' . $lo . ' &le; X &le; ' . $hi . ').</span> Both ends are included, so the rows are x = ' . $lo . ' through x = ' . $hi . ': ' . $aSum . ' = <b>' . $answer[0] . '</b>.</p>
      <p><span class="term-label">Part (b): P(X &ge; 1).</span> &ldquo;At least one&rdquo; is every row except x = 0. Add them, or use the budget: the whole table is 1, and the one row left out is ' . $zero . ', so 1 &minus; ' . $zero . ' = <b>' . $answer[1] . '</b>.</p>
      <p><span class="term-label">Part (c): P(X &gt; ' . $kc . ').</span> Strictly greater leaves out x = ' . $kc . ' itself, so the rows start at ' . $kcNext . ': <b>x = ' . $kcNext . ' through x = 5</b>. Starting at ' . $kc . ' would be P(X &ge; ' . $kc . ').</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">$ctx The distribution is below.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find `P($lo <= X <= $hi)`.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find the probability of <b>at least one</b>, `P(X >= 1)`.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which rows of the table does `P(X > $kc)` select?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
