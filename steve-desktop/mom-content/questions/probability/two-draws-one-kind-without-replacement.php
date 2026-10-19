// === NAME - DESCRIPTION: Two Draws Without Replacement - Use a tree to find one path, exactly one of a kind by adding two paths, and at least one of a kind by the complement ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 3.5. Two items drawn one after the other with the first NOT put back. The point is that the
// second fractions change once the first item is gone. Counts are 2 to 6 of each kind, so the total
// is at most 12 and every probability stays well away from 0.
$anstypes = array("numfunc", "numfunc", "numfunc")

$i = rand(0, 3)
$containers = array("box", "drawer", "stack", "basket")
$objs = array("batteries", "name cards", "name cards", "apples")
$adj1s = array("new", "pink", "yellow", "red")
$adj2s = array("dead", "green", "blue", "green")
$container = $containers[$i]
$obj = $objs[$i]
$sw = rand(0, 1)
if ($sw == 0) {
  $kA = $adj1s[$i]
  $kB = $adj2s[$i]
} else {
  $kA = $adj2s[$i]
  $kB = $adj1s[$i]
}

$nA = rand(2, 6)
$nB = rand(2, 6)
$T = $nA + $nB
$rem = $T - 1
$D = $T * $rem

$p0 = ($nA * $nB) / $D
$p1 = (2 * $nA * $nB) / $D
$pNeither = ($nB * ($nB - 1)) / $D
$p2 = 1 - $pNeither

$answer[0] = $p0
$answer[1] = $p1
$answer[2] = $p2

// The tree, as inline SVG (MyOpenMath has no tree primitive). Geometry and colours follow q35.
// Leaf text starts at x=290 and the longest leaf ("yellow then yellow", 18 chars) runs about 125px,
// so 420 wide holds it, and max-width:100% lets the figure scale down on a phone.
$a1 = $nA - 1
$b1 = $nB - 1
$tw = 420
$th = 260
$svg = '<svg width="' . $tw . '" height="' . $th . '" viewBox="0 0 ' . $tw . ' ' . $th . '" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Tree diagram of two draws without replacement" style="display:block;margin:12px auto;background:#fff;max-width:100%;height:auto">'
$svg = $svg . '<circle cx="30" cy="130" r="5" fill="#21242c"/>'
$svg = $svg . '<line x1="35" y1="130" x2="115" y2="65" stroke="#1865f2" stroke-width="2"/>'
$svg = $svg . '<line x1="35" y1="130" x2="115" y2="195" stroke="#059669" stroke-width="2"/>'
$svg = $svg . '<text x="62" y="84" text-anchor="end" font-family="Arial" font-size="13" fill="#1865f2">' . $nA . '/' . $T . '</text>'
$svg = $svg . '<text x="62" y="186" text-anchor="end" font-family="Arial" font-size="13" fill="#059669">' . $nB . '/' . $T . '</text>'
$svg = $svg . '<text x="158" y="52" text-anchor="end" font-family="Arial" font-size="14" font-weight="bold" fill="#1865f2">' . $kA . '</text>'
$svg = $svg . '<text x="158" y="214" text-anchor="end" font-family="Arial" font-size="14" font-weight="bold" fill="#059669">' . $kB . '</text>'
$svg = $svg . '<line x1="165" y1="62" x2="280" y2="28" stroke="#1865f2" stroke-width="2"/>'
$svg = $svg . '<line x1="165" y1="70" x2="280" y2="105" stroke="#1865f2" stroke-width="2"/>'
$svg = $svg . '<line x1="165" y1="190" x2="280" y2="158" stroke="#059669" stroke-width="2"/>'
$svg = $svg . '<line x1="165" y1="198" x2="280" y2="232" stroke="#059669" stroke-width="2"/>'
$svg = $svg . '<text x="212" y="30" text-anchor="middle" font-family="Arial" font-size="13" fill="#374151">' . $a1 . '/' . $rem . '</text>'
$svg = $svg . '<text x="212" y="112" text-anchor="middle" font-family="Arial" font-size="13" fill="#374151">' . $nB . '/' . $rem . '</text>'
$svg = $svg . '<text x="212" y="164" text-anchor="middle" font-family="Arial" font-size="13" fill="#374151">' . $nA . '/' . $rem . '</text>'
$svg = $svg . '<text x="212" y="244" text-anchor="middle" font-family="Arial" font-size="13" fill="#374151">' . $b1 . '/' . $rem . '</text>'
$svg = $svg . '<text x="290" y="33" font-family="Arial" font-size="13" fill="#21242c">' . $kA . ' then ' . $kA . '</text>'
$svg = $svg . '<text x="290" y="110" font-family="Arial" font-size="13" fill="#21242c">' . $kA . ' then ' . $kB . '</text>'
$svg = $svg . '<text x="290" y="163" font-family="Arial" font-size="13" fill="#21242c">' . $kB . ' then ' . $kA . '</text>'
$svg = $svg . '<text x="290" y="237" font-family="Arial" font-size="13" fill="#21242c">' . $kB . ' then ' . $kB . '</text>'
$svg = $svg . '<text x="30" y="12" font-family="Arial" font-size="12" fill="#6b7280">first draw</text>'
$svg = $svg . '<text x="175" y="12" font-family="Arial" font-size="12" fill="#6b7280">second draw</text>'
$svg = $svg . '</svg>'

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
      <p><span class="term-label">Read the tree.</span> The first draw is ' . $kA . ' with probability `' . $nA . '/' . $T . '` or ' . $kB . ' with probability `' . $nB . '/' . $T . '`. The first item is not put back, so only ' . $rem . ' are left for the second draw. That is why the second fractions on the tree have ' . $rem . ' underneath, and why the numerator drops by one on the branch that repeats the first kind.</p>
      <ul>
        <li>After a first ' . $kA . ': ' . $a1 . ' ' . $kA . ' and ' . $nB . ' ' . $kB . ' are left, so the branches are `' . $a1 . '/' . $rem . '` and `' . $nB . '/' . $rem . '`.</li>
        <li>After a first ' . $kB . ': ' . $nA . ' ' . $kA . ' and ' . $b1 . ' ' . $kB . ' are left, so the branches are `' . $nA . '/' . $rem . '` and `' . $b1 . '/' . $rem . '`.</li>
      </ul>
      <p><span class="term-label">(a) One path.</span> Multiply along the branch: `' . $nA . '/' . $T . ' xx ' . $nB . '/' . $rem . ' = ' . $p0 . '`.</p>
      <p><span class="term-label">(b) Exactly one ' . $kA . '.</span> It can happen two ways: ' . $kA . ' then ' . $kB . ', or ' . $kB . ' then ' . $kA . '. Both paths have the same probability, so add them: `' . $p0 . ' + ' . $p0 . ' = ' . $p1 . '`.</p>
      <p><span class="term-label">(c) At least one ' . $kA . '.</span> The opposite is that neither is ' . $kA . ', which means both are ' . $kB . ': `' . $nB . '/' . $T . ' xx ' . ($nB - 1) . '/' . $rem . ' = ' . $pNeither . '`. Then `1 - ' . $pNeither . ' = ' . $p2 . '`.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A $container holds <b>$nA $kA</b> and <b>$nB $kB</b> $obj. Two are drawn one after the other, and the first is not put back. The tree shows both draws.</p>
    $svg
    <p style="margin:10px 0 0 0; color:#374151; font-size:14px;">Enter each probability as a fraction or a decimal rounded to 4 places.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> P(the first is <b>$kA</b> and the second is <b>$kB</b>), along one path. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> P(exactly one is <b>$kA</b>), adding two paths. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> P(at least one is <b>$kA</b>), using the complement. $answerbox[2]
  </div>
</div>

// === ANSWER ===

$sol
