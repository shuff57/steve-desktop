// === NAME - DESCRIPTION: Binomial Setup and Interpretation - Check the three conditions, name n and p, write and compute a binomial probability, and say what it means in context ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The FRQ for 4.3, written 2026-11-06 for the Chapter 4 Group Test. It follows the shape
// pre-frq-grade-a-binomial-setup defined (parameters / probability question / compute and
// interpret) and adds the three-conditions check, so the four categories are:
//   Check the Conditions (4) / Name the Parameters (2) / State and Compute (4) / Interpret (4) = 14.
//
// The three scenarios are different from the pre-FRQ's three, so a student who graded the pre-FRQ
// has not seen these. Each scenario uses a different inequality (at least, at most, more than),
// because translating the English into the right inequality is where students lose the point.
//
// CATEGORY PURITY: the conditions sentence names the three conditions and nothing about n or p;
// the parameters sentence states n, p and the model and nothing else; the question sentence writes
// the inequality and the value; the interpretation sentence reads the answer in context and
// compares it with the mean np.

loadlibrary("stats")

$anstypes = array("essay")
$displayformat[0] = 'editornopaste'

$i = rand(0, 2)

$setups = array(
  "A basketball player makes 65% of her free throws, and each shot is independent of the others. She takes 12 free throws in a game.",
  "A seed company says 90% of its seeds sprout, and each seed sprouts independently of the others. A packet holds 20 seeds.",
  "A call-center agent makes a sale on 30% of calls, and each call is independent of the others. The agent makes 15 calls in a shift."
)
$asks = array(
  "Find the probability that she makes at least 10 of the 12 shots.",
  "Find the probability that at most 16 of the 20 seeds sprout.",
  "Find the probability that the agent makes more than 6 sales."
)
$trials = array("one free throw", "one seed", "one call")
$successes = array("the shot goes in", "the seed sprouts", "the call ends in a sale")
$nVals = array(12, 20, 15)
$pVals = array("0.65", "0.90", "0.30")
$qVals = array("0.35", "0.10", "0.70")
$events = array("P(X >= 10)", "P(X <= 16)", "P(X > 6)")
$eventWords = array(
  "at least 10 means 10, 11 or 12 shots",
  "at most 16 means 0 up to 16 seeds",
  "more than 6 means 7 or more sales, and 6 is not included"
)
$answers = array("0.1513", "0.1330", "0.1311")
$means = array("7.8", "18", "4.5")
$units = array("shots", "seeds", "sales")
$meanings = array(
  "About 15% of games like this would have 10 or more made shots.",
  "About 13% of packets like this would have 16 or fewer sprouts.",
  "About 13% of shifts like this would have more than 6 sales."
)
$compares = array(
  "The mean is np = 12 x 0.65 = 7.8 shots, so 10 or more is above what she usually makes and is not common.",
  "The mean is np = 20 x 0.90 = 18 seeds, so 16 or fewer is below the usual 18 and is not common.",
  "The mean is np = 15 x 0.30 = 4.5 sales, so more than 6 is above the usual 4.5 and is not common."
)

$setup = $setups[$i]
$ask = $asks[$i]
$n = $nVals[$i]
$p = $pVals[$i]
$q = $qVals[$i]
$event = $events[$i]

$r_cond = "A fixed number of trials (" . $n . "). Each trial has two outcomes: " . $successes[$i] . " or it does not. The chance of " . $successes[$i] . " is the same each time, and the trials are independent."
$r_params = "One trial is " . $trials[$i] . ", and a success is that " . $successes[$i] . ". So n = " . $n . ", p = " . $p . " and q = " . $q . ", and X ~ B(" . $n . ", " . $p . ")."
$r_question = "The question asks for " . $event . ": " . $eventWords[$i] . ". " . $event . " = " . $answers[$i] . "."
$r_interp = $meanings[$i] . " " . $compares[$i]

$model = $r_cond . " " . $r_params . " " . $r_question . " " . $r_interp

$css = '
<style>
  .frq8 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .frq8 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .frq8 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .frq8 .rubric-container summary::-webkit-details-marker { display:none; }
  .frq8 .rubric-container .arrow-open { display:none; }
  .frq8 .rubric-container details[open] .arrow-closed { display:none; }
  .frq8 .rubric-container details[open] .arrow-open { display:inline; }
  .frq8 .rubric-table ul { margin:0; padding-left:1.1em; list-style:none; }
  .frq8 .ideal-ans { display:block; background:#e8f5e9; font-style:italic; font-weight:bold; font-size:0.95em; margin:5px 0 10px 0; border-left:3px solid #4CAF50; padding-left:8px; }
  .frq8 .full-response-box { margin-top:15px; border:2px solid #4CAF50; background:#e8f5e9; padding:15px; border-radius:5px; }
  .frq8 .rubric-content { padding:0.75em; background:#fafafa; }
  .frq8 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .frq8 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .frq8 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .frq8 .row-colored { background:#fff9ea; }
</style>'

$rubric = $css . '
<div class="frq8">
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
            <td><b>Check the Conditions<br>(4 pts)</b></td>
            <td>
              <ul>
                <li><label><input type="checkbox"> Say there is a fixed number of trials, and how many.</label></li>
                <li><label><input type="checkbox"> Say each trial has two outcomes, success or failure.</label></li>
                <li><label><input type="checkbox"> Say the chance of success is the same every trial and the trials are independent.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td><b>Name the Parameters<br>(2 pts)</b></td>
            <td>
              <ul>
                <li><label><input type="checkbox"> Say what one trial is and what counts as a success.</label></li>
                <li><label><input type="checkbox"> Give the values of n and p, and write X ~ B(n, p).</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td><b>State and Compute<br>(4 pts)</b></td>
            <td>
              <ul>
                <li><label><input type="checkbox"> Write the probability asked for with the correct inequality, for example P(X &gt;= k).</label></li>
                <li><label><input type="checkbox"> Give the value, using a calculator or the complement.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td><b>Interpret<br>(4 pts)</b></td>
            <td>
              <ul>
                <li><label><input type="checkbox"> Say what the probability means for this situation, in a sentence.</label></li>
                <li><label><input type="checkbox"> Find the mean np and say whether the outcome asked about is above or below it.</label></li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$rubricanswerbutton = $css . '
<div class="frq8">
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
            <td><b>Check the Conditions<br>(4 pts)</b></td>
            <td>
              <ul>
                <li>Fixed number of trials, stated with its value; two outcomes; same chance each trial; independent trials.
                  <span class="ideal-ans">Target: "' . $r_cond . '"</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td><b>Name the Parameters<br>(2 pts)</b></td>
            <td>
              <ul>
                <li>One trial and one success described; n and p given; model written.
                  <span class="ideal-ans">Target: "' . $r_params . '"</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td><b>State and Compute<br>(4 pts)</b></td>
            <td>
              <ul>
                <li>Correct inequality (2 pts) and value (2 pts).
                  <span class="ideal-ans">Target: "' . $r_question . '"</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td><b>Interpret<br>(4 pts)</b></td>
            <td>
              <ul>
                <li>Meaning in context (2 pts); mean np and the comparison (2 pts).
                  <span class="ideal-ans">Target: "' . $r_interp . '"</span></li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="full-response-box">
        <span>Model Narrative Response:</span><br><br>
        ' . $model . '
      </div>
      <p><b>Common wrong answer.</b> Computing P(X = k) for "at least", "at most" or "more than". It earns the setup points and loses the State and Compute points. For "more than 6", the sum starts at 7.</p>
      <p><b>Marking note.</b> Accept the value within 0.001 of the target, and accept the complement route (for example 1 - P(X &lt;= 9)) as full credit. Accept the mean as np with units or without.</p>
    </div>
  </details>
</div>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 8px 0;">$setup</p>
    <p style="margin:0 0 8px 0;">$ask</p>
    <p style="margin:0;">Let `X` be the number of successes. Show that a binomial model fits, name `n` and `p`, write the probability you need, find its value, and say what it means. Then find the mean `np` and compare it with the outcome in the question.</p>
  </div>
  $rubric
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    $answerbox[0]
  </div>
</div>

// === ANSWER ===

$rubricanswerbutton
