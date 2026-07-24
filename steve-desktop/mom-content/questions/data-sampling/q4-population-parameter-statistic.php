// === NAME - DESCRIPTION: Population vs Sample, Parameter vs Statistic - Read a study description; identify the population, the sample, and whether a given numeric summary is a parameter or a statistic ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices", "choices")

// Three scenarios — each gives a stated population, a sample of size n, and a numeric value with its origin.
// Scenario 0: All 12,400 community-college students; sample of 220 surveyed; mean study-hours 14.2 hrs reported FROM THE SAMPLE → statistic
// Scenario 1: All 5,000 registered voters in a city; sample of 300 polled; 62% support measure → from sample → statistic
// Scenario 2: All employees at a 1,800-person hospital; HR pulled the full payroll and computed mean salary $58,400 → from POPULATION → parameter
// Scenario 3: All 240 cars in a parking lot; researcher walked through ALL of them and counted 38 with expired tags → from population → parameter
// Scenario 4: All 2,500 trees in a state forest; rangers measured 80 trees; mean height 31.7 ft → from sample → statistic

$ctxs = array(
  "An education researcher wants to know how many hours per week the <b>12,400 students at a community college</b> spend studying. She randomly surveys <b>220 of those students</b> and finds their <b>mean study time is 14.2 hours per week</b>.",
  "A city has <b>5,000 registered voters</b>. A polling firm phones <b>300 of them</b> and finds that <b>62% support a ballot measure</b>.",
  "A hospital's HR director pulled the <b>complete payroll for all 1,800 hospital employees</b> and computed that the <b>mean annual salary is $58,400</b>.",
  "A campus researcher inspected <b>every one of the 240 cars in the staff parking lot</b> and counted that <b>38 cars had expired registration tags</b>.",
  "A forest manager oversees <b>2,500 trees in a state forest</b>. Rangers measured a random sample of <b>80 trees</b> and recorded a <b>mean height of 31.7 ft</b>."
)

// Population text (correct choice = 0), then sample text, then param/stat (0 param, 1 stat)
$popsamp = array(
  array("All 12,400 community-college students", "The 220 students who were surveyed", 1),
  array("All 5,000 registered voters", "The 300 voters polled", 1),
  array("All 1,800 hospital employees", "(There is no sample — the entire population was measured)", 0),
  array("All 240 cars in the staff parking lot", "(There is no sample — every car was inspected)", 0),
  array("All 2,500 trees in the state forest", "The 80 trees the rangers measured", 1)
)

$i = rand(0, count($ctxs)-1)
$ctx = $ctxs[$i]
$pop_correct = $popsamp[$i][0]
$samp_correct = $popsamp[$i][1]
$answer[2] = $popsamp[$i][2]

// To force consistency across scenarios, populations and samples differ — use scenario-specific 2-option choices each time
$choices[0] = array($pop_correct, $samp_correct)
$choices[1] = array($samp_correct, $pop_correct)
$answer[0] = 0
$answer[1] = 0
$noshuffle[0] = "all"
$noshuffle[1] = "all"
$noshuffle[2] = "all"
$choices[2] = array("Parameter (computed from the whole population)", "Statistic (computed from a sample)")

$why = "The value was computed from a subset (the sample) of the population, so it is a <b>statistic</b>."
if ($popsamp[$i][2] == 0) { $why = "Every member of the stated population was measured, so the reported value is a <b>parameter</b>." }

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><b>Population:</b> ' . $pop_correct . '.</p>
      <p><b>Sample:</b> ' . $samp_correct . '.</p>
      <p><b>Parameter vs Statistic:</b> ' . $why . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Identify the <b>population</b>. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Identify the <b>sample</b> (or indicate that there is no sample). $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is the bolded numeric value a <b>parameter</b> or a <b>statistic</b>? $answerbox[2]
  </div>
</div>


// === ANSWER ===

$solutionguide
