// === NAME - DESCRIPTION: Union and Neither from a Two-Way Table - Count the union directly off the table, check it against the addition rule, and find the probability of neither event ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 3.4. A two-way table is the one place a student can SEE why the addition rule subtracts
// the overlap: three of the four cells are the union, and the fourth is "neither". Part (c) makes
// them get the same number a second way, from the formula, so the subtraction stops being a rule
// they were told and becomes a count they can verify.
$anstypes = array("numfunc", "numfunc", "numfunc")

$i = rand(0, 2)

$contexts = array(
  "180 households surveyed about owning a car and owning a bicycle",
  "180 job applicants recorded by whether they had a referral and whether they had prior experience",
  "180 photographs tagged by whether they were taken outdoors and whether they were taken at night"
)
$context = $contexts[$i]

$rowY = array("Owns a car", "Had a referral", "Outdoors")
$rowN = array("No car", "No referral", "Indoors")
$colY = array("Owns a bicycle", "Prior experience", "At night")
$colN = array("No bicycle", "No experience", "Daytime")

$rY = $rowY[$i]
$rN = $rowN[$i]
$cY = $colY[$i]
$cN = $colN[$i]

$grand = 180
$a = 6 * rand(4, 9)
$b = 6 * rand(4, 9)
$c = 6 * rand(3, 8)
$d = $grand - $a - $b - $c
$r1 = $a + $b
$r2 = $c + $d
$c1 = $a + $c
$c2 = $b + $d

$union = $a + $b + $c
$pUnion = $union / $grand
$pNeither = $d / $grand
$pRow = $r1 / $grand
$pCol = $c1 / $grand
$pJoint = $a / $grand

$answer[0] = $pUnion
$answer[1] = $pNeither
$answer[2] = $pUnion

$sol = '
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
    <summary><span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span> Step-by-Step Solution</summary>
    <div class="sol-body">
      <p><span class="term-label">(a) The union is three cells.</span> Everything except the corner where NEITHER happens: `' . $a . ' + ' . $b . ' + ' . $c . ' = ' . $union . '`, so `' . $union . ' -: ' . $grand . ' = ' . $pUnion . '`.</p>
      <p><span class="term-label">(b) Neither is the fourth cell.</span> `' . $d . ' -: ' . $grand . ' = ' . $pNeither . '`. Notice (a) and (b) add to 1 &mdash; they are complements, because every entry is in exactly one of the four cells.</p>
      <p><span class="term-label">(c) The formula, and why it subtracts.</span> `P(A) + P(B) = ' . $pRow . ' + ' . $pCol . '`. That counts the ' . $a . ' in the overlap TWICE &mdash; once in the row and once in the column &mdash; so one copy comes back off: `- ' . $pJoint . '`. The result is ' . $pUnion . ', the same number you counted in (a).</p>
      <p><span class="term-label">This is the point of the section.</span> The addition rule is not a formula to memorise; it is bookkeeping for a double count you can point at in the grid. If (a) and (c) disagree, one of them used the wrong cells &mdash; and the table will show you which.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">$context.</p>
    <table style="border-collapse:collapse; margin:10px 0; font-size:15px;">
      <tr><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff;"></th><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff;">$cY</th><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff;">$cN</th><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff;">Total</th></tr>
      <tr><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff;">$rY</th><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$a</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$b</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$r1</td></tr>
      <tr><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff;">$rN</th><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$c</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$d</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$r2</td></tr>
      <tr><th style="border:1px solid #d1d5db; padding:6px 14px; background:#f0f4ff;">Total</th><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$c1</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$c2</td><td style="border:1px solid #d1d5db; padding:6px 14px; text-align:center;">$grand</td></tr>
    </table>
    <p style="margin:6px 0 0 0; color:#374151; font-size:14px;">Enter each probability as a fraction or a decimal rounded to 4 places.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> <b>P($rY or $cY)</b>, counted straight off the table. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> <b>P(neither)</b> &mdash; not $rY and not $cY. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Now get part (a) a second way, from <b>P(A) + P(B) &minus; P(A and B)</b>. It must match. $answerbox[2]
  </div>
</div>

// === ANSWER ===

$sol
