// === DEGREE RADIAN SINE VALUES - Convert degrees to radians and find sin(theta) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

// --- 15 standard unit circle angles ---
$degs = array(30, 45, 60, 90, 120, 135, 150, 180, 210, 225, 240, 270, 300, 315, 330)

// Radian = rad_num * pi / rad_den
$rad_nums = array(1, 1, 1, 1, 2, 3, 5, 1, 7, 5, 4, 3, 5, 7, 11)
$rad_dens = array(6, 4, 3, 2, 3, 4, 6, 1, 6, 4, 3, 2, 3, 4, 6)

// y-coordinate display strings (= sin values)
$ydisp = array("1/2", "sqrt(2)/2", "sqrt(3)/2", "1", "sqrt(3)/2", "sqrt(2)/2", "1/2", "0", "-1/2", "-sqrt(2)/2", "-sqrt(3)/2", "-1", "-sqrt(3)/2", "-sqrt(2)/2", "-1/2")

// x-coordinate display strings (= cos values)
$xdisp = array("sqrt(3)/2", "sqrt(2)/2", "1/2", "0", "-1/2", "-sqrt(2)/2", "-sqrt(3)/2", "-1", "-sqrt(3)/2", "-sqrt(2)/2", "-1/2", "0", "1/2", "sqrt(2)/2", "sqrt(3)/2")

// Pick a random angle (exclude 180° and 0° equivalents for cleaner questions)
// Use indices 0-6, 8-14 (skip 7=180° for simplicity, but keep 90 and 270)
$i = rand(0, 14)

$deg = $degs[$i]
$rn = $rad_nums[$i]
$rd = $rad_dens[$i]
$xd = $xdisp[$i]
$yd = $ydisp[$i]

// Build radian answer string
if ($rd == 1) {
  $rad_ans = "pi"
  $rad_show = "pi"
} else if ($rn == 1) {
  $rad_ans = "pi/" . $rd
  $rad_show = "pi/" . $rd
} else {
  $rad_ans = "(" . $rn . "pi)/" . $rd
  $rad_show = "(" . $rn . "pi)/" . $rd
}

// Numeric coordinates for graph
$xn = cos($deg * pi / 180)
$yn = sin($deg * pi / 180)

// Unit circle graph
$circle = showplot("[cos(t),sin(t)],blue,0,6.2832,,,2", -1.5, 1.5, -1.5, 1.5, "1:1", "1:1", 300, 300)
$dot = showplot("dot,$xn,$yn,closed,red", -1.5, 1.5, -1.5, 1.5, "1:1", "1:1", 300, 300)
$radius = showplot("[$xn*t,$yn*t],gray,0,1,,,1,dash", -1.5, 1.5, -1.5, 1.5, "1:1", "1:1", 300, 300)
$graph = mergeplots($circle, $radius, $dot)
$graph = addlabel($graph, $xn, $yn, "P", "red", "right")

// Answer setup: (a) radians, (b) sin value
$anstypes = array("calculated", "calculated")
$answer[0] = $rad_ans
$answer[1] = $yd
$answerformat[0] = "nodecimal"
$answerformat[1] = "nodecimal"
$ansprompt[0] = $deg . " degrees = "
$ansprompt[1] = "sin(" . $deg . "&deg;) = "

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
            <td>An angle of <b>' . $deg . '&deg;</b> in standard position has a terminal point P on the unit circle.</td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Step 1</b></td>
            <td><b>Convert degrees to radians.</b><br>
            Multiply by `pi/180`:<br>
            `' . $deg . ' * pi/180 = ' . $rad_show . '`<br>
            So <b>' . $deg . '&deg; = ' . $rad_show . '</b> radians.</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Step 2</b></td>
            <td><b>Find sin(' . $deg . '&deg;).</b><br>
            The terminal point for ' . $deg . '&deg; on the unit circle is (`' . $xd . '`, `' . $yd . '`).<br>
            Since <b>sin(theta) = y-coordinate</b> of the terminal point:<br>
            sin(' . $deg . '&deg;) = sin(' . $rad_show . ') = <b>`' . $yd . '`</b></td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Result</b></td>
            <td class="col-check-bot">The angle ' . $deg . '&deg; equals ' . $rad_show . ' radians, and sin(' . $deg . '&deg;) = `' . $yd . '`.<br>
            <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
              <b>Degrees:</b> ' . $deg . '&deg;&nbsp;&nbsp; <b>Radians:</b> ' . $rad_show . '&nbsp;&nbsp; <b>sin:</b> `' . $yd . '`
            </div></td>
          </tr>
        </tbody>
      </table>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:Arial; font-size:medium; line-height:1.6;">
<p>The point P on the unit circle below corresponds to an angle of <b>{$deg}&deg;</b> in standard position.</p>
<div style="margin:15px auto; text-align:center;">$graph</div>
<p>Convert this angle from degrees to radians, then find its sine value. Remember: <b>sin(theta) equals the y-coordinate</b> of the terminal point on the unit circle.</p>
</div>

<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part (a)</span>
Convert {$deg}&deg; to radians.
<div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
</div>

<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part (b)</span>
Find sin({$deg}&deg;).
<div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
</div>

///

$solutionguide