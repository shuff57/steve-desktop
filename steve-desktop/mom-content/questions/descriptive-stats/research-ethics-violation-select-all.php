// === NAME - DESCRIPTION: Research Ethics Violations (IRB, Informed Consent, Data Fraud) - Given a randomized scenario where a researcher skips IRB review, withholds a known risk, and selectively discards data, select every real ethical or procedural violation from a mixed list that also includes true-but-irrelevant statistical statements ===
// === SET QUESTION TYPE TO: multans ===

// === COMMON CONTROL ===

$name,$heshe,$himher,$hisher,$hishers,$himherself = randnamewpronouns()

$institutionPool = array(
  "Bellhaven University",
  "Crestmoor College",
  "Ashford State University",
  "Marlowe Institute",
  "Thornebridge University",
  "Redcliff College"
)
$institution = randfrom($institutionPool)

$productPool = array(
  "a memory supplement called NeuroBoost",
  "an energy supplement called Vitalyn",
  "a nootropic capsule called Cognivex",
  "an herbal supplement called Focalta",
  "a pre-workout powder called Amplify",
  "a relaxation supplement called Calmara"
)
$product = randfrom($productPool)

$claimPool = array(
  "short-term memory",
  "mental focus",
  "exam concentration",
  "reaction time",
  "study stamina"
)
$claim = randfrom($claimPool)

$riskPool = array(
  "a noticeable increase in resting heart rate",
  "occasional dizziness",
  "trouble sleeping the night after taking it",
  "mild nausea within an hour of taking it",
  "a spike in blood pressure"
)
$risk = randfrom($riskPool)

$n = rand(30, 70)
$numDiscarded = rand(4, 9)
$keptN = $n - $numDiscarded
$extraCredit = rand(2, 5)

$questions = array(
  "The study began enrolling volunteers before $hisher institution&#8217;s Institutional Review Board had reviewed or approved it.",
  "Volunteers were told the study was testing $product for $claim, but were never told beforehand about $risk, a known side effect of $product.",
  "After collecting results from all $n volunteers, $name discarded the scores from $numDiscarded participants whose results did not show the expected improvement, then reported conclusions based only on the remaining $keptN scores.",
  "The study enrolled only $n volunteers, which is too small a sample to generalize the results to the whole population.",
  "Volunteers were offered $extraCredit points of extra course credit for taking part in the study.",
  "The comparison group received an identical-looking placebo capsule instead of $product.",
  "The study&#8217;s results have not yet been submitted to a peer-reviewed journal for publication."
)
$answers = "0,1,2"
$scoremethod = "allornothing"

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
      <p><span class="term-label">Step 1 &mdash; IRB approval.</span> Every planned study must be reviewed and approved by an Institutional Review Board before it begins, to protect the human subjects in it. Enrolling volunteers before that review happened is a real violation, no matter how the study turns out.</p>
      <p><span class="term-label">Step 2 &mdash; Informed consent.</span> Informed consent means the risks of participation are clearly explained to subjects before they agree to take part. Leaving out $risk &mdash; a known side effect &mdash; means the volunteers never got to weigh that risk when they consented. That is a real violation too.</p>
      <p><span class="term-label">Step 3 &mdash; Data honesty.</span> Discarding the $numDiscarded scores that did not fit the expected result, and reporting conclusions from only the remaining $keptN, is exactly the kind of selective reporting that makes published findings meaningless &mdash; it is a form of data fraud, whether or not any number was directly altered.</p>
      <p><span class="term-label">Step 4 &mdash; Rule out the rest.</span> The sample size ($n) might be small, but that is a question about how far the conclusion generalizes, not an ethics or procedure violation. Extra course credit is a routine incentive, not coercion, unless refusing it carries a penalty. A placebo-controlled comparison group is good experimental design, not a violation. And a study not yet being published is completely normal and says nothing about how it was conducted.</p>
      <p><b>Answer:</b> the three real violations are no IRB review, withholding a known risk from consent, and discarding unfavorable data before reporting results. The sample size, the extra credit, the placebo group, and the publication status are all true-but-irrelevant statements.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$name, a researcher at $institution, wants to find out whether $product improves $claim, and recruits $n student volunteers, beginning the study before it has ever been submitted to $hisher university&#8217;s Institutional Review Board for review.</p>
    <p style="margin:12px 0 0 0;">Volunteers are told only that the study is testing $product for $claim. None of them are told beforehand about $risk. Each volunteer is randomly assigned to receive either $product or an identical-looking placebo capsule, and is offered $extraCredit points of extra course credit for taking part.</p>
    <p style="margin:12px 0 0 0;">After all $n volunteers finish the study, $name discards the scores from $numDiscarded participants whose results did not show the expected improvement, then writes up conclusions using only the remaining $keptN scores. The write-up has not yet been submitted anywhere for publication.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <b>Select every statement below that describes a real ethical or procedural violation in $hisher study.</b> Leave unselected any statement that is true but is not itself a violation.
    <div style="margin-top:12px;">$answerbox</div>
  </div>
</div>

// === ANSWER ===

$solutionguide
