// === COMMON CONTROL ===

// Question: Given y = A*sin(Bx), find amplitude, period, and a specific value
// Comprehensive: amplitude, period, and sin at a specific angle

// Amplitude choices
$A_vals = array(2, 3, 4, 5, -2, -3)
$a_i = rand(0, 5)
$A = $A_vals[$a_i]
$amp = abs($A)

// B choices
$B_vals = array(1, 2, 3, 1/2)
$B_disps = array("1", "2", "3", "1/2")
$B_periods = array("(2pi)/1", "pi", "(2pi)/3", "4pi")
$B_period_nums = array(2, 1, 2, 4)
$B_period_dens = array(1, 1, 3, 1)
$b_i = rand(0, 3)
$B = $B_vals[$b_i]
$B_disp = $B_disps[$b_i]
$period_disp = $B_periods[$b_i]
$period_num = $B_period_nums[$b_i]
$period_den = $B_period_dens[$b_i]

// Build period answer string
if ($period_den == 1) {
  if ($period_num == 1) {
    $period_ans = "pi"
  } else {
    $period_ans = $period_num . "pi"
  }
} else {
  if ($period_num == 1) {
    $period_ans = "pi/" . $period_den
  } else {
    $period_ans = "(" . $period_num . "pi)/" . $period_den
  }
}

// Equation display
$eq_show = "y = " . $A . " sin(" . $B_disp . "x)"

// Pick a standard angle to evaluate sin at
// Use angles that work cleanly with B values
// For part (c), ask: evaluate y at x = some angle
$eval_degs = array(30, 45, 60, 90)
$eval_sines = array("1/2", "sqrt(2)/2", "sqrt(3)/2", "1")
$eval_radians = array("pi/6", "pi/4", "pi/3", "pi/2")
$e_i = rand(0, 3)
$eval_deg = $eval_degs[$e_i]
$eval_sine = $eval_sines[$e_i]
$eval_rad = $eval_radians[$e_i]

// Compute y = A * sin(Bx) = A * sin(B * angle_in_radians)
// Use numeric computation for answer comparison, but build exact display string
$eval_x_rad = $eval_deg * pi / 180
$eval_y_numeric = $A * sin($B * $eval_x_rad)

// For the answer: we need sin(B * angle_in_radians)
// E.g., if B=2 and angle=pi/4, then sin(2*pi/4) = sin(pi/2) = 1
// We'll precompute a small table of B*angle combos
// B*angle in radians must give a standard unit circle angle

// Instead, let's compute the actual numeric answer and round
// For exact display, we build the string
$comp_angle_deg = $B * $eval_deg

// Check if comp_angle_deg is a standard angle
// Standard angles: 0, 30, 45, 60, 90, 120, 135, 150, 180, 210, 225, 240, 270, 300, 315, 330, 360
// sin values for these
$all_degs = array(0, 30, 45, 60, 90, 120, 135, 150, 180, 210, 225, 240, 270, 300, 315, 330, 360)
$all_sines = array("0", "1/2", "sqrt(2)/2", "sqrt(3)/2", "1", "sqrt(3)/2", "sqrt(2)/2", "1/2", "0", "-1/2", "-sqrt(2)/2", "-sqrt(3)/2", "-1", "-sqrt(3)/2", "-sqrt(2)/2", "-1/2", "0")

// Find matching sin value
$comp_angle_rounded = round($comp_angle_deg)
$sin_comp = ""
$s = 0
while ($s < count($all_degs)) {
  if ($all_degs[$s] == $comp_angle_rounded) {
    $sin_comp = $all_sines[$s]
    $s = count($all_degs)
  }
  $s = $s + 1
}

// If no exact match found, fall back to numeric (shouldn't happen with our combos)
if ($sin_comp == "") {
  $sin_comp = round($eval_y_numeric / $A, 4)
}

// Build y answer: A * sin_comp
// Need to handle A * sin_comp properly for display
if ($A > 0) {
  if ($sin_comp == "0") {
    $y_ans = "0"
  } else if ($sin_comp == "1") {
    $y_ans = "" . $A
  } else if ($sin_comp == "-1") {
    $y_ans = "" . (-$A)
  } else {
    $y_ans = $A . "*" . $sin_comp
  }
} else {
  // A is negative
  if ($sin_comp == "0") {
    $y_ans = "0"
  } else if ($sin_comp == "1") {
    $y_ans = "" . $A
  } else if ($sin_comp == "-1") {
    $y_ans = "" . (-$A)
  } else {
    $y_ans = $A . "*" . $sin_comp
  }
}

// For $answer[2], use calculated type with the computed numeric value
// Use the numeric value for auto-grading, but nodecimal allows radical expressions

// Graph the function
$func_str = $A . "*sin(" . $B . "*x)"
if ($B >= 1) {
  $x_win = 7.5
} else {
  $x_win = 14
}
$y_win = $amp + 1.5
$ny_win = -1 * $y_win

$sine_curve = showplot($func_str . ",blue,-0.3," . $x_win . ",,,2", -0.5, $x_win, $ny_win, $y_win, "pi/2:1", "1:1", 400, 250)
if ($B == 2) {
  $sine_graph = addfractionaxislabels($sine_curve, "pi/4")
} else if ($B == 3) {
  $sine_graph = addfractionaxislabels($sine_curve, "pi/3")
} else if ($B == 0.5) {
  $sine_graph = addfractionaxislabels($sine_curve, "pi")
} else {
  $sine_graph = addfractionaxislabels($sine_curve, "pi/2")
}
$graph = $sine_graph

// Answer setup
$anstypes = array("number", "calculated", "calculated")
$answer[0] = $amp
$answer[1] = $period_ans
$answer[2] = $y_ans
$answerformat[0] = "integer"
$answerformat[1] = "nodecimal"
$answerformat[2] = "nodecimal"
$ansprompt[0] = "Amplitude = "
$ansprompt[1] = "Period = "
$ansprompt[2] = $eq_show . " at x = " . $eval_rad . " = "

// Negative sign note
if ($A < 0) {
  $neg_note = "Since A = " . $A . " is negative, the graph is reflected over the x-axis. Amplitude = |A| = |" . $A . "| = " . $amp . "."
} else {
  $neg_note = "A = " . $A . ", so amplitude = |A| = " . $amp . "."
}

// Evaluation note for solution
$eval_note = "sin(" . $B_disp . " &middot; " . $eval_rad . ")"

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
            <td style="text-align:center;"><b>Given</b></td>
            <td>`' . $eq_show . '`</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Part (a)</b></td>
            <td><b>Amplitude = |A|.</b><br>
            ' . $neg_note . '<br>
            <b>Amplitude = ' . $amp . '</b></td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Part (b)</b></td>
            <td><b>Period = (2`pi`)/B.</b><br>
            B = ' . $B_disp . ', so Period = `(2pi)/' . $B_disp . '` = `' . $period_disp . '`</td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Part (c)</b></td>
            <td class="col-check-bot"><b>Evaluate at x = ' . $eval_rad . '.</b><br>
            `' . $eq_show . '` at x = `' . $eval_rad . '`:<br>
            = ' . $A . ' &middot; sin(' . $B_disp . ' &middot; ' . $eval_rad . ') = ' . $A . ' &middot; `' . $sin_comp . '` = `' . $y_ans . '`
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Answers:</b> Amplitude = ' . $amp . ', Period = `' . $period_disp . '`, f(' . $eval_rad . ') = `' . $y_ans . '`
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
<p>Consider the function <b>$eq_show</b>.</p>
<div style="margin:15px auto; text-align:center;">$graph</div>
<p>Find the following:</p>
</div>

(a) Amplitude: $answerbox[0]

(b) Period: $answerbox[1]

(c) Evaluate $eq_show at x = $eval_rad: $answerbox[2]


// === ANSWER ===

$solutionguide