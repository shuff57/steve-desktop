// === NAME - DESCRIPTION: Addition Rule with an Overlap - Find P(A), P(B) and P(both) in a group, then P(A or B) by adding and subtracting the overlap ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 3.3. The addition rule on a group where two activities overlap. Adding the two counts
// counts the overlap twice, so subtract it once. N, S, M and B are chosen so the union stays below N.
$anstypes = array("numfunc", "numfunc", "numfunc", "numfunc")

$i = rand(0, 3)
$groups = array("choir", "robotics club", "book club", "hiking club")
$people = array("singers", "members", "members", "members")
$actA = array("play piano", "can code", "read mysteries", "own a tent")
$actB = array("play guitar", "can solder", "read science fiction", "own a stove")
$labA = array("piano", "coding", "mysteries", "tent")
$labB = array("guitar", "soldering", "science fiction", "stove")
$group = $groups[$i]
$person = $people[$i]
$doA = $actA[$i]
$doB = $actB[$i]
$nameA = $labA[$i]
$nameB = $labB[$i]

$N = randfrom(array(30, 36, 40, 45, 50))
$B = rand(3, 8)
$S = $B + rand(3, 9)
$M = $B + rand(3, 9)
$U = $S + $M - $B

$pS = $S / $N
$pM = $M / $N
$pB = $B / $N
$pU = $U / $N

$answer[0] = $pS
$answer[1] = $pM
$answer[2] = $pB
$answer[3] = $pU


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
      <p><span class="term-label">The three simple ones.</span> Count over the whole group of ' . $N . '. `P(' . $nameA . ') = ' . $S . '/' . $N . ' = ' . $pS . '`, `P(' . $nameB . ') = ' . $M . '/' . $N . ' = ' . $pM . '`, and `P(both) = ' . $B . '/' . $N . ' = ' . $pB . '`.</p>
      <p><span class="term-label">Adding the counts.</span> ' . $S . ' + ' . $M . ' = ' . ($S + $M) . ', but the ' . $B . ' who do both are in each count, so they were counted twice. Subtract them once: ' . $S . ' + ' . $M . ' - ' . $B . ' = ' . $U . ' people do at least one.</p>
      <p><span class="term-label">Addition rule.</span> `P(' . $nameA . ' or ' . $nameB . ') = ' . $pS . ' + ' . $pM . ' - ' . $pB . ' = ' . $pU . '`, which is ' . $U . '/' . $N . '.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A $group has $N $person. $S of them $doA, $M of them $doB, and $B do both. One person is picked at random.</p>
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Enter each probability as a fraction or a decimal rounded to 4 places.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> <b>P($nameA)</b> $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> <b>P($nameB)</b> $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> <b>P($nameA and $nameB)</b> $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> <b>P($nameA or $nameB)</b> $answerbox[3]
  </div>
</div>

// === ANSWER ===

$sol
