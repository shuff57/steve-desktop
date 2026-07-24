// === NAME - DESCRIPTION: Two Predictions - House size and listing price ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$x1 = rand(8, 14)
$x2 = $x1 + rand(4, 10)

$y1 = 45 + 12.5 * $x1
$y2 = 45 + 12.5 * $x2
$diff = $y2 - $y1
$sqft1 = $x1 * 100
$sqft2 = $x2 * 100

$anstypes = array("number", "number")
$answer[0] = $y1
$answer[1] = $y2
$reltolerance = 0.01

$prod1 = 12.5 * $x1
$prod2 = 12.5 * $x2

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
      <p><b>Part a:</b> Substitute `x = ' . $x1 . '`:</p>
      <p>`hat{y} = 45 + 12.5(' . $x1 . ') = 45 + ' . $prod1 . ' = ' . $y1 . '`</p>
      <p><b>Part b:</b> Substitute `x = ' . $x2 . '`:</p>
      <p>`hat{y} = 45 + 12.5(' . $x2 . ') = 45 + ' . $prod2 . ' = ' . $y2 . '`</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Predictions:</b><br>
        ' . $sqft1 . ' sq ft house: $' . $y1 . ',000<br>
        ' . $sqft2 . ' sq ft house: $' . $y2 . ',000
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A real estate agent models the relationship between house size `x` (in hundreds of square feet) and listing price `y` (in thousands of dollars). The regression equation is:</p>
    <p style="margin:0 0 10px 0; text-align:center;">`hat{y} = 45 + 12.5x`</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Predict the listing price for a house that is <b>$sqft1 square feet</b> (`x = $x1`). $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Predict the listing price for a house that is <b>$sqft2 square feet</b> (`x = $x2`). $answerbox[1]
  </div>
</div>


// === ANSWER ===

$solutionguide
