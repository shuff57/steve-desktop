// === NAME - DESCRIPTION: Standard Form from a Budget and from Point-Slope Form - Write Ax + By = C for two items that add up to a total, give both intercepts, then change a point-slope equation into standard form ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.6 (2.6.3 Point-Slope Form and 2.6.4 Standard Form). Part (a) and (b): two prices a and b
// and a total chosen as a multiple of lcm(a, b), so both intercepts are whole numbers. When a and b share
// a factor, the page says to divide it out, and the prompt asks for no common factor, so the key is unique.
// Part (c): slope p/q with q from 2 to 5 and gcd(p, q) = 1, so after clearing the fraction A, B, C have no
// common factor, B is never 0, and C is never 0. A is made positive.
$anstypes = array("calculated", "calculated", "calculated", "calculated", "calculated", "calculated", "calculated", "calculated")
$abstolerance = 0.0001
$answerboxsize = [8, 8, 8, 8, 8, 8, 8, 8]

$i = rand(0, 3)
$p1 = array("The drama club spends exactly &#36;", "A pottery stall took in exactly &#36;", "A school store took in exactly &#36;", "A fair booth took in exactly &#36;")
$p2 = array(" on masks and capes. A mask costs &#36;", " selling large mugs and small mugs. A large mug costs &#36;", " selling notebooks and binders. A notebook costs &#36;", " selling child tickets and adult tickets. A child ticket costs &#36;")
$p3 = array(" and a cape costs &#36;", " and a small mug costs &#36;", " and a binder costs &#36;", " and an adult ticket costs &#36;")
$it1 = array("masks", "large mugs", "notebooks", "child tickets")
$it2 = array("capes", "small mugs", "binders", "adult tickets")
$item1 = $it1[$i]
$item2 = $it2[$i]

$priceList = array(2, 3, 4, 5, 6, 8, 9, 10, 12, 15)
$ia = rand(0, 9)
$ib = mod($ia + rand(1, 9), 10)
$pa = $priceList[$ia]
$pb = $priceList[$ib]
$g = gcd($pa, $pb)
$lc = $pa * $pb / $g
$kmin = ceil(60 / $lc)
$kmax = floor(300 / $lc)
if ($kmax < $kmin) {
  $kmax = $kmin
}
$tot = $lc * rand($kmin, $kmax)
$intro = $p1[$i] . $tot . $p2[$i] . $pa . $p3[$i] . $pb . "."

$A1 = $pa / $g
$B1 = $pb / $g
$C1 = $tot / $g
$xint = $tot / $pa
$yint = $tot / $pb

$gNote = "The numbers " . $pa . ", " . $pb . " and " . $tot . " have no common factor other than 1, so this is already in standard form."
if ($g > 1) {
  $gNote = "Every number divides by " . $g . ", so divide the whole equation by " . $g . ": `" . $A1 . "x + " . $B1 . "y = " . $C1 . "`."
}

$q = rand(2, 5)
$p = rand(1, 7)
if (gcd($p, $q) != 1) {
  $p = 1
}
$p = $p * randfrom(array(-1, 1))
$x1 = rand(1, 9) * randfrom(array(-1, 1))
$y1 = rand(1, 9) * randfrom(array(-1, 1))
$cRaw = $q * $y1 - $p * $x1
if ($cRaw == 0) {
  $y1 = $y1 + 10
  $cRaw = $q * $y1 - $p * $x1
}
$A0 = 0 - $p
$B0 = $q
$C0 = $cRaw
if ($A0 < 0) {
  $A0 = 0 - $A0
  $B0 = 0 - $B0
  $C0 = 0 - $C0
}

$yShow = "y - " . $y1
if ($y1 < 0) {
  $yShow = "y + " . abs($y1)
}
$xShow = "x - " . $x1
if ($x1 < 0) {
  $xShow = "x + " . abs($x1)
}
$psText = $yShow . " = " . $p . "/" . $q . " (" . $xShow . ")"

$dy = 0 - $q * $y1
$dx = 0 - $p * $x1
$dyS = " + " . $dy
if ($dy < 0) {
  $dyS = " - " . abs($dy)
}
$dxS = " + " . $dx
if ($dx < 0) {
  $dxS = " - " . abs($dx)
}
$pTerm = $p . "x"
if ($p == 1) {
  $pTerm = "x"
}
if ($p == -1) {
  $pTerm = "-x"
}
$negp = 0 - $p
$nTerm = $negp . "x"
if ($negp == 1) {
  $nTerm = "x"
}
if ($negp == -1) {
  $nTerm = "-x"
}
$aTerm = $A0 . "x"
if ($A0 == 1) {
  $aTerm = "x"
}
$bTerm = " + " . $B0 . "y"
if ($B0 < 0) {
  $bTerm = " - " . abs($B0) . "y"
}
$rawLeft = $nTerm . " + " . $q . "y"
$finalEq = $aTerm . $bTerm . " = " . $C0
$flipText = "The x term is already positive, so this is standard form."
if ($p > 0) {
  $flipText = "A has to be positive, so multiply both sides by -1."
}

$answer[0] = $A1
$answer[1] = $B1
$answer[2] = $C1
$answer[3] = $xint
$answer[4] = $yint
$answer[5] = $A0
$answer[6] = $B0
$answer[7] = $C0

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
      <p><span class="term-label">(a) The budget.</span> The ' . $item1 . ' cost ' . $pa . 'x dollars and the ' . $item2 . ' cost ' . $pb . 'y dollars. Together that is exactly ' . $tot . ' dollars: `' . $pa . 'x + ' . $pb . 'y = ' . $tot . '`. ' . $gNote . ' So `A = ' . $A1 . '`, `B = ' . $B1 . '` and `C = ' . $C1 . '`.</p>
      <p><span class="term-label">(b) Intercepts.</span> For the x-intercept, put in `y = 0`: `' . $A1 . 'x = ' . $C1 . '`, so `x = ' . $xint . '`. For the y-intercept, put in `x = 0`: `' . $B1 . 'y = ' . $C1 . '`, so `y = ' . $yint . '`. The line passes through `(' . $xint . ', 0)` and `(0, ' . $yint . ')`.</p>
      <p><span class="term-label">(c) Point-slope to standard form.</span> Multiply both sides by ' . $q . ' to clear the fraction: `' . $q . '(' . $yShow . ') = ' . $p . '(' . $xShow . ')`. Distribute: `' . $q . 'y' . $dyS . ' = ' . $pTerm . $dxS . '`. Put the x term on the left and the number on the right: `' . $rawLeft . ' = ' . $cRaw . '`. ' . $flipText . ' The standard form is `' . $finalEq . '`, so `A = ' . $A0 . '`, `B = ' . $B0 . '` and `C = ' . $C0 . '`.</p>
      <p>Check the point in the answer: `' . $A0 . '(' . $x1 . ') + (' . $B0 . ')(' . $y1 . ') = ' . $C0 . '`.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$intro Let `x` be the number of $item1 and `y` the number of $item2.</p>
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Enter every answer as a number. Standard form means `Ax + By = C` with integers that have no common factor other than 1, and `A` positive.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Write the total in standard form.<br><br>
    <b>A</b> = $answerbox[0] &nbsp; <b>B</b> = $answerbox[1] &nbsp; <b>C</b> = $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find both intercepts of that line.<br><br>
    The x-intercept: <b>x</b> = $answerbox[3] when `y = 0`<br><br>
    The y-intercept: <b>y</b> = $answerbox[4] when `x = 0`
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> A different line is written in point-slope form: `$psText`. Write it in standard form.<br><br>
    <b>A</b> = $answerbox[5] &nbsp; <b>B</b> = $answerbox[6] &nbsp; <b>C</b> = $answerbox[7]
  </div>
</div>

// === ANSWER ===

$sol
