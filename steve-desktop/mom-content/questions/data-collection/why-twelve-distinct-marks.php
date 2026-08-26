// === NAME - DESCRIPTION: Why Twelve Distinct Marks - a 24-name list and a step of 4 visit only 6 names before the pattern repeats ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices")

// Fixed counter-case from the lab: 24 names, start 7, step 4.
// gcd(24, 4) = 4, so the walk visits 24/4 = 6 names before cycling.
$N = 24
$s = 7
$step = 4
$six = 6

$questions[0] = array(
  "6",
  "12",
  "24",
  "3"
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  "The list size and the step size share a common factor of 4, so stepping by 4 revisits the same names on a short cycle of $six.",
  "Because 12 marks are being made and 12 fits 24 evenly, so the marks pair up.",
  "The starting point 7 is odd, and odd starts break the counting.",
  "Any even-numbered list always cycles, regardless of the step."
)
$answer[1] = 0
$noshuffle[1] = "all"

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
      <p><span class="term-label">Step 1 &mdash; walk the list.</span> Start at ' . $s . ' and add ' . $step . ' each time, wrapping past ' . $N . ' back to the top: 7, 11, 15, 19, 23, 3, and the next step lands on 7 again.</p>
      <p><span class="term-label">Step 2 &mdash; count the distinct names.</span> Before the first repeat you have marked only 6 different people, not 12.</p>
      <p><span class="term-label">Step 3 &mdash; why.</span> ' . $N . ' and ' . $step . ' share the common factor ' . $step . ', so stepping by ' . $step . ' visits only ' . $N . '/' . $step . ' = 6 names before cycling. That is the exact trap the lab warns about: when the list size and the step share a common factor, the same people get sampled over and over, and you do not have 12 independent picks.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A class list holds $N names. You randomly pick name number $s as the start, then step down 4 names at a time, wrapping back to the top, until you have 12 marks.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 8px 0;"><b>a.</b> How many <b>different</b> names get marked before a name repeats? $answerbox[0]</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 8px 0;"><b>b.</b> Why does this happen? $answerbox[1]</p>
  </div>
</div>


// === ANSWER ===

$solutionguide