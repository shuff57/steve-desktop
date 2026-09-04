// === NAME - DESCRIPTION: 6.4 Installment Payment and Present Value of an Annuity ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Part A: Installment Payment on a Loan
// Formula: P(1+r/n)^(nt) = m[(1+r/n)^(nt) - 1] / (r/n), solve for m
$loan_amounts = array(8000, 12000, 15000, 18000, 22000, 25000, 30000)
$P = $loan_amounts[rand(0,6)]
$rate_pcts_a = array(3, 4, 5, 6, 7, 8)
$rate_pct_a = $rate_pcts_a[rand(0,5)]
$r_a = $rate_pct_a / 100
$n_a = 12
$t_years_a = array(3, 4, 5, 6)
$t_a = $t_years_a[rand(0,3)]
$nt_a = $n_a * $t_a
$rn_a = $r_a / $n_a
$factor_a = (1 + $rn_a)^($nt_a)
$m_a = round($P * $factor_a * $rn_a / ($factor_a - 1), 2)

// Part B: Present Value of an Annuity (withdrawal from retirement)
// Same formula but solve for m given P
$retirement_amounts = array(200000, 300000, 400000, 500000, 750000)
$P_b = $retirement_amounts[rand(0,4)]
$rate_pcts_b = array(4, 5, 6, 7, 8)
$rate_pct_b = $rate_pcts_b[rand(0,4)]
$r_b = $rate_pct_b / 100
$n_b = 12
$t_years_b = array(15, 20, 25, 30)
$t_b = $t_years_b[rand(0,3)]
$nt_b = $n_b * $t_b
$rn_b = $r_b / $n_b
$factor_b = (1 + $rn_b)^($nt_b)
$m_b = round($P_b * $factor_b * $rn_b / ($factor_b - 1), 2)

// Answer setup
$anstypes = array("number","number")
$answer[0] = $m_a
$answer[1] = $m_b
$abstolerance = 5
$reqdecimals = "r2"
$ansprompt[0] = "Monthly payment: \$"
$ansprompt[1] = "Monthly withdrawal: \$"

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
      <p><b>Part A: Installment Payment</b></p>
      <p>Use the formula: `P(1 + r/n)^{nt} = m cdot [(1 + r/n)^{nt} - 1] / (r/n)`</p>
      <p>`P = ' . $P . '`, `r = ' . $r_a . '`, `n = 12`, `t = ' . $t_a . '`</p>
      <p>`r/n = ' . $r_a . '/12 = ' . round($rn_a, 8) . '`</p>
      <p>`(1 + r/n)^{nt} = (1 + ' . round($rn_a, 8) . ')^{' . $nt_a . '} = ' . round($factor_a, 6) . '`</p>
      <p>`m = (' . $P . ' times ' . round($factor_a, 6) . ' times ' . round($rn_a, 8) . ') / (' . round($factor_a, 6) . ' - 1)`</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Monthly payment `m = $' . $m_a . '`</b>
      </div>
      <hr>
      <p><b>Part B: Present Value of an Annuity</b></p>
      <p>Same formula, solve for `m`.</p>
      <p>`P = ' . $P_b . '`, `r = ' . $r_b . '`, `n = 12`, `t = ' . $t_b . '`</p>
      <p>`(1 + r/n)^{nt} = ' . round($factor_b, 6) . '`</p>
      <p>`m = (' . $P_b . ' times ' . round($factor_b, 6) . ' times ' . round($rn_b, 8) . ') / (' . round($factor_b, 6) . ' - 1)`</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Monthly withdrawal `m = $' . $m_b . '`</b>
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.4; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:8px; padding:8px 12px; margin:0 0 4px 0; box-shadow:0 2px 4px rgba(0,0,0,0.06); width:100%; box-sizing:border-box;">
    <p style="margin:0 0 6px 0;"><b>Part A: Installment Payment on a Loan</b></p>
    <p style="margin:0 0 6px 0;">You take out a loan of <b>$$P</b> at <b>$rate_pct_a%</b> interest compounded monthly, to be repaid over <b>$t_a years</b>.</p>
    <p style="margin:0 0 6px 0;">What is the monthly payment?</p>
    <p>$answerbox[0]</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:8px; padding:8px 12px; margin:0; box-shadow:0 2px 4px rgba(0,0,0,0.06); width:100%; box-sizing:border-box;">
    <p style="margin:0 0 6px 0;"><b>Part B: Present Value of an Annuity</b></p>
    <p style="margin:0 0 6px 0;">You have <b>$$P_b</b> saved for retirement in an account that earns <b>$rate_pct_b%</b> interest compounded monthly. You want to make equal monthly withdrawals for <b>$t_b years</b>.</p>
    <p style="margin:0 0 6px 0;">How much can you withdraw each month?</p>
    <p>$answerbox[1]</p>
  </div>
</div>

// === ANSWER ===

$solutionguide
