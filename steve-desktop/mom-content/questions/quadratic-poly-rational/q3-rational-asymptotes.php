// === NAME - DESCRIPTION: Rational Asymptotes - Find vertical and horizontal asymptotes of a linear rational function ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats");

$anstypes = array("number", "number", "choices")
$displayformat[2] = "select"
$noshuffle[2] = "all"

// Four scenarios: f(x) = (a*x + b) / (c*x + d)
// VA at x = -d/c (clean integer), HA at y = a/c (clean integer or simple fraction)
// Domain restriction: all real x except the VA value.
// (A) f(x) = (2x + 6) / (x - 3):   a=2,b=6,c=1,d=-3  VA x=3,  HA y=2   domain: x != 3
// (B) f(x) = (3x - 9) / (x + 3):   a=3,b=-9,c=1,d=3  VA x=-3, HA y=3   domain: x != -3
// (C) f(x) = (4x + 8) / (2x - 4):  a=4,b=8,c=2,d=-4  VA x=2,  HA y=2   domain: x != 2
// (D) f(x) = (x - 5) / (x + 5):    a=1,b=-5,c=1,d=5  VA x=-5, HA y=1   domain: x != -5

$func_strs = array(
  "f(x) = (2x + 6) / (x - 3)",
  "f(x) = (3x - 9) / (x + 3)",
  "f(x) = (4x + 8) / (2x - 4)",
  "f(x) = (x - 5) / (x + 5)"
)
$va_vals   = array(3,   -3,  2,   -5)
$ha_vals   = array(2,    3,  2,    1)
// Domain restriction: answer index into choices (0 = first option = matches the VA)
// We build choices per scenario below. Since all 4 have the same choices structure
// (x != VA), we key the correct answer index to always be 0 and shuffle the others.
// Choices: 0 = "All real numbers except x = <VA>", 1,2,3 = plausible wrong domain restrictions.
$va_strs   = array("3",  "-3",  "2",  "-5")
$ha_strs   = array("2",   "3",  "2",   "1")
// Wrong domain options are fixed distractors; we make them relative to the scenario.
// We use the HA value and 0 as plausible wrong zeros.
$wrong1s   = array("x = -3", "x = -9", "x = -4", "x = 5")
$wrong2s   = array("x = 2",  "x = 0",  "x = 4",  "x = -1")
$wrong3s   = array("x = -6", "x = 9",  "x = 1",  "x = 0")

$picked = jointrandfrom($func_strs, $va_vals, $ha_vals, $va_strs, $ha_strs, $wrong1s, $wrong2s, $wrong3s)
$func_str = $picked[0]
$va_val   = $picked[1]
$ha_val   = $picked[2]
$va_str   = $picked[3]
$ha_str   = $picked[4]
$wrong1   = $picked[5]
$wrong2   = $picked[6]
$wrong3   = $picked[7]

$answer[0] = $va_val
$answer[1] = $ha_val
$answer[2] = 0

$questions[2] = array(
  "All real numbers except x = " . $va_str,
  "All real numbers except " . $wrong1,
  "All real numbers except " . $wrong2,
  "All real numbers (no restrictions)"
)

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
      <p><b>Function:</b> `'.$func_str.'`</p>
      <p><b>(a) Vertical Asymptote:</b> Set the denominator equal to zero and solve for x.</p>
      <p style="margin-left:1em;">Denominator = 0 gives <b>x = '.$va_str.'</b>. This is the vertical asymptote.</p>
      <p><b>(b) Horizontal Asymptote:</b> For a rational function where the numerator and denominator have the same degree (both degree 1), the horizontal asymptote is the ratio of the leading coefficients.</p>
      <p style="margin-left:1em;">Leading coefficient of numerator / Leading coefficient of denominator = <b>y = '.$ha_str.'</b>.</p>
      <p><b>(c) Domain:</b> The function is defined everywhere the denominator is not zero.</p>
      <p style="margin-left:1em;">Domain: all real numbers except <b>x = '.$va_str.'</b>.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Vertical asymptote: <b>x = '.$va_str.'</b> &nbsp;&bull;&nbsp; Horizontal asymptote: <b>y = '.$ha_str.'</b>
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 4px 0;">Consider the rational function:</p>
    <p style="margin:0; text-align:center;">`$func_str`</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the <b>vertical asymptote</b>? Enter the x-value where the asymptote occurs. <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the <b>horizontal asymptote</b>? Enter the y-value. <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which of the following best describes the <b>domain</b> of `f(x)`? <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>


// === ANSWER ===

$solutionguide
