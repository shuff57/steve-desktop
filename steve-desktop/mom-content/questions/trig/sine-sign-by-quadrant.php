// === COMMON CONTROL ===

// --- 12 non-axial unit circle angles ---
$degs = array(30, 45, 60, 120, 135, 150, 210, 225, 240, 300, 315, 330)

// Quadrant for each angle
$quads = array(1, 1, 1, 2, 2, 2, 3, 3, 3, 4, 4, 4)

// Sine values (y-coordinates) — exact form
$sine_vals = array("1/2", "sqrt(2)/2", "sqrt(3)/2", "sqrt(3)/2", "sqrt(2)/2", "1/2", "-1/2", "-sqrt(2)/2", "-sqrt(3)/2", "-sqrt(3)/2", "-sqrt(2)/2", "-1/2")

// Cosine values (x-coordinates) — exact form
$cos_vals = array("sqrt(3)/2", "sqrt(2)/2", "1/2", "-1/2", "-sqrt(2)/2", "-sqrt(3)/2", "-sqrt(3)/2", "-sqrt(2)/2", "-1/2", "1/2", "sqrt(2)/2", "sqrt(3)/2")

// Numeric coordinates for graphing
$xn = array(0.866, 0.707, 0.5, -0.5, -0.707, -0.866, -0.866, -0.707, -0.5, 0.5, 0.707, 0.866)
$yn = array(0.5, 0.707, 0.866, 0.866, 0.707, 0.5, -0.5, -0.707, -0.866, -0.866, -0.707, -0.5)

$i = rand(0, 11)

$deg = $degs[$i]
$quad = $quads[$i]
$sv = $sine_vals[$i]
$cv = $cos_vals[$i]
$pxn = $xn[$i]
$pyn = $yn[$i]

// Which quadrants is sine positive?
// Sine > 0 in Q1 and Q2, Sine < 0 in Q3 and Q4
$ques = array("In which quadrants is sin(&theta;) positive?", "In which quadrants is sin(&theta;) negative?", "In which quadrant does sin(&theta;) = positive value for this angle?", "If sin(&theta;) < 0, which quadrants could &theta; be in?")
$ans_ques = array("1 and 2", "3 and 4", "Quadrant $quad", "3 and 4")
$ans_idx = rand(0, 3)

// Build the question and answer based on random selection
if ($ans_idx == 0) {
  $prompt = "In which quadrants is sin(&theta;) positive?"
  $correct = "1 and 2"
  $explanation = "Sine equals the y-coordinate on the unit circle. The y-coordinate is positive above the x-axis, which means Quadrants I and II."
  $highlight = "positive"
} else if ($ans_idx == 1) {
  $prompt = "In which quadrants is sin(&theta;) negative?"
  $correct = "3 and 4"
  $explanation = "Sine equals the y-coordinate on the unit circle. The y-coordinate is negative below the x-axis, which means Quadrants III and IV."
  $highlight = "negative"
} else if ($ans_idx == 2) {
  $prompt = "The angle &theta; = $deg&deg; is in Quadrant $quad. What is the sign of sin($deg&deg;)?"
  $correct = "Quadrant $quad"
  $explanation = "The angle $deg&deg; is in Quadrant $quad. Sine is " . ($quad <= 2 ? "positive" : "negative") . " in Quadrant $quad."
  $highlight = $quad <= 2 ? "positive" : "negative"
} else {
  $prompt = "If sin(&theta;) < 0, which quadrants could &theta; be in?"
  $correct = "3 and 4"
  $explanation = "If sin(&theta;) < 0, the y-coordinate is negative, which means the angle is below the x-axis — Quadrants III or IV."
  $highlight = "negative"
}

// Unit circle graph
$circle = showplot("[cos(t),sin(t)],blue,0,6.2832,,,2", -1.5, 1.5, -1.5, 1.5, "1:1", "1:1", 300, 300)
$dot = showplot("dot,$pxn,$pyn,closed,red", -1.5, 1.5, -1.5, 1.5, "1:1", "1:1", 300, 300)
$radius = showplot("[$pxn*t,$pyn*t],gray,0,1,,,1,dash", -1.5, 1.5, -1.5, 1.5, "1:1", "1:1", 300, 300)
$vline = showplot("[$pxn*t,0],red,0,$pyn,,,1,dash", -1.5, 1.5, -1.5, 1.5, "1:1", "1:1", 300, 300)
$graph = mergeplots($circle, $radius, $vline, $dot)
$graph = addlabel($graph, $pxn, $pyn, "P", "red", "right")

// Multiple choice setup
$anstypes = array("multiple_choice")

if ($ans_idx == 0) {
  $questions = array("Quadrants I and II", "Quadrants I and III", "Quadrants II and IV", "Quadrants III and IV")
  $answer = 0
} else if ($ans_idx == 1) {
  $questions = array("Quadrants I and II", "Quadrants I and III", "Quadrants II and IV", "Quadrants III and IV")
  $answer = 3
} else if ($ans_idx == 2) {
  $questions = array("Quadrant I", "Quadrant II", "Quadrant III", "Quadrant IV")
  $answer = $quad - 1
} else {
  $questions = array("Quadrants I and II", "Quadrants I and III", "Quadrants II and IV", "Quadrants III and IV")
  $answer = 3
}

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

// Build sign description for solution
if ($quad <= 2) {
  $sign_rule = "Sine is positive in Quadrants I and II (above the x-axis) and negative in Quadrants III and IV (below the x-axis)."
} else {
  $sign_rule = "Sine is positive in Quadrants I and II (above the x-axis) and negative in Quadrants III and IV (below the x-axis)."
}

// Solution guide — dynamically builds based on question variant
$sign_detail = ""
if ($ans_idx == 0) {
  $sign_detail = "<b>Step 2</b></td><td><b>Determine where y-coordinates are positive.</b><br>Above the x-axis &rarr; Quadrants I and II. Below &rarr; Quadrants III and IV.<br>Therefore sin(&theta;) is positive in <b>Quadrants I and II</b>"
} else if ($ans_idx == 1) {
  $sign_detail = "<b>Step 2</b></td><td><b>Determine where y-coordinates are negative.</b><br>Below the x-axis &rarr; Quadrants III and IV. Above &rarr; Quadrants I and II.<br>Therefore sin(&theta;) is negative in <b>Quadrants III and IV</b>"
} else if ($ans_idx == 2) {
  $sign_detail = "<b>Step 2</b></td><td><b>Locate the quadrant and check the sign.</b><br>" . $deg . "&deg; is in Quadrant " . $quad . ". In Quadrant " . $quad . ", the y-coordinate is " . ($quad <= 2 ? "positive" : "negative") . ", so sin(" . $deg . "&deg;) is <b>" . ($quad <= 2 ? "positive" : "negative") . "</b>"
} else {
  $sign_detail = "<b>Step 2</b></td><td><b>Identify quadrants where y is negative.</b><br>Below the x-axis &rarr; Quadrants III and IV. Therefore &theta; must be in <b>Quadrants III or IV</b>"
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
            <td style="text-align:center;"><b>Step 1</b></td>
            <td><b>Remember: sin(&theta;) = y-coordinate on the unit circle.</b><br>
            ' . $sign_rule . '</td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;">' . $sign_detail . '
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Answer:</b> ' . $correct . '
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
<p>On the unit circle, cos(&theta;) = `' . $cv . '` and sin(&theta;) = `' . $sv . '` for the point P shown below.</p>
<div style="margin:15px auto; text-align:center;">$graph</div>
<p>' . $prompt . '</p>
</div>

$answerbox[0]


// === ANSWER ===

$solutionguide