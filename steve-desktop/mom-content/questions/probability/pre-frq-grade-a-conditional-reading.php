// === NAME - DESCRIPTION: Pre-FRQ Grade a Conditional Reading - A two-way table scenario with a grading checklist, where the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The pre-FRQ for 3.4. Chapter 3 has no intro-stats FRQ to mirror, so the scenario and checklist are
// ORIGINAL and define the shape a later 3.4 FRQ should match. See reference/pre-frq-template.md.
//
// The dropped category is STATE THE DIRECTION. Students divide the right two numbers and never say
// WHICH conditional they produced, so a correct calculation gets attached to the wrong sentence.
// Related to but not the same as 3.2's dropped category: 3.2 is about two different TESTS (mutually
// exclusive versus independent), this is about the two directions of ONE conditional. Different
// skill, different failure.
//
// CATEGORY PURITY: $sCounts reports the two numbers WITHOUT naming which group they came from, and
// $sDirection names the direction WITHOUT restating the numbers. If the counts sentence said "out of
// the N who commute", it would hand the direction category back.
$anstypes = array("choices", "multans", "choices")

$i = rand(0, 2)

$settings = array(
  "students at a college, by whether they commute and whether they hold a job",
  "employees at a firm, by whether they work remotely and whether they use the shuttle",
  "members of a gym, by whether they attend mornings and whether they take classes"
)
$setting = $settings[$i]

$rowLabels = array("Commutes", "Works remotely", "Attends mornings")
$rowLabel = $rowLabels[$i]

$rowOther = array("Lives on campus", "Works on site", "Attends evenings")
$rowOtherLabel = $rowOther[$i]

$colLabels = array("Has a job", "Uses shuttle", "Takes classes")
$colLabel = $colLabels[$i]

$aShort = array("Commutes", "Remote", "Mornings")
$aS = $aShort[$i]

$bShort = array("Job", "Shuttle", "Classes")
$bS = $bShort[$i]

$whoLabels = array("students", "employees", "members")
$who = $whoLabels[$i]

// Each row totals 100 by construction, so the conditional is an exact decimal and the row total is
// never a rounding artifact. Disjoint from the column totals, which are deliberately different.
$r1c1 = 10 * rand(4, 7)
$r1c2 = 100 - $r1c1
$r2c1 = 10 * rand(2, 4)
$r2c2 = 100 - $r2c1
$rowTotal = 100
$grand = 200
$c1 = $r1c1 + $r2c1
$c2 = $r1c2 + $r2c2
$pDec = $r1c1 / 100

// One sentence per rubric category. None restates another.
$sCounts = 'The two numbers the calculation needs are ' . $r1c1 . ' and ' . $rowTotal . '.'
$sCompute = 'Dividing gives ' . $r1c1 . ' / ' . $rowTotal . ' = ' . $pDec . '.'
$sDirection = 'That result is P(' . $bS . ' given ' . $aS . '), not P(' . $aS . ' given ' . $bS . '): swapping the two puts a different total underneath and produces a different number.'

$rFull = $sCounts . ' ' . $sCompute . ' ' . $sDirection
$rNoDirection = $sCounts . ' ' . $sCompute
$rNoCompute = $sCounts . ' ' . $sDirection
$rCountsOnly = $sCounts . ' Those are the entries the table supplies.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoDirection
$rC = $rNoCompute
$rD = $rCountsOnly
if ($pos == 1) {
  $rA = $rNoDirection
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoCompute
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rCountsOnly
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noDirectionLabel = "B"
if ($pos == 1) { $noDirectionLabel = "A" }

$questions[1] = array(
  "Read the Right Counts (3 pts)",
  "Compute the Conditional (4 pts)",
  "State the Direction (3 pts)"
)
$answer[1] = "0,1"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. A conditional probability is only meaningful once you say which way round it runs; the same two events give two different numbers, and a correct division attached to the wrong sentence is still wrong.",
  "Yes. Once the correct division is carried out, the direction is obvious from the numbers used.",
  "No, but only because the response did not restate the row total a second time.",
  "Yes, as long as the two counts were read off the table correctly."
)
$answer[2] = 0

$css = '
<style>
  .qscope34 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope34 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope34 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope34 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope34 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope34 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope34 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope34 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope34 .row-colored { background:#fff9ea; }
  .qscope34 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope34 .resp b { color:#1865f2; }
  .qscope34 .twoway { border-collapse:collapse; margin:10px 0; font-size:15px; }
  .qscope34 .twoway td, .qscope34 .twoway th { border:1px solid #d1d5db; padding:6px 16px; text-align:center; }
  .qscope34 .twoway th { background:#f0f4ff; }
</style>'

$rubric = $css . '
<div class="qscope34">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Read the Right Counts<br>(3 pts)</b></td>
            <td>Take the correct two numbers off the table for this calculation.</td></tr>
          <tr><td style="text-align:center;"><b>Compute the Conditional<br>(4 pts)</b></td>
            <td>Carry out the division and give the probability.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>State the Direction<br>(3 pts)</b></td>
            <td>Say which of the two conditionals this is, so the number is attached to the right sentence.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$tableBlock = '
<div class="qscope34">
  <table class="twoway">
    <tr><th></th><th>' . $colLabel . '</th><th>No</th><th>Total</th></tr>
    <tr><th>' . $rowLabel . '</th><td>' . $r1c1 . '</td><td>' . $r1c2 . '</td><td>' . $rowTotal . '</td></tr>
    <tr><th>' . $rowOtherLabel . '</th><td>' . $r2c1 . '</td><td>' . $r2c2 . '</td><td>' . $rowTotal . '</td></tr>
    <tr><th>Total</th><td>' . $c1 . '</td><td>' . $c2 . '</td><td>' . $grand . '</td></tr>
  </table>
</div>'

$responses = '
<div class="qscope34">
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
      <p><span class="term-label">Part (a): only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> takes the right two numbers, divides them, and says which conditional the answer is. Each of the other three drops a whole category.</p>
      <p><span class="term-label">The arithmetic, and the trap next to it.</span> `' . $r1c1 . ' -: ' . $rowTotal . ' = ' . $pDec . '`. The tempting wrong denominator is the column total ' . $c1 . ', or the grand total ' . $grand . ': both give a number, neither answers the question asked.</p>
      <p><span class="term-label">Part (b): grading Response ' . $noDirectionLabel . ' line by line.</span></p>
      <ul>
        <li><b>Read the Right Counts: earned.</b> It uses ' . $r1c1 . ' and ' . $rowTotal . '.</li>
        <li><b>Compute the Conditional: earned.</b> The division and the value are right.</li>
        <li><b>State the Direction: NOT earned.</b> It never says whether this is P(' . $bS . ' given ' . $aS . ') or the reverse. This is the only category it misses.</li>
      </ul>
      <p><span class="term-label">Part (c): why the direction is its own category.</span> The same table answers both questions and they are different numbers. A correct division with no direction attached is a number in search of a sentence: and the sentence a reader supplies is usually the reverse of the one intended, because the reverse is the more interesting claim.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the lab and on the test this scenario comes with a blank box and this same checklist. The direction is the category most often missing, because once the division is done the number feels like the answer.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> A survey sorted $setting.</p>
    $tableBlock
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> Among the $who in the $rowLabel row, find the probability of $colLabel, and say clearly which conditional you have computed.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noDirectionLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is computing the right division enough on its own to cover stating the direction? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
