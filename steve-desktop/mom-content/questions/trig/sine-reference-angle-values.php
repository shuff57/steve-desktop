// === COMMON CONTROL ===

// --- 12 non-axial unit circle angles ---
$degs = array(30, 45, 60, 120, 135, 150, 210, 225, 240, 300, 315, 330)

// Reference angles for each
$refs = array(30, 45, 60, 60, 45, 30, 30, 45, 60, 60, 45, 30)

// Quadrant for each
$quads = array(1, 1, 1, 2, 2, 2, 3, 3, 3, 4, 4, 4)

// Reference angle sine values (always positive: these are for the acute reference angle)
$ref_sine_vals = array("1/2", "sqrt(2)/2", "sqrt(3)/2", "sqrt(3)/2", "sqrt(2)/2", "1/2", "1/2", "sqrt(2)/2", "sqrt(3)/2", "sqrt(3)/2", "sqrt(2)/2", "1/2")

// Actual sine values (with sign)
$actual_sine_vals = array("1/2", "sqrt(2)/2", "sqrt(3)/2", "sqrt(3)/2", "sqrt(2)/2", "1/2", "-1/2", "-sqrt(2)/2", "-sqrt(3)/2", "-sqrt(3)/2", "-sqrt(2)/2", "-1/2")

// Radian = rad_num * pi / rad_den (for each angle)
$rad_nums = array(1, 1, 1, 2, 3, 5, 7, 5, 4, 5, 7, 11)
$rad_dens = array(6, 4, 3, 3, 4, 6, 6, 4, 3, 3, 4, 6)

// Reference angle radians
$ref_rad_nums = array(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1)  // all are pi/6, pi/4, or pi/3
$ref_rad_dens = array(6, 4, 3, 3, 4, 6, 6, 4, 3, 3, 4, 6)

$i = rand(0, 11)

$deg = $degs[$i]
$ref = $refs[$i]
$quad = $quads[$i]
$ref_sine = $ref_sine_vals[$i]
$actual_sine = $actual_sine_vals[$i]
$rn = $rad_nums[$i]
$rd = $rad_dens[$i]
$rrn = $ref_rad_nums[$i]
$rrd = $ref_rad_dens[$i]

// Build radian display for the given angle
if ($rd == 1) {
  $rad_show = "`pi`"
} else if ($rn == 1) {
  $rad_show = "`pi/" . $rd . "`"
} else {
  $rad_show = "`(" . $rn . "pi)/" . $rd . "`"
}

// Build radian display for reference angle
if ($rrd == 1) {
  $ref_rad_show = "`pi`"
} else if ($rrn == 1) {
  $ref_rad_show = "`pi/" . $rrd . "`"
} else {
  $ref_rad_show = "`(" . $rrn . "pi)/" . $rrd . "`"
}

// Sign rule for quadrant
if ($quad == 1) {
  $sign_word = "positive"
  $sign_sym = ""
} else if ($quad == 2) {
  $sign_word = "positive"
  $sign_sym = ""
} else if ($quad == 3) {
  $sign_word = "negative"
  $sign_sym = "-"
} else {
  $sign_word = "negative"
  $sign_sym = "-"
}

// Answer: sin(theta) in exact form
$anstypes = array("number", "calculated", "calculated")
$answerformat[0] = "integer"
$answerformat[1] = "nodecimal"
$answerformat[2] = "nodecimal"

$answer[0] = $ref
// Reference angle sine (always positive)
$answer[1] = $ref_sine
// Final answer with sign
$answer[2] = $actual_sine

$ansprompt[0] = "Reference angle (degrees): "
$ansprompt[1] = "sin(ref. angle) = "
$ansprompt[2] = "sin(" . $deg . "&deg;) = "

// Unit circle graph
$xn_val = cos($deg * pi / 180)
$yn_val = sin($deg * pi / 180)
$circle = showplot("[cos(t),sin(t)],blue,0,6.2832,,,2", -1.5, 1.5, -1.5, 1.5, "1:1", "1:1", 300, 300)
$dot = showplot("dot,$xn_val,$yn_val,closed,red", -1.5, 1.5, -1.5, 1.5, "1:1", "1:1", 300, 300)
$radius = showplot("[$xn_val*t,$yn_val*t],gray,0,1,,,1,dash", -1.5, 1.5, -1.5, 1.5, "1:1", "1:1", 300, 300)
$vline = showplot("[$xn_val*t,0],red,0,$yn_val,,,1,dash", -1.5, 1.5, -1.5, 1.5, "1:1", "1:1", 300, 300)
$graph = mergeplots($circle, $radius, $vline, $dot)
$graph = addlabel($graph, $xn_val, $yn_val, "P", "red", "right")

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
            <td>Find sin(' . $deg . '&deg;) = sin(' . $rad_show . ').</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Step 1</b></td>
            <td><b>Find the reference angle.</b><br>
            The reference angle is the acute angle formed with the nearest x-axis. For ' . $deg . '&deg; in Quadrant ' . $quad . ', the reference angle is <b>' . $ref . '&deg;</b> = ' . $ref_rad_show . '.</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Step 2</b></td>
            <td><b>Find sin of the reference angle.</b><br>
            sin(' . $ref . '&deg;) = `' . $ref_sine . '` (always positive since reference angles are acute).</td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Step 3</b></td>
            <td class="col-check-bot"><b>Apply the sign rule for Quadrant ' . $quad . '.</b><br>
            In Quadrant ' . $quad . ', sine is ' . $sign_word . ', so:<br>
            sin(' . $deg . '&deg;) = ' . ($sign_sym ? $sign_sym : "") . ' sin(' . $ref . '&deg;) = <b>' . $actual_sine . '</b>
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Answer:</b> sin(' . $deg . '&deg;) = ' . $actual_sine . '
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
<p>Use the reference angle method to find the exact value of sin($deg&deg;).</p>
<div style="margin:15px auto; text-align:center;">$graph</div>
<p>The point P is on the unit circle at an angle of $deg&deg;. Give exact answers.</p>
</div>

(a) Reference angle (in degrees): $answerbox[0]

(b) sin(ref. angle) = $answerbox[1]

(c) sin($deg&deg;) = $answerbox[2]


// === ANSWER ===

$solutionguide