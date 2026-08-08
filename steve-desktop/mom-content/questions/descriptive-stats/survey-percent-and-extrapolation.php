// === NAME - DESCRIPTION: Survey Percent Complement, Extrapolated Count, and Population Limits - From one institution's survey percentages, find a complement percent, extrapolate a respondent count, then judge whether those percentages would hold at a contrasting institution ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "choices")

$ctxIdx = rand(0, 2)

$collegeAList = array("Fairview City College", "Union Square Community College", "Northfield Community College")
$collegeBList = array("Sable Valley College", "Brookhaven State University", "Meridian Virtual University")
$topicList = array("distance learning", "financial aid and working while in school", "course format preferences")

$itemADisplayList = array("Have a computer at home", "Receive some form of financial aid", "Prefer at least some in-person classes")
$itemAInlineList = array("have a computer at home", "receive some form of financial aid", "prefer at least some in-person classes")
$itemANotInlineList = array("do not have a computer at home", "do not receive any financial aid", "do not prefer at least some in-person classes, meaning they prefer fully online courses")

$itemBDisplayList = array("Live at least 15 miles from campus", "Work at least 20 hours per week at a paying job", "Have taken at least one hybrid, part-online and part-in-person, course")
$itemBVerbInlineList = array("live at least 15 miles from campus", "work at least 20 hours per week at a paying job", "have taken at least one hybrid, part-online and part-in-person, course")

$itemCDisplayList = array("Would like the college to offer more distance-learning sections", "Would recommend this college to a friend", "Are satisfied with their course format options")

$reasonList = array(
  "serves a rural area spread across a much larger distance, and home internet access may be less consistent across that region",
  "is a four-year school where most students live on campus full time, a very different population from the working commuter students surveyed here",
  "offers no in-person classes at all, so its students specifically chose a fully online school, unlike the mix of in-person and online students surveyed here"
)

$collegeA = $collegeAList[$ctxIdx]
$collegeB = $collegeBList[$ctxIdx]
$topic = $topicList[$ctxIdx]
$itemADisplay = $itemADisplayList[$ctxIdx]
$itemAInline = $itemAInlineList[$ctxIdx]
$itemANotInline = $itemANotInlineList[$ctxIdx]
$itemBDisplay = $itemBDisplayList[$ctxIdx]
$itemBVerbInline = $itemBVerbInlineList[$ctxIdx]
$itemCDisplay = $itemCDisplayList[$ctxIdx]
$reason = $reasonList[$ctxIdx]

$pctA = rand(85, 98)
$pctB = rand(8, 30)
$pctC = rand(55, 92)
$N = rand(300, 950)

$pctANot = 100 - $pctA

$countBExact = $N * $pctB / 100
$countB = round($countBExact)
$countBDisp = round($countBExact, 2)

$answer[0] = $pctANot
$abstolerance[0] = 0.1

$answer[1] = $countB
$abstolerance[1] = 1

$choices[2] = array(
  "No, probably not. These percentages describe only the " . $N . " " . $collegeA . " students who answered this survey. " . $collegeB . " " . $reason . ", so there is good reason to expect the same questions to get different answers there. A percentage measured from the respondents to one survey does not automatically apply to a different population that was not surveyed.",
  "Yes, the percentages measure something intrinsic to the survey questions themselves, so any group of students asked the same questions would answer the same way.",
  "Yes, since " . $N . " is a large sample size, the results generalize to any similar institution, regardless of who was actually surveyed.",
  "No, but only because " . $collegeB . " would need approval from its own administration to run the same survey, not because its student population differs."
)
$answer[2] = 0

$introText = $N . " students at " . $collegeA . " responded to a survey about " . $topic . " during a recent academic year. Selected results are shown below."

$tableHtml = '<table style="border-collapse:collapse; margin:10px 0; font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:15px;">'
$tableHtml = $tableHtml . '<tr style="background:#e8f0fe;"><th style="border:1px solid #c8d4ea; padding:7px 16px; text-align:left;">Survey Item</th><th style="border:1px solid #c8d4ea; padding:7px 16px;">Percent</th></tr>'
$tableHtml = $tableHtml . '<tr><td style="border:1px solid #d9dee8; padding:7px 16px;">' . $itemADisplay . '</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $pctA . '%</td></tr>'
$tableHtml = $tableHtml . '<tr><td style="border:1px solid #d9dee8; padding:7px 16px;">' . $itemBDisplay . '</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $pctB . '%</td></tr>'
$tableHtml = $tableHtml . '<tr><td style="border:1px solid #d9dee8; padding:7px 16px;">' . $itemCDisplay . '</td><td style="border:1px solid #d9dee8; padding:7px 16px; text-align:center;">' . $pctC . '%</td></tr>'
$tableHtml = $tableHtml . '</table>'

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
      <p><span class="term-label">a. Percent who ' . $itemANotInline . '.</span> ' . $pctA . '% of the ' . $N . ' respondents ' . $itemAInline . ', so the rest must be everyone left over: 100% &minus; ' . $pctA . '% = <b>' . $pctANot . '%</b>.</p>
      <p><span class="term-label">b. About how many ' . $itemBVerbInline . '.</span> ' . $pctB . '% of the ' . $N . ' respondents ' . $itemBVerbInline . ': ' . $pctB . '/100 &times; ' . $N . ' = ' . $countBDisp . ' &approx; <b>' . $countB . ' students</b>.</p>
      <p><span class="term-label">c. Would the percentages be the same at ' . $collegeB . '?</span> Probably not. These percentages describe only the ' . $N . ' ' . $collegeA . ' students who happened to answer this particular survey. ' . $collegeB . ' ' . $reason . ', so there is good reason to expect the same survey questions to produce different answers there. A survey result is a property of the population that was actually sampled, not a universal constant of the questions themselves &mdash; a different institution needs its own survey, not someone else&rsquo;s percentages.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$introText</p>
    $tableHtml
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What percent of the students surveyed $itemANotInline? Enter your answer as a whole-number percent (for example, if your answer were 4%, enter 4). $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> About how many of the students surveyed $itemBVerbInline? Round to the nearest whole student. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> If the same survey were given to students at $collegeB, do you think the percentages above would come out the same? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
