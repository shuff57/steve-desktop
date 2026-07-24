// === NAME - DESCRIPTION: Lattice Paths - Shortest routes across a rectangular grid ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number")

// Five rectangle dimensions with precomputed C(a+b, a) routes.
$as    = array(3, 4, 5, 3, 4)
$bs    = array(3, 4, 5, 5, 3)
$tots  = array(6, 8, 10, 8, 7)            // a + b
$ans   = array(20, 70, 252, 56, 35)
$expls = array("`\dbinom{6}{3} = \dfrac{6!}{3!\,3!} = 20`",
               "`\dbinom{8}{4} = \dfrac{8!}{4!\,4!} = 70`",
               "`\dbinom{10}{5} = \dfrac{10!}{5!\,5!} = 252`",
               "`\dbinom{8}{3} = \dfrac{8!}{3!\,5!} = 56`",
               "`\dbinom{7}{4} = \dfrac{7!}{4!\,3!} = 35`")
$picked = jointrandfrom($as, $bs, $tots, $ans, $expls)
$a = $picked[0]
$b = $picked[1]
$tot = $picked[2]
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
      <p>Each shortest route uses '.$a.' east-blocks and '.$b.' north-blocks for '.$tot.' blocks total. A route is determined by <b>choosing which '.$a.' of the '.$tot.' positions are east moves</b>; the remaining '.$b.' are north.</p>
      <p>'.$expl.'</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Answer: '.$answer[0].' routes.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A taxi driver wants to go from one corner of a <b>$a-by-$b</b> block grid to the opposite corner, traveling only east or north and never backtracking. How many different shortest routes are possible?</p>
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
