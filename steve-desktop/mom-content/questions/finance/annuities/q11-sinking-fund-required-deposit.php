// === NAME - DESCRIPTION: 6.3 Sinking Fund - Required Periodic Deposit ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("finance")

/* ---------- 1. Scenario context ---------- */
$contexts = array(
  array("saving for a home down payment", "down payment fund", "have enough for a down payment"),
  array("building an equipment replacement fund", "equipment reserve", "replace the equipment when it wears out"),
  array("saving for your child's college tuition", "college savings account", "cover tuition costs"),
  array("building a business emergency reserve", "business savings account", "have a cushion for unexpected expenses")
)
$ci = rand(0, count($contexts)-1)
$ctx_desc = $contexts[$ci][0]
$ctx_type = $contexts[$ci][1]
$ctx_goal = $contexts[$ci][2]

/* ---------- 2. Randomize goal, rate, compounding, time via joint arrays ---------- */
$goal_vals = array(10000, 15000, 20000, 25000, 30000, 40000, 50000, 20000, 15000, 30000, 25000, 40000)
$r_pcts    = array(4,     5,     6,     4,     5,     6,     3,     7,     8,     4,     6,     5    )
$n_vals    = array(12,    12,    12,    4,     4,     4,     12,    12,    4,     12,    4,     12   )
$n_labels  = array("monthly","monthly","monthly","quarterly","quarterly","quarterly","monthly","monthly","quarterly","monthly","quarterly","monthly")
$t_vals    = array(5,     8,     10,    6,     5,     8,     10,    3,     5,     7,     6,     4    )
$ji = rand(0, 11)
$goal    = $goal_vals[$ji]
$r_pct   = $r_pcts[$ji]
$n       = $n_vals[$ji]
$n_label = $n_labels[$ji]
$t       = $t_vals[$ji]
$r       = $r_pct / 100

/* ---------- 3. Compute answers ---------- */
// Part a: periodic interest rate r/n (keep 6 decimal places for display)
$rn      = round($r / $n, 6)

// Part b: total number of periods nt
$nt      = $n * $t

// Part c: required periodic deposit via sinking fund formula
// m = A * (r/n) / ((1 + r/n)^(nt) - 1)
$factor  = (1 + $rn)^($nt)
$pmt     = round(abs(payment($r_pct, $nt, $n, 0, $goal)), 2)

/* ---------- 4. Display helpers ---------- */
$factor_disp = round($factor, 6)

/* ---------- 5. Answer setup ---------- */
$anstypes = array("numfunc", "numfunc", "numfunc")
$answer[0] = $rn
$answer[1] = $nt
$answer[2] = $pmt
$abstolerance[0] = 0.000001
$abstolerance[1] = 0
$abstolerance[2] = 0.01
$reqdecimals[0] = "r6"
$reqdecimals[1] = "r0"
$reqdecimals[2] = "r2"
$ansprompt[0] = "Periodic rate r/n = "
$ansprompt[1] = "Total periods nt = "
$ansprompt[2] = "Required deposit = $"

/* ---------- 6. Solution ---------- */
$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
  .sol-ans { margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><b>Sinking fund formula (solve for the deposit `m`):</b></p>
      <p>`A = m cdot ((1 + r/n)^{nt} - 1) / (r/n)` so `m = (A cdot r/n) / ((1 + r/n)^{nt} - 1)`</p>
      <p>Given: `A = $' . $goal . '`, `r = ' . $r_pct . '% = ' . $r . '`, `n = ' . $n . '` (' . $n_label . '), `t = ' . $t . '` years</p>
      <p><b>Part (a): Periodic interest rate</b></p>
      <p>`r/n = ' . $r . ' / ' . $n . ' = ' . $rn . '`</p>
      <div class="sol-ans"><b>Periodic rate `r/n = ' . $rn . '`</b></div>
      <p><b>Part (b): Total number of periods</b></p>
      <p>`nt = ' . $n . ' times ' . $t . ' = ' . $nt . '`</p>
      <div class="sol-ans"><b>Total periods `nt = ' . $nt . '`</b></div>
      <p><b>Part (c): Required deposit</b></p>
      <p>`(1 + r/n)^{nt} = (1 + ' . $rn . ')^{' . $nt . '} = ' . $factor_disp . '`</p>
      <p>`m = ' . $goal . ' cdot ' . $rn . ' / (' . $factor_disp . ' - 1)`</p>
      <p>`m = ' . round($goal * $rn, 4) . ' / ' . round($factor_disp - 1, 6) . '`</p>
      <div class="sol-ans"><b>Required deposit `m = $' . $pmt . '` per period</b></div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.4; color:#21242c; max-width:688px;">
<p style="margin:0 0 8px 0;">You are $ctx_desc. Your goal is to accumulate <b>$$goal</b> in <b>$t years</b> by making equal deposits at the end of each period into a $ctx_type earning <b>$r_pct%</b> per year, compounded <b>$n_label</b>.</p>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part a</span>What is the <b>periodic interest rate</b> `r/n`? (Round to 6 decimal places.)<span style="margin-left:8px;">$answerbox[0]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part b</span>How many <b>total periods</b> `nt` are there?<span style="margin-left:8px;">$answerbox[1]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part c</span>What <b>deposit amount</b> must you make each period to reach your goal? (Round to the nearest cent.)<span style="margin-left:8px;">$answerbox[2]</span>
</div>
</div>

// === ANSWER ===

$solutionguide
