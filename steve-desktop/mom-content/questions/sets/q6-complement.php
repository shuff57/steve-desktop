// === NAME - DESCRIPTION: Complement - Complement of A relative to U ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("ntuple")

// Three scenarios with different universes so students must reread each time.
$Us           = array("1, 2, 3, ..., 10", "1, 2, 3, ..., 8",  "1, 2, 3, ..., 12")
$As           = array("2, 4, 6, 8, 10",   "1, 3, 5, 7",       "4, 8, 12")
$complements  = array("{1,3,5,7,9}",       "{2,4,6,8}",        "{1,2,3,5,6,7,9,10,11}")
$picked = jointrandfrom($Us, $As, $complements)
$U_display = $picked[0]
$A_display = $picked[1]
$answer = $picked[2]
$displayformat[0] = "set"
$answerformat[0] = "anyorder"

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
      <p>The <b>complement</b> of <b>A</b>, written <b><span style="text-decoration:overline;">A</span></b> or <b>A<sup>c</sup></b>, is the set of elements in the universal set <b>U</b> that are <b>not</b> in <b>A</b>.</p>
      <p>U = { '.$U_display.' } and A = { '.$A_display.' }. Remove the elements of A from U.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <span style="text-decoration:overline;">A</span> = '.$answer.'
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Let the universal set be <b>U = { $U_display }</b> and <b>A = { $A_display }</b>.</p>
    <p style="margin:0.5em 0 0 0;">Find <b><span style="text-decoration:overline;">A</span></b>. Enter the set using braces, e.g. <code>{1,3}</code>. Order does not matter.</p>
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
