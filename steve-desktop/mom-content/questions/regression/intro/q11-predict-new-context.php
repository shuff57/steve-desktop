// === NAME - DESCRIPTION: Predict from Equation: randomized context, above/below line ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$ci = rand(0, 3)
$xnames = array("hours of sleep per night", "hours studied per week", "miles run per week", "months of practice")
$ynames = array("memory test score (out of 100)", "GPA (out of 4.0)", "resting heart rate (bpm)", "typing speed (wpm)")
$b0s    = array(40,   1.5,  90,   25)
$b1s    = array( 6.5,  0.4,  -1.2,  3.5)
$xmins  = array( 4,    2,     5,    1)
$xmaxs  = array( 9,   15,    25,   18)
$yunits = array("points", "GPA points", "bpm", "wpm")

$xname = $xnames[$ci]
$yname = $ynames[$ci]
$b0    = $b0s[$ci]
$b1    = $b1s[$ci]
$yunit = $yunits[$ci]

$xval = rrand($xmins[$ci], $xmaxs[$ci], 1)
$yhat = round($b0 + $b1 * $xval, 2)

$above = rand(0, 1)
$offset = rrand(0.3, 1.5, 0.1)
if ($above == 1) {
  $yobs  = round($yhat + $offset, 1)
  $answer[1] = 0
  $postext = "above"
} else {
  $yobs  = round($yhat - $offset, 1)
  $answer[1] = 1
  $postext = "below"
}

$anstypes = array("number", "choices")
$answer[0] = $yhat
$reltolerance[0] = 0.01
$choices[1] = array("Above the regression line", "Below the regression line")
$noshuffle[1] = "all"

$product = round($b1 * $xval, 2)
$b1disp  = ($b1 > 0) ? "+" . $b1 : $b1

$solutionguide = '
<style>
  .sol-wrap details{width:100%;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;background:#fff;}
  .sol-wrap summary{cursor:pointer;display:block;width:100%;background:#f0f4ff;color:#21242c;padding:0.5em 0.75em;font-weight:700;font-size:15px;border-bottom:1px solid #e5e7eb;list-style:none;}
  .sol-wrap summary::-webkit-details-marker{display:none;}
  .sol-arrow-open{display:none;}
  .sol-wrap details[open] .sol-arrow-closed{display:none;}
  .sol-wrap details[open] .sol-arrow-open{display:inline;}
  .sol-body{padding:0.75em;background:#fafafa;}
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif;font-size:16px;line-height:1.6;color:#21242c;max-width:688px;margin:1em 0;">
  <details>
    <summary><span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span> Step-by-Step Solution</summary>
    <div class="sol-body">
      <p><b>Part a:</b> Substitute `x = ' . $xval . '` into the equation:</p>
      <p>`hat{y} = ' . $b0 . ' ' . $b1disp . '(' . $xval . ') = ' . $b0 . ' + (' . $product . ') = ' . $yhat . '`</p>
      <p><b>Part b:</b> The observed value is ' . $yobs . ' and the predicted value is ' . $yhat . '. Since ' . $yobs . ' is ' . $postext . ' ' . $yhat . ', the point is <b>' . $postext . '</b> the regression line.</p>
      <div style="margin:10px 0;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 8px 8px 0;">
        Predicted: ' . $yhat . ' ' . $yunit . '<br>
        Observed: ' . $yobs . ' ' . $yunit . '<br>
        Point is <b>' . $postext . '</b> the line.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif;font-size:16px;line-height:1.6;color:#21242c;max-width:688px;">
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:20px;margin:10px 0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A study models <b>$yname</b> from <b>$xname</b>. The regression equation is:</p>
    <p style="margin:0 0 10px 0;text-align:center;">`hat{y} = $b0 $b1disp x`</p>
    <p style="margin:0;">One subject had <b>$xval $xname</b> and an observed $yname of <b>$yobs $yunit</b>.</p>
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:20px;margin:10px 0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block;background:#e8f0fe;color:#1865f2;border-radius:6px;padding:3px 10px;font-size:13px;font-weight:700;margin-right:10px;vertical-align:middle;">a.</span> What is the <b>predicted</b> value of $yname when $xname `= $xval`? $answerbox[0]
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:20px;margin:10px 0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block;background:#e8f0fe;color:#1865f2;border-radius:6px;padding:3px 10px;font-size:13px;font-weight:700;margin-right:10px;vertical-align:middle;">b.</span> Is this subject's observed value <b>above</b> or <b>below</b> the regression line? $answerbox[1]
  </div>
</div>

// === ANSWER ===

$solutionguide
