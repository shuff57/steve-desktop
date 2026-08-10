// === NAME - DESCRIPTION: Pre-FRQ Grade an Independence Check - The scenario and grading checklist of a mutually-exclusive-versus-independent question, where the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The pre-FRQ for 3.2. Chapter 3 has no intro-stats FRQ to mirror, so the scenario and checklist are
// ORIGINAL and define the shape a later 3.2 FRQ should match. See reference/pre-frq-template.md.
//
// The dropped category is DISTINGUISH THE TWO. Students run both tests correctly and never say they
// are different questions, which is how "not mutually exclusive" gets read as if it settled
// independence. Distinct from the dropped categories on 2.3, 2.4, 2.5, 2.6, 2.7, 3.1 and 3.3.
//
// THE NUMBERS ARE THE LESSON: the joint probability is exactly the product, so the two events are
// NOT mutually exclusive AND ARE independent. That is the pair of answers a student cannot reach by
// conflating the two tests, because the two verdicts point opposite ways.
//
// CATEGORY PURITY: $sDistinguish says the tests differ and names the two comparisons, but states
// NEITHER verdict and computes nothing -- otherwise the response that drops the independence test
// would still earn it.
$anstypes = array("choices", "multans", "choices")

$i = rand(0, 2)

$settings = array(
  "students at a college, taking a lab science and holding a campus job",
  "commuters in a city, owning a bicycle and holding a transit pass",
  "households in a town, subscribing to a streaming service and owning a pet"
)
$setting = $settings[$i]

$eventA_labels = array("takes a lab science", "owns a bicycle", "subscribes to a streaming service")
$eventA = $eventA_labels[$i]

$eventB_labels = array("holds a campus job", "holds a transit pass", "owns a pet")
$eventB = $eventB_labels[$i]

$who_labels = array("students", "commuters", "households")
$who = $who_labels[$i]

// Singular form for every place an article precedes the noun. Using the plural $who after "a"
// rendered "a students" / "a commuters" / "a households" in the fact table AND in every sample
// response -- the same defect fixed on 3.3 earlier the same day, reintroduced here.
$who_singular_labels = array("student", "commuter", "household")
$whoOne = $who_singular_labels[$i]

// Multiples of ten so the product is exact. The joint IS the product, deliberately.
$a = rand(4, 6) * 10
$b = rand(3, 5) * 10
$jointPct = $a * $b / 100

$aDec = $a / 100
$bDec = $b / 100
$jointDec = $jointPct / 100

// One sentence per rubric category, none restating another.
$sMutex = 'They are not mutually exclusive: P(A and B) is ' . $jointDec . ', which is not zero, so a ' . $whoOne . ' can be in both groups at once.'
$sIndep = 'They are independent: P(A) x P(B) = ' . $aDec . ' x ' . $bDec . ' = ' . $jointDec . ', which is exactly the joint probability the survey reports.'
$sDistinguish = 'These are two different questions settled by two different comparisons &mdash; one against zero, the other against the product of the two probabilities &mdash; so answering one leaves the other completely open.'

$rFull = $sMutex . ' ' . $sIndep . ' ' . $sDistinguish
$rNoDistinguish = $sMutex . ' ' . $sIndep
$rNoIndep = $sMutex . ' ' . $sDistinguish
$rMutexOnly = $sMutex . ' That settles how the two events relate.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoDistinguish
$rC = $rNoIndep
$rD = $rMutexOnly
if ($pos == 1) {
  $rA = $rNoDistinguish
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoIndep
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rMutexOnly
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noDistinguishLabel = "B"
if ($pos == 1) { $noDistinguishLabel = "A" }

$questions[1] = array(
  "Test for Mutual Exclusivity (3 pts)",
  "Test for Independence (4 pts)",
  "Distinguish the Two (3 pts)"
)
$answer[1] = "0,1"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. Two verdicts placed side by side never say that they answer different questions, and a student who has not separated them reads &#39;not mutually exclusive&#39; as though it had settled independence.",
  "Yes. Once both tests have been carried out correctly, the difference between them is self-evident.",
  "No, but only because the response did not restate the joint probability a second time.",
  "Yes, as long as both comparisons use the right numbers."
)
$answer[2] = 0

$css = '
<style>
  .qscope32 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope32 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope32 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope32 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope32 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope32 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope32 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope32 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope32 .row-colored { background:#fff9ea; }
  .qscope32 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope32 .resp b { color:#1865f2; }
  .qscope32 .facts { border-collapse:collapse; margin:10px 0; font-size:15px; }
  .qscope32 .facts td, .qscope32 .facts th { border:1px solid #d1d5db; padding:6px 14px; text-align:left; }
  .qscope32 .facts th { background:#f0f4ff; }
</style>'

$rubric = $css . '
<div class="qscope32">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Test for Mutual Exclusivity<br>(3 pts)</b></td>
            <td>Compare the joint probability against zero and state the verdict.</td></tr>
          <tr><td style="text-align:center;"><b>Test for Independence<br>(4 pts)</b></td>
            <td>Compare the product of the two probabilities against the joint probability and state the verdict.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Distinguish the Two<br>(3 pts)</b></td>
            <td>Say why these are separate questions, so neither verdict is read as settling the other.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$factBlock = '
<div class="qscope32">
  <table class="facts">
    <tr><th>What was measured</th><th>Probability</th></tr>
    <tr><td>A &mdash; a ' . $whoOne . ' ' . $eventA . '</td><td>' . $aDec . '</td></tr>
    <tr><td>B &mdash; a ' . $whoOne . ' ' . $eventB . '</td><td>' . $bDec . '</td></tr>
    <tr><td>both A and B</td><td>' . $jointDec . '</td></tr>
  </table>
</div>'

$responses = '
<div class="qscope32">
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
      <p><span class="term-label">Part (a) &mdash; only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> runs both tests and then says why they are different questions. Each of the other three drops a whole category.</p>
      <p><span class="term-label">The numbers, and why they were chosen.</span> `P(A) = ' . $aDec . '`, `P(B) = ' . $bDec . '`, and the joint is `' . $jointDec . '`. That is not zero, so the two events are <b>not mutually exclusive</b>. And `' . $aDec . ' xx ' . $bDec . ' = ' . $jointDec . '`, which is exactly the joint, so they <b>are independent</b>. The two verdicts point opposite ways on purpose &mdash; a student who has quietly merged the two ideas cannot get both right.</p>
      <p><span class="term-label">Part (b) &mdash; grading Response ' . $noDistinguishLabel . ' line by line.</span></p>
      <ul>
        <li><b>Test for Mutual Exclusivity &mdash; earned.</b> It compares the joint against zero and gives the verdict.</li>
        <li><b>Test for Independence &mdash; earned.</b> It computes the product and compares it against the joint.</li>
        <li><b>Distinguish the Two &mdash; NOT earned.</b> Both verdicts are there and correct, and nothing says they answer different questions. This is the only category it misses.</li>
      </ul>
      <p><span class="term-label">Part (c) &mdash; why distinguishing them is its own category.</span> Mutually exclusive asks <i>can both happen</i>; independent asks <i>does one change the chance of the other</i>. One is settled against zero, the other against a product, and neither answer constrains the other much: here the events overlap AND are independent. The common wreck is reading "not mutually exclusive" as if it meant "related", which is how a correct pair of calculations still produces a wrong conclusion.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the lab and on the test this scenario comes with a blank box and this same checklist. Distinguishing the two is the category most often missing, because once both tests are done the work looks finished.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> A survey of $setting reported three probabilities.</p>
    $factBlock
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> Decide whether A and B are mutually exclusive, whether they are independent, and explain how those two questions differ.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noDistinguishLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is running both tests enough on its own to cover distinguishing them? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
