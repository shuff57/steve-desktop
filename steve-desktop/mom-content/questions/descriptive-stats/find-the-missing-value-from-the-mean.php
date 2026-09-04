// === NAME - DESCRIPTION: Find a Missing Value from the Mean - Turn a known mean back into a total, subtract the values you have to recover the one that is missing, then say what happens to the mean if that value is raised ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Every other mean question in the section runs forwards: values in, mean out. This one runs
// backwards, which is where students discover they were treating the mean as a label rather than as
// a total shared out. The single move is `total = n * mean`, and everything else is subtraction.
//
// The missing value is CHOSEN FIRST and the mean is computed from the finished set, so the mean is
// always exact and the recovered value is always a whole number. Building the set first and hoping
// for a clean mean is what produces a question whose answer is 7.333.
$anstypes = array("number", "number", "number", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "A student has taken six quizzes this term."
  $unitWord = "points"
  $itemWord = "quiz score"
  $itemPlural = "quiz scores"
}
else {
  $intro = "A cyclist recorded the distance of each of her six rides last week."
  $unitWord = "miles"
  $itemWord = "ride distance"
  $itemPlural = "ride distances"
}

$n = 6
$v0 = rand(11, 19)
$v1 = rand(11, 19)
$v2 = rand(11, 19)
$v3 = rand(11, 19)
$v4 = rand(11, 19)
$missing = rand(11, 19)

$total = $v0 + $v1 + $v2 + $v3 + $v4 + $missing
$knownSum = $v0 + $v1 + $v2 + $v3 + $v4

// The mean is only announced to the student if it is exact. Nudging the missing value up or down
// until the total is divisible by 6 keeps it inside the same range and keeps every other value
// untouched, so no seed can produce a mean like 15.1666.
$rem = $total % $n
if ($rem > 0) {
  $missing = $missing + $n - $rem
  $total = $knownSum + $missing
}
$mean = $total / $n

// Part (c): raising the missing value by a fixed amount raises the mean by that amount over n.
$bump = $n * rand(1, 3)
$newMean = ($total + $bump) / $n
$rise = $bump / $n

$answer[0] = $total
$answer[1] = $missing
$answer[2] = $newMean
$abstolerance[2] = 0.005
$answerboxsize = 6

$questions[3] = array(
  "The total rises by " . $bump . ", and that rise is shared over all " . $n . " values, so the mean rises by only " . $rise . ".",
  "The mean rises by " . $bump . ", because changing one value changes the mean by the same amount.",
  "The mean does not change, because only one of the " . $n . " values was altered.",
  "The mean rises by " . $bump . " times " . $n . ", because every value has to absorb the change."
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
      <p><span class="term-label">Step 1: read the mean backwards.</span> `bar x = "total" -: n`, so `"total" = n xx bar x`. With ' . $n . ' ' . $itemPlural . ' averaging ' . $mean . ' ' . $unitWord . ':</p>
      <p style="text-align:center;">`"total" = ' . $n . ' xx ' . $mean . ' = ` <b>' . $total . '</b> ' . $unitWord . '</p>
      <p>This is the whole idea of a mean: it is the number every value WOULD be if the total were shared out equally. Knowing it and knowing `n` is the same as knowing the total.</p>
      <p><span class="term-label">Step 2: subtract what you can see.</span> The five ' . $itemPlural . ' you were given add to `' . $v0 . ' + ' . $v1 . ' + ' . $v2 . ' + ' . $v3 . ' + ' . $v4 . ' = ' . $knownSum . '`. Whatever is left over must be the missing one:</p>
      <p style="text-align:center;">`' . $total . ' - ' . $knownSum . ' = ` <b>' . $missing . '</b> ' . $unitWord . '</p>
      <p><span class="term-label">Step 3: check it.</span> Put ' . $missing . ' back in and recompute: the six values total ' . $total . ', and `' . $total . ' -: ' . $n . ' = ' . $mean . '`. That is the mean you were given, so the answer holds.</p>
      <p><span class="term-label">Part (c): one value moves, the mean moves less.</span> Raising that ' . $itemWord . ' by ' . $bump . ' raises the TOTAL by ' . $bump . ', but the total is still divided among ' . $n . ' values. So the mean rises by `' . $bump . ' -: ' . $n . ' = ' . $rise . '`, giving <b>' . $newMean . '</b>. Expecting the mean to jump by the full ' . $bump . ' is the usual slip: a single value never moves the mean by its own change unless there is only one value.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$intro The mean of all $n $itemPlural is <b>$mean $unitWord</b>, but one of them has been smudged and cannot be read:</p>
    <p style="margin:12px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; font-family:ui-monospace,Menlo,Consolas,monospace; font-size:15px; line-height:1.8;">$v0, $v1, $v2, $v3, $v4, ?</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What must all $n $itemPlural add up to? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the smudged $itemWord? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Suppose that smudged value had been <b>$bump $unitWord higher</b>. What would the mean of the $n $itemPlural be then? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Why did the mean not go up by the full $bump $unitWord? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
