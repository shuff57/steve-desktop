// === NAME - DESCRIPTION: Pre-FRQ Grade a Probability Setup - A two-dice probability scenario with a grading checklist, where the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The pre-FRQ for 3.1. Chapter 3 has no intro-stats FRQ to mirror (questions/frq/probability holds
// only an expected-value item), so the scenario and checklist here are ORIGINAL and define the shape
// a later 3.1 FRQ should match. See reference/pre-frq-template.md.
//
// The dropped category is DEFINE THE SAMPLE SPACE. Students count the favourable outcomes and give a
// number, never establishing what the full set of equally likely outcomes is. 3.1 is the section
// where that set IS the content, and it is the step that feels like throat-clearing.
//
// CATEGORY PURITY, the trap to watch here: the probability sentence must NOT be written as a
// fraction over 36, because that states the size of the sample space and hands the dropped category
// back. It is given as a decimal instead, which a student can produce from memory without ever
// saying what the denominator counts.
$anstypes = array("choices", "multans", "choices")

$i = rand(0, 3)

$ks = array(5, 8, 9, 10)
$k = $ks[$i]

$counts = array(4, 5, 4, 3)
$cnt = $counts[$i]

$pairlists = array(
  "(1,4), (2,3), (3,2) and (4,1)",
  "(2,6), (3,5), (4,4), (5,3) and (6,2)",
  "(3,6), (4,5), (5,4) and (6,3)",
  "(4,6), (5,5) and (6,4)"
)
$pairs = $pairlists[$i]

$decimals = array("0.111", "0.139", "0.111", "0.083")
$dec = $decimals[$i]

$fracs = array("1/9", "5/36", "1/9", "1/12")
$frac = $fracs[$i]

// One sentence per rubric category. None restates another -- in particular $sProb names no
// denominator, so the response that drops the sample space never leaks it.
$sSpace = 'Rolling two dice gives 36 equally likely ordered outcomes, because each of the six faces on the first die can pair with each of the six faces on the second, and (2,3) is a different outcome from (3,2).'
$sEvent = 'The outcomes with a sum of ' . $k . ' are ' . $pairs . ' &mdash; ' . $cnt . ' of them.'
$sProb = 'The probability of that event is therefore about ' . $dec . '.'

$rFull = $sSpace . ' ' . $sEvent . ' ' . $sProb
$rNoSpace = $sEvent . ' ' . $sProb
$rNoEvent = $sSpace . ' ' . $sProb
$rSpaceOnly = $sSpace . ' That is the setup the question needs.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoSpace
$rC = $rNoEvent
$rD = $rSpaceOnly
if ($pos == 1) {
  $rA = $rNoSpace
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoEvent
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rSpaceOnly
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noSpaceLabel = "B"
if ($pos == 1) { $noSpaceLabel = "A" }

$questions[1] = array(
  "Define the Sample Space (3 pts)",
  "Identify the Event (4 pts)",
  "State the Probability (3 pts)"
)
$answer[1] = "1,2"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. A probability is a share OF something, so the set it is a share of has to be stated; without it the number is unsupported even when it is right.",
  "Yes. Once the favourable outcomes are counted, the probability follows and the sample space does not need stating.",
  "No, but only because the response did not list the favourable outcomes a second time.",
  "Yes, as long as the final probability is correct."
)
$answer[2] = 0

$css = '
<style>
  .qscope31 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope31 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope31 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope31 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope31 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope31 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope31 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope31 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope31 .row-colored { background:#fff9ea; }
  .qscope31 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope31 .resp b { color:#1865f2; }
  .qscope31 .setup { border:1px solid #d1d5db; border-radius:8px; padding:10px 14px; margin:10px 0; background:#f8fafc; font-size:15px; }
</style>'

$rubric = $css . '
<div class="qscope31">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Define the Sample Space<br>(3 pts)</b></td>
            <td>State how many equally likely outcomes the experiment has, and why that is the count.</td></tr>
          <tr><td style="text-align:center;"><b>Identify the Event<br>(4 pts)</b></td>
            <td>Say which outcomes make up the event, and how many there are.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>State the Probability<br>(3 pts)</b></td>
            <td>Give the probability of the event as a number.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$setupBlock = '
<div class="qscope31">
  <div class="setup">Two fair six-sided dice are rolled and the outcome is recorded as an ordered pair (first die, second die).<br>
  <b>The event:</b> the two faces sum to ' . $k . '.</div>
</div>'

$responses = '
<div class="qscope31">
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
      <p><span class="term-label">Part (a) &mdash; only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> establishes the 36 outcomes, lists the ones in the event, and gives the probability. Each of the other three drops a whole category.</p>
      <p><span class="term-label">The numbers.</span> `6 xx 6 = 36` ordered outcomes. The pairs summing to ' . $k . ' are ' . $pairs . ', so the event holds ' . $cnt . ' of them and the probability is `' . $frac . ' ~~ ' . $dec . '`.</p>
      <p><span class="term-label">Part (b) &mdash; grading Response ' . $noSpaceLabel . ' line by line.</span></p>
      <ul>
        <li><b>Define the Sample Space &mdash; NOT earned.</b> It never says there are 36 equally likely outcomes or why. This is the only category it misses.</li>
        <li><b>Identify the Event &mdash; earned.</b> It lists the pairs and counts them.</li>
        <li><b>State the Probability &mdash; earned.</b> It gives the number.</li>
      </ul>
      <p><span class="term-label">Part (c) &mdash; why the sample space is its own category.</span> Every probability is a share of something. ' . $cnt . ' outcomes is not a probability until you say ' . $cnt . ' out of what &mdash; and the answer is not obvious, because the natural wrong answer is 21 unordered pairs rather than 36 ordered ones. A response that skips it can be right by memory and cannot show it is right.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the lab and on the test this scenario comes with a blank box and this same checklist. The sample space is the category most often missing, because counting the favourable outcomes feels like where the real work is.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b></p>
    $setupBlock
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> Set up the sample space, identify the event, and find its probability.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noSpaceLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is counting the favourable outcomes and giving the probability enough on its own to cover the sample space? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
