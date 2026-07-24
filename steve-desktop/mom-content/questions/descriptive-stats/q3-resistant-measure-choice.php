// === NAME - DESCRIPTION: Choose a Resistant Measure for a Skewed Distribution - Decide whether to report the mean+SD or median+IQR based on shape, then explain why ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices")

// Scenario 0: Home prices in a city — strong right skew, large outliers — median+IQR is better; reason = mean pulled by outliers
// Scenario 1: SAT scores at a competitive school — roughly symmetric — mean+SD is fine; reason = no outliers, symmetric
// Scenario 2: Time on a webpage (most users <30s, few stay >1hr) — right skewed — median+IQR
// Scenario 3: Heights of adult men — symmetric — mean+SD
// Scenario 4: Income at a town with a few millionaires — strongly right skewed — median+IQR

$ctxs = array(
  "home prices in a coastal city, where most homes are between $400K-$700K but a few mansions sell for $5M-$15M",
  "SAT scores at a competitive private high school, which look roughly bell-shaped with no extreme outliers",
  "time spent on a single webpage by site visitors, where most users leave within 30 seconds but a small group stays for over an hour",
  "heights of adult men, which form a roughly symmetric bell-shaped distribution with no extreme outliers",
  "annual incomes in a small town that includes a few residents with extreme high earnings"
)
$preferred = array(1, 0, 1, 0, 1)  // 0 = mean + SD, 1 = median + IQR

$why = array(
  "Because the distribution is roughly symmetric and has no extreme outliers, the mean is a good summary of center and the standard deviation reflects typical spread.",
  'Because the distribution is strongly skewed with extreme high values, the mean and standard deviation are pulled by the outliers. The median (a resistant measure of center) and the `"IQR"` (a resistant measure of spread) give a better picture of a typical value and its variability.'
)

$picked = jointrandfrom($ctxs, $preferred)
$ctx = $picked[0]
$answer[0] = $picked[1]
$ans_why = $why[$picked[1]]

// Part b: pick the correct rationale
$rationaleIdx = $picked[1]
$answer[1] = $rationaleIdx

$choices[0] = array(
  "Report the <b>mean</b> and <b>standard deviation</b>.",
  'Report the <b>median</b> and <b>`"IQR"`</b>.'
)
$choices[1] = array(
  "The distribution is roughly symmetric with no extreme outliers, so the mean and SD are appropriate.",
  'The distribution is strongly skewed (or has extreme outliers), so the median and `"IQR"` are more resistant and give a better picture of a typical value.'
)
$noshuffle[0] = "all"
$noshuffle[1] = "all"

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
      <p>' . $ans_why . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">You are asked to summarize the distribution of <b>$ctx</b>.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which pair of summary statistics should you report? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Why? $answerbox[1]
  </div>
</div>


// === ANSWER ===

$solutionguide
