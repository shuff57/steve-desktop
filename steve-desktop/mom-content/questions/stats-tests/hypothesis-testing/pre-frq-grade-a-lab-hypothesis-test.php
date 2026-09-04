// === NAME - DESCRIPTION: Pre-FRQ Grade a Lab Hypothesis Test - the scenario and grading checklist of the (authored-first) lab hypothesis-test FRQ, where the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Authored-first pre-FRQ (no lab FRQ to mirror; the 8.1-8.5 pre-FRQs are claimed).
// Categories: State the Hypotheses (3) / Run the Test (4) / Write the Conclusion (3) = 10.
//
// The dropped category is RUN THE TEST. A student can state the hypotheses and write a
// conclusion for a given result without ever computing the test statistic - the lab's own
// "the distribution is a decision you make before you compute anything" is exactly the step a
// plausible answer skips (the arithmetic is the one category the others imply but never
// demand).
//
// CATEGORY PURITY: $sHypotheses states the pair and nothing else; $sRun shows the arithmetic
// and nothing else; $sConclusion writes the conclusion and nothing else.
$anstypes = array("choices", "multans", "choices")

$contexts = array(
  array("The Television Survey: Americans watch 4 hours per day, sigma = 2 known. A class of 30 students reports an average of 3.2 hours per day. Test at alpha = 0.05 whether the average is lower.",
        "`H_0: mu = 4` and `H_a: mu < 4`.",
        "The standard error is 2/sqrt(30) = 0.3651, so z = (3.2 - 4)/0.3651 = -2.19 and the p-value is 0.0142.",
        "At the 5% significance level there is sufficient evidence to conclude that the mean hours of television watched per day by students in this class is less than four."),
  array("The Language Survey: 42.3% of Californians speak a language other than English at home. In a class of 25 students, 14 report speaking a language other than English at home. Test at alpha = 0.05 whether the proportion differs from 42.3%.",
        "`H_0: p = 0.423` and `H_a: p != 0.423`.",
        "p' = 14/25 = 0.56, SE = 0.0988, so z = (0.56 - 0.423)/0.0988 = 1.39 and the two-tailed p-value is 0.165.",
        "At the 5% significance level there is not sufficient evidence to conclude that the proportion of students at this school who speak a language other than English at home differs from 42.3%."),
  array("The Jeans Survey: young adults own 3 pairs of jeans on average. Eight students report owning an average of 3.5 pairs with a sample standard deviation of 0.76. Test at alpha = 0.05 whether the average is higher than three.",
        "`H_0: mu = 3` and `H_a: mu > 3`.",
        "The standard error is 0.76/sqrt(8) = 0.2687, so t = (3.5 - 3)/0.2687 = 1.86 with df = 7 and the p-value is 0.053.",
        "At the 5% significance level there is not sufficient evidence to conclude that young adults own more than three pairs of jeans on average.")
)

$i = rand(0, count($contexts)-1)
$ctx = $contexts[$i][0]
$sHypotheses = $contexts[$i][1]
$sRun = $contexts[$i][2]
$sConclusion = $contexts[$i][3]

$rFull    = $sHypotheses . ' ' . $sRun . ' ' . $sConclusion
$rNoRun   = $sHypotheses . ' ' . $sConclusion
$rNoConcl = $sHypotheses . ' ' . $sRun
$rMinimal = $sHypotheses . ' The test is the whole story.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoRun
$rC = $rNoConcl
$rD = $rMinimal
if ($pos == 1) {
  $rA = $rNoRun
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoConcl
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rMinimal
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noRunLabel = "B"
if ($pos == 1) { $noRunLabel = "A" }

$questions[1] = array(
  "State the Hypotheses (3 pts)",
  "Run the Test (4 pts)",
  "Write the Conclusion (3 pts)"
)
$answer[1] = "0,2"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. The hypotheses can be stated and the conclusion written for a given result without ever computing the test statistic, so running the test has to be its own category.",
  "Yes. Once the hypotheses are stated, the arithmetic follows automatically, so there is nothing separate to award.",
  "No, but only because the conclusion is the hard part.",
  "Yes, as long as the conclusion is written, the arithmetic does not matter."
)
$answer[2] = 0

$css = '
<style>
  .qscope86 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope86 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope86 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope86 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope86 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope86 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope86 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope86 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope86 .row-colored { background:#fff9ea; }
  .qscope86 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope86 .resp b { color:#1865f2; }
</style>'

$rubric = $css . '
<div class="qscope86">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>State the Hypotheses<br>(3 pts)</b></td>
            <td>State `H_0` and `H_a` in symbols for the named survey.</td></tr>
          <tr><td style="text-align:center;"><b>Run the Test<br>(4 pts)</b></td>
            <td>Compute the test statistic and the p-value on the correct distribution.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Write the Conclusion<br>(3 pts)</b></td>
            <td>Make the decision and write the conclusion in plain language about the population actually sampled.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$responses = '
<div class="qscope86">
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
      <p><span class="term-label">Part (a): only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> states the hypotheses, runs the test, and writes the conclusion. Each of the other three misses a whole category.</p>
      <p><span class="term-label">The setup, and the answer.</span> ' . $sHypotheses . ' ' . $sRun . ' ' . $sConclusion . '</p>
      <p><span class="term-label">Part (b): grading Response ' . $noRunLabel . ' line by line.</span></p>
      <ul>
        <li><b>State the Hypotheses: earned.</b> The pair is stated in symbols.</li>
        <li><b>Run the Test: NOT earned.</b> The response never computes the test statistic or the p-value.</li>
        <li><b>Write the Conclusion: earned.</b> The decision and the plain-language conclusion are written about the population actually sampled.</li>
      </ul>
      <p><span class="term-label">Part (c): why running the test is its own category.</span> The hypotheses and the conclusion are both possible without ever computing the test statistic: the arithmetic is the one category the others imply but never demand, and a response that skips it has not answered the question.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> This scenario has no FRQ yet: the pre-FRQ defines the scenario and rubric a later FRQ should match. Running the test is the category most often skipped, because once the hypotheses are written the computation feels like busywork.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> $ctx</p>
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> State the hypotheses, run the test on the correct distribution, and write the conclusion in a complete sentence about the population you sampled.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noRunLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is stating the hypotheses and writing the conclusion enough on its own to cover running the test? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
