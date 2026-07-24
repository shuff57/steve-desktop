// === COMMON CONTROL ===

// --- 12 non-axial standard unit circle angles ---
$degs = array(30, 45, 60, 120, 135, 150, 210, 225, 240, 300, 315, 330)

// x-coordinate display strings
$xdisp = array("sqrt(3)/2", "sqrt(2)/2", "1/2", "-1/2", "-sqrt(2)/2", "-sqrt(3)/2", "-sqrt(3)/2", "-sqrt(2)/2", "-1/2", "1/2", "sqrt(2)/2", "sqrt(3)/2")

// y-coordinate display strings
$ydisp = array("1/2", "sqrt(2)/2", "sqrt(3)/2", "sqrt(3)/2", "sqrt(2)/2", "1/2", "-1/2", "-sqrt(2)/2", "-sqrt(3)/2", "-sqrt(3)/2", "-sqrt(2)/2", "-1/2")

// Quadrant for each angle
$quads = array(1, 1, 1, 2, 2, 2, 3, 3, 3, 4, 4, 4)

// Reference angle in degrees
$ref_degs = array(30, 45, 60, 60, 45, 30, 30, 45, 60, 60, 45, 30)

// Reference angle radian denominator (numerator is always 1: pi/6, pi/4, pi/3)
$ref_dens = array(6, 4, 3, 3, 4, 6, 6, 4, 3, 3, 4, 6)

// Rule used to find reference angle
$rules = array("theta", "theta", "theta", "180 - theta", "180 - theta", "180 - theta", "theta - 180", "theta - 180", "theta - 180", "360 - theta", "360 - theta", "360 - theta")

$i = rand(0, 11)

$deg = $degs[$i]
$xd = $xdisp[$i]
$yd = $ydisp[$i]
$quad = $quads[$i]
$refd = $ref_degs[$i]
$refden = $ref_dens[$i]
$rule = $rules[$i]

// Numeric coordinates for plotting
$xn = cos($deg * pi / 180)
$yn = sin($deg * pi / 180)

// Reference angle in radians (always pi/refden)
$ref_rad_ans = "pi/$refden"

// Unit circle graph
$circle = showplot("[cos(t),sin(t)],blue,0,6.2832,,,2", -1.5, 1.5, -1.5, 1.5, "1:1", "1:1", 300, 300)
$dot = showplot("dot,$xn,$yn,closed,red", -1.5, 1.5, -1.5, 1.5, "1:1", "1:1", 300, 300)
$radius = showplot("[$xn*t,$yn*t],gray,0,1,,,1,dash", -1.5, 1.5, -1.5, 1.5, "1:1", "1:1", 300, 300)
$graph = mergeplots($circle, $radius, $dot)
$graph = addlabel($graph, $xn, $yn, "P", "red", "right")

// Multipart answer setup
$anstypes = array("number", "calculated")
$answer[0] = $refd
$answer[1] = $ref_rad_ans
$answerformat[0] = "integer"
$answerformat[1] = "nodecimal"
$ansprompt[0] = "Reference angle in degrees: "
$ansprompt[1] = "Reference angle in radians: "

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
            <td>The point P = `(' . $xd . ', ' . $yd . ')` is on the unit circle. We need to find the reference angle in both degrees and radians.</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Step 1</b></td>
            <td><b>Identify the angle and its quadrant.</b><br>
            Look up the coordinates on the unit circle. The standard angle with these coordinates is ' . $deg . '&deg;, which is in <b>Quadrant ' . $quad . '</b>.</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Step 2</b></td>
            <td><b>Apply the reference angle rule.</b><br>
            The reference angle is the acute angle between the terminal side and the x-axis. Each quadrant has its own formula:
              <ul style="margin:0.3em 0 0.3em 1.2em;">
                <li>Quadrant I: reference angle = `theta`</li>
                <li>Quadrant II: reference angle = `180 - theta`</li>
                <li>Quadrant III: reference angle = `theta - 180`</li>
                <li>Quadrant IV: reference angle = `360 - theta`</li>
              </ul>
            Since ' . $deg . '&deg; is in Quadrant ' . $quad . ', we use `' . $rule . '`:<br>
            Reference angle = `' . $rule . '` = `' . $refd . '`&deg;</td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Step 3</b></td>
            <td class="col-check-bot"><b>Convert degrees to radians.</b><br>
            To convert any angle from degrees to radians, multiply by `pi/180`:<br>
            `' . $refd . ' * pi/180 = pi/' . $refden . '`
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Reference angle:</b> ' . $refd . '&deg; = `pi/' . $refden . '` radians
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
<p>The point P = `($xd, $yd)` is on the unit circle.</p>
<div style="margin:15px auto; text-align:center;">$graph</div>
<p>Find the <b>reference angle</b> for the angle in standard position whose terminal side passes through P.</p>
</div>

(a) $answerbox[0] degrees

(b) $answerbox[1] radians


// === ANSWER ===

$solutionguide
