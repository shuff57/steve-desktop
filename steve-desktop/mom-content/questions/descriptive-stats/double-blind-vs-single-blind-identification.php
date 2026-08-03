// === NAME - DESCRIPTION: Single-Blind vs Double-Blind vs Not Blinded - classify a study from an explicit statement of which roles do and do not know each participant's treatment assignment ===
// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL ===

// Scenario pool: subject group, the treatment, the comparison condition, and the
// role who records outcomes, all picked together so the wording stays consistent.
$subjectPool = array(
  "patients with migraines",
  "adults enrolled in a weight-loss study",
  "college students",
  "competitive sprinters",
  "clients at a counseling center",
  "adults with chronic insomnia"
)
$studyPool = array(
  "a new pill called Cardizal",
  "a daily meal-replacement shake marketed for weight loss",
  "a phone app that claims to sharpen short-term memory",
  "a sports drink marketed to improve sprint speed",
  "a guided-meditation app marketed to reduce anxiety",
  "an over-the-counter sleep aid called Somnolex"
)
$controlPool = array(
  "an identical-looking placebo pill",
  "a shake with the same calories but none of the marketed active ingredient",
  "a phone app with the same layout but no memory exercises",
  "a flavor-matched drink with none of the marketed ingredients",
  "an app that plays unguided background music for the same length of time",
  "an identical-looking pill with no active ingredient"
)
$raterPool = array(
  "nurse who records each patient's headache-severity score",
  "dietitian who weighs each participant at the study end",
  "researcher who grades each student's memory-test score",
  "coach who times each sprinter's 100-meter run",
  "psychologist who scores each client's anxiety survey",
  "sleep-lab technician who records each patient's total sleep time"
)
$picked = jointrandfrom($subjectPool, $studyPool, $controlPool, $raterPool)
$subjectNoun = $picked[0]
$study = $picked[1]
$control = $picked[2]
$raterLabel = $picked[3]

$n = rand(6, 24) * 10

// Blind the subjects and the rater independently, so a blinded role never
// automatically implies both roles are blinded.
$subjectBlind = rand(0, 1)
$raterBlind = rand(0, 1)

$subjectSentence = "None of the $n $subjectNoun are told whether they received $study or $control." if ($subjectBlind == 1)
$subjectSentence = "Each of the $n $subjectNoun is told, as soon as the study begins, whether they received $study or $control." if ($subjectBlind == 0)

$raterSentence = "The $raterLabel is kept from knowing which participants received $study and which received $control until after all the data are recorded." if ($raterBlind == 1)
$raterSentence = "The $raterLabel knows, while recording the data, which participants received $study and which received $control." if ($raterBlind == 0)

$subjectStatus = "kept from knowing their own assignment" if ($subjectBlind == 1)
$subjectStatus = "told their own assignment" if ($subjectBlind == 0)

$raterStatus = "kept from knowing each participant's assignment" if ($raterBlind == 1)
$raterStatus = "told each participant's assignment" if ($raterBlind == 0)

if ($subjectBlind == 1 && $raterBlind == 1) {
  $answer = 1
  $whoBlinded = "Both roles are blinded: the $subjectNoun are $subjectStatus, and the $raterLabel is $raterStatus."
} elseif ($subjectBlind == 1 && $raterBlind == 0) {
  $answer = 0
  $whoBlinded = "Only one role is blinded: the $subjectNoun are $subjectStatus, but the $raterLabel is $raterStatus."
} elseif ($raterBlind == 1 && $subjectBlind == 0) {
  $answer = 0
  $whoBlinded = "Only one role is blinded: the $raterLabel is $raterStatus, but the $subjectNoun are $subjectStatus."
} else {
  $answer = 2
  $whoBlinded = "Neither role is blinded: the $subjectNoun are $subjectStatus, and the $raterLabel is $raterStatus."
}

$questions = array(
  "Single-blind &#8212; exactly one of the two roles described (the participants or the person recording the outcome) is kept from knowing the assignment",
  "Double-blind &#8212; both the participants and the person recording the outcome are kept from knowing the assignment",
  "Not blinded &#8212; neither the participants nor the person recording the outcome is kept from knowing the assignment"
)

$correctlabel = $questions[$answer]

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><b>Step 1 &#8212; Check each role separately.</b> Blinding is judged one role at a time. A study can blind the participants only, the person recording the outcome only, both, or neither &#8212; the wording has to be checked for each role, not assumed from the other.</p>
      <p><b>Step 2 &#8212; The participants.</b> ' . $subjectSentence . '</p>
      <p><b>Step 3 &#8212; The person recording the outcome.</b> ' . $raterSentence . '</p>
      <p><b>Step 4 &#8212; Count how many roles are blinded.</b> ' . $whoBlinded . ' Not blinded means zero roles are blinded. Single-blind means exactly one role is blinded. Double-blind means both roles are blinded &#8212; a study is not automatically double-blind just because someone in it is blinded.</p>
      <p><b>Answer:</b> ' . $correctlabel . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Researchers want to know whether $study works better than $control. They enroll $n $subjectNoun and randomly assign each one to receive $study or $control.</p>
    <p style="margin:12px 0 0 0;">$subjectSentence</p>
    <p style="margin:12px 0 0 0;">$raterSentence</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    Based on this description, is the study single-blind, double-blind, or not blinded?
    <div style="margin-top:12px;">$answerbox</div>
  </div>
</div>

// === ANSWER ===

$solutionguide
