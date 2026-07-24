// === NAME - DESCRIPTION: Subsets Count - How many subsets of an n-element set ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number")

// Randomize the set size so students must actually apply 2^n, not memorize.
$ns = array(4, 5, 6, 7)
$displays = array("a, b, c, d", "a, b, c, d, e", "a, b, c, d, e, f", "a, b, c, d, e, f, g")
$answers = array(16, 32, 64, 128)
$picked = jointrandfrom($ns, $displays, $answers)
$n = $picked[0]
$set_display = $picked[1]

$answer[0] = $picked[2]

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
      <p>A set with <b>n</b> elements has <b>2<sup>n</sup></b> subsets. Each element is either in a given subset or out of it, and those 2 independent choices for each of n elements multiply to 2<sup>n</sup>.</p>
      <p>Here n = <b>'.$n.'</b>, so the number of subsets is 2<sup>'.$n.'</sup> = <b>'.$answer[0].'</b>.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Answer: '.$answer[0].' subsets.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">How many subsets does the set <b>{ $set_display }</b> have? <i>Do not list them.</i></p>
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
