// === NAME - DESCRIPTION: Glue Trick - k specific people sit together in a row of n ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number")

// Five (n, k) scenarios. Answer = (n-k+1)! * k!
$ns      = array(5, 6, 6, 7, 7)
$ks      = array(2, 2, 3, 2, 3)
$mvals   = array(4, 5, 4, 6, 5)         // n - k + 1, the count of items after gluing
$mfacts  = array(24, 120, 24, 720, 120) // (n-k+1)!
$kfacts  = array(2, 2, 6, 2, 6)         // k!
$ans     = array(48, 240, 144, 1440, 720)
$picked = jointrandfrom($ns, $ks, $mvals, $mfacts, $kfacts, $ans)
$n = $picked[0]
$k = $picked[1]
$mval = $picked[2]
$mfact = $picked[3]
$kfact = $picked[4]
$answer[0] = $picked[5]

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
      <p><b>Glue the '.$k.' tied people into a single super-person.</b> That leaves '.$mval.' items to arrange.</p>
      <p>Arrange the '.$mval.' items in a row: `'.$mval.'! = '.$mfact.'`.</p>
      <p>Account for the internal order of the glued group: `'.$k.'! = '.$kfact.'`.</p>
      <p>Multiply: `'.$mfact.' \cdot '.$kfact.' = '.$answer[0].'`.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Answer: '.$answer[0].' seatings.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">In how many different ways can <b>$n</b> people be seated in a row if <b>$k</b> specific people insist on sitting together?</p>
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
