// === NAME - DESCRIPTION: Raw Count Versus a Fair Rate - Airline Complaints - Given a bar chart of raw complaint counts for six randomized airlines of implied different size, decide that a raw count alone cannot support a claim about which carriers are worst, then name the rate needed to make the comparison fair ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "essay")

$scenarios = array(
  array("Continental Skyways", "Meridian Air", "Union Pacific Airlines", "Alpine Regional Air", "Coastal Hopper Airlines", "Prairie Wing Airlines"),
  array("Falcon National Airlines", "Horizon Skyways", "Liberty Air", "Bluegrass Regional Air", "Tidewater Airlines", "Summit Hopper Air"),
  array("Crestline National Air", "Vantage Airlines", "Sky Union Air", "Cascade Regional Air", "Gulfstream Hopper", "Timberline Air"),
  array("Patriot Airlines", "Meridian Skyways", "National Crestline Air", "Ridgeline Regional Air", "Bayshore Hopper", "Sierra Wing Air"),
  array("Continental Vista Air", "Union Skyways", "Liberty National Air", "Foothill Regional Air", "Harbor Hopper Airlines", "Pineridge Air")
)
$si = rand(0, 4)
$bigA = $scenarios[$si][0]
$bigB = $scenarios[$si][1]
$bigC = $scenarios[$si][2]
$smallA = $scenarios[$si][3]
$smallB = $scenarios[$si][4]
$smallC = $scenarios[$si][5]

$cBigA = rand(140, 210)
$cBigB = rand(95, 139)
$cBigC = rand(55, 94)
$cSmallA = rand(4, 9)
$cSmallB = rand(4, 10)
$cSmallC = rand(3, 8)

$monthYears = array("February 2013", "March 2016", "October 2018", "June 2021", "January 2023")
$myi = rand(0, 4)
$monthYear = $monthYears[$myi]

$bigList = $bigA . ", " . $bigB . ", and " . $bigC
$smallList = $smallA . ", " . $smallB . ", and " . $smallC

$barNames = array($bigA, $bigB, $bigC, $smallA, $smallB, $smallC)
$barCounts = array($cBigA, $cBigB, $cBigC, $cSmallA, $cSmallB, $cSmallC)

$maxCount = $barCounts[0]
for ($i=1..5) {
  if ($barCounts[$i] > $maxCount) {
    $maxCount = $barCounts[$i]
  }
}

$barMaxWidth = 260
$barsHtml = ''
for ($i=0..5) {
  $w = round($barCounts[$i] / $maxCount * $barMaxWidth, 1)
  $barColor = '#1865f2'
  if ($i > 2) {
    $barColor = '#9aa7bd'
  }
  $barsHtml = $barsHtml . '<div style="display:flex; align-items:center; margin:6px 0;"><div style="width:180px; font-size:14px; color:#21242c; text-align:right; padding-right:10px;">' . $barNames[$i] . '</div><div style="background:' . $barColor . '; height:22px; border-radius:4px; width:' . $w . 'px;"></div><div style="padding-left:8px; font-size:14px; font-weight:700; color:#21242c;">' . $barCounts[$i] . '</div></div>'
}

$choices[0] = array(
  "No &mdash; a complaint count is a raw total with no denominator. Without knowing how many passengers each airline carried in " . $monthYear . ", the higher totals for " . $bigList . " may simply reflect that these are larger airlines, not that they treat passengers worse.",
  "Yes &mdash; the airlines with the most complaints filed against them are, by definition, the ones passengers are least satisfied with.",
  "No &mdash; but only because six airlines is too small a group to ever draw a conclusion about which carriers are worst.",
  "Yes &mdash; since every complaint went to the same regulator, the six counts are already measured on the same footing and can be compared directly."
)
$answer[0] = 0

$answer[1] = ""
$scoremethod[1] = "takeanything"
$displayformat[1] = "editornopaste"
$answerboxsize[1] = "5,60"

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
      <p><span class="term-label">Step 1 &mdash; Read the chart.</span> The chart shows raw complaint counts: ' . $cBigA . ' for ' . $bigA . ', ' . $cBigB . ' for ' . $bigB . ', and ' . $cBigC . ' for ' . $bigC . ', against only ' . $cSmallA . ', ' . $cSmallB . ', and ' . $cSmallC . ' for ' . $smallA . ', ' . $smallB . ', and ' . $smallC . '.</p>
      <p><span class="term-label">Step 2 &mdash; Ask what the counts are missing.</span> A complaint count is a numerator with no denominator. ' . $bigList . ' carry far more passengers each month than ' . $smallList . '. Even if all six airlines annoyed exactly the same <i>share</i> of their passengers, the larger carriers would still generate more complaints in total, simply because they flew more people.</p>
      <p><span class="term-label">Step 3 &mdash; Say what would settle the question.</span> The comparable figure is a <b>rate</b>: complaints per, say, 100,000 passengers boarded, over the same period. Only a rate puts all six airlines on the same footing.</p>
      <p><span class="term-label">Step 4 &mdash; Note a second problem.</span> Complaints are also self-reported, so differences in how easy each airline makes it to file one, and in what its passengers expect, affect the count without saying anything about service quality on their own.</p>
      <p><b>Answer:</b> (a) No. The chart shows raw counts, not rates, so it mostly measures how many passengers each airline carries. Without complaints per passenger, we cannot conclude that ' . $bigList . ' are the worst carriers. (b) The missing piece is each airline&rsquo;s passenger volume for the same period, so the counts can be converted into a rate &mdash; complaints per passengers carried &mdash; instead of being compared as raw totals.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">The chart below shows the number of complaints reported to a national aviation regulator in $monthYear, for six different airlines. $bigList have far more complaints reported than $smallA, $smallB, and $smallC.</p>
    $barsHtml
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Can we conclude that $bigList are the worst airline carriers, since they have the most complaints reported? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What additional piece of information about each airline would you need to make a fair comparison between the six carriers? Explain why that piece of information would settle the question. $answerbox[1]
  </div>
</div>

// === ANSWER ===

$solutionguide
