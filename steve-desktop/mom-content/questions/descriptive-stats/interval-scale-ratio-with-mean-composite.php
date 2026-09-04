// === NAME - DESCRIPTION: Rounding Rule and Interval-Scale Ratio Claims - Compute the mean of several temperature readings using the correct rounding rule, then explain why a "twice as hot" ratio claim is invalid for interval-scale data ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "choices")

$scaleNames = array("Fahrenheit", "Celsius")
$scaleAbbrevs = array("&deg;F", "&deg;C")
$scaleIdx = rand(0, 1)
$scaleName = $scaleNames[$scaleIdx]
$scaleAbbrev = $scaleAbbrevs[$scaleIdx]

$rangeLoArr = array(28, -5)
$rangeHiArr = array(96, 35)
$lo = $rangeLoArr[$scaleIdx]
$hi = $rangeHiArr[$scaleIdx]

$cityNames = array("Boulder", "Tucson", "Portland", "Minneapolis", "Savannah", "Fargo", "Asheville", "Spokane")
$cityIdx = rand(0, count($cityNames)-1)
$city = $cityNames[$cityIdx]

$precision = rand(0, 1)
$roundplaces = $precision + 1

$precisionLabels = array("the nearest whole degree", "the nearest tenth of a degree")
$precisionText = $precisionLabels[$precision]

$placeLabels = array("", "one decimal place", "two decimal places")
$placeText = $placeLabels[$roundplaces]

$sum = 0
$listStr = ""
$sep = ""
for ($i=0..5) {
  if ($precision == 0) {
    $v[$i] = rand($lo, $hi)
    $vstr[$i] = $v[$i]
  } else {
    $t = rand($lo*10, $hi*10)
    $whole = floor($t/10)
    $frac = $t - $whole*10
    $v[$i] = $t/10
    $vstr[$i] = $whole . "." . $frac
  }
  $sum = $sum + $v[$i]
  $listStr = $listStr . $sep . $vstr[$i] . $scaleAbbrev
  $sep = ", "
}

$mean = round($sum/6, $roundplaces)

$reqd = "=" . $roundplaces
if ($roundplaces == 1) {
  $tol = 0.05
} else {
  $tol = 0.005
}

$answer[0] = $mean
$abstolerance[0] = $tol
$reqdecimals[0] = $reqd

$choiceTexts = array(
  "$scaleName is an interval scale: 0$scaleAbbrev is a chosen reference point, not a total absence of heat, so one reading can never be described as a multiple of another.",
  "The two readings cannot be compared at all, since they were not recorded on the exact same day.",
  "Ratios are never meaningful for numerical data, so no two measurements can ever be described as 'twice as much.'",
  "$scaleName readings are only recorded to $precisionText, and that rounding error is what makes the ratio unreliable."
)
$questions[1] = $choiceTexts
$answer[1] = 0

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
      <p><span class="term-label">a. Mean temperature:</span></p>
      <p><b>Step 1: Add the six readings.</b> ' . $listStr . ' sum to ' . $sum . ' ' . $scaleAbbrev . '.</p>
      <p><b>Step 2: Divide by how many readings there are:</b> `' . $sum . ' / 6 = ' . round($sum/6, 4) . '`</p>
      <p><b>Step 3: Round.</b> Each reading was recorded to ' . $precisionText . ', so the final answer carries one more decimal place than the data: ' . $placeText . '.</p>
      <p><b>Answer:</b> ' . $mean . ' ' . $scaleAbbrev . '.</p>
      <p><span class="term-label">b. Why the ratio claim fails:</span> ' . $scaleName . ' is an interval scale, not a ratio scale. Its 0' . $scaleAbbrev . ' is a human convention, the freezing point of water, for Celsius, and an arbitrary brine mixture, for Fahrenheit, not the total absence of temperature. Because there is no true zero, dividing one reading by another does not produce a meaningful ratio, so a claim like "80' . $scaleAbbrev . ' is twice as hot as 40' . $scaleAbbrev . '" is not a valid statement even though the arithmetic 80/40 = 2 is correct.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A weather station in $city recorded the high temperature, in degrees $scaleName, on six consecutive days. Each reading was recorded to $precisionText:</p>
    <p style="margin:0; font-family:'DM Mono',monospace;">$listStr</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Compute the mean of these six readings. Round your answer to the correct number of decimal places, given the precision of the data above ($placeText). $answerbox[0] $scaleAbbrev
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> A meteorologist looks at readings like these and claims: "A high of 80$scaleAbbrev is twice as hot as a high of 40$scaleAbbrev." Which of the following best explains why this claim is <b>not</b> statistically valid? $answerbox[1]
  </div>
</div>

// === ANSWER ===

$solutionguide
