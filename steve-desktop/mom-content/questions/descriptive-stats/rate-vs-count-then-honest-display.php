// === NAME - DESCRIPTION: Rate Versus Raw Count, Then Honest Display - From two stores' complaint counts and differing customer counts, compute each rate per 100 customers, then choose the display that compares them honestly on one shared labeled scale ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "choices")

$storePairs = array(
  array("Riverside Grocers", "Corner Market"),
  array("Maple Street Diner", "Downtown Bistro"),
  array("Northside Pharmacy", "Village Drugstore"),
  array("Lakeside Hardware", "Main Street Hardware"),
  array("Sunrise Bakery", "Corner Bakery")
)
$si = rand(0, 4)
$storeA = $storePairs[$si][0]
$storeB = $storePairs[$si][1]

$customersA = rand(3000, 6000)
$customersB = rand(600, 1500)

$rateATargetHundredths = rand(80, 150)
$rateBTargetHundredths = rand(180, 320)

$complaintsA = round($customersA * $rateATargetHundredths / 10000, 0)
$complaintsB = round($customersB * $rateBTargetHundredths / 10000, 0)

$rateA = round($complaintsA / $customersA * 100, 2)
$rateB = round($complaintsB / $customersB * 100, 2)

$answer[0] = $rateA
$answer[1] = $rateB
$abstolerance[0] = 0.01
$abstolerance[1] = 0.01

$choices[2] = array(
  "A bar chart with one bar for " . $storeA . " and one bar for " . $storeB . ", each bar showing the complaint rate per 100 customers, both plotted on one shared, numbered vertical scale.",
  "A bar chart with one bar for " . $storeA . " and one bar for " . $storeB . ", each bar showing the raw number of complaints, both plotted on one shared, numbered vertical scale.",
  "Two separate bar charts, one for " . $storeA . " and one for " . $storeB . ", each drawn with its own vertical scale sized to fit that store's numbers.",
  "A pie chart showing what percent of the two stores' combined complaints came from " . $storeA . " versus " . $storeB . "."
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
      <p><span class="term-label">Step 1 &mdash; Read the raw numbers.</span> ' . $storeA . ' recorded ' . $complaintsA . ' complaints out of ' . $customersA . ' customers served that week. ' . $storeB . ' recorded ' . $complaintsB . ' complaints out of ' . $customersB . ' customers served. The two stores serve very different numbers of customers, so the raw complaint counts alone cannot be compared directly.</p>
      <p><span class="term-label">Step 2 &mdash; Convert each count to a rate.</span> A rate puts both stores on the same footing: complaints per 100 customers. For ' . $storeA . ': ' . $complaintsA . ' / ' . $customersA . ' &times; 100 = ' . $rateA . ' complaints per 100 customers. For ' . $storeB . ': ' . $complaintsB . ' / ' . $customersB . ' &times; 100 = ' . $rateB . ' complaints per 100 customers.</p>
      <p><span class="term-label">Step 3 &mdash; Compare the rates, not the counts.</span> ' . $storeB . '\'s rate (' . $rateB . ' per 100 customers) is higher than ' . $storeA . '\'s rate (' . $rateA . ' per 100 customers), even though the raw counts alone might have suggested a different story. The rate is what actually reflects how often a customer at each store complains.</p>
      <p><span class="term-label">Step 4 &mdash; Choose the honest display.</span> A display is only fair if it shows the rate &mdash; not the raw count, which mostly reflects store size &mdash; and if both stores sit on one shared, numbered scale. Two separately-scaled graphs let each store\'s bar be drawn to whatever height fits its own picture, hiding the true comparison just as an unlabeled axis would.</p>
      <p><b>Answer:</b> (a) ' . $storeA . '\'s rate is ' . $rateA . ' complaints per 100 customers; ' . $storeB . '\'s rate is ' . $rateB . ' complaints per 100 customers. (b) The honest display is a bar chart of the two rates, both plotted on one shared, numbered scale.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Last week, $storeA recorded $complaintsA customer complaints out of $customersA customers served. In the same week, $storeB recorded $complaintsB customer complaints out of $customersB customers served. The two stores serve very different numbers of customers each week.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Compute each store's complaint rate per 100 customers, rounded to two decimal places.
    <p style="margin:10px 0 4px 0;">$storeA's rate: $answerbox[0] complaints per 100 customers</p>
    <p style="margin:4px 0 0 0;">$storeB's rate: $answerbox[1] complaints per 100 customers</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Using the rates you just computed, which display would most honestly represent the comparison between $storeA and $storeB? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
