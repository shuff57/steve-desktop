// === NAME - DESCRIPTION: Paired vs independent two-sample - Choose the right test design from scenarios ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices","choices","choices","choices")

// 8 scenarios. Draw 4 per attempt. Answer: 0 = paired, 1 = independent two-sample.
// Key: do the two measurements come from the SAME subject (or matched pair), or from TWO independent groups?

$scenarios = array(
  "A trainer measures each athlete's resting heart rate before and after a 6-week training program and asks whether the program lowered heart rate.",
  "A college researcher compares the average GPA of students living on campus to students living off campus.",
  "A teacher records each student's score on a pre-test and a post-test and tests whether scores improved.",
  "A pharmaceutical company assigns 50 patients to a drug group and 50 different patients to a placebo group and compares mean recovery times.",
  "A psychologist measures each twin's anxiety score and pairs each twin who lives in the city with the co-twin who lives in the country, then compares mean anxiety.",
  "A nutritionist compares the average daily caloric intake of vegetarians to omnivores, with no individual measured twice.",
  "A car magazine measures fuel efficiency of each tested car using both gasoline and a new fuel blend, then compares the means.",
  "A school district compares the average test score of fourth graders in District A to fourth graders in District B (different students)."
)

$correct = array(0, 1, 0, 1, 0, 1, 0, 1)

$idx = diffrands(0, count($scenarios)-1, 4)
$story = array($scenarios[$idx[0]], $scenarios[$idx[1]], $scenarios[$idx[2]], $scenarios[$idx[3]])
$answer[0] = $correct[$idx[0]]
$answer[1] = $correct[$idx[1]]
$answer[2] = $correct[$idx[2]]
$answer[3] = $correct[$idx[3]]

$opt = array(
  "Paired t-test (matched-pairs design)",
  "Independent two-sample t-test"
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

$why = array(
  "Each subject (or pair) is measured twice, so the two measurements are naturally linked. Compare differences within each pair.",
  "Two independent groups, no natural pairing. Compare the two group means."
)

$rationale[0] = "<p>" . $why[$answer[0]] . "</p>"
$rationale[1] = "<p>" . $why[$answer[1]] . "</p>"
$rationale[2] = "<p>" . $why[$answer[2]] . "</p>"
$rationale[3] = "<p>" . $why[$answer[3]] . "</p>"

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
      <p><b>How to decide.</b> Are the two values for each observation linked to the same individual (or a matched pair)? If yes, use <b>paired</b>. If the two groups are separate sets of subjects, use <b>independent two-sample</b>.</p>
      <p><b>Part a.</b> ' . $story[0] . '</p>
      ' . $rationale[0] . '
      <p><b>Part b.</b> ' . $story[1] . '</p>
      ' . $rationale[1] . '
      <p><b>Part c.</b> ' . $story[2] . '</p>
      ' . $rationale[2] . '
      <p><b>Part d.</b> ' . $story[3] . '</p>
      ' . $rationale[3] . '
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">For each scenario, decide whether the right test is a <b>paired t-test</b> (matched-pairs design) or an <b>independent two-sample t-test</b>.</p>
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
