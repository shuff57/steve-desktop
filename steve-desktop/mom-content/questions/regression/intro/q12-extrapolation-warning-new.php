// === NAME - DESCRIPTION: Extrapolation Warning: new context, predict outside range ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$ci = rand(0, 3)
$xnames  = array("weeks of physical therapy", "hours of tutoring per week", "kg of fertilizer applied", "weeks of training")
$ynames  = array("pain score (0-10)", "quiz score (out of 100)", "crop yield (bushels per acre)", "100-meter dash time (seconds)")
$b0s     = array(9.2,  45,   20,   16.0)
$b1s     = array(-0.6,  4.5,  8.5,  -0.15)
$xmins   = array( 1,    1,    1,    1)
$xmaxs   = array(12,   10,   15,   24)
$xpreds  = array(20,   18,   25,   40)
$b1disps = array("-0.6", "+4.5", "+8.5", "-0.15")

$xname  = $xnames[$ci]
$yname  = $ynames[$ci]
$b0     = $b0s[$ci]
$b1     = $b1s[$ci]
$xmin   = $xmins[$ci]
$xmax   = $xmaxs[$ci]
$xpred  = $xpreds[$ci]
$b1disp = $b1disps[$ci]
$ypred  = round($b0 + $b1 * $xpred, 2)

$anstypes = array("number", "choices", "choices")
$answer[0] = $ypred
$reltolerance[0] = 0.01

$answer[1] = 1
$choices[1] = array("Yes: the regression model will give an accurate prediction.", "No: this prediction is not reliable.")
$noshuffle[1] = "all"

$answer[2] = 0
$choices[2] = array(
  "The x value ($xpred) is outside the range of the data ($xmin to $xmax), so we are extrapolating.",
  "The sample size was too small to make any predictions.",
  "The regression equation has a negative slope, so it cannot be used for large values.",
  "The r-squared value is too low to trust the prediction."
)
$noshuffle[2] = "all"

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
      <p><b>Part a:</b> Substituting `x = ' . $xpred . '`:</p>
      <p>`hat{y} = ' . $b0 . ' ' . $b1disp . '(' . $xpred . ') = ' . $ypred . '`</p>
      <p><b>Part b:</b> <b>No</b>: this prediction is not reliable.</p>
      <p><b>Part c:</b> The data used to fit the model ranged from ' . $xmin . ' to ' . $xmax . ' ' . $xname . '. Predicting at ' . $xpred . ' is <b>extrapolation</b>: applying the model well beyond the range of observed data. The relationship may not remain linear outside that range.</p>
      <div style="margin:10px 0;padding:0.6em 1em;background:#fff3cd;border-left:4px solid #f59e0b;border-radius:0 8px 8px 0;">
        <b>Key idea:</b> A regression model is only trustworthy within (or close to) the range of x values used to build it. Extrapolation can produce nonsensical predictions.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif;font-size:16px;line-height:1.6;color:#21242c;max-width:688px;">
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:20px;margin:10px 0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">Researchers collected data on <b>$xname</b> (ranging from $xmin to $xmax) and <b>$yname</b>. The least-squares regression equation is:</p>
    <p style="margin:0 0 10px 0;text-align:center;">`hat{y} = $b0 $b1disp x`</p>
    <p style="margin:0;">A researcher uses this equation to predict $yname when $xname `= $xpred`.</p>
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:20px;margin:10px 0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block;background:#e8f0fe;color:#1865f2;border-radius:6px;padding:3px 10px;font-size:13px;font-weight:700;margin-right:10px;vertical-align:middle;">a.</span> What does the equation predict for $xname `= $xpred`? $answerbox[0]
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:20px;margin:10px 0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block;background:#e8f0fe;color:#1865f2;border-radius:6px;padding:3px 10px;font-size:13px;font-weight:700;margin-right:10px;vertical-align:middle;">b.</span> Is this prediction reliable? $answerbox[1]
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:20px;margin:10px 0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block;background:#e8f0fe;color:#1865f2;border-radius:6px;padding:3px 10px;font-size:13px;font-weight:700;margin-right:10px;vertical-align:middle;">c.</span> Why or why not? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
