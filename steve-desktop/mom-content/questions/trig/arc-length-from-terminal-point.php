// === COMMON CONTROL ===

// --- 15 standard unit circle angles ---
$degs = array(30, 45, 60, 90, 120, 135, 150, 180, 210, 225, 240, 270, 300, 315, 330)

// Radian = rad_num * pi / rad_den
$rad_nums = array(1, 1, 1, 1, 2, 3, 5, 1, 7, 5, 4, 3, 5, 7, 11)
$rad_dens = array(6, 4, 3, 2, 3, 4, 6, 1, 6, 4, 3, 2, 3, 4, 6)

// x-coordinate display (unit circle values)
$xunit = array("sqrt(3)/2", "sqrt(2)/2", "1/2", "0", "-1/2", "-sqrt(2)/2", "-sqrt(3)/2", "-1", "-sqrt(3)/2", "-sqrt(2)/2", "-1/2", "0", "1/2", "sqrt(2)/2", "sqrt(3)/2")
$yunit = array("1/2", "sqrt(2)/2", "sqrt(3)/2", "1", "sqrt(3)/2", "sqrt(2)/2", "1/2", "0", "-1/2", "-sqrt(2)/2", "-sqrt(3)/2", "-1", "-sqrt(3)/2", "-sqrt(2)/2", "-1/2")

$i = rand(0, 14)
$r = rand(2, 8)

$deg = $degs[$i]
$rn = $rad_nums[$i]
$rd = $rad_dens[$i]

// Arc length = r * theta = r * (rn * pi / rd) = (r * rn) * pi / rd
$arc_num = $r * $rn
$arc_den = $rd
$g = gcd($arc_num, $arc_den)
$arc_num_red = $arc_num / $g
$arc_den_red = $arc_den / $g

// Build answer string
if ($arc_den_red == 1) {
  if ($arc_num_red == 1) {
    $arc_ans = "pi"
  } else {
    $arc_ans = $arc_num_red . "pi"
  }
} else {
  if ($arc_num_red == 1) {
    $arc_ans = "pi/$arc_den_red"
  } else {
    $arc_ans = "(" . $arc_num_red . "pi)/" . $arc_den_red
  }
}

// Numeric coordinates for plotting (on circle of radius r)
$xn = $r * cos($deg * pi / 180)
$yn = $r * sin($deg * pi / 180)

// Display coordinates: r * unit circle value
$xu = $xunit[$i]
$yu = $yunit[$i]

// Build display of coordinates on the circle
if ($xu == "0") {
  $pxd = "0"
} else if ($xu == "1" || $xu == "-1") {
  if ($xu == "1") { $pxd = "$r"; } else { $pxd = "-$r"; }
} else {
  $pxd = "$r * $xu"
}

if ($yu == "0") {
  $pyd = "0"
} else if ($yu == "1" || $yu == "-1") {
  if ($yu == "1") { $pyd = "$r"; } else { $pyd = "-$r"; }
} else {
  $pyd = "$r * $yu"
}

// Graph window
$win = $r + 1
$nwin = -1 * $win

// Angle in radians for arc highlight
$theta = $deg * pi / 180

// Circle graph
$circle = showplot("[$r*cos(t),$r*sin(t)],blue,0,6.2832,,,2", $nwin, $win, $nwin, $win, "1:1", "1:1", 300, 300)
$arc = showplot("[$r*cos(t),$r*sin(t)],red,0,$theta,,,3", $nwin, $win, $nwin, $win, "1:1", "1:1", 300, 300)
$dot = showplot("dot,$xn,$yn,closed,red", $nwin, $win, $nwin, $win, "1:1", "1:1", 300, 300)
$startdot = showplot("dot,$r,0,closed,green", $nwin, $win, $nwin, $win, "1:1", "1:1", 300, 300)
$graph = mergeplots($circle, $arc, $dot, $startdot)
$graph = addlabel($graph, $xn, $yn, "P", "red", "right")
$graph = addlabel($graph, $r, 0, "A", "green", "right")

// Answer setup
$anstypes = array("calculated")
$answer = $arc_ans
$answerformat = "nodecimal"

// Display the radian fraction for solution
$rad_disp = dispreducedfraction($rn, $rd)
$arc_disp = dispreducedfraction($arc_num_red, $arc_den_red)

// Shared CSS & JS (copy verbatim from free-response-template.php)
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
      <span class="arrow-closed">▸</span><span class="arrow-open">▾</span>
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
            <td>Circle with radius `r = ' . $r . '`. Point P is on the circle. We need to find the arc length from A to P traveling counterclockwise.</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Step 1</b></td>
            <td><b>Find the central angle `theta`.</b><br>
            Divide the coordinates of P by the radius `r` to convert to unit circle coordinates, then identify the standard angle:<br>
            `theta = ' . $deg . '°` = `(' . $rn . 'pi)/' . $rd . '` radians.</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Step 2</b></td>
            <td><b>Apply the arc length formula.</b><br>
            The arc length formula is `s = r * theta`, where `theta` must be in radians:<br>
            `s = ' . $r . ' * (' . $rn . 'pi)/' . $rd . ' = (' . $arc_num . 'pi)/' . $arc_den . '`</td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Step 3</b></td>
            <td class="col-check-bot"><b>Simplify the fraction.</b><br>
            Reduce `(' . $arc_num . 'pi)/' . $arc_den . '` to lowest terms.
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Arc length:</b> `s = ' . $arc_ans . '`
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
<p>A circle has radius `r = $r`. Point A = `($r, 0)` and point P are on the circle as shown below. The red arc shows the distance from A to P traveling <b>counterclockwise</b>.</p>
<div style="margin:15px auto; text-align:center;">$graph</div>
<p>The coordinates of P are `($pxd, $pyd)`.</p>
<p>Find the arc length from A to P (counterclockwise). Give an exact answer.</p>
</div>

Arc length `s` = $answerbox[0]


// === ANSWER ===

$solutionguide
