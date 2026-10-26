// === NAME - DESCRIPTION: Turn Counts into a PDF - Convert a table of counts to probabilities, read P(X = k) and P(X <= k), name the most likely value ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Five samples of N people counted by value x = 0..5 (counts always add to N). A PDF entry is
// count / N, so the PDF column is the relative frequencies. Parts:
//   (a) numfunc - P(X = ka) = count_ka / N
//   (b) numfunc - P(X <= kb) = (counts of rows 0..kb) / N
//   (c) choices - the most likely value of x (largest count), options x = 0..5, noshuffle
// Invariant: counts sum to N; every answer is a precomputed exact decimal; the mode is unique.
// No expected value anywhere: that is section 4.2.

$anstypes = array("numfunc", "numfunc", "choices")

$c0s = array(2, 5, 1, 8, 3)
$c1s = array(6, 10, 3, 16, 12)
$c2s = array(12, 8, 9, 24, 9)
$c3s = array(10, 15, 6, 20, 18)
$c4s = array(7, 8, 4, 8, 15)
$c5s = array(3, 4, 2, 4, 3)
$ns  = array(40, 50, 25, 80, 60)

$kas = array(3, 1, 4, 5, 0)
$aAnswers = array(0.25, 0.20, 0.16, 0.05, 0.05)
$aCounts = array(10, 10, 4, 4, 3)

$kbs = array(2, 2, 3, 1, 2)
$bAnswers = array(0.50, 0.46, 0.76, 0.30, 0.40)
$bSums = array("2 + 6 + 12 = 20", "5 + 10 + 8 = 23", "1 + 3 + 9 + 6 = 19", "8 + 16 = 24", "3 + 12 + 9 = 24")
$bCounts = array(20, 23, 19, 24, 24)

$modes = array(2, 3, 2, 2, 3)

$contexts = array(
  "A clinic recorded how many times each of 40 patients rang the nurse during a shift. Let `X` be the number of calls.",
  "A survey asked 50 households how many pets they own. Let `X` be the number of pets.",
  "A teacher recorded how many assignments each of 25 students turned in late this month. Let `X` be the number of late assignments.",
  "A shop counted how many items each of 80 customers returned in a visit. Let `X` be the number of items returned.",
  "A coach recorded how many practices each of 60 players missed this season. Let `X` be the number of practices missed."
)

$picked = jointrandfrom($c0s, $c1s, $c2s, $c3s, $c4s, $c5s, $ns, $kas, $aAnswers, $aCounts, $kbs, $bAnswers, $bSums, $bCounts, $modes, $contexts)
$n0 = $picked[0]
$n1 = $picked[1]
$n2 = $picked[2]
$n3 = $picked[3]
$n4 = $picked[4]
$n5 = $picked[5]
$N = $picked[6]
$ka = $picked[7]
$answer[0] = $picked[8]
$aCount = $picked[9]
$kb = $picked[10]
$answer[1] = $picked[11]
$bSum = $picked[12]
$bCount = $picked[13]
$answer[2] = $picked[14]
$ctx = $picked[15]
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005

$questions[2] = array("`x = 0`", "`x = 1`", "`x = 2`", "`x = 3`", "`x = 4`", "`x = 5`")
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
      <p><span class="term-label">The conversion.</span> Every one of the ' . $N . ' people is counted in exactly one row, so `P(X = x)` is that row&rsquo;s count divided by ' . $N . '. The counts add to ' . $N . ', so the probabilities add to 1.</p>
      <p><span class="term-label">Part (a): P(X = ' . $ka . ').</span> ' . $aCount . ' out of ' . $N . ' is <b>' . $answer[0] . '</b>.</p>
      <p><span class="term-label">Part (b): P(X &le; ' . $kb . ').</span> Add the counts for every row from 0 up to and including ' . $kb . ': ' . $bSum . '. Then ' . $bCount . ' out of ' . $N . ' is <b>' . $answer[1] . '</b>.</p>
      <p><span class="term-label">Part (c): the most likely value.</span> The row with the largest count has the largest probability: <b>`x = ' . $answer[2] . '`</b>.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">$ctx The counts are below. One person from the sample is chosen at random.</p>
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
        <th style="border:1px solid #21242c; padding:6px 14px; background:#f0f4ff;">Count</th>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$n0</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$n1</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$n2</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$n3</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$n4</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$n5</td>
      </tr>
    </table>
    <p style="margin:10px 0 0 0;">The counts add to $N, so `P(X = x)` is each count divided by $N.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find `P(X = $ka)`. Give a decimal.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find `P(X <= $kb)`. Give a decimal.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which value of `x` has the largest probability?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
