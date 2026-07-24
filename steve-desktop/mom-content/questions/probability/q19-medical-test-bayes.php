// === NAME - DESCRIPTION: Medical Test Bayes Chain - P(D and +), P(+), P(D | +) from prevalence, sensitivity, false positive rate ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc", "numfunc", "numfunc")

// Three scenarios. P(D), sens = P(+|D), fpr = P(+|~D).
// Compute (a) P(D and +) = sens * P(D), (b) P(+) = sens*P(D) + fpr*(1-P(D)), (c) P(D|+) = (a) / (b).
$pDs        = array(0.10, 0.20, 0.05)
$senses     = array(0.80, 0.90, 0.95)
$fprs       = array(0.10, 0.10, 0.05)
$pDs_show   = array("0.10", "0.20", "0.05")
$pNotDs     = array(0.90, 0.80, 0.95)
$pDintPlus  = array(0.08, 0.18, 0.0475)
$pPlus      = array(0.17, 0.26, 0.095)
$pDgivPlus  = array(0.4706, 0.6923, 0.5)
$pDgivPlus_exact = array("8/17", "18/26 = 9/13", "1/2")
$picked = jointrandfrom($pDs, $senses, $fprs, $pDs_show, $pNotDs, $pDintPlus, $pPlus, $pDgivPlus, $pDgivPlus_exact)
$pD = $picked[0]
$sens = $picked[1]
$fpr = $picked[2]
$pD_show = $picked[3]
$pNotD = $picked[4]
$answer[0] = $picked[5]
$answer[1] = $picked[6]
$answer[2] = $picked[7]
$pDgiv_exact = $picked[8]
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005
$abstolerance[2] = 0.005

$pNotDtimesfpr = $pNotD * $fpr

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
      <p>Let D = "has the disease" and + = "tests positive". Given P(D) = '.$pD.', P(+ | D) = '.$sens.', P(+ | <span style="text-decoration:overline;">D</span>) = '.$fpr.'. So P(<span style="text-decoration:overline;">D</span>) = '.$pNotD.'.</p>
      <p><b>(a) P(D &cap; +).</b> Multiplication rule: P(+ | D) &times; P(D) = '.$sens.' &times; '.$pD.' = <b>'.$answer[0].'</b>.</p>
      <p><b>(b) P(+).</b> Total probability: P(D &cap; +) + P(<span style="text-decoration:overline;">D</span> &cap; +) = '.$answer[0].' + '.$pNotD.' &times; '.$fpr.' = '.$answer[0].' + '.$pNotDtimesfpr.' = <b>'.$answer[1].'</b>.</p>
      <p><b>(c) P(D | +).</b> Conditional formula: P(D &cap; +) / P(+) = '.$answer[0].' / '.$answer[1].' = '.$pDgiv_exact.' &approx; <b>'.$answer[2].'</b>.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Answers: '.$answer[0].', '.$answer[1].', '.$answer[2].'.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 0.6em 0;">A medical test is used to screen for a disease. Let <b>D</b> = "has the disease" and <b>+</b> = "tests positive".</p>
    <table style="border-collapse:separate; border-spacing:0; border-radius:8px; overflow:hidden; margin:0.6em auto; border:1px solid #e5e7eb;">
      <tr>
        <th style="background:#f7f9fa; padding:8px 14px; border-bottom:2px solid #dee1e3; font-weight:600; text-align:left;">Probability</th>
        <th style="background:#f7f9fa; padding:8px 14px; border-bottom:2px solid #dee1e3; border-left:1px solid #e5e7eb; font-weight:600; text-align:left;">Meaning</th>
      </tr>
      <tr>
        <td style="padding:8px 14px; border-bottom:1px solid #e5e7eb;"><b>P(D)</b> = $pD</td>
        <td style="padding:8px 14px; border-bottom:1px solid #e5e7eb; border-left:1px solid #e5e7eb; color:#374151;">prevalence</td>
      </tr>
      <tr>
        <td style="padding:8px 14px; border-bottom:1px solid #e5e7eb;"><b>P(+ | D)</b> = $sens</td>
        <td style="padding:8px 14px; border-bottom:1px solid #e5e7eb; border-left:1px solid #e5e7eb; color:#374151;">sensitivity</td>
      </tr>
      <tr>
        <td style="padding:8px 14px;"><b>P(+ | <span style="text-decoration:overline;">D</span>)</b> = $fpr</td>
        <td style="padding:8px 14px; border-left:1px solid #e5e7eb; color:#374151;">false positive rate</td>
      </tr>
    </table>
    <p style="margin:0.6em 0 0 0; font-size:14px; color:#555;">Enter answers as decimals rounded to 4 places (or exact fractions).</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find <b>P(D &cap; +)</b>, the probability of having the disease and testing positive.
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find <b>P(+)</b>, the overall probability of testing positive.
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find <b>P(D | +)</b>, the probability of having the disease given a positive test result.
    <div style="margin-top:12px;text-align:center;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
