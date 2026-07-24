// === NAME - DESCRIPTION: ANOVA summary table - Compute MS, F, and df from given SS ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc","numfunc","numfunc","numfunc")

// Three scenarios. Each gives k groups, n total, SST (between), SSE (within).
// Parts:
//   a. df_between = k - 1
//   b. df_within = n - k
//   c. MSE = SSE / df_within (compute MSB too internally)
//   d. F = MSB / MSE

$intros = array(
  "A nutritionist compares mean caloric intake across <b>k = 4</b> diet plans. A random sample of <b>n = 60</b> participants is split across the four plans. The ANOVA partition gives `SST_{B} = 24000` (between-group sum of squares) and `SST_{W} = 86800` (within-group sum of squares).",
  "A trainer compares mean vertical jump across <b>k = 3</b> training programs. A random sample of <b>n = 45</b> athletes is divided across programs. `SST_{B} = 120` and `SST_{W} = 504`.",
  "A teacher compares mean reading scores across <b>k = 5</b> elementary schools using random samples. Total `n = 80` students. `SST_{B} = 1820` and `SST_{W} = 6750`."
)
$ks    = array(4,  3,  5)
$ntots = array(60, 45, 80)
$ssbs  = array(24000, 120,  1820)
$ssws  = array(86800, 504,  6750)
$picked = jointrandfrom($intros, $ks, $ntots, $ssbs, $ssws)
$intro= $picked[0]
$k    = $picked[1]
$ntot = $picked[2]
$ssb  = $picked[3]
$ssw  = $picked[4]

$dfb = $k - 1
$dfw = $ntot - $k
$msb = round($ssb / $dfb, 3)
$mse = round($ssw / $dfw, 3)
$f   = round($msb / $mse, 3)

$answer[0] = $dfb
$answer[1] = $dfw
$answer[2] = $mse
$answer[3] = $f

$abstolerance[0] = 0.5
$abstolerance[1] = 0.5
$abstolerance[2] = 0.5
$abstolerance[3] = 0.05

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
      <p><b>ANOVA formulas.</b></p>
      <ul>
        <li>`df_{B} = k - 1`</li>
        <li>`df_{W} = n - k`</li>
        <li>`MSB = SST_{B} / df_{B}`</li>
        <li>`MSE = SST_{W} / df_{W}`</li>
        <li>`F = MSB / MSE`</li>
      </ul>
      <p><b>Part a.</b> `df_{B} = k - 1 = ' . $k . ' - 1 = ' . $dfb . '`</p>
      <p><b>Part b.</b> `df_{W} = n - k = ' . $ntot . ' - ' . $k . ' = ' . $dfw . '`</p>
      <p><b>Part c.</b> `MSE = SST_{W} / df_{W} = ' . $ssw . ' / ' . $dfw . ' approx ' . $mse . '`</p>
      <p><b>Part d.</b> `MSB = SST_{B} / df_{B} = ' . $ssb . ' / ' . $dfb . ' approx ' . $msb . '`. Then `F = MSB / MSE = ' . $msb . ' / ' . $mse . ' approx ' . $f . '`.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$intro</p>
    <p style="margin:8px 0 0 0;">Complete the ANOVA table by computing the degrees of freedom, mean square error, and F-statistic.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Between-group degrees of freedom: $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Within-group degrees of freedom: $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Mean square error (`MSE`): $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> F-statistic: $answerbox[3]
  </div>
</div>


// === ANSWER ===

$solutionguide
