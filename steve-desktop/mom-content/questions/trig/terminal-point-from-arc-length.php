// === COMMON CONTROL ===

// --- 15 standard unit circle angles ---
$degs = array(30, 45, 60, 90, 120, 135, 150, 180, 210, 225, 240, 270, 300, 315, 330)

// Radian = rad_num * pi / rad_den (also equals arc length on unit circle)
$rad_nums = array(1, 1, 1, 1, 2, 3, 5, 1, 7, 5, 4, 3, 5, 7, 11)
$rad_dens = array(6, 4, 3, 2, 3, 4, 6, 1, 6, 4, 3, 2, 3, 4, 6)

// Terminal point x-coordinates (as answer expressions)
$xans = array("sqrt(3)/2", "sqrt(2)/2", "1/2", "0", "-1/2", "-sqrt(2)/2", "-sqrt(3)/2", "-1", "-sqrt(3)/2", "-sqrt(2)/2", "-1/2", "0", "1/2", "sqrt(2)/2", "sqrt(3)/2")

// Terminal point y-coordinates (as answer expressions)
$yans = array("1/2", "sqrt(2)/2", "sqrt(3)/2", "1", "sqrt(3)/2", "sqrt(2)/2", "1/2", "0", "-1/2", "-sqrt(2)/2", "-sqrt(3)/2", "-1", "-sqrt(3)/2", "-sqrt(2)/2", "-1/2")

$i = rand(0, 14)

$deg = $degs[$i]
$rn = $rad_nums[$i]
$rd = $rad_dens[$i]
$xa = $xans[$i]
$ya = $yans[$i]

// Build arc length display string
if ($rd == 1) {
  $arc_disp = "`pi`"
} else if ($rn == 1) {
  $arc_disp = "`pi/$rd`"
} else {
  $arc_disp = "`($rn pi)/$rd`"
}

// Answer as calcntuple
$anstypes = array("calcntuple")
$answer = "($xa, $ya)"
$answerformat = "nodecimal"
$displayformat[0] = "point"

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
            <td>Arc length `s = (' . $rn . 'pi)/' . $rd . '` on the unit circle, starting from `(1, 0)`. We need to find the terminal point after traveling this distance counterclockwise.</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Step 1</b></td>
            <td><b>On the unit circle, arc length equals the central angle in radians.</b><br>
            Since `r = 1`, the arc length formula `s = r * theta` simplifies to `s = theta`. This means the arc length is numerically equal to the angle:<br>
            `theta = (' . $rn . 'pi)/' . $rd . '` radians = ' . $deg . '&deg;.</td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Step 2</b></td>
            <td class="col-check-bot"><b>Find the terminal point using the unit circle.</b><br>
            The terminal point for any angle `theta` on the unit circle is `(cos(theta), sin(theta))`. Look up the coordinates for ' . $deg . '&deg; on the unit circle.
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Terminal point:</b> `(' . $xa . ', ' . $ya . ')`
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
<p>A particle starts at the point `(1, 0)` on the unit circle and travels <b>counterclockwise</b> a distance of `s = ` $arc_disp units along the circle.</p>
<p>What are the coordinates of the terminal point? Give exact values.</p>
</div>

Terminal point: $answerbox[0]


// === ANSWER ===

$solutionguide
