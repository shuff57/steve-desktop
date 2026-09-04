// === NAME - DESCRIPTION: Median Age Vs Life Expectancy - Explain why one town's lower median age reflects a younger population structure (birth rate), not a shorter lifespan, even under equal life expectancy ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices", "choices")

// Two neutral town names stand in for the two groups being compared, so the reasoning stays about
// population structure and never about any real demographic group. Both which pair is drawn and
// which town in the pair gets the lower median age are randomized.
$townPairs = array(
  array("Northgate", "Cedar Falls"),
  array("Riverside", "Fairview"),
  array("Elm Hollow", "Brookline")
)
$tpi = rand(0, 2)
$swap = rand(0, 1)
$townA = $townPairs[$tpi][0]
$townB = $townPairs[$tpi][1]
$lowTown = $townA
$highTown = $townB
if ($swap == 1) { $lowTown = $townB }
if ($swap == 1) { $highTown = $townA }

// highMedian is built as an offset from lowMedian, so the gap between the two towns is bounded and
// never collapses or overlaps no matter what the two rand() calls draw.
$lowMedian = rand(28, 33)
$highMedian = $lowMedian + rand(9, 14)

$questions[0] = array(
  $lowTown . "'s population includes proportionally more young residents than " . $highTown . "'s, for example, from a higher birth rate or an influx of young adults, which pulls its median age down.",
  "On average, people in " . $lowTown . " die younger than people in " . $highTown . ".",
  $lowTown . " was founded more recently, so its median age carries no real meaning.",
  "The two reported medians are close enough that the difference is just measurement error."
)
$answer[0] = 0

$questions[1] = array(
  "No. Median age describes the age structure of the population currently living there, how many young residents there are relative to old, not how long any individual is expected to live.",
  "Yes. A lower median age is direct evidence that people there live shorter lives.",
  "Yes, because median age and life expectancy are calculated from the same data.",
  "It cannot be determined without also knowing each town's average income."
)
$answer[1] = 0

$questions[2] = array(
  "If " . $lowTown . " has a higher birth rate (or more young residents moving in), a larger share of its population is young, which lowers its median age even though people in both towns live equally long on average.",
  "It isn't possible: equal life expectancy always produces equal median ages.",
  $highTown . " must have both a lower birth rate and a shorter life expectancy, and the two effects cancel out.",
  "The median age formula counts older residents twice in towns with more retirees."
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
      <p><span class="term-label">Median age measures a snapshot, not a lifetime.</span> The median age of a living population is the age that splits today\'s residents in half: it is set by how many people of each age are alive right now, which is mostly a matter of birth rates and migration. It says nothing directly about how long any of those people will go on to live.</p>
      <p><span class="term-label">Part (a): why ' . $lowTown . '\'s median age (' . $lowMedian . ') is lower than ' . $highTown . '\'s (' . $highMedian . ').</span> A younger population age structure, more births per year, or more young adults arriving, adds people at the bottom of the age distribution. That shifts the halfway point down. "People there die younger" explains a lower <i>life expectancy</i>, not a lower median age, and the two are easy to mix up but measure different things.</p>
      <p><span class="term-label">Part (b): does the lower median age prove a shorter lifespan?</span> No. A population can be young because it is growing quickly, not because it is dying early. You would need actual mortality or life-expectancy data to say anything about lifespan: the median age alone does not supply it.</p>
      <p><span class="term-label">Part (c): same life expectancy, different median age.</span> Suppose everyone in both towns lives, on average, to the same age. ' . $lowTown . ' can still have the lower median age simply by having more young residents relative to its older ones: a higher birth rate stretches the bottom of the age distribution without touching how long anyone actually lives. Age structure and longevity are different quantities, and one can move while the other stays fixed.</p>
      <p><b>Answer:</b> (a) younger age structure / higher birth rate &nbsp;&nbsp; (b) no, median age is not lifespan &nbsp;&nbsp; (c) a higher birth rate lowers the median age even at equal life expectancy</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">This year, the median age of residents in $lowTown is $lowMedian years; in $highTown it is $highMedian years.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which explanation could account for $lowTown's lower median age? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Does $lowTown's lower median age necessarily mean that people there have shorter lifespans than people in $highTown? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Suppose people in $lowTown and $highTown actually live equally long, on average. How could $highTown still end up with the higher median age? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
