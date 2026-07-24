// === NAME - DESCRIPTION: Committee Selection - Choose r members from a group of n (combinations) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number")

// Four scenarios pair (n, r) with the precomputed nCr answer and a worked-step expansion.
$ns        = array(7, 9, 12, 10)
$rs        = array(3, 5, 4, 4)
$ans       = array(35, 126, 495, 210)
$expansions = array("`\dfrac{7!}{3!\,4!} = \dfrac{7 \cdot 6 \cdot 5}{3 \cdot 2 \cdot 1} = 35`",
                    "`\dfrac{9!}{5!\,4!} = \dfrac{9 \cdot 8 \cdot 7 \cdot 6}{4 \cdot 3 \cdot 2 \cdot 1} = 126`",
                    "`\dfrac{12!}{4!\,8!} = \dfrac{12 \cdot 11 \cdot 10 \cdot 9}{4 \cdot 3 \cdot 2 \cdot 1} = 495`",
                    "`\dfrac{10!}{4!\,6!} = \dfrac{10 \cdot 9 \cdot 8 \cdot 7}{4 \cdot 3 \cdot 2 \cdot 1} = 210`")
$picked = jointrandfrom($ns, $rs, $ans, $expansions)
$nval = $picked[0]
$rval = $picked[1]
$answer[0] = $picked[2]
$expansion = $picked[3]

// Pick a context sentence (independent of the n,r row).
$context_templates = array("a project team of $rval workers from a pool of $nval workers",
                           "a basketball team of $rval players from a roster of $nval players",
                           "a committee of $rval members from a club of $nval people",
                           "a study group of $rval students from a class of $nval students")
$context_idx = rand(0, 3)
$context = $context_templates[$context_idx]

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
      <p>Selecting members for a group is an <b>unordered</b> choice: swapping two names gives the same group. Use combinations.</p>
      <p>Choose '.$rval.' from '.$nval.': '.$expansion.'.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-radius:0 8px 8px 0; border-left:4px solid #4CAF50;">
        Answer: '.$answer[0].' ways.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">In how many different ways can $context be chosen?</p>
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
