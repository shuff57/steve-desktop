// === NAME - DESCRIPTION: Compare Across Groups with z-Scores - Convert two players' raw stats from different teams into z-scores to see who did better relative to his own team, then find a value a set number of standard deviations from a team mean ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "choices", "number")

$sportIdx = rand(0, 1)
if ($sportIdx == 0) {
  $sportWord = "baseball"
}
else {
  $sportWord = "softball"
}
$statWord = "batting average"

$teamNamesA = array("Ridgeview Hawks", "Cedar Falls Miners", "Brookhaven Foxes")
$teamNamesB = array("Sunset Valley Comets", "Harbor City Otters", "Prairie Wolves")
$tAi = rand(0, 2)
$tBi = rand(0, 2)
$teamNameA = $teamNamesA[$tAi]
$teamNameB = $teamNamesB[$tBi]

$playerNamesA = array("Marcus", "Devon", "Jalen")
$playerNamesB = array("Tyler", "Connor", "Diego")
$pAi = rand(0, 2)
$pBi = rand(0, 2)
$playerA = $playerNamesA[$pAi]
$playerB = $playerNamesB[$pBi]

// Team A is the stronger, more consistent lineup: a high mean and a tight spread,
// so Player A's raw average only has to sit a LITTLE above his team mean.
// Team B is the weaker, streakier lineup: a low mean and a wide spread, so Player B's
// raw average sits FAR above his team mean, even though the raw number itself is lower.
// The two team means are built far enough apart, and the "little above" / "far above"
// nudges are built small/large enough, that Player A's raw average always beats
// Player B's raw average while Player B's z-score always beats Player A's z-score.
// Guarantee (worst case over every rand() draw below):
//   min possible rawA_thou = 295 + 8       = 303
//   max possible rawB_thou = 240 + 60      = 300   -> rawA > rawB always (min gap 0.003)
//   max possible zA        = 10 / 22       = 0.4545
//   min possible zB        = 50 / 38       = 1.3158 -> zB - zA always at least 0.86
$teamMeanA_thou = 295 + rand(0, 10)
$teamSdA_thou = 22 + rand(0, 3)
$boostA_thou = 8 + rand(0, 2)
$rawA_thou = $teamMeanA_thou + $boostA_thou

$teamMeanB_thou = 230 + rand(0, 10)
$teamSdB_thou = 30 + rand(0, 8)
$boostB_thou = 50 + rand(0, 10)
$rawB_thou = $teamMeanB_thou + $boostB_thou

$teamMeanA = $teamMeanA_thou / 1000
$teamSdA = $teamSdA_thou / 1000
$rawA = $rawA_thou / 1000

$teamMeanB = $teamMeanB_thou / 1000
$teamSdB = $teamSdB_thou / 1000
$rawB = $rawB_thou / 1000

$zA = round(($rawA - $teamMeanA) / $teamSdA, 2)
$zB = round(($rawB - $teamMeanB) / $teamSdB, 2)

$answer[0] = $zA
$reltolerance[0] = 0.02
$abstolerance[0] = 0.01

$answer[1] = $zB
$reltolerance[1] = 0.02
$abstolerance[1] = 0.01

$questions[2] = array(
  $playerB . " did better relative to his own team, because his z-score is higher: his average sits more standard deviations above his own team's mean than " . $playerA . "'s does above his.",
  $playerA . " did better relative to his own team, because his raw " . $statWord . " is higher than " . $playerB . "'s.",
  $playerA . " did better relative to his own team, because his z-score is higher.",
  "There is no way to tell without knowing how many games each team has played."
)
$answer[2] = 0

$dTeamIdx = rand(0, 1)
if ($dTeamIdx == 0) {
  $dTeamName = $teamNameA
  $dMean = $teamMeanA
  $dSd = $teamSdA
}
else {
  $dTeamName = $teamNameB
  $dMean = $teamMeanB
  $dSd = $teamSdB
}
$dValue = round($dMean + 3 * $dSd, 3)

$answer[3] = $dValue
$reltolerance[3] = 0.02
$abstolerance[3] = 0.002

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
      <p><span class="term-label">Why a raw number is not enough.</span> ' . $playerA . ' and ' . $playerB . ' play for two different teams, and each team has its own mean and its own spread. A raw ' . $statWord . ' cannot be compared across the two groups directly, because a great number on a weak, inconsistent team means something different than the same number on a strong, tight team. The fix is the z-score, `z = (x - bar x)/s`, which restates each player relative only to his own team.</p>
      <p><span class="term-label">Part (a).</span> For the ' . $teamNameA . ', the mean is `bar x = ' . $teamMeanA . '` and the standard deviation is `s = ' . $teamSdA . '`. ' . $playerA . '&#39;s own average is `x = ' . $rawA . '`, so `z = (' . $rawA . ' - ' . $teamMeanA . ')/' . $teamSdA . ' = ' . $zA . '`.</p>
      <p><span class="term-label">Part (b).</span> For the ' . $teamNameB . ', the mean is `bar x = ' . $teamMeanB . '` and the standard deviation is `s = ' . $teamSdB . '`. ' . $playerB . '&#39;s own average is `x = ' . $rawB . '`, so `z = (' . $rawB . ' - ' . $teamMeanB . ')/' . $teamSdB . ' = ' . $zB . '`.</p>
      <p><span class="term-label">Part (c).</span> ' . $playerA . '&#39;s raw average (' . $rawA . ') is higher than ' . $playerB . '&#39;s (' . $rawB . '), but the z-scores tell the opposite story: ' . $zA . ' versus ' . $zB . '. ' . $playerB . ' stands more standard deviations above his own team&#39;s mean, so ' . $playerB . ' did better relative to his own team. Picking the higher raw number is the mistake this question is built to catch: it ignores that the two teams do not share a mean or a spread.</p>
      <p><span class="term-label">Part (d).</span> Three standard deviations above the ' . $dTeamName . '&#39;s mean is `bar x + 3s = ' . $dMean . ' + 3(' . $dSd . ') = ' . $dValue . '`.</p>
      <p><b>Answer:</b> (a) ' . $zA . ' &nbsp;&nbsp; (b) ' . $zB . ' &nbsp;&nbsp; (c) ' . $playerB . ' &nbsp;&nbsp; (d) ' . $dValue . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$playerA plays $sportWord for the $teamNameA. The team's $statWord this season has a mean of $teamMeanA and a standard deviation of $teamSdA. $playerA's own $statWord is $rawA.</p>
    <p style="margin:8px 0 0 0;">$playerB plays for the $teamNameB, a different league entirely. That team's $statWord has a mean of $teamMeanB and a standard deviation of $teamSdB. $playerB's own $statWord is $rawB.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Using `z = (x - bar x)/s`, find $playerA's z-score relative to his own team. (Round to 2 decimal places.) $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find $playerB's z-score relative to his own team. (Round to 2 decimal places.) $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which player did better relative to his own team? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> A $statWord exactly three standard deviations above the mean would be considered exceptional. What $statWord is exactly three standard deviations above the $dTeamName's mean? (Give your answer to 3 decimal places.) $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
