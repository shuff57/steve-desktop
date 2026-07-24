// === NAME - DESCRIPTION: Statistical significance interpretation - Decide significance, identify wrong interpretations, distinguish from practical significance ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices","choices","choices")

// Three scenarios. Each gives p-value and context.
// Part a: is the result statistically significant? (yes / no)
// Part b: which statement is the WRONG interpretation of statistical significance?
// Part c: does statistical significance imply practical significance? (concept)

$contexts = array(
  "A pharmaceutical company tests a new pain reliever against placebo, finding the drug group reports lower pain by an average of 0.4 units on a 10-point scale.",
  "An online retailer A/B-tests a new homepage design and finds it has a click-through rate 0.6 percentage points higher than the original.",
  "A factory changes a stamping process and reports a defect rate 0.2 percentage points lower in the new process after monitoring 50,000 parts."
)
$pvals   = array(0.018, 0.003, 0.041)
$effects = array("0.4 units", "0.6 percentage points", "0.2 percentage points")

$picked = jointrandfrom($contexts, $pvals, $effects)
$ctx    = $picked[0]
$pval   = $picked[1]
$effect = $picked[2]
$alpha  = 0.05

// Part a: statistically significant (yes if p < alpha)
$answer[0] = 0
if ($pval >= $alpha) { $answer[0] = 1 }

$choices[0] = array(
  "Yes, the result is statistically significant at the chosen significance level.",
  "No, the result is not statistically significant at the chosen significance level."
)
$noshuffle[0] = "all"

// Part b: WRONG interpretation. All four options are common student misreadings; choose the one that is FALSE.
// We always make answer index 0 the wrong one, since all four are plausible-sounding traps.
$choices[1] = array(
  "Statistical significance proves the alternative hypothesis is true.",
  "Statistical significance means the observed result would be unusual if the null hypothesis were true.",
  "Statistical significance is a statement about the data, not about how big or important the effect is.",
  "Statistical significance depends on the chosen `alpha` level."
)
$answer[1] = 0
$noshuffle[1] = "all"

// Part c: stat-sig vs practical-sig
$choices[2] = array(
  "Yes, a statistically significant result always means the effect is practically important.",
  "No, statistical significance only tells us the effect is unlikely under the null; whether the effect is large enough to matter is a separate, practical-significance question."
)
$answer[2] = 1
$noshuffle[2] = "all"

$displayformat[0] = "select"
$displayformat[1] = "select"
$displayformat[2] = "select"

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
      <p><b>Part a.</b> A result is statistically significant when `p &lt; alpha`. Here `p = ' . $pval . '` and `alpha = ' . $alpha . '`, so the result <b>' . ($answer[0]==0 ? "is" : "is not") . '</b> statistically significant.</p>
      <p><b>Part b.</b> The false interpretation is "Statistical significance proves the alternative hypothesis is true." Statistical significance never <i>proves</i> a hypothesis. It only tells us the observed data would be unusual if `H_0` were true. The hypothesis could still be wrong, or there could be a Type I error.</p>
      <p><b>Part c.</b> Statistical significance and practical significance are different. A tiny effect can be statistically significant if the sample is large enough; a large, useful effect can fail to reach significance in a small study. Always ask: how big is the effect (' . $effect . ' in this case), and is it large enough to matter for the decision being made?</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#fff8e1; border-left:4px solid #f59e0b; border-radius:0 8px 8px 0;">
        <b>Common trap:</b> a low p-value does not mean the effect is large or important. It only means the data would be unusual under `H_0`.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===
<style>select{max-width:100%;width:100%;box-sizing:border-box;display:block;margin:4px 0;}</style>

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">$ctx</p>
    <p style="margin:0;">The hypothesis test produces a p-value of `$pval`, tested at significance level `alpha = $alpha`. The observed effect was about $effect.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Is the result statistically significant at the chosen `alpha` level?
    <div style="margin-top:12px;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which of the following is a <b>wrong</b> interpretation of "statistical significance"?
    <div style="margin-top:12px;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Does statistical significance imply practical significance?
    <div style="margin-top:12px;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
