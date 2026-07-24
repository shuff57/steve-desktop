// === NAME - DESCRIPTION: 6.4 Amortization Schedule (First Two Payments) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Loan parameters
$loan_amounts = array(5000, 8000, 10000, 15000, 20000, 25000)
$P = $loan_amounts[rand(0,5)]
$rate_pcts = array(3, 4, 5, 6, 7, 8, 9)
$rate_pct = $rate_pcts[rand(0,6)]
$r = $rate_pct / 100
$n = 12
$t_choices = array(2, 3, 4, 5)
$t = $t_choices[rand(0,3)]
$nt = $n * $t
$rn = $r / $n

// Monthly payment
$factor = (1 + $rn)^($nt)
$m = round($P * $factor * $rn / ($factor - 1), 2)

// Payment 1
$int1 = round($P * $rn, 2)
$prin1 = round($m - $int1, 2)
$bal1 = round($P - $prin1, 2)

// Payment 2
$int2 = round($bal1 * $rn, 2)
$prin2 = round($m - $int2, 2)
$bal2 = round($bal1 - $prin2, 2)

// Answers: payment, int1, prin1, bal1, int2, prin2, bal2
$anstypes = array("number","number","number","number","number","number","number")
$answer[0] = $m
$answer[1] = $int1
$answer[2] = $prin1
$answer[3] = $bal1
$answer[4] = $int2
$answer[5] = $prin2
$answer[6] = $bal2
$abstolerance = 0.05
$reqdecimals = "r2"

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
      <p><b>Step 1: Find the monthly payment</b></p>
      <p>`P = ' . $P . '`, `r = ' . $r . '`, `n = 12`, `t = ' . $t . '`</p>
      <p>`r/n = ' . round($rn, 8) . '`, &nbsp; `(1 + r/n)^{nt} = ' . round($factor, 6) . '`</p>
      <p>`m = (' . $P . ' times ' . round($factor, 6) . ' times ' . round($rn, 8) . ') / (' . round($factor, 6) . ' - 1)`</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Monthly payment `= $' . $m . '`</b>
      </div>
      <p><b>Step 2: Build the amortization rows</b></p>
      <p>For each payment:</p>
      <p>`"Interest" = "Previous Balance" times (r/n)`<br>
         `"Principal" = "Payment" - "Interest"`<br>
         `"New Balance" = "Previous Balance" - "Principal"`</p>
      <p><b>Payment 1:</b></p>
      <p>`"Interest" = ' . $P . ' times ' . round($rn, 8) . ' = ' . $int1 . '`<br>
         `"Principal" = ' . $m . ' - ' . $int1 . ' = ' . $prin1 . '`<br>
         `"Balance" = ' . $P . ' - ' . $prin1 . ' = ' . $bal1 . '`</p>
      <p><b>Payment 2:</b></p>
      <p>`"Interest" = ' . $bal1 . ' times ' . round($rn, 8) . ' = ' . $int2 . '`<br>
         `"Principal" = ' . $m . ' - ' . $int2 . ' = ' . $prin2 . '`<br>
         `"Balance" = ' . $bal1 . ' - ' . $prin2 . ' = ' . $bal2 . '`</p>
      <table style="border-collapse:collapse; width:100%; margin:8px 0;">
        <tr style="background:#f7f9fa;">
          <th style="border:1px solid #dee1e3; padding:6px 10px;">Payment #</th>
          <th style="border:1px solid #dee1e3; padding:6px 10px;">Interest</th>
          <th style="border:1px solid #dee1e3; padding:6px 10px;">Principal</th>
          <th style="border:1px solid #dee1e3; padding:6px 10px;">Balance</th>
        </tr>
        <tr>
          <td style="border:1px solid #dee1e3; padding:6px 10px; text-align:center;">0</td>
          <td style="border:1px solid #dee1e3; padding:6px 10px;"></td>
          <td style="border:1px solid #dee1e3; padding:6px 10px;"></td>
          <td style="border:1px solid #dee1e3; padding:6px 10px; text-align:center;">$' . $P . '</td>
        </tr>
        <tr>
          <td style="border:1px solid #dee1e3; padding:6px 10px; text-align:center;">1</td>
          <td style="border:1px solid #dee1e3; padding:6px 10px;">$' . $int1 . '</td>
          <td style="border:1px solid #dee1e3; padding:6px 10px;">$' . $prin1 . '</td>
          <td style="border:1px solid #dee1e3; padding:6px 10px;">$' . $bal1 . '</td>
        </tr>
        <tr>
          <td style="border:1px solid #dee1e3; padding:6px 10px; text-align:center;">2</td>
          <td style="border:1px solid #dee1e3; padding:6px 10px;">$' . $int2 . '</td>
          <td style="border:1px solid #dee1e3; padding:6px 10px;">$' . $prin2 . '</td>
          <td style="border:1px solid #dee1e3; padding:6px 10px;">$' . $bal2 . '</td>
        </tr>
      </table>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.4; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:8px; padding:8px 12px; margin:0 0 4px 0; box-shadow:0 2px 4px rgba(0,0,0,0.06); width:100%; box-sizing:border-box;">
    <p style="margin:0 0 6px 0;"><b>Amortization Schedule</b></p>
    <p style="margin:0 0 6px 0;">You take out a loan of <b>$$P</b> at <b>$rate_pct%</b> interest compounded monthly, to be repaid over <b>$t years</b>.</p>
    <p style="margin:0 0 6px 0;">Find the monthly payment, then fill in the first two rows of the amortization table. Round all answers to 2 decimal places.</p>
    <p>$ansprompt[0] $answerbox[0]</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:8px; padding:8px 12px; margin:0; box-shadow:0 2px 4px rgba(0,0,0,0.06); width:100%; box-sizing:border-box;">
    <table style="border-collapse:collapse; width:100%; margin:4px 0;">
      <tr style="background:#f7f9fa;">
        <th style="border:1px solid #dee1e3; padding:6px 10px;">Payment #</th>
        <th style="border:1px solid #dee1e3; padding:6px 10px;">Interest</th>
        <th style="border:1px solid #dee1e3; padding:6px 10px;">Principal</th>
        <th style="border:1px solid #dee1e3; padding:6px 10px;">Balance</th>
      </tr>
      <tr>
        <td style="border:1px solid #dee1e3; padding:6px 10px; text-align:center;">0</td>
        <td style="border:1px solid #dee1e3; padding:6px 10px;"></td>
        <td style="border:1px solid #dee1e3; padding:6px 10px;"></td>
        <td style="border:1px solid #dee1e3; padding:6px 10px; text-align:center;">$$P</td>
      </tr>
      <tr>
        <td style="border:1px solid #dee1e3; padding:6px 10px; text-align:center;">1</td>
        <td style="border:1px solid #dee1e3; padding:6px 10px;">$answerbox[1]</td>
        <td style="border:1px solid #dee1e3; padding:6px 10px;">$answerbox[2]</td>
        <td style="border:1px solid #dee1e3; padding:6px 10px;">$answerbox[3]</td>
      </tr>
      <tr>
        <td style="border:1px solid #dee1e3; padding:6px 10px; text-align:center;">2</td>
        <td style="border:1px solid #dee1e3; padding:6px 10px;">$answerbox[4]</td>
        <td style="border:1px solid #dee1e3; padding:6px 10px;">$answerbox[5]</td>
        <td style="border:1px solid #dee1e3; padding:6px 10px;">$answerbox[6]</td>
      </tr>
    </table>
  </div>
</div>

// === ANSWER ===

$solutionguide
