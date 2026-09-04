// === NAME - DESCRIPTION: Match Sampling Methods to Studies - Match simple random, stratified, cluster, systematic, and convenience sampling to the study description whose draw mechanics fit, with a census and a two-stage design as unused distractors ===
// === SET QUESTION TYPE TO: matching ===

// === COMMON CONTROL ===

// Seven separate studies get built below, so every surface detail rotates: researcher,
// institution, population unit, group names, sample sizes, and the step k.
$names = array("Dr. Bahl", "Dr. Costa", "Dr. Iverson", "Dr. Mbeki", "Dr. Petrov", "Dr. Reyes", "Dr. Tanaka", "Dr. Ustinov")
$prefixes = array("Millbrook", "Silver Creek", "Oakhaven", "Westbrook", "Ironton", "Blue Harbor", "Kestrel", "Marion")

$w0 = rand(0, 7)
$p0 = rand(0, 7)

// Six settings, each a flat parallel array. Rotating by a random offset gives each of the
// seven descriptions a different institution and population.
$orgtypes = array("Community College", "Regional Medical Center", "Water Authority", "Freight Company", "Public Library System", "Sports Club")
$unitsings = array("student", "nurse", "household", "driver", "cardholder", "member")
$unitplurs = array("students", "nurses", "households", "drivers", "cardholders", "members")
$belongs = array("enrolled at", "employed by", "served by", "working for", "registered with", "who belong to")
$groupplurs = array("academic divisions", "care units", "service zones", "depots", "branch libraries", "club locations")
$orderbys = array("student ID number", "badge number", "account number", "hire date", "card number", "membership number")
$convplaces = array("outside the campus bookstore", "in the staff break room", "in the lobby of the billing office", "at the fuel island of one depot", "at the checkout desk of the main branch", "at the front desk of one location")

$s0 = rand(0, 5)
$i0 = mod($s0 + 0, 6)
$i1 = mod($s0 + 1, 6)
$i2 = mod($s0 + 2, 6)
$i3 = mod($s0 + 3, 6)
$i4 = mod($s0 + 4, 6)
$i5 = mod($s0 + 5, 6)

// --- Study A: simple random sample ---
$whoA = $names[mod($w0 + 0, 8)]
$orgA = $prefixes[mod($p0 + 0, 8)] . " " . $orgtypes[$i0]
$unitA = $unitsings[$i0]
$unitpA = $unitplurs[$i0]
$belongA = $belongs[$i0]
$nAlist = array(30, 40, 50, 60)
$nA = $nAlist[rand(0, count($nAlist)-1)]
$NA = $nA * rand(8, 15)
$descA = $whoA . ' numbers all <b>' . $NA . '</b> ' . $unitpA . ' ' . $belongA . ' <b>' . $orgA . '</b> from 1 to ' . $NA . ', has a computer generate <b>' . $nA . '</b> different numbers at random, and surveys the ' . $unitpA . ' holding those numbers. No groups are formed at any stage.'

// --- Study B: stratified sample ---
$whoB = $names[mod($w0 + 1, 8)]
$orgB = $prefixes[mod($p0 + 1, 8)] . " " . $orgtypes[$i1]
$unitpB = $unitplurs[$i1]
$belongB = $belongs[$i1]
$grpB = $groupplurs[$i1]
$GB = rand(4, 7)
$mB = rand(6, 12)
$totB = $GB * $mB
$descB = $whoB . ' sorts the ' . $unitpB . ' ' . $belongB . ' <b>' . $orgB . '</b> into its ' . $GB . ' ' . $grpB . ', then randomly picks <b>' . $mB . '</b> of them from <b>each one</b> of the ' . $GB . ' ' . $grpB . ', for a sample of ' . $totB . '.'

// --- Study C: cluster sample ---
$whoC = $names[mod($w0 + 2, 8)]
$orgC = $prefixes[mod($p0 + 2, 8)] . " " . $orgtypes[$i2]
$unitC = $unitsings[$i2]
$unitpC = $unitplurs[$i2]
$belongC = $belongs[$i2]
$grpC = $groupplurs[$i2]
$GC = rand(6, 10)
$cC = rand(2, 4)
$remC = $GC - $cC
$descC = $whoC . ' numbers the ' . $GC . ' ' . $grpC . ' of <b>' . $orgC . '</b>, randomly selects <b>' . $cC . '</b> of them, and surveys <b>every</b> ' . $unitC . ' ' . $belongC . ' those ' . $cC . ' ' . $grpC . '. The other ' . $remC . ' contribute nobody.'

// --- Study D: systematic sample ---
$whoD = $names[mod($w0 + 3, 8)]
$orgD = $prefixes[mod($p0 + 3, 8)] . " " . $orgtypes[$i3]
$unitD = $unitsings[$i3]
$unitpD = $unitplurs[$i3]
$belongD = $belongs[$i3]
$orderD = $orderbys[$i3]
$kDlist = array(5, 8, 10, 12, 15, 20)
$kD = $kDlist[rand(0, count($kDlist)-1)]
$nDlist = array(40, 50, 60, 80)
$nD = $nDlist[rand(0, count($nDlist)-1)]
$ND = $nD * $kD
$descD = $whoD . ' prints a list of all <b>' . $ND . '</b> ' . $unitpD . ' ' . $belongD . ' <b>' . $orgD . '</b> in order of ' . $orderD . ', randomly draws a starting position between 1 and ' . $kD . ', and from there contacts <b>every ' . $kD . 'th</b> ' . $unitD . ' down the list until ' . $nD . ' have been reached.'

// --- Study E: convenience sample ---
$whoE = $names[mod($w0 + 4, 8)]
$orgE = $prefixes[mod($p0 + 4, 8)] . " " . $orgtypes[$i4]
$unitpE = $unitplurs[$i4]
$belongE = $belongs[$i4]
$placeE = $convplaces[$i4]
$nElist = array(25, 30, 40, 50)
$nE = $nElist[rand(0, count($nElist)-1)]
$descE = $whoE . ', studying the ' . $unitpE . ' ' . $belongE . ' <b>' . $orgE . '</b>, sets up a table ' . $placeE . ' and questions the first <b>' . $nE . '</b> of them who happen to walk past.'

// --- Distractor 1: a census, not a sample at all ---
$whoF = $names[mod($w0 + 5, 8)]
$orgF = $prefixes[mod($p0 + 5, 8)] . " " . $orgtypes[$i5]
$unitpF = $unitplurs[$i5]
$belongF = $belongs[$i5]
$NF = rand(15, 40) * 10
$descF = $whoF . ' contacts <b>every one</b> of the ' . $NF . ' ' . $unitpF . ' ' . $belongF . ' <b>' . $orgF . '</b> and records an answer from all ' . $NF . '.'

// --- Distractor 2: two-stage: whole groups dropped, then sampled inside the survivors ---
$whoG = $names[mod($w0 + 6, 8)]
$orgG = $prefixes[mod($p0 + 6, 8)] . " Community College"
$pickG = rand(2, 3)
$nGlist = array(15, 20, 25, 30)
$nG = $nGlist[rand(0, count($nGlist)-1)]
$leftG = 4 - $pickG
$descG = $whoG . ' randomly picks <b>' . $pickG . ' of the four class years</b> at <b>' . $orgG . '</b>, and then randomly samples <b>' . $nG . ' students inside each</b> of the ' . $pickG . ' chosen years.'

// The five method names stay in teaching order: the random methods first, in the order the
// section defines them, with the one non-random method last.
$questions = array("Simple random sample", "Stratified sample", "Cluster sample", "Systematic sample", "Convenience sample")

$answers = array($descA, $descB, $descC, $descD, $descE, $descF, $descG)

// Spelled out rather than left to the default: the two distractors make the answer list longer
// than the question list, so the mapping has to stay pinned to the first five.
$matchlist = "0,1,2,3,4"

$questiontitle = "Sampling method"
$answertitle = "Study"
// Terms fixed in teaching order, descriptions shuffled: the point is recognising which draw
// fits which name, not remembering where a row sat last time.
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
      <p>Ignore the topic, the institution, and the size of the sample. Only the <b>mechanics of the draw</b> decide the method.</p>
      <div class="term-row"><span class="term-label">Simple random sample:</span> ' . $whoA . ': numbers everyone and draws ' . $nA . ' numbers at random. No groups are formed, so every individual and every set of ' . $nA . ' ' . $unitpA . ' is equally likely.</div>
      <div class="term-row"><span class="term-label">Stratified sample:</span> ' . $whoB . ': splits the population into ' . $GB . ' ' . $grpB . ' and takes ' . $mB . ' from <em>each one</em>. Some from every group is the signature of stratifying.</div>
      <div class="term-row"><span class="term-label">Cluster sample:</span> ' . $whoC . ': picks ' . $cC . ' whole ' . $grpC . ' at random and takes <em>everyone</em> inside them. Entire groups (' . $remC . ' of them) are left out.</div>
      <div class="term-row"><span class="term-label">Systematic sample:</span> ' . $whoD . ': one random start, then every ' . $kD . 'th name down an ordered list. A fixed step through a list is the signature.</div>
      <div class="term-row"><span class="term-label">Convenience sample:</span> ' . $whoE . ': whoever happens to walk past. No chance mechanism anywhere, which is what makes it the non-random method.</div>
      <div class="sol-warn"><b>Strata slice, clusters scoop.</b> Stratified and cluster both start by dividing the population into groups, which is why they get swapped. The difference is what happens next: stratifying takes <em>a little from every group</em>, so every group is represented; clustering takes <em>everything from a few groups</em>, so whole groups are missing.</div>
      <div class="sol-note"><b>Two studies match nothing.</b><br>
      &bull; ' . $whoF . ' surveys <b>every</b> member of the population. That is a <b>census</b>, not a sample: there is no sampling method to name because no sampling happened.<br>
      &bull; ' . $whoG . ' picks ' . $pickG . ' of the four class years and then samples <b>' . $nG . ' students inside each</b>. That is a <b>two-stage design</b>. It is not stratified, because ' . $leftG . ' class years contribute nobody; and it is not a cluster sample either, because a cluster sample takes <em>all</em> the members of the selected groups, not a sample from within them.</div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Seven researchers each collect data a different way. Match each <b>sampling method</b> to the study that used it.</p>
    <p style="margin:12px 0 0 0;"><b>Two of the studies are not used</b>: they do not illustrate any of the five methods.</p>
  </div>
  $answerbox
</div>

// === ANSWER ===

$solutionguide
