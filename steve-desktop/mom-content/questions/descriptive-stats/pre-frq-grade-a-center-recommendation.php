// === NAME - DESCRIPTION: Pre-FRQ Grade a Center Recommendation - The scenario and grading checklist of the choosing-a-measure-of-center FRQ, but the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The pre-FRQ for 2.5. The SAME scenario and the SAME grading checklist as
// frq/descriptive-statistics/q9-choosing-the-right-measure-of-center, with the writing replaced by
// grading. The three response sentences are the FRQ's own target strings, so a student who studies
// this one is reading the exact prose the FRQ rewards.
//
// The dropped category here is OUTLIER IMPACT -- students jump straight to "use the median because
// it resists outliers" and never say what the extreme value actually DOES to the mean. That is the
// diagnosis the recommendation is supposed to rest on, and it reads as redundant once the verdict
// is given. 2.4's pre-FRQ already drops Practical Interpretation, so this one targets a different
// habit rather than teaching the same lesson twice.
$anstypes = array("choices", "multans", "choices")

$i = rand(0, 2)

$role_labels = array("real estate agent", "HR manager", "sports analyst")
$role = $role_labels[$i]

$value_labels = array("home prices", "annual salaries", "contract values")
$value_label = $value_labels[$i]

$singular_labels = array("home price", "salary", "contract value")
$val_singular = $singular_labels[$i]

$item_labels = array("home", "employee", "athlete")
$item_label = $item_labels[$i]

$setting_labels = array("home prices in a neighborhood", "employee salaries at a small company", "athlete contracts for a professional team")
$setting = $setting_labels[$i]

$multipliers = array(1000, 100, 10000)
$mult = $multipliers[$i]

// Six typical values plus one extreme value. The bands must NOT OVERLAP: the question states the
// list is "in order" and takes the median as $v4, and both are false the moment two values can swap.
// The FRQ this mirrors uses overlapping bands (rand(29,32) against rand(31,34)), which on some seeds
// prints an out-of-order list AND reports a median that is not the median -- e.g. v3=300, v4=280,
// v5=290 makes the true median 290 while $v4 says 280. Keep these disjoint.
$v1 = rand(24, 25) * 10
$v2 = rand(26, 27) * 10
$v3 = rand(28, 29) * 10
$v4 = rand(30, 31) * 10
$v5 = rand(32, 33) * 10
$v6 = rand(34, 35) * 10
$outlier = rand(110, 160) * 10

$d1 = $v1 * $mult
$d2 = $v2 * $mult
$d3 = $v3 * $mult
$d4 = $v4 * $mult
$d5 = $v5 * $mult
$d6 = $v6 * $mult
$d_out = $outlier * $mult

$total = $v1 + $v2 + $v3 + $v4 + $v5 + $v6 + $outlier
$mean_raw = round($total / 7)
$median_raw = $v4
$mean_val = $mean_raw * $mult
$median_val = $median_raw * $mult
$gap = $mean_val - $median_val

$val_list = '&#36;' . prettyint($d1) . ', &#36;' . prettyint($d2) . ', &#36;' . prettyint($d3) . ', &#36;' . prettyint($d4) . ', &#36;' . prettyint($d5) . ', &#36;' . prettyint($d6) . ', and &#36;' . prettyint($d_out)

// The FRQ's own target sentences, one per rubric category.
$sImpact = 'The extreme value of &#36;' . prettyint($d_out) . ' pulls the mean up to approximately &#36;' . prettyint($mean_val) . ', which is significantly higher than what most ' . $item_label . 's actually show, so a single extreme observation inflates the average.'
$sRecommend = 'The median of &#36;' . prettyint($median_val) . ' is the better measure of center here because it is resistant to outliers: it reflects the middle value in the sorted data and is not dragged toward extreme observations.'
// Each sentence must be CATEGORY-PURE: it earns its own rubric line and no other. The FRQ's target
// strings cross-reference each other because they are written to flow as one essay, so this one is
// trimmed -- it used to end "while only one extreme figure inflates the mean", which is the Outlier
// Impact requirement verbatim and made the response that drops Outlier Impact still earn it.
$sPractical = 'The ' . $role . ' should therefore report the median of &#36;' . prettyint($median_val) . ' as the typical ' . $val_singular . ', because that is the figure someone dealing with these ' . $value_label . ' would actually encounter.'

$rFull = $sImpact . ' ' . $sRecommend . ' ' . $sPractical
$rNoImpact = $sRecommend . ' ' . $sPractical
$rNoPractical = $sImpact . ' ' . $sRecommend
$rRecommendOnly = $sRecommend . ' That is the measure I would report.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rNoImpact
$rC = $rNoPractical
$rD = $rRecommendOnly
if ($pos == 1) {
  $rA = $rNoImpact
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoPractical
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rRecommendOnly
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$noImpactLabel = "B"
if ($pos == 1) { $noImpactLabel = "A" }

$questions[1] = array(
  "Outlier Impact (3 pts)",
  "Recommendation (4 pts)",
  "Practical Interpretation (3 pts)"
)
$answer[1] = "1,2"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. Naming the median as the better measure is a choice; the rubric also asks what the extreme value DOES to the mean, which is the evidence the choice rests on.",
  "Yes. Saying the median resists outliers already explains the outlier's effect on the mean.",
  "No, but only because the response did not repeat the median's value a second time.",
  "Yes, as long as the median is computed correctly."
)
$answer[2] = 0

$css = '
<style>
  .qscope9 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope9 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope9 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope9 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope9 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope9 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope9 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope9 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope9 .row-colored { background:#fff9ea; }
  .qscope9 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope9 .resp b { color:#1865f2; }
  .qscope9 .datalist { border:1px solid #d1d5db; border-radius:8px; padding:10px 14px; margin:10px 0; background:#f8fafc; font-size:15px; }
</style>'

$rubric = $css . '
<div class="qscope9">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Outlier Impact<br>(3 pts)</b></td>
            <td>Explain how the one extreme value affects the mean.</td></tr>
          <tr><td style="text-align:center;"><b>Recommendation<br>(4 pts)</b></td>
            <td>State whether the mean or the median is the better measure of center, and explain why that measure resists outliers.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Practical Interpretation<br>(3 pts)</b></td>
            <td>Say what the recommended measure tells the audience about a typical ' . $val_singular . ' in this context.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$dataBlock = '
<div class="qscope9">
  <div class="datalist">The seven ' . $value_label . ', in order: ' . $val_list . '<br>
  Mean = &#36;' . prettyint($mean_val) . ' &nbsp;&bull;&nbsp; Median = &#36;' . prettyint($median_val) . '</div>
</div>'

$responses = '
<div class="qscope9">
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
      <p><span class="term-label">Part (a) &mdash; only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> says what the extreme value does to the mean, names the median and explains why it resists outliers, and then says what to report and to whom. Each of the other three drops a whole category.</p>
      <p><span class="term-label">The numbers.</span> Six of the seven ' . $value_label . ' sit between &#36;' . prettyint($d1) . ' and &#36;' . prettyint($d6) . '. The seventh is &#36;' . prettyint($d_out) . '. That one value drags the mean to &#36;' . prettyint($mean_val) . ' while the median stays at &#36;' . prettyint($median_val) . ' &mdash; a gap of &#36;' . prettyint($gap) . ' created by a single observation.</p>
      <p><span class="term-label">Part (b) &mdash; grading Response ' . $noImpactLabel . ' line by line.</span></p>
      <ul>
        <li><b>Outlier Impact &mdash; NOT earned.</b> It never says what the extreme value does to the mean. "Resistant to outliers" describes a property of the median; it does not report the damage. This is the only category it misses.</li>
        <li><b>Recommendation &mdash; earned.</b> It names the median and explains why it resists outliers.</li>
        <li><b>Practical Interpretation &mdash; earned.</b> It says what the ' . $role . ' should report and what it means for a typical ' . $val_singular . '.</li>
      </ul>
      <p><span class="term-label">Part (c) &mdash; why the impact is separate from the recommendation.</span> "Use the median, it resists outliers" is a rule being applied. The rubric also asks for the diagnosis underneath it: here, that &#36;' . prettyint($d_out) . ' pulls the mean to &#36;' . prettyint($mean_val) . ', roughly &#36;' . prettyint($gap) . ' above the middle of the data. Without that, the recommendation is a memorised reflex &mdash; it would be given identically for a dataset with no outlier at all, where it would be wrong.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the lab and on the test this scenario comes with a blank box and this same checklist. Outlier Impact is the category most often missing, because once you have picked the median the diagnosis feels like something you already said.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> A $role is describing $setting and has collected seven values.</p>
    $dataBlock
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> Recommend whether the mean or the median better describes a typical $val_singular here, and explain why.</p>
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
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $noImpactLabel</b> earn? Select every one it earns, and none that it does not. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is recommending the median enough on its own to cover the outlier's impact on the mean? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
