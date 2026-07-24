// === NAME - DESCRIPTION: Venn 2-Circle Inclusion-Exclusion - Given total, |A|, |B|, |A∩B|: find |A∪B| via formula, only-A, only-B, neither, and P(A) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number", "number", "numfunc")

// Randomize disjoint regions first — guarantees all derived counts are positive.
$only_a  = rand(30, 58)
$only_b  = rand(22, 48)
$both    = rand(10, 28)
$neither = rand(15, 38)

$sizeA = $only_a + $both
$sizeB = $only_b + $both
$union = $only_a + $only_b + $both
$total = $union + $neither

// Answers
$answer[0] = $union
$answer[1] = $only_a
$answer[2] = $only_b
$answer[3] = $neither
// Part (e): P(A) = sizeA / total — numfunc with tolerance 0.005
$answer[4] = $sizeA / $total
$answerformat[4] = "0.005"

// === Scenario picker ===
// Each scenario: labelA, labelB, unit, scene
$labels_a = array("Drama Club", "AP Biology", "vegetarian", "yoga")
$labels_b = array("Math Team", "AP Chemistry", "gluten-free", "running")
$units    = array("students", "students", "diners", "gym members")
$scenes   = array(
  "A high school surveyed all $total students about which extracurricular clubs they belong to.",
  "A school counselor surveyed all $total juniors about which AP science courses they are enrolled in.",
  "A campus dining hall surveyed $total diners about their dietary preferences.",
  "A fitness center surveyed all $total members about which workout activities they do regularly."
)

$ci     = rand(0, 3)
$labelA = $labels_a[$ci]
$labelB = $labels_b[$ci]
$unit   = $units[$ci]
$scene  = $scenes[$ci]

// P(A) display for solution — precompute a rounded string to avoid function calls in solution text
$prob_num = $sizeA
$prob_den = $total

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
      <p>Let <b>A = &ldquo;'.$labelA.'&rdquo;</b> and <b>B = &ldquo;'.$labelB.'&rdquo;</b>. Given: |A| = '.$sizeA.', |B| = '.$sizeB.', |A &cap; B| = '.$both.', total = '.$total.'.</p>
      <table>
        <tr><th>Part</th><th>Formula</th><th>Calculation</th><th>Answer</th></tr>
        <tr><td><b>(a)</b> |A &cup; B|</td><td>|A| + |B| &minus; |A &cap; B|</td><td>'.$sizeA.' + '.$sizeB.' &minus; '.$both.'</td><td><b>'.$union.'</b></td></tr>
        <tr><td><b>(b)</b> A only</td><td>|A| &minus; |A &cap; B|</td><td>'.$sizeA.' &minus; '.$both.'</td><td><b>'.$only_a.'</b></td></tr>
        <tr><td><b>(c)</b> B only</td><td>|B| &minus; |A &cap; B|</td><td>'.$sizeB.' &minus; '.$both.'</td><td><b>'.$only_b.'</b></td></tr>
        <tr><td><b>(d)</b> Neither</td><td>Total &minus; |A &cup; B|</td><td>'.$total.' &minus; '.$union.'</td><td><b>'.$neither.'</b></td></tr>
        <tr><td><b>(e)</b> P(A)</td><td>|A| / Total</td><td>'.$sizeA.' / '.$total.'</td><td><b>'.$sizeA.' / '.$total.'</b></td></tr>
      </table>
      <p><b>Venn diagram check:</b> only-A + both + only-B + neither = '.$only_a.' + '.$both.' + '.$only_b.' + '.$neither.' = '.$total.'. &check;</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        (a) |A &cup; B| = <b>'.$union.'</b> &nbsp;&bull;&nbsp;
        (b) A only = <b>'.$only_a.'</b> &nbsp;&bull;&nbsp;
        (c) B only = <b>'.$only_b.'</b> &nbsp;&bull;&nbsp;
        (d) Neither = <b>'.$neither.'</b> &nbsp;&bull;&nbsp;
        (e) P(A) = '.$sizeA.' / '.$total.'
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 6px 0;">$scene</p>
    <ul style="margin:0; padding-left:1.4em;">
      <li><b>$sizeA</b> $unit are in $labelA</li>
      <li><b>$sizeB</b> $unit are in $labelB</li>
      <li><b>$both</b> $unit are in both $labelA and $labelB</li>
    </ul>
    <p style="margin:6px 0 0 0;">Let <b>A = &ldquo;$labelA&rdquo;</b> and <b>B = &ldquo;$labelB&rdquo;</b>. Use the inclusion-exclusion principle and a Venn diagram to answer each part.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Use the formula |A &cup; B| = |A| + |B| &minus; |A &cap; B| to find the number of $unit in <b>A &cup; B</b> (at least one of the two).<span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> How many $unit are in <b>$labelA only</b> (not $labelB)?<span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> How many $unit are in <b>$labelB only</b> (not $labelA)?<span style="margin-left:8px;">$answerbox[2]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> How many $unit are in <b>neither</b> $labelA nor $labelB?<span style="margin-left:8px;">$answerbox[3]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">e.</span> What is the probability that a randomly chosen $unit is in <b>$labelA</b>? Give your answer as a fraction or decimal (within 0.005).<span style="margin-left:8px;">$answerbox[4]</span>
  </div>
</div>


// === ANSWER ===

$solutionguide
