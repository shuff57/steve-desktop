// === NAME - DESCRIPTION: Associated vs. Independent Variables and the Causation Trap - Given five randomized variable pairs, select all that show association rather than independence, then judge whether one of the associated pairs proves that the first variable causes the second, versus a lurking variable or coincidence explaining it instead ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("multans", "choices")

$assocPairText = array(
  "monthly ice cream sales and the number of drowning incidents that month",
  "the number of firefighters sent to a house fire and the dollar amount of damage the fire causes",
  "the shoe size of a child and the child's score on a reading test",
  "the number of storks nesting in a country and that country's birth rate",
  "a country's per-capita chocolate consumption and the number of Nobel Prize laureates from that country"
)

$assocWhy = array(
  "both rise and fall with the season &mdash; hot weather sends more people to buy ice cream, and more people into the water to swim, which is where drownings happen",
  "both rise and fall with the size of the fire &mdash; a bigger fire gets more firefighters dispatched to it and does more damage, whether or not the firefighters do anything wrong",
  "both rise and fall with the child&#8217;s age &mdash; older children have bigger feet and have also had more time to develop reading skills",
  "both rise and fall with how rural a country is &mdash; rural areas have more nesting habitat for storks and, separately, tend to have higher birth rates",
  "both rise and fall with national wealth &mdash; wealthier countries can afford more chocolate imports and also fund more research, producing more laureates"
)

$indepPairText = array(
  "a student's shoe size and the student's favorite music genre",
  "a person's birth month and their favorite pizza topping",
  "the last digit of a student's phone number and the student's GPA",
  "the color of a person's car and the number of siblings they have",
  "a person's favorite ice cream flavor and their blood type"
)

$indepWhy = array(
  "foot size gives no information at all about musical taste",
  "birth month gives no information at all about pizza-topping preference",
  "a phone number&#8217;s last digit is essentially random and unrelated to academic performance",
  "car color is a personal or dealership choice with no connection to family size",
  "flavor preference is a matter of taste with no connection to a biological trait like blood type"
)

$aIdx = diffrands(0, 4, 3)
$iIdx = diffrands(0, 4, 2)

$q0 = $assocPairText[$aIdx[0]]
$q1 = $assocPairText[$aIdx[1]]
$q2 = $assocPairText[$aIdx[2]]
$q3 = $indepPairText[$iIdx[0]]
$q4 = $indepPairText[$iIdx[1]]

$why0 = $assocWhy[$aIdx[0]]
$why1 = $assocWhy[$aIdx[1]]
$why2 = $assocWhy[$aIdx[2]]
$whyI0 = $indepWhy[$iIdx[0]]
$whyI1 = $indepWhy[$iIdx[1]]

$questions[0] = array($q0, $q1, $q2, $q3, $q4)
$answers[0] = "0,1,2"
$scoremethod[0] = "allornothing"

$bSlot = rand(0, 2)
$bPair = $q0
$bWhy = $why0
if ($bSlot == 1) {
  $bPair = $q1
  $bWhy = $why1
}
if ($bSlot == 2) {
  $bPair = $q2
  $bWhy = $why2
}

$questions[1] = array(
  "No &mdash; an association alone does not prove causation. A lurking variable, or plain coincidence, could produce this pattern without either variable causing the other.",
  "Yes &mdash; whenever two variables are clearly associated, the one that changes first must be causing the change in the other.",
  "Yes, because the association is strong and consistent, so a cause-and-effect explanation is the only one that fits.",
  "No, because the two variables are not actually associated in the first place."
)
$answer[1] = 0

$answeights = array(.6, .4)

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
      <p><span class="term-label">Step 1 &mdash; What association means.</span> Two variables are associated if knowing the value of one tells you anything &mdash; even something weak or coincidental &mdash; about the other. They are independent only if knowing one tells you nothing at all about the other.</p>
      <p><span class="term-label">Step 2 &mdash; Check each pair.</span></p>
      <p>&#8226; ' . $q0 . ' are <b>associated</b>: ' . $why0 . '.</p>
      <p>&#8226; ' . $q1 . ' are <b>associated</b>: ' . $why1 . '.</p>
      <p>&#8226; ' . $q2 . ' are <b>associated</b>: ' . $why2 . '.</p>
      <p>&#8226; ' . $q3 . ' are <b>independent</b>: ' . $whyI0 . '.</p>
      <p>&#8226; ' . $q4 . ' are <b>independent</b>: ' . $whyI1 . '.</p>
      <p><span class="term-label">Step 3 &mdash; Part (a).</span> The three confounded pairs above are the associated ones; the remaining two pairs are independent.</p>
      <p><span class="term-label">Step 4 &mdash; Part (b): does it prove causation?</span> ' . $bPair . ' are associated, but that does not prove the first variable causes the second. Here, ' . $bWhy . '. That lurking variable, not a direct cause-and-effect link, can fully explain the pattern &mdash; which is exactly why "linked to" is not "causes."</p>
      <p><b>Answers:</b> (a) the three confounded pairs are associated, the other two are independent; (b) No &mdash; a lurking variable or coincidence may explain the association instead of causation.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A blog writer is hunting for &ldquo;shocking statistical connections&rdquo; between pairs of variables. Below are five pairs the writer is considering. Recall that two variables are <b>associated</b> if knowing the value of one tells you something about the likely value of the other; they are <b>independent</b> if knowing one tells you nothing about the other.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> <b>Select all of the pairs below that show association</b> (leave unselected any pair that is independent).
    $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The writer notices that $bPair are associated, and wants to publish the headline: &ldquo;The first causes the second!&rdquo; <b>Does this association prove that the first variable causes the second?</b>
    $answerbox[1]
  </div>
</div>

// === ANSWER ===

$solutionguide
