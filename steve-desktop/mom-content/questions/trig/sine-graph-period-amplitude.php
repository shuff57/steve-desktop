// === COMMON CONTROL ===

// Question: Given y = A*sin(Bx), identify amplitude and period
// Amplitude = |A|, Period = 2pi/B

// Randomize A (amplitude): pick from small integers, including negative
$A_vals = array(2, 3, 4, 5, -2, -3)
$a_i = rand(0, 5)
$A = $A_vals[$a_i]
$amp = abs($A)

// Randomize B: pick from small integers > 0
$B_vals = array(1, 2, 3, 4, 1/2, 1/3)
$B_disps = array("1", "2", "3", "4", "1/2", "1/3")
$B_periods = array("(2pi)/1", "pi", "(2pi)/3", "pi/2", "4pi", "6pi")
$B_period_nums = array(2, 1, 2, 1, 4, 6)
$B_period_dens = array(1, 1, 3, 2, 1, 1)

$b_i = rand(0, 5)
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

// Build equation display string
// y = A*sin(Bx)
if ($A < 0) {
  $eq_show = "y = " . $A . " sin(" . $B_disp . "x)"
} else {
  $eq_show = "y = " . $A . " sin(" . $B_disp . "x)"
}

// Build the function string for showplot
// Need to handle fractional B: sin(0.5*x) vs sin(2*x)
$B_val = $B
$func_str = $A . "*sin(" . $B_val . "*x)"

// Graph the function
$xmax_n = $period_num / $period_den * 2 + 0.5
// Estimate a reasonable x window
if ($B_val >= 1) {
  $x_win = 7
} else {
  $x_win = 14
}
$y_win = $amp + 1
$ny_win = -1 * $y_win

$sine_curve = showplot($func_str . ",blue,0," . ($x_win - 0.3) . ",,,2", -0.5, $x_win, $ny_win, $y_win, "pi/2:1", "1:1", 400, 250)

// Add x-axis fraction labels
if ($B_val == 2) {
  $sine_graph = addfractionaxislabels($sine_curve, "pi/4")
} else if ($B_val == 3) {
  $sine_graph = addfractionaxislabels($sine_curve, "pi/3")
} else if ($B_val == 4) {
  $sine_graph = addfractionaxislabels($sine_curve, "pi/8")
} else if ($B_val == 0.5) {
  $sine_graph = addfractionaxislabels($sine_curve, "pi")
} else if ($B_val == 1/3) {
  $sine_graph = addfractionaxislabels($sine_curve, "pi")
} else {
  $sine_graph = addfractionaxislabels($sine_curve, "pi/2")
}

$graph = $sine_graph

// Answer setup
$anstypes = array("number", "calculated")
$answer[0] = $amp
$answer[1] = $period_ans
$answerformat[0] = "integer"
$answerformat[1] = "nodecimal"
$ansprompt[0] = "Amplitude = "
$ansprompt[1] = "Period = "

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

// Negative sign note
if ($A < 0) {
  $neg_note = "Note that A = " . $A . " is negative, but amplitude is always non-negative: amplitude = |A| = |" . $A . "| = " . $amp . ". The negative sign causes a reflection over the x-axis."
} else {
  $neg_note = "Since A = " . $A . " is positive, the amplitude is simply A = " . $amp . "."
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
            <td>`' . $eq_show . '`</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Step 1</b></td>
            <td><b>Find the amplitude.</b><br>
            For y = A sin(Bx), the amplitude = |A|. Here A = ' . $A . '.<br>
            ' . $neg_note . '<br>
            <b>Amplitude = ' . $amp . '</b></td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Step 2</b></td>
            <td class="col-check-bot"><b>Find the period.</b><br>
            For y = A sin(Bx), the period = `(2pi)/B`. Here B = ' . $B_disp . '.<br>
            Period = `(2pi)/' . $B_disp . '` = `' . $period_disp . '`
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Amplitude:</b> ' . $amp . ' &nbsp;&nbsp; <b>Period:</b> `' . $period_disp . '`
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
<p>Find the amplitude and period of this sine function.</p>
</div>

(a) $answerbox[0]

(b) $answerbox[1]


// === ANSWER ===

$solutionguide