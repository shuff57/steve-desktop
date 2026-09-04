// === NAME - DESCRIPTION: Classify a Variable as Qualitative, Discrete, or Continuous - Decide the data type of one variable recorded on each individual in a study ===
// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL ===

// Randomize the researcher and the town so the wording differs student to student.
$names = array("Dr. Alvarez", "Dr. Chen", "Dr. Okafor", "Dr. Nguyen", "Dr. Brennan", "Dr. Silva", "Dr. Whitfield", "Dr. Ramos")
$cities = array("Millbrook", "Cedar Falls", "Port Haven", "Ashford", "Glenbrook", "Fairmont", "Northfield")
$name = $names[rand(0, count($names)-1)]
$city = $cities[rand(0, count($cities)-1)]

// Variable pool spanning all three data types. $stype indexes into $questions below.
$svar = array("the number of text messages the student sent yesterday", "the time, in minutes, the customer spent waiting in line", "the brand of phone the person owns", "the zip code the shopper lives in", "the jersey number the player wears", "the age, in years, of the patient", "the number of credit hours the student is enrolled in", "the IQ score of the participant", "the weight, in kilograms, of the dog", "the number of pets living in the household", "the blood type of the donor", "the number of pairs of shoes the resident owns", "the distance, in miles, from the home to the nearest grocery store", "the make of calculator the student uses")
$sgroup = array("texting habits at a large high school", "checkout lines at a busy grocery store", "phone ownership among adults", "where an online retailer ships its orders", "a youth soccer league", "patients at a walk-in clinic", "course loads at a community college", "participants in a memory study", "dogs at an animal shelter", "pet ownership in one neighborhood", "donors at a blood drive", "households in an apartment building", "grocery access in a rural county", "calculator use in a statistics class")
$sindiv = array("student", "customer", "person", "shopper", "player", "patient", "student", "participant", "dog", "household", "donor", "resident", "home", "student")
$stype = array(1, 2, 0, 0, 0, 2, 1, 2, 2, 1, 0, 1, 2, 0)
$sreason = array("Text messages are counted, and a count lands only on whole values: nobody sends 12.4 messages.", "A waiting time is measured on a clock, and that scale can always be read more finely, so a wait of 4.7 minutes makes sense.", "A brand names a category rather than a quantity, so the values are words rather than numbers.", "A zip code is written with digits, but it labels an area rather than measuring or counting anything: an average zip code would be meaningless.", "A jersey number is a label written with digits, not a quantity: adding two jersey numbers gives nothing meaningful.", "Age is measured rather than counted; reporting it rounded to whole years does not change the fact that age runs continuously.", "Credit hours are counted in whole units, so the values are the result of counting.", "An IQ score comes from a standardized test scored on a continuous scale, so it is a measurement rather than a count.", "A weight comes from a scale, and a scale can always be read more finely, so values such as 12.4 kilograms are possible.", "Pets are counted, and a count lands only on whole values: a household cannot own 2.5 pets.", "A blood type such as AB+ or O&minus; names a category, described by letters rather than numbers.", "Pairs of shoes are counted, so the values are whole numbers with gaps in between.", "A distance is measured, and a measurement may include fractions or decimals.", "A make of calculator names a category, so the values are words rather than numbers.")

$vi = rand(0, count($svar)-1)
$var = $svar[$vi]
$group = $sgroup[$vi]
$indiv = $sindiv[$vi]

// The three data types. The two the student did not pick are the near-miss options.
$questions = array("Qualitative (categorical)", "Quantitative discrete", "Quantitative continuous")
$answer = $stype[$vi]

// Precompute scalars: question text and solution cannot index arrays.
$reason = $sreason[$vi]
$anslabel = $questions[$answer]
$w1 = ($answer + 1) % 3
$w2 = ($answer + 2) % 3
$w1label = $questions[$w1]
$w2label = $questions[$w2]
$typewhy = array("the values would have to name a category or attribute, described by words or letters rather than numbers", "the values would have to come from counting, landing only on certain whole values with gaps in between", "the values would have to come from measuring on a scale that can always be read more finely")
$w1why = $typewhy[$w1]
$w2why = $typewhy[$w2]

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
      <p><b>Step 1: Is it a number or a category?</b> Qualitative (categorical) data name or describe an attribute. Quantitative data are numbers that come from counting or measuring. Watch out: a value written with digits is only quantitative if the number measures or counts something.</p>
      <p><b>Step 2: If it is a number, was it counted or measured?</b> Counting gives whole things, so it is discrete. Measuring gives a reading on a scale that can always be read more finely, so it is continuous. "How many?" is discrete; "how much?" is continuous.</p>
      <p><b>Step 3: Apply it here.</b> The variable recorded on each ' . $indiv . ' is ' . $var . '. ' . $reason . '</p>
      <div class="term-row"><span class="term-label">Answer:</span> ' . $anslabel . '</div>
      <p style="margin-top:1em;"><b>Why the others are wrong:</b></p>
      <div class="term-row"><span class="term-label">' . $w1label . ':</span> for that, ' . $w1why . '.</div>
      <div class="term-row"><span class="term-label">' . $w2label . ':</span> for that, ' . $w2why . '.</div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;"><b>$name</b>, a researcher in <b>$city</b>, is running a study of <b>$group</b>.</p>
    <p style="margin:12px 0 0 0;">For each <b>$indiv</b> in the study, $name records <b>$var</b>.</p>
    <p style="margin:12px 0 0 0;">How should this variable be classified?</p>
  </div>
  $answerbox
</div>

// === ANSWER ===

$solutionguide
