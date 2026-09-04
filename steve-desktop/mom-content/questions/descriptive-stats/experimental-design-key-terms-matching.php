// === NAME - DESCRIPTION: Experimental Design Key Terms - Match explanatory variable, response variable, treatment, experimental unit, and lurking variable to their definitions, targeting the confusion between a treatment and the explanatory variable itself ===
// === SET QUESTION TYPE TO: matching ===

// === COMMON CONTROL ===

// Four experiment scenarios, picked by one index so every term and distractor below describes
// the SAME study.
$si = rand(0, 3)

$intros = array(
  "A garden researcher wants to know whether the brand of fertilizer used on tomato plants affects how tall the plants grow.",
  "A school wants to know whether the format of homework help given to students affects their scores on the unit quiz.",
  "A phone manufacturer wants to know whether the type of charging cable used affects how long the battery lasts.",
  "A researcher wants to know whether the amount of caffeine a person consumes before a task affects their reaction time."
)

$explanatoryDefs = array(
  "the type of fertilizer applied to a plant: the variable the researcher deliberately sets",
  "the homework-help format a student is given: the variable the researcher deliberately sets",
  "the type of charging cable used: the variable the researcher deliberately sets",
  "the amount of caffeine a participant consumes: the variable the researcher deliberately sets"
)

$responseDefs = array(
  "the plant's height after eight weeks: what actually gets measured",
  "the student's score on the unit quiz: what actually gets measured",
  "the number of hours the phone runs before the battery dies: what actually gets measured",
  "the participant's reaction time on the task: what actually gets measured"
)

// Deliberately worded as ONE specific value, not the variable, since that is the distinction
// students most often blur with the explanatory variable above.
$treatmentDefs = array(
  "one specific fertilizer brand, such as Brand A, assigned to a group of plants",
  "one specific homework-help format, such as the video tutorials, assigned to a group of students",
  "one specific cable type, such as the original manufacturer cable, assigned to a group of phones",
  "one specific caffeine dose, such as 200 mg, assigned to a group of participants"
)

$unitDefs = array(
  "a single tomato plant, measured once in the study",
  "a single student, measured once in the study",
  "a single phone, measured once in the study",
  "a single participant, measured once in the study"
)

$lurkingDefs = array(
  "how much sunlight each plant happens to get, left unaccounted for and possibly different between the groups",
  "how many hours of sleep each student got the night before, left unaccounted for and possibly different between the groups",
  "how old each phone's battery already was, left unaccounted for and possibly different between the groups",
  "how much sleep each participant got the night before, left unaccounted for and possibly different between the groups"
)

// Distractor 1 (confusable with Treatment): a no-treatment baseline group is not itself a treatment.
$controlDistractors = array(
  "a group of plants set aside to receive no fertilizer at all, used only as a comparison baseline",
  "a group of students set aside to receive no homework help at all, used only as a comparison baseline",
  "a group of phones set aside to use no cable at all, used only as a comparison baseline",
  "a group of participants set aside to receive no caffeine at all, used only as a comparison baseline"
)

// Distractor 2 (confusable with Experimental unit): the whole population, not one measured individual.
$populationDistractors = array(
  "every tomato plant the garden center has ever grown, not just the ones in this study",
  "every student who has ever attended the school, not just the ones in this study",
  "every phone of that model ever sold, not just the ones in this study",
  "every adult who drinks coffee, not just the ones in this study"
)

$introTxt = $intros[$si]
$explanatoryDef = $explanatoryDefs[$si]
$responseDef = $responseDefs[$si]
$treatmentDef = $treatmentDefs[$si]
$unitDef = $unitDefs[$si]
$lurkingDef = $lurkingDefs[$si]
$controlDistractor = $controlDistractors[$si]
$populationDistractor = $populationDistractors[$si]

// The five terms stay in teaching order: the two variables first, then the treatment they take
// their values from, then who receives it, then what could quietly ruin the comparison.
$questions = array("Explanatory variable", "Response variable", "Treatment", "Experimental unit", "Lurking variable")

// One description per term, in the same order, then two distractors so the last term is not
// free by elimination.
$answers = array($explanatoryDef, $responseDef, $treatmentDef, $unitDef, $lurkingDef, $controlDistractor, $populationDistractor)

$matchlist = "0,1,2,3,4"

$questiontitle = "Key term"
$answertitle = "Description"
// Keep the terms in order and shuffle only the descriptions: the point is recognising which
// description fits, not remembering where it sat last time.
$noshuffle = "questions"

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
  .term-row { margin:0.6em 0; }
  .term-label { font-weight:700; color:#1865f2; }
  .sol-note { margin-top:0.9em; padding:0.6em 0.75em; background:#e8f0fe; border-radius:8px; font-size:15px; }
  .sol-warn { margin-top:0.9em; padding:0.6em 0.75em; border-left:4px solid #f59e0b; background:#fffbeb; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p>' . $introTxt . '</p>
      <div class="term-row"><span class="term-label">Explanatory variable:</span> ' . $explanatoryDef . '.</div>
      <div class="term-row"><span class="term-label">Response variable:</span> ' . $responseDef . '.</div>
      <div class="term-row"><span class="term-label">Treatment:</span> ' . $treatmentDef . '.</div>
      <div class="term-row"><span class="term-label">Experimental unit:</span> ' . $unitDef . '.</div>
      <div class="term-row"><span class="term-label">Lurking variable:</span> ' . $lurkingDef . '.</div>
      <div class="sol-warn"><b>Treatment versus explanatory variable.</b> The explanatory variable is the general thing being varied. A treatment is just one of its specific values, handed to one group. &ldquo;Which variable did the researcher manipulate&rdquo; and &ldquo;which exact value did this group get&rdquo; are two different questions.</div>
      <div class="sol-note">Two of the descriptions are not used. <b>The no-treatment baseline group</b> is a control group, not a treatment: it is defined by receiving none of the explanatory variable. And <b>the full population</b> is far wider than a single experimental unit, which is only ever one measured individual.</div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$introTxt</p>
    <p style="margin:12px 0 0 0;">Match each key term to the description that fits this experiment. <b>Two descriptions are not used.</b></p>
  </div>
  $answerbox
</div>

// === ANSWER ===

$solutionguide
