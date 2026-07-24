// === NAME - DESCRIPTION: ANOVA conclusion - Interpret F result and write conclusion in context ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices","choices","choices")

// Three scenarios. Each gives F, df1, df2, p-value, alpha.
// Part a: decision (reject / fail to reject)
// Part b: best conclusion in context (4 options)
// Part c: what does it mean for the population (4 options)

$intros = array(
  "A teacher compares mean test scores across 4 study-method groups (n = 60 total) using one-way ANOVA.",
  "A clinic compares mean recovery times across 3 physical-therapy programs (n = 36 total) using one-way ANOVA.",
  "A market researcher compares mean weekly grocery spending across 5 U.S. regions (n = 80 total) using one-way ANOVA."
)
$grps   = array("study methods", "therapy programs", "U.S. regions")
$outs   = array("test scores", "recovery times", "weekly grocery spending")
$Fs     = array(6.42, 2.18, 4.05)
$df1s   = array(3, 2, 4)
$df2s   = array(56, 33, 75)
$pvals  = array(0.0008, 0.129, 0.005)
$alphas = array(0.05, 0.05, 0.05)
$picked = jointrandfrom($intros, $grps, $outs, $Fs, $df1s, $df2s, $pvals, $alphas)
$intro = $picked[0]
$grp   = $picked[1]
$out   = $picked[2]
$F     = $picked[3]
$df1   = $picked[4]
$df2   = $picked[5]
$pval  = $picked[6]
$alpha = $picked[7]

$reject = 0
if ($pval < $alpha) { $reject = 1 }

// Part a: decision
$choices[0] = array(
  "Reject `H_0`",
  "Fail to reject `H_0`"
)
$answer[0] = 1 - $reject
$noshuffle[0] = "all"

// Part b: conclusion in context
$concl_reject = "At alpha = " . $alpha . ", there is sufficient evidence to conclude that at least one of the population mean " . $out . " differs across the " . $grp . ". The ANOVA does not specify which group(s) differ."
$concl_fail   = "At alpha = " . $alpha . ", there is not sufficient evidence to conclude that the population mean " . $out . " differ across the " . $grp . "."
$concl_wrong1 = "The ANOVA proves that all " . $grp . " produce different mean " . $out . "."
$concl_wrong2 = "Because F is positive, we automatically reject `H_0` regardless of the p-value."

$choices[1] = array(
  $concl_reject,
  $concl_fail,
  $concl_wrong1,
  $concl_wrong2
)
$answer[1] = 1
if ($reject == 1) { $answer[1] = 0 }
$noshuffle[1] = "all"

// Part c: what does it mean for the population
$choices[2] = array(
  "Even after rejecting, ANOVA only signals that at least one group mean differs; it does not say which ones. Follow-up pairwise comparisons would be needed.",
  "Even after failing to reject, it remains possible that some group means differ; the test simply did not provide strong enough evidence at the chosen alpha.",
  "Rejecting proves the means are all different; failing to reject proves they are all equal.",
  "ANOVA is only valid when all groups have exactly the same sample size."
)
$answer[2] = 1
if ($reject == 1) { $answer[2] = 0 }
$noshuffle[2] = "all"

$displayformat[0] = "select"
$displayformat[1] = "select"
$displayformat[2] = "select"

$decision_word = "fail to reject"
if ($reject == 1) { $decision_word = "reject" }

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
      <p><b>Hypotheses.</b> `H_0:` all group means are equal. `H_a:` at least one mean differs.</p>
      <p><b>Part a.</b> Compare `p = ' . $pval . '` to `alpha = ' . $alpha . '`. Since ' . ($reject==1 ? "p &lt; alpha" : "p &gt;= alpha") . ', we ' . $decision_word . ' `H_0`.</p>
      <p><b>Part b.</b> In context: ' . ($reject==1 ? "there is evidence that at least one mean " . $out . " differs across the " . $grp . "." : "there is not enough evidence to conclude any mean " . $out . " differs across the " . $grp . ".") . '</p>
      <p><b>Part c.</b> ANOVA tells us only whether at least one group mean differs, not which ones. After rejecting, follow up with pairwise comparisons (e.g., Tukey HSD) to identify which groups differ.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#fff8e1; border-left:4px solid #f59e0b; border-radius:0 8px 8px 0;">
        <b>Common trap:</b> rejecting `H_0` does NOT mean all groups differ from each other. It only means at least one group has a different mean.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===
<style>select{max-width:100%;width:100%;box-sizing:border-box;display:block;margin:4px 0;}</style>

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">$intro</p>
    <p style="margin:0;">The ANOVA reports `F = $F` with `df_{B} = $df1` and `df_{W} = $df2`, so `p = $pval`. Use significance level `alpha = $alpha`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Decision at `alpha = $alpha`:
    <div style="margin-top:12px;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which conclusion best fits the result in context?
    <div style="margin-top:12px;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What does this decision mean for the population?
    <div style="margin-top:12px;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
