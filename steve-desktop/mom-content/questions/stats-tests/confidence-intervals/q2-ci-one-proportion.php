// === NAME - DESCRIPTION: Construct a confidence interval for one proportion with randomized story contexts ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats")

$anstypes = array("number","number")

// Randomized story contexts
$ci = rand(0,4)
$populations = array("people","adults","registered voters","employees","college students")
$characteristics = array("had kids","preferred online shopping","supported the new policy","exercised at least 3 times per week","owned a pet")

$pop = $populations[$ci]
$char = $characteristics[$ci]

$c = randfrom("90,95,99")
$z = invnormalcdf($c/100 + .5*(1-$c/100))
$n = rrand(100,600,100)
$p = rrand(.05,.95,.01)
$me = round(round($z,2)*sqrt($p*(1-$p)/$n),3)
$subn = $p*$n

$abstolerance[0] = .0011
$abstolerance[1] = .0011
$answer[0] = $p-$me
$answer[1] = $p+$me

$lower_show = round($p - $me, 3)
$upper_show = round($p + $me, 3)
$z_show = round($z, 3)
$one_minus_p = round(1 - $p, 2)

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 6px 0;">Out of `n = $n` $pop sampled, `$subn` $char.</p>
    <p style="margin:0;">Construct a `$c%` confidence interval for the true population proportion of $pop who $char. Give your answers as decimals to three places.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">CI:</span>
    <span style="margin-left:8px;">$answerbox[0]</span> `< p <` <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

///

<b>Step 1: Find the sample proportion.</b><br>
`\hat{p} = $subn / $n = $p`<br><br>

<b>Step 2: Find the critical z-value for `$c%` confidence.</b><br>
`z^* ~~ $z_show`<br><br>

<b>Step 3: Compute the margin of error.</b><br>
`ME = z^* cdot sqrt(\hat{p}(1-\hat{p})/n) = $z_show cdot sqrt($p cdot $one_minus_p / $n) ~~ $me`<br><br>

<b>Step 4: Construct the interval.</b><br>
`\hat{p} +- ME = $p +- $me`<br>
Lower bound: `$lower_show`<br>
Upper bound: `$upper_show`
