// === NAME - DESCRIPTION: Goodness-of-Fit Expected Counts - Compute E_i = n*p_i for three categories under a claimed distribution ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc", "numfunc", "numfunc")

// Each scenario gives a context paragraph, the claimed percentages (display), sample size n,
// and three precomputed expected counts.
// All scenarios are tuned so expected counts are clean (whole or one-decimal) and observable.

// Scenario 0: candy bag colors, n=200, claim 30/25/25/20 -> E = 60, 50, 50, 40 (ask 30/25/20)
// Scenario 1: candy bag colors, n=300, claim 40/30/20/10 -> E = 120, 90, 60, 30 (ask 40/30/10)
// Scenario 2: weekday clinic visits, n=350, equal claim 1/7 -> E = 50 each (ask 50/50/50)
// Scenario 3: die rolls, n=120, equal claim 1/6 -> E = 20 each (ask 20/20/20)
// Scenario 4: streaming-genre survey, n=400, claim 25/20/20/20/15 -> E = 100, 80, 80, 80, 60 (ask 25/20/15)

$ctxs = array(
  "A candy company claims its small bags contain <b>30% red, 25% blue, 25% green, and 20% yellow</b>. A consumer empties a bag and counts <b>n = 200</b> candies.",
  "A snack maker claims its trail-mix is <b>40% peanuts, 30% raisins, 20% almonds, and 10% chocolate</b>. A worker checks a sample of <b>n = 300</b> pieces.",
  "A clinic claims that patient visits are <b>equally likely on each of the 7 days of the week</b>. A receptionist records the day for <b>n = 350</b> visits.",
  "A casino manager claims a six-sided die is fair, so each face is <b>equally likely</b>. The die is rolled <b>n = 120</b> times.",
  "A streaming service claims viewers split <b>25% drama, 20% comedy, 20% reality, 20% sports, and 15% news</b>. A survey of <b>n = 400</b> subscribers records each viewer's top genre."
)

$labelA = array("red",     "peanuts", "Monday",  "face 1",  "drama")
$labelB = array("blue",    "raisins", "Thursday","face 4",  "comedy")
$labelC = array("yellow",  "chocolate","Sunday", "face 6",  "news")

$pctA = array("30%", "40%", "1/7", "1/6", "25%")
$pctB = array("25%", "30%", "1/7", "1/6", "20%")
$pctC = array("20%", "10%", "1/7", "1/6", "15%")

$ns = array(200, 300, 350, 120, 400)

$eA = array(60, 120, 50, 20, 100)
$eB = array(50,  90, 50, 20,  80)
$eC = array(40,  30, 50, 20,  60)

$picked = jointrandfrom($ctxs, $labelA, $labelB, $labelC, $pctA, $pctB, $pctC, $ns, $eA, $eB, $eC)
$ctx   = $picked[0]
$lA    = $picked[1]
$lB    = $picked[2]
$lC    = $picked[3]
$pA    = $picked[4]
$pB    = $picked[5]
$pC    = $picked[6]
$n     = $picked[7]
$answer[0] = $picked[8]
$answer[1] = $picked[9]
$answer[2] = $picked[10]
$abstolerance[0] = 0.05
$abstolerance[1] = 0.05
$abstolerance[2] = 0.05

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
      <p><b>Rule:</b> For a chi-square goodness-of-fit test, the expected count in category `i` is `E_i = n cdot p_i`, where `p_i` is the claimed proportion.</p>
      <p>Here `n = ' . $n . '`.</p>
      <p><b>$lA:</b> `E = ' . $n . ' xx ' . $pA . ' = ' . $answer[0] . '`</p>
      <p><b>$lB:</b> `E = ' . $n . ' xx ' . $pB . ' = ' . $answer[1] . '`</p>
      <p><b>$lC:</b> `E = ' . $n . ' xx ' . $pC . ' = ' . $answer[2] . '`</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Sanity check:</b> the expected counts across all categories should sum to `n`. If they do not, recheck the claimed proportions or fractions.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">$ctx</p>
    <p style="margin:0;">If the claim is true, what counts should we <b>expect</b> in the categories below? Use `E_i = n cdot p_i`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Expected count for <b>$lA</b> (claimed share $pA): $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Expected count for <b>$lB</b> (claimed share $pB): $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Expected count for <b>$lC</b> (claimed share $pC): $answerbox[2]
  </div>
</div>


// === ANSWER ===

$solutionguide
