// === NAME - DESCRIPTION: Mutually Exclusive or Independent - Given three probabilities, run both tests, state both verdicts, and explain why they are different questions ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The FRQ for 3.2, written 2026-08-10 to match pre-frq-grade-an-independence-check exactly: same
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

$r_mutex = "They are not mutually exclusive, because P(A and B) is " . $jointDec . " and that is not zero: the two can happen together."
$r_indep = "They are independent, because P(A) x P(B) = " . $aDec . " x " . $bDec . " = " . $jointDec . ", which equals the joint probability given."
$r_distinguish = "These are two different questions settled by two different comparisons: one against zero, the other against the product of the two probabilities. Answering one leaves the other open."

$model = $r_mutex . " " . $r_indep . " " . $r_distinguish
$t_zero = "P(A and B) = " . $jointDec . ", which is not zero."
$t_product = $aDec . " x " . $bDec . " = " . $jointDec . ", which equals P(A and B)."

$css = '
<style>
  .frq3 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .frq3 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .frq3 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .frq3 .rubric-container summary::-webkit-details-marker { display:none; }
  .frq3 .rubric-container .arrow-open { display:none; }
  .frq3 .rubric-container details[open] .arrow-closed { display:none; }
  .frq3 .rubric-container details[open] .arrow-open { display:inline; }
  .frq3 .rubric-table ul { margin:0; padding-left:1.1em; list-style:none; }
  .frq3 .ideal-ans { display:block; background:#e8f5e9; font-style:italic; font-weight:bold; font-size:0.95em; margin:5px 0 10px 0; border-left:3px solid #4CAF50; padding-left:8px; }
  .frq3 .full-response-box { margin-top:15px; border:2px solid #4CAF50; background:#e8f5e9; padding:15px; border-radius:5px; }
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
  <details>
    <summary>
      <span class="arrow-closed">&#9656;</span><span class="arrow-open">&#9662;</span>
      Click to View Grading Checklist
    </summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr>
            <th>Category</th>
            <th>What to include</th>
          </tr>
          <tr class="row-colored">
            <td><b>Test for Mutual Exclusivity<br>(3 pts)</b></td>
            <td>
              <ul>
                <li><label><input type="checkbox"> Compare P(A and B) with zero.</label></li>
                <li><label><input type="checkbox"> Say whether A and B are mutually exclusive.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td><b>Test for Independence<br>(4 pts)</b></td>
            <td>
              <ul>
                <li><label><input type="checkbox"> Multiply P(A) and P(B) and compare the result with P(A and B).</label></li>
                <li><label><input type="checkbox"> Say whether A and B are independent, and show the numbers.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td><b>Distinguish the Two<br>(3 pts)</b></td>
            <td>
              <ul>
                <li><label><input type="checkbox"> Explain why knowing one answer does not tell you the other.</label></li>
              </ul>
            </td>
          </tr>
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

$rubricanswerbutton = $css . '
<div class="frq3">
<div class="rubric-container">
  <details>
    <summary>
      <span class="arrow-closed">&#9656;</span><span class="arrow-open">&#9662;</span>
      Rubric &amp; Model Response
    </summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr>
            <th>Category</th>
            <th>Checklist &amp; Ideal Targets</th>
          </tr>
          <tr class="row-colored">
            <td><b>Test for Mutual Exclusivity<br>(3 pts)</b></td>
            <td>
              <ul>
                <li>Compare P(A and B) with zero.
                    <span class="ideal-ans">Target: "' . $t_zero . '"</span></li>
                <li>Say whether A and B are mutually exclusive.
                    <span class="ideal-ans">Target: "' . $r_mutex . '"</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td><b>Test for Independence<br>(4 pts)</b></td>
            <td>
              <ul>
                <li>Multiply P(A) and P(B) and compare the result with P(A and B).
                    <span class="ideal-ans">Target: "' . $t_product . '"</span></li>
                <li>Say whether A and B are independent, and show the numbers.
                    <span class="ideal-ans">Target: "' . $r_indep . '"</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td><b>Distinguish the Two<br>(3 pts)</b></td>
            <td>
              <ul>
                <li>Explain why knowing one answer does not tell you the other.
                    <span class="ideal-ans">Target: "' . $r_distinguish . '"</span></li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="full-response-box">
        <span>Model Narrative Response:</span><br><br>
        ' . $model . '
      </div>
      <p><b>Common wrong answer.</b> "They are not mutually exclusive, so they must be dependent." That is the confusion this question is built to expose. It earns the first category and nothing after it.</p>
      <p><b>Marking note.</b> Accept P(A given B) = P(A) as an equivalent route to the independence verdict, with full credit.</p>
    </div>
  </details>
</div>
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

$rubricanswerbutton
