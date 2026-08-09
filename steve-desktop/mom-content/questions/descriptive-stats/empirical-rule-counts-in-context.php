// === NAME - DESCRIPTION: Turn the Empirical Rule into Head Counts - Find the interval one standard deviation either side of the mean, convert the 68-95-99.7 percentages into numbers of individuals, and say what the rule requires before it may be used ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The bank already applies the empirical rule to get PERCENTAGES. Turning a percentage back into a
// count is the step that gets skipped, and it is the one a real question asks for -- "how many
// students", not "what percent". The last part guards the rule itself: 68-95-99.7 is a statement
// about a BELL-SHAPED distribution and says nothing about a skewed one.
//
// The group size is a multiple of 1000 so 68%, 95% and 99.7% all give whole numbers of individuals,
// and no part needs a rounding instruction that would obscure the method.
$anstypes = array("number", "number", "number", "number", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "The heights of a large group of adult men are roughly bell shaped."
  $unitWord = "centimeters"
  $who = "men"
  $thing = "height"
}
else {
  $intro = "The scores on a national aptitude test are roughly bell shaped."
  $unitWord = "points"
  $who = "candidates"
  $thing = "score"
}

$mu = 10 * rand(15, 24)
$sigma = 5 * rand(1, 3)
$n = 1000 * rand(2, 8)

$loOne = $mu - $sigma
$hiOne = $mu + $sigma
$hiTwo = $mu + 2 * $sigma

$cnt68 = $n * 68 / 100
$cnt95 = $n * 95 / 100

$answer[0] = $loOne
$answer[1] = $hiOne
$answer[2] = $cnt68
$answer[3] = $cnt95
$answerboxsize = 7

$questions[4] = array(
  "Nothing at all can be concluded. The 68-95-99.7 rule only applies to a distribution that is roughly bell shaped, and a strongly skewed one is not.",
  "The same percentages apply, because the rule holds for every distribution.",
  "The percentages apply, but the intervals have to be measured from the median instead of the mean.",
  "Only the 68% part still applies; the 95% and 99.7% parts do not."
)
$answer[4] = 0

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
      <p><span class="term-label">The rule, in one line.</span> For a roughly bell-shaped distribution, about <b>68%</b> of the values lie within one standard deviation of the mean, about <b>95%</b> within two, and about <b>99.7%</b> within three.</p>
      <p><span class="term-label">Parts (a) and (b) &mdash; the one-deviation interval.</span> Step one deviation out on each side of the mean:</p>
      <p style="text-align:center;">`' . $mu . ' - ' . $sigma . ' = ` <b>' . $loOne . '</b> &nbsp;&nbsp; to &nbsp;&nbsp; `' . $mu . ' + ' . $sigma . ' = ` <b>' . $hiOne . '</b> ' . $unitWord . '</p>
      <p>Note that the interval is symmetric about the mean &mdash; the rule always describes a band centered on `mu`, never a one-sided range.</p>
      <p><span class="term-label">Part (c) &mdash; percentage to head count.</span> 68% of the ' . $n . ' ' . $who . ':</p>
      <p style="text-align:center;">`0.68 xx ' . $n . ' = ` <b>' . $cnt68 . '</b> ' . $who . '</p>
      <p>This is the step usually left out. The rule gives a proportion; the question asks for people, so it has to be multiplied by the group size.</p>
      <p><span class="term-label">Part (d) &mdash; two deviations.</span> The band now runs from ' . ($mu - 2 * $sigma) . ' to ' . $hiTwo . ' ' . $unitWord . ', and holds about 95%:</p>
      <p style="text-align:center;">`0.95 xx ' . $n . ' = ` <b>' . $cnt95 . '</b> ' . $who . '</p>
      <p><span class="term-label">Part (e) &mdash; when the rule does not apply.</span> 68-95-99.7 is a fact about the BELL SHAPE, not about means and standard deviations in general. On a strongly skewed distribution the percentages are simply wrong, and no adjustment rescues them &mdash; you would need the actual distribution. This is why the shape is checked before the rule is used, and why every question that invokes it says "roughly bell shaped" somewhere in the setup. If that phrase is missing, the rule is not available.</p>
      <p><span class="term-label">A quick sanity check.</span> The bands are nested, so the two-deviation count must always exceed the one-deviation count, and neither can exceed the group size.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$intro The group contains <b>$n $who</b>, with mean <b>$mu $unitWord</b> and standard deviation <b>$sigma $unitWord</b>.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> One standard deviation <b>below</b> the mean is what $thing? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> One standard deviation <b>above</b> the mean is what $thing? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> About <b>how many of the $n $who</b> have a $thing between those two values? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> About <b>how many</b> lie within <b>two</b> standard deviations of the mean? $answerbox[3]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">e.</span> A different group has the same mean and standard deviation but is <b>strongly skewed</b>. What can the rule tell you about it? $answerbox[4]
  </div>
</div>

// === ANSWER ===

$solutionguide
