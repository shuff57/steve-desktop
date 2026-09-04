// === NAME - DESCRIPTION: Over-representation in a Two-Series Bar Graph - A grouped bar graph puts each group's share of an event beside its share of the population; find the over-represented group, measure the gap in percentage points, and say what the graph cannot tell you ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "number", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "David County has five high schools. Each sent students to a county-wide science competition."
  $seriesA = "Share of competitors"
  $seriesB = "Share of county students"
  $groupWord = "school"
  $people = "students"
  $eventName = "the competition"
  $wholeName = "the county"
  $s0 = "Alabaster"
  $s1 = "Concordia"
  $s2 = "Genoa"
  $s3 = "Mocksville"
  $s4 = "West End"
}
else {
  $intro = "A city library system has five branches. Each hosted sessions of a summer reading program."
  $seriesA = "Share of program sign-ups"
  $seriesB = "Share of registered borrowers"
  $groupWord = "branch"
  $people = "readers"
  $eventName = "the reading program"
  $wholeName = "the city"
  $s0 = "Ashfield"
  $s1 = "Brookvale"
  $s2 = "Corby Hill"
  $s3 = "Denholm"
  $s4 = "Eastgate"
}
$names = array($s0, $s1, $s2, $s3, $s4)

// The population shares are built to total exactly 100. The competition shares are then made by
// moving a few points out of every other group into one: so both series still total 100, and
// exactly one group comes out ahead of its own population share. Generating the two series
// independently would leave ties for "most over-represented", which has no single answer.
$pop = array(0, 0, 0, 0, 0)
$used = 0
for ($k=0..3) {
  $pop[$k] = rand(15, 22)
  $used = $used + $pop[$k]
}
$pop[4] = 100 - $used

$star = rand(0, 4)
$comp = array(0, 0, 0, 0, 0)
$gain = 0
for ($k=0..4) {
  $comp[$k] = $pop[$k]
  if ($k != $star) {
    $take = rand(3, 6)
    $comp[$k] = $pop[$k] - $take
    $gain = $gain + $take
  }
}
$comp[$star] = $pop[$star] + $gain

$starName = $names[$star]
$starComp = $comp[$star]
$starPop = $pop[$star]

$maxV = 0
$tableRows = ""
for ($k=0..4) {
  if ($comp[$k] > $maxV) { $maxV = $comp[$k] }
  if ($pop[$k] > $maxV) { $maxV = $pop[$k] }
  $tableRows = $tableRows . '<tr><td style="border:1px solid #d1d5db; padding:6px 14px;">' . $names[$k] . '</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">' . $comp[$k] . '%</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">' . $pop[$k] . '%</td></tr>'
}

$top = round($maxV / 10 + 0.5, 0) * 10
$gN = $top / 10
$unitPx = 195 / $top

$grid = ""
for ($g=0..$gN) {
  $v = 10 * $g
  $gy = round(230 - $v * $unitPx, 2)
  $grid = $grid . '<line x1="60" y1="' . $gy . '" x2="545" y2="' . $gy . '" stroke="#e5e7eb" stroke-width="1"/>'
  $grid = $grid . '<text x="54" y="' . ($gy + 4) . '" font-size="12" fill="#6b7280" text-anchor="end">' . $v . '</text>'
}

$bars = ""
for ($k=0..4) {
  $gx = 72 + $k * 96
  $hA = round($comp[$k] * $unitPx, 2)
  $hB = round($pop[$k] * $unitPx, 2)
  $yA = round(230 - $hA, 2)
  $yB = round(230 - $hB, 2)
  $bars = $bars . '<rect x="' . $gx . '" y="' . $yA . '" width="34" height="' . $hA . '" fill="#2563eb" stroke="#1e3a8a" stroke-width="1"/>'
  $bars = $bars . '<rect x="' . ($gx + 36) . '" y="' . $yB . '" width="34" height="' . $hB . '" fill="#bfdbfe" stroke="#1e3a8a" stroke-width="1"/>'
  $bars = $bars . '<text x="' . ($gx + 35) . '" y="249" font-size="11" fill="#374151" text-anchor="middle">' . $names[$k] . '</text>'
}

$legend = '<rect x="60" y="264" width="16" height="12" fill="#2563eb" stroke="#1e3a8a" stroke-width="1"/><text x="82" y="274" font-size="12" fill="#374151">' . $seriesA . '</text>'
$legend = $legend . '<rect x="300" y="264" width="16" height="12" fill="#bfdbfe" stroke="#1e3a8a" stroke-width="1"/><text x="322" y="274" font-size="12" fill="#374151">' . $seriesB . '</text>'

$svg = '<svg viewBox="0 0 570 292" width="100%" style="max-width:570px; display:block; margin:10px auto; background:#fff;" xmlns="http://www.w3.org/2000/svg" role="img">'
$svg = $svg . $grid . $bars
$svg = $svg . '<line x1="60" y1="28" x2="60" y2="230" stroke="#374151" stroke-width="2"/><line x1="60" y1="230" x2="545" y2="230" stroke="#374151" stroke-width="2"/>'
$svg = $svg . '<text x="18" y="130" font-size="12" fill="#374151" text-anchor="middle" transform="rotate(-90 18 130)">Percent</text>'
$svg = $svg . $legend
$svg = $svg . '</svg>'

$questions[0] = array($s0, $s1, $s2, $s3, $s4)
$answer[0] = $star

$answer[1] = $gain
$answerformat[1] = "integer"

$questions[2] = array(
  "Which groups are over- or under-represented. It says nothing about how many " . $people . " each one actually contributed, because the two bars are shares of two different wholes.",
  "Which " . $groupWord . " has the most " . $people . " in " . $wholeName . " altogether.",
  "Which " . $groupWord . " did best in " . $eventName . ".",
  "That the tallest dark bar contributed the most " . $people . ", because a larger share always means a larger number."
)
$answer[2] = 0

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
      <p><span class="term-label">Step 1: compare each pair, not the graph as a whole.</span> The question is never "which bar is tallest". It is, for each ' . $groupWord . ', does the dark bar stand above its own pale bar? Everywhere except one, the dark bar is the shorter of the pair.</p>
      <p><span class="term-label">Step 2: the one that stands out.</span> <b>' . $starName . '</b> supplied ' . $starComp . '% of the first column while holding only ' . $starPop . '% of the second. It is over-represented; every other ' . $groupWord . ' contributed a smaller share than its size would suggest.</p>
      <p><span class="term-label">Step 3: measure the gap in percentage points.</span> Subtract: ' . $starComp . '% &minus; ' . $starPop . '% = <b>' . $gain . ' percentage points</b>. Percentage points, not percent: going from ' . $starPop . '% to ' . $starComp . '% is a rise of ' . $gain . ' points, which is a much larger relative jump, and the two numbers are not interchangeable.</p>
      <p><span class="term-label">Step 4: what this cannot tell you.</span> Both columns are percentages of different totals: one of everyone taking part, the other of all ' . $people . ' in ' . $wholeName . '. So the graph shows who is over- and under-represented, and nothing at all about headcounts. A small ' . $groupWord . ' with a handful of keen ' . $people . ' can top this graph while contributing the fewest people of anyone.</p>
      <p><b>Answer:</b> (a) ' . $starName . ' &nbsp;&nbsp; (b) ' . $gain . ' percentage points</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">$intro The bar graph below puts each one's $seriesA next to its $seriesB. Each column of percentages totals 100%.</p>
    $svg
    <table style="border-collapse:collapse; margin:6px auto 0 auto;">
      <thead>
        <tr style="background:#f0f4ff;">
          <th style="border:1px solid #d1d5db; padding:7px 14px; text-align:left;">Location</th>
          <th style="border:1px solid #d1d5db; padding:7px 14px;">$seriesA</th>
          <th style="border:1px solid #d1d5db; padding:7px 14px;">$seriesB</th>
        </tr>
      </thead>
      <tbody>
        $tableRows
      </tbody>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which one is over-represented, with a larger $seriesA than $seriesB? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> By how many <b>percentage points</b> does that $groupWord's $seriesA exceed its $seriesB? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What does putting these two series side by side actually show? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
