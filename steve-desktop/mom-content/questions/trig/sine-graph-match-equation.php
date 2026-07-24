// === COMMON CONTROL ===

// Question: Which equation matches the graph?
// Randomly generate a sine function, graph it, and offer 4 choices

// Amplitude choices
$A_vals = array(2, 3, 4)
$a_i = rand(0, 2)
$A = $A_vals[$a_i]

// B choices (affecting period)
$B_vals = array(1, 2, 3)
$B_disps = array("1", "2", "3")
$B_periods = array("2pi", "pi", "(2pi)/3")
$b_i = rand(0, 2)
$B = $B_vals[$b_i]
$B_disp = $B_disps[$b_i]
$period_disp = $B_periods[$b_i]

// Build correct equation display
$correct_eq = "y = " . $A . " sin(" . $B_disp . "x)"

// Build distractors:
// D1: wrong amplitude (swap A with another value)
// D2: wrong period (swap B with another value)
// D3: wrong amplitude AND period

// Pick wrong A
$wrong_A_options = array()
$j = 0
while ($j < count($A_vals)) {
  if ($A_vals[$j] != $A) {
    $wrong_A_options = arraypush($wrong_A_options, $A_vals[$j])
  }
  $j = $j + 1
}
$wrong_A = $wrong_A_options[rand(0, count($wrong_A_options)-1)]

// Pick wrong B
$wrong_B_options = array()
$k = 0
while ($k < count($B_vals)) {
  if ($B_vals[$k] != $B) {
    $wrong_B_options = arraypush($wrong_B_options, $k)
  }
  $k = $k + 1
}
$wrong_b_idx = $wrong_B_options[rand(0, count($wrong_B_options)-1)]
$wrong_B = $B_vals[$wrong_b_idx]
$wrong_B_disp = $B_disps[$wrong_b_idx]

$distractor1 = "y = " . $wrong_A . " sin(" . $B_disp . "x)"
$distractor2 = "y = " . $A . " sin(" . $wrong_B_disp . "x)"
$distractor3 = "y = " . $wrong_A . " sin(" . $wrong_B_disp . "x)"

// Shuffle correct position
$correct_pos = rand(0, 3)
$choices = array("", "", "", "")
$choices[$correct_pos] = $correct_eq
$remaining = array($distractor1, $distractor2, $distractor3)
$ridx = 0
$p = 0
while ($p < 4) {
  if ($choices[$p] == "") {
    $choices[$p] = $remaining[$ridx]
    $ridx = $ridx + 1
  }
  $p = $p + 1
}

// Graph the correct function
$B_val = $B
$func_str = $A . "*sin(" . $B_val . "*x)"

if ($B_val == 1) {
  $x_win = 7.5
} else if ($B_val == 2) {
  $x_win = 4
} else {
  $x_win = 3
}
$y_win = $A + 1
$ny_win = -1 * $y_win

$sine_graph = showplot($func_str . ",blue,-0.3," . $x_win . ",,,2", -0.5, $x_win, $ny_win, $y_win, "pi/2:1", "1:1", 400, 250)
if ($B_val == 2) {
  $sine_graph = addfractionaxislabels($sine_graph, "pi/4")
} else if ($B_val == 3) {
  $sine_graph = addfractionaxislabels($sine_graph, "pi/3")
} else {
  $sine_graph = addfractionaxislabels($sine_graph, "pi/2")
}
$graph = $sine_graph

// Multiple choice setup
$anstypes = array("multiple_choice")
$questions = $choices
$answer = $correct_pos

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
            <td style="text-align:center;"><b>Step 1</b></td>
            <td><b>Read the amplitude from the graph.</b><br>
            The amplitude is the distance from the center line (y = 0) to the peak. The graph peaks at y = ' . $A . ' and reaches y = ' . (-1*$A) . '.<br>
            Amplitude = |' . $A . '| = <b>' . $A . '</b></td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Step 2</b></td>
            <td><b>Read the period from the graph.</b><br>
            The period is the distance along the x-axis for one complete cycle. From the graph, one full cycle completes at x = `' . $period_disp . '`.<br>
            Period = <b>`' . $period_disp . '`</b></td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Step 3</b></td>
            <td class="col-check-bot"><b>Write the equation.</b><br>
            For y = A sin(Bx): A = ' . $A . ', and since Period = `(2pi)/B`, we get B = ' . $B_disp . '.<br>
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Correct equation:</b> `' . $correct_eq . '`
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
<p>Which equation matches the graph shown below?</p>
<div style="margin:15px auto; text-align:center;">$graph</div>
</div>

$answerbox[0]


// === ANSWER ===

$solutionguide