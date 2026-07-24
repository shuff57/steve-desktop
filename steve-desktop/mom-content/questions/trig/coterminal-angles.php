// === COMMON CONTROL ===

// --- 15 standard unit circle angles ---
$degs = array(30, 45, 60, 90, 120, 135, 150, 180, 210, 225, 240, 270, 300, 315, 330)

// x-coordinate display strings
$xdisp = array("sqrt(3)/2", "sqrt(2)/2", "1/2", "0", "-1/2", "-sqrt(2)/2", "-sqrt(3)/2", "-1", "-sqrt(3)/2", "-sqrt(2)/2", "-1/2", "0", "1/2", "sqrt(2)/2", "sqrt(3)/2")

// y-coordinate display strings
$ydisp = array("1/2", "sqrt(2)/2", "sqrt(3)/2", "1", "sqrt(3)/2", "sqrt(2)/2", "1/2", "0", "-1/2", "-sqrt(2)/2", "-sqrt(3)/2", "-1", "-sqrt(3)/2", "-sqrt(2)/2", "-1/2")

$i = rand(0, 14)

$deg = $degs[$i]
$xd = $xdisp[$i]
$yd = $ydisp[$i]

// Coterminal angles
$pos_coterm = $deg + 360
$neg_coterm = $deg - 360

// Numeric coordinates for plotting
$xn = cos($deg * pi / 180)
$yn = sin($deg * pi / 180)

// Unit circle graph
$circle = showplot("[cos(t),sin(t)],blue,0,6.2832,,,2", -1.5, 1.5, -1.5, 1.5, "1:1", "1:1", 300, 300)
$dot = showplot("dot,$xn,$yn,closed,red", -1.5, 1.5, -1.5, 1.5, "1:1", "1:1", 300, 300)
$radius = showplot("[$xn*t,$yn*t],gray,0,1,,,1,dash", -1.5, 1.5, -1.5, 1.5, "1:1", "1:1", 300, 300)
$graph = mergeplots($circle, $radius, $dot)
$graph = addlabel($graph, $xn, $yn, "P", "red", "right")

// Answer setup
$anstypes = array("number", "number")
$answer[0] = $pos_coterm
$answer[1] = $neg_coterm
$answerformat[0] = "integer"
$answerformat[1] = "integer"
$ansprompt[0] = "Positive coterminal angle: "
$ansprompt[1] = "Negative coterminal angle: "

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
            <td>The angle is ' . $deg . '&deg; (terminal point P = `(' . $xd . ', ' . $yd . ')`).<br>
            <b>Key idea:</b> Coterminal angles are angles that share the same terminal side on the unit circle. You can find them by adding or subtracting full rotations (360&deg;).</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Part (a)</b></td>
            <td><b>Find the smallest positive coterminal angle greater than 360&deg;.</b><br>
            Add one full rotation to the original angle:<br>
            ' . $deg . '&deg; + 360&deg; = <b>' . $pos_coterm . '&deg;</b></td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Part (b)</b></td>
            <td class="col-check-bot"><b>Find the largest negative coterminal angle.</b><br>
            Subtract one full rotation from the original angle:<br>
            ' . $deg . '&deg; &minus; 360&deg; = <b>' . $neg_coterm . '&deg;</b>
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Positive:</b> ' . $pos_coterm . '&deg; &nbsp;&nbsp; <b>Negative:</b> ' . $neg_coterm . '&deg;
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
<p>The point P = `($xd, $yd)` on the unit circle corresponds to an angle of $deg&deg;.</p>
<div style="margin:15px auto; text-align:center;">$graph</div>
<p>Find coterminal angles:</p>
</div>

(a) Smallest positive coterminal angle greater than 360&deg;: $answerbox[0] degrees

(b) Largest negative coterminal angle: $answerbox[1] degrees


// === ANSWER ===

$solutionguide
