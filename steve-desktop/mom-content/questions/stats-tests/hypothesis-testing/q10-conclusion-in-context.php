// === NAME - DESCRIPTION: Write the conclusion of a hypothesis test in context - pick the correct phrasing that links the statistical decision back to the original claim ===
// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL ===

$displayformat = "default"

// Each scenario gives a context, the claim being tested, the decision (reject / fail to reject),
// and four candidate conclusion sentences. Exactly one is phrased correctly.

// Scenario 0 (reject): drug effectiveness > 60%
// Scenario 1 (fail to reject): mean exam score > 70
// Scenario 2 (reject): bolt diameter differs from 10mm
// Scenario 3 (fail to reject): proportion voters in favor < 0.50
// Scenario 4 (reject): more than 30% buy seasonal drink

$ctxs = array(
  "A pharmaceutical company tests whether its new pain reliever is effective for more than 60% of patients. After running the study at significance level `alpha` = 0.05, the team <b>rejects</b> the null hypothesis.",
  "A teacher tests whether the mean score on a state exam is greater than 70 points. At significance level `alpha` = 0.05, the analysis <b>fails to reject</b> the null hypothesis.",
  "A manufacturer tests whether the mean diameter of its bolts differs from 10mm. At significance level `alpha` = 0.01, the team <b>rejects</b> the null hypothesis.",
  "A polling firm tests whether less than 50% of voters favor a new bill. At significance level `alpha` = 0.10, the analysis <b>fails to reject</b> the null hypothesis.",
  "A coffee shop tests whether more than 30% of customers buy the seasonal drink. At significance level `alpha` = 0.05, the analysis <b>rejects</b> the null hypothesis."
)

// For each scenario, four candidate conclusions in a fixed order. Index of correct one in $correctIdx.
// Pattern (for REJECT):
//   0 = "We have proven the claim is true"          (WRONG: 'proven' overreach)
//   1 = "There is significant evidence to support the claim that ..."  (CORRECT)
//   2 = "We accept the alternative hypothesis as true"  (WRONG: 'accept')
//   3 = "We fail to find evidence for the claim"  (WRONG decision)
// Pattern (for FAIL TO REJECT):
//   0 = "We have proven the null is true"           (WRONG)
//   1 = "We do not have enough evidence to support the claim that ..."  (CORRECT)
//   2 = "There is significant evidence to support the claim"  (WRONG decision)
//   3 = "We accept the null hypothesis as true"     (WRONG)

$opt0 = array(
  "We have proven that the pain reliever works for more than 60% of patients.",
  "We have proven that the mean state exam score is greater than 70 points.",
  "We have proven that the mean bolt diameter differs from 10mm.",
  "We have proven that less than 50% of voters favor the new bill.",
  "We have proven that more than 30% of customers buy the seasonal drink."
)
$opt1 = array(
  "There is statistically significant evidence that the pain reliever works for more than 60% of patients.",
  "We do not have enough evidence to conclude that the mean state exam score is greater than 70 points.",
  "There is statistically significant evidence that the mean bolt diameter differs from 10mm.",
  "We do not have enough evidence to conclude that less than 50% of voters favor the new bill.",
  "There is statistically significant evidence that more than 30% of customers buy the seasonal drink."
)
$opt2 = array(
  "We accept the alternative hypothesis as true.",
  "There is statistically significant evidence that the mean state exam score is greater than 70 points.",
  "We accept the alternative hypothesis as true.",
  "There is statistically significant evidence that less than 50% of voters favor the new bill.",
  "We accept the alternative hypothesis as true."
)
$opt3 = array(
  "We do not have evidence that the pain reliever works for more than 60% of patients.",
  "We accept the null hypothesis: the mean state exam score is equal to 70 points.",
  "We do not have evidence that the mean bolt diameter differs from 10mm.",
  "We accept the null hypothesis: the proportion of voters in favor is at least 50%.",
  "We do not have evidence that more than 30% of customers buy the seasonal drink."
)
// Index of the correct option in each scenario (always 1 here, but kept explicit)
$correctIdx = array(1, 1, 1, 1, 1)

$picked = jointrandfrom($ctxs, $opt0, $opt1, $opt2, $opt3, $correctIdx)
$ctx = $picked[0]

$questions = array($picked[1], $picked[2], $picked[3], $picked[4])
$answers = $picked[5]

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
      <p><b>Conclusion-writing rules:</b></p>
      <ul>
        <li>Phrase results in terms of <i>evidence</i>, not <i>proof</i>.</li>
        <li><b>Reject `H_0`</b> &rarr; "there is statistically significant evidence that [claim is true]".</li>
        <li><b>Fail to reject `H_0`</b> &rarr; "we do NOT have enough evidence to conclude [claim]"; do NOT say "we accept the null".</li>
        <li>Always refer back to the original claim in plain English.</li>
      </ul>
      <div style="margin:10px 0; padding:0.6em 1em; background:#fff3e0; border-left:4px solid #ff9800; border-radius:0 8px 8px 0;">
        <b>Common pitfalls:</b> "we proved", "we accept the null", or stating the WRONG decision relative to the question all turn an otherwise correct test into a wrong conclusion. Match wording to the decision and stay in evidence language.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">$ctx</p>
    <p style="margin:0;">Which of the following is the <b>best</b> conclusion in context?</p>
  </div>
</div>


// === ANSWER ===

$solutionguide
