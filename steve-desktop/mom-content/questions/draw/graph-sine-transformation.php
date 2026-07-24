// === Graph Transformed Sine Function - Graph y = a*sin(x-h) + k using shifted anchor points; covers amplitude, phase shift, and vertical shift with period = 2pi ===
// === SET QUESTION TYPE TO: draw ===

// === COMMON CONTROL ===

loadlibrary("stats")

/* ---------- 1. Randomize ---------- */
$amp   = rand(2, 3)
$refl  = randfrom("1,-1")
$a     = $refl * $amp
$h_idx = rand(0, 6)
$d     = randfrom("-2,-1,1,2")
$d_abs = abs($d)

/* ---------- 2. h Parallel Arrays ---------- */
$h_vals     = array(0,       0.5236,  0.7854,  1.0472,  -0.5236, -0.7854, -1.0472)
$h_rn_arr   = array(0,       1,       1,       1,       1,       1,       1)
$h_rd_arr   = array(1,       6,       4,       3,       6,       4,       3)
$h_neg_arr  = array(0,       0,       0,       0,       1,       1,       1)
$h_ans_arr  = array("0",     "pi/6",  "pi/4",  "pi/3",  "-pi/6", "-pi/4", "-pi/3")
$h_abs_arr  = array("0",     "pi/6",  "pi/4",  "pi/3",  "pi/6",  "pi/4",  "pi/3")
$h_adec_arr = array("0",     "0.52",  "0.79",  "1.05",  "0.52",  "0.79",  "1.05")

// Pre-computed decimal x-values for anchor table (avoids floating-point rounding)
$x0_d_arr = array("0",     "0.52",  "0.79",  "1.05",  "-0.52", "-0.79", "-1.05")
$x1_d_arr = array("1.57",  "2.09",  "2.36",  "2.62",  "1.05",  "0.79",  "0.52")
$x2_d_arr = array("3.14",  "3.67",  "3.93",  "4.19",  "2.62",  "2.36",  "2.09")
$x3_d_arr = array("4.71",  "5.24",  "5.50",  "5.76",  "4.19",  "3.93",  "3.67")
$x4_d_arr = array("6.28",  "6.81",  "7.07",  "7.33",  "5.76",  "5.50",  "5.24")

$h_val  = $h_vals[$h_idx]
$h_rn   = $h_rn_arr[$h_idx]
$h_rd   = $h_rd_arr[$h_idx]
$h_neg  = $h_neg_arr[$h_idx]
$h_ans  = $h_ans_arr[$h_idx]
$h_abs  = $h_abs_arr[$h_idx]
$h_adec = $h_adec_arr[$h_idx]
$x0_d   = $x0_d_arr[$h_idx]
$x1_d   = $x1_d_arr[$h_idx]
$x2_d   = $x2_d_arr[$h_idx]
$x3_d   = $x3_d_arr[$h_idx]
$x4_d   = $x4_d_arr[$h_idx]

/* ---------- 3. Compute Float Anchor x-values (for showplot and $answers) ---------- */
$x0 = $h_val
$x1 = $h_val + 1.5708
$x2 = $h_val + 3.1416
$x3 = $h_val + 4.7124
$x4 = $h_val + 6.2832

/* ---------- 4. Compute Anchor y-values ---------- */
$y0 = $d
$y1 = $a + $d
$y2 = $d
$y3 = 0 - $a + $d
$y4 = $d

/* ---------- 5. Build Function Strings ---------- */
// Safe argument string (avoids double negatives for negative h)
if ($h_val == 0) {
  $arg_str = "x"
} elseif ($h_val > 0) {
  $arg_str = "x-" . $h_val
} else {
  $h_abs_val = abs($h_val)
  $arg_str = "x+" . $h_abs_val
}

if ($d >= 0) { $func_str = $a . "*sin(" . $arg_str . ")+" . $d }
else         { $func_str = $a . "*sin(" . $arg_str . ")" . $d }

/* ---------- 6. Equation Display String ---------- */
if ($a == 1)      { $a_eq = "" }
elseif ($a == -1) { $a_eq = "-" }
else              { $a_eq = $a }

if ($h_rn == 0)       { $inner_eq = "x" }
elseif ($h_neg == 0)  { $inner_eq = "(x - " . $h_abs . ")" }
else                  { $inner_eq = "(x + " . $h_abs . ")" }

if ($d > 0) { $k_eq = " + " . $d }
else        { $k_eq = " - " . $d_abs }

$eq_display = "`y = " . $a_eq . "sin(" . $inner_eq . ")" . $k_eq . "`"

/* ---------- 7. Pre-compute All Conditional Display Strings ---------- */
// Reflection
if ($refl == -1) {
  $y1_role   = "minimum"
  $y3_role   = "maximum"
  $refl_note = "a = " . $a . " is <b>negative</b>: reflected over the midline. The curve moves <b>downward first</b> from the midline at x = h, reaching the <b>minimum (" . $y1 . ")</b> at x = h + &pi;/2."
  $start_dir = "downward"
} else {
  $y1_role   = "maximum"
  $y3_role   = "minimum"
  $refl_note = "a = " . $a . " is <b>positive</b>: no reflection. The curve moves <b>upward first</b> from the midline at x = h, reaching the <b>maximum (" . $y1 . ")</b> at x = h + &pi;/2."
  $start_dir = "upward"
}

// Phase shift
if ($h_rn == 0) {
  $phase_note    = "h = 0: <b>no phase shift</b>. The cycle starts at x = 0, same as the parent sine."
  $phase_brief   = "no phase shift"
  $h_label       = "0"
} elseif ($h_neg == 0) {
  $phase_note    = "h = `" . $h_abs . "` &asymp; " . $h_adec . ": cycle shifted <b>right</b>. The five anchor points all move " . $h_adec . " units to the right compared to y = a&middot;sin(x) + k."
  $phase_brief   = "right " . $h_adec . " (&asymp; `" . $h_abs . "`)"
  $h_label       = "+" . $h_abs
} else {
  $phase_note    = "h = &minus;`" . $h_abs . "` &asymp; &minus;" . $h_adec . ": cycle shifted <b>left</b>. The five anchor points all move " . $h_adec . " units to the left compared to y = a&middot;sin(x) + k."
  $phase_brief   = "left " . $h_adec . " (&asymp; `" . $h_abs . "`)"
  $h_label       = "&minus;" . $h_abs
}

// Vertical shift
if ($d > 0) {
  $vshift_note = "k = " . $d . ": midline rises to <b>y = " . $d . "</b>. Every anchor y-value increases by " . $d . "."
} else {
  $vshift_note = "k = " . $d . ": midline drops to <b>y = " . $d . "</b>. Every anchor y-value decreases by " . $d_abs . "."
}

/* ---------- 8. Window and Max/Min ---------- */
$yextent  = $amp + $d_abs + 1.5
$ymin_win = 0 - $yextent
$ymax_win = $yextent
$max_val  = $amp + $d
$min_val  = 0 - $amp + $d

/* ---------- 9. Solution Graph ---------- */
$parent_plt = showplot("sin(x),gray,-1.6,8.2,,,1.5,dash",             -1.6, 8.4, $ymin_win, $ymax_win, "1", "1", 440, 290)
$trans_plt  = showplot($func_str . ",blue," . $x0 . "," . $x4 . ",,,2.5", -1.6, 8.4, $ymin_win, $ymax_win, "1", "1", 440, 290)
$mid_plt    = showplot($d . ",red,-1.6,8.4,,,1.5,dash",                -1.6, 8.4, $ymin_win, $ymax_win, "1", "1", 440, 290)
$maxl_plt   = showplot($max_val . ",green,-1.6,8.4,,,1,dash",          -1.6, 8.4, $ymin_win, $ymax_win, "1", "1", 440, 290)
$minl_plt   = showplot($min_val . ",orange,-1.6,8.4,,,1,dash",         -1.6, 8.4, $ymin_win, $ymax_win, "1", "1", 440, 290)
$dot0       = showplot("dot," . $x0 . "," . $y0 . ",closed,green",    -1.6, 8.4, $ymin_win, $ymax_win, "1", "1", 440, 290)
$dot1       = showplot("dot," . $x1 . "," . $y1 . ",closed,purple",   -1.6, 8.4, $ymin_win, $ymax_win, "1", "1", 440, 290)
$dot2       = showplot("dot," . $x2 . "," . $y2 . ",closed,green",    -1.6, 8.4, $ymin_win, $ymax_win, "1", "1", 440, 290)
$dot3       = showplot("dot," . $x3 . "," . $y3 . ",closed,red",      -1.6, 8.4, $ymin_win, $ymax_win, "1", "1", 440, 290)
$dot4       = showplot("dot," . $x4 . "," . $y4 . ",closed,green",    -1.6, 8.4, $ymin_win, $ymax_win, "1", "1", 440, 290)
$solgraph = mergeplots($parent_plt, $trans_plt, $mid_plt, $maxl_plt, $minl_plt, $dot0, $dot1, $dot2, $dot3, $dot4)
$solgraph = addlabel($solgraph, 8.2, 0.20,           "y = sin(x)",          "gray",   "right")
$solgraph = addlabel($solgraph, 8.2, $d + 0.28,      "midline: y = " . $d,  "red",    "right")
$solgraph = addlabel($solgraph, 8.2, $max_val + 0.25,"max = " . $max_val,   "green",  "right")
$solgraph = addlabel($solgraph, 8.2, $min_val - 0.35,"min = " . $min_val,   "orange", "right")

// Anchor point labels (offset to avoid overlap)
$solgraph = addlabel($solgraph, $x0 + 0.10, $y0 + 0.30, "(" . $x0_d . ", " . $y0 . ")",  "green",  "left")
$solgraph = addlabel($solgraph, $x1 + 0.10, $y1 + 0.30, "(" . $x1_d . ", " . $y1 . ")",  "purple", "left")
$solgraph = addlabel($solgraph, $x2 + 0.10, $y2 + 0.30, "(" . $x2_d . ", " . $y2 . ")",  "green",  "left")
$solgraph = addlabel($solgraph, $x3 + 0.10, $y3 - 0.40, "(" . $x3_d . ", " . $y3 . ")",  "red",    "left")
$solgraph = addlabel($solgraph, $x4 + 0.10, $y4 + 0.30, "(" . $x4_d . ", " . $y4 . ")",  "green",  "left")

/* ---------- 10. Draw Canvas Settings ---------- */
// xscl = pi/2 (1.5708) so tick marks align with anchor x-values for h = 0
// Background: array of drawable strings = midline (dashed) + 5 anchor dots shown behind the student's drawing
// snap x to pi/12 (= 0.2618) so all possible h offsets (0, +/- pi/6, +/- pi/4, +/- pi/3) land on grid; y snaps to integers
$grid         = "-1.6,8.4," . $ymin_win . "," . $ymax_win . ",1.5708,1,440,290"
$background   = array(
  $d . ",red,-1.6,8.4,,,1.5,dash",
  "dot," . $x0 . "," . $y0 . ",closed,gray",
  "dot," . $x1 . "," . $y1 . ",closed,gray",
  "dot," . $x2 . "," . $y2 . ",closed,gray",
  "dot," . $x3 . "," . $y3 . ",closed,gray",
  "dot," . $x4 . "," . $y4 . ",closed,gray"
)
$answers      = $func_str . "," . $x0 . "," . $x4
$answerformat = "twopoint,trig"
$snaptogrid   = "0.2618:1"

/* ---------- 11. CSS ---------- */
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
    .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
    .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
    .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; user-select:text; }
    .row-colored { background:#fff9ea; }
    .col-header { width:21%; border-top-left-radius:8px; }
    .col-check { border-top-right-radius:8px; }
    .col-cat-bot { border-bottom-left-radius:8px; }
    .col-check-bot { border-bottom-right-radius:8px; }
    .highlight-box { margin-top:10px; padding:0.7em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 6px 6px 0; }
    .warn-box { margin:6px 0; padding:0.5em 0.9em; background:#fff3e0; border-left:4px solid #FF9800; border-radius:0 6px 6px 0; font-size:0.93em; }
    .info-box { margin:6px 0; padding:0.5em 0.9em; background:#e3f2fd; border-left:4px solid #1976d2; border-radius:0 6px 6px 0; font-size:0.93em; }
    .anchor-table { border-collapse:collapse; width:100%; font-family:Arial; font-size:small; margin:8px 0; }
    .anchor-table th { background:#e8f0fe; color:#1865f2; border:1px solid #c5d3f0; padding:7px 12px; text-align:center; }
    .anchor-table td { border:1px solid #dee1e3; padding:7px 12px; text-align:center; }
    .param-table { border-collapse:collapse; width:100%; font-family:Arial; font-size:small; margin:8px 0; }
    .param-table th { background:#f2f2f2; border:1px solid #ccc; padding:7px 12px; text-align:center; }
    .param-table td { border:1px solid #dee1e3; padding:7px 12px; }
    .param-table tr:nth-child(even) { background:#fafafa; }
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

/* ---------- 12. Solution Guide ---------- */
$solutionguide = $css_block . '
<div class="rubric-container">
  <details>
    <summary>
      <span class="arrow-closed">&#9656;</span><span class="arrow-open">&#9662;</span>
      Step-by-Step Graphing Solution
    </summary>
    <div class="rubric-content">

      <div class="info-box">
        <b>The key insight for graphing any y = a&middot;sin(x &minus; h) + k (b = 1):</b>
        Start with the five parent anchor points at x = 0, &pi;/2, &pi;, 3&pi;/2, 2&pi;.
        Then apply three adjustments: <b>shift every x by h</b> (phase shift),
        <b>scale every y by a</b> (amplitude + reflection),
        and <b>add k to every y</b> (vertical shift).
      </div>

      <table class="rubric-table" style="margin-top:12px;">
        <tbody>
          <tr>
            <th class="col-header">Step</th>
            <th class="col-check">Work</th>
          </tr>

          <tr class="row-colored">
            <td style="text-align:center;"><b>Step 1</b><br>Identify Parameters</td>
            <td>
              For ' . $eq_display . ':<br><br>
              <table class="param-table">
                <tr>
                  <th>Parameter</th>
                  <th>Value</th>
                  <th>Effect on graph</th>
                </tr>
                <tr>
                  <td><b>a</b></td>
                  <td>' . $a . '</td>
                  <td>Amplitude = |a| = <b>' . $amp . '</b>. ' . ($refl == -1 ? "Negative &rarr; reflected over midline." : "Positive &rarr; no reflection.") . '</td>
                </tr>
                <tr>
                  <td><b>Period</b></td>
                  <td>2&pi;</td>
                  <td>b = 1, so period = 2&pi; &divide; 1 = <b>2&pi;</b> (unchanged).</td>
                </tr>
                <tr>
                  <td><b>h</b></td>
                  <td>' . $h_label . '</td>
                  <td>' . $phase_brief . '. All x-coordinates of the cycle shift by h.</td>
                </tr>
                <tr>
                  <td><b>k</b></td>
                  <td>' . $d . '</td>
                  <td>' . $vshift_note . '</td>
                </tr>
              </table>
            </td>
          </tr>

          <tr>
            <td style="text-align:center;"><b>Step 2</b><br>Locate the Midline</td>
            <td>
              Draw a <b>horizontal dashed line at y = ' . $d . '</b> before touching the curve.<br><br>
              The transformed curve oscillates <b>' . $amp . ' units above and below y = ' . $d . '</b>:
              Maximum = ' . $d . ' + ' . $amp . ' = <b>' . $max_val . '</b> &nbsp;|&nbsp;
              Minimum = ' . $d . ' &minus; ' . $amp . ' = <b>' . $min_val . '</b>
            </td>
          </tr>

          <tr class="row-colored">
            <td style="text-align:center;"><b>Step 3</b><br>Compute the 5 Anchor Points</td>
            <td>
              The parent sine has five key points: (0, 0), (&pi;/2, 1), (&pi;, 0), (3&pi;/2, &minus;1), (2&pi;, 0).<br>
              To transform: <b>add h to every x</b>, apply <b>a &times; parent-y + k</b> to every y.<br><br>
              <table class="anchor-table">
                <tr>
                  <th>Parent x</th>
                  <th>Shifted x = parent + h</th>
                  <th>x &asymp;</th>
                  <th>Parent y</th>
                  <th>Transformed y = a(&middot;) + k</th>
                  <th>Type</th>
                </tr>
                <tr style="background:#f0fff4; font-weight:bold;">
                  <td>0</td>
                  <td>0 + h = ' . $h_label . '</td>
                  <td>' . $x0_d . '</td>
                  <td>0</td>
                  <td>' . $a . '(0) + ' . $d . ' = <b>' . $y0 . '</b></td>
                  <td style="color:#27ae60;">Start (midline)</td>
                </tr>
                <tr style="background:#f5f0ff; font-weight:bold;">
                  <td>&pi;/2</td>
                  <td>&pi;/2 + h</td>
                  <td>' . $x1_d . '</td>
                  <td>1</td>
                  <td>' . $a . '(1) + ' . $d . ' = <b>' . $y1 . '</b></td>
                  <td style="color:#7b2fbe;">' . $y1_role . '</td>
                </tr>
                <tr style="background:#f0fff4; font-weight:bold;">
                  <td>&pi;</td>
                  <td>&pi; + h</td>
                  <td>' . $x2_d . '</td>
                  <td>0</td>
                  <td>' . $a . '(0) + ' . $d . ' = <b>' . $y2 . '</b></td>
                  <td style="color:#27ae60;">Middle (midline)</td>
                </tr>
                <tr style="background:#fff5f5; font-weight:bold;">
                  <td>3&pi;/2</td>
                  <td>3&pi;/2 + h</td>
                  <td>' . $x3_d . '</td>
                  <td>&minus;1</td>
                  <td>' . $a . '(&minus;1) + ' . $d . ' = <b>' . $y3 . '</b></td>
                  <td style="color:#c0392b;">' . $y3_role . '</td>
                </tr>
                <tr style="background:#f0fff4; font-weight:bold;">
                  <td>2&pi;</td>
                  <td>2&pi; + h</td>
                  <td>' . $x4_d . '</td>
                  <td>0</td>
                  <td>' . $a . '(0) + ' . $d . ' = <b>' . $y4 . '</b></td>
                  <td style="color:#27ae60;">End (midline)</td>
                </tr>
              </table>
              <br>' . $refl_note . '
            </td>
          </tr>

          <tr>
            <td style="text-align:center;"><b>Step 4</b><br>Draw &amp; Check</td>
            <td>
              Plot all five anchor dots. Draw a smooth S-shaped curve through them, starting <b>' . $start_dir . '</b> from the midline at x &asymp; ' . $x0_d . '.<br><br>
              <div style="text-align:center; margin:10px 0;">' . $solgraph . '</div>
              <div class="highlight-box">
                <b>Summary for ' . $eq_display . ':</b><br>
                Amplitude: ' . $amp . ' &nbsp;|&nbsp;
                Period: 2&pi; &nbsp;|&nbsp;
                Phase shift: h = ' . $h_label . ' &nbsp;|&nbsp;
                Midline: y = ' . $d . '<br>
                Max: ' . $max_val . ' at x &asymp; ' . $x1_d . ' &nbsp;|&nbsp;
                Min: ' . $min_val . ' at x &asymp; ' . $x3_d . '
              </div>
            </td>
          </tr>

          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Common Errors</b></td>
            <td class="col-check-bot">
              <div class="warn-box">
                <b>Shifting x in the wrong direction:</b> In y = a&middot;sin(x &minus; h) + k, the minus sign means a <i>positive</i> h shifts the curve to the <b>right</b>. When the equation shows (x + ' . $h_abs . '), rewrite it as (x &minus; (&minus;' . $h_abs . ')) to confirm h is negative (shift left).
              </div>
              <div class="warn-box">
                <b>Forgetting to shift the anchor x-values:</b> All five anchor points must use the <i>shifted</i> x-values (parent x + h). A common mistake is to plot points at x = 0, &pi;/2, &pi;... instead of starting at x = h.
              </div>
              <div class="warn-box">
                <b>Measuring amplitude from zero instead of the midline:</b> The curve travels from y = ' . $min_val . ' to y = ' . $max_val . ', not from y = &minus;' . $amp . ' to y = ' . $amp . '. Always add k to every y-value.
              </div>
              <div class="warn-box">
                <b>Forgetting to reflect:</b> If a = ' . $a . ' is negative, the curve goes <b>down</b> first from the midline. Drawing it going up first (like a positive sine) is one of the most common graphing errors.
              </div>
            </td>
          </tr>

        </tbody>
      </table>
    </div>
  </details>
</div>'

/* ---------- 13. Question Text ---------- */
$questiontext = '
<div style="font-family:Arial; font-size:medium; line-height:1.6;">

  <p>Graph one full period of the transformed sine function:</p>
  <p style="text-align:center; font-size:1.2em; font-weight:bold; background:#f0f4ff; padding:12px; border-radius:8px; border:2px solid #c5d3f0;">' . $eq_display . '</p>

  <table style="width:100%; border-collapse:separate; border-spacing:14px 0; margin:12px 0;">
    <tr>
      <td style="vertical-align:top; width:46%;">
        <p style="font-weight:bold; margin:0 0 6px 0; color:#1865f2;">&#x2460; Parameters</p>
        <table class="param-table">
          <tr><th>Feature</th><th>Value</th></tr>
          <tr><td>Amplitude</td><td><b>' . $amp . '</b></td></tr>
          <tr><td>Period</td><td><b>2&pi; &asymp; 6.28</b></td></tr>
          <tr><td>Phase shift h</td><td><b>' . $h_label . '</b> &asymp; ' . ($h_neg == 1 ? "&minus;" : "") . $h_adec . '</td></tr>
          <tr><td>Midline</td><td><b>y = ' . $d . '</b></td></tr>
          <tr><td>Maximum y</td><td><b>' . $max_val . '</b></td></tr>
          <tr><td>Minimum y</td><td><b>' . $min_val . '</b></td></tr>
        </table>
      </td>
      <td style="vertical-align:top;">
        <p style="font-weight:bold; margin:0 0 6px 0; color:#1865f2;">&#x2461; Five Anchor Points (plot these first)</p>
        <table class="anchor-table">
          <tr>
            <th>Role</th>
            <th>Formula x</th>
            <th>x &asymp;</th>
            <th>y</th>
          </tr>
          <tr style="background:#f0fff4; font-weight:bold; color:#27ae60;">
            <td>Start (midline)</td>
            <td>0 + h</td>
            <td>' . $x0_d . '</td>
            <td>' . $y0 . '</td>
          </tr>
          <tr style="background:#f5f0ff; font-weight:bold; color:#7b2fbe;">
            <td>' . $y1_role . '</td>
            <td>&pi;/2 + h</td>
            <td>' . $x1_d . '</td>
            <td>' . $y1 . '</td>
          </tr>
          <tr style="background:#f0fff4; font-weight:bold; color:#27ae60;">
            <td>Middle (midline)</td>
            <td>&pi; + h</td>
            <td>' . $x2_d . '</td>
            <td>' . $y2 . '</td>
          </tr>
          <tr style="background:#fff5f5; font-weight:bold; color:#c0392b;">
            <td>' . $y3_role . '</td>
            <td>3&pi;/2 + h</td>
            <td>' . $x3_d . '</td>
            <td>' . $y3 . '</td>
          </tr>
          <tr style="background:#f0fff4; font-weight:bold; color:#27ae60;">
            <td>End (midline)</td>
            <td>2&pi; + h</td>
            <td>' . $x4_d . '</td>
            <td>' . $y4 . '</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <div style="margin:8px 0; padding:0.6em 1em; background:#fef9e7; border-left:4px solid #f1c40f; border-radius:0 6px 6px 0; font-size:0.93em;">
    <b>&#x1F4CC; X-axis key (decimal &harr; radians):</b><br>
    <table style="border-collapse:collapse; margin-top:6px; font-family:Arial; font-size:0.95em;">
      <tr style="background:#fffbe6;">
        <th style="border:1px solid #e0c060; padding:4px 10px;">Decimal</th>
        <td style="border:1px solid #e0c060; padding:4px 10px;">1.05</td>
        <td style="border:1px solid #e0c060; padding:4px 10px;">1.57</td>
        <td style="border:1px solid #e0c060; padding:4px 10px;">2.09</td>
        <td style="border:1px solid #e0c060; padding:4px 10px;">3.14</td>
        <td style="border:1px solid #e0c060; padding:4px 10px;">4.71</td>
        <td style="border:1px solid #e0c060; padding:4px 10px;">6.28</td>
        <td style="border:1px solid #e0c060; padding:4px 10px;">7.85</td>
      </tr>
      <tr>
        <th style="border:1px solid #e0c060; padding:4px 10px;">Radians</th>
        <td style="border:1px solid #e0c060; padding:4px 10px;">&pi;/3</td>
        <td style="border:1px solid #e0c060; padding:4px 10px;">&pi;/2</td>
        <td style="border:1px solid #e0c060; padding:4px 10px;">2&pi;/3</td>
        <td style="border:1px solid #e0c060; padding:4px 10px;">&pi;</td>
        <td style="border:1px solid #e0c060; padding:4px 10px;">3&pi;/2</td>
        <td style="border:1px solid #e0c060; padding:4px 10px;">2&pi;</td>
        <td style="border:1px solid #e0c060; padding:4px 10px;">5&pi;/2</td>
      </tr>
    </table>
    <div style="margin-top:4px; font-size:0.9em; color:#555;">Each tick on the x-axis is &pi;/2 &asymp; 1.57 apart. The y-axis is labeled at every 1 unit.</div>
  </div>
  <div style="margin:6px 0 10px 0; padding:0.6em 1em; background:#fce4ec; border-left:4px solid #e91e63; border-radius:0 6px 6px 0; font-size:0.93em;">
    <b>&#x26A0; Phase shift reminder:</b> ' . $phase_note . '
    The entire set of anchor x-values shifts -- <b>not just the starting point</b>.
  </div>

  <p style="margin-top:10px;"><b>Directions:</b> Select the <b>sine curve tool</b>, then click two anchor points to define your curve. Place your clicks at the ' . $y1_role . ' and any midline crossing for best accuracy. The curve should:</p>
  <ul style="margin:4px 0 10px 20px;">
    <li>Begin on the midline (y = ' . $d . ') at x &asymp; ' . $x0_d . '</li>
    <li>Reach its <b>' . $y1_role . '</b> of <b>' . $y1 . '</b> at x &asymp; ' . $x1_d . '</li>
    <li>Return to the midline at x &asymp; ' . $x2_d . '</li>
    <li>Reach its <b>' . $y3_role . '</b> of <b>' . $y3 . '</b> at x &asymp; ' . $x3_d . '</li>
    <li>Complete the cycle back on the midline at x &asymp; ' . $x4_d . '</li>
  </ul>

</div>'

// === QUESTION TEXT ===

$questiontext

$answerbox


// === ANSWER ===

$solutionguide
