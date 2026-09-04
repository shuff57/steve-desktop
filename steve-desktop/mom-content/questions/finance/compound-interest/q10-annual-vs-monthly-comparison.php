// === NAME - DESCRIPTION: 6.1 Compound Interest - Annual vs. Monthly Comparison ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("finance")

/* ---------- 1. Scenario context ---------- */
$contexts = array(
  array("deposited", "savings account", "reach your savings goal"),
  array("invested", "certificate of deposit", "grow your investment"),
  array("placed", "high-yield account", "build your emergency fund"),
  array("put", "money market account", "maximize your return")
)
$ci = rand(0, count($contexts)-1)
$ctx_verb = $contexts[$ci][0]
$ctx_type = $contexts[$ci][1]
$ctx_goal = $contexts[$ci][2]

/* ---------- 2. Randomize principal, rate, time via jointrandfrom ---------- */
$P_vals    = array(5000, 8000, 10000, 12000, 15000, 18000, 20000, 25000)
$r_pcts    = array(3,    4,    5,     4,     6,     5,     7,     6    )
$t_vals    = array(5,    3,    10,    7,     5,     10,    3,     7    )
$ji = rand(0, 7)
$P      = $P_vals[$ji]
$r_pct  = $r_pcts[$ji]
$t      = $t_vals[$ji]
$r      = $r_pct / 100

/* ---------- 3. Compute answers ---------- */
// Part a: annual compounding (n=1)
$A_ann = round($P * (1 + $r / 1)^(1 * $t), 2)

// Part b: monthly compounding (n=12)
$A_mon = round($P * (1 + $r / 12)^(12 * $t), 2)

// Part c: which is more? monthly always >= annual for same r, t, P
$winner = "monthly"
$loser  = "annual"

/* ---------- 4. Display helpers ---------- */
$rn_ann   = round($r / 1,  8)
$rn_mon   = round($r / 12, 8)
$exp_ann  = 1 * $t
$exp_mon  = 12 * $t
$base_ann = round(1 + $rn_ann, 8)
$base_mon = round(1 + $rn_mon, 8)

/* ---------- 5. Answer setup ---------- */
$anstypes = array("numfunc", "numfunc", "choices")
$answer[0] = $A_ann
$answer[1] = $A_mon
$answer[2] = 0
$abstolerance[0] = 0.01
$abstolerance[1] = 0.01
$reqdecimals[0] = "r2"
$reqdecimals[1] = "r2"
$ansprompt[0] = "A (annual) = $"
$ansprompt[1] = "A (monthly) = $"
$choices[2] = array("Monthly compounding gives more", "Annual compounding gives more", "Both give exactly the same amount")
$displayformat[2] = "select"

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
      <p><b>Formula: `A = P(1 + r/n)^{nt}`</b></p>
      <p>Given: `P = $' . $P . '`, `r = ' . $r_pct . '% = ' . $r . '`, `t = ' . $t . '` years</p>
      <p><b>Part (a): Annual compounding (`n = 1`)</b></p>
      <p>`A = ' . $P . '(1 + ' . $r . '/1)^{1 cdot ' . $t . '}`</p>
      <p>`A = ' . $P . '(' . $base_ann . ')^{' . $exp_ann . '}`</p>
      <div class="sol-ans"><b>Annual future value `A = $' . $A_ann . '`</b></div>
      <p><b>Part (b): Monthly compounding (`n = 12`)</b></p>
      <p>`A = ' . $P . '(1 + ' . $r . '/12)^{12 cdot ' . $t . '}`</p>
      <p>`A = ' . $P . '(' . $base_mon . ')^{' . $exp_mon . '}`</p>
      <div class="sol-ans"><b>Monthly future value `A = $' . $A_mon . '`</b></div>
      <p><b>Part (c): Comparison</b></p>
      <p>Monthly compounding applies interest more frequently, so your money grows slightly faster. Monthly compounding gives more: `$' . $A_mon . ' > $' . $A_ann . '`.</p>
      <div class="sol-ans"><b>Monthly compounding gives more.</b></div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.4; color:#21242c; max-width:688px;">
<p style="margin:0 0 8px 0;">You have <b>$$P</b> $ctx_verb in a $ctx_type earning <b>$r_pct%</b> per year for <b>$t years</b>. Compare what happens under two compounding schedules.</p>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part a</span>Find the future value with <b>annual compounding</b> (`n = 1`).<span style="margin-left:8px;">$answerbox[0]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part b</span>Find the future value with <b>monthly compounding</b> (`n = 12`).<span style="margin-left:8px;">$answerbox[1]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part c</span>Which compounding schedule gives a larger future value?<span style="margin-left:8px;">$answerbox[2]</span>
</div>
</div>

// === ANSWER ===

$solutionguide
