// === NAME - DESCRIPTION: Error Consequences - both errors stated against a flipped null, where swapping H0 and Ha swaps the errors ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A scenario where the null is flipped between contexts. Parts: (a) choices - the Type I error
// in context (b) choices - the Type II error in context.
// Invariant: both answers are constant per scenario AND match the flipped null.

$anstypes = array("choices", "choices")

$cases = array(
  array("An environmental lab tests water samples for E. coli. The null hypothesis is `H_0`: the sample does NOT contain E. coli; the alternative is `H_a`: the sample contains E. coli.",
        "Judge the sample contaminated when in fact it contains no E. coli.",
        "Judge the sample clean when in fact it contains E. coli."),
  array("An environmental lab tests water samples for E. coli. The null hypothesis is `H_0`: the sample contains E. coli; the alternative is `H_a`: the sample does NOT contain E. coli.",
        "Judge the sample clean when in fact it contains E. coli.",
        "Judge the sample contaminated when in fact it contains no E. coli."),
  array("The FDA reviews a new drug. The null hypothesis is `H_0`: the drug is unsafe; the alternative is `H_a`: the drug is safe.",
        "Approve the drug when in fact it is unsafe.",
        "Reject the drug when in fact it is safe."),
  array("The FDA reviews a new drug. The null hypothesis is `H_0`: the drug is safe; the alternative is `H_a`: the drug is unsafe.",
        "Reject the drug when in fact it is safe.",
        "Approve the drug when in fact it is unsafe.")
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$type1 = $cases[$i][1]
$type2 = $cases[$i][2]

$questions[0] = array(
  $type1,
  $type2,
  "The sample was too small to be useful."
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  $type2,
  $type1,
  "The sample was too small to be useful."
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
      <p><span class="term-label">Part (a) &mdash; the Type I error.</span> Rejecting `H_0` when it is true: ' . $type1 . '</p>
      <p><span class="term-label">Part (b) &mdash; the Type II error.</span> Failing to reject `H_0` when it is false: ' . $type2 . '</p>
      <p><span class="term-label">The flipped-null lesson.</span> Naming the null first matters so much here &mdash; swap `H_0` and `H_a` and you swap the two errors along with them, including which one you were trying hardest to avoid. The same context with the null swapped must produce swapped errors.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the Type I error in this context?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the Type II error in this context?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
