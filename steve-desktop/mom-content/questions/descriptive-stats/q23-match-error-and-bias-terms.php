// === NAME - DESCRIPTION: Match Error and Bias Terms to Study Flaws - Match sampling error, nonsampling error, sampling bias, and confounding to the randomized incident each one names, with a voluntary-response incident and a loaded-wording incident as unused distractors ===
// === SET QUESTION TYPE TO: matching ===

// === COMMON CONTROL ===

// Six incidents, six researchers. Rotating a random offset through the name list keeps every
// incident on a different person without repeating anyone.
$names = array("Dr. Alvarez", "Dr. Boateng", "Dr. Cardoso", "Dr. Dvorak", "Dr. Farrell", "Dr. Haddad", "Dr. Nkemelu", "Dr. Sorensen")
$w0 = rand(0, 7)
$whoA = $names[mod($w0 + 0, 8)]
$whoB = $names[mod($w0 + 1, 8)]
$whoC = $names[mod($w0 + 2, 8)]
$whoD = $names[mod($w0 + 3, 8)]
$whoE = $names[mod($w0 + 4, 8)]
$whoF = $names[mod($w0 + 5, 8)]

// --- Incident A: sampling error: nothing wrong except that the sample was too small ---
// Selection is clean and the instrument is clean, so the wobble can only come from the sample size.
$popAs = array("trout in a mountain lake", "avocado trees in an orchard", "used sedans on a dealer lot", "wells in a rural water district")
$quantAs = array("mean length", "mean height", "mean mileage", "mean nitrate level")
$aI = rand(0, 3)
$popA = $popAs[$aI]
$quantA = $quantAs[$aI]
$nA = rand(6, 12)
$descA = $whoA . ' picked <b>' . $nA . '</b> ' . $popA . ' completely at random and reported the ' . $quantA . '. A second batch of <b>' . $nA . '</b>, drawn the same way, gave a noticeably different figure. The instruments were accurate and every one had the same chance of being picked: <b>' . $nA . '</b> was simply too few to pin the value down precisely.'

// --- Incident B: nonsampling error: a broken instrument, so every recorded value is wrong ---
$devBs = array("a digital scale that had drifted out of calibration", "a defective turnstile counter", "a temperature probe stuck reading high", "an automatic tally sensor with a cracked lens")
$recBs = array("every package weight", "every daily attendance figure", "every storage temperature", "every hourly vehicle count")
$dirBs = array("too heavy", "too low", "too warm", "too low")
$b0 = round(0.1 * rand(2, 8), 1)
$b1 = rand(15, 45)
$b2 = round(0.5 * rand(1, 5), 1)
$b3 = rand(8, 20)
$amtBs = array($b0 . " kg", $b1 . " visitors", $b2 . " degrees", $b3 . " vehicles")
$bI = rand(0, 3)
$descB = $whoB . ' collected the data using <b>' . $devBs[$bI] . '</b>, so ' . $recBs[$bI] . ' came out about <b>' . $amtBs[$bI] . ' ' . $dirBs[$bI] . '</b>, in the same direction every time. Who ended up in the sample was decided perfectly well; what got written down was not.'

// --- Incident C: sampling bias: the selection method itself favors part of the population ---
$descCs = array(
  "drew the sample from the list of members who had opted into text alerts, so members with no mobile number on file had almost no chance of being selected",
  "pulled names only from the daytime shift roster, so night-shift employees were far less likely to be picked than day-shift employees",
  "sampled apartments only in buildings with a working front-desk phone, so residents of the older walk-ups had a much smaller chance of being chosen",
  "selected households from the county list of property owners, so renters were far less likely to appear in the sample than owners"
)
$cI = rand(0, 3)
$descC = $whoC . ' ' . $descCs[$cI] . '. Every member of the population was supposed to have an equally likely chance of being chosen, and they did not: and surveying ten times as many would not repair it.'

// --- Incident D: confounding: two factors move together across the groups ---
$descDs = array(
  "gave the new fertilizer to the east field and the old one to the west field: but the east field also gets two more hours of sun a day, so any yield difference cannot be credited to the fertilizer rather than the sunlight",
  "ran the new training program at the branch that also received new equipment that same month, so any improvement cannot be credited to the training rather than the equipment",
  "tested the new diet on the morning class and the old one on the evening class: but the morning class also meets twice as often, so any weight change cannot be credited to the diet rather than the extra sessions",
  "installed the new streetlights on exactly the roads that were repaved that summer, so any drop in crashes cannot be credited to the lighting rather than the smoother surface"
)
$dI = rand(0, 3)
$descD = $whoD . ' ' . $descDs[$dI] . '. The two factors changed together across the groups, so their effects on the response cannot be separated.'

// --- Distractor 1: voluntary response: self-selection, which is a different named problem ---
$topicEs = array("a proposed parking fee", "a new grading policy", "a change to the bus timetable", "a proposed pet-fee increase")
$eI = rand(0, 3)
$nE = rand(3, 9) * 100
$descE = $whoF . ' posted an open link about ' . $topicEs[$eI] . ' and left it up for a month. The ' . $nE . ' people who felt strongly about it filled it in; everyone else scrolled past.'

// --- Distractor 2: undue influence: the instrument steers the answer ---
$br1s = array("Brand X", "Sunfield", "Northline", "Verado")
$br2s = array("Brand Y", "Maple Ridge", "Cedar Grove", "Altena")
$fI = rand(0, 3)
$descF = $whoE . ' asked, &ldquo;Do you prefer the <b>refreshing</b> taste of ' . $br1s[$fI] . ', or the taste of ' . $br2s[$fI] . '?&rdquo; One option was handed a flattering adjective and the other was not.'

// The four terms stay in teaching order: the two kinds of error first, then bias, then confounding.
$questions = array("Sampling error", "Nonsampling error", "Sampling bias", "Confounding")

$answers = array($descA, $descB, $descC, $descD, $descE, $descF)

// Spelled out rather than left to the default: two of the six incidents match no term, so the
// mapping has to stay pinned to the first four.
$matchlist = "0,1,2,3"

$questiontitle = "Term"
$answertitle = "Incident"
// Terms fixed in teaching order, incidents shuffled.
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
      <p>Ask two questions of each incident: <b>where did the trouble enter</b>, in choosing who to measure, in the measuring itself, or in the design, and <b>would a bigger sample fix it</b>?</p>
      <div class="term-row"><span class="term-label">Sampling error:</span> ' . $whoA . ': selection was random and the instruments were fine. Two honest samples of ' . $nA . ' still disagree, because a sample is never exactly the population. This is the one error a larger sample really does shrink.</div>
      <div class="term-row"><span class="term-label">Nonsampling error:</span> ' . $whoB . ': the trouble is not in the sampling process at all. ' . $devBs[$bI] . ' pushes ' . $recBs[$bI] . ' off in the same direction, so measuring more of them just piles up more wrong numbers.</div>
      <div class="term-row"><span class="term-label">Sampling bias:</span> ' . $whoC . ': some members of the population were less likely to be chosen than others. That is the definition, and it is why a bigger sample does not help: a biased method run at ten times the scale gives a more confident wrong answer.</div>
      <div class="term-row"><span class="term-label">Confounding:</span> ' . $whoD . ': two factors move together across the groups, so the effect of each on the response cannot be separated.</div>
      <div class="sol-warn"><b>Error versus bias.</b> Sampling error is the honest wobble you get from taking a sample instead of a census, and it shrinks as the sample grows. Sampling bias is a slant built into the selection method, and it does not shrink at all. &ldquo;Nonsampling&rdquo; is the giveaway on the second term: the sampling was fine, something else went wrong.</div>
      <div class="sol-note"><b>Two incidents match nothing.</b><br>
      &bull; ' . $whoF . ': the ' . $nE . ' respondents chose themselves. That is <b>voluntary response bias</b>: nobody was sampled at all, so the result measures intensity of feeling, not opinion in the population.<br>
      &bull; ' . $whoE . ': the flattering adjective on one option steers the answer. That is <b>undue influence</b>, built into the wording of the instrument rather than into the sample.</div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Six studies each went wrong in a different way. Match each <b>term</b> to the incident it names.</p>
    <p style="margin:12px 0 0 0;"><b>Two of the incidents are not used</b>: they are real problems, but none of these four terms is their name.</p>
  </div>
  $answerbox
</div>

// === ANSWER ===

$solutionguide
