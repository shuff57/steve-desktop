// === NAME - DESCRIPTION: Pre-FRQ Grade a Variable Classification - The scenario and grading checklist of the classification FRQ, but the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Chapter 1's pre-FRQ for 1.1, built like the 1.2 and 1.4 ones: the SAME scenario and the SAME grading
// checklist as frq/descriptive-statistics/q2-classification-and-justification, with the writing
// replaced by grading. The category students drop here is the last one: why the classification
// matters: because once three variables have been sorted the answer feels finished.
//
// Every response is assembled from fixed sentence-parts, so which categories each earns is a
// structural fact rather than a judgement that could shift with the seed.
$anstypes = array("choices", "multans", "choices")

$si = rand(0, 2)
if ($si == 0) {
  $setting = "A clinic records three things about every patient who visits: their blood type, the number of prior visits they have made, and their body temperature in degrees."
  $v1 = "blood type"
  $v2 = "number of prior visits"
  $v3 = "body temperature"
  $why3 = "it is measured on a scale and can take any value in a range, including fractions"
}
elseif ($si == 1) {
  $setting = "A bookshop records three things about every order: the payment method used, the number of books in the order, and the total weight of the parcel in kilograms."
  $v1 = "payment method"
  $v2 = "number of books"
  $v3 = "parcel weight"
  $why3 = "it is measured on a scale and can take any value in a range, including fractions"
}
else {
  $setting = "A gym records three things about every member: the membership tier they hold, the number of classes they attended last month, and the time in minutes of their longest workout."
  $v1 = "membership tier"
  $v2 = "number of classes attended"
  $v3 = "longest workout time"
  $why3 = "it is measured on a scale and can take any value in a range, including fractions"
}

// --- Sentence-parts. Classification, justification, and the "why it matters" category.
$sClass = "The " . $v1 . " is categorical. The " . $v2 . " is quantitative and discrete, and the " . $v3 . " is quantitative and continuous."
$sJust = "The " . $v1 . " is a label rather than an amount, so it cannot be added or averaged. The " . $v2 . " is a count, so it can only land on whole numbers. The " . $v3 . " is different again, because " . $why3 . "."
$sWhy = "The classification decides what you may do next: a categorical variable is summarized with counts and a bar graph, while a quantitative one can be summarized with a mean and a standard deviation and drawn as a histogram. Applying the wrong one produces a number that means nothing."

$rFull = $sClass . " " . $sJust . " " . $sWhy
$rNoWhy = $sClass . " " . $sJust . " All three were straightforward to sort once the definitions were clear."
$rNoJust = $sClass . " " . $sWhy
$rClassOnly = $sClass . " Those are the three types that appear in this course."

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoWhy
$rC = $rNoJust
$rD = $rClassOnly
if ($pos == 1) {
  $rA = $rNoWhy
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoJust
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rClassOnly
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

// Part (b) grades the response that classifies and justifies but never says why it matters. It earns
// two of the three categories, so ticking all three is wrong and ticking one is wrong.
$noWhyLabel = "B"
if ($pos == 1) { $noWhyLabel = "A" }

$questions[1] = array(
  "Variable Classification (4 pts)",
  "Justification (3 pts)",
  "Why Classification Matters (3 pts)"
)
$answer[1] = "0,1"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. Sorting the variables correctly shows you can apply the definitions; it does not show you know what the classification is FOR. The rubric awards those separately because they are separate pieces of understanding.",
  "Yes. Once all three variables are classified correctly the remaining category follows automatically.",
  "No, but only because the response was too short. Restating the three classifications would have earned it.",
  "Yes, provided the discrete and continuous distinction was also made."
)
$answer[2] = 0

$css = '
<style>
  .qscope5 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope5 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope5 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope5 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope5 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope5 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope5 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope5 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope5 .row-colored { background:#fff9ea; }
  .qscope5 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope5 .resp b { color:#1865f2; }
</style>'

$rubric = $css . '
<div class="qscope5">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Variable Classification<br>(4 pts)</b></td>
            <td>Classify each of the three variables as categorical or quantitative, and for the quantitative ones say whether each is discrete or continuous.</td></tr>
          <tr><td style="text-align:center;"><b>Justification<br>(3 pts)</b></td>
            <td>Explain the reasoning behind each classification: not just the label, but why it applies.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Why Classification Matters<br>(3 pts)</b></td>
            <td>Explain why classifying correctly matters when choosing a statistical method, with at least one example of how the method differs.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$responses = '
<div class="qscope5">
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
      <p><span class="term-label">Part (a): only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> classifies all three variables including the discrete-versus-continuous split, explains the reasoning behind each, and then says what the classification is for. Each of the other three drops a whole category, and a dropped category scores zero however well the rest is written.</p>
      <p><span class="term-label">The correct classification.</span> The ' . $v1 . ' is <b>categorical</b>: a label, not an amount. The ' . $v2 . ' is <b>quantitative and discrete</b>, because it is a count and can only be a whole number. The ' . $v3 . ' is <b>quantitative and continuous</b>, because ' . $why3 . '.</p>
      <p><span class="term-label">Part (b): grading Response ' . $noWhyLabel . ' line by line.</span></p>
      <ul>
        <li><b>Variable Classification: earned.</b> All three are sorted, and the discrete and continuous distinction is made.</li>
        <li><b>Justification: earned.</b> It gives a reason for each, not just the label.</li>
        <li><b>Why Classification Matters: NOT earned.</b> It closes by saying the sorting was straightforward. That is a remark about the task, not an explanation of why the classification changes what you may do next. This is the only category it misses.</li>
      </ul>
      <p><span class="term-label">Part (c): why that last category is separate.</span> Classifying correctly proves you can apply a definition. The rubric also asks what the definition is FOR: a categorical variable is summarized with counts, proportions and a bar graph, while a quantitative one supports a mean, a standard deviation and a histogram. Average the ' . $v1 . ' and you get a number that means nothing at all: the classification is what stops you doing it.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the lab and on the test this scenario comes with a blank box and this same checklist. The last category is the one most often skipped, because once three variables are sorted the answer feels complete. Spotting the gap in someone else&rsquo;s work is the fastest way to stop leaving it in your own.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 12px 0;"><b>The scenario.</b> $setting</p>
    <p style="margin:0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> Classify each of the three variables, justify each classification, and explain why classifying variables correctly matters.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noWhyLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is classifying the three variables correctly enough on its own to cover the last category? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
