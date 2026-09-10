// === NAME - DESCRIPTION: Label the Bins from a Class Width - Fill in the missing class boundary labels of a partly-completed grouped frequency table, then say which class a boundary-sitting value belongs to and why ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number", "number", "number", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $intro = "A gym is setting up a grouped frequency table for the number of minutes each of its members spent on the treadmill yesterday."
  $unitWord = "minutes"
}
else {
  $intro = "A teacher is setting up a grouped frequency table for the number of pages each student read over the weekend."
  $unitWord = "pages"
}

$lo0 = 10 * rand(1, 4)
$w = rand(4, 8)

$b0 = $lo0
$b1 = $lo0 + $w
$b2 = $lo0 + 2 * $w
$b3 = $lo0 + 3 * $w
$b4 = $lo0 + 4 * $w
$b5 = $lo0 + 5 * $w
$e0 = $b1 - 1
$e1 = $b2 - 1
$e2 = $b3 - 1
$e3 = $b4 - 1
$e4 = $b5 - 1

$lab1 = $b0 . '&ndash;' . $e0
$lab3 = $b2 . '&ndash;' . $e2
$lab5 = $b4 . '&ndash;' . $e4

// Row 2 and row 4 are always the two blanked rows: which rows are blank never changes with the
// seed, only the values do, so the answer-box count stays fixed.
$answer[0] = $b1
$answerformat[0] = "integer"
$answer[1] = $e1
$answerformat[1] = "integer"
$answer[2] = $b3
$answerformat[2] = "integer"
$answer[3] = $e3
$answerformat[3] = "integer"

// A recorded value planted exactly on one of the internal boundaries: by construction it always
// equals the LOWER bound of the class it belongs to, which is the whole point being tested. A
// student who thinks the value belongs to the class ABOVE it (the one ending just below the
// boundary) will answer one class width too low.
$checkX = rand(1, 4)
$checkVal = $lo0 + $checkX * $w
$wrongLo = $checkVal - $w

$answer[4] = $checkVal
$answerformat[4] = "integer"

$questions[5] = array(
  "The rule is that each class holds its left endpoint and not its right, so the class that starts at that number claims it, and the class ending just below it does not.",
  "The larger of the two class numbers is always the correct one when a value sits on a boundary.",
  "It does not actually matter, since both classes would count the value the same way in the total.",
  "Boundary values are always placed in whichever class already has fewer measurements."
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
      <p><span class="term-label">Setting it up.</span> Every class is $w $unitWord wide, and each one starts where the previous one ends. Class 1 runs ' . $lab1 . ', so class 2 starts right after class 1 ends: at <b>' . $b1 . '</b>.</p>
      <p><span class="term-label">Row 2: ' . $b1 . '&ndash;' . $e1 . '.</span> The class starts at <b>' . $b1 . '</b> and, since it is $w $unitWord wide, runs up through <b>' . $e1 . '</b>, one short of where class 3 begins at ' . $b2 . '.</p>
      <p><span class="term-label">Row 4: ' . $b3 . '&ndash;' . $e3 . '.</span> The same pattern: class 4 starts at <b>' . $b3 . '</b> and runs up through <b>' . $e3 . '</b>.</p>
      <p><span class="term-label">Part (e): where ' . $checkVal . ' belongs.</span> ' . $checkVal . ' sits exactly on the line between two classes. By the rule that a class holds its left endpoint and not its right, ' . $checkVal . ' belongs to the class that STARTS there, whose lower bound is <b>' . $checkVal . '</b>, not the class ' . $wrongLo . '&ndash;' . ($checkVal - 1) . ', which ends one below it.</p>
      <p><span class="term-label">Part (f): why.</span> If a boundary value could belong to either class, every table would need a tie-breaking rule of its own. Fixing the rule once, left endpoint in and right endpoint out, means every class in every table is decided the same way, and no measurement is ever double-counted or dropped.</p>
      <p><b>Answer:</b> (a) ' . $b1 . ' &nbsp;&nbsp; (b) ' . $e1 . ' &nbsp;&nbsp; (c) ' . $b3 . ' &nbsp;&nbsp; (d) ' . $e3 . ' &nbsp;&nbsp; (e) ' . $checkVal . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$intro Classes are $w $unitWord wide, starting at $lo0. Each class holds its left endpoint and not its right, so a value equal to a boundary belongs to the class that starts there.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <table style="border-collapse:collapse; margin:0; background:#fff;">
      <tr style="background:#f0f4ff;">
        <th style="border:1px solid #d1d5db; padding:6px 16px;">Class</th>
        <th style="border:1px solid #d1d5db; padding:6px 16px;">Lower bound</th>
        <th style="border:1px solid #d1d5db; padding:6px 16px;">Upper bound</th>
        <th style="border:1px solid #d1d5db; padding:6px 10px; font-size:13px; color:#6b7280;">Part</th>
      </tr>
      <tr><td style="border:1px solid #d1d5db; padding:8px 16px; text-align:center;">1</td><td style="border:1px solid #d1d5db; padding:8px 16px; text-align:center;" colspan="2">$lab1</td><td style="border:1px solid #d1d5db; padding:8px 10px; text-align:center; font-size:13px; color:#6b7280;">given</td></tr>
      <tr><td style="border:1px solid #d1d5db; padding:8px 16px; text-align:center;">2</td><td style="border:1px solid #d1d5db; padding:8px 16px;">$answerbox[0]</td><td style="border:1px solid #d1d5db; padding:8px 16px;">$answerbox[1]</td><td style="border:1px solid #d1d5db; padding:8px 10px; text-align:center; font-size:13px; color:#6b7280;">a. / b.</td></tr>
      <tr><td style="border:1px solid #d1d5db; padding:8px 16px; text-align:center;">3</td><td style="border:1px solid #d1d5db; padding:8px 16px; text-align:center;" colspan="2">$lab3</td><td style="border:1px solid #d1d5db; padding:8px 10px; text-align:center; font-size:13px; color:#6b7280;">given</td></tr>
      <tr><td style="border:1px solid #d1d5db; padding:8px 16px; text-align:center;">4</td><td style="border:1px solid #d1d5db; padding:8px 16px;">$answerbox[2]</td><td style="border:1px solid #d1d5db; padding:8px 16px;">$answerbox[3]</td><td style="border:1px solid #d1d5db; padding:8px 10px; text-align:center; font-size:13px; color:#6b7280;">c. / d.</td></tr>
      <tr><td style="border:1px solid #d1d5db; padding:8px 16px; text-align:center;">5</td><td style="border:1px solid #d1d5db; padding:8px 16px; text-align:center;" colspan="2">$lab5</td><td style="border:1px solid #d1d5db; padding:8px 10px; text-align:center; font-size:13px; color:#6b7280;">given</td></tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">a.&ndash;d.</span> Fill in the missing lower and upper bounds for class 2 and class 4 in the table above.
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">e.</span> A recorded value of <b>$checkVal</b> $unitWord sits exactly on the line between two classes. Which class does it belong to? Enter that class's <b>lower bound</b>. $answerbox[4]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px;">f.</span> Why does a boundary value always belong to the class it starts, rather than the class it ends? $answerbox[5]
  </div>
</div>

// === ANSWER ===

$solutionguide
