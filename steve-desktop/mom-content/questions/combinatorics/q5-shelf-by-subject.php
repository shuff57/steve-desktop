// === NAME - DESCRIPTION: Books on a Shelf - Combined permutations across two subject pools ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number")

// Five (math books, history books, math slots, history slots) scenarios with precomputed product.
$ms     = array(4, 5, 5, 6, 4)
$hs     = array(5, 6, 7, 5, 6)
$r1s    = array(3, 2, 3, 2, 2)         // math slots filled first
$r2s    = array(2, 3, 2, 3, 3)         // history slots filled next
$lefts  = array(24, 20, 60, 30, 12)    // mPr1
$rights = array(20, 120, 42, 60, 120)  // hPr2
$ans    = array(480, 2400, 2520, 1800, 1440)
$picked = jointrandfrom($ms, $hs, $r1s, $r2s, $lefts, $rights, $ans)
$m = $picked[0]
$h = $picked[1]
$r1 = $picked[2]
$r2 = $picked[3]
$left = $picked[4]
$right = $picked[5]
$answer[0] = $picked[6]

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><b>Each subject is its own permutation; multiply the two counts.</b></p>
      <p>Math books in the first '.$r1.' slots: `'.$m.'P'.$r1.' = '.$left.'`.</p>
      <p>History books in the next '.$r2.' slots: `'.$h.'P'.$r2.' = '.$right.'`.</p>
      <p>Multiply: `'.$left.' \cdot '.$right.' = '.$answer[0].'`.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Answer: '.$answer[0].' arrangements.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A teacher has <b>$m</b> different math books and <b>$h</b> different history books. She wants to fill a shelf with the first <b>$r1</b> slots holding math books and the next <b>$r2</b> slots holding history books. In how many different ways can the books be arranged?</p>
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
