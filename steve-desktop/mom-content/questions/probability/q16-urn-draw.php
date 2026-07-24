// === NAME - DESCRIPTION: Urn Draw - Sample space size and probability of a color and its complement ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc", "numfunc", "numfunc")

// Three urn scenarios with clean fractions for P(red) and P(not red).
$Rs           = array(4,    3,    5)
$Bs           = array(3,    4,    7)
$Gs           = array(5,    8,    8)
$Tots         = array(12,   15,   20)
$pReds        = array(4/12, 3/15, 5/20)
$pNots        = array(8/12, 12/15, 15/20)
$pReds_show   = array("4/12 = 1/3 &approx; 0.3333", "3/15 = 1/5 = 0.2",   "5/20 = 1/4 = 0.25")
$pNots_show   = array("8/12 = 2/3 &approx; 0.6667", "12/15 = 4/5 = 0.8", "15/20 = 3/4 = 0.75")
$picked = jointrandfrom($Rs, $Bs, $Gs, $Tots, $pReds, $pNots, $pReds_show, $pNots_show)
$R = $picked[0]
$B = $picked[1]
$G = $picked[2]
$T = $picked[3]
$answer[0] = $T
$answer[1] = $picked[4]
$answer[2] = $picked[5]
$pred_show = $picked[6]
$pnot_show = $picked[7]
$abstolerance[1] = 0.005
$abstolerance[2] = 0.005
$nonred = $T - $R

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
      <p>The urn has '.$R.' red, '.$B.' blue, and '.$G.' green balls. Each ball is equally likely.</p>
      <p><b>(a) Sample space size.</b> Total balls: '.$R.' + '.$B.' + '.$G.' = <b>'.$T.'</b>.</p>
      <p><b>(b) P(red).</b> Favorable / total = '.$pred_show.'.</p>
      <p><b>(c) P(not red).</b> Non-red count is '.$nonred.', so P(not red) = '.$pnot_show.'. Equivalently, 1 &minus; P(red).</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Answers: '.$T.', '.$pred_show.', '.$pnot_show.'.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">An urn contains <b>$R red</b>, <b>$B blue</b>, and <b>$G green</b> balls. One ball is drawn at random.</p>
    <p style="margin:0.5em 0 0 0; font-size:14px; color:#555;">Enter probability answers as exact fractions or decimals (rounded to 4 places).</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the size of the sample space (the total number of equally likely outcomes)?
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find <b>P(red)</b>.
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find <b>P(not red)</b>.
    <div style="margin-top:12px;text-align:center;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
