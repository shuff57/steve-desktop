// === NAME - DESCRIPTION: Pre-FRQ Grade a Study Critique - The scenario and grading checklist of the reading-a-study FRQ, but the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Chapter 1's second pre-FRQ, for 1.4, built exactly like the 1.2 sampling one: the SAME scenario and
// the SAME grading checklist as the free-response question this section is assessed by
// (frq/descriptive-statistics/q12-reading-a-study-frq), with the writing replaced by grading. The
// point is the category students silently omit -- here, saying what may and may not be concluded.
//
// Every response is built AGAINST the rubric rather than written and then judged, so which categories
// each one earns is fixed by construction and cannot drift with the seed.
$anstypes = array("choices", "multans", "choices")

$si = rand(0, 2)
if ($si == 0) {
  $study = "A health blog reports that people who drink green tea every day tend to weigh less than people who do not. The data came from an online survey in which readers reported their own tea habits and weight."
  $typeText = "an observational study, because nobody was assigned to drink green tea -- the researchers only recorded habits people already had"
  $biasName = "confounding"
  $biasText = "green-tea drinkers may also exercise more or eat better, and readers who chose to respond are not like everyone else"
  $conclText = "green tea is ASSOCIATED with lower weight in this group, but nothing here shows it CAUSES weight loss"
  $treatment = "drinking green tea"
}
elseif ($si == 1) {
  $study = "A school tries a new math app with one class and compares its test scores with another class that used the old materials. Students were not randomly assigned, and the class using the app happened to be the honors section."
  $typeText = "an observational study in effect, because there was no random assignment -- the two classes were compared as they already stood"
  $biasName = "a confounding variable"
  $biasText = "the app class was the honors section, so the two groups differed in ability before the app was ever used"
  $conclText = "the app is associated with higher scores here, but the honors students might well have scored higher anyway"
  $treatment = "using the math app"
}
else {
  $study = "A researcher notices that towns with more ice cream shops tend to report higher crime rates, using data taken from town records."
  $typeText = "an observational study, because it is built from records already collected and no treatment was assigned to anyone"
  $biasName = "a lurking variable"
  $biasText = "town population size drives both the number of ice cream shops and the amount of crime"
  $conclText = "the two rise together, but ice cream does not cause crime -- the relationship is explained by something else"
  $treatment = "opening ice cream shops"
}

// --- The four responses. Each is assembled from the same three sentence-parts so that dropping a
// category is a structural fact about the response, not a matter of interpretation.
$sType = "This is " . $typeText . "."
$sBias = "A serious problem is " . $biasName . ": " . $biasText . ", which is why the comparison cannot be taken at face value."
$sConcl = "So we can only say that " . $conclText . "."

$rFull = $sType . " " . $sBias . " " . $sConcl
$rNoConcl = $sType . " " . $sBias . " The study therefore has a clear weakness in how it was carried out."
$rNoBias = $sType . " " . $sConcl . " Without a stronger design there is not much more that can be claimed."
$rTypeOnly = $sType . " The finding is interesting and would be worth following up with more data before anything is decided."

// Which of the four is the full-credit answer is randomized, so its position is not the tell.
$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoConcl
$rC = $rNoBias
$rD = $rTypeOnly
// One statement per line -- the dialect allows no separator, and two assignments sharing a line
// happen to parse today but are exactly the shape the rules warn about.
if ($pos == 1) {
  $rA = $rNoConcl
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoBias
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rTypeOnly
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

// Part (b) grades the NO-CONCLUSION response, which is always the one sitting where $rNoConcl ended
// up. It earns Study Type and Bias or Confounding and fails only Justified Conclusion -- one category,
// so ticking everything is wrong and ticking nothing is wrong.
$noConclLabel = "B"
if ($pos == 1) { $noConclLabel = "A" }

$questions[1] = array(
  "Study Type (3 pts)",
  "Bias or Confounding (4 pts)",
  "Justified Conclusion (3 pts)"
)
$answer[1] = "0,1"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. Naming the flaw says the study is weak; it does not say what may still be claimed from it. The rubric awards those separately because they are separate pieces of thinking.",
  "Yes. Once a confounding variable has been named, the conclusion follows automatically and need not be written.",
  "No, but only because the response was too short. Naming the confounder twice would have earned the conclusion.",
  "Yes, as long as the study was correctly classified as observational."
)
$answer[2] = 0

$css = '
<style>
  .qscope4 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope4 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope4 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope4 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope4 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope4 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope4 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope4 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope4 .row-colored { background:#fff9ea; }
  .qscope4 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope4 .resp b { color:#1865f2; }
</style>'

$rubric = $css . '
<div class="qscope4">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Study Type<br>(3 pts)</b></td>
            <td>Classify the study as observational or experimental, and justify it by whether a treatment was randomly assigned.</td></tr>
          <tr><td style="text-align:center;"><b>Bias or Confounding<br>(4 pts)</b></td>
            <td>Name one specific, plausible source of bias or a confounding variable, and explain briefly why it is a problem here.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Justified Conclusion<br>(3 pts)</b></td>
            <td>State what can and cannot be concluded &mdash; association versus causation, or the limits on generalizing.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$responses = '
<div class="qscope4">
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
      <p><span class="term-label">Part (a) &mdash; only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> classifies the study AND says why, names a specific problem AND says why it matters, and then states what may and may not be concluded. Each of the other three drops at least one whole category, and a dropped category scores zero however well the rest is written.</p>
      <p><span class="term-label">Part (b) &mdash; grading Response ' . $noConclLabel . ' line by line.</span></p>
      <ul>
        <li><b>Study Type &mdash; earned.</b> It classifies the study and ties the classification to the absence of random assignment.</li>
        <li><b>Bias or Confounding &mdash; earned.</b> It names ' . $biasName . ' and explains why it matters here.</li>
        <li><b>Justified Conclusion &mdash; NOT earned.</b> It ends by saying the study has a weakness. That is a comment on the design, not a statement of what the data does and does not support. This is the only category it misses.</li>
      </ul>
      <p><span class="term-label">Part (c) &mdash; why the conclusion is its own category.</span> Naming a flaw tells a reader the study is imperfect. It does not tell them what they may still take away, and that is what a decision actually rests on. The honest conclusion here is that ' . $conclText . ' &mdash; a specific, checkable claim, and a different piece of thinking from spotting the flaw. Because no one was assigned to ' . $treatment . ', causation was never available to be claimed.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the lab and on the test this scenario comes with a blank box and this same checklist. Finding the missing category in someone else&rsquo;s answer is the quickest way to stop leaving it out of your own &mdash; and the conclusion is the one most often left out, because it feels like it has already been said.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 12px 0;"><b>The scenario.</b> $study</p>
    <p style="margin:0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> Identify the type of study, name a source of bias or confounding, and state a justified conclusion.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noConclLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is naming the flaw enough on its own to cover the justified conclusion? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
