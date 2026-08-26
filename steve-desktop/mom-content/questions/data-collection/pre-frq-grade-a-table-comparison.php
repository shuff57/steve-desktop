// === NAME - DESCRIPTION: Pre-FRQ Grade a Table Comparison - the scenario and grading checklist of the table-comparison FRQ, where the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The pre-FRQ for the 1.5 lab. No lab FRQ exists to mirror, so the scenario and checklist are
// ORIGINAL and define the shape a later lab FRQ should match. See reference/pre-frq-template.md.
//
// Categories: Defensible Position (3 pts) / Evidence from Your Own Tables (4 pts) /
// Addresses Grouping Tradeoffs (3 pts) = 10.
//
// The dropped category is ADDRESSES GROUPING TRADEOFFS. A student can state a defensible position
// and back it with evidence without ever grappling with the fact that grouping can hide a cut --
// which is the lab's real lesson. Part (c) exists to name that habit.
//
// Not reused from an earlier assignment: 2.3 Percentile, 2.4 Contextual Interpretation, 2.5
// Outlier Impact, 2.6 Further Investigation, 2.7 Practical Conclusion, 3.1 Sample Space, 3.2
// Distinguish the Two, 3.3 Second Factor, 3.4 State the Direction, 3.5 Draw the Structure, 4.1
// State the Values, 4.2 Verify the Sum, 4.3 Name the Parameters, 4.4 State the Theoretical Value
// -- none is this.
//
// CATEGORY PURITY: $sPosition states a position and nothing else; $sEvidence cites the student's
// own tables and nothing else; $sTradeoff names the grouping tradeoff and nothing else. Each
// sentence earns exactly one rubric line and no other.
$anstypes = array("choices", "multans", "choices")

$sPos = "No, one table is not more correct than the other. They are two summaries of the same data, each honest about what it shows."
$sEvidence = "In my tables the at-most-two and at-most-three answers both came from the ungrouped cumulative column, and the more-than-three answer came out the same from both tables."
$sTradeoff = "The tradeoff is that grouping hides any cut that falls inside a row, so a question like more than two needs the ungrouped table even though the data never change."

$rFull        = $sPos . ' ' . $sEvidence . ' ' . $sTradeoff
$rNoTrade     = $sPos . ' ' . $sEvidence
$rNoEvidence  = $sPos . ' ' . $sTradeoff
$rMinimal     = $sPos . ' Both tables were built from the same 60 counts.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoTrade
$rC = $rNoEvidence
$rD = $rMinimal
if ($pos == 1) {
  $rA = $rNoTrade
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoEvidence
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rMinimal
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noTradeLabel = "B"
if ($pos == 1) { $noTradeLabel = "A" }

$questions[1] = array(
  "Defensible Position (3 pts)",
  "Evidence from Your Own Tables (4 pts)",
  "Addresses Grouping Tradeoffs (3 pts)"
)
$answer[1] = "0,1"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. A defensible position with evidence from the tables does not imply the student noticed what grouping hides, so the tradeoffs have to be judged separately.",
  "Yes. Once the position and the evidence are there, the tradeoffs are already implied, so there is nothing separate to award.",
  "No, but only because the evidence is the hard part.",
  "Yes, as long as the two tables agree, the tradeoffs do not matter."
)
$answer[2] = 0

$css = '
<style>
  .qscope16 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope16 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope16 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope16 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope16 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope16 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope16 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope16 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope16 .row-colored { background:#fff9ea; }
  .qscope16 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope16 .resp b { color:#1865f2; }
</style>'

$rubric = $css . '
<div class="qscope16">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Defensible Position<br>(3 pts)</b></td>
            <td>Take a clear position on whether one table is more correct, and defend it.</td></tr>
          <tr><td style="text-align:center;"><b>Evidence from Your Own Tables<br>(4 pts)</b></td>
            <td>Point to the rows, columns, and values in your own tables that support the position.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Addresses Grouping Tradeoffs<br>(3 pts)</b></td>
            <td>Say what grouping can and cannot change about the answers.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$responses = '
<div class="qscope16">
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
      <p><span class="term-label">Part (a) &mdash; only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> takes a defensible position, cites the tables, and names what grouping can hide. Each of the other three misses a whole category.</p>
      <p><span class="term-label">Part (b) &mdash; grading Response ' . $noTradeLabel . ' line by line.</span></p>
      <ul>
        <li><b>Defensible Position &mdash; earned.</b> The response takes a clear position and defends it.</li>
        <li><b>Evidence from Your Own Tables &mdash; earned.</b> It points to the rows and values the answer came from.</li>
        <li><b>Addresses Grouping Tradeoffs &mdash; NOT earned.</b> Nothing in the response says what grouping can and cannot hide, which is the lab&rsquo;s real lesson.</li>
      </ul>
      <p><span class="term-label">Part (c) &mdash; why the tradeoffs are their own category.</span> A defensible position backed by evidence is exactly the answer that still never grapples with the fact that grouping can hide the question. The tradeoffs have to be judged separately, because that is the one line a careful student skips.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> In the lab, each student built an ungrouped table and a grouped table from the same 60 movie counts, then answered the discussion question: is one of the tables more correct than the other, and why or why not?</p>
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> take a defensible position on whether one table is more correct, support it with evidence from your own tables, and address what the grouping does and does not change.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noTradeLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is a defensible position backed by evidence enough on its own to cover addressing the grouping tradeoffs? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide