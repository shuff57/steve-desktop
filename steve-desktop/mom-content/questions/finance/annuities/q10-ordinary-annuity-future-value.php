// === NAME - DESCRIPTION: 6.2 Ordinary Annuity - Future Value and Total Contributions ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("finance")

/* ---------- 1. Scenario context ---------- */
$contexts = array(
  array("deposit", "retirement account", "retirement nest egg"),
  array("contribute", "college savings plan", "tuition fund"),
  array("set aside", "investment account", "long-term savings goal"),
  array("transfer", "brokerage account", "wealth-building fund")
)
$ci = rand(0, count($contexts)-1)
$ctx_verb = $contexts[$ci][0]
$ctx_type = $contexts[$ci][1]
$ctx_goal = $contexts[$ci][2]

/* ---------- 2. Randomize via jointrandfrom (4 scenarios) ---------- */
$PMT_vals  = array(50,   100,  150,  200 )
$r_pcts    = array(5,    6,    4,    8   )
$t_vals    = array(20,   15,   25,   10  )
$ji = rand(0, 3)
$PMT   = $PMT_vals[$ji]
$r_pct = $r_pcts[$ji]
$t     = $t_vals[$ji]
$n     = 12
$r     = $r_pct / 100

/* ---------- 3. Compute answers ---------- */
// Ordinary annuity FV: PMT * ((1 + r/n)^(n*t) - 1) / (r/n)
$rn     = $r / $n
$exp    = $n * $t
$factor = (1 + $rn)^($exp)
$FV     = round($PMT * ($factor - 1) / $rn, 2)

// Total contributions (principal paid in)
$total_contrib = $PMT * $n * $t

/* ---------- 4. Display helpers ---------- */
$rn_disp     = round($rn, 8)
$factor_disp = round($factor, 6)

/* ---------- 5. Answer setup ---------- */
$anstypes = array("number", "number")
$answer[0] = $FV
$answer[1] = $total_contrib
$abstolerance[0] = 0.01
$abstolerance[1] = 0.01
$reqdecimals[0] = "r2"
$reqdecimals[1] = "r2"
$ansprompt[0] = "Future value FV = $"
$ansprompt[1] = "Total contributions = $"

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
      <p><b>Formula (ordinary annuity, end-of-period deposits):</b></p>
      <p>`FV = PMT cdot ((1 + r/n)^{nt} - 1) / (r/n)`</p>
      <p>Given: `PMT = $' . $PMT . '`, `r = ' . $r_pct . '% = ' . $r . '`, `n = 12` (monthly), `t = ' . $t . '` years</p>
      <p><b>Part (a): Future Value</b></p>
      <p>`r/n = ' . $r . '/12 = ' . $rn_disp . '`</p>
      <p>`nt = 12 times ' . $t . ' = ' . $exp . '`</p>
      <p>`(1 + r/n)^{nt} = (1 + ' . $rn_disp . ')^{' . $exp . '} = ' . $factor_disp . '`</p>
      <p>`FV = ' . $PMT . ' cdot (' . $factor_disp . ' - 1) / ' . $rn_disp . '`</p>
      <div class="sol-ans"><b>Future value `FV = $' . $FV . '`</b></div>
      <p><b>Part (b): Total Contributions</b></p>
      <p>`PMT times n times t = ' . $PMT . ' times 12 times ' . $t . ' = $' . $total_contrib . '`</p>
      <p>The difference `$' . $FV . ' - $' . $total_contrib . ' = $' . round($FV - $total_contrib, 2) . '` is the interest earned.</p>
      <div class="sol-ans"><b>Total contributions = `$' . $total_contrib . '`</b></div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.4; color:#21242c; max-width:688px;">
<p style="margin:0 0 8px 0;">You $ctx_verb <b>$$PMT per month</b> at the end of each month into a $ctx_type earning <b>$r_pct%</b> per year, compounded monthly. You keep this up for <b>$t years</b>.</p>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part a</span>What is the future value of the annuity after $t years?<span style="margin-left:8px;">$answerbox[0]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part b</span>What is the total amount you actually deposited over those $t years (no interest)?<span style="margin-left:8px;">$answerbox[1]</span>
</div>
</div>

// === ANSWER ===

$solutionguide
