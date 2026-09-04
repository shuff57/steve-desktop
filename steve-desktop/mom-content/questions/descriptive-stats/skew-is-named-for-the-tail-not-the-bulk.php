// === NAME - DESCRIPTION: Skew Is Named for the Tail, Not the Bulk - Diagnose a classmate who named a skewed distribution backwards by looking at where the tall bars sit, and give the rule that fixes it ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// This is the single most reliable error in the section: students name the skew for the side the DATA
// piles up on, which is always the opposite of the right answer. Rather than warn about it, the
// question shows a worked wrong answer and asks the student to find the flaw: the same pre-FRQ move
// that works elsewhere, at question scale.
//
// The drawn shape is always skewed one way or the other, never symmetric, because a symmetric picture
// gives the misconception nothing to bite on.
$anstypes = array("choices", "choices", "number")

$dir = rand(0, 1)

$axisTop = 24
$binWidth = 5
$barCounts = array(0, 0, 0, 0, 0, 0, 0, 0)

if ($dir == 0) {
  // Skewed RIGHT: bulk on the left, thin tail to the right.
  $barCounts[0] = 2 * rand(6, 9)
  $barCounts[1] = 2 * rand(9, 11)
  $barCounts[2] = 2 * rand(6, 9)
  $barCounts[3] = 2 * rand(3, 5)
  $barCounts[4] = 2 * rand(1, 2)
  $barCounts[5] = 2 * rand(1, 2)
  $barCounts[6] = 2 * rand(1, 2)
  $barCounts[7] = 2 * rand(1, 2)
}
else {
  // Skewed LEFT: the mirror image.
  $barCounts[0] = 2 * rand(1, 2)
  $barCounts[1] = 2 * rand(1, 2)
  $barCounts[2] = 2 * rand(1, 2)
  $barCounts[3] = 2 * rand(1, 2)
  $barCounts[4] = 2 * rand(3, 5)
  $barCounts[5] = 2 * rand(6, 9)
  $barCounts[6] = 2 * rand(9, 11)
  $barCounts[7] = 2 * rand(6, 9)
}

$n = 0
for ($i=0..7) {
  $n = $n + $barCounts[$i]
}

// The wrong name a classmate gives is exactly the reverse of the truth.
$trueName = "skewed right"
$wrongName = "skewed left"
$bulkSide = "left"
$tailSide = "right"
if ($dir == 1) {
  $trueName = "skewed left"
  $wrongName = "skewed right"
  $bulkSide = "right"
  $tailSide = "left"
}

$questions[0] = array(
  "It is " . $trueName . ". The name comes from the side the TAIL runs to, and the tail here runs to the " . $tailSide . ".",
  "It is " . $wrongName . ", exactly as the classmate said.",
  "It is roughly symmetric; neither name applies.",
  "Both names are acceptable, since a histogram can be described either way."
)
$answer[0] = 0

$questions[1] = array(
  "They named it for the side the tall bars are on. Skew is named for the thin tail, which is always on the opposite side from the bulk.",
  "They miscounted the bars, so their picture had the peak in the wrong place.",
  "They used the median instead of the mean to decide the direction.",
  "Nothing went wrong; the two names mean the same thing."
)
$answer[1] = 0

// A small arithmetic anchor so the question is not three multiple choices in a row: the tail bars.
$tailCount = $barCounts[4] + $barCounts[5] + $barCounts[6] + $barCounts[7]
if ($dir == 1) {
  $tailCount = $barCounts[0] + $barCounts[1] + $barCounts[2] + $barCounts[3]
}
$answer[2] = $tailCount
$answerboxsize = 5

// --- Bar geometry, shared with the other 2.6 histograms.
$plotL = 55
$plotB = 260
$plotT = 25
$barW = 50
$unitPx = ($plotB - $plotT) / $axisTop

$grid = ""
for ($g=0..12) {
  $v = 2 * $g
  $gy = round($plotB - $v * $unitPx, 2)
  $grid = $grid . '<line x1="55" y1="' . $gy . '" x2="455" y2="' . $gy . '" stroke="#e5e7eb" stroke-width="1"/>'
  $grid = $grid . '<text x="48" y="' . ($gy + 4) . '" font-size="11" fill="#6b7280" text-anchor="end">' . $v . '</text>'
}

$xlabels = ""
for ($k=0..8) {
  $xp = $plotL + $k * $barW
  $xval = $binWidth * $k
  $xlabels = $xlabels . '<line x1="' . $xp . '" y1="260" x2="' . $xp . '" y2="265" stroke="#374151" stroke-width="1"/>'
  $xlabels = $xlabels . '<text x="' . $xp . '" y="280" font-size="11" fill="#374151" text-anchor="middle">' . $xval . '</text>'
}

$bars = ""
for ($k=0..7) {
  $bx = $plotL + $k * $barW
  $bh = round($barCounts[$k] * $unitPx, 2)
  $by = round($plotB - $bh, 2)
  $bars = $bars . '<rect x="' . $bx . '" y="' . $by . '" width="' . $barW . '" height="' . $bh . '" fill="#93c5fd" stroke="#1e40af" stroke-width="1.5"/>'
}

$svgOpen = '<svg viewBox="0 0 500 320" width="100%" style="max-width:500px; display:block; margin:10px auto; background:#fff;" xmlns="http://www.w3.org/2000/svg" role="img">'
$axes = '<line x1="55" y1="25" x2="55" y2="260" stroke="#374151" stroke-width="2"/><line x1="55" y1="260" x2="455" y2="260" stroke="#374151" stroke-width="2"/><text x="255" y="303" font-size="13" fill="#374151" text-anchor="middle">Wait time in minutes</text><text x="16" y="145" font-size="13" fill="#374151" text-anchor="middle" transform="rotate(-90 16 145)">Frequency</text>'
$hist = $svgOpen . $grid . $bars . $axes . $xlabels . '</svg>'

$halfLo = 4 * $binWidth
$halfHi = 8 * $binWidth
$tailRange = $halfLo . " to " . $halfHi
if ($dir == 1) {
  $tailRange = "0 to " . $halfLo
}

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
      <p><span class="term-label">Part (a): the right name.</span> The tall bars sit on the ' . $bulkSide . ', and the bars thin out into a long low tail on the ' . $tailSide . '. Skew is named for the tail, so this is <b>' . $trueName . '</b>.</p>
      <p><span class="term-label">Part (b): what the classmate did.</span> They looked at where the data PILES UP and named that side. That reading is always exactly backwards, because the bulk and the tail are on opposite sides by definition. Saying "' . $wrongName . '" is not a small slip; it reverses the conclusion, and with it the prediction about whether the mean sits above or below the median.</p>
      <p><span class="term-label">The rule that fixes it for good.</span> Point at the thin end, the side where the bars run out low and long, and read the direction off your finger. Bulk-on-the-' . $bulkSide . ' and tail-to-the-' . $tailSide . ' are the same picture; only the tail names it.</p>
      <p><span class="term-label">Part (c): how little is actually in the tail.</span> The four classes making up the tail, from ' . $tailRange . ' minutes, hold <b>' . $tailCount . '</b> of the ' . $n . ' observations between them. A tail can be long and still be nearly empty: which is exactly why it is easy to overlook, and why the mean it drags can end up describing almost nobody.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">A clinic recorded the wait time of each of $n patients.</p>
    $hist
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; border-left:4px solid #b91c1c; background:#fef7f7;">
    <p style="margin:0;"><b>A classmate writes:</b> &ldquo;Most of the bars are bunched up on the $bulkSide, so the distribution is <b>$wrongName</b>.&rdquo;</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the shape actually called? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Where exactly did the classmate's reasoning go wrong? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> How many of the $n patients fall in the four classes that make up the <b>tail</b>, from $tailRange minutes? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
