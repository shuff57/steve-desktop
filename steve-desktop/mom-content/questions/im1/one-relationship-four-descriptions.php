// === NAME - DESCRIPTION: One Relationship, Four Descriptions - A situation, a table, an equation and a graph describe linear relationships; find the one that does not match, the shared rate and starting value, and what differs ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.7 (2.7.4 One Relationship, Four Descriptions). Exactly one of the four descriptions is
// changed: either its rate or its starting value (never both), so there is one odd one out and one
// thing that differs. The rate is a multiple of 0.5 and the starting value is even, so every table
// value is a whole number and the two graph dots (x = 4 and x = 8) sit on a gridline: 4r and 8r are
// even and b is even. The y gridlines are every 2, labeled every 2. The table never has a row at x = 0.
$anstypes = array("choices", "calculated", "calculated", "choices")
$abstolerance = 0.0001
$answerboxsize = [0, 8, 8, 0]
$noshuffle[0] = "all"
$noshuffle[3] = "all"

$i = rand(0, 3)
$vA = array("A kayak rental costs &#36;", "A museum audio guide costs &#36;", "A taxi charges a base fare of &#36;", "A gym charges a sign-up fee of &#36;")
$vB = array(" to launch, plus &#36;", " to rent, plus &#36;", ", plus &#36;", ", plus &#36;")
$vC = array(" for every half hour.", " for every hall visited.", " for every mile.", " for each visit.")
$xWhat = array("half hours", "halls visited", "miles", "visits")
$xNames = array("Half hours", "Halls visited", "Miles", "Visits")
$xUnit1 = array("half hour", "hall", "mile", "visit")
$unit1 = $xUnit1[$i]
$xWhatText = $xWhat[$i]
$xAxisName = $xNames[$i]

$r = randfrom(array(1.5, 2, 2.5, 3))
$b = randfrom(array(2, 4, 6, 8))
$odd = rand(0, 3)
$mode = rand(0, 1)
$r2 = $r + randfrom(array(-1, -0.5, 0.5, 1))
if ($r2 < 1) {
  $r2 = $r + 1
}
$b2 = $b + 2 * randfrom(array(-1, 1, 2))
if ($b2 < 2) {
  $b2 = $b + 4
}
for ($k=0..3) {
  $rr[$k] = $r
  $bq[$k] = $b
}
if ($mode == 0) {
  $rr[$odd] = $r2
} else {
  $bq[$odd] = $b2
}

$rTxt0 = $rr[0]
if ($rr[0] != floor($rr[0])) {
  $rTxt0 = $rr[0] . "0"
}
$verbal = $vA[$i] . $bq[0] . $vB[$i] . $rTxt0 . $vC[$i]

$skip = rand(0, 4)
$n = 0
for ($k=0..4) {
  if ($k != $skip) {
    $tx[$n] = 2 * ($k + 1)
    $ty[$n] = $rr[1] * $tx[$n] + $bq[1]
    $n = $n + 1
  }
}
$tx0 = $tx[0]
$tx1 = $tx[1]
$tx2 = $tx[2]
$tx3 = $tx[3]
$ty0 = $ty[0]
$ty1 = $ty[1]
$ty2 = $ty[2]
$ty3 = $ty[3]
$eqT = "y = " . $rr[2] . "x + " . $bq[2]

$rg = $rr[3]
$bg = $bq[3]
$y4 = 4 * $rg + $bg
$y8 = 8 * $rg + $bg
$y9 = 9 * $rg + $bg
$ymax = 2 * ceil($y9 / 2)
$yu = 300 / $ymax
$svg = '<svg viewBox="0 0 420 372" width="100%" style="max-width:480px; display:block; margin:10px auto; background:#fff;" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="A graph of a line with two dots on grid corners">'
for ($g=0..9) {
  $gx = 52 + 38 * $g
  $svg = $svg . '<line x1="' . $gx . '" y1="24" x2="' . $gx . '" y2="324" stroke="#e5e7eb" stroke-width="1"/>'
  $svg = $svg . '<text x="' . $gx . '" y="341" font-size="11" fill="#374151" text-anchor="middle">' . $g . '</text>'
}
$nrows = $ymax / 2
for ($g=0..$nrows) {
  $gy = round(324 - $yu * 2 * $g, 2)
  $gyt = round($gy + 3.5, 2)
  $gv = 2 * $g
  $svg = $svg . '<line x1="52" y1="' . $gy . '" x2="394" y2="' . $gy . '" stroke="#e5e7eb" stroke-width="1"/>'
  $svg = $svg . '<text x="46" y="' . $gyt . '" font-size="10" fill="#374151" text-anchor="end">' . $gv . '</text>'
}
$svg = $svg . '<line x1="52" y1="24" x2="52" y2="324" stroke="#374151" stroke-width="1.5"/>'
$svg = $svg . '<line x1="52" y1="324" x2="394" y2="324" stroke="#374151" stroke-width="1.5"/>'
$ly0 = round(324 - $yu * $bg, 2)
$ly9 = round(324 - $yu * $y9, 2)
$svg = $svg . '<line x1="52" y1="' . $ly0 . '" x2="394" y2="' . $ly9 . '" stroke="#1865f2" stroke-width="2.5" stroke-linecap="round"/>'
$d4 = round(324 - $yu * $y4, 2)
$d8 = round(324 - $yu * $y8, 2)
$svg = $svg . '<circle cx="204" cy="' . $d4 . '" r="5.5" fill="#1865f2" stroke="#ffffff" stroke-width="1.5"/>'
$svg = $svg . '<circle cx="356" cy="' . $d8 . '" r="5.5" fill="#1865f2" stroke="#ffffff" stroke-width="1.5"/>'
$svg = $svg . '<text x="223" y="362" font-size="13" fill="#374151" text-anchor="middle">' . $xAxisName . ' x</text>'
$svg = $svg . '<text x="14" y="174" font-size="13" fill="#374151" text-anchor="middle" transform="rotate(-90 14 174)">Cost y in dollars</text>'
$svg = $svg . '</svg>'

$questions[0] = array("The situation", "The table", "The equation", "The graph")
$questions[3] = array("Its rate of change", "Its starting value")
$answer[0] = $odd
$answer[1] = $r
$answer[2] = $b
$answer[3] = $mode

$oddNames = array("situation", "table", "equation", "graph")
$oddName = $oddNames[$odd]
$diffName = "starting value"
$diffSame = "rate"
if ($mode == 0) {
  $diffName = "rate of change"
  $diffSame = "starting value"
}
$rOddTxt = $rr[$odd]
$bOddTxt = $bq[$odd]
$rTx = $rr[1]
$bTx = $bq[1]
$dyT = $ty1 - $ty0
$dxT = $tx1 - $tx0
$goBack = round($rTx * $tx0, 2)
$dyG = $y8 - $y4

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
      <p><span class="term-label">Read the rate and the starting value from each one.</span></p>
      <p><b>The situation:</b> the rate is ' . $rr[0] . ' dollars per ' . $unit1 . ' and the starting value is ' . $bq[0] . ' dollars.<br>
      <b>The table:</b> `(' . $ty1 . ' - ' . $ty0 . ')/(' . $tx1 . ' - ' . $tx0 . ') = ' . $dyT . '/' . $dxT . ' = ' . $rr[1] . '`. There is no row at `x = 0`, so go back from the first row: `' . $ty0 . ' - ' . $rr[1] . '(' . $tx0 . ') = ' . $ty0 . ' - ' . $goBack . ' = ' . $bq[1] . '`.<br>
      <b>The equation:</b> `' . $eqT . '`, so the rate is ' . $rr[2] . ' and the starting value is ' . $bq[2] . '.<br>
      <b>The graph:</b> the dots are the grid corners `(4, ' . $y4 . ')` and `(8, ' . $y8 . ')`. The rate is `(' . $y8 . ' - ' . $y4 . ')/(8 - 4) = ' . $dyG . '/4 = ' . $rr[3] . '`. The line crosses the vertical axis at `(0, ' . $bq[3] . ')`, so the starting value is ' . $bq[3] . '.</p>
      <p><span class="term-label">(a) The odd one.</span> Three descriptions have rate ' . $r . ' and starting value ' . $b . '. The ' . $oddName . ' has rate ' . $rOddTxt . ' and starting value ' . $bOddTxt . '. It is the one that does not match.</p>
      <p><span class="term-label">(b) and (c)</span> The shared rate is ' . $r . ' and the shared starting value is ' . $b . '.</p>
      <p><span class="term-label">(d) What differs.</span> The ' . $diffSame . ' of the ' . $oddName . ' is the same as the others. Its ' . $diffName . ' is different. Comparing one shared point is not enough to decide. Compare the rate and the starting value.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Below are four descriptions of a cost. Let `x` be the number of $xWhatText and `y` the cost in dollars. Three of the descriptions are the same relationship. One is not.</p>
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Enter each number as a plain number, such as 2.5.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 6px 0;"><b>The situation</b></p>
    <p style="margin:0;">$verbal</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 6px 0;"><b>The table</b></p>
    <table style="border-collapse:collapse; margin:6px 0; font-size:15px;">
      <tr><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff; text-align:left;">x</th><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$tx0</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$tx1</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$tx2</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$tx3</td></tr>
      <tr><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff; text-align:left;">y</th><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$ty0</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$ty1</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$ty2</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$ty3</td></tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 6px 0;"><b>The equation</b></p>
    <p style="margin:0;">`$eqT`</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 6px 0;"><b>The graph</b></p>
    <p style="margin:0;">The line passes through the two dots. Each gridline across is 1 unit of x. Each gridline up is 2 dollars.</p>
    $svg
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which description does not match the other three?<br><br>$answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The three that match have the same rate of change. What is it, in dollars per unit of `x`? <b>m</b> = $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> They also have the same starting value, the cost when `x = 0`. What is it? <b>b</b> = $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> How does the one that does not match differ from the other three?<br><br>$answerbox[3]
  </div>
</div>

// === ANSWER ===

$sol
