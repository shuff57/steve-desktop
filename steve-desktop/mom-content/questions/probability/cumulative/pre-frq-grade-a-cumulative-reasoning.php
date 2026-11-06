// === NAME - DESCRIPTION: Pre-FRQ Grade a Cumulative Reasoning - The scenario and grading checklist of the cumulative-table FRQ, but the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The pre-FRQ for 4.5 (cumulative). No FRQ in questions/frq/ covers a cumulative distribution, so
// this one is authored and defines the scenario and rubric a later FRQ should match.
// See reference/pre-frq-template.md.
//
// Categories: Build the Cumulative Table (3 pts) / Read the Requested Probability (4 pts) /
// Separate the Row from the Total (5 pts) = 12, matching the slot.
//
// The dropped category is SEPARATE THE ROW FROM THE TOTAL. A response can build the table and read
// the requested probability correctly while still treating a single row as the running total, so
// nothing else catches the confusion. Not reused from an earlier assignment: 2.3 Percentile, 2.4
// Contextual Interpretation, 2.5 Outlier Impact, 2.6 Further Investigation, 2.7 Practical
// Conclusion, 3.1 Sample Space, 3.2 Distinguish the Two, 3.3 Second Factor, 3.4 State the Direction,
// 3.5 Draw the Structure, 4.1 State the Values, 4.2 Verify the Sum, 4.5-continuous State the Two
// Properties: none is this.
//
// CATEGORY PURITY: $sTable lists the cumulative column and nothing else; $sRead converts the wording
// and shows the subtraction that gives the requested probability; $sRow gives one row value and the
// total at the same value and never restates the requested probability. Each sentence earns exactly
// one rubric line and no other.
$anstypes = array("choices", "multans", "choices")

$scenarios = array(
  "A cafe sells 0 to 3 sandwiches at lunch. `X` is the number of sandwiches sold. The chances are 0 at 0.10, 1 at 0.30, 2 at 0.40 and 3 at 0.20. The task asks for the chance that the cafe sells 1 or 2 sandwiches.",
  "A bike shop repairs 0 to 4 bikes in a day. `X` is the number repaired. The chances are 0 at 0.05, 1 at 0.20, 2 at 0.35, 3 at 0.25 and 4 at 0.15. The task asks for the chance that the shop repairs at least 3 bikes.",
  "A student answers 0 to 3 questions on a short quiz. `X` is the number correct. The chances are 0 at 0.15, 1 at 0.35, 2 at 0.30 and 3 at 0.20. The task asks for the chance that the student answers fewer than 2 correctly.",
  "A plant finds 0 to 4 defective parts in a batch. `X` is the number defective. The chances are 0 at 0.12, 1 at 0.28, 2 at 0.32, 3 at 0.18 and 4 at 0.10. The task asks for the chance that the batch has 1, 2 or 3 defective parts."
)

$i = rand(0, 3)
$scenario = $scenarios[$i]

$sTables = array(
  "The cumulative table is 0.10, 0.40, 0.80, 1.00.",
  "The cumulative table is 0.05, 0.25, 0.60, 0.85, 1.00.",
  "The cumulative table is 0.15, 0.50, 0.80, 1.00.",
  "The cumulative table is 0.12, 0.40, 0.72, 0.90, 1.00."
)

$sReads = array(
  "The chance of 1 or 2 sandwiches is `F(2) - F(0) = 0.80 - 0.10 = 0.70`.",
  "The chance of at least 3 bikes is `1 - F(2) = 1 - 0.60 = 0.40`.",
  "The chance of fewer than 2 correct answers is `F(1) = 0.50`.",
  "The chance of 1, 2 or 3 defective parts is `F(3) - F(0) = 0.90 - 0.12 = 0.78`."
)

$sRows = array(
  "The row for 2 is 0.40, while the total at 2 is 0.80, because the total includes 0, 1 and 2.",
  "The row for 2 is 0.35, while the total at 2 is 0.60, because the total includes 0, 1 and 2.",
  "The row for 2 is 0.30, while the total at 2 is 0.80, because the total includes 0, 1 and 2.",
  "The row for 2 is 0.32, while the total at 2 is 0.72, because the total includes 0, 1 and 2."
)

$sTable = $sTables[$i]
$sRead = $sReads[$i]
$sRow = $sRows[$i]

$rFull = $sTable . ' ' . $sRead . ' ' . $sRow
$rDropped = $sTable . ' ' . $sRead
$rNoRead = $sTable . ' ' . $sRow
$rMinimal = $sTable . ' The table is complete.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rDropped
$rC = $rNoRead
$rD = $rMinimal
if ($pos == 1) {
  $rA = $rDropped
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoRead
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rMinimal
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$droppedLabel = "B"
if ($pos == 1) { $droppedLabel = "A" }

$questions[1] = array(
  "Build the Cumulative Table (3 pts)",
  "Read the Requested Probability (4 pts)",
  "Separate the Row from the Total (5 pts)"
)
$answer[1] = "2"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. The table can be built and the requested probability can be read correctly while the response still treats a single row as the running total, so the distinction has to be judged on its own.",
  "Yes. Once the cumulative table is correct, every row and every total is implied by it.",
  "Yes. Reading the requested probability always includes the row-versus-total distinction.",
  "No, but only because building the table is the hard part."
)
$answer[2] = 0
$noshuffle[2] = "all"

$css = '
<style>
  .qscope45cum .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope45cum .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope45cum .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope45cum .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope45cum .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope45cum .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope45cum .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope45cum .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope45cum .row-colored { background:#fff9ea; }
  .qscope45cum .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope45cum .resp b { color:#1865f2; }
</style>'

$rubric = $css . '
<div class="qscope45cum">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Build the Cumulative Table<br>(3 pts)</b></td>
            <td>Add the rows in order and give the cumulative chance at every value.</td></tr>
          <tr><td style="text-align:center;"><b>Read the Requested Probability<br>(4 pts)</b></td>
            <td>Convert the wording to an inequality and read it from the cumulative table, showing the subtraction.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Separate the Row from the Total<br>(5 pts)</b></td>
            <td>Say that `P(X = x)` is one row, while `F(x)` is the running total through `x`.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$responses = '
<div class="qscope45cum">
  <div class="resp"><b>Response A.</b> ' . $rA . '</div>
  <div class="resp"><b>Response B.</b> ' . $rB . '</div>
  <div class="resp"><b>Response C.</b> ' . $rC . '</div>
  <div class="resp"><b>Response D.</b> ' . $rD . '</div>
</div>'

$fullLabel = "A"
if ($pos == 1) { $fullLabel = "B" }
if ($pos == 2) { $fullLabel = "C" }
if ($pos == 3) { $fullLabel = "D" }

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
      <p><span class="term-label">Part (a): only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> builds the cumulative table, reads the requested probability with the subtraction shown, and separates one row from the running total. Each of the other three misses a whole category.</p>
      <p><span class="term-label">The setup, and the answer.</span> ' . $sTable . ' ' . $sRead . ' ' . $sRow . '</p>
      <p><span class="term-label">Part (b): grading Response ' . $droppedLabel . ' line by line.</span></p>
      <ul>
        <li><b>Build the Cumulative Table: earned.</b> The response gives the full cumulative column.</li>
        <li><b>Read the Requested Probability: earned.</b> The response converts the wording and shows the subtraction that gives the requested chance.</li>
        <li><b>Separate the Row from the Total: NOT earned.</b> The response never says that one row `P(X = x)` is different from the running total `F(x)`, so the confusion that reverses the two goes unchecked.</li>
      </ul>
      <p><span class="term-label">Part (c): why the distinction is its own category.</span> A table can be complete and the requested chance can be correct while the response still reads a row as a total. The two numbers come from the same column, so only a sentence that names the difference shows the response can keep them apart.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the test this scenario comes with a blank box and this same checklist. The row-versus-total distinction is the category most often skipped, because once the subtraction is done the answer looks complete.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> $scenario</p>
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> Build the cumulative table, read the requested probability from it, and say how a single row differs from the running total.</p>
  </div>
  $rubric
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 4px 0;"><b>Four students answered.</b></p>
    $responses
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which response earns <b>full credit</b> on all three categories? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $droppedLabel</b> <b>fail</b> to earn? Select every one it fails to earn, and none that it does. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is reading the requested probability correctly enough on its own to cover separating the row from the total? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
