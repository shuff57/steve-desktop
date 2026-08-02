// === NAME - DESCRIPTION: Sampling With or Without Replacement - Decide from a described draw whether members were returned to the population, could be chosen only once, or whether the description does not say ===

// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL ===

// Which draw this version describes. $cats maps each procedure to the option index it justifies.
$pi = rand(0, 5)
$cats = array(1, 0, 1, 0, 2, 2)
$cat = $cats[$pi]

$names = array("Ms. Okonkwo", "Mr. Delgado", "Dr. Halvorsen", "Ms. Petrova", "Mr. Abara", "Dr. Lindqvist", "Ms. Whitmore", "Mr. Castellanos")
$who = $names[rand(0, count($names)-1)]

// Six populations, flat parallel arrays so nothing needs nested indexing.
$si = rand(0, 5)
$orgpre = array("Riverside", "Lakeshore", "Pine Valley", "Grand Mesa", "Harbor Point", "Cedar Ridge")
$orgpart = $orgpre[rand(0, count($orgpre)-1)]
$orgtypes = array("Community College", "Regional Hospital", "Public Library", "Transit Authority", "Youth Soccer League", "Food Co-op")
$unitsings = array("student", "nurse", "cardholder", "bus driver", "player", "member")
$unitplurs = array("students", "nurses", "cardholders", "bus drivers", "players", "members")
$belongs = array("enrolled at", "on staff at", "registered with", "employed by", "signed up with", "belonging to")
$topics = array("the mean number of hours per week they work for pay", "the mean number of night shifts they take in a month", "the mean number of books they borrow in a year", "the mean length of their daily route in miles", "the mean number of practices they attend in a season", "the mean amount they spend per shopping trip")

$org = $orgpart . " " . $orgtypes[$si]
$unit = $unitsings[$si]
$unitp = $unitplurs[$si]
$belong = $belongs[$si]
$topic = $topics[$si]

// Population size and how many are drawn.
$Nlist = array(240, 300, 350, 420, 500, 600)
$N = $Nlist[rand(0, count($Nlist)-1)]
$n = 5 * rand(2, 8)

$intro = '<b>' . $who . '</b> is studying the <b>' . $N . '</b> ' . $unitp . ' ' . $belong . ' <b>' . $org . '</b>, and wants to estimate ' . $topic . '. Every ' . $unit . ' has an ID number from 1 to ' . $N . '. A sample of <b>' . $n . '</b> is drawn as follows.'

if ($pi == 0) {
  $mechanics = 'Each of the ' . $N . ' ID numbers is written on a slip of paper and all ' . $N . ' slips go into a box. The box is shaken, one slip is drawn and the ID recorded, and the drawn slip is <b>set aside</b> rather than returned. This repeats until ' . $n . ' slips have been drawn.'
  $signature = 'each drawn slip is <b>set aside</b>, so no ID can come up a second time'
}
if ($pi == 1) {
  $mechanics = 'Each of the ' . $N . ' ID numbers is written on a slip of paper and all ' . $N . ' slips go into a box. One slip is drawn and the ID recorded, then that slip is <b>put back in the box</b> and the box is reshaken before the next draw. This repeats until ' . $n . ' IDs have been recorded.'
  $signature = 'the drawn slip is <b>put back in the box</b> before the next draw, so the same ID can be recorded more than once'
}
if ($pi == 2) {
  $mechanics = 'A random number generator is set to produce whole numbers from 1 to ' . $N . ' with the <b>&quot;no repeats&quot;</b> box checked, and ' . $n . ' numbers are generated at once. The ' . $unitp . ' holding those IDs are surveyed.'
  $signature = 'the <b>&quot;no repeats&quot;</b> setting forces ' . $n . ' different IDs, so no ' . $unit . ' can be picked twice'
}
if ($pi == 3) {
  $mechanics = 'A random number generator produces ' . $n . ' whole numbers from 1 to ' . $N . '. The generator <b>can return the same number more than once</b>, and when it does, that ' . $unit . ' is recorded again.'
  $signature = 'the generator <b>can return the same number more than once</b>, and a repeat is recorded again'
}
if ($pi == 4) {
  $mechanics = 'A computer picks ' . $n . ' of the ' . $N . ' ID numbers at random and the ' . $unitp . ' holding them are surveyed. The write-up does not say whether an ID could come up more than once.'
  $signature = 'nothing in the write-up says whether an ID could come up more than once'
}
if ($pi == 5) {
  $mechanics = 'A spreadsheet returns ' . $n . ' ID numbers drawn at random from the ' . $N . ' on the roster. Nothing is recorded about what happens to an ID once it has been selected.'
  $signature = 'nothing is recorded about what happens to an ID once it has been selected'
}

$questions = array("Sampling with replacement", "Sampling without replacement", "Cannot be determined from the description")
$answer = $cat
$correct = $questions[$cat]
$noshuffle = "last"

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
  .sol-warn { margin-top:1em; padding:0.6em 0.75em; border-left:4px solid #f59e0b; background:#fffbeb; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><b>Step 1 &mdash; Ask one question: after a member is chosen, can it be chosen again?</b> That single fact settles it. The size of the sample and the topic of the study have nothing to do with it.</p>
      <div class="term-row"><span class="term-label">Sampling with replacement:</span> a chosen member is returned to the population, so it may be picked again &mdash; the same ' . $unit . ' could appear twice in the sample.</div>
      <div class="term-row"><span class="term-label">Sampling without replacement:</span> a member of the population may be chosen only once.</div>
      <p><b>Step 2 &mdash; The deciding detail here:</b> ' . $signature . '.</p>
      <div class="term-row"><span class="term-label">Answer:</span> ' . $correct . '</div>
      <div class="sol-warn"><b>How much does it matter?</b> Here ' . $n . ' ' . $unitp . ' are drawn from ' . $N . ', so the chance of drawing the same ' . $unit . ' twice is small either way and the two methods give nearly the same results. In practice most surveys are run without replacement. The distinction becomes a real mathematical issue only when the population is small &mdash; drawing 5 names from 12 is a very different thing depending on whether the names go back in.</div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$intro</p>
    <p style="margin:12px 0 0 0;">$mechanics</p>
    <p style="margin:12px 0 0 0;"><b>Is this sampling with replacement or without replacement?</b></p>
  </div>
  $answerbox
</div>

// === ANSWER ===

$solutionguide
