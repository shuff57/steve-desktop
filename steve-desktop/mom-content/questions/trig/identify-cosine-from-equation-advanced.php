// === IDENTIFY COSINE TRANSFORMATIONS (ADVANCED) - All four transformations active. Equation in degrees; ask for period and phase shift in degrees, then radian equivalents. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

/* ---------- 1. Advanced (all 4 of {a, b, h, k} active) ---------- */
$a_on = 1
$b_on = 1
$h_on = 1
$k_on = 1

/* ---------- 2. Randomize each parameter ---------- */
// a (amplitude with optional reflection)
$a_pool      = array(-3, -2,  2,  3)
$a_idx       = rand(0, 3)
if ($a_on == 1) { $a = $a_pool[$a_idx] } else { $a = 1 }
$amp = abs($a)

// b (period coefficient) - pool produces clean periods
$b_pool      = array(2, 3, 4)
$bdisp_pool  = array("2", "3", "4")
$pdeg_pool   = array(180, 120, 90)        // 360/b
$prad_n_pool = array(1,    2,   1)        // numerator of (n*pi)/d
$prad_d_pool = array(1,    3,   2)        // denominator
$b_idx       = rand(0, 2)
if ($b_on == 1) {
  $b      = $b_pool[$b_idx]
  $bdisp  = $bdisp_pool[$b_idx]
  $p_deg  = $pdeg_pool[$b_idx]
  $p_rn   = $prad_n_pool[$b_idx]
  $p_rd   = $prad_d_pool[$b_idx]
} else {
  $b      = 1
  $bdisp  = "1"
  $p_deg  = 360
  $p_rn   = 2
  $p_rd   = 1
}

// h (phase shift in degrees)
$h_deg_pool   = array( 30,    45,    60,    90,   -30,    -45,    -60,    -90)
$h_rn_pool    = array(  1,     1,     1,     1,    -1,     -1,     -1,     -1)
$h_rd_pool    = array(  6,     4,     3,     2,     6,      4,      3,      2)
$h_idx        = rand(0, 7)
if ($h_on == 1) {
  $h_deg = $h_deg_pool[$h_idx]
  $h_rn  = $h_rn_pool[$h_idx]
  $h_rd  = $h_rd_pool[$h_idx]
} else {
  $h_deg = 0
  $h_rn  = 0
  $h_rd  = 1
}

// k (vertical shift)
$k_pool = array(-3, -2, -1, 1, 2, 3)
$k_idx  = rand(0, 5)
if ($k_on == 1) { $k = $k_pool[$k_idx] } else { $k = 0 }

/* ---------- 3. Build the equation display string ---------- */
// "a" piece
if ($a == 1)       { $a_str = "" }
elseif ($a == -1)  { $a_str = "-" }
else               { $a_str = $a }

// inner argument piece
if ($b_on == 1 && $h_on == 1) {
  // factored form: b(x - h)
  if ($h_deg > 0) { $inner = $bdisp . "(x - " . $h_deg . "&deg;)" }
  else            { $inner = $bdisp . "(x + " . abs($h_deg) . "&deg;)" }
} elseif ($b_on == 1) {
  $inner = $bdisp . "x"
} elseif ($h_on == 1) {
  if ($h_deg > 0) { $inner = "x - " . $h_deg . "&deg;" }
  else            { $inner = "x + " . abs($h_deg) . "&deg;" }
} else {
  $inner = "x"
}

// k piece
if ($k > 0)      { $k_str = " + " . $k }
elseif ($k < 0)  { $k_str = " - " . abs($k) }
else             { $k_str = "" }

$eq_show = "y = " . $a_str . "cos(" . $inner . ")" . $k_str

/* ---------- 4. Build period (radians) answer + display ---------- */
if ($p_rd == 1) {
  if ($p_rn == 1) { $p_rad_ans = "pi";          $p_rad_show = "&pi;" }
  else            { $p_rad_ans = $p_rn . "pi";  $p_rad_show = $p_rn . "&pi;" }
} else {
  if ($p_rn == 1) { $p_rad_ans = "pi/" . $p_rd;                   $p_rad_show = "&pi;/" . $p_rd }
  else            { $p_rad_ans = "(" . $p_rn . "pi)/" . $p_rd;    $p_rad_show = $p_rn . "&pi;/" . $p_rd }
}

/* ---------- 5. Build phase shift (radians) answer + display ---------- */
if ($h_rn == 0) {
  $h_rad_ans  = "0"
  $h_rad_show = "0"
} elseif ($h_rn > 0) {
  $h_rad_ans  = "pi/" . $h_rd
  $h_rad_show = "&pi;/" . $h_rd
} else {
  $h_rad_ans  = "-pi/" . $h_rd
  $h_rad_show = "-&pi;/" . $h_rd
}

/* ---------- 6. Answers ---------- */
$anstypes        = array("number", "number",  "calculated", "number", "calculated", "number")
$answer[0]       = $amp
$answer[1]       = $p_deg
$answer[2]       = $p_rad_ans
$answer[3]       = $h_deg
$answer[4]       = $h_rad_ans
$answer[5]       = $k
$answerformat[0] = "integer"
$answerformat[1] = "integer"
$answerformat[2] = "nodecimal"
$answerformat[3] = "integer"
$answerformat[4] = "nodecimal"
$answerformat[5] = "integer"

/* ---------- 7. Reflection note ---------- */
if ($a < 0) {
  $a_note = "Note that a = " . $a . " is negative. Amplitude is always non-negative, so amplitude = |a| = " . $amp . ". The negative sign reflects the curve over the midline."
} else {
  $a_note = "Since a = " . $a . " is positive, the amplitude is simply a = " . $amp . "."
}

/* ---------- 8. Phase shift direction note ---------- */
if ($h_deg == 0) {
  $h_note = "h = 0, so there is <b>no phase shift</b>."
} elseif ($h_deg > 0) {
  $h_note = "h = " . $h_deg . "&deg; is positive, so the curve shifts <b>" . $h_deg . "&deg; to the right</b>. Watch the sign: the standard form uses <b>(x &minus; h)</b>, so (x &minus; " . $h_deg . "&deg;) means h = +" . $h_deg . "&deg;."
} else {
  $h_abs = abs($h_deg)
  $h_note = "The equation shows (x + " . $h_abs . "&deg;), which is the same as (x &minus; (&minus;" . $h_abs . "&deg;)). So h = &minus;" . $h_abs . "&deg; (negative), and the curve shifts <b>" . $h_abs . "&deg; to the left</b>."
}

/* ---------- 9. Period note ---------- */
if ($b == 1) {
  $b_note = "Since b = 1, the period is unchanged: 360&deg; = 2&pi;."
} else {
  $b_note = "With b = " . $bdisp . ", the period = 360&deg; &divide; " . $bdisp . " = <b>" . $p_deg . "&deg;</b>, which equals " . $p_rad_show . " radians."
}

/* ---------- 10. Vertical shift note ---------- */
if ($k == 0) {
  $k_note = "k = 0, so the midline stays at y = 0."
} elseif ($k > 0) {
  $k_note = "k = " . $k . ", so the midline shifts <b>up " . $k . " unit(s)</b> to y = " . $k . "."
} else {
  $k_note = "k = " . $k . ", so the midline shifts <b>down " . abs($k) . " unit(s)</b> to y = " . $k . "."
}

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
    .rubric-container details[open] .rubric-content { max-height:3000px; opacity:1; padding:0.75em; }
    .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
    .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
    .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; user-select:text; }
    .row-colored { background:#fff9ea; }
    .col-header { width:25%; border-top-left-radius:8px; }
    .col-check { border-top-right-radius:8px; }
    .col-cat-bot { border-bottom-left-radius:8px; }
    .col-check-bot { border-bottom-right-radius:8px; }
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

/* ---------- 12. Solution guide ---------- */
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
            <td>The general form is <b>y = a cos(b(x &minus; h)) + k</b>.<br><br>
              For <b>' . $eq_show . '</b>, match each piece:
              <table class="param-table" style="margin-top:8px;">
                <tr><th>Parameter</th><th>Value</th></tr>
                <tr><td>a</td><td>' . $a . '</td></tr>
                <tr><td>b</td><td>' . $bdisp . '</td></tr>
                <tr><td>h</td><td>' . $h_deg . '&deg; = ' . $h_rad_show . '</td></tr>
                <tr><td>k</td><td>' . $k . '</td></tr>
              </table>
            </td>
          </tr>

          <tr class="row-colored">
            <td style="text-align:center;"><b>Step 1</b><br>Amplitude</td>
            <td>Amplitude = |a| = |' . $a . '| = <b>' . $amp . '</b>.<br>' . $a_note . '</td>
          </tr>

          <tr>
            <td style="text-align:center;"><b>Step 2</b><br>Period</td>
            <td>Period = <b>360&deg; &divide; b</b> (or 2&pi; &divide; b in radians).<br>
              ' . $b_note . '<br><br>
              <b>In degrees: ' . $p_deg . '&deg;</b><br>
              <b>In radians: ' . $p_rad_show . '</b>
            </td>
          </tr>

          <tr class="row-colored">
            <td style="text-align:center;"><b>Step 3</b><br>Phase shift</td>
            <td>Phase shift = <b>h</b>, the value subtracted from x inside the parentheses.<br>
              ' . $h_note . '<br><br>
              <b>In degrees: ' . $h_deg . '&deg;</b><br>
              <b>In radians: ' . $h_rad_show . '</b>
            </td>
          </tr>

          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Step 4</b><br>Vertical shift</td>
            <td class="col-check-bot">Vertical shift = <b>k</b>, the constant added at the end.<br>
              ' . $k_note . '<br><br>
              <b>k = ' . $k . '</b>
              <div style="margin-top:10px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Summary for ' . $eq_show . ':</b><br>
                Amplitude: ' . $amp . ' &nbsp;|&nbsp;
                Period: ' . $p_deg . '&deg; (' . $p_rad_show . ') &nbsp;|&nbsp;
                Phase shift: ' . $h_deg . '&deg; (' . $h_rad_show . ') &nbsp;|&nbsp;
                Vertical shift: ' . $k . '
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.4; color:#21242c; max-width:688px;"><p style="margin:0 0 4px 0;">The general form of a transformed cosine function is</p><p style="text-align:center; font-size:1.05em; margin:0 0 8px 0;"><b>y = a cos(b(x &minus; h)) + k</b></p><p style="margin:0 0 4px 0;">For the function</p><p style="text-align:center; font-size:1.2em; font-weight:bold; background:#f0f4ff; padding:12px; border-radius:8px; border:1px solid #c5d3f0; margin:0 0 8px 0;">{$eq_show}</p><p style="margin:0 0 8px 0;">identify each transformation.</p></div><div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04); max-width:688px;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part (a)</span>What is the <b>amplitude</b>? <span style="margin-left:8px;">$answerbox[0]</span></div><div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04); max-width:688px;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part (b)</span>What is the <b>period in degrees</b>? <span style="margin-left:8px;">$answerbox[1]</span></div><div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04); max-width:688px;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part (c)</span>What is the <b>period in radians</b>? <span style="margin-left:8px;">$answerbox[2]</span></div><div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04); max-width:688px;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part (d)</span>What is the <b>phase shift in degrees</b>? <span style="margin-left:8px;">$answerbox[3]</span></div><div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04); max-width:688px;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part (e)</span>What is the <b>phase shift in radians</b>? <span style="margin-left:8px;">$answerbox[4]</span></div><div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04); max-width:688px;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part (f)</span>What is the <b>vertical shift</b>? <span style="margin-left:8px;">$answerbox[5]</span></div>

///

$solutionguide
