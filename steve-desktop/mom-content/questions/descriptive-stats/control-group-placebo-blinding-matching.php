// === NAME - DESCRIPTION: Control Group, Placebo, and Blinding Key Terms - Match control group, placebo, blinding, and double-blind to their definitions in a randomized drug trial, separating what is given from what is hidden and from who is kept unaware ===
// === SET QUESTION TYPE TO: matching ===

// === COMMON CONTROL ===

// Four trial scenarios, picked by one index so every term and distractor below describes the
// SAME study.
$si = rand(0, 3)

$intros = array(
  "A cardiologist wants to test whether a new cholesterol drug lowers the chance of a heart attack. Volunteers are randomly split into two groups: one group takes the new drug, and the other takes a sugar pill that looks identical to it. Neither the volunteers nor the nurses recording heart attacks are told which group any volunteer is in until the study ends.",
  "A sleep researcher wants to test whether an herbal supplement helps people fall asleep faster. Volunteers are randomly split into two groups: one group takes the supplement, and the other takes an identical-looking capsule with no active ingredient. Neither the volunteers nor the technicians timing how long it takes them to fall asleep are told which group any volunteer is in until the study ends.",
  "A dermatologist wants to test whether a new acne cream reduces breakouts. Volunteers are randomly split into two groups: one group uses the new cream, and the other uses a plain lotion with no medication, packaged in an identical tube. Neither the volunteers nor the dermatologist scoring their skin are told which group any volunteer is in until the study ends.",
  "A physical therapist wants to test whether a new knee injection reduces pain. Volunteers are randomly split into two groups: one group receives the new injection, and the other receives a saline injection that looks identical. Neither the volunteers nor the therapist recording pain scores are told which group any volunteer is in until the study ends."
)

$actives = array(
  "the new cholesterol drug",
  "the herbal sleep supplement",
  "the new acne cream",
  "the new knee injection"
)

$inactives = array(
  "the sugar pill",
  "the inactive capsule",
  "the plain lotion",
  "the saline injection"
)

$introTxt = $intros[$si]
$active = $actives[$si]
$inactive = $inactives[$si]

$controlGroupDef = "the volunteers assigned to receive " . $inactive . " instead of " . $active . ", used only as a baseline for comparison"

$placeboDef = $inactive . " itself &mdash; made to look and feel exactly like " . $active . " even though it has no active ingredient"

$blindingDef = "keeping each volunteer from knowing whether they received " . $active . " or " . $inactive

$doubleBlindDef = "a design in which neither the volunteers nor the staff working directly with them know who received " . $active . " and who received " . $inactive . ", until the study ends"

// Distractor 1 (confusable with blinding/double-blind): describes HOW subjects were sorted into
// groups, not who is kept unaware of the sorting.
$randomAssignDistractor = "sorting volunteers into the two groups purely by chance, so that lurking variables such as age and health habits are spread evenly between the groups"

// Distractor 2 (confusable with control group, since both happen before/around treatment):
// a paperwork requirement, not a feature of the treatment design itself.
$informedConsentDistractor = "each volunteer's written agreement to take part, given only after the risks of the study were clearly explained to them"

// The four terms stay in teaching order: the group defined first, then what it is given, then
// who is kept from knowing, then how far that not-knowing extends.
$questions = array("Control group", "Placebo", "Blinding", "Double-blind experiment")

// One description per term, in the same order, then two distractors so the last term is not free
// by elimination.
$answers = array($controlGroupDef, $placeboDef, $blindingDef, $doubleBlindDef, $randomAssignDistractor, $informedConsentDistractor)

$matchlist = "0,1,2,3"

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
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p>' . $introTxt . '</p>
      <div class="term-row"><span class="term-label">Control group:</span> ' . $controlGroupDef . '.</div>
      <div class="term-row"><span class="term-label">Placebo:</span> ' . $placeboDef . '.</div>
      <div class="term-row"><span class="term-label">Blinding:</span> ' . $blindingDef . '.</div>
      <div class="term-row"><span class="term-label">Double-blind experiment:</span> ' . $doubleBlindDef . '.</div>
      <div class="sol-note"><b>What is given, what is hidden, and who is kept unaware.</b> The placebo is the object handed to the control group &mdash; a thing. Blinding is the act of hiding group membership from one side of the study &mdash; an action. A double-blind experiment is not a stronger placebo; it is blinding extended to <em>both</em> the volunteers <em>and</em> the staff who work with them, so neither side can let their expectations leak into the results.</div>
      <div class="sol-note">Two of the descriptions are not used. <b>Random assignment</b> describes how volunteers were sorted into the two groups &mdash; by chance &mdash; which is a different safeguard from blinding, which describes who is kept from knowing the result of that sort. And <b>informed consent</b> is a paperwork requirement about explaining risk before the study starts; it says nothing about placebos or who is blinded once the study is underway.</div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$introTxt</p>
    <p style="margin:12px 0 0 0;">Match each key term to the description that fits this study. <b>Two descriptions are not used.</b></p>
  </div>
  $answerbox
</div>

// === ANSWER ===

$solutionguide
