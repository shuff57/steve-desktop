// === NAME - DESCRIPTION: Interpret r Value - Direction, strength, and units of correlation ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices", "choices")

// Scenario randomization
$ctx_x = array("weekly study hours", "daily screen time (hours)", "outdoor temperature (&deg;F)", "annual household income (thousands of dollars)", "minutes of weekly exercise")
$ctx_y = array("GPA", "hours of sleep per night", "ice cream sales (dollars)", "monthly savings (dollars)", "resting heart rate (bpm)")
$picked_ctx = jointrandfrom($ctx_x, $ctx_y)
$xname = $picked_ctx[0]
$yname = $picked_ctx[1]

// Parallel r values with their direction and strength categories
$rs   = array(-0.92, -0.78, -0.55, -0.18, 0.18, 0.55, 0.78, 0.92)
$dirs = array(1, 1, 1, 1, 0, 0, 0, 0)        // 0 = positive, 1 = negative
$strs = array(2, 2, 1, 0, 0, 1, 2, 2)        // 0 = weak, 1 = moderate, 2 = strong
$picked_r = jointrandfrom($rs, $dirs, $strs)
$r = $picked_r[0]
$abs_r = abs($r)

$choices[0] = array("Positive", "Negative")
$displayformat[0] = "select"
$noshuffle[0] = "all"
$answer[0] = $picked_r[1]

$choices[1] = array("Weak", "Moderate", "Strong")
$displayformat[1] = "select"
$noshuffle[1] = "all"
$answer[1] = $picked_r[2]

$choices[2] = array("True", "False")
$displayformat[2] = "select"
$noshuffle[2] = "all"
$answer[2] = 1   // r is unitless, so "r has units" is False

// Helper scalars for the solution guide
if ($answer[0] == 0) {
  $direction_text = "positive"
} else {
  $direction_text = "negative"
}

if ($answer[1] == 0) {
  $strength_text = "weak"
} elseif ($answer[1] == 1) {
  $strength_text = "moderate"
} else {
  $strength_text = "strong"
}

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><b>Direction.</b> The sign of `r` gives the direction. Here `r = ' . $r . '`, so the relationship is <b>' . $direction_text . '</b>.</p>
      <p><b>Strength.</b> The size of `|r|` gives strength. A common rule of thumb is `|r| < 0.3` weak, `0.3 \le |r| < 0.7` moderate, and `|r| \ge 0.7` strong. Here `|r| = ' . $abs_r . '`, so the relationship is <b>' . $strength_text . '</b>.</p>
      <p><b>Units.</b> Correlation `r` is a unitless number between `-1` and `1`. It does not carry the units of `x` or `y`, so the statement "r has units" is <b>False</b>.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Direction: ' . $direction_text . '. Strength: ' . $strength_text . '. Units: none.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A study reports that the correlation between <b>{ $xname }</b> and <b>{ $yname }</b> is `r = $r`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the <b>direction</b> of the linear relationship? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> How would you describe the <b>strength</b>? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> True or False: `r` has units (the same units as `y`). $answerbox[2]
  </div>
</div>


// === ANSWER ===

$solutionguide
