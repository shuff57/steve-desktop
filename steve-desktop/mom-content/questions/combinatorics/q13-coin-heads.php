// === NAME - DESCRIPTION: Coin Tosses With k Heads - Choose which positions are heads ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number")

// Five (n tosses, k heads) scenarios with precomputed nCk.
$ns       = array(5, 6, 6, 7, 8)
$ks       = array(3, 4, 2, 3, 5)
$tlosses  = array(2, 2, 4, 4, 3)        // n - k, the number of tails for the explanation
$ans      = array(10, 15, 15, 35, 56)
$expls    = array("`\dfrac{5!}{3!\,2!} = 10`",
                  "`\dfrac{6!}{4!\,2!} = 15`",
                  "`\dfrac{6!}{2!\,4!} = 15`",
                  "`\dfrac{7!}{3!\,4!} = 35`",
                  "`\dfrac{8!}{5!\,3!} = 56`")
$picked = jointrandfrom($ns, $ks, $tlosses, $ans, $expls)
$n = $picked[0]
$k = $picked[1]
$tn = $picked[2]
$answer[0] = $picked[3]
$expl = $picked[4]

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
      <p>Of the '.$n.' toss positions, <b>choose which '.$k.' land heads</b>. The other '.$tn.' are automatically tails. Position is what makes outcomes different, so the choice is unordered: use combinations.</p>
      <p>'.$expl.'</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Answer: '.$answer[0].' outcomes.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A fair coin is tossed <b>$n</b> times. In how many different sequences does it land exactly <b>$k</b> heads and <b>$tn</b> tails?</p>
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
