// === NAME - DESCRIPTION: Pre-FRQ Judge a Comparison Against the Rubric - An FRQ-style prompt with four sample student responses; pick the one that earns full credit, select every reason a weaker one loses credit, and state what a complete comparison must contain ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A "pre-FRQ": the setup and the rubric of a free-response question, but every part is auto-graded,
// so it can sit in homework where free response is not allowed. It rehearses the marking scheme the
// real FRQs on the labs and tests are scored against -- the point is to make the rubric visible
// BEFORE the student has to satisfy it from a blank page.
$anstypes = array("choices", "multans", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $groupA = "Riverside"
  $groupB = "Hillcrest"
  $what = "the number of minutes each pupil spent travelling to school"
  $varName = "travel time"
  $unit = "minutes"
}
else {
  $groupA = "Northgate"
  $groupB = "Southbank"
  $what = "the weekly amount, in dollars, each member spent at the cafe"
  $varName = "weekly spend"
  $unit = "dollars"
}

// A is centred higher; B is more spread out AND right-skewed. Two groups that differ on BOTH centre
// and spread is what makes an incomplete answer visibly incomplete -- if they differed only in
// centre, the response that never mentions spread would not actually be missing anything.
$medB = 5 * rand(6, 10)
$medA = $medB + 5 * rand(1, 3)
$halfA = rand(3, 5)
$halfB = $halfA + rand(2, 4)
$iqrA = 2 * $halfA
$iqrB = 2 * $halfB
$q1A = $medA - $halfA
$q3A = $medA + $halfA
$q1B = $medB - $halfB
$q3B = $medB + $halfB
$minA = $q1A - rand(4, 6)
$maxA = $q3A + rand(4, 6)
$minB = $q1B - rand(2, 3)
// B's maximum MUST clear A's. The one-extreme-value distractor asserts that B holds the single
// largest value, and on a seed where A's maximum happens to be higher that response is simply false
// rather than tempting -- a student rejects it without engaging with the reasoning error it is
// there to teach. The long upper tail also keeps B visibly right-skewed.
$maxB = $maxA + rand(3, 8)

$rFull = "Group " . $groupA . " has the higher centre: its median " . $varName . " is " . $medA . " " . $unit . " against " . $medB . " " . $unit . " for " . $groupB . ". " . $groupB . " is the more spread out of the two, with an interquartile range of " . $iqrB . " " . $unit . " compared with " . $iqrA . " for " . $groupA . ". " . $groupA . " is roughly symmetric, while " . $groupB . " is skewed to the right, with a long tail running out to the high values."

$rNoCompare = "Group " . $groupA . " has a median of " . $medA . " " . $unit . ", an interquartile range of " . $iqrA . " " . $unit . ", and is roughly symmetric. Group " . $groupB . " has a median of " . $medB . " " . $unit . ", an interquartile range of " . $iqrB . " " . $unit . ", and is skewed to the right."

$rVague = "The first group is bigger than the second one. Its middle value is higher, so that group is the better of the two."

$rOneValue = "Group " . $groupB . " contains the single largest value, " . $maxB . ", so " . $groupB . " is higher overall. Group " . $groupA . " has nothing anywhere near that large."

$questions[0] = array($rFull, $rNoCompare, $rVague, $rOneValue)
$answer[0] = 0

$questions[1] = array(
  "It reports each group's figures one after the other without ever comparing them &mdash; there is no comparative word such as higher, larger or more spread out.",
  "It never says which group has the higher centre.",
  "It never says which group is more spread out.",
  "The median it reports for " . $groupA . " does not match the data given.",
  "It says nothing about the shape of either distribution."
)
$answers[1] = "0,1,2"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "Centre, spread and shape &mdash; each one written as a direct comparison between the two groups, and each stated in terms of " . $varName . " rather than as bare numbers.",
  "The full five-number summary of each group, written out so the reader can see every value.",
  "A clear statement of which group is better, backed up by the largest value in each group.",
  "Centre and spread for each group, listed separately, so that the reader can compare them."
)
$answer[2] = 0

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
  .rub { border-left:4px solid #1865f2; padding-left:12px; margin:8px 0; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><span class="term-label">The rubric.</span> A comparison of two distributions earns credit for four things, and dropping any one of them costs marks:</p>
      <div class="rub">
        <p style="margin:4px 0;"><b>1. Centre</b> &mdash; which group is higher, with the medians quoted.</p>
        <p style="margin:4px 0;"><b>2. Spread</b> &mdash; which group is more variable, with the IQRs or ranges quoted.</p>
        <p style="margin:4px 0;"><b>3. Shape</b> &mdash; symmetric, skewed left, skewed right.</p>
        <p style="margin:4px 0;"><b>4. Comparative language, in context</b> &mdash; the words <i>higher</i>, <i>larger</i>, <i>more spread out</i>, applied to ' . $varName . ', not just two lists of numbers.</p>
      </div>
      <p><span class="term-label">Part (a).</span> Only one response does all four. It says ' . $groupA . ' is centred higher (' . $medA . ' against ' . $medB . '), that ' . $groupB . ' is the more spread out (IQR ' . $iqrB . ' against ' . $iqrA . '), and that the shapes differ &mdash; and it says all of it about ' . $varName . '.</p>
      <p><span class="term-label">Part (b) &mdash; the most common way to lose marks.</span> The response quoted is not <i>wrong</i> anywhere. Every number in it is right and it does mention both shapes. What it never does is <b>compare</b>: it puts one group beside the other and leaves the reader to do the work. That is why the two options accusing it of a wrong median or of ignoring shape are false &mdash; read the response before selecting, because a plausible-sounding criticism that the response does not actually commit still costs you the part.</p>
      <p><span class="term-label">Part (c).</span> Listing the two groups separately is exactly the trap in part (b), so the option that says "listed separately, so that the reader can compare them" is describing the response that loses marks. The comparison has to be made in the answer, not left to the reader.</p>
      <p><span class="term-label">Why this matters.</span> On a lab or a test this question is asked with a blank box instead of four options. The rubric is the same one. Learning to spot the missing piece in someone else\'s answer is the fastest way to stop leaving it out of your own.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 4px 0; font-size:13px; font-weight:700; color:#1865f2; letter-spacing:0.04em;">FREE-RESPONSE PROMPT</p>
    <p style="margin:0 0 12px 0;">A study recorded $what at two schools, $groupA and $groupB. The five-number summaries are below.</p>
    <table style="border-collapse:collapse; margin:4px 0 12px 0; background:#fff;">
      <tr style="background:#f0f4ff;">
        <th style="border:1px solid #d1d5db; padding:6px 14px;">School</th>
        <th style="border:1px solid #d1d5db; padding:6px 14px;">Min</th>
        <th style="border:1px solid #d1d5db; padding:6px 14px;">Q&#8321;</th>
        <th style="border:1px solid #d1d5db; padding:6px 14px;">Median</th>
        <th style="border:1px solid #d1d5db; padding:6px 14px;">Q&#8323;</th>
        <th style="border:1px solid #d1d5db; padding:6px 14px;">Max</th>
      </tr>
      <tr>
        <td style="border:1px solid #d1d5db; padding:6px 14px; font-weight:700;">$groupA</td>
        <td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$minA</td>
        <td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$q1A</td>
        <td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$medA</td>
        <td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$q3A</td>
        <td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$maxA</td>
      </tr>
      <tr>
        <td style="border:1px solid #d1d5db; padding:6px 14px; font-weight:700;">$groupB</td>
        <td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$minB</td>
        <td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$q1B</td>
        <td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$medB</td>
        <td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$q3B</td>
        <td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$maxB</td>
      </tr>
    </table>
    <p style="margin:0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task:</b> Compare the distributions of $varName at the two schools.</p>
    <p style="margin:12px 0 0 0; font-size:14px; color:#6b7280;">You are not writing the answer here. You are marking it.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Four students answered. Which response earns <b>full credit</b>? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Another student wrote this:
    <p style="margin:10px 0; padding:12px; background:#fffbeb; border-left:4px solid #f59e0b; border-radius:4px;">$rNoCompare</p>
    Select <b>every</b> reason this response loses credit. Read it carefully first &mdash; some of the statements below are not true of it. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> In general, what must a complete comparison of two distributions contain? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
