// === NAME - DESCRIPTION: Select All Quantitative Discrete Variables - Apply the count-versus-measure rule across a list of recorded variables and check every one that is quantitative discrete ===
// === SET QUESTION TYPE TO: multans ===

// === COMMON CONTROL ===

// Randomize who is collecting the data and where, so the wording differs student to student.
$names = array("Dr. Alvarez", "Dr. Chen", "Dr. Okafor", "Dr. Nguyen", "Dr. Brennan", "Dr. Silva", "Dr. Whitfield", "Dr. Ramos")
$cities = array("Millbrook", "Cedar Falls", "Port Haven", "Ashford", "Glenbrook", "Fairmont", "Northfield")
$name = $names[rand(0, count($names)-1)]
$city = $cities[rand(0, count($cities)-1)]

// Correct pool: every item is a count, so every item is quantitative discrete.
$cor = array("the number of pairs of shoes a resident owns", "the number of classes a student takes per year", "the number of correct answers a student gets on the quiz", "the number of tickets sold for the show", "the number of students enrolled in the course")

// Wrong pool A: qualitative: the value names a category instead of counting or measuring.
$qual = array("the favorite baseball team of a fan", "the brand of toothpaste a shopper buys", "the hair color of a participant", "the type of car a commuter drives")
$qualwhy = array("names a team, so the value is a word rather than a number: qualitative, not quantitative", "names a brand, so the value is a category rather than a quantity", "names a color, so the value describes an attribute rather than counting anything", "names a kind of vehicle, so the value is a word rather than a number")

// Wrong pool B: continuous quantities that look countable because they get reported as whole numbers.
$meas = array("the age, in whole years, of an executive", "the IQ score of a participant", "the percent body fat of an athlete", "the distance, in miles, to the nearest theatre", "the time, in minutes, a customer waits in line")
$measwhy = array("age is read off a calendar and runs continuously: an executive is 34 years and 7 months old even when the sheet records 34, so the whole number is rounding, not a restriction", "an IQ score comes from a test scored on a continuous scale, so it is a measurement reported as a whole number rather than a count", "percent body fat is read off a measuring instrument and can be 18.4 percent, so it is continuous", "a distance is measured, and the measuring scale can always be read more finely, so it is continuous", "a time is read off a clock, and the clock can always be read more finely, so it is continuous")

// Draw 3 correct items. Steps of 1 or 2 are coprime to 5, so the three indices are always distinct.
$c0 = rand(0, 4)
$cstep = rand(1, 2)
$ca = $c0
$cb = ($c0 + $cstep) % 5
$cc = ($c0 + 2*$cstep) % 5

// Draw 2 qualitative and 2 continuous distractors, so the wrong four always mix both traps.
$q0 = rand(0, 3)
$q1 = ($q0 + rand(1, 3)) % 4
$m0 = rand(0, 4)
$m1 = ($m0 + rand(1, 4)) % 5

// Correct items first so the answer key is fixed; MOM shuffles the display order for the student.
$questions = array($cor[$ca], $cor[$cb], $cor[$cc], $qual[$q0], $qual[$q1], $meas[$m0], $meas[$m1])
$answers = "0,1,2"

// Precompute scalars: question text and solution cannot index arrays.
$cortext1 = $cor[$ca]
$cortext2 = $cor[$cb]
$cortext3 = $cor[$cc]
$wrong1 = $qual[$q0] . ': ' . $qualwhy[$q0] . '.'
$wrong2 = $qual[$q1] . ': ' . $qualwhy[$q1] . '.'
$wrong3 = $meas[$m0] . ': ' . $measwhy[$m0] . '.'
$wrong4 = $meas[$m1] . ': ' . $measwhy[$m1] . '.'

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
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><b>The rule:</b> quantitative discrete data are the result of <b>counting</b>, so they land only on certain whole values with gaps in between. The phrase <b>"the number of"</b> is the signal that something was counted. Anything read off a <b>scale, a clock, or a measuring instrument</b> is continuous: even when it is rounded to a whole number. And if the value is a word rather than a quantity, it is qualitative, not quantitative at all.</p>
      <p><b>Step 1: Number or category?</b> Throw out anything whose value is a name or a label. Those are qualitative.</p>
      <p><b>Step 2: Counted or measured?</b> Ask "how many?" versus "how much?" A count cannot land between whole values; a measurement can always be read more finely.</p>
      <div class="term-row"><span class="term-label">Quantitative discrete (check these):</span></div>
      <div class="term-row">&bull; ' . $cortext1 . '</div>
      <div class="term-row">&bull; ' . $cortext2 . '</div>
      <div class="term-row">&bull; ' . $cortext3 . '</div>
      <p style="margin-top:1em;"><b>Why the others are not:</b></p>
      <div class="term-row">&bull; ' . $wrong1 . '</div>
      <div class="term-row">&bull; ' . $wrong2 . '</div>
      <div class="term-row">&bull; ' . $wrong3 . '</div>
      <div class="term-row">&bull; ' . $wrong4 . '</div>
      <p style="margin-top:1em;"><b>Watch the trap:</b> a value written as a whole number is not automatically a count. Age, IQ, percent body fat, distance and time all get reported as whole numbers, and all of them come from a scale, so all of them are continuous.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;"><b>$name</b> is assembling a data sheet in <b>$city</b>. Each variable below was recorded on a separate study.</p>
    <p style="margin:12px 0 0 0;"><b>Select every variable that is quantitative discrete data</b>: data that come from counting and can take only certain whole-number values.</p>
  </div>
  $answerbox
</div>

// === ANSWER ===

$solutionguide
