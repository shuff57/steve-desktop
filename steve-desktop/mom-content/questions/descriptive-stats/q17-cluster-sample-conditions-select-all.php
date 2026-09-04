// === NAME - DESCRIPTION: Conditions Defining a Cluster or Stratified Sample - Check every statement that is true of the named sampling method, separating clustering from stratifying and from the neighbouring methods ===
// === SET QUESTION TYPE TO: multans ===

// === COMMON CONTROL ===

// Randomize the researcher, so the wording differs student to student.
$names = array("Dr. Alvarez", "Dr. Chen", "Dr. Okafor", "Dr. Nguyen", "Dr. Brennan", "Dr. Silva", "Dr. Whitfield", "Dr. Ramos")
$name = $names[rand(0, count($names)-1)]

// Which method is the target: 0 = cluster, 1 = stratified.
$t = rand(0, 1)

// The statement that is true of BOTH methods.
$both1 = "The population is first divided into groups."

// True of a cluster sample only.
$clu1 = "The groups are chosen at random."
$clu2 = "Every member of a chosen group is in the sample."
$clu3 = "Some groups contribute nobody to the sample."

// True of a stratified sample only.
$str1 = "A proportionate number is taken at random from each group."
$str2 = "Every group is represented in the sample."

// Neighbouring methods: false for both target methods.
$nb = array("A random starting point is chosen and then every `k`th item on the list is taken.", "Members are chosen because they were the easiest to reach.", "Every group of the same size is equally likely to be selected.")
$nbwhy = array("that is a <b>systematic</b> sample: one random start, then a fixed step down an ordered list", "that is a <b>convenience</b> sample: no chance process at all, just whoever was readily available", "that is what makes a sample a <b>simple random</b> sample: every possible group of the same size equally likely")

// Two distinct neighbouring distractors, so the wrong options are not the same set twice.
$n0 = rand(0, 2)
$n1 = ($n0 + rand(1, 2)) % 3
$nbA = $nb[$n0]
$nbB = $nb[$n1]
$wrongN1 = $nbA . ': ' . $nbwhy[$n0] . '.'
$wrongN2 = $nbB . ': ' . $nbwhy[$n1] . '.'

// Correct options first so the answer key is fixed; MOM shuffles the display order for the student.
$method = "cluster" if ($t==0)
$method = "stratified" if ($t==1)
$questions = array($both1, $clu1, $clu2, $clu3, $str1, $str2, $nbA, $nbB) if ($t==0)
$answers = "0,1,2,3" if ($t==0)
$questions = array($both1, $str1, $str2, $clu1, $clu2, $clu3, $nbA, $nbB) if ($t==1)
$answers = "0,1,2" if ($t==1)

// Precompute the solution pieces as scalars: the guide cannot index arrays.
$mdef = "divide the population into clusters (groups), randomly select some of the clusters, and take <i>all</i> the members of the selected clusters" if ($t==0)
$mdef = "divide the population into groups called strata, then take a proportionate number at random from <i>every</i> stratum" if ($t==1)

$truelist = '<div class="term-row">&bull; ' . $both1 . '</div><div class="term-row">&bull; ' . $clu1 . '</div><div class="term-row">&bull; ' . $clu2 . '</div><div class="term-row">&bull; ' . $clu3 . '</div>' if ($t==0)
$truelist = '<div class="term-row">&bull; ' . $both1 . '</div><div class="term-row">&bull; ' . $str1 . '</div><div class="term-row">&bull; ' . $str2 . '</div>' if ($t==1)

$otherlist = '<div class="term-row">&bull; ' . $str1 . ': that is <b>stratifying</b>, which takes a share from every group instead of whole groups.</div><div class="term-row">&bull; ' . $str2 . ': clustering leaves entire groups out, so not every group is represented.</div>' if ($t==0)
$otherlist = '<div class="term-row">&bull; ' . $clu1 . ': in a stratified sample no group is dropped, so the groups are not the thing being randomized.</div><div class="term-row">&bull; ' . $clu2 . ': that is <b>clustering</b>, which scoops up whole groups.</div><div class="term-row">&bull; ' . $clu3 . ': stratifying draws from every stratum, so no group is left out.</div>' if ($t==1)

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
      <p><b>The definition:</b> to choose a <b>' . $method . '</b> sample, ' . $mdef . '.</p>
      <p><b>Strata slice, clusters scoop.</b> Stratifying takes a little from every group, so every group is represented. Clustering takes everything from a few groups, so entire groups are left out. Both are random: they just randomize different things.</p>
      <div class="term-row"><span class="term-label">True of a ' . $method . ' sample (check these):</span></div>
      ' . $truelist . '
      <p style="margin-top:1em;"><b>Why the other statements do not belong:</b></p>
      ' . $otherlist . '
      <div class="term-row">&bull; ' . $wrongN1 . '</div>
      <div class="term-row">&bull; ' . $wrongN2 . '</div>
      <p style="margin-top:1em;"><b>Watch the trap:</b> every one of these methods starts by dividing the population up or lining it up, so &quot;there are groups&quot; is never enough on its own. What separates them is <i>what gets randomized</i> and <i>who inside a group ends up in the sample</i>.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;"><b>$name</b> is planning how to draw a sample from a large population and has settled on a <b>$method sample</b>.</p>
    <p style="margin:12px 0 0 0;"><b>Select every statement that is true of a $method sample.</b> More than one statement is true, and a statement counts only if it is true of a $method sample in particular.</p>
  </div>
  $answerbox
</div>

// === ANSWER ===

$solutionguide
