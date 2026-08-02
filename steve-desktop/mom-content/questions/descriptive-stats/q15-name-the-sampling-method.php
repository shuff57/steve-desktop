// === NAME - DESCRIPTION: Name the Sampling Method - Identify a study as simple random, stratified, cluster, systematic, or convenience sampling from how the sample was drawn ===
// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL ===

// Which of the five methods this version actually illustrates.
$mi = rand(0, 4)

// Surface details: researcher, institution, population, sizes, step, group count.
$names = array("Dr. Alvarez", "Dr. Chen", "Dr. Okafor", "Dr. Nguyen", "Dr. Brennan", "Dr. Silva", "Dr. Whitfield", "Dr. Kowalski")
$who = $names[rand(0, count($names)-1)]

$orgpre = array("Riverside", "Lakeshore", "Pine Valley", "Grand Mesa", "Harbor Point", "Fairhaven", "Northline", "Cedar Ridge")
$orgpart = $orgpre[rand(0, count($orgpre)-1)]

// Six settings, each a flat parallel array (no nested indexing anywhere).
$si = rand(0, 5)
$orgtypes = array("State College", "General Hospital", "Utility District", "Logistics", "Unified School District", "Fitness Network")
$unitsings = array("student", "nurse", "household", "employee", "teacher", "member")
$unitplurs = array("students", "nurses", "households", "employees", "teachers", "members")
$belongs = array("enrolled at", "employed by", "served by", "working for", "teaching in", "who belong to")
$topics = array("the mean number of hours per week that they study", "the mean number of overtime hours they work in a month", "the mean monthly water usage", "the mean one-way commute time", "the mean number of hours per week they spend on lesson planning", "the mean number of visits they make each week")
$groupplurs = array("academic departments", "hospital units", "city neighborhoods", "branch locations", "elementary schools", "gym locations")
$orderbys = array("last name", "employee ID number", "account number", "hire date", "last name", "membership number")
$convplaces = array("outside the campus library", "in the staff cafeteria", "in the lobby of the district office", "by the main entrance of the head office", "in the parking lot after a district meeting", "at the front desk of one branch")

$org = $orgpart . " " . $orgtypes[$si]
$unit = $unitsings[$si]
$unitp = $unitplurs[$si]
$belong = $belongs[$si]
$topic = $topics[$si]
$groupp = $groupplurs[$si]
$orderby = $orderbys[$si]
$convplace = $convplaces[$si]

// Sizes. Population is the sample size times the step, so the systematic version is consistent.
$nlist = array(40, 50, 60, 80, 100)
$klist = array(6, 8, 10, 12, 15, 20)
$n = $nlist[rand(0, count($nlist)-1)]
$k = $klist[rand(0, count($klist)-1)]
$N = $n * $k
$G = rand(5, 9)
$perGroup = rand(5, 12)
$strat = $perGroup * $G
$c = rand(2, 4)
$remaining = $G - $c

$popphrase = 'the <b>' . $N . '</b> ' . $unitp . ' ' . $belong . ' <b>' . $org . '</b>'
$intro = '<b>' . $who . '</b> is studying ' . $popphrase . ', and wants to estimate ' . $topic . '.'

// The mechanics of the draw, plus the phrase that settles the identification.
if ($mi == 0) {
  $mechanics = 'The researcher assigns every ' . $unit . ' a number from 1 to ' . $N . ', has a computer generate ' . $n . ' different numbers at random, and surveys the ' . $unitp . ' holding those numbers. No grouping is used at any stage: every individual, and every possible set of ' . $n . ' ' . $unitp . ', is equally likely to be chosen.'
  $signature = 'no grouping at all, and every set of ' . $n . ' ' . $unitp . ' equally likely'
}
if ($mi == 1) {
  $mechanics = 'The researcher sorts the ' . $unitp . ' into the ' . $G . ' ' . $groupp . ', then randomly picks ' . $perGroup . ' ' . $unitp . ' from <b>each one</b> of the ' . $G . ' ' . $groupp . ', for a sample of ' . $strat . '.'
  $signature = $perGroup . ' ' . $unitp . ' from <b>each one</b> of the ' . $G . ' ' . $groupp
}
if ($mi == 2) {
  $mechanics = 'The researcher numbers the ' . $G . ' ' . $groupp . ', randomly picks ' . $c . ' of them, and surveys <b>every</b> ' . $unit . ' in those ' . $c . ' ' . $groupp . '. The other ' . $remaining . ' ' . $groupp . ' are not sampled at all.'
  $signature = '<b>every</b> ' . $unit . ' in the ' . $c . ' chosen ' . $groupp . ', with the other ' . $remaining . ' left out entirely'
}
if ($mi == 3) {
  $mechanics = 'The researcher prints a list of all ' . $N . ' ' . $unitp . ' ordered by ' . $orderby . ', randomly draws a starting position between 1 and ' . $k . ', and from there surveys every ' . $k . 'th ' . $unit . ' on the list until ' . $n . ' have been contacted.'
  $signature = 'a random start, then <b>every ' . $k . 'th</b> ' . $unit . ' down an ordered list'
}
if ($mi == 4) {
  $mechanics = 'The researcher sets up a table ' . $convplace . ' and surveys the first ' . $n . ' ' . $unitp . ' who happen to pass by.'
  $signature = 'the first ' . $n . ' ' . $unitp . ' <b>who happen to pass by</b> &mdash; no chance mechanism anywhere'
}

// All five options appear every time; MyOpenMath shuffles them.
$questions = array("Simple random sample", "Stratified sample", "Cluster sample", "Systematic sample", "Convenience sample")
$answer = $mi
$correct = $questions[$mi]

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
      <p><b>Step 1 &mdash; Find the phrase that describes how the sample was actually drawn.</b> Ignore the topic and the numbers; only the mechanics of the draw decide the method.</p>
      <p><b>Step 2 &mdash; The deciding phrase here:</b> ' . $signature . '.</p>
      <div class="term-row"><span class="term-label">Answer:</span> ' . $correct . '</div>
      <p style="margin-top:1em;"><b>The five signatures:</b></p>
      <div class="term-row"><span class="term-label">Simple random:</span> every individual, and every group of the same size, equally likely &mdash; no grouping used.</div>
      <div class="term-row"><span class="term-label">Stratified:</span> split the population into groups, then take <em>some from every group</em>.</div>
      <div class="term-row"><span class="term-label">Cluster:</span> pick whole groups at random, then take <em>everyone in the chosen groups</em>.</div>
      <div class="term-row"><span class="term-label">Systematic:</span> a random start, then every `k`th item down an ordered list.</div>
      <div class="term-row"><span class="term-label">Convenience:</span> whoever was handy &mdash; no chance mechanism at all.</div>
      <div class="sol-warn"><b>Strata slice, clusters scoop.</b> Stratified and cluster both begin by dividing the population into groups, which is why they get swapped. The difference is what happens next: stratifying takes <em>a little from every group</em>, so every group is represented; clustering takes <em>everything from a few groups</em>, so whole groups are left out.</div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$intro</p>
    <p style="margin:12px 0 0 0;">$mechanics</p>
    <p style="margin:12px 0 0 0;"><b>Which sampling method did the researcher use?</b></p>
  </div>
  $answerbox
</div>

// === ANSWER ===

$solutionguide
