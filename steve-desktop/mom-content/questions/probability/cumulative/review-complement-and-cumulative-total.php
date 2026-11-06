// === NAME - DESCRIPTION: Review of Complements and the Cumulative Column - Find a complement, test whether a table is a probability distribution, and state what the cumulative column must do ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Four three-row chance tables. Parts: (a) numfunc - the complement of a given chance;
// (b) choices - is the table a probability distribution? (c) choices - what the cumulative column does.
// Invariant: the printed rows sum to the stated sum; the validity index matches that sum; the
// cumulative column never decreases and its last entry is 1.

$anstypes = array("numfunc", "choices", "choices")

$ps = array(0.35, 0.62, 0.48, 0.71)
$comps = array(0.65, 0.38, 0.52, 0.29)
$p0s = array("0.2", "0.1", "0.25", "0.4")
$p1s = array("0.5", "0.3", "0.25", "0.4")
$p2s = array("0.4", "0.6", "0.5", "0.3")
$sums = array("1.1", "1.0", "1.0", "1.1")
$valid = array(0, 1, 1, 0)

$contexts = array(
  "A raffle ticket can win 0, 1 or 2 prizes. The table gives the chance of each number of prizes.",
  "A student can get 0, 1 or 2 questions right on a short quiz. The table gives the chance of each number right.",
  "A vending machine can jam 0, 1 or 2 times in a day. The table gives the chance of each number of jams.",
  "A bus can be 0, 1 or 2 minutes late at a stop. The table gives the chance of each delay."
)

$picked = jointrandfrom($ps, $comps, $p0s, $p1s, $p2s, $sums, $valid, $contexts)
$p = $picked[0]
$answer[0] = $picked[1]
$pd0 = $picked[2]
$pd1 = $picked[3]
$pd2 = $picked[4]
$sum = $picked[5]
$answer[1] = $picked[6]
$ctx = $picked[7]
$abstolerance[0] = 0.005

$questions[1] = array(
  "No. The chances do not add to 1.",
  "Yes. Every chance is between 0 and 1 and the chances add to 1.",
  "No. A chance cannot be greater than 0.5.",
  "Yes. The largest chance is in the last row."
)
$noshuffle[1] = "all"

$questions[2] = array(
  "It never decreases and its last entry is 1.",
  "It never increases and its last entry is 0.",
  "Its last entry is the largest row of the table.",
  "It can rise above 1 as long as the rows do."
)
$answer[2] = 0
$noshuffle[2] = "all"

$validText = "The three chances add to " . $sum . ", so the table is not a probability distribution."
if ($answer[1] == 1) { $validText = "The three chances add to " . $sum . ", so the table is a probability distribution." }

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
      <p><span class="term-label">Part (a): the complement.</span> A chance and its complement fill the whole scale, so they add to 1. The complement of `' . $p . '` is `1 - ' . $p . ' = ` <b>' . $answer[0] . '</b>.</p>
      <p><span class="term-label">Part (b): is it a distribution?</span> Add the three rows in order: ' . $pd0 . ' + ' . $pd1 . ' + ' . $pd2 . ' = ' . $sum . '. ' . $validText . ' A distribution also needs every chance to be between 0 and 1, and these are.</p>
      <p><span class="term-label">Part (c): the cumulative column.</span> Each entry is the sum of the rows up to that point, so the column can only stay the same or grow. It never decreases, and the last entry is the sum of every row, which is 1.</p>
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
      </tr>
      <tr>
        <th style="border:1px solid #21242c; padding:6px 14px; background:#f0f4ff;">`P(X = x)`</th>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$pd0</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$pd1</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$pd2</td>
      </tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> A chance is `$p`. Find its complement (Definition 3.1.10). Write the answer as a decimal.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Is this table a probability distribution (Definitions 4.1.2 and 4.1.3)?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What must the cumulative column of a probability table do?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
