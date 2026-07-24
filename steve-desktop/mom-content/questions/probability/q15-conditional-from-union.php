// === NAME - DESCRIPTION: Conditional from Union - Solve for P(A intersect B) using the Addition Rule, then compute P(A|B) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc")

$pAs     = array(0.40, 0.45, 0.30, 0.50, 0.35, 0.55, 0.42)
$pBs     = array(0.50, 0.60, 0.40, 0.55, 0.45, 0.50, 0.38)
$pUnions = array(0.70, 0.80, 0.55, 0.85, 0.65, 0.75, 0.62)

$picked = jointrandfrom($pAs, $pBs, $pUnions)
$pA = $picked[0]
$pB = $picked[1]
$pUnion = $picked[2]

$pInt = $pA + $pB - $pUnion
$pInt_show = round($pInt, 4)

$answer = $pInt / $pB
$abstolerance[0] = 0.005
$pAcondB_show = round($answer, 4)

$pA_only = round($pA - $pInt, 4)
$pB_only = round($pB - $pInt, 4)

// Two-step Venn diagram. Question: neutral structure with given values listed.
// Solution venn_step1 derives P(A∩B); venn_step2 conditions on B (B as new sample space, A∩B as favorable).
$venn_open = '<svg width="320" height="220" viewBox="0 0 320 220" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Venn diagram of two events" style="display:block;margin:12px auto;background:#fff">'
$lens_path = '<path d="M 150,58 A 70,70 0 0 1 150,172 A 70,70 0 0 1 150,58 Z" '
$circA = '<circle cx="110" cy="115" r="70" '
$circB = '<circle cx="190" cy="115" r="70" '

$venn_q = $venn_open
$venn_q = $venn_q . $circA . 'fill="#ffffff" fill-opacity="0.6"/>'
$venn_q = $venn_q . $circB . 'fill="#ffffff" fill-opacity="0.6"/>'
$venn_q = $venn_q . $circA . 'fill="none" stroke="#374151" stroke-width="2"/>'
$venn_q = $venn_q . $circB . 'fill="none" stroke="#374151" stroke-width="2"/>'
$venn_q = $venn_q . '<text x="60" y="30" font-size="20" font-weight="700" fill="#1f2937">A</text>'
$venn_q = $venn_q . '<text x="240" y="30" font-size="20" font-weight="700" fill="#1f2937">B</text>'
$venn_q = $venn_q . '</svg>'

// Step 1 solution: shade union (all three regions) and label P(A∩B) in lens.
$venn_step1 = $venn_open
$venn_step1 = $venn_step1 . $circA . 'fill="#fed7aa" fill-opacity="0.85" stroke="#ea580c" stroke-width="2"/>'
$venn_step1 = $venn_step1 . $circB . 'fill="#e9d5ff" fill-opacity="0.85" stroke="#9333ea" stroke-width="2"/>'
$venn_step1 = $venn_step1 . $lens_path . 'fill="#bbf7d0" stroke="#10b981" stroke-width="2"/>'
$venn_step1 = $venn_step1 . $circA . 'fill="none" stroke="#ea580c" stroke-width="2"/>'
$venn_step1 = $venn_step1 . $circB . 'fill="none" stroke="#9333ea" stroke-width="2"/>'
$venn_step1 = $venn_step1 . '<text x="60" y="30" font-size="20" font-weight="700" fill="#1f2937">A</text>'
$venn_step1 = $venn_step1 . '<text x="240" y="30" font-size="20" font-weight="700" fill="#1f2937">B</text>'
$venn_step1 = $venn_step1 . '<text x="80" y="120" font-size="13" font-weight="700" fill="#7c2d12" text-anchor="middle">' . $pA_only . '</text>'
$venn_step1 = $venn_step1 . '<text x="150" y="120" font-size="14" font-weight="700" fill="#065f46" text-anchor="middle">' . $pInt_show . '</text>'
$venn_step1 = $venn_step1 . '<text x="220" y="120" font-size="13" font-weight="700" fill="#581c87" text-anchor="middle">' . $pB_only . '</text>'
$venn_step1 = $venn_step1 . '</svg>'

// Step 2 solution: B as the new sample space, A∩B as the favorable.
$venn_step2 = $venn_open
$venn_step2 = $venn_step2 . $circB . 'fill="#e9d5ff" fill-opacity="0.85" stroke="#9333ea" stroke-width="2"/>'
$venn_step2 = $venn_step2 . $lens_path . 'fill="#bbf7d0" stroke="#10b981" stroke-width="2"/>'
$venn_step2 = $venn_step2 . $circA . 'fill="none" stroke="#374151" stroke-width="2"/>'
$venn_step2 = $venn_step2 . $circB . 'fill="none" stroke="#9333ea" stroke-width="2"/>'
$venn_step2 = $venn_step2 . '<text x="60" y="30" font-size="20" font-weight="700" fill="#1f2937">A</text>'
$venn_step2 = $venn_step2 . '<text x="240" y="30" font-size="20" font-weight="700" fill="#1f2937">B</text>'
$venn_step2 = $venn_step2 . '<text x="150" y="120" font-size="14" font-weight="700" fill="#065f46" text-anchor="middle">' . $pInt_show . '</text>'
$venn_step2 = $venn_step2 . '<text x="220" y="120" font-size="13" font-weight="700" fill="#581c87" text-anchor="middle">' . $pB_only . '</text>'
$venn_step2 = $venn_step2 . '</svg>'

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
      <p><b>Step 1.</b> Use the Addition Rule to find P(A &cap; B):</p>
      <p>P(A &cup; B) = P(A) + P(B) &minus; P(A &cap; B), so P(A &cap; B) = P(A) + P(B) &minus; P(A &cup; B).</p>
      <p>P(A &cap; B) = '.$pA.' + '.$pB.' &minus; '.$pUnion.' = <b>'.$pInt_show.'</b></p>
      '.$venn_step1.'
      <p>The union now has three labeled regions whose values sum to P(A &cup; B) = '.$pUnion.'.</p>
      <p><b>Step 2.</b> Condition on B. Restrict attention to the purple region (B is the new sample space). Within B, the green region (A &cap; B) is favorable:</p>
      '.$venn_step2.'
      <p>P(A | B) = P(A &cap; B) / P(B) = '.$pInt_show.' / '.$pB.' = <b>'.$pAcondB_show.'</b></p>
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
      <li>P(A) = <b>$pA</b></li>
      <li>P(B) = <b>$pB</b></li>
      <li>P(A &cup; B) = <b>$pUnion</b></li>
    </ul>
    $venn_q
    <p style="margin:0.5em 0 0 0;">Find <b>P(A | B)</b>.</p>
    <p style="margin:0.5em 0 0 0; font-size:14px; color:#555;">Hint: first use the Addition Rule to find P(A &cap; B), then apply the conditional probability formula. Enter as a decimal rounded to 4 places.</p>
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
