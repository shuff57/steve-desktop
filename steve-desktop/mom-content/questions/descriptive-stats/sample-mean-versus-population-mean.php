// === NAME - DESCRIPTION: Sample Mean Versus Population Mean - Compute the mean of a whole group and the mean of a sample drawn from it, attach the right symbol to each, and say which one is a parameter and which is a statistic ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The arithmetic is identical for both means -- that IS the lesson. What separates them is which
// group the numbers came from, so the question hands over one small population, marks a subset of
// it as the sample, and asks for both means side by side. Students who think the formula changes
// discover that only the label does.
//
// The population is 10 values and the sample is 4 of them, chosen as a contiguous block so the
// prompt can point at them unambiguously without a second list to mis-copy. Both totals are forced
// divisible by their counts, so neither mean needs rounding and the two are never equal.
$anstypes = array("number", "number", "choices", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $popWord = "all ten machines on the shop floor"
  $sampWord = "the four machines the inspector actually tested"
  $unitWord = "minutes"
  $measure = "the setup time"
}
else {
  $popWord = "all ten trees in the orchard row"
  $sampWord = "the four trees the grower actually picked from"
  $unitWord = "kilograms"
  $measure = "the yield"
}

$b0 = rand(20, 26)
$b1 = rand(20, 26)
$b2 = rand(20, 26)
$b3 = rand(20, 26)
$b4 = rand(20, 26)
$b5 = rand(20, 26)
$b6 = rand(20, 26)
$b7 = rand(20, 26)
$b8 = rand(20, 26)
$b9 = rand(20, 26)

// The sample is the first four. Nudge one NON-sample value to clear the population remainder and
// one sample value to clear the sample remainder, in that order, so neither fix undoes the other.
$sampSum = $b0 + $b1 + $b2 + $b3
$sRem = $sampSum % 4
if ($sRem > 0) {
  $b0 = $b0 + 4 - $sRem
  $sampSum = $b0 + $b1 + $b2 + $b3
}
$popSum = $sampSum + $b4 + $b5 + $b6 + $b7 + $b8 + $b9
$pRem = $popSum % 10
if ($pRem > 0) {
  $b9 = $b9 + 10 - $pRem
  $popSum = $sampSum + $b4 + $b5 + $b6 + $b7 + $b8 + $b9
}

$popMean = $popSum / 10
$sampMean = $sampSum / 4

$answer[0] = $popMean
$abstolerance[0] = 0.005
$answer[1] = $sampMean
$abstolerance[1] = 0.005
$answerboxsize = 6

$questions[2] = array(
  "`mu` for the mean of all ten, and `bar x` for the mean of the four that were measured.",
  "`bar x` for the mean of all ten, and `mu` for the mean of the four that were measured.",
  "`mu` for both, because the arithmetic is the same either way.",
  "`bar x` for both, because both were calculated from data that someone recorded."
)
$answer[2] = 0

$questions[3] = array(
  "The mean of all ten is a PARAMETER, because it describes the whole group. The mean of the four is a STATISTIC, because it describes a sample drawn from that group.",
  "The mean of the four is the parameter, because it is the one actually measured.",
  "Both are parameters, because both were computed from real measurements.",
  "Neither is a parameter until the whole population has been measured a second time."
)
$answer[3] = 0

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
      <p><span class="term-label">Both means, same arithmetic.</span> Add the values, divide by how many there are. Nothing about the calculation changes between a population and a sample.</p>
      <p>All ten: `' . $popSum . ' -: 10 = ` <b>' . $popMean . '</b> ' . $unitWord . '.<br>
         The four measured: `' . $sampSum . ' -: 4 = ` <b>' . $sampMean . '</b> ' . $unitWord . '.</p>
      <p><span class="term-label">What the symbols record.</span> `mu` is the mean of a POPULATION &mdash; every member of the group you are asking about. `bar x` is the mean of a SAMPLE &mdash; the part of it you actually measured. Here `mu = ' . $popMean . '` and `bar x = ' . $sampMean . '`. The symbol is not decoration: it tells a reader which group the number describes, which is the only thing separating these two answers.</p>
      <p><span class="term-label">Parameter or statistic.</span> A number describing a whole population is a <b>parameter</b>; a number describing a sample is a <b>statistic</b>. So ' . $popMean . ' is a parameter and ' . $sampMean . ' is a statistic. The words follow the group, not the arithmetic.</p>
      <p><span class="term-label">Why the two differ.</span> The four measured items are not a perfect miniature of all ten, so their mean lands near the population mean but not on it. That gap is sampling variability, and almost everything later in the course exists to measure it. Notice that here you could check the gap because the whole population was listed &mdash; in real work you only ever see `bar x`, which is exactly why `mu` has to be estimated rather than looked up.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">An inspector is interested in $measure, in $unitWord, of $popWord. All ten values are listed below. The <b>first four</b>, shown in bold, are $sampWord.</p>
    <p style="margin:12px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; font-family:ui-monospace,Menlo,Consolas,monospace; font-size:15px; line-height:1.8;"><b>$b0, $b1, $b2, $b3</b>, $b4, $b5, $b6, $b7, $b8, $b9</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the mean of <b>all ten</b> values. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find the mean of <b>the four</b> that were measured. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which symbol belongs to which mean? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> One of these numbers is a parameter and the other is a statistic. Which is which? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
