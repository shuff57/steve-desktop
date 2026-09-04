// === NAME - DESCRIPTION: Pre-FRQ Grade a Diagram Setup - A two-stage tree-or-Venn scenario with a grading checklist, where the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The pre-FRQ for 3.5. No intro-stats FRQ to mirror, so the scenario and checklist are ORIGINAL and
// define the shape a later 3.5 FRQ should match. See reference/pre-frq-template.md.
//
// The dropped category is DRAW THE STRUCTURE. Students produce a plausible-looking product without
// ever committing to a tree or a Venn, so nothing catches a mis-stated second-stage probability.
// Not reused from an earlier assignment: 3.1 dropped Define the Sample Space, 3.3 dropped Justify
// the Second Factor: neither is this.
//
// CATEGORY PURITY: $sFill gives the two stage probabilities WITHOUT naming a tree or Venn (no word
// "branch", no "diagram"), so a response that drops the structure earns Fill but cannot earn
// Structure back from the numbers. $sAnswer gives the final probability WITHOUT re-listing the
// branches. Each sentence earns exactly one rubric line and no other.
$anstypes = array("choices", "multans", "choices")

$i = rand(0, 2)

$contexts = array(
  "A bag of marbles",
  "A drawer of socks",
  "A box of pens"
)
$context = $contexts[$i]

$c1Names = array("red", "black", "blue")
$c2Names = array("blue", "white", "green")
$c1 = $c1Names[$i]
$c2 = $c2Names[$i]

$itemNames = array("marbles", "socks", "pens")
$item = $itemNames[$i]

$r = rand(3, 5)
$total = 10
$b = $total - $r
$n1 = $total - 1

$pFirstRed = $r / $total
$pBothC2 = ($b / $total) * (($b - 1) / $n1)
$ans = 1 - $pBothC2

// One sentence per rubric category. None restates another, and none implies a diagram.
$sStructure = 'I drew a tree with the first draw branching into ' . $c1 . ' and ' . $c2 . ', and each of those branching again for the second draw.'
$sFill = 'The probability the first draw is ' . $c1 . ' is ' . $r . '/' . $total . ', and the probability both are ' . $c2 . ' is ' . $b . '/' . $total . ' &times; (' . ($b - 1) . '/' . $n1 . ').'
$sAnswer = 'So P(at least one ' . $c1 . ') is 1 minus that, or ' . $ans . '.'

$rFull = $sStructure . ' ' . $sFill . ' ' . $sAnswer
$rNoStructure = $sFill . ' ' . $sAnswer
$rNoAnswer = $sStructure . ' ' . $sFill
$rStructureOnly = $sStructure . ' Setting up the picture is the whole task.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoStructure
$rC = $rNoAnswer
$rD = $rStructureOnly
if ($pos == 1) {
  $rA = $rNoStructure
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoAnswer
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rStructureOnly
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noStructureLabel = "B"
if ($pos == 1) { $noStructureLabel = "A" }

$questions[1] = array(
  "Draw the Structure (3 pts)",
  "Fill the Branches or Regions (4 pts)",
  "Answer the Question Asked (3 pts)"
)
$answer[1] = "1,2"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. A student can produce the correct numbers without ever committing to a tree or a Venn, and the picture is the only thing that catches a mis-stated second-stage probability, so the structure has to be judged on its own.",
  "Yes. Once the branch probabilities are correct, the structure is implied, so there is nothing separate to award.",
  "No, but only because a diagram with no numbers on it is worthless.",
  "Yes, as long as the final probability is correct, the setup does not matter."
)
$answer[2] = 0

$css = '
<style>
  .qscope35 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope35 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope35 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope35 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope35 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope35 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope35 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope35 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope35 .row-colored { background:#fff9ea; }
  .qscope35 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope35 .resp b { color:#1865f2; }
</style>'

$rubric = $css . '
<div class="qscope35">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Draw the Structure<br>(3 pts)</b></td>
            <td>Commit to a tree or a Venn diagram that shows the two stages of the problem.</td></tr>
          <tr><td style="text-align:center;"><b>Fill the Branches or Regions<br>(4 pts)</b></td>
            <td>Place the correct probabilities on the branches, or the counts in the regions.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Answer the Question Asked<br>(3 pts)</b></td>
            <td>Use the diagram to give the probability the question asks for.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$responses = '
<div class="qscope35">
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
      <p><span class="term-label">Part (a): only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> draws the tree, puts the stage probabilities on it, and gives P(at least one ' . $c1 . '). Each of the other three misses a whole category.</p>
      <p><span class="term-label">The setup, and the answer.</span> P(first draw ' . $c1 . ') = ' . $r . '/' . $total . '. The only way to fail the event is both ' . $c2 . ', so P(at least one ' . $c1 . ') = 1 &minus; (' . $b . '/' . $total . ' &times; (' . ($b - 1) . '/' . $n1 . ')) = ' . $ans . '. Without the tree, nothing would stop a student from reusing the first-stage ' . $r . '/' . $total . ' on the second stage: which is exactly what committing to the structure prevents.</p>
      <p><span class="term-label">Part (b): grading Response ' . $noStructureLabel . ' line by line.</span></p>
      <ul>
        <li><b>Draw the Structure: NOT earned.</b> The probabilities appear as bare calculations with no tree and no Venn, so there is no picture in which a mis-stated second-stage probability could be caught.</li>
        <li><b>Fill the Branches or Regions: earned.</b> Both stage probabilities are present and correct: ' . $r . '/' . $total . ' for the first stage, and ' . $b . '/' . $total . ' &times; (' . ($b - 1) . '/' . $n1 . ') for both ' . $c2 . '.</li>
        <li><b>Answer the Question Asked: earned.</b> It gives P(at least one ' . $c1 . ') = ' . $ans . '.</li>
      </ul>
      <p><span class="term-label">Part (c): why the structure is its own category.</span> The numbers can be right while the picture was never committed to. The structure is the only place a wrong second-stage probability is visible, so a response that skips it produces a plausible-looking answer that a grader cannot audit.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the lab and on the test this scenario comes with a blank box and this same checklist. The structure is the category most often missing, because once the numbers are down the drawing feels like busywork.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> $context holds <b>$r $c1</b> and <b>$b $c2</b> $item, $total in total. Two are drawn at random, without replacement.</p>
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> Set up a tree or Venn diagram that shows the two draws, fill in the branch or region probabilities, and use it to find P(at least one $c1).</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noStructureLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is getting the probabilities right enough on its own to cover drawing the structure? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
