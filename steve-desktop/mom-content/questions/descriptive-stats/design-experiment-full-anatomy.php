// === NAME - DESCRIPTION: Anatomy of an Experiment - Given a randomized research question, identify the explanatory and response variables, the population, the experimental units, the two treatments, and whether the subjects can be blinded ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices", "choices", "choices", "choices", "choices")

$teaDoseOptions = array(150, 200, 250, 300)
$teaDose = $teaDoseOptions[rand(0, 3)]

$walkMinOptions = array(10, 15, 20, 25)
$walkMin = $walkMinOptions[rand(0, 3)]

$nOptions = array(40, 50, 60, 75, 80, 100, 120)
$n = $nOptions[rand(0, 6)]

// Two scenarios. Every option list puts the correct choice at index 0; MOM shuffles the
// displayed order per seed, so the position never leaks. The distractors are the mistakes the
// topic actually produces: swapping explanatory and response, naming the sample or one treatment
// group as the population, naming the treatments as the units, naming the groups or the response
// levels as the treatments, and confusing random assignment with blinding.
$s = rand(0, 1)

if ($s == 0) {
  $studyDesc = "Researchers want to know whether a " . $teaDose . " mg dose of an herbal sleep tea taken before bed shortens the time it takes to fall asleep."
  $expShort = "which tea the participant drank before bed, the herbal sleep tea or the placebo tea"
  $respShort = "the number of minutes it took the participant to fall asleep"
  $popText = "adults who report occasional difficulty falling asleep"
  $unitsText = "the individual volunteers enrolled in the sleep study"
  $activeShort = "the herbal sleep tea at " . $teaDose . " mg"
  $inactiveShort = "a placebo tea with no active ingredient, made to look and taste the same as the herbal tea"
  $blindText = "Yes"
  $blindReason = "the herbal tea and the placebo tea can be prepared to look, smell, and taste identical, so a participant cannot tell which one they drank"
  $questions[0] = array(
    "which tea the participant drank before bed, the herbal sleep tea or the placebo tea",
    "the number of minutes it took the participant to fall asleep",
    "the " . $teaDose . " mg dose of herbal sleep tea",
    "the " . $n . " volunteers recruited for the study"
  )
  $questions[1] = array(
    "the number of minutes it took the participant to fall asleep",
    "which tea the participant drank before bed, the herbal sleep tea or the placebo tea",
    "the placebo tea with no active ingredient",
    "the " . $n . " volunteers recruited for the study"
  )
  $questions[2] = array(
    "adults who report occasional difficulty falling asleep",
    "the " . $n . " volunteers recruited for the study",
    "the half of the volunteers who drank the herbal sleep tea",
    "the minutes it took each volunteer to fall asleep"
  )
  $questions[3] = array(
    "the individual volunteers enrolled in the sleep study",
    "adults who report occasional difficulty falling asleep",
    "the herbal sleep tea and the placebo tea",
    "the minutes recorded for each volunteer"
  )
  $questions[4] = array(
    "the herbal sleep tea and a look-alike placebo tea with no active ingredient",
    "the herbal sleep tea and no tea at all",
    "falling asleep quickly and falling asleep slowly",
    "the volunteers who drank the herbal tea and the volunteers who drank the placebo"
  )
  $questions[5] = array(
    "Yes. The herbal tea and the placebo tea can be made to look, smell, and taste the same, so a participant cannot tell which one they drank.",
    "No. A participant always knows what they drank before bed.",
    "Yes. The volunteers were assigned to the two teas at random.",
    "No. The researchers know which tea each volunteer received."
  )
}

if ($s == 1) {
  $studyDesc = "Researchers want to know whether a " . $walkMin . "-minute brisk walk taken before an afternoon shift reduces the fatigue score a worker reports at the end of the shift."
  $expShort = "whether the participant took a brisk walk or sat quietly before the shift"
  $respShort = "the fatigue score the participant reported at the end of the shift"
  $popText = "employees who work afternoon shifts at the company"
  $unitsText = "the individual employee volunteers enrolled in the study"
  $activeShort = "a " . $walkMin . "-minute brisk walk taken before the shift"
  $inactiveShort = "sitting quietly for " . $walkMin . " minutes before the shift"
  $blindText = "No"
  $blindReason = "a participant obviously knows whether they walked or sat quietly before their shift, so the subject cannot be kept unaware of the treatment"
  $questions[0] = array(
    "whether the participant took a brisk walk or sat quietly before the shift",
    "the fatigue score the participant reported at the end of the shift",
    "the " . $walkMin . "-minute brisk walk",
    "the " . $n . " employee volunteers recruited for the study"
  )
  $questions[1] = array(
    "the fatigue score the participant reported at the end of the shift",
    "whether the participant took a brisk walk or sat quietly before the shift",
    "sitting quietly for " . $walkMin . " minutes before the shift",
    "the " . $n . " employee volunteers recruited for the study"
  )
  $questions[2] = array(
    "employees who work afternoon shifts at the company",
    "the " . $n . " employee volunteers recruited for the study",
    "the half of the volunteers who took the brisk walk",
    "the fatigue scores reported at the end of the shift"
  )
  $questions[3] = array(
    "the individual employee volunteers enrolled in the study",
    "employees who work afternoon shifts at the company",
    "the brisk walk and the quiet sitting",
    "the fatigue score recorded for each volunteer"
  )
  $questions[4] = array(
    "a " . $walkMin . "-minute brisk walk before the shift and sitting quietly for " . $walkMin . " minutes before the shift",
    "a brisk walk before the shift and no instruction at all",
    "high fatigue and low fatigue at the end of the shift",
    "the volunteers who walked and the volunteers who sat quietly"
  )
  $questions[5] = array(
    "No. A participant obviously knows whether they walked or sat quietly before the shift, so the subject cannot be kept unaware of the treatment.",
    "Yes. The walk and the quiet sitting take the same amount of time, so a participant cannot tell which treatment they received.",
    "Yes. The volunteers were assigned to the two activities at random.",
    "No. The researchers know which activity each volunteer was assigned."
  )
}

$answer[0] = 0
$answer[1] = 0
$answer[2] = 0
$answer[3] = 0
$answer[4] = 0
$answer[5] = 0

$displayformat[0] = "select"
$displayformat[1] = "select"
$displayformat[2] = "select"
$displayformat[3] = "select"
$displayformat[4] = "select"
$displayformat[5] = "select"

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
      <p><span class="term-label">Step 1: Name the variables.</span> ' . $studyDesc . ' The explanatory variable is ' . $expShort . '. The response variable is ' . $respShort . '.</p>
      <p><span class="term-label">Step 2: Population and experimental units.</span> The population is ' . $popText . '. The experimental units are ' . $unitsText . '.</p>
      <p><span class="term-label">Step 3: Treatments.</span> There are two treatments. The active treatment is ' . $activeShort . '. The inactive, placebo-like treatment is ' . $inactiveShort . '. Every experimental unit is assigned to one of the two treatments at random, not by letting participants choose: letting them choose would sort motivated or health-conscious participants into one group and hand the study a lurking variable.</p>
      <p><span class="term-label">Step 4: Blinding.</span> Can the subjects be blinded? <b>' . $blindText . '</b>, because ' . $blindReason . '. Random assignment is a different idea: it decides who gets which treatment, and it does not hide the treatment from the subject.</p>
      <p><b>Answer:</b> explanatory: ' . $expShort . '; response: ' . $respShort . '; population: ' . $popText . '; experimental units: ' . $unitsText . '; treatments: ' . $activeShort . ' as the active treatment and ' . $inactiveShort . ' as the inactive treatment; blinding of subjects: ' . $blindText . '.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$studyDesc They recruit $n volunteers for the study. Half of the volunteers will be randomly assigned to an active treatment and half to an inactive, placebo-like treatment.</p>
    <p style="margin:12px 0 0 0;">Use the anatomy of an experiment to describe this design. Choose the best description from each list.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the <b>explanatory variable</b> in this study? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the <b>response variable</b> in this study? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which describes the <b>population</b> being studied? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> What are the <b>experimental units</b>? $answerbox[3]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">e.</span> Which pair names the <b>two treatments</b> used in this study, one active and one inactive? $answerbox[4]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">f.</span> Is <b>blinding</b> possible for the subjects in this study? Choose the answer with the correct reason. $answerbox[5]
  </div>
</div>

// === ANSWER ===

$solutionguide
