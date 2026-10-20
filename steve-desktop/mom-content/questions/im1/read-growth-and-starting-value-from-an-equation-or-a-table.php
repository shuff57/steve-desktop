// === NAME - DESCRIPTION: Read Growth and Starting Value from an Equation or a Table - Find both numbers, then use them to count the tiles in a far figure ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 2.1 (2.1.3, 2.1.4). The pattern is shown either as an equation or as a table of
// Figures 0 to 4, chosen by $mode. Growth g and starting value s are different on every draw
// (s is moved off g), so the answer to part c is never just g times the figure number. N is 12 to 80.
$anstypes = array("number", "number", "number")

$g = rand(2, 9)
$s = rand(2, 9)
if ($s == $g) {
  $s = $g + 1
}
$mode = rand(0, 1)
$N = rand(12, 80)
$total = $g * $N + $s

$t = array($s, $s + $g, $s + 2 * $g, $s + 3 * $g, $s + 4 * $g)
$given = "A tile pattern is described by the equation `y = " . $g . "x + " . $s . "`. Here x is the figure number and y is the number of tiles."
$tbl = ""
$how = "Read both numbers from the equation. The growth is the coefficient of x and the starting value is the constant added on."
if ($mode == 1) {
  $given = "The table shows the number of tiles in Figures 0 to 4 of a tile pattern. The growth is the same from one figure to the next."
  $tbl = horizshowarrays("Figure", array(0, 1, 2, 3, 4), "Tiles", $t)
  $how = "Read both numbers from the table. The growth is the jump between consecutive entries and the starting value is the Figure 0 entry."
}

$answer[0] = $g
$answer[1] = $s
$answer[2] = $total
$answerboxsize[0] = 6
$answerboxsize[1] = 6
$answerboxsize[2] = 8
$abstolerance[0] = 0.001
$abstolerance[1] = 0.001
$abstolerance[2] = 0.001

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
      <p><span class="term-label">(a) and (b).</span> ' . $how . ' The growth is ' . $g . ' tiles per figure and the starting value is ' . $s . ' tiles. Check: Figure 1 has ' . ($s + $g) . ' tiles and Figure 0 has ' . $s . ', and the difference is `' . ($s + $g) . ' - ' . $s . ' = ' . $g . '`.</p>
      <p><span class="term-label">(c) Figure ' . $N . '.</span> The equation is `y = ' . $g . 'x + ' . $s . '`. Substitute x = ' . $N . ': `y = ' . $g . '(' . $N . ') + ' . $s . ' = ' . ($g * $N) . ' + ' . $s . ' = ' . $total . '` tiles.</p>
      <p><span class="term-label">Watch for.</span> ' . ($g * $N) . ' is only the growth part. The pattern already had ' . $s . ' tiles before Figure 0, so every figure holds those too.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$given</p>
    <div style="margin:10px 0;">$tbl</div>
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Enter whole numbers.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the growth, in tiles per figure? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the starting value, the number of tiles in Figure 0? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> How many tiles are in Figure $N? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$sol
