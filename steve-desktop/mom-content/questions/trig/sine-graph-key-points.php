// === COMMON CONTROL ===

// Graph of y = sin(x) over one period [0, 2pi]
// Student identifies coordinates of key points: maximum, zero, minimum

// Key points on y = sin(x), one full period:
// (0, 0), (pi/2, 1), (pi, 0), (3pi/2, -1), (2pi, 0)

// Randomly select which point to ask about
$point_types = array("maximum", "zero", "minimum", "zero")
$x_vals = array("pi/2", "pi", "3pi/2", "2pi")
$y_vals = array("1", "0", "-1", "0")

// Answer strings for calculated ntuple type
$ans_x = array("pi/2", "pi", "(3pi)/2", "(2pi)/1")
$ans_y = array("1", "0", "-1", "0")

// Display labels
$point_labels = array("the maximum point", "the leftmost zero in [pi, 2pi]", "the minimum point", "the rightmost zero")

// Choose a random key point
$j = rand(0, 2)

// Only 3 distinct key points: max, mid-zero, min
// Let's ask about one specific type
$ask_type = array("maximum", "zero", "minimum")
$ask_idx = rand(0, 2)

if ($ask_idx == 0) {
  // Maximum: (pi/2, 1)
  $ask_label = "the maximum point"
  $ans_x_str = "pi/2"
  $ans_y_str = "1"
  $x_display = "`pi/2`"
  $y_display = "`1`"
} else if ($ask_idx == 1) {
  // Zero at x = pi
  $ask_label = "a zero of the function (where the graph crosses the x-axis) other than the origin"
  $ans_x_str = "pi"
  $ans_y_str = "0"
  $x_display = "`pi`"
  $y_display = "`0`"
} else {
  // Minimum: (3pi/2, -1)
  $ask_label = "the minimum point"
  $ans_x_str = "(3pi)/2"
  $ans_y_str = "-1"
  $x_display = "`(3pi)/2`"
  $y_display = "`-1`"
}

// Build sine graph using showplot
// y = sin(x) from 0 to 2pi+0.5
$sine_curve = showplot("sin(x),blue,0,6.78,,,2", -0.5, 7, -1.8, 1.8, "pi/2:1", "1:1", 400, 250)
// Add pi-fraction labels on x-axis
$sine_graph = addfractionaxislabels($sine_curve, "pi/2")

// Labeled key points with dots
$dot_max = showplot("dot,1.5708,1,closed,red", -0.5, 7, -1.8, 1.8, "pi/2:1", "1:1", 400, 250)
$dot_zero_pi = showplot("dot,3.1416,0,closed,green", -0.5, 7, -1.8, 1.8, "pi/2:1", "1:1", 400, 250)
$dot_min = showplot("dot,4.7124,-1,closed,orange", -0.5, 7, -1.8, 1.8, "pi/2:1", "1:1", 400, 250)
$dot_zero_2pi = showplot("dot,6.2832,0,closed,green", -0.5, 7, -1.8, 1.8, "pi/2:1", "1:1", 400, 250)
$dot_origin = showplot("dot,0,0,closed,green", -0.5, 7, -1.8, 1.8, "pi/2:1", "1:1", 400, 250)

$graph = mergeplots($sine_curve, $dot_max, $dot_zero_pi, $dot_min, $dot_zero_2pi, $dot_origin)
$graph = addfractionaxislabels($graph, "pi/2")
$graph = addlabel($graph, 1.5708, 1, "A", "red", "above")
$graph = addlabel($graph, 3.1416, 0, "B", "green", "below")
$graph = addlabel($graph, 4.7124, -1, "C", "orange", "below")
$graph = addlabel($graph, 6.2832, 0, "D", "green", "below")
$graph = addlabel($graph, 0, 0, "O", "green", "below")

// Multipart: ask for (x, y) as calculated ntuple
$anstypes = array("calcntuple")
$answer[0] = "($ans_x_str, $ans_y_str)"
$answerformat[0] = "nodecimal"

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

// Build solution based on which point was asked
if ($ask_idx == 0) {
  $sol_steps = '
          <tr>
            <td style="text-align:center;"><b>Step 1</b></td>
            <td><b>Identify the maximum.</b><br>
            The graph of y = sin(x) reaches its peak at y = 1. On the graph, this is point A at the top of the wave.</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Step 2</b></td>
            <td><b>Read the x-coordinate.</b><br>
            The maximum occurs one-quarter of the way through the period. For y = sin(x) with period 2`pi`, the maximum is at x = `pi/2`.</td>
          </tr>'
} else if ($ask_idx == 1) {
  $sol_steps = '
          <tr>
            <td style="text-align:center;"><b>Step 1</b></td>
            <td><b>Identify the zero.</b><br>
            The graph of y = sin(x) crosses the x-axis (where sin(x) = 0) at x = 0, x = `pi`, and x = `2pi`. On the graph, these are points O, B, and D.</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Step 2</b></td>
            <td><b>Read the coordinates.</b><br>
            A zero inside the period (other than the endpoints) is point B at x = `pi`. Since it is on the x-axis, y = 0.</td>
          </tr>'
} else {
  $sol_steps = '
          <tr>
            <td style="text-align:center;"><b>Step 1</b></td>
            <td><b>Identify the minimum.</b><br>
            The graph of y = sin(x) reaches its lowest point at y = -1. On the graph, this is point C at the bottom of the wave.</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Step 2</b></td>
            <td><b>Read the x-coordinate.</b><br>
            The minimum occurs three-quarters of the way through the period. For y = sin(x) with period 2`pi`, the minimum is at x = `(3pi)/2`.</td>
          </tr>'
}

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
            <td>The graph of y = sin(x) over one period. Key points are labeled A through D.</td>
          </tr>' . $sol_steps . '
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Answer</b></td>
            <td class="col-check-bot">
              <div style="margin-top:4px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Coordinates:</b> ' . $x_display . ', ' . $y_display . '
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
<p>The graph of <b>y = sin(x)</b> over one period is shown below. The labeled points are:</p>
<ul style="margin:0.3em 0 0.3em 1.2em;">
  <li><b>O</b> = origin (0, 0)</li>
  <li><b>A</b> = maximum</li>
  <li><b>B</b> = zero at x = `pi`</li>
  <li><b>C</b> = minimum</li>
  <li><b>D</b> = zero at x = `2pi`</li>
</ul>
<div style="margin:15px auto; text-align:center;">$graph</div>
<p>Find the coordinates of ' . $ask_label . '. Give x as a multiple of `pi` (e.g., <code>pi/2</code> or <code>(3pi)/2</code>) and y as an integer.</p>
</div>

Coordinates: $answerbox[0]


// === ANSWER ===

$solutionguide