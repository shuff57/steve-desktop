// === NAME - DESCRIPTION: Exponential Growth and Decay Models - Evaluate and interpret A(t) = A0 * b^t in applied contexts ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats");

$anstypes = array("number", "choices", "number")
$displayformat[1] = "select"
$noshuffle[1] = "all"

// Three contexts, all using A(t) = A0 * b^t with b = 2 or b = 1/2 so log is clean.
// Context A: Bacteria doubling every hour. b=2.  A0=100, t=3 -> A=800.  Time to reach 1600: 4 hrs.
// Context B: Drug half-life. b=1/2.  A0=80, t=2 -> A=20.  Time to reach 10: 3 hours (80*(1/2)^3=10).
// Context C: Savings doubling. b=2.  A0=500, t=2 -> A=2000.  Time to reach 4000: 3 periods.

// Precompute b as decimal for display; use fraction form in code.
// Context A: b=2, A0=100, t_eval=3, A_eval=800, target=1600, t_target=4
// Context B: b=0.5, A0=80, t_eval=2, A_eval=20, target=10, t_target=3
// Context C: b=2, A0=500, t_eval=2, A_eval=2000, target=4000, t_target=3

$contexts    = array("bacteria", "drug", "savings")
$a0s         = array(100,    80,    500)
$bs_num      = array(2,       1,      2)    // numerator of b
$bs_den      = array(1,       2,      1)    // denominator of b
$bs_dec      = array(2,     0.5,      2)    // decimal for computation
$bs_disp     = array("2", "1/2",    "2")   // display string
$t_evals     = array(3,       2,      2)
$a_evals     = array(800,    20,   2000)
$targets     = array(1600,   10,   4000)
$t_targets   = array(4,       3,      3)
$time_units  = array("hours", "hours", "periods")
$unit_label  = array("hour", "hour", "period")
$intro_strs  = array(
  "A bacterial colony starts with 100 cells and doubles every hour.",
  "A patient takes a drug with an initial amount of 80 mg. The drug's half-life is 1 hour, so the amount halves each hour.",
  "A savings plan starts with $500 and doubles every period."
)
$model_strs  = array(
  "A(t) = 100 * 2^t",
  "A(t) = 80 * (1/2)^t",
  "A(t) = 500 * 2^t"
)
$grow_decay  = array("growth", "decay", "growth")
// Part (b) answer: 0 = "Exponential growth (b > 1)", 1 = "Exponential decay (b < 1)"
$ans1s       = array(0, 1, 0)

$picked = jointrandfrom($contexts, $a0s, $bs_dec, $bs_disp, $t_evals, $a_evals, $targets, $t_targets, $time_units, $unit_label, $intro_strs, $model_strs, $grow_decay, $ans1s)
$ctx        = $picked[0]
$a0         = $picked[1]
$b_dec      = $picked[2]
$b_disp     = $picked[3]
$t_eval     = $picked[4]
$a_eval     = $picked[5]
$target_val = $picked[6]
$t_target   = $picked[7]
$time_unit  = $picked[8]
$unit_lbl   = $picked[9]
$intro_str  = $picked[10]
$model_str  = $picked[11]
$gd_str     = $picked[12]
$answer[1]  = $picked[13]

$answer[0] = $a_eval
$answer[2] = $t_target

$questions[1] = array(
  "Exponential growth (b > 1)",
  "Exponential decay (0 < b < 1)",
  "Linear growth",
  "Neither growth nor decay"
)

// Build computation string for solution part (a)
$b_steps_a = $b_disp . "^" . $t_eval
$b_steps_b = $b_disp . "^" . $t_target

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
      <p><b>Model:</b> `'.$model_str.'`</p>
      <p><b>(a)</b> Substitute `t = '.$t_eval.'`:</p>
      <p style="margin-left:1em;">`A('.$t_eval.') = '.$a0.' * ('.$b_steps_a.') = '.$a_eval.'`</p>
      <p><b>(b)</b> The base is `b = '.$b_disp.'`. Since '.$b_disp.' is '.($b_dec > 1 ? "greater than 1" : "between 0 and 1").', this model represents <b>'.$gd_str.'</b>.</p>
      <p><b>(c)</b> Find `t` such that `A(t) = '.$target_val.'`.</p>
      <p style="margin-left:1em;">`'.$a0.' * '.$b_disp.'^t = '.$target_val.'`</p>
      <p style="margin-left:1em;">`'.$b_disp.'^t = '.($target_val / $a0).'`</p>
      <p style="margin-left:1em;">Since `'.$b_disp.'^'.$t_target.' = '.($target_val / $a0).'`, we get <b>t = '.$t_target.'</b> '.$time_unit.'.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        (a) <b>'.$a_eval.'</b> &nbsp;&bull;&nbsp; (b) <b>'.$gd_str.'</b> &nbsp;&bull;&nbsp; (c) <b>t = '.$t_target.'</b> '.$time_unit.'
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 4px 0;">$intro_str</p>
    <p style="margin:0; text-align:center;">`$model_str`</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the value of <b>A($t_eval)</b>? <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Based on the value of the base `b = $b_disp`, what type of model is this? <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> After how many <b>$time_unit</b> will the amount first reach <b>$target_val</b>? <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>


// === ANSWER ===

$solutionguide
