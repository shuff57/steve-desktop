// === NAME - DESCRIPTION: Mixed Operations - A union complement of (B intersect C) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("ntuple")

// Each scenario computes A u (B n C)^c inside U = {1..10}.
// Precomputed answers keep arithmetic clean.
$As        = array("1, 2, 3, 4, 5", "1, 3, 5",       "2, 4, 6, 8")
$Bs        = array("1, 3, 4, 6",    "2, 4, 6",       "1, 3, 5, 7")
$Cs        = array("2, 4, 6",       "1, 2, 3, 6",    "2, 4, 6, 8")
$final     = array("{1,2,3,4,5,7,8,9,10}", "{1,3,4,5,7,8,9,10}", "{1,2,3,4,5,6,7,8,9,10}")
$picked = jointrandfrom($As, $Bs, $Cs, $final)
$A_display = $picked[0]
$B_display = $picked[1]
$C_display = $picked[2]
$answer = $picked[3]
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
      <p><b>Step 1.</b> Find B &cap; C (elements in both B = { '.$B_display.' } and C = { '.$C_display.' }).</p>
      <p><b>Step 2.</b> Take the complement inside U = {1, 2, ..., 10}: remove the shared elements from U.</p>
      <p><b>Step 3.</b> Union with A = { '.$A_display.' }: add any elements of A that are still missing.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        A &cup; <span style="text-decoration:overline;">B &cap; C</span> = '.$answer.'
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Let <b>U = {1, 2, 3, &hellip;, 10}</b>, <b>A = { $A_display }</b>, <b>B = { $B_display }</b>, and <b>C = { $C_display }</b>.</p>
    <p style="margin:0.5em 0 0 0;">Find <b>A &cup; <span style="text-decoration:overline;">B &cap; C</span></b>. Enter the set using braces, e.g. <code>{1,2,3}</code>. Order does not matter.</p>
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
