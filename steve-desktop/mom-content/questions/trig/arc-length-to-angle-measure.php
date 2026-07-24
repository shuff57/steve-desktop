// === Arc Length to Angle Measure - Find radian and degree measure from arc length on a non-unit circle ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Randomize radius from 2 to 5
$r = rand(2, 5)

// Arc length is fixed at 3pi/2
// theta = s/r = (3pi/2) / r = 3pi / (2r)
// degree = theta * 180/pi = 270/r

// Compute the radian answer as reduced fraction times pi
$num = 3
$den = 2 * $r

// Reduce 3/(2r) fraction
if ($r == 3) {
  $rn = 1
  $rd = 2
} else {
  $rn = 3
  $rd = $den
}

// Build radian display for solution guide
if ($rn == 1) {
  $rad_show = "`pi/$rd`"
} else {
  $rad_show = "`($rn pi)/$rd`"
}

// Degree answer
$deg_ans = 270 / $r

// Radian answer expression for calculated type
$rad_ans = "(" . $rn . "*pi)/" . $rd

// Numeric angle for plotting the arc and terminal ray
$theta_num = 3 * pi / (2 * $r)
$xn = $r * cos($theta_num)
$yn = $r * sin($theta_num)

// Graph: circle of radius r with arc and angle shown
$lim = $r + 1
$circle = showplot("[$r*cos(t),$r*sin(t)],blue,0,6.2832,,,2", -$lim, $lim, -$lim, $lim, "1:1", "1:1", 300, 300)
$arc = showplot("[$r*cos(t),$r*sin(t)],red,0,$theta_num,,,3", -$lim, $lim, -$lim, $lim, "1:1", "1:1", 300, 300)
$ray = showplot("[$xn*t,$yn*t],gray,0,1,,,1,dash", -$lim, $lim, -$lim, $lim, "1:1", "1:1", 300, 300)
$initial = showplot("[$r*t,0],gray,0,1,,,1,dash", -$lim, $lim, -$lim, $lim, "1:1", "1:1", 300, 300)
$dot_start = showplot("dot,$r,0,closed,blue", -$lim, $lim, -$lim, $lim, "1:1", "1:1", 300, 300)
$dot_end = showplot("dot,$xn,$yn,closed,red", -$lim, $lim, -$lim, $lim, "1:1", "1:1", 300, 300)
$graph = mergeplots($circle, $arc, $initial, $ray, $dot_start, $dot_end)
$graph = addlabel($graph, $r + 0.3, 0, "($r, 0)", "blue", "right")

// Arc length display
$arc_disp = "`(3pi)/2`"

// Multipart answer setup
$anstypes = array("calculated", "number")
$answer[0] = $rad_ans
$answer[1] = $deg_ans
$answerformat[0] = "nodecimal"
$ansprompt[0] = "Radian measure: "
$ansprompt[1] = "Degree measure: "

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
</script>'

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
            <td>A circle of radius `r = ' . $r . '` centered at the origin. An arc of length `s = (3pi)/2` is drawn starting from `(' . $r . ', 0)`.</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Step 1</b></td>
            <td><b>Use the arc length formula to find the radian measure.</b><br>
            The arc length formula is `s = r * theta`, so `theta = s/r`.<br>
            `theta = ((3pi)/2) / ' . $r . ' = (3pi)/(2 * ' . $r . ') = ' . $rn . 'pi/' . $rd . '`
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Radian measure:</b> ' . $rad_show . '
              </div>
            </td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Step 2</b></td>
            <td class="col-check-bot"><b>Convert radians to degrees.</b><br>
            Multiply by `180/pi` to convert:<br>
            `theta = (' . $rn . 'pi)/' . $rd . ' * 180/pi = (' . $rn . ' * 180)/' . $rd . ' = ' . $deg_ans . '`&deg;
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Degree measure:</b> ' . $deg_ans . '&deg;
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
  <p>A circle of radius `r = $r` is centered at the origin. An angle is drawn in standard position so that the arc intercepted by the angle (shown in red) has length `s =` $arc_disp units.</p>
  <div style="margin:15px auto; text-align:center;">$graph</div>
  <p>Find the radian measure and degree measure of this angle.</p>
  <p><i>Hint: Use the arc length formula `s = r * theta`.</i></p>
</div>

(a) $answerbox[0] radians

(b) $answerbox[1] degrees


// === ANSWER ===

$solutionguide
