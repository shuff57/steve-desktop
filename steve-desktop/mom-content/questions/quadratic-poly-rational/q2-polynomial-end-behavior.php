// === NAME - DESCRIPTION: Polynomial End Behavior - Describe the end behavior of a polynomial using the leading term test ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats");

$anstypes = array("choices", "choices")
$displayformat[0] = "select"
$displayformat[1] = "select"
$noshuffle[0] = "all"
$noshuffle[1] = "all"

// Four scenarios: (degree, leading-coeff sign)
// (A) degree 3, positive leading coeff:  x->+inf rises, x->-inf falls
// (B) degree 3, negative leading coeff:  x->+inf falls, x->-inf rises
// (C) degree 4, positive leading coeff:  x->+inf rises, x->-inf rises
// (D) degree 4, negative leading coeff:  x->+inf falls, x->-inf falls

$degs      = array(3,     3,     4,     4)
$leadcoefs = array(2,    -2,     3,    -3)
$lead_strs = array("2",  "-2",  "3",  "-3")
$func_strs = array(
  "f(x) = 2x^3 - 5x + 1",
  "f(x) = -2x^3 + 4x^2 - 7",
  "f(x) = 3x^4 - x^2 + 6",
  "f(x) = -3x^4 + 2x^3 - 1"
)
// ans[0]: behavior as x->+inf.  0=rises, 1=falls
// ans[1]: behavior as x->-inf.  0=rises, 1=falls
$ans0s = array(0,  1,  0,  1)
$ans1s = array(1,  0,  0,  1)

$picked = jointrandfrom($degs, $leadcoefs, $lead_strs, $func_strs, $ans0s, $ans1s)
$deg      = $picked[0]
$leadcoef = $picked[1]
$lead_str = $picked[2]
$func_str = $picked[3]
$answer[0] = $picked[4]
$answer[1] = $picked[5]

// Choices for both dropdowns
$questions[0] = array("Rises (goes to +infinity)", "Falls (goes to -infinity)")
$questions[1] = array("Rises (goes to +infinity)", "Falls (goes to -infinity)")

// Degree parity label for solution
$deg_parity = "odd"
if ($deg == 4) { $deg_parity = "even" }

$lead_sign = "positive"
if ($leadcoef < 0) { $lead_sign = "negative" }

// Build end-behavior description strings for solution
$pos_inf_str = "rises (goes to +infinity)"
if ($answer[0] == 1) { $pos_inf_str = "falls (goes to -infinity)" }
$neg_inf_str = "rises (goes to +infinity)"
if ($answer[1] == 1) { $neg_inf_str = "falls (goes to -infinity)" }

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
      <p><b>Leading Term Test:</b> Only the leading term determines end behavior.</p>
      <p>For <b>`'.$func_str.'`</b>, the leading term is `'.$lead_str.'x^'.$deg.'`.</p>
      <p>Degree: <b>'.$deg.'</b> ('.$deg_parity.') &nbsp;&bull;&nbsp; Leading coefficient: <b>'.$lead_str.'</b> ('.$lead_sign.')</p>
      <p><b>Rules:</b></p>
      <ul>
        <li>Odd degree, positive leading coeff: falls left, rises right.</li>
        <li>Odd degree, negative leading coeff: rises left, falls right.</li>
        <li>Even degree, positive leading coeff: rises on both ends.</li>
        <li>Even degree, negative leading coeff: falls on both ends.</li>
      </ul>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        As x &rarr; +&infin;: <b>'.$pos_inf_str.'</b><br>
        As x &rarr; -&infin;: <b>'.$neg_inf_str.'</b>
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 4px 0;">Consider the polynomial function:</p>
    <p style="margin:0; text-align:center;">`$func_str`</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Describe the end behavior as <b>x &rarr; +&infin;</b>. <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Describe the end behavior as <b>x &rarr; -&infin;</b>. <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>


// === ANSWER ===

$solutionguide
