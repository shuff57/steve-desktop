// === NAME - DESCRIPTION: Starter Selection Combinations - Multi-constraint combinations: total, include a player, exclude a player, probability ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number", "numfunc")

/* ---------- 1. Randomized scenarios ---------- */
// Each row: n, k, sport, role, C(n,k), C(n-1,k-1), C(n-1,k), prob_num, prob_den
// Precomputed and verified:
//   C(12,5)=792,  C(11,4)=330, C(11,5)=462,  330+462=792  P=330/792=5/12
//   C(10,4)=210,  C(9,3)=84,   C(9,4)=126,   84+126=210   P=84/210=2/5
//   C(15,5)=3003, C(14,4)=1001,C(14,5)=2002, 1001+2002=3003 P=1001/3003=1/3
//   C(9,3)=84,    C(8,2)=28,   C(8,3)=56,    28+56=84     P=28/84=1/3

$ns        = array(12,         10,           15,           9)
$ks        = array(5,          4,            5,            3)
$sports    = array("basketball","soccer",     "volleyball", "baseball")
$roledesc  = array("starting lineup of 5",
                   "starting field lineup of 4",
                   "starting lineup of 5",
                   "3-person outfield group")
$totals    = array(792,        210,          3003,         84)
$with_j    = array(330,        84,           1001,         28)
$without_j = array(462,        126,          2002,         56)
$prob_nums = array(5,          2,            1,            1)
$prob_dens = array(12,         5,            3,            3)

$picked   = jointrandfrom($ns, $ks, $sports, $roledesc, $totals, $with_j, $without_j, $prob_nums, $prob_dens)
$nval     = $picked[0]
$kval     = $picked[1]
$sport    = $picked[2]
$role     = $picked[3]
$answer[0] = $picked[4]
$answer[1] = $picked[5]
$answer[2] = $picked[6]
$pnum     = $picked[7]
$pden     = $picked[8]
$answer[3] = $pnum / $pden

$nm1 = $nval - 1
$km1 = $kval - 1

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
      <p><b>Part (a): Total lineups with no restriction.</b></p>
      <p>Order does not matter (a lineup is a set of players, not a ranked list), so use combinations: choose '.$kval.' players from all '.$nval.' on the roster.</p>
      <p style="text-align:center;">`C('.$nval.', '.$kval.') = ('.$nval.'!)/('.$kval.'! * '.$nm1.'!) = '.$answer[0].'`</p>
      <p><b>Part (b): Lineups that INCLUDE Jordan.</b></p>
      <p>Lock Jordan into one of the '.$kval.' spots. The remaining '.$km1.' spots are chosen freely from the other `'.$nval.' - 1 = '.$nm1.'` players.</p>
      <p style="text-align:center;">`C('.$nm1.', '.$km1.') = '.$answer[1].'`</p>
      <p><b>Part (c): Lineups that EXCLUDE Jordan.</b></p>
      <p>Remove Jordan from consideration entirely. All '.$kval.' players must come from the other '.$nm1.' on the roster.</p>
      <p style="text-align:center;">`C('.$nm1.', '.$kval.') = '.$answer[2].'`</p>
      <p><em>Check:</em> lineups with Jordan + lineups without Jordan = '.$answer[1].' + '.$answer[2].' = '.$answer[0].'. &#10003;</p>
      <p><b>Part (d): Probability the lineup includes Jordan.</b></p>
      <p>Of the '.$answer[0].' equally likely lineups, exactly '.$answer[1].' include Jordan.</p>
      <p style="text-align:center;">`P("Jordan is selected") = '.$answer[1].'/'.$answer[0].' = '.$pnum.'/'.$pden.'`</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        (a) '.$answer[0].' lineups &nbsp;|&nbsp; (b) '.$answer[1].' include Jordan &nbsp;|&nbsp; (c) '.$answer[2].' exclude Jordan &nbsp;|&nbsp; (d) P = '.$pnum.'/'.$pden.'
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 6px 0;">A <b>$sport team</b> has <b>$nval players</b> on its roster, including a player named <b>Jordan</b>. The coach needs to select a <b>$role</b> from the roster. (Order does not matter: only which players are chosen.)</p>
    <p style="margin:0;">Answer each part below. You may express the probability in part (d) as a fraction.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span>How many different lineups of $kval players are possible from the full $nval-player roster?<span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span>How many of those lineups <b>include Jordan</b>?<span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span>How many of those lineups <b>do not include Jordan</b>?<span style="margin-left:8px;">$answerbox[2]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span>If the coach picks the lineup completely at random, what is the probability that Jordan is selected?<span style="margin-left:8px;">$answerbox[3]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
