// === NAME - DESCRIPTION: Exponential Evaluation - Evaluate f(x) = a * b^x at x = 0, 1, 2 ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats");

$anstypes = array("number", "number", "number")

// Five (a, b) combos with clean integer outputs at x = 0, 1, 2.
// f(0) = a * b^0 = a
// f(1) = a * b^1 = a*b
// f(2) = a * b^2 = a*b^2
// (A) a=1, b=2: f(0)=1, f(1)=2,  f(2)=4
// (B) a=2, b=2: f(0)=2, f(1)=4,  f(2)=8
// (C) a=3, b=2: f(0)=3, f(1)=6,  f(2)=12
// (D) a=1, b=3: f(0)=1, f(1)=3,  f(2)=9
// (E) a=2, b=3: f(0)=2, f(1)=6,  f(2)=18

$a_vals  = array(1,  2,  3,  1,  2)
$b_vals  = array(2,  2,  2,  3,  3)
$f0_vals = array(1,  2,  3,  1,  2)
$f1_vals = array(2,  4,  6,  3,  6)
$f2_vals = array(4,  8, 12,  9, 18)
$eq_strs = array(
  "f(x) = 2^x",
  "f(x) = 2 * 2^x",
  "f(x) = 3 * 2^x",
  "f(x) = 3^x",
  "f(x) = 2 * 3^x"
)

$picked = jointrandfrom($a_vals, $b_vals, $f0_vals, $f1_vals, $f2_vals, $eq_strs)
$a_val  = $picked[0]
$b_val  = $picked[1]
$f0     = $picked[2]
$f1     = $picked[3]
$f2     = $picked[4]
$eq_str = $picked[5]

$answer[0] = $f0
$answer[1] = $f1
$answer[2] = $f2

// For solution display: a * b^0, a * b^1, a * b^2
$b2 = $b_val * $b_val

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
      <p><b>Function:</b> `'.$eq_str.'`</p>
      <p><b>(a)</b> `f(0) = '.$a_val.' * '.$b_val.'^0 = '.$a_val.' * 1 = '.$f0.'`</p>
      <p><b>(b)</b> `f(1) = '.$a_val.' * '.$b_val.'^1 = '.$a_val.' * '.$b_val.' = '.$f1.'`</p>
      <p><b>(c)</b> `f(2) = '.$a_val.' * '.$b_val.'^2 = '.$a_val.' * '.$b2.' = '.$f2.'`</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        f(0) = <b>'.$f0.'</b> &nbsp;&bull;&nbsp; f(1) = <b>'.$f1.'</b> &nbsp;&bull;&nbsp; f(2) = <b>'.$f2.'</b>
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 4px 0;">Consider the exponential function:</p>
    <p style="margin:0; text-align:center;">`$eq_str`</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find <b>f(0)</b>. <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find <b>f(1)</b>. <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find <b>f(2)</b>. <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>


// === ANSWER ===

$solutionguide
