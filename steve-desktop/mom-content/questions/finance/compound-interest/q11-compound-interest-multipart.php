// === NAME - DESCRIPTION: 6.1 Compound Interest - Comprehensive Multipart (r/n → FV → Interest → EAY) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("finance")

/* ---------- 1. Scenario context ---------- */
$scenarios = array(
  array("retirement account", "retire comfortably", "deposited"),
  array("college savings fund", "cover tuition costs", "set aside"),
  array("business investment account", "grow your capital", "invested"),
  array("health savings account", "cover future medical expenses", "placed")
)
$si = rand(0, count($scenarios)-1)
$ctx_account = $scenarios[$si][0]
$ctx_goal    = $scenarios[$si][1]
$ctx_verb    = $scenarios[$si][2]

/* ---------- 2. Randomize inputs via jointrandfrom ----------
   Parallel arrays: P (principal), r_pct (APR %), n (compounding freq), n_label, t (years)
   Kept to 8 pairs — wide range of outputs, all arithmetically clean.
--------------------------------------------------------------*/
$P_vals   = array(5000,  8000,  10000, 15000, 20000, 25000, 30000, 50000)
$r_pcts   = array(4,     5,     6,     3,     7,     5,     8,     4    )
$n_vals   = array(12,    4,     12,    4,     12,    365,   4,     12   )
$n_labels = array("monthly","quarterly","monthly","quarterly","monthly","daily","quarterly","monthly")
$t_vals   = array(10,    15,    20,    10,    5,     10,    15,    20   )

$ji       = rand(0, 7)
$P        = $P_vals[$ji]
$r_pct    = $r_pcts[$ji]
$n        = $n_vals[$ji]
$n_label  = $n_labels[$ji]
$t        = $t_vals[$ji]
$r        = $r_pct / 100

/* ---------- 3. Compute answers ---------- */
// Part (a): periodic rate r/n, rounded to 6 decimal places for display
$rn       = round($r / $n, 6)

// Part (b): future value FV = P(1 + r/n)^{nt}
$nt       = $n * $t
$FV       = round($P * (1 + $r / $n)^$nt, 2)

// Part (c): total interest earned
$interest = round($FV - $P, 2)

// Part (d): effective annual yield EAY = (1 + r/n)^n - 1, expressed as a percent rounded to 4 decimal places
$EAY_dec  = round((1 + $r / $n)^$n - 1, 6)
$EAY_pct  = round($EAY_dec * 100, 4)

/* ---------- 4. Display helpers for solution ---------- */
$base     = round(1 + $r / $n, 8)

/* ---------- 5. Answer setup ---------- */
$anstypes = array("number", "number", "number", "number")

// (a) periodic rate — decimal to 6 places
$answer[0]       = $rn
$abstolerance[0] = 0.0001
$reqdecimals[0]  = "r6"
$ansprompt[0]    = "r/n = "

// (b) future value — currency
$answer[1]       = $FV
$abstolerance[1] = 0.01
$reqdecimals[1]  = "r2"
$ansprompt[1]    = "FV = $"

// (c) interest earned — currency
$answer[2]       = $interest
$abstolerance[2] = 0.01
$reqdecimals[2]  = "r2"
$ansprompt[2]    = "Interest = $"

// (d) EAY as a percent — accept to 2 decimal places
$answer[3]       = $EAY_pct
$abstolerance[3] = 0.01
$reqdecimals[3]  = "r2"
$ansprompt[3]    = "EAY = "

/* ---------- 6. Solution ---------- */
$css_block = '
<style>
    .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
    .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
    .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; border:none; }
    .rubric-container details[open] summary { box-shadow: inset 0 -1px 0 #ccc; }
    .rubric-container summary::-webkit-details-marker { display:none; }
    .arrow-open { display:none; }
    .rubric-container details[open] .arrow-closed { display:none; }
    .rubric-container details[open] .arrow-open { display:inline; }
    .rubric-content { overflow:hidden; max-height:0; opacity:0; transition:max-height 300ms ease-out, opacity 300ms ease-out, padding 200ms ease-out; margin-top:0; background:#fafafa; box-sizing:border-box; padding:0 0.75em; }
    .rubric-container details[open] .rubric-content { max-height:3000px; opacity:1; padding:0.75em; }
    .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
    .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
    .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; user-select:text; }
    .row-colored { background:#fff9ea; }
</style>
<script>
document.addEventListener("DOMContentLoaded", function() {
  var details = document.querySelectorAll(".rubric-container details");
  details.forEach(function(det) {
    var content = det.querySelector(".rubric-content");
    det.addEventListener("toggle", function() {
      if (det.open) {
        content.style.maxHeight = content.scrollHeight + "px";
        content.style.opacity = "1";
      } else {
        content.style.maxHeight = content.scrollHeight + "px";
        content.offsetHeight;
        content.style.maxHeight = "0";
        content.style.opacity = "0";
      }
    });
    content.addEventListener("transitionend", function() {
      if (!det.open) content.style.maxHeight = null;
    });
  });
});
</script>'

$solutionguide = $css_block . '
<div class="rubric-container">
  <details>
    <summary>
      <span class="arrow-closed">&#9658;</span><span class="arrow-open">&#9660;</span>
      Step-by-Step Solution
    </summary>
    <div class="rubric-content">
      <p><b>Given:</b> `P = $' . $P . '`, `r = ' . $r_pct . '% = ' . $r . '`, compounded ' . $n_label . ' (`n = ' . $n . '`), `t = ' . $t . '` years</p>
      <table class="rubric-table">
        <tbody>
          <tr>
            <th style="width:28%;">Part</th>
            <th>Work</th>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Part (a)<br>Periodic Rate<br>`r/n`</b></td>
            <td>
              Divide the annual rate by the number of compounding periods per year:<br>
              `r/n = ' . $r . '/' . $n . ' = ' . $rn . '`
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Periodic rate `= ' . $rn . '`</b>
              </div>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Part (b)<br>Future Value<br>`FV = P(1+r/n)^{nt}`</b></td>
            <td>
              `n cdot t = ' . $n . ' times ' . $t . ' = ' . $nt . '` periods<br>
              `FV = ' . $P . '(1 + ' . $rn . ')^{' . $nt . '}`<br>
              `FV = ' . $P . '(' . $base . ')^{' . $nt . '}`
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Future Value `FV = $' . $FV . '`</b>
              </div>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Part (c)<br>Interest Earned<br>`I = FV - P`</b></td>
            <td>
              `I = ' . $FV . ' - ' . $P . '`
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Interest earned `= $' . $interest . '`</b>
              </div>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Part (d)<br>Effective Annual Yield<br>`EAY = (1+r/n)^n - 1`</b></td>
            <td>
              `EAY = (1 + ' . $rn . ')^{' . $n . '} - 1`<br>
              `EAY = (' . $base . ')^{' . $n . '} - 1`<br>
              `EAY = ' . $EAY_dec . '`<br>
              As a percent: `EAY = ' . $EAY_pct . '%`
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Effective Annual Yield `= ' . $EAY_pct . '%`</b>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.5; color:#21242c; max-width:688px;">

<p style="margin:0 0 10px 0;">
You have <b>$$P</b> $ctx_verb in a <b>$ctx_account</b> to $ctx_goal. The account earns
<b>$r_pct% APR</b> compounded <b>$n_label</b> (`n = $n`) for <b>$t years</b>.
Work through each part below using the same principal, rate, and time.
</p>

<div style="background:#f0f4ff; border:1px solid #c7d7fc; border-radius:10px; padding:10px 16px; margin:0 0 10px 0; font-size:14px; color:#1a3a7a;">
  <b>Formula reference:</b>
  `FV = P(1 + r/n)^{nt}` &nbsp;|&nbsp;
  `EAY = (1 + r/n)^n - 1`
</div>

<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 2px 4px rgba(0,0,0,0.06);">
  <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part a</span>
  Find the <b>periodic interest rate</b> `r/n` (as a decimal, rounded to 6 decimal places).
  <span style="margin-left:8px;">$answerbox[0]</span>
</div>

<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 2px 4px rgba(0,0,0,0.06);">
  <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part b</span>
  Find the <b>future value</b> of the account after $t years. Round to the nearest cent.
  <span style="margin-left:8px;">$answerbox[1]</span>
</div>

<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 2px 4px rgba(0,0,0,0.06);">
  <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part c</span>
  Find the <b>total interest earned</b> (`FV - P`) over the $t years. Round to the nearest cent.
  <span style="margin-left:8px;">$answerbox[2]</span>
</div>

<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 2px 4px rgba(0,0,0,0.06);">
  <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part d</span>
  Find the <b>effective annual yield (EAY)</b> as a <b>percent</b>, rounded to 2 decimal places.<br>
  <span style="font-size:13px; color:#555;">(Use `EAY = (1 + r/n)^n - 1`, then convert to %.)</span>
  <span style="margin-left:8px;">$answerbox[3]</span>%
</div>

</div>

// === ANSWER ===

$solutionguide
