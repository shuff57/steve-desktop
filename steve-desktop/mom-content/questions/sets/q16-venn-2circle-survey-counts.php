// === NAME - DESCRIPTION: Venn 2-Circle Survey Counts - Given total, |A|, |B|, |A∩B|: find only-A, only-B, A∪B, and neither ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc", "numfunc", "numfunc", "numfunc")

// Randomize disjoint regions first, then reconstruct the student-facing givens.
// Keeps all derived answers consistent and positive.
$only_a  = rand(28, 55)
$only_b  = rand(20, 45)
$both    = rand(10, 28)
$neither = rand(12, 35)

$sizeA = $only_a + $both
$sizeB = $only_b + $both
$union = $only_a + $only_b + $both
$total = $union + $neither

$answer[0] = $only_a
$answer[1] = $only_b
$answer[2] = $union
$answer[3] = $neither

// === Scenario picker ===
// Each scenario: label_a, label_b, unit, scene_intro
$labels_a = array("Netflix", "soccer", "Spanish", "dogs")
$labels_b = array("Hulu",    "basketball", "French", "cats")
$units    = array("students", "students", "students", "households")
$scenes   = array(
  "A high school surveyed $total students about their streaming habits.",
  "A recreation center surveyed $total students about the sports they play.",
  "A community college surveyed $total students about which languages they speak at home.",
  "A neighborhood association surveyed $total households about the pets they own."
)

$ci      = rand(0, 3)
$labelA  = $labels_a[$ci]
$labelB  = $labels_b[$ci]
$unit    = $units[$ci]
$scene   = $scenes[$ci]

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
  .sol-wrap table { border-collapse:collapse; margin:0.5em 0; width:100%; }
  .sol-wrap th, .sol-wrap td { border:1px solid #dee1e3; padding:6px 10px; text-align:center; }
  .sol-wrap th { background:#f7f9fa; font-weight:600; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p>Let <b>A = "'.$labelA.'"</b> and <b>B = "'.$labelB.'"</b>. We know |A| = '.$sizeA.', |B| = '.$sizeB.', |A &cap; B| = '.$both.', and the survey total is '.$total.'.</p>
      <p>Fill the Venn diagram from the inside out:</p>
      <table>
        <tr><th>Region</th><th>Formula</th><th>Count</th></tr>
        <tr><td>A &cap; B (both)</td><td>given</td><td><b>'.$both.'</b></td></tr>
        <tr><td>A only</td><td>|A| &minus; |A &cap; B| = '.$sizeA.' &minus; '.$both.'</td><td><b>'.$only_a.'</b></td></tr>
        <tr><td>B only</td><td>|B| &minus; |A &cap; B| = '.$sizeB.' &minus; '.$both.'</td><td><b>'.$only_b.'</b></td></tr>
        <tr><td>A &cup; B</td><td>A only + both + B only = '.$only_a.' + '.$both.' + '.$only_b.'</td><td><b>'.$union.'</b></td></tr>
        <tr><td>Neither</td><td>Total &minus; |A &cup; B| = '.$total.' &minus; '.$union.'</td><td><b>'.$neither.'</b></td></tr>
      </table>
      <p><b>Inclusion-exclusion check:</b> |A &cup; B| = |A| + |B| &minus; |A &cap; B| = '.$sizeA.' + '.$sizeB.' &minus; '.$both.' = '.$union.'. &check;</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        (a) '.$labelA.' only: <b>'.$only_a.'</b> &nbsp;&bull;&nbsp; (b) '.$labelB.' only: <b>'.$only_b.'</b> &nbsp;&bull;&nbsp; (c) A &cup; B: <b>'.$union.'</b> &nbsp;&bull;&nbsp; (d) Neither: <b>'.$neither.'</b>
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 6px 0;">$scene</p>
    <ul style="margin:0; padding-left:1.4em;">
      <li><b>$sizeA</b> $unit like $labelA</li>
      <li><b>$sizeB</b> $unit like $labelB</li>
      <li><b>$both</b> $unit like both $labelA and $labelB</li>
    </ul>
    <p style="margin:6px 0 0 0;">Use a Venn diagram to answer each part. Let <b>A = "$labelA"</b> and <b>B = "$labelB"</b>.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> How many $unit like <b>$labelA only</b> (not $labelB)?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> How many $unit like <b>$labelB only</b> (not $labelA)?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> How many $unit like <b>$labelA or $labelB</b> (at least one)?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> How many $unit like <b>neither</b> $labelA nor $labelB?
    <span style="margin-left:8px;">$answerbox[3]</span>
  </div>
</div>


// === ANSWER ===

$solutionguide
