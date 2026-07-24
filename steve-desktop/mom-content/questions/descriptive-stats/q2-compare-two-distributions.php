// === NAME - DESCRIPTION: Compare Two Distributions - Given summary statistics for two groups, identify which has the larger center and which has the larger spread, and pick the correct verbal comparison ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices", "choices")

// Each scenario: two groups A and B with (mean, sd) summaries.
// Parts: a) which has larger mean, b) which has larger SD, c) which verbal summary best describes the comparison

$cases = array(
  array("Quiz scores", "Class A", "Class B", 78, 6, 82, 12, 1, 1, 1),
  array("Reaction times (ms)", "Group A (caffeinated)", "Group B (placebo)", 245, 22, 268, 25, 1, 1, 0),
  array("Heights (in)", "Boys", "Girls", 70, 3, 65, 4, 0, 1, 2),
  array("Hours of sleep", "Workdays", "Weekends", 6.8, 1.1, 8.4, 1.6, 1, 1, 1),
  array("Wait times (min)", "Branch A", "Branch B", 12, 3, 12, 7, 2, 1, 3)
)
// Fields: [context, labelA, labelB, meanA, sdA, meanB, sdB, larger_mean (0=A,1=B,2=equal), larger_sd (0=A,1=B,2=equal), verbal_idx]
// verbal_idx maps into $verbals below

$verbals = array(
  "Group B has a higher center, and Group B is also more variable (less consistent).",
  "Group B has a higher center, and Group B is more variable (less consistent than Group A).",
  "Group A has a higher center, and Group B is more variable.",
  "The two groups have the same center, but Group B is more variable (less consistent)."
)

$i = rand(0, count($cases)-1)
$ctx     = $cases[$i][0]
$labelA  = $cases[$i][1]
$labelB  = $cases[$i][2]
$mA      = $cases[$i][3]
$sA      = $cases[$i][4]
$mB      = $cases[$i][5]
$sB      = $cases[$i][6]
$answer[0] = $cases[$i][7]
$answer[1] = $cases[$i][8]
$answer[2] = $cases[$i][9]
$verbalChosen = $verbals[$cases[$i][9]]

$choices[0] = array($labelA, $labelB, "They are equal")
$choices[1] = array($labelA, $labelB, "They are equal")
$choices[2] = $verbals
$noshuffle[0] = "all"
$noshuffle[1] = "all"
$noshuffle[2] = "all"

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
      <p>Compare the two means: ' . $labelA . ' = ' . $mA . ', ' . $labelB . ' = ' . $mB . '. The larger mean tells you which distribution is centered higher.</p>
      <p>Compare the two standard deviations: ' . $labelA . ' s = ' . $sA . ', ' . $labelB . ' s = ' . $sB . '. The larger s means more spread / less consistent.</p>
      <p><b>Best verbal summary:</b> ' . $verbalChosen . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">Two groups have the following summary statistics for $ctx:</p>
    <ul style="margin:0 0 0 1.2em;">
      <li><b>$labelA:</b> `bar(x) = $mA`, `s = $sA`</li>
      <li><b>$labelB:</b> `bar(x) = $mB`, `s = $sB`</li>
    </ul>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which group has the <b>larger center</b> (mean)? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which group has the <b>larger standard deviation</b> (more variable)? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Pick the best <b>verbal comparison</b>: $answerbox[2]
  </div>
</div>


// === ANSWER ===

$solutionguide
