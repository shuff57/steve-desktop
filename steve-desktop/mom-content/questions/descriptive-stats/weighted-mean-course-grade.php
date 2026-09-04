// === NAME - DESCRIPTION: Weighted Mean of a Course Grade - Build the contribution column of a weighted mean, total it, and say why the answer differs from the plain average of the four category scores ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// 2.5 had no weighted mean at all. The thing the student BUILDS here is the contribution column:
// once every category's weight times score is on the page, the weighted mean is just that column's
// total. Entering the column is doing the calculation rather than reciting a formula.
//
// The four weights are 10/20/30/40 in a rotated order, so they always sum to 100 and are always
// distinct: the weighted mean can never coincide with the plain average by luck of the seed, which
// is the comparison part (f) turns on. Scores are multiples of 5, so weight x score / 100 always
// lands on a half, and nothing the student types needs rounding.
$anstypes = array("number", "number", "number", "number", "number", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $course = "statistics"
  $c0 = "Homework"
  $c1 = "Quizzes"
  $c2 = "Labs"
  $c3 = "Final exam"
}
else {
  $course = "chemistry"
  $c0 = "Problem sets"
  $c1 = "Lab reports"
  $c2 = "Midterms"
  $c3 = "Final exam"
}

$wSet = array(10, 20, 30, 40)
$rot = rand(0, 3)
$w0 = $wSet[$rot]
$w1 = $wSet[($rot + 1) % 4]
$w2 = $wSet[($rot + 2) % 4]
$w3 = $wSet[($rot + 3) % 4]

$s0 = 5 * rand(12, 19)
$s1 = 5 * rand(12, 19)
$s2 = 5 * rand(12, 19)
$s3 = 5 * rand(12, 19)

$p0 = $w0 * $s0 / 100
$p1 = $w1 * $s1 / 100
$p2 = $w2 * $s2 / 100
$p3 = $w3 * $s3 / 100
$wmean = $p0 + $p1 + $p2 + $p3

$plain = ($s0 + $s1 + $s2 + $s3) / 4
$plainShown = round($plain, 2)

$answer[0] = $p0
$answer[1] = $p1
$answer[2] = $p2
$answer[3] = $p3
$answer[4] = $wmean
for ($k=0..4) {
  $abstolerance[$k] = 0.005
}
$answerboxsize = 6

$questions[5] = array(
  "The plain average treats all four categories as equally important. The weighted mean counts each one according to its weight, so the categories worth more move the result more.",
  "The plain average is wrong because it does not divide by the number of categories.",
  "They differ only because of rounding; with enough decimal places the two agree.",
  "The weighted mean is always larger than the plain average, because the weights add to 100."
)
$answer[5] = 0

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
      <p><span class="term-label">Step 1: one row at a time.</span> A category worth ' . $w0 . '% of the grade contributes ' . $w0 . '% OF its score, not the whole score. Multiply the score by the weight written as a decimal:</p>
      <table style="border-collapse:collapse; margin:8px 0;">
        <tr style="background:#f0f4ff;"><th style="border:1px solid #d1d5db; padding:5px 14px; text-align:left;">Category</th><th style="border:1px solid #d1d5db; padding:5px 14px;">Weight</th><th style="border:1px solid #d1d5db; padding:5px 14px;">Score</th><th style="border:1px solid #d1d5db; padding:5px 14px;">Contribution</th></tr>
        <tr><td style="border:1px solid #d1d5db; padding:5px 14px;">' . $c0 . '</td><td style="border:1px solid #d1d5db; padding:5px 14px; text-align:center;">' . $w0 . '%</td><td style="border:1px solid #d1d5db; padding:5px 14px; text-align:center;">' . $s0 . '</td><td style="border:1px solid #d1d5db; padding:5px 14px; text-align:center;">0.' . $w0 . ' &times; ' . $s0 . ' = <b>' . $p0 . '</b></td></tr>
        <tr><td style="border:1px solid #d1d5db; padding:5px 14px;">' . $c1 . '</td><td style="border:1px solid #d1d5db; padding:5px 14px; text-align:center;">' . $w1 . '%</td><td style="border:1px solid #d1d5db; padding:5px 14px; text-align:center;">' . $s1 . '</td><td style="border:1px solid #d1d5db; padding:5px 14px; text-align:center;">0.' . $w1 . ' &times; ' . $s1 . ' = <b>' . $p1 . '</b></td></tr>
        <tr><td style="border:1px solid #d1d5db; padding:5px 14px;">' . $c2 . '</td><td style="border:1px solid #d1d5db; padding:5px 14px; text-align:center;">' . $w2 . '%</td><td style="border:1px solid #d1d5db; padding:5px 14px; text-align:center;">' . $s2 . '</td><td style="border:1px solid #d1d5db; padding:5px 14px; text-align:center;">0.' . $w2 . ' &times; ' . $s2 . ' = <b>' . $p2 . '</b></td></tr>
        <tr><td style="border:1px solid #d1d5db; padding:5px 14px;">' . $c3 . '</td><td style="border:1px solid #d1d5db; padding:5px 14px; text-align:center;">' . $w3 . '%</td><td style="border:1px solid #d1d5db; padding:5px 14px; text-align:center;">' . $s3 . '</td><td style="border:1px solid #d1d5db; padding:5px 14px; text-align:center;">0.' . $w3 . ' &times; ' . $s3 . ' = <b>' . $p3 . '</b></td></tr>
      </table>
      <p><span class="term-label">Step 2: total the column.</span> `' . $p0 . ' + ' . $p1 . ' + ' . $p2 . ' + ' . $p3 . ' = ` <b>' . $wmean . '</b>. Because the weights add to 100%, that total IS the weighted mean: there is no second division at the end. That is the step most often added by mistake.</p>
      <p><span class="term-label">Step 3: compare with the plain average.</span> Adding the four scores and dividing by 4 gives ' . $plainShown . ', which is a different number. The plain average is the special case where every weight is the same. Here they are not: ' . $c3 . ' is worth ' . $w3 . '% and ' . $c0 . ' only ' . $w0 . '%, so those two scores do not get an equal say.</p>
      <p><span class="term-label">The check worth doing.</span> A weighted mean always lands between the smallest and the largest score. If your answer sits outside that range, a weight was used as a whole number instead of a decimal.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A $course course sets the final grade from four categories. The weights and one student's scores are below.</p>
    <p style="margin:12px 0 0 0;"><b>Build the contribution column</b>, then total it to get the weighted mean. Enter each contribution as a decimal.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <table style="border-collapse:collapse; margin:0; background:#fff;">
      <tr style="background:#f0f4ff;">
        <th style="border:1px solid #d1d5db; padding:6px 14px; text-align:left;">Category</th>
        <th style="border:1px solid #d1d5db; padding:6px 14px;">Weight</th>
        <th style="border:1px solid #d1d5db; padding:6px 14px;">Score</th>
        <th style="border:1px solid #d1d5db; padding:6px 14px;">Contribution</th>
        <th style="border:1px solid #d1d5db; padding:6px 10px; font-size:13px; color:#6b7280;">Part</th>
      </tr>
      <tr><td style="border:1px solid #d1d5db; padding:8px 14px;">$c0</td><td style="border:1px solid #d1d5db; padding:8px 14px; text-align:center;">$w0%</td><td style="border:1px solid #d1d5db; padding:8px 14px; text-align:center;">$s0</td><td style="border:1px solid #d1d5db; padding:8px 14px;">$answerbox[0]</td><td style="border:1px solid #d1d5db; padding:8px 10px; text-align:center; font-size:13px; color:#6b7280;">a.</td></tr>
      <tr><td style="border:1px solid #d1d5db; padding:8px 14px;">$c1</td><td style="border:1px solid #d1d5db; padding:8px 14px; text-align:center;">$w1%</td><td style="border:1px solid #d1d5db; padding:8px 14px; text-align:center;">$s1</td><td style="border:1px solid #d1d5db; padding:8px 14px;">$answerbox[1]</td><td style="border:1px solid #d1d5db; padding:8px 10px; text-align:center; font-size:13px; color:#6b7280;">b.</td></tr>
      <tr><td style="border:1px solid #d1d5db; padding:8px 14px;">$c2</td><td style="border:1px solid #d1d5db; padding:8px 14px; text-align:center;">$w2%</td><td style="border:1px solid #d1d5db; padding:8px 14px; text-align:center;">$s2</td><td style="border:1px solid #d1d5db; padding:8px 14px;">$answerbox[2]</td><td style="border:1px solid #d1d5db; padding:8px 10px; text-align:center; font-size:13px; color:#6b7280;">c.</td></tr>
      <tr><td style="border:1px solid #d1d5db; padding:8px 14px;">$c3</td><td style="border:1px solid #d1d5db; padding:8px 14px; text-align:center;">$w3%</td><td style="border:1px solid #d1d5db; padding:8px 14px; text-align:center;">$s3</td><td style="border:1px solid #d1d5db; padding:8px 14px;">$answerbox[3]</td><td style="border:1px solid #d1d5db; padding:8px 10px; text-align:center; font-size:13px; color:#6b7280;">d.</td></tr>
      <tr style="background:#f8fafc;"><td style="border:1px solid #d1d5db; padding:8px 14px;" colspan="3"><b>Weighted mean</b></td><td style="border:1px solid #d1d5db; padding:8px 14px;">$answerbox[4]</td><td style="border:1px solid #d1d5db; padding:8px 10px; text-align:center; font-size:13px; color:#6b7280;">e.</td></tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">f.</span> Adding the four scores and dividing by 4 gives a different number. Why? $answerbox[5]
  </div>
</div>

// === ANSWER ===

$solutionguide
