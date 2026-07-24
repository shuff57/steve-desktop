// === NAME - DESCRIPTION: Lottery Ticket Combinations - Count possible tickets, count tickets with a specific number, probability of winning ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc", "numfunc", "numfunc")

/* ---------- 1. Randomized scenarios ---------- */
// Each row: [n, k, C(n,k), C(n-1,k-1), 1/C(n,k) as decimal]
// Precomputed and verified:
//   C(10,3)=120,   C(9,2)=36,    1/120  ~0.0083333...
//   C(15,4)=1365,  C(14,3)=364,  1/1365 ~0.0007326...
//   C(12,5)=792,   C(11,4)=330,  1/792  ~0.0012626...
//   C(20,6)=38760, C(19,5)=11628,1/38760~0.0000258...

$ns       = array(10,   15,   12,   20)
$ks       = array(3,    4,    5,    6)
$totals   = array(120,  1365, 792,  38760)
$with_num = array(36,   364,  330,  11628)
// Probability of winning = 1/total; stored as exact decimal string for display
$prob_strs = array("1/120", "1/1365", "1/792", "1/38760")
// Numeric answer for grading (IMathAS number type accepts fractions)
$prob_ans  = array(0.0083333333, 0.0007326007, 0.0012626263, 0.0000257997)

$picked   = jointrandfrom($ns, $ks, $totals, $with_num, $prob_strs, $prob_ans)
$nval     = $picked[0]
$kval     = $picked[1]
$answer[0] = $picked[2]
$answer[1] = $picked[3]
$pstr      = $picked[4]
$answer[2] = $picked[5]

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
      <p><b>Part (a): Total possible tickets.</b></p>
      <p>A lottery ticket is an unordered selection of '.$kval.' numbers from '.$nval.', so use combinations.</p>
      <p style="text-align:center;">`C('.$nval.','.$kval.') = ('.$nval.'!)/('.$kval.'! * '.$nm1.'!) = '.$answer[0].'`</p>
      <p><b>Part (b): Tickets that include a specific number.</b></p>
      <p>Lock that number in as one of the '.$kval.' picks. The remaining '.$km1.' spots come from the other `'.$nval.' - 1 = '.$nm1.'` numbers.</p>
      <p style="text-align:center;">`C('.$nm1.','.$km1.') = '.$answer[1].'`</p>
      <p><b>Part (c): Probability of winning with one ticket.</b></p>
      <p>Only 1 ticket out of '.$answer[0].' possible tickets wins. Probability = 1 divided by the total number of tickets.</p>
      <p style="text-align:center;">`P(win) = 1/'.$answer[0].' = '.$pstr.'`</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        (a) '.$answer[0].' possible tickets &nbsp;|&nbsp; (b) '.$answer[1].' tickets include your lucky number &nbsp;|&nbsp; (c) P(win) = '.$pstr.'
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 6px 0;">A local lottery draws <b>$kval numbers</b> at random from <b>1 through $nval</b> (no repeats, order does not matter). You buy one ticket, which also picks $kval numbers from 1 through $nval.</p>
    <p style="margin:0;">Answer each part below. You may express probabilities as fractions.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span>How many different lottery tickets are possible?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span>How many of those tickets include the number <b>7</b> as one of the $kval picks?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span>If you buy one ticket, what is the probability it matches the winning ticket exactly? (Express as a fraction.)
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
