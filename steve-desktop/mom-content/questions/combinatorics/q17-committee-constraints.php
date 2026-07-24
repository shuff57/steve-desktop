// === NAME - DESCRIPTION: Committees with At-Least-One via Complement - Find committees that include at least one of two named people using total minus none ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number")

// Goal: How many committees of r from n include AT LEAST ONE of Alex or Bea?
// Steps: (a) Total = C(n,r), (b) Neither = C(n-2, r), (c) At least one = (a) - (b).
$ns          = array(10,  8,   12)
$rs          = array(4,   3,   5)
$totals      = array(210, 56,  792)
$neithers    = array(70,  20,  252)
$atleastones = array(140, 36,  540)
$nm2s        = array(8,   6,   10)
$picked = jointrandfrom($ns, $rs, $totals, $neithers, $atleastones, $nm2s)
$n = $picked[0]
$r = $picked[1]
$answer[0] = $picked[2]
$answer[1] = $picked[3]
$answer[2] = $picked[4]
$nm2 = $picked[5]

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
      <p>Goal: count committees of '.$r.' from '.$n.' that include AT LEAST ONE of Alex or Bea. Use complement counting.</p>
      <p><b>(a) Total committees</b> with no restriction: `C('.$n.', '.$r.') = '.$answer[0].'`.</p>
      <p><b>(b) Committees with NEITHER Alex nor Bea.</b> Pick all '.$r.' members from the other '.$nm2.' people: `C('.$nm2.', '.$r.') = '.$answer[1].'`.</p>
      <p><b>(c) Committees with at least one of Alex or Bea.</b> Subtract the &ldquo;none&rdquo; case from the total: '.$answer[0].' &minus; '.$answer[1].' = <b>'.$answer[2].'</b>.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Answer: '.$answer[2].' committees.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A club has <b>$n members</b>, including two specific members named <b>Alex</b> and <b>Bea</b>. The club is forming a committee of <b>$r members</b>. (Order does not matter.)</p>
    <p style="margin:0.5em 0 0 0;"><b>Goal:</b> How many committees include <b>at least one of Alex or Bea</b>? Use complement counting (total &minus; "neither") to solve it in three steps.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Step 1: Find the <b>total number of committees</b> of $r members from the $n people (no restriction).
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Step 2: Find the number of committees with <b>NEITHER Alex nor Bea</b> (pick all $r members from the other $nm2 people).
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Step 3: Use the complement to find committees with <b>at least one</b> of Alex or Bea = (a) &minus; (b).
    <div style="margin-top:12px;text-align:center;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
