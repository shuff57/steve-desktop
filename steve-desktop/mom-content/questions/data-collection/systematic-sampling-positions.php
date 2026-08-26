// === NAME - DESCRIPTION: Systematic Sampling Positions - mark the 12 positions on a numbered list by stepping by 4 from a random start, wrapping back to the top at the bottom ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("ntuple")

// N odd, so gcd(N, 4) = 1 and 12 distinct marks are guaranteed.
$N = randfrom("21,23,25,27,29")
$s = rand(1, $N)
$step = 4

$seq = $s
$wraps = 0
$p = $s
for ($i = 1..11) {
  $p = $p + $step
  if ($p > $N) {
    $p = $p - $N
    $wraps = $wraps + 1
  }
  $seq = $seq . "," . $p
}
$answer[0] = "{" . $seq . "}"
$displayformat[0] = "set"
$answerformat[0] = "anyorder"

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
      <p><span class="term-label">Step 1 &mdash; set up the rule.</span> Mark position ' . $s . ', then add ' . $step . ' each time. The only random decision you ever make is that first start; everything after it is arithmetic.</p>
      <p><span class="term-label">Step 2 &mdash; count forward, wrapping at ' . $N . '.</span> Each time a position would run past ' . $N . ', subtract ' . $N . ' to wrap back to the top. This particular set wraps ' . $wraps . ' times.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Positions: ' . $seq . '
      </div>
      <p><span class="term-label">Step 3 &mdash; check that nobody got marked twice.</span> All 12 positions are different, which is what you want &mdash; 12 different classmates giving 5 values each is the 60 data values the lab asks for. It works because ' . $N . ' and 4 share no common factor, so stepping by 4 walks through the whole list before it ever returns to the start.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A class list has $N names, numbered 1 through $N. You randomly pick name number $s as your starting point, then move down 4 names each time until you have marked 12 names, wrapping back to the top of the list when you run off the bottom.</p>
    <p style="margin:0.5em 0 0 0;">Enter the <b>12 positions</b> you mark, as a set such as <code>{1,5,9,13}</code>. <b>Order does not matter.</b></p>
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide