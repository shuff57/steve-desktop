// === NAME - DESCRIPTION: R-squared Interpretation: select correct meaning in context ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$ci = rand(0, 2)
$xnames = array("hours of exercise per week", "hours of sleep per night", "years of education")
$ynames = array("resting heart rate (bpm)", "memory test score", "annual income (thousands of dollars)")
$r2vals = array(
  array(62, 48, 71),
  array(55, 39, 68),
  array(44, 77, 83)
)
$r2picks = array(rand(0,2), rand(0,2), rand(0,2))

$xname = $xnames[$ci]
$yname = $ynames[$ci]
$r2 = $r2vals[$ci][$r2picks[$ci]]
$r2dec = $r2 / 100

$anstypes = array("choices")
$answer[0] = 0
$noshuffle[0] = "all"
$choices[0] = array(
  "$r2% of the variation in $yname is explained by the linear relationship with $xname.",
  "There is a correlation of $r2dec between $xname and $yname.",
  "For each one-unit increase in $xname, $yname changes by $r2%.",
  "$r2% of the data points fall exactly on the regression line."
)

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
      <p><b>Correct answer:</b> <em>' . $r2 . '% of the variation in ' . $yname . ' is explained by the linear relationship with ' . $xname . '.</em></p>
      <p>`r^2` measures how much of the variability in the response variable is accounted for by the regression model. It does <b>not</b> equal `r` itself, does not describe the slope, and does not count points on the line.</p>
      <div style="margin:10px 0;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 8px 8px 0;">
        <b>Template:</b> `r^2`% of the variation in <em>[response]</em> is explained by the linear relationship with <em>[explanatory]</em>.
      </div>
      <p><b>Why the others are wrong:</b><br>
      &bull; Correlation of ' . $r2dec . ': that would be `r`, not `r^2`.<br>
      &bull; "Changes by ' . $r2 . '%&quot;: that is the slope interpretation.<br>
      &bull; "' . $r2 . '% fall exactly on the line": `r^2` is about explained variation, not point location.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif;font-size:16px;line-height:1.6;color:#21242c;max-width:688px;">
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:20px;margin:10px 0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A regression of <b>$yname</b> on <b>$xname</b> gives `r^2 = $r2dec` (i.e., $r2%). Select the correct interpretation of `r^2`.</p>
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:20px;margin:10px 0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    $answerbox[0]
  </div>
</div>

// === ANSWER ===

$solutionguide
