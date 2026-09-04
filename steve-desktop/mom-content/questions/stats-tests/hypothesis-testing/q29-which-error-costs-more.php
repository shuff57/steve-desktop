// === NAME - DESCRIPTION: Which Error Costs More - the heavier consequence in a high-stakes scenario, and the reason ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A high-stakes scenario. Parts: (a) choices - which error carries the heavier consequence
// (b) choices - a one-sentence reason.
// Invariant: both answers are constant per scenario and the reason matches the chosen error.

$anstypes = array("choices", "choices")

$cases = array(
  array("The FDA reviews a new drug. The null hypothesis is `H_0`: the drug is unsafe; the alternative is `H_a`: the drug is safe. Approving a drug that is actually unsafe is a Type I error; rejecting a drug that is actually safe is a Type II error.",
        "The Type I error: approving an unsafe drug puts patients at risk.",
        "The Type I error: approving an unsafe drug puts patients at risk."),
  array("Doctors must decide whether to operate. The null hypothesis is `H_0`: the procedure will go well; the alternative is `H_a`: the procedure will not go well. Declining an operation that would have gone well is a Type I error; performing an operation that goes badly is a Type II error.",
        "The Type II error: the patient gets a procedure that goes badly.",
        "The Type II error: the patient gets a procedure that goes badly."),
  array("A smoke-detection system makes a decision each minute. The null hypothesis is `H_0`: there is NO fire; the alternative is `H_a`: there IS a fire. Sounding the alarm with no fire is a Type I error; staying silent during a real fire is a Type II error.",
        "The Type II error: the alarm staying silent during a real fire.",
        "The Type II error: the alarm staying silent during a real fire.")
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$heavier = $cases[$i][1]
$reason = $cases[$i][2]

$questions[0] = array(
  $heavier,
  "The other error: it is the one that costs more in this scenario.",
  "Neither: both errors are equally bad in every scenario."
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  $reason,
  "The error is more expensive because it is more likely to happen.",
  "The error is more expensive because it involves more arithmetic."
)
$answer[1] = 0
$noshuffle[1] = "all"

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
      <p><span class="term-label">Part (a): the heavier consequence.</span> ' . $heavier . '</p>
      <p><span class="term-label">Part (b): the reason.</span> ' . $reason . '</p>
      <p>The judgment behind every significance level is which mistake you can least afford. A Type I error is the alarm going off while you make toast; a Type II error is the alarm staying silent during a real fire: and they are not equally bad.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which error carries the heavier consequence in this scenario?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the reason?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
