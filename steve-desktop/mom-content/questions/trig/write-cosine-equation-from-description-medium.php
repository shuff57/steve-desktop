// === WRITE COSINE EQUATION FROM DESCRIPTION (MEDIUM) - Two transformations described. Student supplies a, b, h, k. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

/* ---------- 1. Medium (exactly 2 of {a, b, h, k} active) ---------- */
$_combo = rand(0, 5)
$a_on = 0
$b_on = 0
$h_on = 0
$k_on = 0
if ($_combo == 0) { $a_on = 1; $b_on = 1 }
if ($_combo == 1) { $a_on = 1; $h_on = 1 }
if ($_combo == 2) { $a_on = 1; $k_on = 1 }
if ($_combo == 3) { $b_on = 1; $h_on = 1 }
if ($_combo == 4) { $b_on = 1; $k_on = 1 }
if ($_combo == 5) { $h_on = 1; $k_on = 1 }

/* ---------- 2. Randomize each parameter ---------- */
$a_pool = array(-3, -2,  2,  3)
$a_idx  = rand(0, 3)
if ($a_on == 1) { $a = $a_pool[$a_idx] } else { $a = 1 }
$amp = abs($a)

$b_pool      = array(2, 3, 4)
$bdisp_pool  = array("2", "3", "4")
$pdeg_pool   = array(180, 120, 90)
$prad_show_pool = array("&pi;", "(2&pi;)/3", "&pi;/2")
$b_idx       = rand(0, 2)
if ($b_on == 1) {
  $b = $b_pool[$b_idx]; $bdisp = $bdisp_pool[$b_idx]
  $p_deg = $pdeg_pool[$b_idx]; $p_rad_show = $prad_show_pool[$b_idx]
} else {
  $b = 1; $bdisp = "1"; $p_deg = 360; $p_rad_show = "2&pi;"
}

$h_deg_pool  = array( 30,  45,  60,  90, -30, -45, -60, -90)
$h_rn_pool   = array(  1,   1,   1,   1,  -1,  -1,  -1,  -1)
$h_rd_pool   = array(  6,   4,   3,   2,   6,   4,   3,   2)
$h_idx       = rand(0, 7)
if ($h_on == 1) {
  $h_deg = $h_deg_pool[$h_idx]; $h_rn = $h_rn_pool[$h_idx]; $h_rd = $h_rd_pool[$h_idx]
} else {
  $h_deg = 0; $h_rn = 0; $h_rd = 1
}

$k_pool = array(-3, -2, -1, 1, 2, 3)
$k_idx  = rand(0, 5)
if ($k_on == 1) { $k = $k_pool[$k_idx] } else { $k = 0 }

/* ---------- 3. Build phase shift display ---------- */
if ($h_rn == 0)      { $h_rad_show = "0";              $h_dir = "" }
elseif ($h_rn > 0)   { $h_rad_show = "&pi;/" . $h_rd;  $h_dir = "to the right" }
else                 { $h_rad_show = "-&pi;/" . $h_rd; $h_dir = "to the left" }

/* ---------- 4. Build verbal description bullets ---------- */
if ($a_on == 1) {
  if ($a < 0) { $bullet_a = "Amplitude of <b>" . $amp . "</b> with a <b>reflection</b> over the midline" }
  else        { $bullet_a = "Amplitude of <b>" . $amp . "</b>" }
} else {
  $bullet_a = "Amplitude of <b>1</b>"
}
if ($b_on == 1) {
  $bullet_b = "Period of <b>" . $p_deg . "&deg;</b> (which is " . $p_rad_show . " radians)"
} else {
  $bullet_b = "Period of <b>360&deg;</b> (which is 2&pi; radians)"
}
if ($h_on == 1) {
  if ($h_deg > 0) { $bullet_h = "Phase shift of <b>" . abs($h_deg) . "&deg;</b> to the <b>right</b>" }
  else            { $bullet_h = "Phase shift of <b>" . abs($h_deg) . "&deg;</b> to the <b>left</b>" }
} else {
  $bullet_h = "<b>No</b> phase shift"
}
if ($k_on == 1) {
  if ($k > 0) { $bullet_k = "Vertical shift of <b>" . $k . " unit(s) up</b>" }
  else        { $bullet_k = "Vertical shift of <b>" . abs($k) . " unit(s) down</b>" }
} else {
  $bullet_k = "<b>No</b> vertical shift"
}

/* ---------- 5. Answers ---------- */
$anstypes        = array("number", "number", "number", "number")
$answer[0]       = $a
$answer[1]       = $b
$answer[2]       = $h_deg
$answer[3]       = $k
$answerformat[0] = "integer"
$answerformat[1] = "integer"
$answerformat[2] = "integer"
$answerformat[3] = "integer"

/* ---------- 6. CSS ---------- */
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

/* ---------- 7. Solution guide ---------- */
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
            <td style="text-align:center;"><b>Form</b></td>
            <td>The general form is <b>y = a cos(b(x &minus; h)) + k</b>. Match each verbal property to the right parameter.</td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>a</b></td>
            <td>The amplitude is |a|. ' . ($a < 0 ? "A reflection means a is <b>negative</b>." : "No reflection mentioned, so a is <b>positive</b>.") . ' Therefore <b>a = ' . $a . '</b>.</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>b</b></td>
            <td>Period = 360&deg; &divide; b, so b = 360&deg; &divide; period = 360&deg; &divide; ' . $p_deg . '&deg; = <b>' . $bdisp . '</b>.</td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>h</b></td>
            <td>' . ($h_deg == 0 ? "No phase shift means h = <b>0</b>." : "A phase shift " . $h_dir . " of " . abs($h_deg) . "&deg; means h = <b>" . $h_deg . "&deg;</b> (" . ($h_deg > 0 ? "positive for right shift" : "negative for left shift") . ").") . '</td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>k</b></td>
            <td class="col-check-bot">' . ($k == 0 ? "No vertical shift means k = <b>0</b>." : ($k > 0 ? "Up " . $k . " unit(s) means k = <b>" . $k . "</b>." : "Down " . abs($k) . " unit(s) means k = <b>" . $k . "</b>.")) . '
              <div style="margin-top:10px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Answer:</b> a = ' . $a . ', b = ' . $bdisp . ', h = ' . $h_deg . '&deg;, k = ' . $k . '<br>
                <b>Equation:</b> y = ' . ($a == 1 ? "" : ($a == -1 ? "-" : $a)) . 'cos(' . ($b == 1 && $h_deg == 0 ? "x" : ($b == 1 ? ($h_deg > 0 ? "x - " . $h_deg . "&deg;" : "x + " . abs($h_deg) . "&deg;") : ($h_deg == 0 ? $bdisp . "x" : $bdisp . "(x " . ($h_deg > 0 ? "- " . $h_deg : "+ " . abs($h_deg)) . "&deg;)"))) . ')' . ($k == 0 ? "" : ($k > 0 ? " + " . $k : " - " . abs($k))) . '
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.4; color:#21242c; max-width:688px;"><p style="margin:0 0 4px 0;">A transformed cosine function has the following properties:</p><ul style="margin:4px 0 8px 24px; padding:0;"><li>{$bullet_a}</li><li>{$bullet_b}</li><li>{$bullet_h}</li><li>{$bullet_k}</li></ul><p style="margin:0 0 8px 0;">The general form is <b>y = a cos(b(x &minus; h)) + k</b>. Find the values of a, b, h, and k. Enter h in <b>degrees</b> (use a negative number for a left shift); enter 0 for any parameter that is not transformed.</p></div><div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04); max-width:688px;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part (a)</span>Find <b>a</b>: <span style="margin-left:8px;">$answerbox[0]</span></div><div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04); max-width:688px;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part (b)</span>Find <b>b</b>: <span style="margin-left:8px;">$answerbox[1]</span></div><div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04); max-width:688px;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part (c)</span>Find <b>h</b> in degrees: <span style="margin-left:8px;">$answerbox[2]</span></div><div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04); max-width:688px;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part (d)</span>Find <b>k</b>: <span style="margin-left:8px;">$answerbox[3]</span></div>

///

$solutionguide
