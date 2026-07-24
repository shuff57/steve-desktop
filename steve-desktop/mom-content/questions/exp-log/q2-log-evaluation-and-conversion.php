// === NAME - DESCRIPTION: Log Evaluation and Conversion - Evaluate a logarithm and convert between log and exponential form ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats");

$anstypes = array("number", "choices", "choices")
$displayformat[1] = "select"
$displayformat[2] = "select"
$noshuffle[1] = "all"
$noshuffle[2] = "all"

// Scenarios: b in {2, 3, 5, 10}, k in {2, 3, 4}, x = b^k
// log_b(x) = k (clean integer answer)
// (A) log_2(4)  = 2   b=2, k=2, x=4
// (B) log_2(8)  = 3   b=2, k=3, x=8
// (C) log_2(16) = 4   b=2, k=4, x=16
// (D) log_3(9)  = 2   b=3, k=2, x=9
// (E) log_3(27) = 3   b=3, k=3, x=27
// (F) log_5(25) = 2   b=5, k=2, x=25
// (G) log_10(100) = 2  b=10,k=2,x=100

$bs   = array(2,  2,  2,  3,  3,  5,  10)
$ks   = array(2,  3,  4,  2,  3,  2,   2)
$xs   = array(4,  8, 16,  9, 27, 25, 100)

$picked = jointrandfrom($bs, $ks, $xs)
$b_val  = $picked[0]
$k_val  = $picked[1]
$x_val  = $picked[2]

$answer[0] = $k_val

// Part (b): convert log_b(x) = k  to exponential form b^k = x
// Correct: b^k = x. Distractors swap b/k, or use k^b, or log_k(x)=b.
$answer[1] = 0
$questions[1] = array(
  "" . $b_val . "^" . $k_val . " = " . $x_val,
  "" . $k_val . "^" . $b_val . " = " . $x_val,
  "" . $b_val . "^" . $x_val . " = " . $k_val,
  "log_" . $k_val . "(" . $x_val . ") = " . $b_val
)

// Part (c): convert b^k = x  to logarithmic form log_b(x) = k
// Correct: log_b(x) = k. Distractors swap b/k inside log, or use wrong RHS.
$answer[2] = 0
$questions[2] = array(
  "log_" . $b_val . "(" . $x_val . ") = " . $k_val,
  "log_" . $k_val . "(" . $x_val . ") = " . $b_val,
  "log_" . $b_val . "(" . $k_val . ") = " . $x_val,
  "log_" . $x_val . "(" . $b_val . ") = " . $k_val
)

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
      <p><b>Key relationship:</b> `log_b(x) = k` means the same thing as `b^k = x`. The base `b` raised to the exponent `k` equals `x`.</p>
      <p><b>(a)</b> `log_'.$b_val.'('.$x_val.')` asks: "What power of '.$b_val.' gives '.$x_val.'?"</p>
      <p style="margin-left:1em;">`'.$b_val.'^'.$k_val.' = '.$x_val.'`, so `log_'.$b_val.'('.$x_val.') = '.$k_val.'`.</p>
      <p><b>(b)</b> Convert `log_'.$b_val.'('.$x_val.') = '.$k_val.'` to exponential form.</p>
      <p style="margin-left:1em;">Base `'.$b_val.'`, exponent `'.$k_val.'`, result `'.$x_val.'` gives `'.$b_val.'^'.$k_val.' = '.$x_val.'`.</p>
      <p><b>(c)</b> Convert `'.$b_val.'^'.$k_val.' = '.$x_val.'` to logarithmic form.</p>
      <p style="margin-left:1em;">Base is `'.$b_val.'`, exponent is `'.$k_val.'`, so `log_'.$b_val.'('.$x_val.') = '.$k_val.'`.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        (a) <b>'.$k_val.'</b> &nbsp;&bull;&nbsp; (b) <b>'.$b_val.'^'.$k_val.' = '.$x_val.'</b> &nbsp;&bull;&nbsp; (c) <b>log_'.$b_val.'('.$x_val.') = '.$k_val.'</b>
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">This question explores the connection between logarithms and exponentials using base <b>$b_val</b>.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Evaluate: <b>`log_$b_val($x_val)`</b> <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which equation is the <b>exponential form</b> of `log_$b_val($x_val) = $k_val`? <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which equation is the <b>logarithmic form</b> of `$b_val^$k_val = $x_val`? <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>


// === ANSWER ===

$solutionguide
