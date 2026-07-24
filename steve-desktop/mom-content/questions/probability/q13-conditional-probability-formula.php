// === NAME - DESCRIPTION: Conditional Probability Formula - Apply P(A|B) = P(A intersect B) / P(B) given the two probabilities ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc")

$pABs = array(0.32, 0.18, 0.24, 0.15, 0.12, 0.21, 0.28)
$pBs  = array(0.46, 0.40, 0.50, 0.30, 0.36, 0.35, 0.55)

$picked = jointrandfrom($pABs, $pBs)
$pAB = $picked[0]
$pB = $picked[1]

$answer = $pAB / $pB
$abstolerance[0] = 0.005
$pAcondB_show = round($answer, 4)

// 2-circle Venn diagram. Question: neutral. Solution: B shaded as the new sample space, A∩B as favorable.
$venn_open = '<svg width="320" height="220" viewBox="0 0 320 220" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Venn diagram of two events" style="display:block;margin:12px auto;background:#fff">'
$lens_path = '<path d="M 150,58 A 70,70 0 0 1 150,172 A 70,70 0 0 1 150,58 Z" '
$circA = '<circle cx="110" cy="115" r="70" '
$circB = '<circle cx="190" cy="115" r="70" '

$venn_q = $venn_open
$venn_q = $venn_q . $circA . 'fill="#ffffff"/>'
$venn_q = $venn_q . $circB . 'fill="#ffffff" fill-opacity="0.6"/>'
$venn_q = $venn_q . $circA . 'fill="none" stroke="#374151" stroke-width="2"/>'
$venn_q = $venn_q . $circB . 'fill="none" stroke="#374151" stroke-width="2"/>'
$venn_q = $venn_q . '<text x="60" y="30" font-size="20" font-weight="700" fill="#1f2937">A</text>'
$venn_q = $venn_q . '<text x="240" y="30" font-size="20" font-weight="700" fill="#1f2937">B</text>'
$venn_q = $venn_q . '</svg>'

// Solution: B is the new sample space (purple fill), A∩B inside it is favorable (green).
// A-only region stays unfilled (outside the conditioning event).
$venn_sol = $venn_open
$venn_sol = $venn_sol . $circB . 'fill="#e9d5ff" fill-opacity="0.85" stroke="#9333ea" stroke-width="2"/>'
$venn_sol = $venn_sol . $lens_path . 'fill="#bbf7d0" stroke="#10b981" stroke-width="2"/>'
$venn_sol = $venn_sol . $circA . 'fill="none" stroke="#374151" stroke-width="2"/>'
$venn_sol = $venn_sol . $circB . 'fill="none" stroke="#9333ea" stroke-width="2"/>'
$venn_sol = $venn_sol . '<text x="60" y="30" font-size="20" font-weight="700" fill="#1f2937">A</text>'
$venn_sol = $venn_sol . '<text x="240" y="30" font-size="20" font-weight="700" fill="#1f2937">B</text>'
// Region labels: P(A∩B) in lens, P(B only) = P(B) - P(A∩B) in right lune.
$venn_sol = $venn_sol . '<text x="150" y="120" font-size="14" font-weight="700" fill="#065f46" text-anchor="middle">' . $pAB . '</text>'
$venn_sol = $venn_sol . '<text x="220" y="120" font-size="13" font-weight="700" fill="#581c87" text-anchor="middle">' . round($pB - $pAB, 4) . '</text>'
$venn_sol = $venn_sol . '</svg>'

$legend = '<div style="display:flex;justify-content:center;gap:14px;margin:4px 0 12px 0;font-size:13px;color:#374151;flex-wrap:wrap"><span><span style="display:inline-block;width:14px;height:14px;background:#e9d5ff;border:2px solid #9333ea;border-radius:3px;vertical-align:middle"></span> conditioning event B (new sample space)</span><span><span style="display:inline-block;width:14px;height:14px;background:#bbf7d0;border:2px solid #10b981;border-radius:3px;vertical-align:middle"></span> A &cap; B (favorable within B)</span></div>'

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
      <p>Conditioning on B restricts attention to the purple region. Within B, the green region (A &cap; B) is favorable.</p>
      '.$venn_sol.'
      '.$legend.'
      <p><b>Conditional Probability Formula:</b> P(A | B) = P(A &cap; B) / P(B), provided P(B) > 0.</p>
      <p>P(A &cap; B) = '.$pAB.', P(B) = '.$pB.'.</p>
      <p>P(A | B) = '.$pAB.' / '.$pB.' = <b>'.$pAcondB_show.'</b></p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        P(A | B) = '.$pAcondB_show.'
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Suppose A and B are events with</p>
    <ul style="margin:0.5em 0 0 1.2em;">
      <li>P(A &cap; B) = <b>$pAB</b></li>
      <li>P(B) = <b>$pB</b></li>
    </ul>
    $venn_q
    <p style="margin:0.5em 0 0 0;">Find <b>P(A | B)</b>.</p>
    <p style="margin:0.5em 0 0 0; font-size:14px; color:#555;">Enter as a decimal rounded to 4 places.</p>
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
