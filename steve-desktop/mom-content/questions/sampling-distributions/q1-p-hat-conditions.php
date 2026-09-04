// === NAME - DESCRIPTION: Sampling distribution of p-hat - check the np>=10 and n(1-p)>=10 conditions and decide whether the sampling distribution is approximately normal ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices","choices","choices")
$displayformat = "select"
$answerboxsize = 12

// Each scenario sets context, population proportion p, and sample size n.
// Scenarios are tuned to hit a mix of all-pass, np-fail, and n(1-p)-fail outcomes.

// Scenario 0: opinion poll, n=200, p=0.40 -> np=80 (pass), n(1-p)=120 (pass) -> normal
// Scenario 1: small voter survey, n=50, p=0.15 -> np=7.5 (FAIL), n(1-p)=42.5 (pass) -> NOT normal
// Scenario 2: high-yield manufacturing check, n=100, p=0.95 -> np=95 (pass), n(1-p)=5 (FAIL) -> NOT normal
// Scenario 3: school survey, n=300, p=0.25 -> np=75 (pass), n(1-p)=225 (pass) -> normal
// Scenario 4: rare-event audit, n=80, p=0.08 -> np=6.4 (FAIL), n(1-p)=73.6 (pass) -> NOT normal
// Scenario 5: medium poll, n=400, p=0.60 -> np=240 (pass), n(1-p)=160 (pass) -> normal

$ctxs = array(
  "A polling firm samples <b>`n = 200`</b> registered voters and records whether each one approves of a new city ordinance. Assume the population proportion who approve is <b>`p = 0.40`</b>, and the sample is random with the voter population at least 10 times n.",
  "A campus survey of <b>`n = 50`</b> students asks whether each student plans to vote in the upcoming election. Assume the true proportion of student voters is <b>`p = 0.15`</b>, and the sample is random with the student population at least 10 times n.",
  "A factory inspects <b>`n = 100`</b> randomly chosen parts. Assume the population proportion of acceptable parts is <b>`p = 0.95`</b>, and the daily run is at least 10 times n.",
  "A school district samples <b>`n = 300`</b> students at random and records whether each one rides the bus. Assume the proportion of bus riders is <b>`p = 0.25`</b>, and the district population is at least 10 times n.",
  "An auditor samples <b>`n = 80`</b> tax returns at random. Assume the population proportion containing an error is <b>`p = 0.08`</b>, and the filed-return population is at least 10 times n.",
  "A market-research firm samples <b>`n = 400`</b> adults and asks whether each owns a streaming subscription. Assume the population proportion is <b>`p = 0.60`</b>, and the adult population is at least 10 times n."
)

$ns       = array(200,  50, 100, 300,  80, 400)
$ps       = array(0.40, 0.15, 0.95, 0.25, 0.08, 0.60)
$nps      = array(80,   7.5, 95,   75,   6.4, 240)
$nq       = array(120, 42.5,  5,  225, 73.6, 160)
// 0 = Yes (>=10), 1 = No (<10): mapping into the choices array below
$ansNp    = array(0,    1,    0,    0,    1,    0)
$ansNq    = array(0,    0,    1,    0,    0,    0)
// final conclusion: 0 = Yes (approx normal), 1 = No (NOT approx normal)
$ansFinal = array(0,    1,    1,    0,    1,    0)

$picked = jointrandfrom($ctxs, $ns, $ps, $nps, $nq, $ansNp, $ansNq, $ansFinal)
$ctx   = $picked[0]
$n     = $picked[1]
$p     = $picked[2]
$np    = $picked[3]
$nq_v  = $picked[4]

$questions[0] = array("Yes, the condition is satisfied", "No, the condition is NOT satisfied")
$answer[0] = $picked[5]

$questions[1] = array("Yes, the condition is satisfied", "No, the condition is NOT satisfied")
$answer[1] = $picked[6]

$questions[2] = array("Yes, the sampling distribution of `\hat{p}` is approximately normal", "No, the sampling distribution of `\hat{p}` is NOT approximately normal")
$answer[2] = $picked[7]

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
      <p><b>Rule:</b> The sampling distribution of `\hat{p}` is approximately normal when BOTH <b>large-counts</b> conditions hold:</p>
      <p style="text-align:center;">`n cdot p >= 10` &nbsp; AND &nbsp; `n cdot (1-p) >= 10`</p>
      <p>(Plus a random sample and independence: the population should be at least `10n`.)</p>
      <p><b>(a)</b> `n cdot p = ' . $n . ' cdot ' . $p . ' = ' . $np . '`. Is this `>= 10`?</p>
      <p><b>(b)</b> `n cdot (1-p) = ' . $n . ' cdot ' . (1 - $p) . ' = ' . $nq_v . '`. Is this `>= 10`?</p>
      <p><b>(c)</b> If both are `>= 10`, the sampling distribution is approximately normal. If <i>either</i> fails, it is NOT approximately normal.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Sanity check:</b> small `p` makes `n cdot p` the limiting condition; large `p` (close to 1) makes `n cdot (1-p)` the limiting condition. Watch the smaller of the two.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">$ctx</p>
    <p style="margin:0;">Decide whether the sampling distribution of the sample proportion `\hat{p}` is approximately normal by checking the <b>large-counts</b> conditions.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Is the condition `n cdot p >= 10` satisfied?
    <div style="margin-top:12px;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Is the condition `n cdot (1-p) >= 10` satisfied?
    <div style="margin-top:12px;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Based on parts (a) and (b), is the sampling distribution of `\hat{p}` approximately normal?
    <div style="margin-top:12px;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
