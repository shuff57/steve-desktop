// === NAME - DESCRIPTION: 6.4 Loan Payment and True Cost of Borrowing ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

/* ---------- 1. Scenario context ---------- */
$contexts = array(
  array("auto loan", "purchase a vehicle", "car"),
  array("personal loan", "consolidate debt", "loan"),
  array("small business loan", "fund a startup expense", "loan"),
  array("home improvement loan", "renovate your home", "loan")
)
$ci = rand(0, count($contexts)-1)
$ctx_type = $contexts[$ci][0]
$ctx_verb = $contexts[$ci][1]
$ctx_noun = $contexts[$ci][2]

/* ---------- 2. Randomize via jointrandfrom (4 scenarios) ---------- */
$P_vals    = array(10000, 15000, 20000, 25000)
$r_pcts    = array(6,     5,     7,     4    )
$t_vals    = array(3,     5,     7,     5    )
$ji = rand(0, 3)
$P     = $P_vals[$ji]
$r_pct = $r_pcts[$ji]
$t     = $t_vals[$ji]
$n     = 12
$r     = $r_pct / 100

/* ---------- 3. Compute answers ---------- */
// Monthly payment: PMT = P * (r/n) / (1 - (1 + r/n)^(-n*t))
$rn      = $r / $n
$exp     = $n * $t
$factor  = (1 + $rn)^(-$exp)
$PMT     = round($P * $rn / (1 - $factor), 2)

// Total paid over life of loan
$total_paid = round($PMT * $n * $t, 2)

// Total interest
$total_interest = round($total_paid - $P, 2)

/* ---------- 4. Display helpers ---------- */
$rn_disp      = round($rn, 8)
$factor_full  = (1 + $rn)^($exp)
$factor_disp  = round($factor_full, 6)
$factor_neg   = round($factor, 6)

/* ---------- 5. Answer setup ---------- */
$anstypes = array("numfunc", "numfunc", "numfunc")
$answer[0] = $PMT
$answer[1] = $total_paid
$answer[2] = $total_interest
$abstolerance[0] = 0.05
$abstolerance[1] = 1.00
$abstolerance[2] = 1.00
$reqdecimals[0] = "r2"
$reqdecimals[1] = "r2"
$reqdecimals[2] = "r2"
$ansprompt[0] = "Monthly payment = $"
$ansprompt[1] = "Total paid = $"
$ansprompt[2] = "Total interest = $"

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
      <p><b>Formula (monthly loan payment):</b></p>
      <p>`PMT = P cdot (r/n) / (1 - (1 + r/n)^{-nt})`</p>
      <p>Given: `P = $' . $P . '`, `r = ' . $r_pct . '% = ' . $r . '`, `n = 12`, `t = ' . $t . '` years</p>
      <p><b>Part (a) -- Monthly Payment</b></p>
      <p>`r/n = ' . $r . '/12 = ' . $rn_disp . '`</p>
      <p>`nt = 12 times ' . $t . ' = ' . $exp . '`</p>
      <p>`(1 + r/n)^{-nt} = (1 + ' . $rn_disp . ')^{-' . $exp . '} = ' . $factor_neg . '`</p>
      <p>`PMT = ' . $P . ' times ' . $rn_disp . ' / (1 - ' . $factor_neg . ')`</p>
      <div class="sol-ans"><b>Monthly payment `PMT = $' . $PMT . '`</b></div>
      <p><b>Part (b) -- Total Amount Paid</b></p>
      <p>`PMT times n times t = ' . $PMT . ' times 12 times ' . $t . ' = $' . $total_paid . '`</p>
      <div class="sol-ans"><b>Total paid = `$' . $total_paid . '`</b></div>
      <p><b>Part (c) -- Total Interest</b></p>
      <p>`Total paid - Principal = $' . $total_paid . ' - $' . $P . ' = $' . $total_interest . '`</p>
      <div class="sol-ans"><b>Total interest = `$' . $total_interest . '`</b></div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.4; color:#21242c; max-width:688px;">
<p style="margin:0 0 8px 0;">You take out a <b>$ctx_type</b> of <b>$$P</b> at <b>$r_pct%</b> annual interest, compounded monthly, to $ctx_verb. The loan term is <b>$t years</b>.</p>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part a</span>What is the monthly payment?<span style="margin-left:8px;">$answerbox[0]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part b</span>What is the total amount you will pay over the life of the $ctx_noun?<span style="margin-left:8px;">$answerbox[1]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part c</span>How much of that total is interest (the true cost of borrowing)?<span style="margin-left:8px;">$answerbox[2]</span>
</div>
</div>

// === ANSWER ===

$solutionguide
