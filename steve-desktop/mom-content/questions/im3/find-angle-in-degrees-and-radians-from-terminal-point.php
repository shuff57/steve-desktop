// === NAME - DESCRIPTION: Find Angle in Degrees and Radians from Terminal Point (local for DEYCI LOPEZ) (local for Steven Huff) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
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

// Numeric coordinates for plotting
$xn = cos($deg * pi / 180)
$yn = sin($deg * pi / 180)

// Build radian answer string
if ($rd == 1) {
  $rad_ans = "pi"
  $rad_show = "`pi`"
} else if ($rn == 1) {
  $rad_ans = "pi/" . $rd
  $rad_show = dispreducedfraction($rn, $rd)
} else {
  $rad_ans = "(" . $rn . "pi)/" . $rd
  $rad_show = dispreducedfraction($rn, $rd)
}

// Unit circle graph
$circle = showplot("[cos(t),sin(t)],blue,0,6.2832,,,2", -1.5, 1.5, -1.5, 1.5, "1:1", "1:1", 300, 300)
$dot = showplot("dot,$xn,$yn,closed,red", -1.5, 1.5, -1.5, 1.5, "1:1", "1:1", 300, 300)
$radius = showplot("[$xn*t,$yn*t],gray,0,1,,,1,dash", -1.5, 1.5, -1.5, 1.5, "1:1", "1:1", 300, 300)
$graph = mergeplots($circle, $radius, $dot)
$graph = addlabel($graph, $xn, $yn, "P", "red", "right")

// Multipart answer setup
$anstypes = array("number", "calculated")
$answer[0] = $deg
$answer[1] = $rad_ans
$answerformat[0] = "integer"
$answerformat[1] = "nodecimal"
$ansprompt[0] = "Angle in degrees: "
$ansprompt[1] = "Angle in radians: "

// Solution guide
$solutionguide = '
<div style="font-family:Arial;font-size:medium;margin:1em 0;border:2px solid #ccc;border-radius:8px;overflow:hidden;">
  <details>
    <summary style="cursor:pointer;padding:0.6em 1em;background:#f0f4ff;font-weight:bold;list-style:none;border-bottom:1px solid #ccc;">
      &#9658; Step-by-Step Solution
    </summary>
    <div style="padding:1em 1.4em;line-height:1.8;">
      <p style="margin:0 0 0.6em;"><b>Given:</b> The point P = `(' . $xd . ', ' . $yd . ')` is on the unit circle.</p>
      <p style="margin:0 0 0.4em;"><b>Step 1:</b> Recognize the coordinates from the unit circle.</p>
      <p style="margin:0 0 0.6em 1.5em;">These coordinates correspond to a standard angle of <b>' . $deg . '&deg;</b>.</p>
      <p style="margin:0 0 0.4em;"><b>Step 2:</b> Convert degrees to radians.</p>
      <p style="margin:0 0 0.6em 1.5em;">Multiply by `pi/180`:</p>
      <p style="margin:0 0 0.6em 1.5em;">`' . $deg . ' * pi/180 = (' . $rn . 'pi)/' . $rd . '`</p>
      <div style="margin:0 0 0.5em;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;display:inline-block;">
        <b>Degrees:</b> ' . $deg . '&deg; &nbsp;&nbsp; <b>Radians:</b> `(' . $rn . 'pi)/' . $rd . '`
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===
<div style="font-family:Arial; font-size:medium; line-height:1.6;">
  <p>The point P = `($xd, $yd)` is on the unit circle.</p>
  <div style="margin:15px auto; text-align:center;">$graph</div>
  <p>Find the angle `theta` (where `0 &lt;= theta &lt; 2pi`) in standard position whose terminal side passes through P.</p>
</div>

(a) $answerbox[0] degrees

(b) $answerbox[1] radians

// === ANSWER ===
$solutionguide
