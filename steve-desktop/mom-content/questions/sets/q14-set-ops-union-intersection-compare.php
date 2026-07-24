// === NAME - DESCRIPTION: Set Ops Compare - Union and intersection of two integer sets, then compare their sizes ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("ntuple", "ntuple", "choices")

// Four scenarios with meaningful overlap. Unions and intersections are precomputed.
// Each pair has some shared and some exclusive elements so neither operation trivially dominates.
$As        = array("2, 4, 6, 8, 10, 12",  "1, 3, 5, 7, 9",     "2, 3, 5, 7, 11",    "1, 4, 6, 8, 10")
$Bs        = array("3, 6, 9, 12",          "3, 5, 6, 7, 8",      "3, 6, 7, 9, 11",    "2, 4, 6, 9, 10")
$unions    = array("{2,3,4,6,8,9,10,12}",  "{1,3,5,6,7,8,9}",   "{2,3,5,6,7,9,11}",  "{1,2,4,6,8,9,10}")
$intersects = array("{6,12}",              "{3,5,7}",             "{3,7,11}",           "{4,6,10}")
$u_sizes   = array(8,                      7,                     7,                    7)
$i_sizes   = array(2,                      3,                     3,                    3)
// All scenarios: |A ∪ B| > |A ∩ B|, so the conceptual answer is always "A ∪ B is larger".

$picked = jointrandfrom($As, $Bs, $unions, $intersects, $u_sizes, $i_sizes)
$A_display = $picked[0]
$B_display = $picked[1]
$answer[0] = $picked[2]
$answer[1] = $picked[3]
$usize     = $picked[4]
$isize     = $picked[5]

$displayformat[0] = "set"
$answerformat[0]  = "anyorder"
$displayformat[1] = "set"
$answerformat[1]  = "anyorder"

// choices for part (c)
$choices[2] = array("|A &cup; B| is larger", "|A &cap; B| is larger", "They are the same size")
$answer[2]  = 0
$displayformat[2] = "select"
$noshuffle[2] = "all"

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
      <p>Given A = { '.$A_display.' } and B = { '.$B_display.' }.</p>
      <p><b>(a) A &cup; B</b> collects every element in A, in B, or both. Combine both sets and list each element once:</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        A &cup; B = '.$answer[0].' &nbsp; &rarr; &nbsp; |A &cup; B| = '.$usize.'
      </div>
      <p><b>(b) A &cap; B</b> keeps only the elements appearing in both sets:</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        A &cap; B = '.$answer[1].' &nbsp; &rarr; &nbsp; |A &cap; B| = '.$isize.'
      </div>
      <p><b>(c)</b> Since |A &cup; B| = '.$usize.' and |A &cap; B| = '.$isize.', <b>|A &cup; B| is larger</b>. The union always contains the intersection (every shared element is also in the union), so |A &cup; B| &ge; |A &cap; B|, with equality only when A = B.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Let <b>A = { $A_display }</b> and <b>B = { $B_display }</b>, where both are sets of positive integers.</p>
    <p style="margin:0.5em 0 0 0;">Enter set answers using braces, e.g. <code>{1,2,3}</code>. Order does not matter.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find <b>A &cup; B</b>.
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find <b>A &cap; B</b>.
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which is larger: <b>|A &cup; B|</b> or <b>|A &cap; B|</b>?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>


// === ANSWER ===

$solutionguide
