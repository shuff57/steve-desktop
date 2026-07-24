// === NAME - DESCRIPTION: Addition Rule (Percentages) - Apply P(A union B) = P(A) + P(B) - P(A intersect B) to a real-context word problem ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc")

$pAs       = array(0.20, 0.36, 0.50, 0.72, 0.45)
$pBs       = array(0.30, 0.12, 0.60, 0.46, 0.40)
$pABs      = array(0.10, 0.08, 0.30, 0.32, 0.20)
$ctxAs     = array("Finite Math", "chocolate", "Accounting", "the final exam", "soccer")
$ctxBs     = array("Statistics",  "nuts",      "English",     "a research paper", "basketball")
$ctxIntros = array(
  "At a college, the percentage of students who take each subject is given.",
  "A bakery makes cookies that may contain certain allergens.",
  "Of the students at a university, the proportion who pass each course is given.",
  "A history class lists course requirements; each student is required to do each task with the given probabilities.",
  "Children at a summer camp choose afternoon activities."
)

$picked = jointrandfrom($pAs, $pBs, $pABs, $ctxAs, $ctxBs, $ctxIntros)
$pA = $picked[0]
$pB = $picked[1]
$pAB = $picked[2]
$ctxA = $picked[3]
$ctxB = $picked[4]
$intro = $picked[5]

$answer = $pA + $pB - $pAB
$abstolerance[0] = 0.005
$pA_show = $pA
$pB_show = $pB
$pAB_show = $pAB
$pAorB_show = round($answer, 4)
$pA_only = round($pA - $pAB, 4)
$pB_only = round($pB - $pAB, 4)

// 2-circle Venn diagram. Question shows neutral structure; solution shades union and breaks regions down.
// Geometry: viewBox 320x200, A center (110,95) r=70, B center (190,95) r=70.
// Lens path between circles: M 150,38 A 70,70 0 0 1 150,152 A 70,70 0 0 1 150,38 Z
$venn_open = '<svg width="320" height="220" viewBox="0 0 320 220" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Venn diagram of two events" style="display:block;margin:12px auto;background:#fff">'
$lens_path = '<path d="M 150,58 A 70,70 0 0 1 150,172 A 70,70 0 0 1 150,58 Z" '
$circA = '<circle cx="110" cy="115" r="70" '
$circB = '<circle cx="190" cy="115" r="70" '

// Question Venn: neutral outlines, only event labels.
$venn_q = $venn_open
$venn_q = $venn_q . $circA . 'fill="#ffffff" fill-opacity="0.6"/>'
$venn_q = $venn_q . $circB . 'fill="#ffffff" fill-opacity="0.6"/>'
$venn_q = $venn_q . $circA . 'fill="none" stroke="#374151" stroke-width="2"/>'
$venn_q = $venn_q . $circB . 'fill="none" stroke="#374151" stroke-width="2"/>'
$venn_q = $venn_q . '<text x="110" y="30" font-size="20" font-weight="700" fill="#1f2937" text-anchor="middle">A</text>'
$venn_q = $venn_q . '<text x="190" y="30" font-size="20" font-weight="700" fill="#1f2937" text-anchor="middle">B</text>'
$venn_q = $venn_q . '</svg>'

$venn_caption = '<p style="text-align:center;margin:0 0 10px 0;font-size:13px;color:#6b7280">A = ' . $ctxA . ' &nbsp;&bull;&nbsp; B = ' . $ctxB . '</p>'

// Solution Venn: shade all three regions of the union with their respective probability values.
$venn_sol = $venn_open
$venn_sol = $venn_sol . $circA . 'fill="#fed7aa" fill-opacity="0.85" stroke="#ea580c" stroke-width="2"/>'
$venn_sol = $venn_sol . $circB . 'fill="#e9d5ff" fill-opacity="0.85" stroke="#9333ea" stroke-width="2"/>'
$venn_sol = $venn_sol . $lens_path . 'fill="#bbf7d0" stroke="#10b981" stroke-width="2"/>'
$venn_sol = $venn_sol . $circA . 'fill="none" stroke="#ea580c" stroke-width="2"/>'
$venn_sol = $venn_sol . $circB . 'fill="none" stroke="#9333ea" stroke-width="2"/>'
$venn_sol = $venn_sol . '<text x="110" y="30" font-size="20" font-weight="700" fill="#1f2937" text-anchor="middle">A</text>'
$venn_sol = $venn_sol . '<text x="190" y="30" font-size="20" font-weight="700" fill="#1f2937" text-anchor="middle">B</text>'
// Region labels: P(A only) in left lune, P(A∩B) in lens, P(B only) in right lune.
$venn_sol = $venn_sol . '<text x="80" y="120" font-size="14" font-weight="700" fill="#7c2d12" text-anchor="middle">' . $pA_only . '</text>'
$venn_sol = $venn_sol . '<text x="150" y="120" font-size="14" font-weight="700" fill="#065f46" text-anchor="middle">' . $pAB_show . '</text>'
$venn_sol = $venn_sol . '<text x="220" y="120" font-size="14" font-weight="700" fill="#581c87" text-anchor="middle">' . $pB_only . '</text>'
$venn_sol = $venn_sol . '</svg>'

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
      <p><b>Addition Rule:</b> P(A &cup; B) = P(A) + P(B) &minus; P(A &cap; B). The Venn diagram below breaks the union into three non-overlapping regions:</p>
      '.$venn_sol.'
      '.$venn_caption.'
      <ul style="margin:0.5em 0 0.5em 1.2em;">
        <li>'.$ctxA.' only: '.$pA_show.' &minus; '.$pAB_show.' = <b>'.$pA_only.'</b></li>
        <li>both '.$ctxA.' and '.$ctxB.': <b>'.$pAB_show.'</b></li>
        <li>'.$ctxB.' only: '.$pB_show.' &minus; '.$pAB_show.' = <b>'.$pB_only.'</b></li>
      </ul>
      <p>Sum of the three regions = P('.$ctxA.' OR '.$ctxB.') = '.$pA_only.' + '.$pAB_show.' + '.$pB_only.' = <b>'.$pAorB_show.'</b></p>
      <p>Or apply the formula directly: '.$pA_show.' + '.$pB_show.' &minus; '.$pAB_show.' = <b>'.$pAorB_show.'</b>.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        P('.$ctxA.' or '.$ctxB.') = '.$pAorB_show.'
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$intro</p>
    <ul style="margin:0.5em 0 0 1.2em;">
      <li>P($ctxA) = $pA_show</li>
      <li>P($ctxB) = $pB_show</li>
      <li>P($ctxA AND $ctxB) = $pAB_show</li>
    </ul>
    $venn_q
    $venn_caption
    <p style="margin:0.5em 0 0 0;">Find <b>P($ctxA OR $ctxB)</b>.</p>
    <p style="margin:0.5em 0 0 0; font-size:14px; color:#555;">Enter as a decimal rounded to 4 places.</p>
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
