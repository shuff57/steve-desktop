// === NAME - DESCRIPTION: Repeated-Measures Design Identification - In a randomized sleep-deprivation driving study, name the explanatory and response variables, identify the design against three distractors, and say what the within-subject comparison and session order control ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("string", "string", "choices", "string", "string")

$driversOptions = array(12, 15, 18, 19, 21, 24, 28, 30)
$nDrivers = $driversOptions[rand(0, 7)]

$hoursOptions = array(20, 22, 24, 27, 30, 33, 36)
$hoursDeprived = $hoursOptions[rand(0, 6)]

$taskOptions = array(
  "a driving simulation",
  "a reaction-time test on a driving simulator",
  "a lane-keeping exercise on a driving simulator",
  "a hazard-perception test using recorded driving footage",
  "a simulated highway merging task"
)
$task = $taskOptions[rand(0, 4)]

$expVarAns = "the sleep condition the driver was in, normal sleep versus " . $hoursDeprived . " hours of total sleep deprivation or which of the two sleep conditions the driver experienced before the session"
$strflags[0] = "ignore_case,trim_whitespace"

$respVarAns = "the driver's performance on " . $task . " or how well the driver performed on " . $task . " during the session"
$strflags[1] = "ignore_case,trim_whitespace"

$designOptions = array(
  "A randomized, repeated-measures (matched) design &ndash; each driver serves as their own comparison in both sleep conditions, and the order of the two sessions is randomized",
  "An independent-groups design &ndash; each driver is randomly assigned to only one of the two sleep conditions, and the two separate groups of drivers are compared",
  "A randomized block design &ndash; drivers are first sorted into blocks of similar drivers, then randomly assigned to one sleep condition within each block",
  "A purely observational study &ndash; researchers record each driver's existing sleep habits and driving performance without assigning anyone to a sleep condition"
)

$choices[2] = $designOptions
$answer[2] = 0

$ownControlAns = "lurking variables that differ between people, such as baseline reaction speed, driving experience, and eyesight, since the same driver appears in both sleep conditions or between-subject differences like reaction speed, experience, and eyesight, because each driver is compared only against themselves"
$strflags[3] = "ignore_case,trim_whitespace"

$orderControlAns = "order effects, such as practice on the simulator and fatigue from the time of day, from being tied to one sleep condition instead of the other or which session came first, so that practice and fatigue from session order do not unfairly favor one sleep condition"
$strflags[4] = "ignore_case,trim_whitespace"

$answer[0] = $expVarAns
$answer[1] = $respVarAns
$answer[3] = $ownControlAns
$answer[4] = $orderControlAns

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
  .term-label { font-weight:700; color:#1865f2; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><span class="term-label">Step 1 &mdash; Identify the variables.</span> The explanatory variable is ' . $expVarAns . '. The response variable is ' . $respVarAns . '.</p>
      <p><span class="term-label">Step 2 &mdash; Identify the units and treatments.</span> The experimental units are the ' . $nDrivers . ' professional drivers. The two treatments are the two sleep conditions.</p>
      <p><span class="term-label">Step 3 &mdash; Name the design.</span> Each driver went through <strong>both</strong> sessions, so every driver is their own control &mdash; this is a matched, repeated-measures design. Because the two conditions were assigned in <strong>random order</strong>, the study is also randomized. That rules out an independent-groups design (which would need two separate groups of drivers), a randomized block design (which sorts different drivers into blocks before assigning a single condition to each), and a purely observational study (no condition is assigned at all).</p>
      <p><span class="term-label">Step 4 &mdash; Say what that buys you.</span> Having each driver serve as their own comparison removes ' . $ownControlAns . '. Randomizing the session order removes ' . $orderControlAns . '.</p>
      <p><b>Answer:</b> (a) explanatory &ndash; ' . $expVarAns . '; response &ndash; ' . $respVarAns . '. (b) ' . $designOptions[0] . '. (c) each driver as their own comparison controls for ' . $ownControlAns . '; randomizing session order controls for ' . $orderControlAns . '.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">How does sleep deprivation affect a driver's ability to drive? A study measured the effects on $nDrivers professional drivers. Each driver participated in two experimental sessions: one after a normal night of sleep and one after $hoursDeprived hours of total sleep deprivation. The two sessions were assigned to each driver in random order. In each session, performance was measured using $task.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the <b>explanatory variable</b> in this study? $answerbox[0]
    <p style="margin:12px 0 0 0;">What is the <b>response variable</b> in this study? $answerbox[1]</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which of the following best describes the design of this study? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What does having each driver serve as their own comparison (appearing in <b>both</b> sleep conditions) control for? $answerbox[3]
    <p style="margin:12px 0 0 0;">What does randomizing the <b>order</b> of the two sessions control for? $answerbox[4]</p>
  </div>
</div>

// === ANSWER ===

$solutionguide
