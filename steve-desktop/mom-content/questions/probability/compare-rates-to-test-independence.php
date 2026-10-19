// === NAME - DESCRIPTION: Independence from Two Rates - Find the rate in each of two groups and decide from the rates whether the group and the outcome are independent ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 3.4. Independence from two rates. Both groups are built from a common fraction m/q, so on
// an independent seed the two rates are exactly equal. On the other seeds group 2 is moved a full
// step of 1/q away, so the rates always differ by at least 0.1 and the verdict is never close.
$anstypes = array("numfunc", "numfunc", "choices")

$i = rand(0, 3)
$g1s = array("first-year students", "morning shift workers", "grade 9 students", "players in the junior league")
$g2s = array("second-year students", "evening shift workers", "grade 10 students", "players in the senior league")
$does = array("own a bike", "use the new software", "ride the bus", "wear a mouth guard")
$g1 = $g1s[$i]
$g2 = $g2s[$i]
$doing = $does[$i]

$q = randfrom(array(4, 5, 10))
$m = rand(2, $q - 2)
$sizes = diffrands(2, 6, 2)
$s1 = $sizes[0]
$s2 = $sizes[1]
$n1 = $q * $s1
$n2 = $q * $s2
$x1 = $m * $s1

$ind = rand(0, 1)
$step = 2 * rand(0, 1) - 1
if ($ind == 1) {
  $x2 = $m * $s2
} else {
  $x2 = ($m + $step) * $s2
}

$r1 = $x1 / $n1
$r2 = $x2 / $n2

$questions[2] = array(
  "Independent, because the two rates are equal",
  "Not independent, because the two rates are different",
  "Independent, because both groups have some who " . $doing,
  "Not independent, because the groups have different sizes"
)
if ($ind == 1) {
  $answer[2] = 0
} else {
  $answer[2] = 1
}
$answer[0] = $r1
$answer[1] = $r2

if ($ind == 1) {
  $verdict = "are equal, so the group does not change the chance and the events are independent"
} else {
  $verdict = "are different, so the group changes the chance and the events are not independent"
}


$sol = '
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
    <summary><span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span> Step-by-Step Solution</summary>
    <div class="sol-body">
      <p><span class="term-label">Find each rate.</span> Group 1: `' . $x1 . '/' . $n1 . ' = ' . $r1 . '`. Group 2: `' . $x2 . '/' . $n2 . ' = ' . $r2 . '`.</p>
      <p><span class="term-label">What to compare.</span> Two things are independent when knowing the group does not change the probability. So compare the two rates. If they are equal, the group makes no difference and the events are independent. If they are different, the group does change the chance, so the events are not independent.</p>
      <p><span class="term-label">Here.</span> ' . $r1 . ' and ' . $r2 . ' ' . $verdict . '.</p>
      <p><span class="term-label">The other answers.</span> Group size does not matter, only the rate. And having some people in each group who ' . $doing . ' does not matter either, because the rates can still differ.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$x1 of $n1 $g1 $doing. $x2 of $n2 $g2 $doing.</p>
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Enter each rate as a fraction or a decimal rounded to 4 places.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Rate for the $g1. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Rate for the $g2. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is the group independent of whether they $doing? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$sol
