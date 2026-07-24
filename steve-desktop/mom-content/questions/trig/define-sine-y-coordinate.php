// === COMMON CONTROL ===

loadlibrary("stats")

// --- 15 standard unit circle angles ---
$degs = array(30, 45, 60, 90, 120, 135, 150, 180, 210, 225, 240, 270, 300, 315, 330)

// Radian = rad_num * pi / rad_den
$rad_nums = array(1, 1, 1, 1, 2, 3, 5, 1, 7, 5, 4, 3, 5, 7, 11)
$rad_dens = array(6, 4, 3, 2, 3, 4, 6, 1, 6, 4, 3, 2, 3, 4, 6)

// x-coordinate display strings
$xdisp = array("sqrt(3)/2", "sqrt(2)/2", "1/2", "0", "-1/2", "-sqrt(2)/2", "-sqrt(3)/2", "-1", "-sqrt(3)/2", "-sqrt(2)/2", "-1/2", "0", "1/2", "sqrt(2)/2", "sqrt(3)/2")

// y-coordinate display strings
$ydisp = array("1/2", "sqrt(2)/2", "sqrt(3)/2", "1", "sqrt(3)/2", "sqrt(2)/2", "1/2", "0", "-1/2", "-sqrt(2)/2", "-sqrt(3)/2", "-1", "-sqrt(3)/2", "-sqrt(2)/2", "-1/2")

$i = rand(0, 14)

$deg = $degs[$i]
$rn = $rad_nums[$i]
$rd = $rad_dens[$i]
$xd = $xdisp[$i]
$yd = $ydisp[$i]

// Build radian display string
if ($rd == 1) {
  $rad_disp = "`pi`"
} else if ($rn == 1) {
  $rad_disp = "`pi/$rd`"
} else {
  $rad_disp = "`($rn pi)/$rd`"
}

// Numeric coordinates for plotting
$xn = cos($deg * pi / 180)
$yn = sin($deg * pi / 180)

// Unit circle graph
$circle = showplot("[cos(t),sin(t)],blue,0,6.2832,,,2", -1.5, 1.5, -1.5, 1.5, "1:1", "1:1", 300, 300)
$dot = showplot("dot,$xn,$yn,closed,red", -1.5, 1.5, -1.5, 1.5, "1:1", "1:1", 300, 300)
$radius = showplot("[$xn*t,$yn*t],gray,0,1,,,1,dash", -1.5, 1.5, -1.5, 1.5, "1:1", "1:1", 300, 300)
$graph = mergeplots($circle, $radius, $dot)
$graph = addlabel($graph, $xn, $yn, "P", "red", "right")

// Multipart answer setup
$anstypes = array("calculated", "calculated", "choices")
$answer[0] = $yd
$answer[1] = $xd
$answerformat[0] = "nodecimal"
$answerformat[1] = "nodecimal"
$questions[2] = array("y-coordinate", "x-coordinate")
$noshuffle[2] = "all"
$answer[2] = 0
$ansprompt[0] = "y-coordinate = "
$ansprompt[1] = "x-coordinate = "
$ansprompt[2] = ""

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
            <td>An angle of ' . $deg . '&deg; (' . $rad_disp . ' radians) is drawn in standard position on the unit circle, with terminal point P shown on the graph.</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Part (a)</b></td>
            <td><b>Read the y-coordinate of P from the unit circle.</b><br>
            The point P has coordinates `(' . $xd . ', ' . $yd . ')`. The y-coordinate is `' . $yd . '`.</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Part (b)</b></td>
            <td><b>Read the x-coordinate of P from the unit circle.</b><br>
            The point P has coordinates `(' . $xd . ', ' . $yd . ')`. The x-coordinate is `' . $xd . '`.</td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Part (c)</b></td>
            <td class="col-check-bot"><b>By definition, sin(theta) equals the y-coordinate of the terminal point.</b><br>
            For any angle `theta` in standard position, the terminal point on the unit circle is `(cos(theta), sin(theta))`. This means:
              <ul style="margin:0.3em 0 0.3em 1.2em;">
                <li>The <b>x-coordinate</b> equals `cos(theta)`</li>
                <li>The <b>y-coordinate</b> equals `sin(theta)`</li>
              </ul>
            So `sin(' . $deg . '&deg;) = ' . $yd . '`, which is the y-coordinate.
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>sin(theta)</b> is the <b>y-coordinate</b> of the terminal point.
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
<p>On the unit circle, an angle of <b>$deg&deg;</b> (that is, $rad_disp) is drawn in standard position. The terminal point P is plotted on the graph below.</p>
<div style="margin:15px auto; text-align:center;">$graph</div>
<p>Use the definition of sine and cosine to answer the following.</p>
</div>

(a) $answerbox[0] What is the <b>y-coordinate</b> of the terminal point P?

(b) $answerbox[1] What is the <b>x-coordinate</b> of the terminal point P?

(c) $answerbox[2] Which coordinate of the terminal point equals `sin(theta)`?


// === ANSWER ===

$solutionguide