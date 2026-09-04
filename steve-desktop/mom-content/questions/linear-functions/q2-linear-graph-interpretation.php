// === NAME - DESCRIPTION: Linear Graph Interpretation - Read a verbal linear scenario to find intercept, slope, and function value ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

$anstypes = array("number", "number", "number")

/* ---------- 1. Context scenarios ---------- */
// Each context provides a verbal description of a linear function.
// Data: two (input, output) pairs embedded in prose, from which students find:
//   (a) y-intercept b  (b) slope m  (c) f(eval_x) = m*eval_x + b
//
// Contexts differ by real-world setting, not just numbers.
// Stored as parallel arrays indexed by context $ci.

// Context 0: Temperature decay over time
// "A cup of coffee cools from $T0 degrees at time 0, and after $dt minutes it has cooled to $T1 degrees."
// b = T0,  m = (T1-T0)/dt,  eval at $tx minutes
$T0s  = array(90, 80, 75)    // initial temp (y-intercept)
$T1s  = array(70, 60, 45)    // temp after dt minutes
$dts  = array(10,  5, 15)    // elapsed time (for data point 2)
$txs  = array(20, 20, 30)    // eval time for part (c)
// m = (T1-T0)/dt, precomputed:  (70-90)/10=-2  (60-80)/5=-4  (45-75)/15=-2
$ms_temp  = array(-2, -4, -2)
// f(eval_x) = b + m*eval_x:  90+(-2)*20=50  80+(-4)*20=0  75+(-2)*30=15
$fxs_temp = array(50, 0, 15)

// Context 1: Distance vs time (car driving away)
// "A car is $d0 miles from home at time 0, and $dt2 hours later it is $d1 miles from home."
// b = d0,  m = (d1-d0)/dt2 (positive, moving away),  eval at $hx hours
$d0s  = array(20, 10, 30)
$d1s  = array(80, 70, 90)
$dt2s = array( 3,  4,  4)
$hxs  = array( 5,  6,  5)
// m: (80-20)/3=20  (70-10)/4=15  (90-30)/4=15
$ms_dist  = array(20, 15, 15)
// f(eval): 20+20*5=120  10+15*6=100  30+15*5=105
$fxs_dist = array(120, 100, 105)

// Context 2: Account balance over months
// "A savings account has $A0 dollars at the start of the year, and after $mo months the balance is $A1 dollars."
// b = A0,  m = (A1-A0)/mo (negative if withdrawing),  eval at $mx months
$A0s  = array(500, 300, 800)
$A1s  = array(380, 120, 560)
$mos  = array(  4,   6,   8)
$mxs  = array(  6,  10,  12)
// m: (380-500)/4=-30  (120-300)/6=-30  (560-800)/8=-30   all -30: vary
// Let me redo for variety:
// row 0: A0=500,A1=380,mo=4  m=(380-500)/4=-120/4=-30  f(6)=500+(-30)*6=500-180=320
// row 1: A0=600,A1=450,mo=5  m=(450-600)/5=-150/5=-30  f(8)=600-240=360  -- still -30
// Use different drops:
// row 0: A0=500,A1=300,mo=4  m=-200/4=-50  f(6)=500-300=200
// row 1: A0=400,A1=160,mo=4  m=-240/4=-60  f(6)=400-360=40
// row 2: A0=600,A1=360,mo=6  m=-240/6=-40  f(9)=600-360=240
$A0s  = array(500, 400, 600)
$A1s  = array(300, 160, 360)
$mos  = array(  4,   4,   6)
$mxs  = array(  6,   6,   9)
$ms_acct  = array(-50, -60, -40)
$fxs_acct = array(200,  40, 240)

// Pick context (0=temp, 1=distance, 2=account)
$ci = rand(0, 2)

// Sub-index within context (0,1,2 for variety within each context type)
// Use a second rand for the sub-scenario
$si = rand(0, 2)

// Build scenario strings and pull correct numbers
if ($ci == 0) {
  $T0 = $T0s[$si]
  $T1 = $T1s[$si]
  $dt = $dts[$si]
  $tx = $txs[$si]
  $b_ans = $T0
  $m_ans = $ms_temp[$si]
  $fx_ans = $fxs_temp[$si]
  $scenario = 'A cup of coffee starts at <b>'.$T0.' degrees</b> when it is poured (time = 0 minutes). After <b>'.$dt.' minutes</b>, its temperature has dropped to <b>'.$T1.' degrees</b>. Assume the temperature decreases at a constant rate, so this is a linear function T(t).'
  $part_a_label = 'What was the temperature at time 0 (the y-intercept)?'
  $part_b_label = 'How many degrees does the coffee cool per minute (the slope)?'
  $part_c_label = 'What temperature does the model predict at t = '.$tx.' minutes?'
  $units_a = 'degrees'
  $units_b = 'degrees per minute'
  $units_c = 'degrees'
}
if ($ci == 1) {
  $d0 = $d0s[$si]
  $d1 = $d1s[$si]
  $dt2 = $dt2s[$si]
  $hx = $hxs[$si]
  $b_ans = $d0
  $m_ans = $ms_dist[$si]
  $fx_ans = $fxs_dist[$si]
  $scenario = 'A car starts <b>'.$d0.' miles</b> from home at time 0. After <b>'.$dt2.' hours</b> of driving at a constant speed, the car is <b>'.$d1.' miles</b> from home. Let D(t) be the distance from home as a linear function of time t (hours).'
  $part_a_label = 'What is the starting distance from home (the y-intercept in miles)?'
  $part_b_label = 'How fast is the car traveling (the slope in miles per hour)?'
  $part_c_label = 'How far from home will the car be at t = '.$hx.' hours?'
  $units_a = 'miles'
  $units_b = 'miles per hour'
  $units_c = 'miles'
}
if ($ci == 2) {
  $A0 = $A0s[$si]
  $A1 = $A1s[$si]
  $mo = $mos[$si]
  $mx = $mxs[$si]
  $b_ans = $A0
  $m_ans = $ms_acct[$si]
  $fx_ans = $fxs_acct[$si]
  $scenario = 'A savings account starts with <b>$'.$A0.'</b> at the beginning of the year (month 0). After <b>'.$mo.' months</b>, the balance has dropped to <b>$'.$A1.'</b> due to regular monthly withdrawals. Let B(t) be the balance as a linear function of time t (months).'
  $part_a_label = 'What is the starting balance (the y-intercept in dollars)?'
  $part_b_label = 'How much is withdrawn each month (the slope in dollars per month)?'
  $part_c_label = 'What does the model predict for the balance at month '.$mx.'?'
  $units_a = 'dollars'
  $units_b = 'dollars per month'
  $units_c = 'dollars'
}

$answer[0] = $b_ans
$answer[1] = $m_ans
$answer[2] = $fx_ans

/* ---------- 2. Solution guide ---------- */
// Slope calculation display: use if blocks (no ternary operators)
$y2_sol = 0
$x2_sol = 0
$eval_x = 0
if ($ci == 0) { $y2_sol = $T1s[$si] }
if ($ci == 1) { $y2_sol = $d1s[$si] }
if ($ci == 2) { $y2_sol = $A1s[$si] }
if ($ci == 0) { $x2_sol = $dts[$si] }
if ($ci == 1) { $x2_sol = $dt2s[$si] }
if ($ci == 2) { $x2_sol = $mos[$si] }
if ($ci == 0) { $eval_x = $txs[$si] }
if ($ci == 1) { $eval_x = $hxs[$si] }
if ($ci == 2) { $eval_x = $mxs[$si] }
$dy_sol = $y2_sol - $b_ans
$mx_eval = $m_ans * $eval_x

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
      <p>Two data points from the description: (0, '.$b_ans.') and ('.$x2_sol.', '.$y2_sol.').</p>
      <p><b>(a) Y-intercept:</b> The value at time 0 is given directly: <b>'.$b_ans.'</b> '.$units_a.'.</p>
      <p><b>(b) Slope:</b><br>
        m = ('.$y2_sol.' &minus; '.$b_ans.') / ('.$x2_sol.' &minus; 0)
        = '.$dy_sol.' / '.$x2_sol.'
        = <b>'.$m_ans.'</b> '.$units_b.'.</p>
      <p><b>(c) Function value at '.$eval_x.':</b><br>
        f('.$eval_x.') = '.$m_ans.' &middot; '.$eval_x.' + '.$b_ans.'
        = '.$mx_eval.' + '.$b_ans.'
        = <b>'.$fx_ans.'</b> '.$units_c.'.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        (a) '.$b_ans.' &nbsp;&bull;&nbsp; (b) '.$m_ans.' &nbsp;&bull;&nbsp; (c) '.$fx_ans.'
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<p style="margin:0 0 4px 0;">$scenario</p>
<p style="margin:8px 0 0 0; font-size:14px; color:#555;">Enter exact values for all three parts.</p>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> $part_a_label<span style="margin-left:8px;">$answerbox[0]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> $part_b_label<span style="margin-left:8px;">$answerbox[1]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> $part_c_label<span style="margin-left:8px;">$answerbox[2]</span>
</div>
</div>


// === ANSWER ===

$solutionguide
