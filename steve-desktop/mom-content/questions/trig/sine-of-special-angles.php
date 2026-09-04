// === COMMON CONTROL ===

// --- 12 non-axial unit circle angles (exclude 90, 180, 270 for variety) ---
$degs = array(30, 45, 60, 120, 135, 150, 210, 225, 240, 300, 315, 330)

// Radian = rad_num * pi / rad_den
$rad_nums = array(1, 1, 1, 2, 3, 5, 7, 5, 4, 5, 7, 11)
$rad_dens = array(6, 4, 3, 3, 4, 6, 6, 4, 3, 3, 4, 6)

// Sine values (y-coordinates): exact form
$sine_vals = array("1/2", "sqrt(2)/2", "sqrt(3)/2", "sqrt(3)/2", "sqrt(2)/2", "1/2", "-1/2", "-sqrt(2)/2", "-sqrt(3)/2", "-sqrt(3)/2", "-sqrt(2)/2", "-1/2")

// Quadrant for each angle
$quads = array(1, 1, 1, 2, 2, 2, 3, 3, 3, 4, 4, 4)

$i = rand(0, 11)

$deg = $degs[$i]
$rn = $rad_nums[$i]
$rd = $rad_dens[$i]
$sval = $sine_vals[$i]
$quad = $quads[$i]

// Reference angle (in degrees)
$ref_angles = array(30, 45, 60, 60, 45, 30, 30, 45, 60, 60, 45, 30)
$ref = $ref_angles[$i]

// Build radian display string
if ($rd == 1) {
  $rad_show = "`pi`"
} else if ($rn == 1) {
  $rad_show = "`pi/" . $rd . "`"
} else {
  $rad_show = "`(" . $rn . "pi)/" . $rd . "`"
}

// Answer
$anstypes = array("calculated")
$answer[0] = $sval
$answerformat[0] = "nodecimal"
$ansprompt[0] = "sin(" . $deg . "&deg;) = "

// Shared CSS & JS
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
    .rubric-container details[open] .rubric-content { max-height:2000px; opacity:1; padding:0.75em; }
    .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
    .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
    .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; user-select:text; }
    .row-colored { background:#fff9ea; }
    .col-header { width:25%; border-top-left-radius:8px; }
    .col-check { border-top-right-radius:8px; }
    .col-cat-bot { border-bottom-left-radius:8px; }
    .col-check-bot { border-bottom-right-radius:8px; }
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
</script>';

// Determine sign explanation based on quadrant
if ($quad == 1) {
  $sign_expl = "Quadrant I, where sine is positive"
} else if ($quad == 2) {
  $sign_expl = "Quadrant II, where sine is positive"
} else if ($quad == 3) {
  $sign_expl = "Quadrant III, where sine is negative"
} else {
  $sign_expl = "Quadrant IV, where sine is negative"
}

// Solution guide
$solutionguide = $css_block . '
<div class="rubric-container">
  <details>
    <summary>
      <span class="arrow-closed">&#9656;</span><span class="arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr>
            <th class="col-header">Step</th>
            <th class="col-check">Work</th>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Given</b></td>
            <td>Find sin(' . $deg . '&deg;), which equals sin(' . $rad_show . ').</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Step 1</b></td>
            <td><b>Identify the quadrant and reference angle.</b><br>
            The angle ' . $deg . '&deg; is in ' . $sign_expl . '.<br>
            The reference angle is ' . $ref . '&deg;.</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Step 2</b></td>
            <td><b>Find sin of the reference angle.</b><br>
            sin(' . $ref . '&deg;) = `' . ($quad <= 2 ? $sval : str_replace("-", "", $sval)) . '`
            <br>(From the unit circle, the reference angle ' . $ref . '&deg; has this sine value.)</td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Step 3</b></td>
            <td class="col-check-bot"><b>Apply the correct sign based on the quadrant.</b><br>
            In ' . $sign_expl . ', so sin(' . $deg . '&deg;) = ' . $sval . '.
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Answer:</b> sin(' . $deg . '&deg;) = <b>' . $sval . '</b>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:Arial; font-size:medium; line-height:1.6;">
<p>Find the exact value of sin($deg&deg;). Give your answer in exact form using <code>sqrt()</code> for square roots and <code>/</code> for fractions.</p>
</div>

sin($deg&deg;) = $answerbox[0]


// === ANSWER ===

$solutionguide