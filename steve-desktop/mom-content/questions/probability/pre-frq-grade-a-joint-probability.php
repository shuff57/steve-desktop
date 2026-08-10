// === NAME - DESCRIPTION: Pre-FRQ Grade a Joint Probability - A multiplication-rule scenario with a grading checklist, where the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The pre-FRQ for 3.3. Chapter 3 has no intro-stats FRQ to mirror, so the scenario and checklist
// here are ORIGINAL and define the shape a later 3.3 FRQ should match. See
// reference/pre-frq-template.md.
//
// The dropped category is JUSTIFY THE SECOND FACTOR. Students reach for the multiplication rule and
// multiply the two numbers in front of them without ever saying WHY the conditional rate is the
// right second factor rather than the overall rate. It is the whole content of 3.3 and it is the
// step that looks like restating the arithmetic.
//
// CATEGORY PURITY: the scenario deliberately supplies THREE percentages -- P(A), P(B given A) and
// P(B) overall -- so the arithmetic sentence can quote the right one as a bare number without
// explaining the choice. The justification is the only sentence that says which and why.
$anstypes = array("choices", "multans", "choices")

$i = rand(0, 2)

$settings = array(
  "customers at a restaurant, ordering dessert and ordering coffee",
  "riders on a bus route, buying a day pass and transferring to a second line",
  "visitors to a museum, joining a guided tour and visiting the gift shop"
)
$setting = $settings[$i]

$eventA_labels = array("ordered dessert", "bought a day pass", "joined a guided tour")
$eventA = $eventA_labels[$i]

$eventB_labels = array("ordered coffee", "transferred to a second line", "visited the gift shop")
$eventB = $eventB_labels[$i]

$who_labels = array("customers", "riders", "visitors")
$who = $who_labels[$i]

// Percentages as whole numbers so the arithmetic is exact. The overall rate for B is deliberately
// well away from the conditional rate, so the two events are visibly NOT independent and picking
// the wrong factor is a real mistake rather than a rounding difference.
$a = rand(3, 6) * 10
$b = rand(2, 5) * 10
$bOverall = $b + 15
$jointPct = $a * $b / 100

$aDec = $a / 100
$bDec = $b / 100
$jointDec = $jointPct / 100

// One sentence per rubric category. $sApply quotes the conditional rate as a bare number and never
// says why it is the right one -- that is $sJustify's job alone.
$sRule = 'Finding the chance that one of the ' . $who . ' did both calls for the multiplication rule, because it asks for an AND rather than an OR.'
$sApply = 'That works out as ' . $aDec . ' x ' . $bDec . ' = ' . $jointDec . ', or ' . $jointPct . '% of all ' . $who . '.'
$sJustify = 'The second factor has to be the ' . $b . '% measured among those who ' . $eventA . ', not the ' . $bOverall . '% measured across everyone, because the two events are not independent: knowing a ' . $who . ' ' . $eventA . ' changes the chance they also ' . $eventB . '.'

$rFull = $sRule . ' ' . $sApply . ' ' . $sJustify
$rNoJustify = $sRule . ' ' . $sApply
$rNoApply = $sRule . ' ' . $sJustify
$rRuleOnly = $sRule . ' That is the rule this situation calls for.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoJustify
$rC = $rNoApply
$rD = $rRuleOnly
if ($pos == 1) {
  $rA = $rNoJustify
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoApply
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rRuleOnly
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noJustifyLabel = "B"
if ($pos == 1) { $noJustifyLabel = "A" }

$questions[1] = array(
  "Choose the Right Rule (3 pts)",
  "Apply It Correctly (4 pts)",
  "Justify the Second Factor (3 pts)"
)
$answer[1] = "0,1"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. Two rates were on offer and only one is correct; using the right one without saying why leaves the choice unexplained, and it is the choice the section is about.",
  "Yes. Multiplying the correct two numbers demonstrates the reasoning without needing to state it.",
  "No, but only because the response did not restate the final percentage a second time.",
  "Yes, as long as the multiplication is arithmetically correct."
)
$answer[2] = 0

$css = '
<style>
  .qscope33 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope33 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope33 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope33 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope33 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope33 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope33 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope33 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope33 .row-colored { background:#fff9ea; }
  .qscope33 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope33 .resp b { color:#1865f2; }
  .qscope33 .facts { border-collapse:collapse; margin:10px 0; font-size:15px; }
  .qscope33 .facts td, .qscope33 .facts th { border:1px solid #d1d5db; padding:6px 14px; text-align:left; }
  .qscope33 .facts th { background:#f0f4ff; }
</style>'

$rubric = $css . '
<div class="qscope33">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Choose the Right Rule<br>(3 pts)</b></td>
            <td>Say which rule the question calls for, and what about the question decides it.</td></tr>
          <tr><td style="text-align:center;"><b>Apply It Correctly<br>(4 pts)</b></td>
            <td>Carry out the calculation and give the resulting probability.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Justify the Second Factor<br>(3 pts)</b></td>
            <td>Say which of the two rates for the second event belongs in the product, and why the other one does not.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$factBlock = '
<div class="qscope33">
  <table class="facts">
    <tr><th>What was measured</th><th>Rate</th></tr>
    <tr><td>' . $who . ' who ' . $eventA . '</td><td>' . $a . '%</td></tr>
    <tr><td>among those who ' . $eventA . ', the share who also ' . $eventB . '</td><td>' . $b . '%</td></tr>
    <tr><td>' . $who . ' who ' . $eventB . ', across everyone</td><td>' . $bOverall . '%</td></tr>
  </table>
</div>'

$responses = '
<div class="qscope33">
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
      <p><span class="term-label">Part (a) &mdash; only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> names the rule, does the arithmetic, and says which of the two rates belongs in the product and why. Each of the other three drops a whole category.</p>
      <p><span class="term-label">The numbers.</span> `' . $aDec . ' xx ' . $bDec . ' = ' . $jointDec . '`, so ' . $jointPct . '% of all ' . $who . ' did both. The ' . $bOverall . '% is there to be rejected, not used.</p>
      <p><span class="term-label">Part (b) &mdash; grading Response ' . $noJustifyLabel . ' line by line.</span></p>
      <ul>
        <li><b>Choose the Right Rule &mdash; earned.</b> It identifies this as an AND and reaches for the multiplication rule.</li>
        <li><b>Apply It Correctly &mdash; earned.</b> The product and the final percentage are right.</li>
        <li><b>Justify the Second Factor &mdash; NOT earned.</b> It uses the ' . $b . '% and never says why that one rather than the ' . $bOverall . '%. This is the only category it misses.</li>
      </ul>
      <p><span class="term-label">Part (c) &mdash; why the justification is its own category.</span> The scenario hands over two different rates for the same event, and only one of them belongs in the product. Picking the right one silently is indistinguishable from picking it by luck &mdash; and a student who cannot say why would take the ' . $bOverall . '% the moment the question is worded the other way round. That choice, not the multiplication, is what 3.3 teaches.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the lab and on the test this scenario comes with a blank box and this same checklist. The justification is the category most often missing, because once the two numbers are multiplied the work looks finished.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> A survey of $setting produced three figures.</p>
    $factBlock
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> Find the probability that one of the $who both $eventA and $eventB, and explain which figures you used.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noJustifyLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is using the right rate enough on its own to cover the justification? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
