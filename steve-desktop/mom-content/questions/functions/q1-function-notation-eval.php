// === NAME - DESCRIPTION: Function Notation Eval - Evaluate f(x) for a randomized polynomial at three x values ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

$anstypes = array("number", "number", "number")

/* ---------- 1. Randomize polynomial and x values ---------- */
// Parallel arrays: each row is one scenario [a, b, c, x1, x2, x3, fx1, fx2, fx3, degree, degword]
// f(x) = a*x^2 + b*x + c  (degree 2) or  f(x) = a*x^3 + b*x + c  (degree 3)
// Keep coefficients and x values small so arithmetic stays clean.

// Scenario set A: quadratics  f(x) = ax^2 + bx + c
$aA = array(1,  2,  1, -1,  3,  2)
$bA = array(2, -1,  3,  4, -2,  1)
$cA = array(-3,  4, -1,  0,  5, -2)
$x1A = array(0,  1, -1,  2, -1,  3)
$x2A = array(2,  3,  2, -2,  2,  0)
$x3A = array(-2, -1,  3,  4,  0, -3)
// precompute f(x) = a*x^2 + b*x + c  for each scenario and each x
// row 0: a=1,b=2,c=-3  x1=0->-3  x2=2->1+4-3=5  x3=-2->4-4-3=-3  wait let me recompute carefully
// row 0: f(0)=0+0-3=-3  f(2)=4+4-3=5  f(-2)=4-4-3=-3
// row 1: a=2,b=-1,c=4  f(1)=2-1+4=5  f(3)=18-3+4=19  f(-1)=2+1+4=7
// row 2: a=1,b=3,c=-1  f(-1)=1-3-1=-3  f(2)=4+6-1=9  f(3)=9+9-1=17
// row 3: a=-1,b=4,c=0  f(2)=-4+8=4  f(-2)=-4-8=-12  f(4)=-16+16=0
// row 4: a=3,b=-2,c=5  f(-1)=3+2+5=10  f(2)=12-4+5=13  f(0)=5
// row 5: a=2,b=1,c=-2  f(3)=18+3-2=19  f(0)=0+0-2=-2  f(-3)=18-3-2=13

$fx1A = array(-3, 5, -3, 4, 10, 19)
$fx2A = array(5, 19, 9, -12, 13, -2)
$fx3A = array(-3, 7, 17, 0, 5, 13)

$pickedA = jointrandfrom($aA, $bA, $cA, $x1A, $x2A, $x3A, $fx1A, $fx2A, $fx3A)
$a = $pickedA[0]
$b = $pickedA[1]
$c = $pickedA[2]
$xv1 = $pickedA[3]
$xv2 = $pickedA[4]
$xv3 = $pickedA[5]
$ans1 = $pickedA[6]
$ans2 = $pickedA[7]
$ans3 = $pickedA[8]

$answer[0] = $ans1
$answer[1] = $ans2
$answer[2] = $ans3

// Build display sign strings to avoid printing "+ -"
$bsign = "+"
if ($b < 0) { $bsign = "-" }
$babs = abs($b)
$csign = "+"
if ($c < 0) { $csign = "-" }
$cabs = abs($c)

// Coefficient display: hide leading 1 for x^2 term
$adisp = ""
if ($a != 1) { $adisp = $a }
if ($a == -1) { $adisp = "-" }
if ($a == 1) { $adisp = "" }

$poly_display = "f(x) = " . $adisp . "x^2 " . $bsign . " " . $babs . "x " . $csign . " " . $cabs

// Precompute display strings for solution
$x1sq = $xv1 * $xv1
$x2sq = $xv2 * $xv2
$x3sq = $xv3 * $xv3
$ax1sq = $a * $x1sq
$ax2sq = $a * $x2sq
$ax3sq = $a * $x3sq
$bx1 = $b * $xv1
$bx2 = $b * $xv2
$bx3 = $b * $xv3

/* ---------- 2. Solution guide ---------- */
$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p>Given <b>f(x) = '.$a.'x&sup2; + '.$b.'x + '.$c.'</b>, substitute each x value and simplify.</p>
      <p><b>(a)</b> f('.$xv1.') = '.$a.'&middot;('.$xv1.')<sup>2</sup> + '.$b.'&middot;('.$xv1.') + '.$c.'
        = '.$a.'&middot;'.$x1sq.' + '.$bx1.' + '.$c.'
        = '.$ax1sq.' + '.$bx1.' + '.$c.'
        = <b>'.$ans1.'</b></p>
      <p><b>(b)</b> f('.$xv2.') = '.$a.'&middot;('.$xv2.')<sup>2</sup> + '.$b.'&middot;('.$xv2.') + '.$c.'
        = '.$a.'&middot;'.$x2sq.' + '.$bx2.' + '.$c.'
        = '.$ax2sq.' + '.$bx2.' + '.$c.'
        = <b>'.$ans2.'</b></p>
      <p><b>(c)</b> f('.$xv3.') = '.$a.'&middot;('.$xv3.')<sup>2</sup> + '.$b.'&middot;('.$xv3.') + '.$c.'
        = '.$a.'&middot;'.$x3sq.' + '.$bx3.' + '.$c.'
        = '.$ax3sq.' + '.$bx3.' + '.$c.'
        = <b>'.$ans3.'</b></p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        (a) '.$ans1.' &nbsp;&bull;&nbsp; (b) '.$ans2.' &nbsp;&bull;&nbsp; (c) '.$ans3.'
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<p style="margin:0;">Use function notation to evaluate the polynomial below.</p>
<p style="margin:8px 0 4px 0; font-size:18px; text-align:center;"><b>f(x) = $a x&sup2; + $b x + $c</b></p>
<p style="margin:4px 0 0 0; font-size:14px; color:#555;">Substitute the given x value, then compute. Enter exact integer answers.</p>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find <b>f($xv1)</b>.<span style="margin-left:8px;">$answerbox[0]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find <b>f($xv2)</b>.<span style="margin-left:8px;">$answerbox[1]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find <b>f($xv3)</b>.<span style="margin-left:8px;">$answerbox[2]</span>
</div>
</div>


// === ANSWER ===

$solutionguide
