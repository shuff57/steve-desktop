// === NAME - DESCRIPTION: Function Comprehensive - Evaluate, find domain, and compute AROC for one real-world function ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

$anstypes = array("number", "number", "number")

/* ---------- 1. Randomization ---------- */
// 6 scenarios: rows 0-2 are radical f(x)=a*sqrt(x-h), rows 3-5 are rational f(x)=p*x/(x-k)
// ftype: 0=radical, 1=rational
// Columns: ftype, a_or_p, h_or_k, x_eval, f_eval, x1_aroc, x2_aroc, fx1, fx2, aroc_num, aroc_den, domain_ans
//
// Verified by hand:
// row 0: f=2*sqrt(x-1)  x_eval=5: 2*2=4  [1,5]: f(1)=0,f(5)=4  AROC=4/4=1  LB=1
// row 1: f=3*sqrt(x+4)  x_eval=5: 3*3=9  [0,5]: f(0)=6,f(5)=9  AROC=3/5    LB=-4
// row 2: f=5*sqrt(x)    x_eval=4: 5*2=10 [0,4]: f(0)=0,f(4)=10 AROC=10/4=5/2 LB=0
// row 3: f=6x/(x-3)     x_eval=6: 36/3=12 [6,9]: f(6)=12,f(9)=9  AROC=-3/3=-1  excl=3
// row 4: f=8x/(x-2)     x_eval=3: 24/1=24 [3,6]: f(3)=24,f(6)=12 AROC=-12/3=-4 excl=2
// row 5: f=4x/(x-1)     x_eval=3: 12/2=6  [2,3]: f(2)=8,f(3)=6   AROC=-2/1=-2  excl=1

$ftypes   = array(0,    0,   0,   1,   1,   1)
$as       = array(2,    3,   5,   6,   8,   4)
$hs       = array(1,   -4,   0,   3,   2,   1)
$xevals   = array(5,    5,   4,   6,   3,   3)
$fevals   = array(4,    9,  10,  12,  24,   6)
$x1s      = array(1,    0,   0,   6,   3,   2)
$x2s      = array(5,    5,   4,   9,   6,   3)
$fx1s     = array(0,    6,   0,  12,  24,   8)
$fx2s     = array(4,    9,  10,   9,  12,   6)
$aroc_nums = array(4,   3,  10,  -3, -12,  -2)
$aroc_dens = array(4,   5,   4,   3,   3,   1)
$domain_ans = array(1, -4,   0,   3,   2,   1)

$picked = jointrandfrom($ftypes, $as, $hs, $xevals, $fevals, $x1s, $x2s, $fx1s, $fx2s, $aroc_nums, $aroc_dens, $domain_ans)
$ftype   = $picked[0]
$a       = $picked[1]
$h       = $picked[2]
$xeval   = $picked[3]
$feval   = $picked[4]
$x1      = $picked[5]
$x2      = $picked[6]
$fx1     = $picked[7]
$fx2     = $picked[8]
$aroc_n  = $picked[9]
$aroc_d  = $picked[10]
$dom_ans = $picked[11]

$answer[0] = $feval
$answer[1] = $dom_ans
$answer[2] = $aroc_n / $aroc_d

/* ---------- 2. Display strings ---------- */
// Function display and context labels
$h_abs = abs($h)

// Radical display: a*sqrt(x - h)
$rad_inside = "x"
if ($h > 0) { $rad_inside = "x - " . $h }
if ($h < 0) { $rad_inside = "x + " . $h_abs }

// Rational display: a*x / (x - h)   where h here is the excluded value k
$rat_denom = "x"
if ($h > 0) { $rat_denom = "x - " . $h }
if ($h < 0) { $rat_denom = "x + " . $h_abs }

// Function rule text for each type
$func_rule = $a . "x / (" . $rat_denom . ")"
if ($ftype == 0) { $func_rule = $a . "&radic;(" . $rad_inside . ")" }

// Domain instruction varies by type
$domain_prompt = "Enter the x value that cannot be in the domain (where R &ne; ?):"
if ($ftype == 0) { $domain_prompt = "Enter the smallest x value allowed in the domain (where R &ge; ?):" }

// Domain summary line for solution
$domain_explanation = "Set " . $rat_denom . " = 0 &rArr; x = " . $h . ". The domain is all real numbers with x &ne; " . $h . "."
if ($ftype == 0) { $domain_explanation = "Require " . $rad_inside . " &ge; 0 &rArr; x &ge; " . $h . ". The domain is [" . $h . ", &infin;)." }

// AROC fraction display
$diff_f = $fx2 - $fx1
$diff_x = $x2 - $x1
$aroc_disp = $aroc_n . " / " . $aroc_d
if ($aroc_d == 1) { $aroc_disp = $aroc_n }

// Context labels by scenario type
$context_name = "average cost model"
if ($ftype == 0) { $context_name = "revenue model" }

/* ---------- 3. Solution guide ---------- */
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
      <p style="margin:0 0 6px 0;"><b>Function: R(x) = '.$func_rule.'</b></p>
      <p style="margin:0 0 4px 0;"><b>Part (a): Evaluate R('.$xeval.'):</b><br>
      Substitute x = '.$xeval.' into the function rule.<br>
      R('.$xeval.') = <b>'.$feval.'</b></p>
      <p style="margin:8px 0 4px 0;"><b>Part (b): Domain:</b><br>
      '.$domain_explanation.'<br>
      Answer: <b>'.$dom_ans.'</b></p>
      <p style="margin:8px 0 4px 0;"><b>Part (c): Average Rate of Change on ['.$x1.', '.$x2.']:</b><br>
      AROC = (R(x<sub>2</sub>) &minus; R(x<sub>1</sub>)) / (x<sub>2</sub> &minus; x<sub>1</sub>)<br>
      R('.$x1.') = '.$fx1.' &nbsp;&nbsp; R('.$x2.') = '.$fx2.'<br>
      AROC = ('.$fx2.' &minus; '.$fx1.') / ('.$x2.' &minus; '.$x1.')
           = '.$diff_f.' / '.$diff_x.'
           = <b>'.$aroc_disp.'</b></p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        (a) R('.$xeval.') = '.$feval.' &nbsp;&bull;&nbsp; (b) domain answer = '.$dom_ans.' &nbsp;&bull;&nbsp; (c) AROC = '.$aroc_disp.'
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<p style="margin:0 0 4px 0;">A company models its $context_name with the function</p>
<p style="margin:0 0 8px 0; font-size:18px; text-align:center;"><b>R(x) = $func_rule</b></p>
<p style="margin:0; font-size:14px; color:#555;">where x is the number of units produced (in hundreds) and R(x) is revenue (in thousands of dollars). Answer all three parts using this function.</p>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> <b>Evaluate:</b> Find <b>R($xeval)</b>.<span style="margin-left:8px;">$answerbox[0]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> <b>Domain:</b> $domain_prompt<span style="margin-left:8px;">$answerbox[1]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> <b>Average Rate of Change:</b> Find the average rate of change of R on the interval <b>[$x1, $x2]</b>. Use the formula AROC = (R(x<sub>2</sub>) &minus; R(x<sub>1</sub>)) / (x<sub>2</sub> &minus; x<sub>1</sub>). Enter an exact decimal or fraction.<span style="margin-left:8px;">$answerbox[2]</span>
</div>
</div>


// === ANSWER ===

$solutionguide
