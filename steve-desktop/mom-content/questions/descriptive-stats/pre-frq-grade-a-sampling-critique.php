// === NAME - DESCRIPTION: Pre-FRQ Grade a Sampling Design Critique - The scenario and grading checklist of the sampling-critique FRQ, but the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Chapter 1's companion to the 2.x pre-FRQ. Same idea: the scenario and the SAME grading checklist
// as the real free-response item (questions/frq/descriptive-statistics/q10-sampling-design-critique.php),
// but every part auto-grades, so it can live in homework where free response is not allowed.
// Keep the three categories and their point values identical to q10: the value of this question is
// entirely that the checklist is the one students will actually be marked against.
$anstypes = array("choices", "multans", "choices")

// TWO of the four scenarios run too high and two run too low, on purpose. All three of the original
// scenarios resolved "too high", which meant a student could answer part (b)'s direction correctly
// without reading the plan at all: and predicting the direction is the whole category the question
// is built to drill. A rubric category a student can pass by pattern-matching is not being assessed.
//
// The scenario this replaced also had a soft answer: students outside a careers office were said to
// work MORE hours because they are "job-hunting or already working", but job-hunting students
// plausibly work fewer. A direction the writer has to argue for is a direction a student can
// reasonably get wrong.
$si = rand(0, 3)
if ($si == 0) {
  $who = "A campus health service"
  $goal = "what share of students have skipped a meal because they could not afford one"
  $plan = "handing the survey to students as they came out of the campus dining hall at lunchtime"
  $missed = "students who cannot afford to eat there at all, and anyone skipping lunch that day"
  $bias = "convenience"
  $dir = "too low"
  $why = "the students the survey is about are precisely the ones not in the dining hall to be asked"
  $frame = "the registrar's list of all enrolled students"
}
else if ($si == 3) {
  $who = "A property manager"
  $goal = "what share of tenants have an unresolved maintenance problem"
  $plan = "asking tenants who turned up to the building's summer social evening"
  $missed = "tenants who stay away from building events, including those most frustrated with management"
  $bias = "voluntary response"
  $dir = "too low"
  $why = "a tenant with a complaint nobody has fixed is the least likely to spend an evening with the management"
  $frame = "the full tenancy list"
}
else if ($si == 1) {
  $who = "A city council"
  $goal = "what share of residents own a car"
  $plan = "surveying people as they came out of a large out-of-town shopping center on a Saturday"
  $missed = "residents without transport, who are far less likely to be at an out-of-town site"
  $bias = "convenience"
  $dir = "too high"
  $why = "reaching an out-of-town shopping center nearly requires a car in the first place"
  $frame = "the city voter registration list"
}
else {
  $who = "A gym chain"
  $goal = "how satisfied its members are"
  $plan = "emailing the survey only to members who had visited at least twelve times that month"
  $missed = "members who rarely attend, or who have quietly stopped coming"
  $bias = "voluntary response and undercoverage"
  $dir = "too high"
  $why = "the members most likely to be dissatisfied are exactly the ones who stopped turning up"
  $frame = "the full membership database"
}

// The full-credit response hits all three categories. Each weaker one drops exactly one or two, and
// which categories it drops is what part (b) asks for: so every response has to be built against
// the checklist rather than written loosely.
$rFull = "The plan is a " . $bias . " sample: " . $plan . " reaches only people already in one place, so " . $missed . " never get a chance to appear. That pushes the result " . $dir . ", because " . $why . ". A better design would take a simple random sample from " . $frame . ", so every member of the population has the same chance of being picked."

// Names the bias and explains it, and proposes a fix: but never says which way the answer is off.
$rNoDirection = "This is a " . $bias . " sample, because " . $plan . " only reaches people who happen to be there, leaving out " . $missed . ". Instead they should take a simple random sample from " . $frame . " so that everyone has an equal chance of selection."

// Direction and improvement, but never NAMES the bias or explains why the plan causes it.
$rNoBias = "The result will come out " . $dir . ", since " . $why . ". They would do better to draw a simple random sample from " . $frame . " rather than surveying whoever is standing there."

// Names and explains the bias and gives the direction, but proposes no design at all.
$rNoDesign = "It is a " . $bias . " sample: " . $plan . " only ever reaches one kind of person, so " . $missed . " are missed entirely. The figure will therefore be " . $dir . ", because " . $why . ". They should be more careful about who they ask."

$questions[0] = array($rFull, $rNoDirection, $rNoBias, $rNoDesign)
$answer[0] = 0

// Part (b) grades the NO-DIRECTION response: it earns Identify the Bias and Improved Design, and
// fails only Predict the Direction. One category, not all three: so ticking everything fails.
$questions[1] = array(
  "Identify the Bias (4 pts)",
  "Predict the Direction (3 pts)",
  "Improved Design (3 pts)"
)
$answer[1] = "1"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. Saying a sample is biased does not say which way it is wrong, and the rubric asks for the direction and the reason behind it as a separate point.",
  "Yes. Once the bias is named, the direction follows automatically and does not need stating.",
  "Yes, as long as the proposed better design is correct, because that shows the bias was understood.",
  "No, but only because the response was too short. Naming the bias twice would have earned the direction."
)
$answer[2] = 0

$css_block = '
<style>
  .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .rubric-container summary::-webkit-details-marker { display:none; }
  .arrow-open { display:none; }
  .rubric-container details[open] .arrow-closed { display:none; }
  .rubric-container details[open] .arrow-open { display:inline; }
  .rubric-content { background:#fafafa; padding:0.75em; box-sizing:border-box; }
  .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .row-colored { background:#fff9ea; }
  .resp-box { border:1px solid #ccc; border-left:4px solid #f59e0b; background:#fffbeb; padding:12px; border-radius:4px; margin:10px 0; font-family:Arial; }
</style>'

$rubricblock = $css_block . '
<div class="rubric-container">
  <details open>
    <summary><span class="arrow-closed">&#9656;</span><span class="arrow-open">&#9662;</span> Grading Checklist: 10 points</summary>
    <div class="rubric-content">
      <p style="margin:0 0 0.5em 0;"><b>Grading Criteria</b>: a full-credit critique must address:</p>
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Identify the Bias<br>(4 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li>Name the type of sampling bias.</li>
              <li>Explain why the sampling plan creates that bias.</li>
            </ul></td></tr>
          <tr><td style="text-align:center;"><b>Predict the Direction<br>(3 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li>State whether the result will be too high or too low.</li>
              <li>Explain why, using a feature of the sampled versus the missed groups.</li>
            </ul></td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Improved Design<br>(3 pts)</b></td>
            <td><ul style="list-style:none;margin:0;padding-left:0;">
              <li>Propose a randomized sampling method.</li>
              <li>Name the sampling frame (the full population list).</li>
            </ul></td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>'

$respBox = '<div class="resp-box">' . $rNoDirection . '</div>'

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
      <p><span class="term-label">Part (a).</span> Only one response earns all three categories. It names the bias (' . $bias . ') AND says what about the plan causes it, states the direction (' . $dir . ') AND the reason, and proposes a randomized method together with the sampling frame: ' . $frame . '. Each of the other three drops at least one whole category.</p>
      <p><span class="term-label">Part (b): grade it category by category.</span> The response you were given is correct in everything it says. Work down the checklist:</p>
      <ul>
        <li><b>Identify the Bias: earned.</b> It names the bias and explains why the plan produces it.</li>
        <li><b>Predict the Direction: not earned.</b> It never says whether the figure comes out too high or too low. This is the only category it misses.</li>
        <li><b>Improved Design: earned.</b> Simple random sample, and it names the frame.</li>
      </ul>
      <p>So 7 of 10, for an answer with nothing wrong in it. Notice that ticking all three boxes would have been just as wrong as ticking none: the question is which categories were missed, not whether the answer was imperfect.</p>
      <p><span class="term-label">Part (c): why the direction is its own category.</span> "This sample is biased" tells a reader that the number cannot be trusted, but not which way to lean. A survey that runs ' . $dir . ' is a specific, checkable claim, and it is what makes the critique useful to whoever has to act on the figure. That is why the rubric awards it separately from naming the bias.</p>
      <p><span class="term-label">Why this exists.</span> On the lab and on the test this scenario comes with a blank box and this same checklist. Finding the missing category in someone else\'s answer is the quickest way to stop leaving it out of your own.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 4px 0; font-size:13px; font-weight:700; color:#1865f2; letter-spacing:0.04em;">FREE-RESPONSE PROMPT</p>
    <p style="margin:0 0 10px 0;">$who wanted to find out $goal. It collected its data by $plan.</p>
    <p style="margin:0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task:</b> Critique this sampling plan. Identify the bias, predict the direction of the error, and propose a better design.</p>
    $rubricblock
    <p style="margin:8px 0 0 0; font-size:14px; color:#6b7280;">You are not writing the critique here. You are grading it, against the checklist above.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which response earns <b>all 10 points</b>? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Now grade this one:
    $respBox
    Select <b>every category it fails to earn</b>. Nothing it says is wrong: read it against the checklist before you choose. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is naming the bias enough on its own to cover the direction of the error? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
