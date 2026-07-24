// === NAME - DESCRIPTION: Card Hand from a Deck - Choose r cards from 52 (combinations) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number")

// Four hand sizes from a 52-card deck with precomputed nCr answers.
$rs       = array(2, 3, 4, 5)
$ans      = array(1326, 22100, 270725, 2598960)
$expls    = array("`\dfrac{52!}{2!\,50!} = \dfrac{52 \cdot 51}{2 \cdot 1} = 1{,}326`",
                  "`\dfrac{52!}{3!\,49!} = \dfrac{52 \cdot 51 \cdot 50}{3 \cdot 2 \cdot 1} = 22{,}100`",
                  "`\dfrac{52!}{4!\,48!} = \dfrac{52 \cdot 51 \cdot 50 \cdot 49}{4 \cdot 3 \cdot 2 \cdot 1} = 270{,}725`",
                  "`\dfrac{52!}{5!\,47!} = \dfrac{52 \cdot 51 \cdot 50 \cdot 49 \cdot 48}{5 \cdot 4 \cdot 3 \cdot 2 \cdot 1} = 2{,}598{,}960`")
$picked = jointrandfrom($rs, $ans, $expls)
$r = $picked[0]
$answer[0] = $picked[1]
$expl = $picked[2]

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
      <p>A card hand is unordered, so use combinations: choose '.$r.' cards from a 52-card deck.</p>
      <p>'.$expl.'</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Answer: '.$answer[0].' hands.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">How many different <b>$r</b>-card hands can be chosen from a standard 52-card deck?</p>
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
