// === NAME - DESCRIPTION: Percentiles and Shares of a Group - Convert between a percentile and the share of a group it sits above, working out which percentile the top few percent starts at and what share a stated percentile puts within reach ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "choices", "number")

$topPct = rand(8, 20)
$cutPercentile = 100 - $topPct

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "A state university admits students using an index score built from grades and test results. It aims to admit the top " . $topPct . "% of high school students in the state."
  $localCut = 96
  $localWord = "students at their own high school"
  $localName = "eligible in the local context"
}
else {
  $intro = "A conservatoire auditions applicants and scores each one. It aims to offer places to the top " . $topPct . "% of applicants nationally."
  $localCut = 95
  $localWord = "applicants from their own region"
  $localName = "regionally eligible"
}
$localShare = 100 - $localCut

$housePct = rand(20, 45)
// "$housePct th" substitutes with a space before the suffix; build the ordinals in the control.
$hSuf = "th"
$hLast = $housePct % 10
$hTens = ($housePct - $hLast) / 10
if ($hTens != 1) {
  if ($hLast == 1) { $hSuf = "st" }
  if ($hLast == 2) { $hSuf = "nd" }
  if ($hLast == 3) { $hSuf = "rd" }
}
$housePctLabel = $housePct . $hSuf
$localCutLabel = $localCut . "th"
$houseOther = 100 - $housePct
$housePrice = 10000 * rand(18, 40)

$answer[0] = $cutPercentile
$answerformat[0] = "integer"

$questions[1] = array(
  "About " . $housePct . "% of them, because the " . $housePct . "th percentile has about " . $housePct . "% of the prices at or below it.",
  "About " . $houseOther . "% of them, because the " . $housePct . "th percentile has about " . $houseOther . "% of the prices at or below it.",
  "About " . $housePct . "% of them, because the percentile counts down from the most expensive house.",
  "There is no way to tell without knowing how many houses are for sale."
)
$answer[1] = 0

$answer[2] = $localShare
$answerformat[2] = "integer"

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
      <p><span class="term-label">One idea, read from either end.</span> A percentile counts up from the bottom: the `k`th percentile has about `k`% of the group at or below it. "The top `p`%" counts down from the top. So the two always add to 100, and every part of this question is that one subtraction.</p>
      <p><span class="term-label">Part (a).</span> The top ' . $topPct . '% is everyone above the rest, and the rest is 100 &minus; ' . $topPct . ' = <b>' . $cutPercentile . '</b>%. So the cut-off is the <b>' . $cutPercentile . 'th percentile</b>. Writing it as the ' . $topPct . 'th is the mistake here, and it would admit the weakest ' . $topPct . '% instead of the strongest.</p>
      <p><span class="term-label">Part (b).</span> The most expensive house within reach sits at the ' . $housePct . 'th percentile of prices, so about <b>' . $housePct . '%</b> of the houses are at or below that price. The other ' . $houseOther . '% cost more. It is a smaller share than people expect, which is the useful thing a percentile tells you here.</p>
      <p><span class="term-label">Part (c).</span> Being ' . $localName . ' means standing at or above the ' . $localCut . 'th percentile of ' . $localWord . '. That leaves 100 &minus; ' . $localCut . ' = <b>' . $localShare . '%</b>: and it is ' . $localShare . '% of <i>every</i> school, large or small, because each one is measured against its own students rather than against the whole state.</p>
      <p><b>Answer:</b> (a) ' . $cutPercentile . 'th percentile &nbsp;&nbsp; (b) about ' . $housePct . '% &nbsp;&nbsp; (c) ' . $localShare . '%</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$intro</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The top $topPct% starts at which percentile? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Separately, you are buying a house. The most expensive one you can afford sits at the <b>$housePctLabel percentile</b> of prices in the town, which is &#36;$housePrice. Of the houses for sale, how many can you afford? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Back to the first scheme. Anyone at or above the <b>$localCutLabel percentile</b> of $localWord counts as $localName. What percent of each group is that? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
