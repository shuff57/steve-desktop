// === NAME - DESCRIPTION: None and At Least One in a Binomial - Find P(X = 0) as q to the n, then P(X >= 1) as its complement, then the mean np ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Five binomial scenarios. Parts: (a) numfunc - P(X = 0) = q^n; (b) numfunc - P(X >= 1) = 1 - q^n; (c) numfunc - np.
// Invariant: (a) + (b) = 1 on every row; values rounded to 4 places in the generator.

$anstypes = array("numfunc", "numfunc", "numfunc")

$ns = array(8, 5, 10, 6, 12)
$ps = array(0.1, 0.3, 0.15, 0.25, 0.05)
$qs = array(0.9, 0.7, 0.85, 0.75, 0.95)
$zeros = array(0.4305, 0.1681, 0.1969, 0.178, 0.5404)
$atleast = array(0.5695, 0.8319, 0.8031, 0.822, 0.4596)
$mus = array(0.8, 1.5, 1.5, 1.5, 0.6)
$ctxs = array("A vending machine fails to give out a drink on 10% of purchases, and the purchases are independent. You make 8 purchases. `X` is the number of purchases where it fails.", "A free-throw shooter misses 30% of her shots, and the shots are independent. She takes 5 shots. `X` is the number she misses.", "A delivery arrives late 15% of the time, and the deliveries are independent. A shop receives 10 deliveries. `X` is the number that arrive late.", "A prize game pays out on 25% of plays, and the plays are independent. You play 6 times. `X` is the number of plays that pay out.", "A light bulb is defective 5% of the time, and the bulbs are independent. A box holds 12 bulbs. `X` is the number of defective bulbs.")

$picked = jointrandfrom($ns, $ps, $qs, $zeros, $atleast, $mus, $ctxs)
$n = $picked[0]
$p = $picked[1]
$q = $picked[2]
$answer[0] = $picked[3]
$answer[1] = $picked[4]
$answer[2] = $picked[5]
$ctx = $picked[6]

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
  .term-label { font-weight:700; color:#1865f2; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><span class="term-label">The model.</span> `X ~ B(' . $n . ', ' . $p . ')`, so `q = ' . $q . '`.</p>
      <p><span class="term-label">Part (a).</span> No successes means every trial is a failure: `P(X = 0) = q^n = ' . $q . '^' . $n . ' = ` <b>' . $answer[0] . '</b>.</p>
      <p><span class="term-label">Part (b).</span> At least one is the complement of none: `P(X >= 1) = 1 - P(X = 0) = 1 - ' . $answer[0] . ' = ` <b>' . $answer[1] . '</b>.</p>
      <p><span class="term-label">Part (c).</span> `mu = np = ' . $n . ' x ' . $p . ' = ` <b>' . $answer[2] . '</b>.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is `P(X = 0)`?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is `P(X >= 1)`?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is the mean `mu = np`?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
