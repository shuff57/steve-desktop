// === NAME - DESCRIPTION: Estimate r from a Scatterplot - Pick the correlation that matches a verbal description ===
// === SET QUESTION TYPE TO: multiple_choice ===

// === COMMON CONTROL ===

// Randomize the variable pairing
$ctx_x = array("hours of weekly exercise", "annual income (thousands of dollars)", "outdoor temperature (&deg;F)", "miles driven per week", "daily time on social media (hours)")
$ctx_y = array("resting heart rate (bpm)", "monthly savings (dollars)", "ice cream sales (dollars)", "weekly fuel cost (dollars)", "self-reported happiness (1-10)")
$picked_ctx = jointrandfrom($ctx_x, $ctx_y)
$xname = $picked_ctx[0]
$yname = $picked_ctx[1]

// Five scenarios — description + correct index into $questions
$descriptions = array(
  "the points are tightly bunched along a line that slopes upward from the lower-left to the upper-right",
  "the points are tightly bunched along a line that slopes downward from the upper-left to the lower-right",
  "the points show a clear upward drift, but with a lot of scatter around the trend",
  "the points show a clear downward drift, but with a lot of scatter around the trend",
  "the points form an aimless cloud with no apparent linear trend"
)
$correct_idxs = array(0, 1, 2, 3, 4)
$picked = jointrandfrom($descriptions, $correct_idxs)
$desc = $picked[0]
$answer = $picked[1]

$questions = array(
  "`r = 0.92`",
  "`r = -0.92`",
  "`r = 0.40`",
  "`r = -0.40`",
  "`r = 0.05`"
)
$noshuffle = "all"

// Helper text for solution
if ($answer == 0) {
  $why_text = "A tight upward pattern means a strong positive correlation, so `r` should be close to `+1`. Of the choices, `0.92` is the only strong positive value."
} elseif ($answer == 1) {
  $why_text = "A tight downward pattern means a strong negative correlation, so `r` should be close to `-1`. Of the choices, `-0.92` is the only strong negative value."
} elseif ($answer == 2) {
  $why_text = "A loose upward drift is a moderate positive correlation. `r = 0.40` is positive but well below `+1`, which fits a noisy upward cloud."
} elseif ($answer == 3) {
  $why_text = "A loose downward drift is a moderate negative correlation. `r = -0.40` is negative but well above `-1`, which fits a noisy downward cloud."
} else {
  $why_text = "An aimless cloud with no trend has correlation near zero. `r = 0.05` is the only choice close to `0`."
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
      <p><b>Two things to read off a scatterplot:</b></p>
      <ul>
        <li><b>Direction</b> — sloping up means positive `r`, sloping down means negative `r`, no slope means `r approx 0`.</li>
        <li><b>Strength</b> — tight points around a line means `|r|` near `1`, loose cloud means `|r|` near `0`.</li>
      </ul>
      <p>' . $why_text . '</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Best estimate: ' . $questions[$answer] . '.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A scatterplot of <b>{ $yname }</b> versus <b>{ $xname }</b> is described as follows:</p>
    <p style="margin:0; padding:10px 14px; background:#f0f4ff; border-left:4px solid #1865f2; border-radius:0 8px 8px 0;">{ $desc }.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Which value is the most reasonable estimate of the correlation `r`?</p>
  </div>
</div>


// === ANSWER ===

$solutionguide
