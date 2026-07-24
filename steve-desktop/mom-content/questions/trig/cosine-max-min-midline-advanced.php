// === COSINE MAX/MIN/MIDLINE (ADVANCED) - All four transformations active. From the equation, find maximum, minimum, and midline. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

/* ---------- 1. Advanced (all 4 of {a, b, h, k} active) ---------- */
$a_on = 1
$b_on = 1
$h_on = 1
$k_on = 1

/* ---------- 2. Randomize each parameter ---------- */
$a_pool = array(-3, -2,  2,  3)
$a_idx  = rand(0, 3)
if ($a_on == 1) { $a = $a_pool[$a_idx] } else { $a = 1 }
$amp = abs($a)

$b_pool      = array(2, 3, 4)
$bdisp_pool  = array("2", "3", "4")
$b_idx       = rand(0, 2)
if ($b_on == 1) { $b = $b_pool[$b_idx]; $bdisp = $bdisp_pool[$b_idx] }
else            { $b = 1; $bdisp = "1" }

$h_deg_pool = array( 30,  45,  60,  90, -30, -45, -60, -90)
$h_idx      = rand(0, 7)
if ($h_on == 1) { $h_deg = $h_deg_pool[$h_idx] } else { $h_deg = 0 }

$k_pool = array(-3, -2, -1, 1, 2, 3)
$k_idx  = rand(0, 5)
if ($k_on == 1) { $k = $k_pool[$k_idx] } else { $k = 0 }

/* ---------- 3. Compute answers ---------- */
$max_val = $amp + $k
$min_val = 0 - $amp + $k
$mid_val = $k

/* ---------- 4. Build equation display ---------- */
if ($a == 1)       { $a_str = "" }
elseif ($a == -1)  { $a_str = "-" }
else               { $a_str = $a }

if ($b_on == 1 && $h_on == 1) {
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

if ($k > 0)      { $k_str = " + " . $k }
elseif ($k < 0)  { $k_str = " - " . abs($k) }
else             { $k_str = "" }

$eq_show = "y = " . $a_str . "cos(" . $inner . ")" . $k_str

/* ---------- 5. Answers ---------- */
$anstypes        = array("number", "number", "number")
$answer[0]       = $max_val
$answer[1]       = $min_val
$answer[2]       = $mid_val
$answerformat[0] = "integer"
$answerformat[1] = "integer"
$answerformat[2] = "integer"

/* ---------- 6. Notes ---------- */
if ($a < 0) {
  $a_note = "a = " . $a . " (negative). The amplitude is |a| = " . $amp . ". The reflection swaps where the max and min appear on the curve, but the max and min <i>values</i> are still " . $max_val . " and " . $min_val . "."
} else {
  $a_note = "a = " . $a . " (positive). The amplitude is a = " . $amp . "."
}
if ($b == 1) { $b_note = "b = 1, so the period is 360&deg;. <b>b does not affect max, min, or midline.</b>" }
else         { $b_note = "b = " . $bdisp . ", so the period is 360&deg; &divide; " . $bdisp . " = " . (360/$b) . "&deg;. <b>b changes the period only, not the max/min/midline.</b>" }
if ($h_deg == 0) { $h_note = "h = 0 (no phase shift). <b>h does not affect max, min, or midline.</b>" }
else             { $h_note = "h = " . $h_deg . "&deg; shifts the curve horizontally. <b>h does not affect max, min, or midline.</b>" }
if ($k == 0)     { $k_note = "k = 0, so the midline is y = 0." }
elseif ($k > 0)  { $k_note = "k = " . $k . ", so the midline rises to y = " . $k . "." }
else             { $k_note = "k = " . $k . ", so the midline drops to y = " . $k . "." }

/* ---------- 7. CSS ---------- */
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
</style>
<script>
document.addEventListener("DOMContentLoaded", function() {
  var details = document.querySelectorAll(".rubric-container details");
  details.forEach(function(det) {
    var content = det.querySelector(".rubric-content");
    det.addEventListener("toggle", function() {
      if (det.open) { content.style.maxHeight = content.scrollHeight + "px"; content.style.opacity = "1"; }
      else { content.style.maxHeight = content.scrollHeight + "px"; content.offsetHeight; content.style.maxHeight = "0"; content.style.opacity = "0"; }
    });
    content.addEventListener("transitionend", function() { if (!det.open) content.style.maxHeight = null; });
  });
});
</script>'

/* ---------- 8. Solution guide ---------- */
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
          <tr><th class="col-header">Step</th><th class="col-check">Work</th></tr>
          <tr>
            <td style="text-align:center;"><b>Given</b></td>
            <td>For <b>' . $eq_show . '</b>, the only parameters that affect max/min/midline are <b>a</b> (amplitude) and <b>k</b> (vertical shift).<br><br>
              ' . $a_note . '<br>' . $b_note . '<br>' . $h_note . '<br>' . $k_note . '
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Step 1</b><br>Maximum</td>
            <td>Maximum = <b>k + |a|</b> = ' . $k . ' + ' . $amp . ' = <b>' . $max_val . '</b></td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Step 2</b><br>Minimum</td>
            <td>Minimum = <b>k &minus; |a|</b> = ' . $k . ' &minus; ' . $amp . ' = <b>' . $min_val . '</b></td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Step 3</b><br>Midline</td>
            <td class="col-check-bot">Midline: <b>y = k</b> = <b>y = ' . $k . '</b>
              <div style="margin-top:10px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Summary:</b> Max = ' . $max_val . ', Min = ' . $min_val . ', Midline y = ' . $k . '
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.4; color:#21242c; max-width:688px;"><p style="margin:0 0 4px 0;">For the function</p><p style="text-align:center; font-size:1.2em; font-weight:bold; background:#f0f4ff; padding:12px; border-radius:8px; border:1px solid #c5d3f0; margin:0 0 8px 0;">{$eq_show}</p><p style="margin:0 0 8px 0;">find the maximum value, minimum value, and midline.</p></div><div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04); max-width:688px;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part (a)</span>What is the <b>maximum value</b>? <span style="margin-left:8px;">$answerbox[0]</span></div><div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04); max-width:688px;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part (b)</span>What is the <b>minimum value</b>? <span style="margin-left:8px;">$answerbox[1]</span></div><div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04); max-width:688px;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part (c)</span>What is the <b>midline</b>? Enter just the y-value (the midline is y = your answer). <span style="margin-left:8px;">$answerbox[2]</span></div>

///

$solutionguide
