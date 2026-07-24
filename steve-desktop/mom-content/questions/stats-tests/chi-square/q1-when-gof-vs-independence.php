// === NAME - DESCRIPTION: When to use Goodness-of-Fit vs Test of Independence - Pick the right chi-square test for a scenario ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices")

// Scenario text and the right chi-square test for each.
// Answer index: 0 = Goodness of Fit, 1 = Test of Independence, 2 = Neither.
$scenarios = array(
  "A casino manager wants to know if a six-sided die in a craps table is fair. After 600 rolls she records how many times each face came up and wants to compare those counts to the 100 each face would be expected to show.",
  "A college survey asks 400 students their political party and whether they live on campus. The dean wants to know if party affiliation and on-campus living are related.",
  "A candy company claims its bags contain 30% red, 20% blue, 20% green, 15% yellow, and 15% orange. A consumer counts the colors in a bag of 200 candies and asks whether the bag matches the claim.",
  "A hospital records 250 patients by blood type and by whether they had a transfusion. The researcher wants to know whether blood type and needing a transfusion are independent.",
  "An online retailer claims that customers are equally likely to shop on each weekday. A traffic log of 700 visits over one week is compared to the equally-likely claim.",
  "A city tracks 350 traffic stops by driver age group and by whether a citation was issued, and asks whether age group and citation outcome are associated."
)

$correct = array(0, 1, 0, 1, 0, 1)

$picked = jointrandfrom($scenarios, $correct)
$story = $picked[0]
$answer[0] = $picked[1]

$choices[0] = array(
  "Chi-square goodness-of-fit test",
  "Chi-square test of independence",
  "Neither, a different test is needed"
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
      <p><b>How to choose:</b></p>
      <ul>
        <li><b>Goodness of Fit</b> compares one categorical variable to a claimed distribution. Counts in <i>k</i> categories vs an expected pattern (equal, or a percent breakdown).</li>
        <li><b>Test of Independence</b> compares <b>two</b> categorical variables from one sample, laid out as a contingency table, to see if the variables are associated.</li>
      </ul>
      <p><b>This scenario:</b> $story</p>'
if ($answer[0] == 0) { $solutionguide .= '<p>One categorical variable (the outcome) is being compared to a claimed distribution, so the correct choice is the <b>chi-square goodness-of-fit test</b>.</p>' }
if ($answer[0] == 1) { $solutionguide .= '<p>Two categorical variables are recorded on the same sample, so the correct choice is the <b>chi-square test of independence</b>.</p>' }
$solutionguide .= '
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$story</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which chi-square test should the researcher use?
    <div style="margin-top:12px;">$answerbox[0]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
