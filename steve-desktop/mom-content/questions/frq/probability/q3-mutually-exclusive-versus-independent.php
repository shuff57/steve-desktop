// === NAME - DESCRIPTION: Mutually Exclusive or Independent - Given three probabilities, run both tests, state both verdicts, and explain why they are different questions ===
// === SET QUESTION TYPE TO: essay ===

// === COMMON CONTROL ===

// The FRQ for 3.2, written 2026-08-10 to match pre-frq-grade-an-independence-check exactly -- same
// three categories, same point split, same target sentences.
//
// The numbers are chosen so the joint IS the product: the events are NOT mutually exclusive and ARE
// independent. The two verdicts point opposite ways, so a student who has merged the two tests
// cannot produce both. That is the whole reason this question exists.

loadlibrary("stats")

$anstypes = array("essay")
$displayformat[0] = 'editornopaste'

$i = rand(0, 2)

$settings = array(
  "students at a college, where A is taking a lab science and B is holding a campus job",
  "commuters in a city, where A is owning a bicycle and B is holding a transit pass",
  "households in a town, where A is subscribing to a streaming service and B is owning a pet"
)
$setting = $settings[$i]

$aPct = 10 * rand(4, 6)
$bPct = 10 * rand(3, 5)
$jointPct = $aPct * $bPct / 100

$aDec = $aPct / 100
$bDec = $bPct / 100
$jointDec = $jointPct / 100

$r_mutex = "They are not mutually exclusive, because P(A and B) is " . $jointDec . " and that is not zero -- the two can happen together."
$r_indep = "They are independent, because P(A) x P(B) = " . $aDec . " x " . $bDec . " = " . $jointDec . ", which equals the joint probability given."
$r_distinguish = "These are two different questions settled by two different comparisons: one against zero, the other against the product of the two probabilities. Answering one leaves the other open."

$model = $r_mutex . " " . $r_indep . " " . $r_distinguish

$css = '
<style>
  .frq3 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .frq3 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .frq3 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .frq3 .rubric-container summary::-webkit-details-marker { display:none; }
  .frq3 .rubric-content { padding:0.75em; background:#fafafa; }
  .frq3 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .frq3 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .frq3 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .frq3 .row-colored { background:#fff9ea; }
  .frq3 .facts { border-collapse:collapse; margin:10px 0; font-size:15px; }
  .frq3 .facts td, .frq3 .facts th { border:1px solid #d1d5db; padding:6px 14px; text-align:left; }
  .frq3 .facts th { background:#f0f4ff; }
</style>'

$rubric = $css . '
<div class="frq3">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist &mdash; 10 points</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Test for Mutual Exclusivity<br>(3 pts)</b></td>
            <td>Compare the joint probability against zero and state the verdict.</td></tr>
          <tr><td style="text-align:center;"><b>Test for Independence<br>(4 pts)</b></td>
            <td>Compare the product of the two probabilities against the joint probability and state the verdict.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Distinguish the Two<br>(3 pts)</b></td>
            <td>Say why these are separate questions, so neither verdict is read as settling the other.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$factBlock = '
<div class="frq3">
  <table class="facts">
    <tr><th>Quantity</th><th>Value</th></tr>
    <tr><td>P(A)</td><td>' . $aDec . '</td></tr>
    <tr><td>P(B)</td><td>' . $bDec . '</td></tr>
    <tr><td>P(A and B)</td><td>' . $jointDec . '</td></tr>
  </table>
</div>'

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-body { padding:0.75em; background:#fafafa; }
  .term-label { font-weight:700; color:#1865f2; }
  .ideal { display:block; background:#eef4ff; border-left:3px solid #1865f2; padding:8px 12px; margin:6px 0; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>Model Response and Marking Notes</summary>
    <div class="sol-body">
      <p><span class="term-label">A full-credit answer.</span></p>
      <span class="ideal">' . $model . '</span>
      <p><span class="term-label">Why these numbers.</span> `' . $aDec . ' xx ' . $bDec . ' = ' . $jointDec . '`, exactly the joint. So the honest answers are NOT mutually exclusive and IS independent &mdash; opposite directions. A student who thinks the two tests are the same question will give the same verdict twice and lose both the second and third categories.</p>
      <p><span class="term-label">Test for Mutual Exclusivity (3).</span> Needs the comparison against zero, not just the word. "They overlap" with no reference to P(A and B) earns 1.</p>
      <p><span class="term-label">Test for Independence (4).</span> Needs the product computed and compared. A verdict with no arithmetic earns 1. Accept P(A given B) = P(A) as an equivalent route, fully credited.</p>
      <p><span class="term-label">Distinguish the Two (3).</span> Award only for a statement about the two TESTS, not a restatement of the two verdicts. This is the category most often missing, and it is the one the section is about.</p>
      <p><span class="term-label">Common wrong answer worth recognising.</span> "They are not mutually exclusive, so they must be dependent." That sentence is the exact confusion this question is built to expose &mdash; it earns the first category and nothing after it.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 8px 0;"><b>The survey.</b> A study of $setting reported the following.</p>
    $factBlock
    <p style="margin:8px 0 0 0;">Decide whether A and B are mutually exclusive, decide whether they are independent, and explain how those two questions differ. Show the comparison behind each verdict.</p>
  </div>
  $rubric
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    $answerbox[0]
  </div>
</div>

// === ANSWER ===

$solutionguide
