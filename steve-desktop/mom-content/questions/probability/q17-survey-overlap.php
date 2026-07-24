// === NAME - DESCRIPTION: P(neither) via Naive Sum, Addition Rule, Complement - Three chained steps from given P(A), P(B), P(A and B) to P(neither) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc", "numfunc", "numfunc")

// Goal: find P(neither A nor B). Three chained steps:
//   (a) Naive sum P(A) + P(B)  (over-counts the overlap)
//   (b) P(A u B) = (a) - P(A n B)
//   (c) P(neither) = 1 - (b)
$pAs       = array(0.60, 0.70, 0.65)
$pBs       = array(0.45, 0.50, 0.55)
$pABs      = array(0.25, 0.30, 0.30)
$labels_A  = array("reads the news",       "drinks coffee",      "uses Facebook")
$labels_B  = array("watches sports",       "drinks tea",         "uses Instagram")
$naives    = array(1.05, 1.20, 1.20)
$pUnions   = array(0.80, 0.90, 0.90)
$pNeither  = array(0.20, 0.10, 0.10)
$picked = jointrandfrom($pAs, $pBs, $pABs, $labels_A, $labels_B, $naives, $pUnions, $pNeither)
$pA = $picked[0]
$pB = $picked[1]
$pAB = $picked[2]
$labelA = $picked[3]
$labelB = $picked[4]
$answer[0] = $picked[5]
$answer[1] = $picked[6]
$answer[2] = $picked[7]
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005
$abstolerance[2] = 0.005

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
      <p>Goal: find P(neither A nor B), where A = "'.$labelA.'", B = "'.$labelB.'". Given P(A) = '.$pA.', P(B) = '.$pB.', P(A &cap; B) = '.$pAB.'.</p>
      <p><b>(a) Naive sum.</b> P(A) + P(B) = '.$pA.' + '.$pB.' = <b>'.$answer[0].'</b>. This over-counts the overlap (notice it can exceed 1).</p>
      <p><b>(b) P(A &cup; B) by the Addition Rule.</b> Subtract the over-count: '.$answer[0].' &minus; '.$pAB.' = <b>'.$answer[1].'</b>.</p>
      <p><b>(c) P(neither) by complement.</b> 1 &minus; '.$answer[1].' = <b>'.$answer[2].'</b>.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Final answer: P(neither A nor B) = '.$answer[2].'.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A survey is conducted on people's habits. Let:</p>
    <ul style="margin:0.5em 0 0.5em 1.5em;">
      <li>A = "<b>$labelA</b>"</li>
      <li>B = "<b>$labelB</b>"</li>
    </ul>
    <p style="margin:0.5em 0 0 0;">From the survey: P(A) = <b>$pA</b>, P(B) = <b>$pB</b>, P(A &cap; B) = <b>$pAB</b>.</p>
    <p style="margin:0.5em 0 0 0;"><b>Goal:</b> Find <b>P(neither A nor B)</b>. Work through the three steps.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Step 1: Compute the <b>naive sum</b> P(A) + P(B). (This is the over-count we'll correct in step 2.)
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Step 2: Apply the Addition Rule: <b>P(A &cup; B)</b> = (a) &minus; P(A &cap; B).
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Step 3: Use the complement to find <b>P(neither)</b> = 1 &minus; (b).
    <div style="margin-top:12px;text-align:center;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
