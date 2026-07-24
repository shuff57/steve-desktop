// === GRAPH SINE FIND VALUES - Given a sine graph, find sin(theta) for random angles ===
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

// Pick 3 different random angles
$idx = diffrands(0, 14, 3)
$a1 = $idx[0]
$a2 = $idx[1]
$a3 = $idx[2]

// Extract angle data for each
$deg1 = $degs[$a1]
$deg2 = $degs[$a2]
$deg3 = $degs[$a3]

$rn1 = $rad_nums[$a1]
$rd1 = $rad_dens[$a1]
$rn2 = $rad_nums[$a2]
$rd2 = $rad_dens[$a2]
$rn3 = $rad_nums[$a3]
$rd3 = $rad_dens[$a3]

// Build radian display strings
if ($rd1 == 1) {
  $rad1 = "pi"
} else if ($rn1 == 1) {
  $rad1 = "pi/" . $rd1
} else {
  $rad1 = "(" . $rn1 . "pi)/" . $rd1
}

if ($rd2 == 1) {
  $rad2 = "pi"
} else if ($rn2 == 1) {
  $rad2 = "pi/" . $rd2
} else {
  $rad2 = "(" . $rn2 . "pi)/" . $rd2
}

if ($rd3 == 1) {
  $rad3 = "pi"
} else if ($rn3 == 1) {
  $rad3 = "pi/" . $rd3
} else {
  $rad3 = "(" . $rn3 . "pi)/" . $rd3
}

// Sin values (y-coordinate = sin(theta))
$sin1 = $ydisp[$a1]
$sin2 = $ydisp[$a2]
$sin3 = $ydisp[$a3]

// Answer setup
$anstypes = array("calculated", "calculated", "calculated")
$answer[0] = $sin1
$answer[1] = $sin2
$answer[2] = $sin3
$answerformat[0] = "nodecimal"
$answerformat[1] = "nodecimal"
$answerformat[2] = "nodecimal"

// Sine graph
$graph = showplot("sin(x),blue,0,6.2832,,,2", -0.5, 7, -1.5, 1.5, "1:1", "1:1", 400, 250)

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
            <td style="text-align:center;"><b>Key Idea</b></td>
            <td>The sine of an angle equals the <b>y-coordinate</b> of the corresponding terminal point on the unit circle. On the graph of y = sin(x), each x-value is an angle (in radians), and the y-value is sin(x), which is the y-coordinate of the unit circle point.</td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Part (a)</b></td>
            <td>Find sin(' . $rad1 . ').<br>
            The angle ' . $rad1 . ' = ' . $deg1 . '&deg; on the unit circle has terminal point (' . $xdisp[$a1] . ', ' . $sin1 . ').<br>
            The y-coordinate gives us sin(' . $rad1 . ') = <b>' . $sin1 . '</b>.</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Part (b)</b></td>
            <td>Find sin(' . $rad2 . ').<br>
            The angle ' . $rad2 . ' = ' . $deg2 . '&deg; on the unit circle has terminal point (' . $xdisp[$a2] . ', ' . $sin2 . ').<br>
            The y-coordinate gives us sin(' . $rad2 . ') = <b>' . $sin2 . '</b>.</td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Part (c)</b></td>
            <td class="col-check-bot">Find sin(' . $rad3 . ').<br>
            The angle ' . $rad3 . ' = ' . $deg3 . '&deg; on the unit circle has terminal point (' . $xdisp[$a3] . ', ' . $sin3 . ').<br>
            The y-coordinate gives us sin(' . $rad3 . ') = <b>' . $sin3 . '</b>.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:Arial; font-size:medium; line-height:1.6;">
<p>The graph of <b>y = sin(x)</b> is shown below for one full period from <b>x = 0</b> to <b>x = 2pi</b>.</p>
<div style="margin:15px auto; text-align:center;">$graph</div>
<p>Remember: on this graph, the x-axis shows angles in radians, and the y-value at each point equals sin(x), which is the y-coordinate of the terminal point on the unit circle.</p>
</div>

<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part (a)</span>
Find sin(`$rad1`).
<div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
</div>

<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part (b)</span>
Find sin(`$rad2`).
<div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
</div>

<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part (c)</span>
Find sin(`$rad3`).
<div style="margin-top:12px;text-align:center;">$answerbox[2]</div>
</div>

///

$solutionguide