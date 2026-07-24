// === NAME - DESCRIPTION: Variable Type Identification - Classify a variable as qualitative or quantitative ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices")

// Scenario 0: Eye color — qualitative
// Scenario 1: T-shirt size (S/M/L/XL) — qualitative
// Scenario 2: Temperature in F — quantitative
// Scenario 3: Weight in lb — quantitative
// Scenario 4: Letter grade (A,B,C,D,F) — qualitative
// Scenario 5: Number of siblings — quantitative
// Scenario 6: Movie ratings 1-5 stars — qualitative
// Scenario 7: Time of day in hours since midnight — quantitative

$ctxs = array(
  "A pet shelter records the <b>eye color</b> of each cat (e.g., green, blue, yellow).",
  "A retailer records the <b>T-shirt size</b> sold (S, M, L, XL).",
  "A weather station records the <b>daily high temperature in degrees Fahrenheit</b>.",
  "A gym records each member's <b>weight in pounds</b>.",
  "A school records each student's <b>letter grade</b> (A, B, C, D, F) for a course.",
  "A demographer records the <b>number of siblings</b> each respondent has.",
  "A streaming service records each viewer's <b>movie rating</b> (1, 2, 3, 4, or 5 stars).",
  "A scheduler records the <b>time of day</b> (in hours since midnight) when each appointment starts."
)
$qualquant = array(0, 0, 1, 1, 0, 1, 0, 1) // 0 = qualitative, 1 = quantitative
$why_qq = array(
  "Eye color is a non-numeric category, so it is qualitative.",
  "T-shirt size is a label (S, M, L, XL) with no meaningful arithmetic, so it is qualitative.",
  "Temperature is a number, so it is quantitative.",
  "Weight is a number, so it is quantitative.",
  "Letter grades are non-numeric labels, so qualitative.",
  "Number of siblings is a count (a number), so quantitative.",
  "Star ratings are ordered labels with no fixed numerical distance between them, so qualitative.",
  "Time of day in hours is a numeric measurement, so quantitative."
)

$picked = jointrandfrom($ctxs, $qualquant, $why_qq)
$ctx       = $picked[0]
$answer[0] = $picked[1]
$wqq       = $picked[2]

$choices[0] = array("Qualitative (categorical)", "Quantitative (numerical)")
$noshuffle[0] = "all"

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
      <p>' . $wqq . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 12px 0;">Is this variable qualitative or quantitative?</p>
    $answerbox[0]
  </div>
</div>


// === ANSWER ===

$solutionguide
