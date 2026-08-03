// === NAME - DESCRIPTION: Anatomy of an Experiment - Given a randomized research question, identify the explanatory and response variables, the population, the experimental units, the two treatments, and whether the subjects can be blinded ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("string", "string", "string", "string", "string", "string")

$teaDoseOptions = array(150, 200, 250, 300)
$teaDose = $teaDoseOptions[rand(0, 3)]

$walkMinOptions = array(10, 15, 20, 25)
$walkMin = $walkMinOptions[rand(0, 3)]

$nOptions = array(40, 50, 60, 75, 80, 100, 120)
$n = $nOptions[rand(0, 6)]

$scenarioDesc = array(
  "Researchers want to know whether a " . $teaDose . " mg dose of an herbal sleep tea taken before bed shortens the time it takes to fall asleep.",
  "Researchers want to know whether a " . $walkMin . "-minute brisk walk taken before an afternoon shift reduces a worker's self-reported fatigue score at the end of the shift."
)

$expVarAns = array(
  "which tea the participant drank before bed, the active herbal sleep tea versus the placebo tea or the type of pre-bed tea consumed, active herbal tea versus placebo tea",
  "whether the participant took the brisk walk versus sitting quietly before the shift or the type of pre-shift activity assigned, walking versus sitting quietly"
)

$respVarAns = array(
  "the number of minutes it took the participant to fall asleep or the time to fall asleep, measured in minutes",
  "the participant's self-reported fatigue score at the end of the shift or the afternoon fatigue score reported by the participant"
)

$popAns = array(
  "adults who report occasional difficulty falling asleep or adults with occasional trouble falling asleep",
  "employees who work afternoon shifts at the company or workers assigned to afternoon shifts at the company"
)

$unitsAns = array(
  "the individual volunteers enrolled in the sleep study or the volunteer participants in the study",
  "the individual employee volunteers enrolled in the study or the employee volunteers who participated in the study"
)

$treatAns = array(
  "the active herbal sleep tea and a look-alike placebo tea with no active ingredient or an active dose of the herbal sleep tea versus a placebo tea with no active ingredient",
  "a brisk walk before the shift and sitting quietly for the same amount of time or walking briskly before the shift versus sitting quietly for the same amount of time"
)

$blindAns = array(
  "yes, subjects can be blinded because the active tea and the placebo tea can be made identical in appearance, smell, and taste or yes, blinding of the subjects is possible since the two teas can be prepared to look and taste the same",
  "no, subjects cannot be blinded because a participant always knows which activity they were assigned, walking versus sitting quietly or no, blinding of the subjects is not possible since a person obviously knows whether they exercised before the shift"
)

$activeTreatShort = array(
  "the active herbal sleep tea (" . $teaDose . " mg)",
  "a " . $walkMin . "-minute brisk walk taken before the shift"
)

$inactiveTreatShort = array(
  "a placebo tea with no active ingredient, made to look and taste the same as the herbal tea",
  "sitting quietly for " . $walkMin . " minutes before the shift"
)

$expVarShort = array(
  "which tea the participant drank before bed &ndash; the active herbal sleep tea or the placebo tea",
  "whether the participant walked or sat quietly before their shift"
)

$respVarShort = array(
  "the number of minutes it took the participant to fall asleep",
  "the participant's self-reported fatigue score at the end of the shift"
)

$popShort = array(
  "adults who report occasional difficulty falling asleep",
  "employees who work afternoon shifts at the company"
)

$unitsShort = array(
  "the individual volunteers enrolled in the sleep study",
  "the individual employee volunteers enrolled in the study"
)

$blindYN = array("Yes", "No")

$blindReasonShort = array(
  "the active tea and the placebo tea can be prepared to look, smell, and taste identical, so a participant cannot tell which one they drank",
  "a participant obviously knows whether they walked or sat quietly before their shift, so the subject cannot be kept unaware of the treatment"
)

$picked = jointrandfrom($scenarioDesc, $expVarAns, $respVarAns, $popAns, $unitsAns, $treatAns, $blindAns, $activeTreatShort, $inactiveTreatShort, $expVarShort, $respVarShort, $popShort, $unitsShort, $blindYN, $blindReasonShort)

$studyDesc = $picked[0]
$expVarAnswer = $picked[1]
$respVarAnswer = $picked[2]
$popAnswer = $picked[3]
$unitsAnswer = $picked[4]
$treatAnswer = $picked[5]
$blindAnswer = $picked[6]
$activeShort = $picked[7]
$inactiveShort = $picked[8]
$expShort = $picked[9]
$respShort = $picked[10]
$popText = $picked[11]
$unitsText = $picked[12]
$blindText = $picked[13]
$blindReason = $picked[14]

$answer[0] = $expVarAnswer
$strflags[0] = "ignore_case,trim_whitespace"

$answer[1] = $respVarAnswer
$strflags[1] = "ignore_case,trim_whitespace"

$answer[2] = $popAnswer
$strflags[2] = "ignore_case,trim_whitespace"

$answer[3] = $unitsAnswer
$strflags[3] = "ignore_case,trim_whitespace"

$answer[4] = $treatAnswer
$strflags[4] = "ignore_case,trim_whitespace"

$answer[5] = $blindAnswer
$strflags[5] = "ignore_case,trim_whitespace"

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
      <p><span class="term-label">Step 1 &mdash; Name the variables.</span> ' . $studyDesc . ' The explanatory variable is ' . $expShort . '. The response variable is ' . $respShort . '.</p>
      <p><span class="term-label">Step 2 &mdash; Population and experimental units.</span> The population is ' . $popText . '. The experimental units are ' . $unitsText . '.</p>
      <p><span class="term-label">Step 3 &mdash; Treatments.</span> There are two treatments. The active treatment is ' . $activeShort . '. The inactive, placebo-like treatment is ' . $inactiveShort . '. Every experimental unit is assigned to one of the two treatments at random, not by letting participants choose &mdash; letting them choose would sort motivated or health-conscious participants into one group and hand the study a lurking variable.</p>
      <p><span class="term-label">Step 4 &mdash; Blinding.</span> Can the subjects be blinded? <b>' . $blindText . '</b>, because ' . $blindReason . '.</p>
      <p><b>Answer:</b> explanatory &ndash; ' . $expShort . '; response &ndash; ' . $respShort . '; population &ndash; ' . $popText . '; experimental units &ndash; ' . $unitsText . '; treatments &ndash; ' . $activeShort . ' (active) and ' . $inactiveShort . ' (inactive); blinding of subjects &ndash; ' . $blindText . '.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$studyDesc They recruit $n volunteers for the study. Half of the volunteers will be randomly assigned to an active treatment and half to an inactive, placebo-like treatment.</p>
    <p style="margin:12px 0 0 0;">Use the anatomy of an experiment to describe this design.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the <b>explanatory variable</b> in this study? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the <b>response variable</b> in this study? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Describe the <b>population</b> being studied. $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> What are the <b>experimental units</b>? $answerbox[3]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">e.</span> Name the <b>two treatments</b> used in this study &ndash; one active and one inactive (placebo-like). $answerbox[4]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">f.</span> Is <b>blinding</b> possible for the subjects in this study? Answer yes or no, and justify your answer in one sentence. $answerbox[5]
  </div>
</div>

// === ANSWER ===

$solutionguide
