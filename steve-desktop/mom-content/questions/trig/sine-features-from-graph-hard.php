// === SINE FEATURES FROM GRAPH (HARD) - Three transformations on the graph; MC for amplitude, period (degrees), vertical shift. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

/* ---------- 1. Hard (exactly 3 of {a, b, h, k} active; one left out) ---------- */
$_combo = rand(0, 3)
$a_on = 1
$b_on = 1
$h_on = 1
$k_on = 1
if ($_combo == 0) { $a_on = 0 }
if ($_combo == 1) { $b_on = 0 }
if ($_combo == 2) { $h_on = 0 }
if ($_combo == 3) { $k_on = 0 }

/* ---------- 2. Randomize each parameter ---------- */
$a_pool = array(-3, -2,  2,  3)
$a_idx  = rand(0, 3)
if ($a_on == 1) { $a = $a_pool[$a_idx] } else { $a = 1 }
$amp = abs($a)

$b_pool      = array(2, 3, 4)
$b_idx       = rand(0, 2)
if ($b_on == 1) { $b = $b_pool[$b_idx] } else { $b = 1 }
$p_deg = 360 / $b

$h_deg_pool = array( 30,  45,  60,  90, -30, -45, -60, -90)
$h_idx      = rand(0, 7)
if ($h_on == 1) { $h_deg = $h_deg_pool[$h_idx] } else { $h_deg = 0 }

$k_pool = array(-3, -2, -1, 1, 2, 3)
$k_idx  = rand(0, 5)
if ($k_on == 1) { $k = $k_pool[$k_idx] } else { $k = 0 }

/* ---------- 3. Build function string for showplot (degree mode) ---------- */
// y = a * sin(b * (x - h) * pi/180) + k
$func_str = $a . "*sin(" . $b . "*(x-" . $h_deg . ")*pi/180)+" . $k

/* ---------- 4. Window and scale (degrees) ---------- */
$x_min   = $h_deg - 30
$x_max   = $h_deg + $p_deg + 30
$xscl    = $p_deg / 4
$yextent = $amp + abs($k) + 1.5
$y_min   = 0 - $yextent
$y_max   = $yextent

/* ---------- 5. Build the graph ---------- */
$mid_plt   = showplot($k . ",red," . $x_min . "," . $x_max . ",,,1.5,dash", $x_min, $x_max, $y_min, $y_max, $xscl, "1", 480, 280)
$curve_plt = showplot($func_str . ",blue," . $x_min . "," . $x_max . ",,,2.5",   $x_min, $x_max, $y_min, $y_max, $xscl, "1", 480, 280)
$graph     = mergeplots($mid_plt, $curve_plt)
$graph     = addlabel($graph, $x_max - 0.5, $k + 0.3, "midline y = " . $k, "red", "right")

/* ---------- 6. MC choice arrays ---------- */
// Part (a): amplitude  (correct = $amp)
$amp_choices = array($amp, $a, $amp + 1, $amp + $k)
$qa = array("" . $amp_choices[0], "" . $amp_choices[1], "" . $amp_choices[2], "" . $amp_choices[3])

// Part (b): period in degrees  (correct = $p_deg)
$pd_pool = array(360, 180, 120, 90, 720)
$distractors = array()
$d_count = 0
$j = 0
while ($d_count < 3 && $j < 5) {
  if ($pd_pool[$j] != $p_deg) {
    $distractors[$d_count] = $pd_pool[$j]
    $d_count = $d_count + 1
  }
  $j = $j + 1
}
$qb = array($p_deg . "&deg;", $distractors[0] . "&deg;", $distractors[1] . "&deg;", $distractors[2] . "&deg;")

// Part (c): vertical shift  (correct = $k)
$qc_d1 = 0 - $k
$qc_d2 = $k + $amp
$qc_d3 = $k - $amp
$qc = array("" . $k, "" . $qc_d1, "" . $qc_d2, "" . $qc_d3)

/* ---------- 7. Answers ---------- */
$anstypes        = array("choices", "choices", "choices")
$questions[0]    = $qa
$questions[1]    = $qb
$questions[2]    = $qc
$answer[0]       = 0
$answer[1]       = 0
$answer[2]       = 0
$displayformat[0] = "select"
$displayformat[1] = "select"
$displayformat[2] = "select"

/* ---------- 8. CSS ---------- */
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

/* ---------- 9. Solution guide ---------- */
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
          <tr><th class="col-header">Feature</th><th class="col-check">From the graph</th></tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Amplitude</b></td>
            <td>Distance from the midline to a peak (or trough). The midline is at y = ' . $k . '; the peak is at y = ' . ($amp + $k) . '. <b>Amplitude = ' . ($amp + $k) . ' &minus; ' . $k . ' = ' . $amp . '</b>.</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Period</b></td>
            <td>Horizontal length of one complete cycle. The graph completes one cycle every ' . $p_deg . '&deg;. <b>Period = ' . $p_deg . '&deg;</b>.</td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Vertical shift</b></td>
            <td class="col-check-bot">y-value of the midline (the dashed red line). <b>Vertical shift = ' . $k . '</b>.
              <div style="margin-top:10px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Summary:</b> amplitude = ' . $amp . ', period = ' . $p_deg . '&deg;, vertical shift = ' . $k . '
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.4; color:#21242c; max-width:688px;"><p style="margin:0 0 6px 0;">The graph below shows a transformed sine function. The <b style="color:#c0392b;">red dashed line</b> is the midline. Use the graph to identify each feature.</p><div style="text-align:center; margin:8px 0;">{$graph}</div></div><div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04); max-width:688px;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part (a)</span>What is the <b>amplitude</b>? <span style="margin-left:8px;">$answerbox[0]</span></div><div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04); max-width:688px;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part (b)</span>What is the <b>period</b>? <span style="margin-left:8px;">$answerbox[1]</span></div><div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04); max-width:688px;"><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part (c)</span>What is the <b>vertical shift</b>? <span style="margin-left:8px;">$answerbox[2]</span></div>

///

$solutionguide
