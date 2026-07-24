// === NAME - DESCRIPTION: Venn 2-Circle Exactly One - Decompose a 2-circle survey into "only A", "only B", and "neither" ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number")

// Four survey scenarios using a math-vs-CS enrollment context.
// Regions defined as disjoint counts: math_only, cs_only, both, neither.
// All four are randomized jointly so answers are precomputed and consistent.
$math_onlys = array(22, 18, 30, 14)
$cs_onlys   = array(15, 24, 12, 20)
$boths      = array(8,  10, 6,  12)
$neithers   = array(5,  8,  12, 4)

$picked = jointrandfrom($math_onlys, $cs_onlys, $boths, $neithers)
$math_only = $picked[0]
$cs_only   = $picked[1]
$both      = $picked[2]
$neither   = $picked[3]

$math  = $math_only + $both
$cs    = $cs_only   + $both
$total = $math_only + $cs_only + $both + $neither

$answer[0] = $math_only
$answer[1] = $cs_only
$answer[2] = $neither

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
      <p>Build the Venn diagram from the overlap outward. Let M = enrolled in Math, C = enrolled in CS.</p>
      <ul>
        <li>|M &cap; C| = <b>'.$both.'</b> (given directly)</li>
        <li>M only (Math but not CS) = |M| &minus; |M &cap; C| = '.$math.' &minus; '.$both.' = <b>'.$math_only.'</b></li>
        <li>C only (CS but not Math) = |C| &minus; |M &cap; C| = '.$cs.' &minus; '.$both.' = <b>'.$cs_only.'</b></li>
        <li>Neither = Total &minus; (M only + both + C only) = '.$total.' &minus; ('.$math_only.' + '.$both.' + '.$cs_only.') = <b>'.$neither.'</b></li>
      </ul>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        (a) Math only: <b>'.$math_only.'</b> &nbsp;&bull;&nbsp; (b) CS only: <b>'.$cs_only.'</b> &nbsp;&bull;&nbsp; (c) Neither: <b>'.$neither.'</b>
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A college surveyed <b>$total</b> students about their current course enrollment. Of those surveyed, <b>$math</b> are enrolled in a Math course, <b>$cs</b> are enrolled in a CS course, and <b>$both</b> are enrolled in both. The rest are enrolled in neither.</p>
    <p style="margin:0.5em 0 0 0;">Use a Venn diagram to answer each part.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> How many students are enrolled in Math <b>but not</b> CS?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> How many students are enrolled in CS <b>but not</b> Math?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> How many students are enrolled in <b>neither</b> Math nor CS?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>


// === ANSWER ===

$solutionguide
