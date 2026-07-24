// === NAME - DESCRIPTION: Compute nPr - Break the permutation formula into its pieces (n!, (n-r)!, final) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number")

// One (n, r) pair per version. Each row carries: n, r, n!, (n-r)!, P(n,r).
$ns       = array(5,   5,  6,   6  )
$rs       = array(2,   3,  2,   3  )
$nfacts   = array(120, 120, 720, 720)
$nrfacts  = array(6,   2,   24,  6  )
$Pvals    = array(20,  60,  30,  120)

$picked = jointrandfrom($ns, $rs, $nfacts, $nrfacts, $Pvals)
$n     = $picked[0]
$r     = $picked[1]
$nfact = $picked[2]
$nrfact = $picked[3]
$P     = $picked[4]
$nr    = $n - $r

$answer[0] = $nfact
$answer[1] = $nrfact
$answer[2] = $P

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
      <p>The permutation formula is</p>
      <p style="margin:0.4em 0 0.4em 1.5em;">`P(n, r) = (n!)/((n-r)!)`</p>
      <p>For `n = '.$n.'` and `r = '.$r.'`, n &minus; r = '.$nr.'.</p>
      <p><b>(a)</b> n! = '.$n.'! = <b>'.$nfact.'</b>.</p>
      <p><b>(b)</b> (n &minus; r)! = '.$nr.'! = <b>'.$nrfact.'</b>.</p>
      <p><b>(c)</b> `P('.$n.', '.$r.') = ('.$nfact.')/('.$nrfact.') = '.$P.'`.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        `P('.$n.', '.$r.') = '.$P.'`
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Compute `P($n, $r)` by filling in the pieces of the permutation formula `P(n, r) = (n!)/((n-r)!)`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> `n!` =
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> `(n-r)!` =
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> `P($n, $r)` =
    <div style="margin-top:12px;text-align:center;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
