// === COMMON CONTROL ===

// --- 15 standard unit circle angles ---
$degs = array(30, 45, 60, 90, 120, 135, 150, 180, 210, 225, 240, 270, 300, 315, 330)

// Radian = rad_num * pi / rad_den
$rad_nums = array(1, 1, 1, 1, 2, 3, 5, 1, 7, 5, 4, 3, 5, 7, 11)
$rad_dens = array(6, 4, 3, 2, 3, 4, 6, 1, 6, 4, 3, 2, 3, 4, 6)

// x-coordinate display strings
$xdisp = array("sqrt(3)/2", "sqrt(2)/2", "1/2", "0", "-1/2", "-sqrt(2)/2", "-sqrt(3)/2", "-1", "-sqrt(3)/2", "-sqrt(2)/2", "-1/2", "0", "1/2", "sqrt(2)/2", "sqrt(3)/2")

// y-coordinate display strings (these ARE the sine values)
$ydisp = array("1/2", "sqrt(2)/2", "sqrt(3)/2", "1", "sqrt(3)/2", "sqrt(2)/2", "1/2", "0", "-1/2", "-sqrt(2)/2", "-sqrt(3)/2", "-1", "-sqrt(3)/2", "-sqrt(2)/2", "-1/2")

$i = rand(0, 14)

$deg = $degs[$i]
$rn = $rad_nums[$i]
$rd = $rad_dens[$i]
$xd = $xdisp[$i]
$yd = $ydisp[$i]

// Numeric coordinates for plotting
$xn = cos($deg * pi / 180)
$yn = sin($deg * pi / 180)

// Build radian display string
if ($rd == 1) {
  $rad_show = "`pi`"
} else if ($rn == 1) {
  $rad_show = "`pi/" . $rd . "`"
} else {
  $rad_show = "`(" . $rn . "pi)/" . $rd . "`"
}

// Build sine answer string (calculated type accepts radical expressions)
// sin(θ) = ydisp which is already in exact radical form
$sin_ans = $yd

// Unit circle graph
$circle = showplot("[cos(t),sin(t)],blue,0,6.2832,,,2", -1.5, 1.5, -1.5, 1.5, "1:1", "1:1", 300, 300)
$dot = showplot("dot,$xn,$yn,closed,red", -1.5, 1.5, -1.5, 1.5, "1:1", "1:1", 300, 300)
$radius = showplot("[$xn*t,$yn*t],gray,0,1,,,1,dash", -1.5, 1.5, -1.5, 1.5, "1:1", "1:1", 300, 300)
// Vertical line from x-axis to point (shows y = sin)
$vline = showplot("[$xn*t,0],red,0,$yn,,,1,dash", -1.5, 1.5, -1.5, 1.5, "1:1", "1:1", 300, 300)
$graph = mergeplots($circle, $radius, $vline, $dot)
$graph = addlabel($graph, $xn, $yn, "P", "red", "right")
$graph = addlabel($graph, $xn, 0, "", "red", "below")

// Multipart answer setup
$anstypes = array("calculated")
$answer[0] = $sin_ans
$answerformat[0] = "nodecimal"
$ansprompt[0] = "sin(&theta;) = "

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

// Sign of sine for solution
if ($yn > 0) {
  $sin_sign = "positive"
} else if ($yn < 0) {
  $sin_sign = "negative"
} else {
  $sin_sign = "zero"
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
            <td>A point P = `(' . $xd . ', ' . $yd . ')` is on the unit circle at angle `theta = ' . $deg . '&deg;` = ' . $rad_show . '.</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Step 1</b></td>
            <td><b>Recall the definition of sine on the unit circle.</b><br>
            For any point on the unit circle, the <b>y-coordinate</b> equals sin(`theta`). That is, if P = `(x, y)`, then sin(`theta`) = y.</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Step 2</b></td>
            <td><b>Read off the y-coordinate.</b><br>
            The y-coordinate of P is `' . $yd . '`, and since the y-value is ' . $sin_sign . ', this is consistent with the angle being in ' . ($deg >= 180 && $deg < 360 ? "Quadrants III or IV (where sine is negative)" : ($deg > 90 && $deg < 180 ? "Quadrant II (where sine is positive)" : "Quadrant I (where sine is positive)")) . '.</td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Answer</b></td>
            <td class="col-check-bot">
              <div style="margin-top:4px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                sin(' . $deg . '&deg;) = <b>' . $yd . '</b>
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
<p>On the unit circle, the point P = `($xd, $yd)` corresponds to the angle `theta = $deg&deg;` (shown below).</p>
<div style="margin:15px auto; text-align:center;">$graph</div>
<p>The dashed red segment shows the <b>y-coordinate</b> of P.</p>
<p>Find sin(`theta`). Express your answer in exact form (use <code>sqrt()</code> for square roots if needed).</p>
</div>

sin(`theta`) = $answerbox[0]


// === ANSWER ===

$solutionguide