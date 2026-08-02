// === NAME - DESCRIPTION: Fitness Center Key Terms - Match population, sample, parameter, statistic, variable, and data to their descriptions in a study about mean client exercise time ===
// === SET QUESTION TYPE TO: matching ===

// === COMMON CONTROL ===

// Randomize the business type and the time unit.
$businesses = array("fitness center", "gym", "health club", "recreation center", "wellness studio")
$units = array("weekly", "daily", "monthly")

$business = $businesses[rand(0, count($businesses)-1)]
$unit = $units[rand(0, count($units)-1)]

// The six terms stay in teaching order — population before sample, parameter before statistic —
// so the list reads as the definition sequence rather than a scramble.
$questions = array("Population", "Sample", "Parameter", "Statistic", "Variable", "Data")

// One description per term, in the same order, then two distractors. With six terms and exactly
// six descriptions the last answer is free by elimination; the extras take that away.
$answers = array(
  "all clients of the {$business}",
  "the clients whose exercise time was recorded",
  "the mean {$unit} exercise time for ALL clients",
  "the mean {$unit} exercise time for the clients in the SAMPLE",
  "the amount of time one client exercises {$unit}",
  "the recorded times, such as 2 hours, 5 hours, 0 hours",
  "the number of clients who exercised at all",
  "everyone living near the {$business}, member or not"
)

// Spelled out rather than left to the default, because the two distractors make the answer list
// longer than the question list and the mapping must stay pinned to the first six.
$matchlist = "0,1,2,3,4,5"

$questiontitle = "Key term"
$answertitle = "Description"
// Keep the terms in order and shuffle only the descriptions: the point is recognising which
// description fits, not remembering where it sat last time.
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
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p>The study wants the <b>mean</b> amount of time clients of a ' . $business . ' exercise ' . $unit . '.</p>
      <div class="term-row"><span class="term-label">Population:</span> all clients of the ' . $business . '</div>
      <div class="term-row"><span class="term-label">Sample:</span> the clients whose exercise time is actually recorded</div>
      <div class="term-row"><span class="term-label">Parameter:</span> the mean ' . $unit . ' exercise time for <em>all</em> clients</div>
      <div class="term-row"><span class="term-label">Statistic:</span> the mean ' . $unit . ' exercise time for the clients in the sample</div>
      <div class="term-row"><span class="term-label">Variable:</span> the amount of time one client exercises ' . $unit . '</div>
      <div class="term-row"><span class="term-label">Data:</span> the recorded time values, such as 2 hours, 5 hours, 0 hours</div>
      <div class="sol-note">Two of the descriptions are not used. <b>The number</b> of clients who exercised is a count, not a mean &mdash; the parameter and the statistic here both have to be mean times. And <b>everyone living nearby</b> is a wider group than the clients this study is about.</div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A <b>$business</b> is interested in the <b>mean amount of time</b> a client exercises in the center <b>$unit</b>. The manager records the exercise time of a group of clients.</p>
    <p style="margin:12px 0 0 0;">Match each key term to the description that fits this study. <b>Two descriptions are not used.</b></p>
  </div>
</div>


// === ANSWER ===

$solutionguide
