// === NAME - DESCRIPTION: Pre-FRQ Grade a Percentile Reading - A percentile scenario with a grading checklist, where the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The pre-FRQ for 2.3. There is no FRQ in questions/frq/ covering measures of location, so the
// scenario and checklist here are ORIGINAL rather than mirrored: they define the shape a later
// 2.3 FRQ should match. See reference/pre-frq-template.md.
//
// The dropped category is STATE WHAT THE PERCENTILE MEANS. Students find the value at the percentile
// and jump straight to a verdict, never saying that a percentile is a statement about the share of
// the data at or below it. It is the definition the whole section rests on and it feels like
// something everyone already knows. Distinct from 2.4, 2.5, 2.6 and 2.7.
//
// Every sentence below is CATEGORY-PURE: it earns its own rubric line and no other. In particular
// the direction sentence talks about which END of the scale is desirable, never about how much of
// the data sits below: that would leak the meaning category back in.
$anstypes = array("choices", "multans", "choices")

$i = rand(0, 2)

$settings = array(
  "the wait times, in minutes, of the 20 patients seen in an emergency room one evening",
  "the delivery times, in minutes, of the 20 orders a restaurant sent out on a Friday night",
  "the times, in minutes, that 20 callers spent on hold with a support line"
)
$setting = $settings[$i]

$who_labels = array("patient", "order", "caller")
$who = $who_labels[$i]

$role_labels = array("charge nurse", "shift manager", "support supervisor")
$role = $role_labels[$i]

// Twenty ordered values built from a strictly increasing base, so the k-th smallest is unambiguous
// and the percentile position is never a judgement call. Disjoint steps, deliberately: an
// overlapping generator can print an out-of-order list and misreport the value at the percentile.
$base = rand(8, 12)
$step = rand(2, 3)
$p = 80
$rank = 16
$valAtP = $base + ($rank - 1) * $step
$valNext = $base + $rank * $step
$minV = $base
$maxV = $base + 19 * $step
$below = 80
$above = 20

$listPart1 = $base . ', ' . ($base + $step) . ', ' . ($base + 2 * $step) . ', ' . ($base + 3 * $step) . ', ' . ($base + 4 * $step) . ', ' . ($base + 5 * $step) . ', ' . ($base + 6 * $step) . ', ' . ($base + 7 * $step) . ', ' . ($base + 8 * $step) . ', ' . ($base + 9 * $step)
$listPart2 = ($base + 10 * $step) . ', ' . ($base + 11 * $step) . ', ' . ($base + 12 * $step) . ', ' . ($base + 13 * $step) . ', ' . ($base + 14 * $step) . ', ' . $valAtP . ', ' . $valNext . ', ' . ($base + 17 * $step) . ', ' . ($base + 18 * $step) . ', ' . $maxV

// One sentence per rubric category. None of them restates another.
$sLocate = 'With 20 values in order, the ' . $p . 'th percentile falls at the ' . $rank . 'th value, which is ' . $valAtP . ' minutes.'
// No article before an interpolated noun: "a order" and "a caller" cannot both be right, and the
// sentence does not need one. Rendered as "a order" before this was reworded.
$sMeaning = 'Being at the ' . $p . 'th percentile means ' . $below . '% of the twenty values are at or below ' . $valAtP . ' minutes, and the other ' . $above . '% are longer.'
$sDirection = 'On this scale a longer time is worse, so scoring high here is bad news: the same percentile on an exam score would be good news, and the number alone does not tell you which.'

$rFull = $sLocate . ' ' . $sMeaning . ' ' . $sDirection
$rNoMeaning = $sLocate . ' ' . $sDirection
$rNoDirection = $sLocate . ' ' . $sMeaning
$rLocateOnly = $sLocate . ' That is the figure the percentile asks for.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoMeaning
$rC = $rNoDirection
$rD = $rLocateOnly
if ($pos == 1) {
  $rA = $rNoMeaning
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoDirection
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rLocateOnly
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noMeaningLabel = "B"
if ($pos == 1) { $noMeaningLabel = "A" }

$questions[1] = array(
  "Locate the Value (3 pts)",
  "State What the Percentile Means (4 pts)",
  "Direction in Context (3 pts)"
)
$answer[1] = "0,2"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. A percentile is a statement about what share of the data sits at or below a value; naming the value and judging it good or bad never says that, and it is the definition the rest of the section is built on.",
  "Yes. Once the value at the percentile is found and judged, its meaning is obvious from the number itself.",
  "No, but only because the response did not repeat the value a second time.",
  "Yes, as long as the position was counted correctly."
)
$answer[2] = 0

$css = '
<style>
  .qscope23 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope23 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope23 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope23 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope23 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope23 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope23 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope23 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope23 .row-colored { background:#fff9ea; }
  .qscope23 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope23 .resp b { color:#1865f2; }
  .qscope23 .datalist { border:1px solid #d1d5db; border-radius:8px; padding:10px 14px; margin:10px 0; background:#f8fafc; font-size:15px; line-height:1.8; }
</style>'

$rubric = $css . '
<div class="qscope23">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Locate the Value<br>(3 pts)</b></td>
            <td>Find the value sitting at the stated percentile in the ordered list.</td></tr>
          <tr><td style="text-align:center;"><b>State What the Percentile Means<br>(4 pts)</b></td>
            <td>Say what being at that percentile means: the share of the data at or below the value, and the share above it.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Direction in Context<br>(3 pts)</b></td>
            <td>Say which end of this particular scale is the desirable one, so a high percentile is read the right way round.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$dataBlock = '
<div class="qscope23">
  <div class="datalist"><b>All twenty values, in order (minutes):</b><br>' . $listPart1 . ',<br>' . $listPart2 . '</div>
</div>'

$responses = '
<div class="qscope23">
  <div class="resp"><b>Response A.</b> ' . $rA . '</div>
  <div class="resp"><b>Response B.</b> ' . $rB . '</div>
  <div class="resp"><b>Response C.</b> ' . $rC . '</div>
  <div class="resp"><b>Response D.</b> ' . $rD . '</div>
</div>'

$fullLabel = "A"
if ($pos == 1) { $fullLabel = "B" }
if ($pos == 2) { $fullLabel = "C" }
if ($pos == 3) { $fullLabel = "D" }

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
      <p><span class="term-label">Part (a): only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> finds the value, says what the percentile means, and says which direction is the good one on this scale. Each of the other three drops a whole category.</p>
      <p><span class="term-label">The position.</span> Twenty values in order, so the ' . $p . 'th percentile sits at the ' . $rank . 'th of them: <b>' . $valAtP . ' minutes</b>. Sixteen of the twenty are at or below it and four are above.</p>
      <p><span class="term-label">Part (b): grading Response ' . $noMeaningLabel . ' line by line.</span></p>
      <ul>
        <li><b>Locate the Value: earned.</b> It counts to the ' . $rank . 'th value and reports ' . $valAtP . ' minutes.</li>
        <li><b>State What the Percentile Means: NOT earned.</b> It never says that ' . $below . '% of the values are at or below ' . $valAtP . '. This is the only category it misses.</li>
        <li><b>Direction in Context: earned.</b> It says a longer time is the worse end of this scale.</li>
      </ul>
      <p><span class="term-label">Part (c): why the meaning is its own category.</span> A percentile is not a score and it is not a rank out of a hundred. It is a statement about <i>share</i>: ' . $below . '% of these ' . $who . 's came in at or under ' . $valAtP . ' minutes. Without that sentence the reader has a number and a verdict and no idea what the number counts: which is exactly how "' . $p . 'th percentile" gets read as "' . $p . '% correct".</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the lab and on the test this scenario comes with a blank box and this same checklist. The meaning is the category most often missing, because the definition feels too obvious to write down once you have found the number.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> A $role recorded $setting.</p>
    $dataBlock
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> One $who is at the {$p}th percentile. Find that value, explain what the percentile means, and say what it tells the $role.</p>
  </div>
  $rubric
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 4px 0;"><b>Four students answered.</b></p>
    $responses
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which response earns <b>full credit</b> on all three categories? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noMeaningLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is finding the value and judging it good or bad enough on its own to cover what the percentile means? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
