// === NAME - DESCRIPTION: Randomized Block Design Application - With one lurking variable known in advance, identify the blocking variable, recognize that random assignment happens within each block, and explain how the two together control known and unknown lurking variables ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("string", "choices", "string")

$si = rand(0, 1)

if ($si == 0) {
  $unit = "students"
  $unitSingular = "student"
  $responseVar = "quiz score at the end of the unit"
  $techAOptions = array("a spaced-repetition review app", "a structured practice-test routine", "a guided peer-tutoring format", "a daily low-stakes quiz habit")
  $techBOptions = array("their usual, unstructured studying", "a single end-of-week review session", "silent independent reading of the textbook")
  $techA = $techAOptions[rand(0, 3)]
  $techB = $techBOptions[rand(0, 2)]
  $lurkingVar = "prior GPA"
  $groupHigh = "students whose GPA before the unit was 3.0 or higher"
  $groupLow = "students whose GPA before the unit was below 3.0"
  $context = "prior GPA is already known to predict quiz performance on its own, no matter which study technique a student ends up using"
  $n = rand(48, 84)
  $pctHigh = rand(25, 40)
}
else {
  $unit = "field plots"
  $unitSingular = "plot"
  $responseVar = "crop yield, in bushels per acre"
  $techAOptions = array("a new slow-release fertilizer blend", "an experimental nitrogen-rich fertilizer", "a new organic compost fertilizer")
  $techBOptions = array("the standard fertilizer currently in use", "the farm's usual synthetic fertilizer", "no fertilizer at all")
  $techA = $techAOptions[rand(0, 2)]
  $techB = $techBOptions[rand(0, 2)]
  $lurkingVar = "soil quality"
  $groupHigh = "plots with naturally fertile soil"
  $groupLow = "plots with naturally poor soil"
  $context = "soil quality is already known to affect yield on its own, no matter which fertilizer is applied"
  $n = rand(24, 60)
  $pctHigh = rand(25, 40)
}

$nHigh = round($n * $pctHigh / 100)
$nLow = $n - $nHigh

if ($si == 0) {
  $blockAnswer = "prior GPA or GPA band or students' prior GPA or GPA"
}
else {
  $blockAnswer = "soil quality or soil fertility or quality of the soil"
}
$strflags[0] = "ignore_case,trim_whitespace"

$choices[1] = array(
  "Randomly assign about half of the " . $unit . " within each block to " . $techA . " and half to " . $techB . ", doing this separately in the " . $groupHigh . " block and again in the " . $groupLow . " block.",
  "Randomly assign treatments across the whole sample of " . $n . " " . $unit . " at once, without paying attention to which block each " . $unitSingular . " is in.",
  "Randomly choose which treatment each entire block receives, so every " . $unitSingular . " in the same block gets the same treatment.",
  "Skip random assignment once the blocks are formed &ndash; the researcher can assign treatments within each block using her own judgment."
)
$answer[1] = 0

if ($si == 0) {
  $whyAnswer = "blocking guarantees both treatment groups contain the same mix of prior GPA, so that known difference can't be mistaken for a study-technique effect, while random assignment within each block still spreads any other lurking variables, such as motivation and time available to study, evenly across the two techniques or sorting students into GPA blocks first controls for GPA by design, and randomizing within each block takes care of every other lurking variable by chance, so both the known and unknown ones are handled"
}
else {
  $whyAnswer = "blocking guarantees both treatment groups contain the same mix of soil quality, so that known difference can't be mistaken for a fertilizer effect, while random assignment within each block still spreads any other lurking variables, such as drainage and sun exposure, evenly across the two fertilizers or sorting plots into soil-quality blocks first controls for soil quality by design, and randomizing within each block takes care of every other lurking variable by chance, so both the known and unknown ones are handled"
}
$strflags[2] = "ignore_case,trim_whitespace"

$answer[0] = $blockAnswer
$answer[2] = $whyAnswer

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
      <p><span class="term-label">Step 1 &mdash; Name the blocking variable.</span> The researcher already knows ' . $context . '. That known, measurable variable &ndash; ' . $lurkingVar . ' &ndash; is exactly what should be used to form the blocks, before any treatment is assigned.</p>
      <p><span class="term-label">Step 2 &mdash; Place the random assignment correctly.</span> Sort the ' . $unit . ' into the ' . $groupHigh . ' block and the ' . $groupLow . ' block first. Then, separately within each block, randomly assign about half to ' . $techA . ' and half to ' . $techB . '. Both treatment groups now hold the same mix of ' . $lurkingVar . ' by construction, instead of by luck.</p>
      <p><span class="term-label">Step 3 &mdash; Say why blocking plus random assignment together cover everything.</span> ' . $whyAnswer . '.</p>
      <p><b>Answer:</b> (a) block on ' . $lurkingVar . '. (b) randomly assign treatments separately within each block. (c) ' . $whyAnswer . '.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A researcher wants to know whether $techA raises $responseVar compared to $techB. There are $n $unit available for the study. $nHigh of them are $groupHigh, and the remaining $nLow are $groupLow. That matters because $context, so the researcher wants to control for it directly rather than leave it to chance.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Before randomly assigning treatments, the researcher wants to sort the $n $unit into blocks. Which variable should she use to form the blocks? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Once the $unit are sorted into blocks based on that variable, where should random assignment to $techA versus $techB happen? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Explain why using blocking together with random assignment, as described in part (b), controls for both $lurkingVar and any other lurking variables the researcher hasn't thought of. $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
