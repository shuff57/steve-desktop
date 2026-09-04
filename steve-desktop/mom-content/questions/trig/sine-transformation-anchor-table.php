// === Sine Transformation Anchor Table - Given a partially filled anchor table for y = a*sin(x-h) + k, fill in missing y-values and identify max, min, midline, and equation ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

/* ---------- 1. Randomize ---------- */
$amp   = rand(2, 4)
$refl  = randfrom("1,-1")
$a     = $refl * $amp
$h_idx = rand(0, 6)
$d     = randfrom("-2,-1,1,2")
$d_abs = abs($d)

/* ---------- 2. h Arrays ---------- */
$h_vals    = array(0,       0.5236,  0.7854,  1.0472,  -0.5236, -0.7854, -1.0472)
$h_neg_arr = array(0,       0,       0,       0,       1,       1,       1)
$h_abs_arr = array("0",     "pi/6",  "pi/4",  "pi/3",  "pi/6",  "pi/4",  "pi/3")
$h_frac_arr= array("0",     "pi/6",  "pi/4",  "pi/3",  "pi/6",  "pi/4",  "pi/3")
$h_ans_arr = array("0",     "pi/6",  "pi/4",  "pi/3",  "-pi/6", "-pi/4", "-pi/3")

$x0_pi_arr = array("0",     "pi/6",  "pi/4",  "pi/3",  "-pi/6", "-pi/4", "-pi/3")
$x1_pi_arr = array("pi/2",  "2pi/3", "3pi/4", "5pi/6", "pi/3",  "pi/4",  "pi/6")
$x2_pi_arr = array("pi",    "7pi/6", "5pi/4", "4pi/3", "5pi/6", "3pi/4", "2pi/3")
$x3_pi_arr = array("3pi/2", "5pi/3", "7pi/4", "11pi/6","4pi/3", "5pi/4", "7pi/6")
$x4_pi_arr = array("2pi",   "13pi/6","7pi/4", "7pi/3", "11pi/6","7pi/4", "5pi/3")

$h_val  = $h_vals[$h_idx]
$h_neg  = $h_neg_arr[$h_idx]
$h_abs  = $h_abs_arr[$h_idx]
$h_frac = $h_frac_arr[$h_idx]
$h_ans  = $h_ans_arr[$h_idx]
$x0_pi  = $x0_pi_arr[$h_idx]
$x1_pi  = $x1_pi_arr[$h_idx]
$x2_pi  = $x2_pi_arr[$h_idx]
$x3_pi  = $x3_pi_arr[$h_idx]
$x4_pi  = $x4_pi_arr[$h_idx]

/* ---------- 3. Anchor y-values ---------- */
$y0 = $d
$y1 = $a + $d
$y2 = $d
$y3 = 0 - $a + $d
$y4 = $d

$max_val = $amp + $d
$min_val = 0 - $amp + $d

/* ---------- 4. Equation Display ---------- */
if ($a == 1)      { $a_eq = "" }
elseif ($a == -1) { $a_eq = "-" }
else              { $a_eq = $a }

if ($h_neg == 0 && $h_val != 0) { $inner_eq = "(x - " . $h_frac . ")" }
elseif ($h_neg == 1)             { $inner_eq = "(x + " . $h_frac . ")" }
else                             { $inner_eq = "x" }

if ($d > 0) { $k_eq = " + " . $d }
else        { $k_eq = " - " . $d_abs }

$eq_display = "`y = " . $a_eq . "sin(" . $inner_eq . ")" . $k_eq . "`"

/* ---------- 5. Pre-compute Conditional Strings ---------- */
if ($refl == -1) {
  $y1_role = "minimum"
  $y3_role = "maximum"
  $refl_note = "a is negative &rarr; reflected. The curve drops to the minimum (" . $y1 . ") at anchor 2 before rising to the maximum (" . $y3 . ") at anchor 4."
} else {
  $y1_role = "maximum"
  $y3_role = "minimum"
  $refl_note = "a is positive &rarr; not reflected. The curve rises to the maximum (" . $y1 . ") at anchor 2."
}

if ($h_val == 0) {
  $phase_note = "h = 0: no phase shift."
} elseif ($h_neg == 0) {
  $phase_note = "h = `" . $h_abs . "`: shifted right. All anchor x-values are pi-fraction + `" . $h_abs . "`."
} else {
  $phase_note = "h = &minus;`" . $h_abs . "`: shifted left. All anchor x-values are pi-fraction &minus; `" . $h_abs . "`."
}

/* ---------- 6. Function String for Solution Graph ---------- */
if ($h_val == 0) { $arg_str = "x" }
elseif ($h_val > 0) { $arg_str = "x-" . $h_val }
else { $h_abs_val = abs($h_val); $arg_str = "x+" . $h_abs_val }

if ($d >= 0) { $func_str = $a . "*sin(" . $arg_str . ")+" . $d }
else         { $func_str = $a . "*sin(" . $arg_str . ")" . $d }

$x0f = $h_val
$x1f = $h_val + 1.5708
$x2f = $h_val + 3.1416
$x3f = $h_val + 4.7124
$x4f = $h_val + 6.2832

$yextent  = $amp + $d_abs + 1.5
$ymin_win = 0 - $yextent
$ymax_win = $yextent

/* ---------- 7. Solution Graph ---------- */
$trans_plt = showplot($func_str . ",blue,-1.6,8.4,,,2.5", -1.6, 8.6, $ymin_win, $ymax_win, "1", "1", 420, 270)
$mid_plt   = showplot($d . ",red,-1.6,8.6,,,1.5,dash",    -1.6, 8.6, $ymin_win, $ymax_win, "1", "1", 420, 270)
$d0        = showplot("dot," . $x0f . "," . $y0 . ",closed,green",  -1.6, 8.6, $ymin_win, $ymax_win, "1", "1", 420, 270)
$d1        = showplot("dot," . $x1f . "," . $y1 . ",closed,purple", -1.6, 8.6, $ymin_win, $ymax_win, "1", "1", 420, 270)
$d2        = showplot("dot," . $x2f . "," . $y2 . ",closed,green",  -1.6, 8.6, $ymin_win, $ymax_win, "1", "1", 420, 270)
$d3        = showplot("dot," . $x3f . "," . $y3 . ",closed,red",    -1.6, 8.6, $ymin_win, $ymax_win, "1", "1", 420, 270)
$d4        = showplot("dot," . $x4f . "," . $y4 . ",closed,green",  -1.6, 8.6, $ymin_win, $ymax_win, "1", "1", 420, 270)
$solgraph = mergeplots($trans_plt, $mid_plt, $d0, $d1, $d2, $d3, $d4)
$solgraph = addlabel($solgraph, 8.4, $d + 0.30,      "midline: y=" . $d,  "red",    "right")
$solgraph = addlabel($solgraph, $x0f + 0.10, $y0 + 0.35, "(" . $x0_pi . "," . $y0 . ")", "green",  "left")
$solgraph = addlabel($solgraph, $x1f + 0.10, $y1 + 0.35, "(" . $x1_pi . "," . $y1 . ")", "purple", "left")
$solgraph = addlabel($solgraph, $x2f + 0.10, $y2 + 0.35, "(" . $x2_pi . "," . $y2 . ")", "green",  "left")
$solgraph = addlabel($solgraph, $x3f + 0.10, $y3 - 0.45, "(" . $x3_pi . "," . $y3 . ")", "red",    "left")
$solgraph = addlabel($solgraph, $x4f + 0.10, $y4 + 0.35, "(" . $x4_pi . "," . $y4 . ")", "green",  "left")

/* ---------- 8. Answer Setup ---------- */
$anstypes = array("number","number","number","number","number","number","number","number")

$answer[0]    = $y1
$ansprompt[0] = "Anchor 2 y-value = "

$answer[1]    = $y3
$ansprompt[1] = "Anchor 4 y-value = "

$answer[2]    = $max_val
$ansprompt[2] = "Maximum value = "

$answer[3]    = $min_val
$ansprompt[3] = "Minimum value = "

$answer[4]    = $d
$ansprompt[4] = "Midline: y = "

$answer[5]    = $a
$ansprompt[5] = "a = "

$answer[6]    = $d
$ansprompt[6] = "k = "

$answer[7]    = $amp
$ansprompt[7] = "Amplitude |a| = "

/* ---------- 9. CSS ---------- */
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
    .rubric-container details[open] .rubric-content { max-height:4000px; opacity:1; padding:0.75em; }
    .highlight-box { margin-top:10px; padding:0.7em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 6px 6px 0; }
    .warn-box { margin:6px 0; padding:0.5em 0.9em; background:#fff3e0; border-left:4px solid #FF9800; border-radius:0 6px 6px 0; font-size:0.93em; }
    .anchor-table { border-collapse:collapse; width:100%; font-family:Arial; font-size:small; margin:8px 0; }
    .anchor-table th { background:#e8f0fe; color:#1865f2; border:1px solid #c5d3f0; padding:8px 12px; text-align:center; }
    .anchor-table td { border:1px solid #dee1e3; padding:8px 12px; text-align:center; }
    .blank-cell { background:#fffde7; font-weight:bold; color:#e65100; }
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

/* ---------- 10. Solution Guide ---------- */
$solutionguide = $css_block . '
<div class="rubric-container">
  <details>
    <summary>
      <span class="arrow-closed">&#9656;</span><span class="arrow-open">&#9662;</span>
      Full Solution
    </summary>
    <div class="rubric-content">

      <p><b>Equation:</b> ' . $eq_display . ' &nbsp;: &nbsp; a = ' . $a . ', h = ' . $h_ans . ', k = ' . $d . '</p>
      <p>' . $phase_note . '<br>' . $refl_note . '</p>

      <p style="margin-top:10px;"><b>Completed anchor table:</b></p>
      <table class="anchor-table">
        <tr>
          <th>Anchor</th>
          <th>Transformed x</th>
          <th>Parent y</th>
          <th>Transformed y = ' . $a . '(parent y) + (' . $d . ')</th>
          <th>Role</th>
        </tr>
        <tr style="background:#f0fff4; font-weight:bold;">
          <td>1</td><td>`' . $x0_pi . '`</td><td>0</td>
          <td style="color:#27ae60;">' . $y0 . '</td>
          <td style="color:#27ae60;">Start / midline</td>
        </tr>
        <tr style="background:#f5f0ff; font-weight:bold;">
          <td>2</td><td>`' . $x1_pi . '`</td><td>1</td>
          <td style="color:#7b2fbe;">' . $y1 . ' &larr; ' . $a . '(1)+' . $d . '</td>
          <td style="color:#7b2fbe;">' . $y1_role . '</td>
        </tr>
        <tr style="background:#f0fff4; font-weight:bold;">
          <td>3</td><td>`' . $x2_pi . '`</td><td>0</td>
          <td style="color:#27ae60;">' . $y2 . '</td>
          <td style="color:#27ae60;">Middle / midline</td>
        </tr>
        <tr style="background:#fff5f5; font-weight:bold;">
          <td>4</td><td>`' . $x3_pi . '`</td><td>&minus;1</td>
          <td style="color:#c0392b;">' . $y3 . ' &larr; ' . $a . '(&minus;1)+' . $d . '</td>
          <td style="color:#c0392b;">' . $y3_role . '</td>
        </tr>
        <tr style="background:#f0fff4; font-weight:bold;">
          <td>5</td><td>`' . $x4_pi . '`</td><td>0</td>
          <td style="color:#27ae60;">' . $y4 . '</td>
          <td style="color:#27ae60;">End / midline</td>
        </tr>
      </table>

      <p style="margin-top:12px;"><b>Completed graph:</b></p>
      <div style="text-align:center; margin:10px 0;">' . $solgraph . '</div>

      <div class="highlight-box">
        Max = k + |a| = ' . $d . ' + ' . $amp . ' = <b>' . $max_val . '</b> &nbsp;|&nbsp;
        Min = k &minus; |a| = ' . $d . ' &minus; ' . $amp . ' = <b>' . $min_val . '</b> &nbsp;|&nbsp;
        Midline: <b>y = ' . $d . '</b>
      </div>
    </div>
  </details>
</div>'

/* ---------- 11. Question Text ---------- */
$questiontext = '
<div style="font-family:Arial; font-size:medium; line-height:1.6;">

  <p>The table below shows the five anchor points for the transformed sine function:</p>
  <p style="text-align:center; font-size:1.15em; font-weight:bold; background:#f0f4ff; padding:10px; border-radius:8px; border:2px solid #c5d3f0;">' . $eq_display . '</p>

  <p style="margin-top:12px;"><b>Known parameters:</b> &nbsp; a = ' . $a . ' &nbsp;|&nbsp; h = `' . $h_ans . '` &nbsp;|&nbsp; k = ' . $d . '</p>
  <p style="font-size:small; color:#555;">Recall: Transformed y = a &times; (parent y) + k</p>

  <table class="anchor-table">
    <tr>
      <th>Anchor</th>
      <th>Transformed x</th>
      <th>Parent y = sin(parent x)</th>
      <th>Transformed y = ' . $a . '(parent y) + (' . $d . ')</th>
    </tr>
    <tr style="background:#f0fff4;">
      <td><b>1</b></td>
      <td>`' . $x0_pi . '`</td>
      <td>0</td>
      <td style="color:#27ae60; font-weight:bold;">' . $y0 . '</td>
    </tr>
    <tr>
      <td><b>2</b></td>
      <td>`' . $x1_pi . '`</td>
      <td>1</td>
      <td class="blank-cell">&#x2753; Find in part (a)</td>
    </tr>
    <tr style="background:#f0fff4;">
      <td><b>3</b></td>
      <td>`' . $x2_pi . '`</td>
      <td>0</td>
      <td style="color:#27ae60; font-weight:bold;">' . $y2 . '</td>
    </tr>
    <tr>
      <td><b>4</b></td>
      <td>`' . $x3_pi . '`</td>
      <td>&minus;1</td>
      <td class="blank-cell">&#x2753; Find in part (b)</td>
    </tr>
    <tr style="background:#f0fff4;">
      <td><b>5</b></td>
      <td>`' . $x4_pi . '`</td>
      <td>0</td>
      <td style="color:#27ae60; font-weight:bold;">' . $y4 . '</td>
    </tr>
  </table>

</div>'

// === QUESTION TEXT ===

$questiontext

<p style="font-family:Arial; font-size:medium;"><b>(a)</b> Complete Anchor 2: compute y = ' . $a . '(1) + (' . $d . ').<br>
$answerbox[0]</p>

<p style="font-family:Arial; font-size:medium;"><b>(b)</b> Complete Anchor 4: compute y = ' . $a . '(&minus;1) + (' . $d . ').<br>
$answerbox[1]</p>

<p style="font-family:Arial; font-size:medium;"><b>(c)</b> What is the <b>maximum value</b> of the function? (Max = k + |a|)<br>
$answerbox[2]</p>

<p style="font-family:Arial; font-size:medium;"><b>(d)</b> What is the <b>minimum value</b>? (Min = k &minus; |a|)<br>
$answerbox[3]</p>

<p style="font-family:Arial; font-size:medium;"><b>(e)</b> What is the <b>midline</b> equation? (y = k)<br>
Midline: y = $answerbox[4]</p>

<p style="font-family:Arial; font-size:medium;"><b>(f)</b> What is the value of <b>a</b> in this equation? (Include sign: negative if reflected.)<br>
$answerbox[5]</p>

<p style="font-family:Arial; font-size:medium;"><b>(g)</b> What is the value of <b>k</b>?<br>
$answerbox[6]</p>

<p style="font-family:Arial; font-size:medium;"><b>(h)</b> What is the <b>amplitude</b> |a|? (Always positive.)<br>
$answerbox[7]</p>


// === ANSWER ===

$solutionguide
