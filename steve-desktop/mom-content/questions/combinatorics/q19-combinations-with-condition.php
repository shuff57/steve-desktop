// === NAME - DESCRIPTION: Combinations With Condition - Count committees from a group, then count those including a specific member ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

$anstypes = array("number", "number")

/* ---------- 1. Randomized scenarios ---------- */
// Each row: [n, r, nCr(n,r), nCr(n-1,r-1), group_name, person_name, item_label]
// C(n,r) and C(n-1,r-1) precomputed and verified.
//
// C(8,3)=56,  C(7,2)=21
// C(9,3)=84,  C(8,2)=28
// C(10,3)=120, C(9,2)=36
// C(10,4)=210, C(9,3)=84
// C(11,4)=330, C(10,3)=120
// C(12,5)=792, C(11,4)=330

$ns       = array( 8,  9, 10, 10, 11, 12)
$rs       = array( 3,  3,  3,  4,  4,  5)
$ncrs     = array(56, 84, 120, 210, 330, 792)
$ncr_conds = array(21, 28,  36,  84, 120, 330)
$group_names = array(
  "a book club of 8 readers",
  "a study group of 9 students",
  "a reading circle of 10 members",
  "a community board of 10 volunteers",
  "a research team of 11 scholars",
  "a creative writing workshop of 12 participants"
)
$person_names = array("Maya", "Jordan", "Riley", "Sam", "Taylor", "Morgan")
$item_labels = array(
  "a 3-person selection committee",
  "a 3-person peer review panel",
  "a 3-person discussion leadership team",
  "a 4-person planning subcommittee",
  "a 4-person advisory panel",
  "a 5-person editorial board"
)

$picked = jointrandfrom($ns, $rs, $ncrs, $ncr_conds, $group_names, $person_names, $item_labels)
$nval   = $picked[0]
$rval   = $picked[1]
$answer[0] = $picked[2]
$answer[1] = $picked[3]
$grp    = $picked[4]
$pname  = $picked[5]
$ilab   = $picked[6]

$nm1    = $nval - 1
$rm1    = $rval - 1

/* ---------- 2. Solution Guide ---------- */
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
      <p><b>Part (a): Total combinations.</b></p>
      <p>Order does not matter for committee membership, so use the combination formula `C(n,r) = (n!)/(r!(n-r)!)`.</p>
      <p style="text-align:center;">`C('.$nval.','.$rval.') = '.$answer[0].'`</p>
      <p><b>Part (b): Committees that include '.$pname.'.</b></p>
      <p>Lock '.$pname.' in as one member. The remaining '.$rm1.' seats must be chosen from the other `'.$nval.' - 1 = '.$nm1.'` members.</p>
      <p style="text-align:center;">`C('.$nm1.','.$rm1.') = '.$answer[1].'`</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        (a) '.$answer[0].' total selections &nbsp;|&nbsp; (b) '.$answer[1].' selections include '.$pname.'
      </div>
    </div>
  </details>
</div>'

/* ---------- 3. Question Text ---------- */

// Precomputed for the question text: substitution takes scalars only, and a variable
// followed by letters would read as a different variable name.
$__qt1 = $ilab . "s"
$__qt2 = $ilab . "s"

//question text// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">From $grp, the group needs to form $ilab. One member of the group is named $pname.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> How many different $__qt1 are possible (any $rval members)?
    <div style="margin-top:12px; text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> How many of those $__qt2 include $pname?
    <div style="margin-top:12px; text-align:center;">$answerbox[1]</div>
  </div>
</div>

// === ANSWER ===
$solutionguide
