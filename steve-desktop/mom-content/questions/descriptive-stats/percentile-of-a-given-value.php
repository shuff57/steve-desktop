// === NAME - DESCRIPTION: Find the Percentile of a Given Value - Work the percentile formula in reverse with (x + 0.5y)/n times 100, counting the values below and the values equal to the one named, including a value that appears more than once ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "choices")

$ci = rand(0, 1)
$contexts = array(
  "the ages, in years, of the winners of a national film award",
  "the weights, in pounds, of the dogs registered at one veterinary clinic"
)
$unitWords = array("years", "pounds")
$context = $contexts[$ci]
$unitWord = $unitWords[$ci]

// The list is built so that one asked-about value appears TWICE and the other appears once. The
// repeated one is the point: y is the count of values EQUAL to it, and a student who assumes y = 1
// gets a plausible wrong answer that no render check would catch.
$n = 30
$vals = array()
$v = rand(14, 20)
for ($j=0..29) {
  $vals[$j] = $v
  $v = $v + rand(1, 4)
}

// Force a duplicate at a position clear of both ends, then rebuild the printed list from the array.
$dupAt = rand(8, 20)
$vals[$dupAt] = $vals[$dupAt - 1]

$valueList = ""
for ($j=0..29) {
  if ($j == 0) { $valueList = "" . $vals[$j] }
  if ($j > 0) { $valueList = $valueList . "; " . $vals[$j] }
}

// Part (a) asks about the repeated value; part (b) about one that occurs once.
$aVal = $vals[$dupAt]
$bIdx = rand(22, 28)
$bVal = $vals[$bIdx]

// Count below and equal by walking the list, never by assuming a position.
$aBelow = 0
$aEqual = 0
$bBelow = 0
$bEqual = 0
for ($j=0..29) {
  if ($vals[$j] < $aVal) { $aBelow = $aBelow + 1 }
  if ($vals[$j] == $aVal) { $aEqual = $aEqual + 1 }
  if ($vals[$j] < $bVal) { $bBelow = $bBelow + 1 }
  if ($vals[$j] == $bVal) { $bEqual = $bEqual + 1 }
}

$aRaw = ($aBelow + 0.5 * $aEqual) / $n * 100
$bRaw = ($bBelow + 0.5 * $bEqual) / $n * 100
$aPct = round($aRaw, 0)
$bPct = round($bRaw, 0)
$aRawShown = round($aRaw, 2)
$bRawShown = round($bRaw, 2)

$answer[0] = $aPct
$answerformat[0] = "integer"

$answer[1] = $bPct
$answerformat[1] = "integer"

$questions[2] = array(
  "It is the count of values equal to the one being ranked, and it is halved so that a repeated value sits in the middle of the positions it occupies rather than at one end.",
  "It is the count of values equal to the one being ranked, and it is halved to correct for rounding.",
  "It is the count of values above the one being ranked, halved to keep the answer under 100.",
  "It is always 1, because a value is equal to itself."
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
      <p><span class="term-label">The rule, and it is the other one.</span> Finding a percentile from a rank uses `i = (k/100)(n+1)`. Going the other way, you are handed a value and asked where it sits, uses `(x + 0.5y)/n xx 100`, where `x` counts the values strictly below it, `y` counts the values equal to it, and `n = ' . $n . '`.</p>
      <p><span class="term-label">Part (a): a value that appears twice.</span> Counting from the bottom, <b>' . $aBelow . '</b> values are below ' . $aVal . ' and <b>' . $aEqual . '</b> are equal to it. So `(' . $aBelow . ' + 0.5(' . $aEqual . '))/' . $n . ' xx 100 = ' . $aRawShown . '`, which rounds to the <b>' . $aPct . 'th percentile</b>. Taking `y = 1` here because it is "one value" is the usual slip; ' . $aVal . ' occupies two positions in the list.</p>
      <p><span class="term-label">Part (b): a value that appears once.</span> ' . $bBelow . ' values are below ' . $bVal . ' and ' . $bEqual . ' equals it: `(' . $bBelow . ' + 0.5(' . $bEqual . '))/' . $n . ' xx 100 = ' . $bRawShown . '`, so the <b>' . $bPct . 'th percentile</b>.</p>
      <p><span class="term-label">Part (c): why the half.</span> A repeated value fills several positions at once. Counting all of them would push it too high and counting none too low, so half of them is the honest middle. With one occurrence the half adds 0.5 to a count of ' . $n . ': small, but it is the same rule.</p>
      <p><b>Answer:</b> (a) ' . $aPct . 'th percentile &nbsp;&nbsp; (b) ' . $bPct . 'th percentile</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 12px 0;">Listed below, in order, are $context. There are $n values.</p>
    <p style="margin:0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; font-family:ui-monospace,Menlo,Consolas,monospace; font-size:15px; line-height:1.8;">$valueList</p>
    <p style="margin:12px 0 0 0; font-size:15px; color:#444;">To find the percentile of a given value, use `(x + 0.5y)/n xx 100`, where `x` is how many values are below it and `y` is how many are equal to it. Round each answer to the nearest whole percentile.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the percentile of <b>$aVal $unitWord</b>. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find the percentile of <b>$bVal $unitWord</b>. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> In the formula, what is `y`, and why is it halved? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
