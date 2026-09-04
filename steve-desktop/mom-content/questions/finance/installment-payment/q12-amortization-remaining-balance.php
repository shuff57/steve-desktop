// === NAME - DESCRIPTION: 6.4 Amortization: Remaining Balance After N Payments ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("finance")

/* ---------- 1. Scenario context ---------- */
$contexts = array(
  array("car loan", "purchase a vehicle", "car"),
  array("personal loan", "consolidate debt", "loan"),
  array("home equity loan", "fund a home renovation", "loan"),
  array("auto loan", "buy a used car", "car")
)
$ci = rand(0, count($contexts)-1)
$ctx_type = $contexts[$ci][0]
$ctx_verb = $contexts[$ci][1]
$ctx_noun = $contexts[$ci][2]

/* ---------- 2. Randomize loan parameters ---------- */
// Four coordinated scenarios: principal, APR%, term (years), payments-made
$P_vals    = array(18000, 24000, 30000, 15000)
$r_pcts    = array(5,     6,     7,     4    )
$t_vals    = array(5,     6,     5,     4    )
$N_vals    = array(24,    30,    36,    24   )
$ji = rand(0, 3)
$P     = $P_vals[$ji]
$r_pct = $r_pcts[$ji]
$t     = $t_vals[$ji]
$N_paid = $N_vals[$ji]
$n     = 12
$r     = $r_pct / 100
$rn    = $r / $n
$nt    = $n * $t

/* ---------- 3. Compute monthly payment ---------- */
// PMT = P * (r/n) / (1 - (1 + r/n)^{-nt})
$factor_neg = (1 + $rn)^(-$nt)
$PMT = round($P * $rn / (1 - $factor_neg), 2)

/* ---------- 4. Remaining balance after N_paid payments ---------- */
// Balance = PMT * (1 - (1 + r/n)^{-(nt - N_paid)}) / (r/n)
// = PV of the remaining (nt - N_paid) payments
$remaining = $nt - $N_paid
$factor_rem = (1 + $rn)^(-$remaining)
$balance = round($PMT * (1 - $factor_rem) / $rn, 2)

/* ---------- 5. Interest paid so far ---------- */
$total_paid_so_far = round($PMT * $N_paid, 2)
$principal_paid = round($P - $balance, 2)
$interest_paid = round($total_paid_so_far - $principal_paid, 2)

/* ---------- 6. Display helpers ---------- */
$rn_disp        = round($rn, 8)
$factor_neg_disp = round($factor_neg, 6)
$factor_rem_disp = round($factor_rem, 6)

/* ---------- 7. Answer setup ---------- */
$anstypes = array("numfunc", "numfunc", "numfunc")
$answer[0] = $PMT
$answer[1] = $balance
$answer[2] = $interest_paid
$abstolerance[0] = 0.05
$abstolerance[1] = 1.00
$abstolerance[2] = 1.00
$reqdecimals[0] = "r2"
$reqdecimals[1] = "r2"
$reqdecimals[2] = "r2"
$ansprompt[0] = "Monthly payment = $"
$ansprompt[1] = "Remaining balance = $"
$ansprompt[2] = "Total interest paid so far = $"

/* ---------- 8. Solution ---------- */
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
      <p><b>Part (a): Monthly Payment</b></p>
      <p>`PMT = P cdot (r/n) / (1 - (1 + r/n)^{-nt})`</p>
      <p>Given: `P = $' . $P . '`, `r = ' . $r_pct . '% = ' . $r . '`, `n = 12`, `t = ' . $t . '` yr</p>
      <p>`r/n = ' . $rn_disp . '`, &nbsp; `nt = ' . $nt . '`</p>
      <p>`(1 + r/n)^{-nt} = (1 + ' . $rn_disp . ')^{-' . $nt . '} = ' . $factor_neg_disp . '`</p>
      <p>`PMT = ' . $P . ' times ' . $rn_disp . ' / (1 - ' . $factor_neg_disp . ')`</p>
      <div class="sol-ans"><b>Monthly payment `PMT = $' . $PMT . '`</b></div>
      <p><b>Part (b): Remaining Balance After ' . $N_paid . ' Payments</b></p>
      <p>The remaining balance equals the present value of the <b>' . $remaining . ' payments still owed</b>:</p>
      <p>`B = PMT cdot (1 - (1 + r/n)^{-k}) / (r/n)` &nbsp; where `k = nt - N = ' . $remaining . '`</p>
      <p>`(1 + r/n)^{-k} = (1 + ' . $rn_disp . ')^{-' . $remaining . '} = ' . $factor_rem_disp . '`</p>
      <p>`B = ' . $PMT . ' times (1 - ' . $factor_rem_disp . ') / ' . $rn_disp . '`</p>
      <div class="sol-ans"><b>Remaining balance `B = $' . $balance . '`</b></div>
      <p><b>Part (c): Total Interest Paid So Far</b></p>
      <p>Total paid: `PMT times N = ' . $PMT . ' times ' . $N_paid . ' = $' . $total_paid_so_far . '`</p>
      <p>Principal paid: `P - B = $' . $P . ' - $' . $balance . ' = $' . $principal_paid . '`</p>
      <p>Interest paid: `$' . $total_paid_so_far . ' - $' . $principal_paid . ' = $' . $interest_paid . '`</p>
      <div class="sol-ans"><b>Total interest paid so far `= $' . $interest_paid . '`</b></div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.4; color:#21242c; max-width:688px;">
<p style="margin:0 0 8px 0;">You took out a <b>$ctx_type</b> of <b>$$P</b> at <b>$r_pct%</b> APR, compounded monthly, to $ctx_verb. The loan term is <b>$t years</b>. You have now made <b>$N_paid payments</b> and want to know where you stand.</p>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part a</span>What is the monthly payment on this $ctx_noun?<span style="margin-left:8px;">$answerbox[0]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part b</span>What is the remaining principal balance after payment <b>#$N_paid</b>? (Use the present value of the remaining payments.)<span style="margin-left:8px;">$answerbox[1]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part c</span>Of the $N_paid payments you have made, how much has gone to interest?<span style="margin-left:8px;">$answerbox[2]</span>
</div>
</div>

// === ANSWER ===

$solutionguide
