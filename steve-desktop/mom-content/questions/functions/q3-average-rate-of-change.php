// === NAME - DESCRIPTION: Average Rate of Change - Compute (f(x2)-f(x1))/(x2-x1) for a randomized quadratic ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

$anstypes = array("number")

/* ---------- 1. Randomize via parallel arrays ---------- */
// Each row: [a, b, c, x1, x2]
// Precomputed answers keep arithmetic student-friendly (integer or simple fraction).
// f(x) = ax^2 + bx + c;   AROC = (f(x2)-f(x1))/(x2-x1)

// Verified by hand:
// row 0: a=1,b=2,c=0  x1=0,x2=3  f(0)=0  f(3)=9+6=15  AROC=15/3=5
// row 1: a=1,b=-1,c=2  x1=1,x2=4  f(1)=1-1+2=2  f(4)=16-4+2=14  AROC=12/3=4
// row 2: a=2,b=1,c=-3  x1=-1,x2=2  f(-1)=2-1-3=-2  f(2)=8+2-3=7  AROC=9/3=3
// row 3: a=1,b=3,c=1  x1=0,x2=2  f(0)=1  f(2)=4+6+1=11  AROC=10/2=5
// row 4: a=-1,b=4,c=0  x1=1,x2=3  f(1)=-1+4=3  f(3)=-9+12=3  AROC=0/2=0
// row 5: a=1,b=0,c=-4  x1=-2,x2=2  f(-2)=4-4=0  f(2)=4-4=0  AROC=0/4=0   -- too boring, skip
// row 5: a=2,b=-3,c=1  x1=0,x2=3  f(0)=1  f(3)=18-9+1=10  AROC=9/3=3
// row 6: a=1,b=1,c=-2  x1=-1,x2=3  f(-1)=1-1-2=-2  f(3)=9+3-2=10  AROC=12/4=3
// row 7: a=3,b=-2,c=0  x1=1,x2=2  f(1)=3-2=1  f(2)=12-4=8  AROC=7/1=7
// row 8: a=1,b=4,c=-5  x1=0,x2=2  f(0)=-5  f(2)=4+8-5=7  AROC=12/2=6
// row 9: a=2,b=1,c=0  x1=-2,x2=1  f(-2)=8-2=6  f(1)=2+1=3  AROC=-3/3=-1

$as   = array(1,  1,  2,  1, -1,  2,  1,  3,  1,  2)
$bs   = array(2, -1,  1,  3,  4, -3,  1, -2,  4,  1)
$cs   = array(0,  2, -3,  1,  0,  1, -2,  0, -5,  0)
$x1s  = array(0,  1, -1,  0,  1,  0, -1,  1,  0, -2)
$x2s  = array(3,  4,  2,  2,  3,  3,  3,  2,  2,  1)
$fx1s = array(0,  2, -2,  1,  3,  1, -2,  1, -5,  6)
$fx2s = array(15, 14, 7, 11,  3, 10, 10,  8,  7,  3)
$arocs = array(5,  4,  3,  5,  0,  3,  3,  7,  6, -1)

$picked = jointrandfrom($as, $bs, $cs, $x1s, $x2s, $fx1s, $fx2s, $arocs)
$a  = $picked[0]
$b  = $picked[1]
$c  = $picked[2]
$x1 = $picked[3]
$x2 = $picked[4]
$fx1 = $picked[5]
$fx2 = $picked[6]
$aroc = $picked[7]

$answer[0] = $aroc

// Display strings for solution
$b_abs = abs($b)
$c_abs = abs($c)
$b_sign = "+"
if ($b < 0) { $b_sign = "-" }
$c_sign = "+"
if ($c < 0) { $c_sign = "-" }

$diff_f = $fx2 - $fx1
$diff_x = $x2 - $x1

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
      <p>The <b>average rate of change</b> of f on [x<sub>1</sub>, x<sub>2</sub>] is:
        <br>&nbsp;&nbsp;&nbsp;AROC = (f(x<sub>2</sub>) &minus; f(x<sub>1</sub>)) / (x<sub>2</sub> &minus; x<sub>1</sub>)</p>
      <p><b>Step 1:</b> Evaluate f('.$x1.'):<br>
        &nbsp;&nbsp;&nbsp;f('.$x1.') = '.$a.'('.$x1.')<sup>2</sup> '.$b_sign.' '.$b_abs.'('.$x1.') '.$c_sign.' '.$c_abs.'
        = <b>'.$fx1.'</b></p>
      <p><b>Step 2:</b> Evaluate f('.$x2.'):<br>
        &nbsp;&nbsp;&nbsp;f('.$x2.') = '.$a.'('.$x2.')<sup>2</sup> '.$b_sign.' '.$b_abs.'('.$x2.') '.$c_sign.' '.$c_abs.'
        = <b>'.$fx2.'</b></p>
      <p><b>Step 3:</b> Apply the formula:<br>
        &nbsp;&nbsp;&nbsp;AROC = ('.$fx2.' &minus; '.$fx1.') / ('.$x2.' &minus; '.$x1.')
        = '.$diff_f.' / '.$diff_x.'
        = <b>'.$aroc.'</b></p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Average rate of change = <b>'.$aroc.'</b>
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<p style="margin:0 0 8px 0;">Find the <b>average rate of change</b> of the function</p>
<p style="margin:0 0 8px 0; font-size:18px; text-align:center;"><b>f(x) = $a x&sup2; $b_sign $b_abs x $c_sign $c_abs</b></p>
<p style="margin:0;">on the interval <b>[$x1, $x2]</b>.</p>
<p style="margin:8px 0 0 0; font-size:14px; color:#555;">Use the formula: AROC = (f(x<sub>2</sub>) &minus; f(x<sub>1</sub>)) / (x<sub>2</sub> &minus; x<sub>1</sub>). Enter the exact value.</p>
<div style="margin-top:14px; text-align:center;">$answerbox[0]</div>
</div>
</div>


// === ANSWER ===

$solutionguide
