// === NAME - DESCRIPTION: Disjoint vs Complement - Disjoint sets that are not complements ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number")

// Three scenarios. In each, A n B = {} (disjoint) but A u B != U (so not complements).
// The answers are |A n B| = 0, |A u B|, and |U|. Asking students to compare parts (b) and (c)
// reveals whether the two sets fully cover the universe.
$As       = array("1, 3",     "2, 4, 6",  "1, 2, 3")
$Bs       = array("2, 4, 6",  "1, 3, 5",  "7, 8, 9")
$unions   = array(5,          6,          6)
$Usizes   = array(10,         10,         10)
$picked = jointrandfrom($As, $Bs, $unions, $Usizes)
$A_display = $picked[0]
$B_display = $picked[1]
$answer[0] = 0
$answer[1] = $picked[2]
$answer[2] = $picked[3]

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
      <p>With A = { '.$A_display.' } and B = { '.$B_display.' } inside U = {1, 2, ..., '.$answer[2].'}:</p>
      <p>A &cap; B has no shared elements, so <b>the number of elements in A &cap; B is 0</b>. That confirms <b>A and B are disjoint</b>.</p>
      <p>A &cup; B combines the two lists without duplicates, giving <b>'.$answer[1].' elements in A &cup; B</b>. The total number of elements in U is <b>'.$answer[2].'</b>.</p>
      <p>For B to be the <b>complement</b> of A we would need BOTH conditions:</p>
      <ul>
        <li>A &cap; B = &empty; (disjoint) &#10003;</li>
        <li>A &cup; B = U</li>
      </ul>
      <p>Here A &cup; B has '.$answer[1].' elements while U has '.$answer[2].', so A &cup; B &ne; U.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        A and B are <b>disjoint</b> (no overlap) but are <b>not complements</b> of each other.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Let <b>U = {1, 2, 3, &hellip;, $answer[2]}</b>, <b>A = { $A_display }</b>, and <b>B = { $B_display }</b>. Use these sets to decide whether A and B are disjoint, complements, or both.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> How many elements are in <b>A &cap; B</b>?
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> How many elements are in <b>A &cup; B</b>?
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> How many elements are in <b>U</b>?
    <div style="margin-top:12px;text-align:center;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
