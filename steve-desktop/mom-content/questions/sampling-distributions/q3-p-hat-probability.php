// === NAME - DESCRIPTION: Sampling distribution of p-hat - compute the mean, standard error, and a less-than or greater-than probability for a sample proportion ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

$anstypes = array("number","number","number")

// Each scenario gives p, n, and a cutoff c. Conditions are pre-checked (all scenarios
// have np>=10 and n(1-p)>=10). Direction (less-than vs greater-than) is randomized.

// Scenario 0: poll approval,        p=0.40, n=200, c=0.45
// Scenario 1: customer renewal,     p=0.55, n=400, c=0.50
// Scenario 2: drug effectiveness,   p=0.30, n=150, c=0.35
// Scenario 3: graduation rate,      p=0.65, n=250, c=0.70
// Scenario 4: exam pass rate,       p=0.80, n=100, c=0.75
// Scenario 5: park-visitor return,  p=0.45, n=300, c=0.40

$ctxs = array(
  "A polling firm samples <b>`n = 200`</b> registered voters. Assume the population proportion who approve of a new ordinance is <b>`p = 0.40`</b>. Let `\hat{p}` be the sample proportion who approve.",
  "A subscription service samples <b>`n = 400`</b> customers. Assume the population proportion who renew is <b>`p = 0.55`</b>. Let `\hat{p}` be the sample renewal proportion.",
  "A drug trial samples <b>`n = 150`</b> patients. Assume the population proportion for whom the drug works is <b>`p = 0.30`</b>. Let `\hat{p}` be the sample success proportion.",
  "An admissions office samples <b>`n = 250`</b> first-year students. Assume the population graduation proportion is <b>`p = 0.65`</b>. Let `\hat{p}` be the sample graduation proportion.",
  "A testing center samples <b>`n = 100`</b> exam-takers. Assume the population pass proportion is <b>`p = 0.80`</b>. Let `\hat{p}` be the sample pass proportion.",
  "A national park samples <b>`n = 300`</b> visitors. Assume the population proportion of returning visitors is <b>`p = 0.45`</b>. Let `\hat{p}` be the sample proportion of returning visitors."
)

$ps  = array(0.40, 0.55, 0.30, 0.65, 0.80, 0.45)
$ns  = array(200,  400,  150,  250,  100,  300)
$cs  = array(0.45, 0.50, 0.35, 0.70, 0.75, 0.40)

$picked = jointrandfrom($ctxs, $ps, $ns, $cs)
$ctx = $picked[0]
$p   = $picked[1]
$n   = $picked[2]
$c   = $picked[3]

$se = sqrt($p * (1 - $p) / $n)
$z = ($c - $p) / $se

$dir = rand(0, 1)
// dir 0 = less-than (P(\hat{p} < c))
// dir 1 = greater-than (P(\hat{p} > c))
$prob_lt = normalcdf($z)
if ($dir == 0) {
  $relsym = "<"
  $relword = "less than"
  $prob = $prob_lt
} else {
  $relsym = ">"
  $relword = "greater than"
  $prob = 1 - $prob_lt
}

$answer[0] = $p
$answer[1] = round($se, 4)
$answer[2] = round($prob, 4)
$abstolerance[0] = 0.001
$abstolerance[1] = 0.0005
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
      <p><b>(a)</b> Mean of the sampling distribution of `\hat{p}` is `p = ' . $p . '`.</p>
      <p><b>(b)</b> Standard error is `SE = sqrt((p(1-p))/n) = sqrt((' . $p . ' cdot ' . (1 - $p) . ')/' . $n . ') ~~ ' . round($se, 4) . '`.</p>
      <p><b>(c)</b> We want `P(\hat{p} ' . $relsym . ' ' . $c . ')`. Compute the z-score:</p>
      <p style="text-align:center;">`z = (' . $c . ' - ' . $p . ') / ' . round($se, 4) . ' ~~ ' . round($z, 3) . '`</p>
      <p>Then use a normal table or technology with `mu = ' . $p . '` and `SE = ' . round($se, 4) . '`:</p>
      <p style="text-align:center;">`P(\hat{p} ' . $relsym . ' ' . $c . ') ~~ ' . round($prob, 4) . '`</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#fff3e0; border-left:4px solid #ff9800; border-radius:0 8px 8px 0;">
        <b>Watch the inequality direction.</b> If you computed the less-than probability when the question asked for greater-than (or vice versa), the two answers must sum to 1.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">$ctx</p>
    <p style="margin:0;">The conditions for a normal sampling distribution are satisfied.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the <b>mean</b> of the sampling distribution of `\hat{p}`?
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the <b>standard error</b> of `\hat{p}`? (Round to 4 decimal places.)
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find `P(\hat{p} $relsym $c)`, the probability that the sample proportion is <b>$relword</b> $c. (Round to 4 decimal places.)
    <div style="margin-top:12px;text-align:center;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
