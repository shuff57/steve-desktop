// === NAME - DESCRIPTION: Choose the right Ch6 test - Pick single-prop, two-prop, GOF, or independence from a scenario ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices","choices","choices","choices")

// 8 scenarios across all four Ch6 test types. Draw 4 per attempt.
// Answer index: 0 = One-sample proportion z-test, 1 = Two-sample proportion z-test,
//               2 = Chi-square goodness-of-fit, 3 = Chi-square test of independence.

$scenarios = array(
  "A national survey claims 40% of adults read a printed newspaper at least weekly. A polling firm collects responses from 500 adults this year and wants to test whether the proportion who read printed news has changed.",
  "A medical team compares the proportion of patients who experience side effects in a treatment group of 120 patients versus a placebo group of 110 patients, and wants to know if the two proportions differ.",
  "A statistics class records 240 M&M candies by color and compares the observed counts to the manufacturer's published distribution of 24% blue, 20% orange, 16% green, 14% yellow, 13% red, 13% brown.",
  "A school district tracks 600 students by grade level and by whether they participate in extracurricular activities, then asks whether grade level and participation are associated.",
  "A pharmaceutical company tests whether more than 65% of patients who use a new inhaler report improvement, surveying 250 patients in a single sample.",
  "A registrar tabulates 800 students by major area (STEM, humanities, business) and by housing (on-campus, off-campus) and asks whether major and housing choice are independent.",
  "A casino monitors 720 spins of a roulette wheel, counting how often each color (red, black, green) lands, and compares the counts to the equally-likely pattern claimed by the manufacturer.",
  "A market researcher records whether a sample of 90 men and 110 women favor a new product and wants to determine if the favorability rate differs between men and women."
)

$correct = array(0, 1, 2, 3, 0, 3, 2, 1)

// Pick 4 distinct scenarios
$idx = diffrands(0, count($scenarios)-1, 4)
$story = array($scenarios[$idx[0]], $scenarios[$idx[1]], $scenarios[$idx[2]], $scenarios[$idx[3]])
$answer[0] = $correct[$idx[0]]
$answer[1] = $correct[$idx[1]]
$answer[2] = $correct[$idx[2]]
$answer[3] = $correct[$idx[3]]

$opt = array(
  "One-sample proportion z-test",
  "Two-sample proportion z-test",
  "Chi-square goodness-of-fit test",
  "Chi-square test of independence"
)
$choices[0] = $opt
$choices[1] = $opt
$choices[2] = $opt
$choices[3] = $opt
$noshuffle[0] = "all"
$noshuffle[1] = "all"
$noshuffle[2] = "all"
$noshuffle[3] = "all"
$displayformat[0] = "select"
$displayformat[1] = "select"
$displayformat[2] = "select"
$displayformat[3] = "select"

// Build per-part rationales
$why = array(
  "<p>One categorical variable, one sample, compared to a claimed proportion. Use the <b>one-sample proportion z-test</b>.</p>",
  "<p>Two independent samples, each with a categorical outcome. Use the <b>two-sample proportion z-test</b>.</p>",
  "<p>One categorical variable with multiple categories, observed counts compared to a claimed distribution. Use the <b>chi-square goodness-of-fit test</b>.</p>",
  "<p>Two categorical variables recorded on the same sample, displayed as a contingency table. Use the <b>chi-square test of independence</b>.</p>"
)

$rationale[0] = $why[$answer[0]]
$rationale[1] = $why[$answer[1]]
$rationale[2] = $why[$answer[2]]
$rationale[3] = $why[$answer[3]]

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
      <p><b>How to choose a Chapter 6 test:</b></p>
      <ul>
        <li><b>One-sample proportion z-test</b>: one categorical outcome, one sample, compared to a claimed proportion `p_0`.</li>
        <li><b>Two-sample proportion z-test</b>: one categorical outcome measured on two independent samples; compare `p_1` to `p_2`.</li>
        <li><b>Chi-square goodness-of-fit</b>: one categorical variable with three or more categories, observed counts compared to a claimed distribution.</li>
        <li><b>Chi-square test of independence</b>: two categorical variables on one sample, laid out as a contingency table.</li>
      </ul>
      <p><b>Part a:</b> ' . $story[0] . '</p>
      ' . $rationale[0] . '
      <p><b>Part b:</b> ' . $story[1] . '</p>
      ' . $rationale[1] . '
      <p><b>Part c:</b> ' . $story[2] . '</p>
      ' . $rationale[2] . '
      <p><b>Part d:</b> ' . $story[3] . '</p>
      ' . $rationale[3] . '
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">For each scenario, pick the chi-square or proportion test that fits. The four choices are: one-sample proportion z-test, two-sample proportion z-test, chi-square goodness-of-fit test, and chi-square test of independence.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> $story[0]
    <div style="margin-top:12px;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> $story[1]
    <div style="margin-top:12px;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> $story[2]
    <div style="margin-top:12px;">$answerbox[2]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> $story[3]
    <div style="margin-top:12px;">$answerbox[3]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
