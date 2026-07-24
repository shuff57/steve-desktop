// === Non-Unit Circle Coordinates - Find terminal point coordinates on a circle of radius r ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// --- 12 standard angles (exclude quadrantals to force triangle reasoning) ---
$degs = array(30, 45, 60, 120, 135, 150, 210, 225, 240, 300, 315, 330)

$rad_nums = array(1, 1, 1, 2, 3, 5, 7, 5, 4, 5, 7, 11)
$rad_dens = array(6, 4, 3, 3, 4, 6, 6, 4, 3, 3, 4, 6)

// Unit circle x-coordinates (answer expressions)
$xans = array("sqrt(3)/2", "sqrt(2)/2", "1/2", "-1/2", "-sqrt(2)/2", "-sqrt(3)/2", "-sqrt(3)/2", "-sqrt(2)/2", "-1/2", "1/2", "sqrt(2)/2", "sqrt(3)/2")

// Unit circle y-coordinates (answer expressions)
$yans = array("1/2", "sqrt(2)/2", "sqrt(3)/2", "sqrt(3)/2", "sqrt(2)/2", "1/2", "-1/2", "-sqrt(2)/2", "-sqrt(3)/2", "-sqrt(3)/2", "-sqrt(2)/2", "-1/2")

$i = rand(0, 11)
$r = rand(2, 6)

$deg = $degs[$i]
$rn = $rad_nums[$i]
$rd = $rad_dens[$i]
$xa = $xans[$i]
$ya = $yans[$i]

// Build radian display string
if ($rd == 1) {
  $rad_disp = "`pi`"
} else if ($rn == 1) {
  $rad_disp = "`pi/$rd`"
} else {
  $rad_disp = "`($rn pi)/$rd`"
}

// Numeric coordinates for plotting
$xn = $r * cos($deg * pi / 180)
$yn = $r * sin($deg * pi / 180)
$xn_unit = cos($deg * pi / 180)
$yn_unit = sin($deg * pi / 180)

// Graph: both circles with terminal point on r-circle
$lim = $r + 1
$circle_r = showplot("[$r*cos(t),$r*sin(t)],blue,0,6.2832,,,2", -$lim, $lim, -$lim, $lim, "1:1", "1:1", 300, 300)
$circle_unit = showplot("[cos(t),sin(t)],gray,0,6.2832,,,1,dash", -$lim, $lim, -$lim, $lim, "1:1", "1:1", 300, 300)
$radius_line = showplot("[$xn*t,$yn*t],orange,0,1,,,1,dash", -$lim, $lim, -$lim, $lim, "1:1", "1:1", 300, 300)
$dot = showplot("dot,$xn,$yn,closed,red", -$lim, $lim, -$lim, $lim, "1:1", "1:1", 300, 300)
$graph = mergeplots($circle_unit, $circle_r, $radius_line, $dot)
$graph = addlabel($graph, $xn, $yn, "P", "red", "right")
$graph = addlabel($graph, 0.3, -0.5, "r=1", "gray", "right")
$graph = addlabel($graph, $r*0.6, -$r*0.4, "r=$r", "blue", "right")

// Answer: (r * unit_x, r * unit_y) as calcntuple
$anstypes = array("calcntuple")
$answer = "($r*($xa), $r*($ya))"
$answerformat = "nodecimal"
$displayformat[0] = "point"

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
            <td>A circle of radius `r = ' . $r . '` centered at the origin. We need the terminal point at angle `theta = ' . $rn . 'pi/' . $rd . '` (which is ' . $deg . '&deg;).</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Step 1</b></td>
            <td><b>Find the unit circle coordinates at this angle.</b><br>
            On the unit circle (`r = 1`), the terminal point at ' . $deg . '&deg; is `(' . $xa . ', ' . $ya . ')`. You can find these using the special right triangle for this angle.</td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Step 2</b></td>
            <td class="col-check-bot"><b>Scale the coordinates by the radius.</b><br>
            On a circle of radius `r`, every point is `r` times as far from the origin. Multiply each unit circle coordinate by ' . $r . ':<br>
            `x = ' . $r . ' * (' . $xa . ') = ' . $r . '(' . $xa . ')`<br>
            `y = ' . $r . ' * (' . $ya . ') = ' . $r . '(' . $ya . ')`
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Terminal point:</b> `(' . $r . '(' . $xa . '), ' . $r . '(' . $ya . '))`
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
  <p>Consider a circle of radius `r = $r` centered at the origin (shown in blue below). The dashed gray circle is the unit circle for reference.</p>
  <div style="margin:15px auto; text-align:center;">$graph</div>
  <p>The angle `theta =` $rad_disp radians ($deg&deg;) is drawn in standard position. Using your knowledge of special right triangles and the unit circle, determine the <b>exact coordinates</b> of the terminal point P on the circle of radius $r.</p>
  <p><i>Hint: How do the coordinates change when the radius is not 1?</i></p>
</div>

Terminal point on the circle of radius $r: $answerbox[0]


// === ANSWER ===

$solutionguide
