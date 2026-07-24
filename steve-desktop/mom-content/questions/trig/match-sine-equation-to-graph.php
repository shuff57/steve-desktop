// === Match Sine Equation to Graph - Given y = a*sin(x-h) + k, identify the correct graph from four options showing different transformation errors ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

/* ---------- 1. Randomize Correct Equation ---------- */
$amp   = rand(2, 3)
$refl  = randfrom("1,-1")
$a     = $refl * $amp
$h_idx = rand(0, 6)
$d     = randfrom("-2,-1,1,2")
$d_abs = abs($d)

/* ---------- 2. h Parallel Arrays ---------- */
$h_vals    = array(0,       0.5236,  0.7854,  1.0472,  -0.5236, -0.7854, -1.0472)
$h_neg_arr = array(0,       0,       0,       0,       1,       1,       1)
$h_abs_arr = array("0",     "pi/6",  "pi/4",  "pi/3",  "pi/6",  "pi/4",  "pi/3")
$h_frac_arr= array("0",     "pi/6",  "pi/4",  "pi/3",  "pi/6",  "pi/4",  "pi/3")
$h_ans_arr = array("0",     "pi/6",  "pi/4",  "pi/3",  "-pi/6", "-pi/4", "-pi/3")

$h_val  = $h_vals[$h_idx]
$h_neg  = $h_neg_arr[$h_idx]
$h_abs  = $h_abs_arr[$h_idx]
$h_frac = $h_frac_arr[$h_idx]
$h_ans  = $h_ans_arr[$h_idx]

/* ---------- 3. Compute Correct Function String ---------- */
if ($h_val == 0) {
  $arg_correct = "x"
} elseif ($h_val > 0) {
  $arg_correct = "x-" . $h_val
} else {
  $h_abs_val = abs($h_val)
  $arg_correct = "x+" . $h_abs_val
}

if ($d >= 0) { $func_correct = $a . "*sin(" . $arg_correct . ")+" . $d }
else         { $func_correct = $a . "*sin(" . $arg_correct . ")" . $d }

/* ---------- 4. Three Wrong Variants ---------- */
// Wrong A: reflection removed (use +amp instead of $a)
$wrong_a_val = $amp
if ($d >= 0) { $func_wrong_a = $wrong_a_val . "*sin(" . $arg_correct . ")+" . $d }
else         { $func_wrong_a = $wrong_a_val . "*sin(" . $arg_correct . ")" . $d }

// Wrong B: phase shift reversed (flip sign of h)
$h_flip = 0 - $h_val
if ($h_flip == 0) {
  $arg_flip = "x"
} elseif ($h_flip > 0) {
  $arg_flip = "x-" . $h_flip
} else {
  $h_flip_abs = abs($h_flip)
  $arg_flip = "x+" . $h_flip_abs
}
if ($d >= 0) { $func_wrong_b = $a . "*sin(" . $arg_flip . ")+" . $d }
else         { $func_wrong_b = $a . "*sin(" . $arg_flip . ")" . $d }

// Wrong C: vertical shift negated (use -d)
$d_flip = 0 - $d
if ($d_flip >= 0) { $func_wrong_c = $a . "*sin(" . $arg_correct . ")+" . $d_flip }
else              { $func_wrong_c = $a . "*sin(" . $arg_correct . ")" . $d_flip }

/* ---------- 5. Window ---------- */
$yextent  = $amp + $d_abs + 1.5
$ymin_win = 0 - $yextent
$ymax_win = $yextent

/* ---------- 6. Build Four Graph Panels ---------- */
// Helper: each panel is 250x200 with the correct window
$pw = 280
$ph = 200

$g_correct = showplot($func_correct . ",blue,-1.6,8.4,,,2.5", -1.6, 8.4, $ymin_win, $ymax_win, "1", "1", $pw, $ph)
$m_correct = showplot($d . ",red,-1.6,8.4,,,1.5,dash",         -1.6, 8.4, $ymin_win, $ymax_win, "1", "1", $pw, $ph)
$panel_A   = mergeplots($g_correct, $m_correct)
$panel_A   = addlabel($panel_A, 7.5, $d + 0.25, "y=" . $d, "red", "right")

$g_wrong_a = showplot($func_wrong_a . ",blue,-1.6,8.4,,,2.5", -1.6, 8.4, $ymin_win, $ymax_win, "1", "1", $pw, $ph)
$m_wrong_a = showplot($d . ",red,-1.6,8.4,,,1.5,dash",         -1.6, 8.4, $ymin_win, $ymax_win, "1", "1", $pw, $ph)
$panel_B   = mergeplots($g_wrong_a, $m_wrong_a)
$panel_B   = addlabel($panel_B, 7.5, $d + 0.25, "y=" . $d, "red", "right")

$g_wrong_b = showplot($func_wrong_b . ",blue,-1.6,8.4,,,2.5", -1.6, 8.4, $ymin_win, $ymax_win, "1", "1", $pw, $ph)
$m_wrong_b = showplot($d . ",red,-1.6,8.4,,,1.5,dash",         -1.6, 8.4, $ymin_win, $ymax_win, "1", "1", $pw, $ph)
$panel_C   = mergeplots($g_wrong_b, $m_wrong_b)
$panel_C   = addlabel($panel_C, 7.5, $d + 0.25, "y=" . $d, "red", "right")

$g_wrong_c = showplot($func_wrong_c . ",blue,-1.6,8.4,,,2.5", -1.6, 8.4, $ymin_win, $ymax_win, "1", "1", $pw, $ph)
$d_flip_label = 0 - $d
$m_wrong_c = showplot($d_flip_label . ",red,-1.6,8.4,,,1.5,dash", -1.6, 8.4, $ymin_win, $ymax_win, "1", "1", $pw, $ph)
$panel_D   = mergeplots($g_wrong_c, $m_wrong_c)
$panel_D   = addlabel($panel_D, 7.5, $d_flip_label + 0.25, "y=" . $d_flip_label, "red", "right")

/* ---------- 7. Pre-compute Conditional Equation and Explanation Strings ---------- */
if ($a == 1)      { $a_eq = "" }
elseif ($a == -1) { $a_eq = "-" }
else              { $a_eq = $a }

if ($h_neg == 0 && $h_val != 0) { $inner_eq = "(x - " . $h_frac . ")" }
elseif ($h_neg == 1)             { $inner_eq = "(x + " . $h_frac . ")" }
else                             { $inner_eq = "x" }

if ($d > 0) { $k_eq = " + " . $d }
else        { $k_eq = " - " . $d_abs }

$eq_display = "`y = " . $a_eq . "sin(" . $inner_eq . ")" . $k_eq . "`"

// Descriptions of each error
if ($refl == -1) {
  $exp_B = "Graph B uses a = +" . $amp . " (positive), so the curve goes <b>up first</b> from x = h. But the equation has a = " . $a . " (negative), meaning the curve must go <b>down first</b>. B has the reflection wrong."
} else {
  $exp_B = "Graph B uses a = &minus;" . $amp . " (negative), so the curve goes <b>down first</b>. But the equation has a = " . $a . " (positive), meaning the curve goes <b>up first</b>. B has the reflection wrong."
}

if ($h_val == 0) {
  $exp_C = "Graph C shifts the cycle, but h = 0 means there should be no horizontal shift. The cycle must start at x = 0."
} elseif ($h_neg == 0) {
  $exp_C = "Graph C shifts the cycle to the <b>left</b> by `" . $h_abs . "`, but h = +" . $h_ans . " means the shift is to the <b>right</b>. The minus sign in (x &minus; h) is part of the formula structure, not a signal to go left."
} else {
  $exp_C = "Graph C shifts the cycle to the <b>right</b> by `" . $h_abs . "`, but h = " . $h_ans . " (negative) means the shift is to the <b>left</b>. When the equation shows (x + " . $h_frac . "), h is negative."
}

if ($d > 0) {
  $exp_D = "Graph D places the midline at y = &minus;" . $d . " (below zero), but k = +" . $d . " means the midline must be at y = +" . $d . " (above zero). D has the vertical shift sign wrong."
} else {
  $exp_D = "Graph D places the midline at y = +" . $d_abs . " (above zero), but k = " . $d . " means the midline must be at y = " . $d . " (below zero). D has the vertical shift sign wrong."
}

/* ---------- 8. Answer Setup ---------- */
$anstypes = array("choices", "choices", "choices")

// Part a: which panel is correct
$questions[0] = array(
  "Graph A &mdash; " . $panel_A,
  "Graph B &mdash; " . $panel_B,
  "Graph C &mdash; " . $panel_C,
  "Graph D &mdash; " . $panel_D
)
$answer[0]    = 0
$noshuffle[0] = "all"

// Part b: which graph has wrong reflection
$questions[1] = array(
  "Graph B &mdash; it uses the wrong sign for a, flipping the curve in the wrong direction.",
  "Graph A &mdash; it has the correct reflection but the wrong amplitude.",
  "Graph C &mdash; the horizontal shift reversal also causes a reflected appearance.",
  "Graph D &mdash; a wrong vertical shift can look like a reflection if the midline is crossed."
)
$answer[1]    = 0
$noshuffle[1] = "all"

// Part c: what error does Graph D contain
$questions[2] = array(
  "The vertical shift (k) has the wrong sign. The midline is at y = " . $d_flip_label . " instead of y = " . $d . ".",
  "The amplitude is wrong. The curve travels too far from the midline.",
  "The phase shift direction is reversed, moving the cycle the wrong way.",
  "The reflection of a is missing; the curve should go in the opposite direction."
)
$answer[2]    = 0
$noshuffle[2] = "all"

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
    .info-box { margin:6px 0; padding:0.5em 0.9em; background:#e3f2fd; border-left:4px solid #1976d2; border-radius:0 6px 6px 0; font-size:0.93em; }
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
      Solution &amp; Error Analysis
    </summary>
    <div class="rubric-content">
      <p><b>Correct equation:</b> ' . $eq_display . ' &mdash; a = ' . $a . ', h = ' . $h_ans . ', k = ' . $d . '</p>

      <p><b>(a) Correct graph: A.</b><br>
      Graph A shows a = ' . $a . ' (correct reflection), midline at y = ' . $d . ' (correct vertical shift), and cycle starting at x = h = ' . $h_ans . ' (correct phase shift).</p>

      <p style="margin-top:10px;"><b>(b) Wrong reflection: Graph B.</b><br>' . $exp_B . '</p>

      <p style="margin-top:10px;"><b>(c) Graph D error: wrong vertical shift sign.</b><br>' . $exp_D . '</p>

      <p style="margin-top:10px;"><b>What Graph C gets wrong:</b><br>' . $exp_C . '</p>

      <div class="highlight-box">
        <b>Checklist when matching equation to graph:</b><br>
        1. Find the midline &mdash; it must be at y = k.<br>
        2. Check where the cycle crosses the midline going up or down &mdash; this reveals h and the sign of a.<br>
        3. Measure the amplitude from midline to peak &mdash; must equal |a|.<br>
        4. Verify the direction: positive a = up first; negative a = down first.
      </div>
    </div>
  </details>
</div>'

/* ---------- 11. Question Text ---------- */
$questiontext = '
<div style="font-family:Arial; font-size:medium; line-height:1.6;">
  <p>Four graphs are shown below. <b>Each graph has the same amplitude and period</b> but one or more parameters differ. Only one graph correctly represents:</p>
  <p style="text-align:center; font-size:1.2em; font-weight:bold; background:#f0f4ff; padding:12px; border-radius:8px; border:2px solid #c5d3f0;">' . $eq_display . '</p>
  <p>The <b>red dashed line</b> in each graph shows the midline of that graph.</p>
  <div style="margin:10px 0; padding:0.6em 1em; background:#fef9e7; border-left:4px solid #f1c40f; border-radius:0 6px 6px 0; font-size:0.93em;">
    <b>What to look for:</b> &nbsp;
    Midline position (y = k) &nbsp;|&nbsp;
    Direction of curve at x = h (up first or down first) &nbsp;|&nbsp;
    Where the cycle starts (phase shift h)
  </div>
</div>'

// === QUESTION TEXT ===

$questiontext

<p style="font-family:Arial; font-size:medium;"><b>(a)</b> Which graph correctly represents ' . $eq_display . '?<br>
  $answerbox[0]</p>

<p style="font-family:Arial; font-size:medium;"><b>(b)</b> One graph has the <b>reflection of a wrong</b> -- the curve goes in the opposite vertical direction. Which graph is it, and why?<br>
  $answerbox[1]</p>

<p style="font-family:Arial; font-size:medium;"><b>(c)</b> Graph D has an error in the <b>vertical shift</b>. Which statement best describes the mistake?<br>
  $answerbox[2]</p>


// === ANSWER ===

$solutionguide
