// === NAME - DESCRIPTION: Test a Table for Constant Growth - Pick the linear tile pattern from two tables, then find its growth, starting value and a far figure ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.1 (2.1.2, 2.1.3). Two patterns share Figures 1 and 2, so checking only the first jump
// cannot decide which is linear. The linear one has growth g and starting value s, with s at least 2,
// so stepping back from Figure 1 never gives a negative tile count. The other pattern's jumps are
// g, g+e, g+2e with e from 1 to 3. The far figure N is 20 to 60.
$anstypes = array("choices", "number", "number", "number")

$g = rand(2, 9)
$s = rand(2, 8)
$e = rand(1, 3)
$c1 = $g + $s
$c2 = $c1 + $g
$l3 = $c2 + $g
$l4 = $l3 + $g
$n3 = $c2 + $g + $e
$n4 = $n3 + $g + 2 * $e

$lin = rand(0, 1)
$pr = array($c1, $c2, $l3, $l4)
$ps = array($c1, $c2, $n3, $n4)
$linName = "Pattern R"
$jumpsLin = "+" . $g . ", +" . $g . ", +" . $g
$jumpsOther = "+" . $g . ", +" . ($g + $e) . ", +" . ($g + 2 * $e)
$otherName = "Pattern S"
if ($lin == 1) {
  $pr = array($c1, $c2, $n3, $n4)
  $ps = array($c1, $c2, $l3, $l4)
  $linName = "Pattern S"
  $otherName = "Pattern R"
}
$tbl = horizshowarrays("Figure", array(1, 2, 3, 4), "Pattern R", $pr, "Pattern S", $ps)

$N = rand(20, 60)
$total = $g * $N + $s

$questions[0] = array("Pattern R", "Pattern S")
$noshuffle[0] = "all"
$displayformat[0] = "select"
$answer[0] = $lin
$answer[1] = $g
$answer[2] = $s
$answer[3] = $total
$answerboxsize[1] = 6
$answerboxsize[2] = 6
$answerboxsize[3] = 8
$abstolerance[1] = 0.001
$abstolerance[2] = 0.001
$abstolerance[3] = 0.001

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
      <p><span class="term-label">(a) Compute the jumps.</span> ' . $linName . ' jumps ' . $jumpsLin . '. The growth is the same every time, so ' . $linName . ' is linear. ' . $otherName . ' jumps ' . $jumpsOther . '. The growth keeps changing, so ' . $otherName . ' is not linear. The two patterns match at Figures 1 and 2, so only the third figure tells them apart.</p>
      <p><span class="term-label">(b) Growth.</span> The growth of ' . $linName . ' is `' . $c2 . ' - ' . $c1 . ' = ' . $g . '` tiles per figure.</p>
      <p><span class="term-label">(c) Starting value.</span> Figure 0 is one growth before Figure 1: `' . $c1 . ' - ' . $g . ' = ' . $s . '` tiles.</p>
      <p><span class="term-label">(d) Figure ' . $N . '.</span> The equation is `y = ' . $g . 'x + ' . $s . '`. Then `y = ' . $g . '(' . $N . ') + ' . $s . ' = ' . ($g * $N) . ' + ' . $s . ' = ' . $total . '` tiles.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">The table shows the number of tiles in Figures 1 to 4 of two tile patterns. Exactly one of them is linear.</p>
    <div style="margin:10px 0;">$tbl</div>
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Parts b, c and d are about the linear pattern. Enter whole numbers.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which pattern is linear? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the growth, in tiles per figure? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is the starting value, the number of tiles in Figure 0? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> How many tiles are in Figure $N? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$sol
