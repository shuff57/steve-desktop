// === NAME - DESCRIPTION: Construct a confidence interval for a population mean (sigma unknown, t-distribution) with randomized story contexts ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats")

$anstypes = array("number","number")
$hidetips = true
$answerboxsize[0] = 5
$answerboxsize[1] = 5

// Randomized story contexts
$ci = rand(0,4)
$names = array("Karen","David","Maria","James","Priya")
$items = array("Big Chip cookies","artisan bagels","energy bars","protein shakes","granola packs")
$measures = array("chocolate chips per cookie","sesame seeds per bagel","grams of fiber per bar","grams of protein per shake","calories per pack")
$businesses = array("bakery","cafe","health food company","gym smoothie bar","snack company")

$name = $names[$ci]
$item = $items[$ci]
$measure = $measures[$ci]
$biz = $businesses[$ci]

$c = randfrom("80,90,95,98,99")
$n = rand(41,79)
$xbar = rand(5,19) + rand(1,9)/10
$s = rand(1,3) + rand(1,9)/10
$t = invtcdf($c/100 + .5*(1-$c/100),$n-1)
$me = round($t,3)*$s/sqrt($n)
$mer = round($me,1)

$abstolerance[0] = .051
$abstolerance[1] = .051
$answer[0] = $xbar - $me
$answer[1] = $xbar + $me

$lower_show = round($xbar - $me, 1)
$upper_show = round($xbar + $me, 1)
$t_show = round($t, 3)
$me_show = round($me, 2)
$df = $n - 1
$cum_area = round($c/100 + .5*(1-$c/100), 4)

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 6px 0;">$name wants to advertise the number of $measure at their $biz. They randomly select a sample of `n = $n` $item and find that the $measure in the sample has a mean of `bar(x) = $xbar` and a standard deviation of `s = $s`.</p>
    <p style="margin:0;">What is the `$c%` confidence interval for the mean number of $measure for $item? Enter your answers accurate to one decimal place.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">CI:</span>
    <span style="margin-left:8px;">$answerbox[0]</span> `< mu <` <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

///

<b>Step 1: Identify the procedure.</b><br>
We want a confidence interval for a population mean `mu`. The population standard deviation `sigma` is unknown, so we use a <b>t-interval</b> with `df = n - 1 = $df`.<br><br>

<b>Step 2: Find the critical t-value.</b><br>
For a `$c%` confidence interval with `df = $df`, the critical value is `t^* ~~ $t_show`.<br>
Use the invT function on your calculator with cumulative area `$cum_area`, or look up the t-table row for `df = $df`.<br><br>

<b>Step 3: Compute the margin of error.</b><br>
`E = t^* xx s/sqrt(n)`<br>
`E = $t_show xx $s/sqrt($n) ~~ $me_show`<br><br>

<b>Step 4: Construct the interval.</b><br>
`bar(x) +- E = $xbar +- $me_show`<br>
Lower bound: `$lower_show`<br>
Upper bound: `$upper_show`<br><br>

<b>Conclusion:</b> We are `$c%` confident the true mean number of $measure for $item is between <b>$lower_show</b> and <b>$upper_show</b>.
