// === NAME - DESCRIPTION: Mixed Operations - Complement of union, intersected with C ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number")

// Each scenario has U = {1..10}. Values of A, B, C are chosen so that
// (A u B)^c intersected with C equals the empty set.
// Three precomputed parallel rows.
$As          = array("1, 2, 3, 4, 5", "1, 3, 5", "2, 4, 6, 8")
$Bs          = array("1, 3, 4, 6",    "2, 4, 6", "1, 3, 5, 7")
$Cs          = array("2, 4, 6",       "1, 3, 5", "2, 4, 6, 8")
$union_sizes = array(6, 6, 8)
$comp_sizes  = array(4, 4, 2)
$final_sizes = array(0, 0, 0)
$picked = jointrandfrom($As, $Bs, $Cs, $union_sizes, $comp_sizes, $final_sizes)
$A_display = $picked[0]
$B_display = $picked[1]
$C_display = $picked[2]
$answer[0] = $picked[3]
$answer[1] = $picked[4]
$answer[2] = $picked[5]

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
      <p>Work from the inside of the expression outward.</p>
      <p><b>Step 1.</b> Combine A = { '.$A_display.' } and B = { '.$B_display.' } into A &cup; B, then count: <b>A &cup; B has '.$answer[0].' elements</b>.</p>
      <p><b>Step 2.</b> The complement keeps elements of U = {1, 2, ..., 10} not in A &cup; B: <b><span style="text-decoration:overline;">A &cup; B</span> has '.$answer[1].' elements</b>.</p>
      <p><b>Step 3.</b> Intersect with C = { '.$C_display.' }. No elements of C land in the complement, so the final set is empty: <b>'.$answer[2].' elements</b>.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <span style="text-decoration:overline;">A &cup; B</span> &cap; C = &empty;
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Let <b>U = {1, 2, 3, &hellip;, 10}</b>, <b>A = { $A_display }</b>, <b>B = { $B_display }</b>, and <b>C = { $C_display }</b>.</p>
    <p style="margin:0.6em 0 0 0;">Find <b><span style="text-decoration:overline;">A &cup; B</span> &cap; C</b>.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> How many elements are in <b>A &cup; B</b>?
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> How many elements are in <b><span style="text-decoration:overline;">A &cup; B</span></b>?
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> How many elements are in <b><span style="text-decoration:overline;">A &cup; B</span> &cap; C</b>?
    <div style="margin-top:12px;text-align:center;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
