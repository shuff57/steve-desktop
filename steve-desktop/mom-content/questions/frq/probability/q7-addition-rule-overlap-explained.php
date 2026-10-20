// === NAME - DESCRIPTION: Addition Rule and the Overlap - Find P(A or B) from counts, name the overlap, and explain why it is subtracted ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The FRQ for 3.3 addition rule, written 2026-10-19 for the Chapter 3 individual test. There is no
// pre-FRQ for this skill, so the three categories are new, in the same 3 / 4 / 3 shape as q3 to q6.
//
// The prompt gives three counts: those in A, those in B, and those in both. A student who adds the
// first two gets a union larger than the group total on most draws, which is the trap: the Explain
// the Subtraction category is where that slip is named.
$anstypes = array("essay")
$displayformat[0] = 'editornopaste'

$i = rand(0, 2)
$settings = array("members of a gym, by the classes they attend", "people who visited a library, by what they borrowed", "customers at a cafe, by what they ordered")
$setting = $settings[$i]
$whos = array("members", "visitors", "customers")
$who = $whos[$i]
$whoSing = array("member", "visitor", "customer")
$one = $whoSing[$i]
$actA = array("attend yoga", "borrow a book", "order coffee")
$actB = array("attend spin", "borrow a DVD", "order a pastry")
$nameA = array("yoga", "a book", "coffee")
$nameB = array("spin", "a DVD", "a pastry")
$phr = array("attended yoga or spin", "borrowed a book or a DVD", "ordered coffee or a pastry")
$phrase = $phr[$i]
$doA = $actA[$i]
$doB = $actB[$i]
$nA = $nameA[$i]
$nB = $nameB[$i]

$N = randfrom(array(40, 50, 60, 80))
$both = rand(5, 10)
$cA = $both + rand(8, 14)
$cB = $both + rand(8, 14)
$union = $cA + $cB - $both
$twice = $cA + $cB
$pU = $union / $N
$pPct = 100 * $pU

$t_overlap = "The " . $both . " " . $who . " who " . $doA . " and " . $doB . " are in both counts."
$t_double = "Adding " . $cA . " and " . $cB . " gives " . $twice . ", which counts those " . $both . " twice."
$t_rule = "P(" . $nA . " or " . $nB . ") = " . $cA . "/" . $N . " + " . $cB . "/" . $N . " - " . $both . "/" . $N . "."
$t_prob = "That is " . $union . "/" . $N . " = " . $pU . ", or " . $pPct . "%."
$t_term = "The term being subtracted is P(" . $nA . " and " . $nB . "), the overlap."
$t_skip = "Leaving it out gives " . $twice . "/" . $N . ", which is too large because the overlap is counted twice."
$model = $t_overlap . " " . $t_double . " " . $t_rule . " " . $t_prob . " " . $t_term . " " . $t_skip

$css = '
<style>
  .frq7 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .frq7 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .frq7 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .frq7 .rubric-container summary::-webkit-details-marker { display:none; }
  .frq7 .rubric-container .arrow-open { display:none; }
  .frq7 .rubric-container details[open] .arrow-closed { display:none; }
  .frq7 .rubric-container details[open] .arrow-open { display:inline; }
  .frq7 .rubric-table ul { margin:0; padding-left:1.1em; list-style:none; }
  .frq7 .ideal-ans { display:block; background:#e8f5e9; font-style:italic; font-weight:bold; font-size:0.95em; margin:5px 0 10px 0; border-left:3px solid #4CAF50; padding-left:8px; }
  .frq7 .full-response-box { margin-top:15px; border:2px solid #4CAF50; background:#e8f5e9; padding:15px; border-radius:5px; }
  .frq7 .rubric-content { padding:0.75em; background:#fafafa; }
  .frq7 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .frq7 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .frq7 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .frq7 .row-colored { background:#fff9ea; }
</style>'

$rubric = $css . '
<div class="frq7">
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
            <td><b>Name the Overlap<br>(3 pts)</b></td>
            <td>
              <ul>
                <li><label><input type="checkbox"> Say how many are in both groups.</label></li>
                <li><label><input type="checkbox"> Say what happens when the two counts are added.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td><b>Apply the Addition Rule<br>(4 pts)</b></td>
            <td>
              <ul>
                <li><label><input type="checkbox"> Write the rule with the numbers from the problem.</label></li>
                <li><label><input type="checkbox"> Give the probability.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td><b>Explain the Subtraction<br>(3 pts)</b></td>
            <td>
              <ul>
                <li><label><input type="checkbox"> Say which probability is being subtracted.</label></li>
                <li><label><input type="checkbox"> Say what goes wrong if it is left out.</label></li>
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
<div class="frq7">
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
            <td><b>Name the Overlap<br>(3 pts)</b></td>
            <td>
              <ul>
                <li>Say how many are in both groups.
                    <span class="ideal-ans">Target: "' . $t_overlap . '"</span></li>
                <li>Say what happens when the two counts are added.
                    <span class="ideal-ans">Target: "' . $t_double . '"</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td><b>Apply the Addition Rule<br>(4 pts)</b></td>
            <td>
              <ul>
                <li>Write the rule with the numbers from the problem.
                    <span class="ideal-ans">Target: "' . $t_rule . '"</span></li>
                <li>Give the probability.
                    <span class="ideal-ans">Target: "' . $t_prob . '"</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td><b>Explain the Subtraction<br>(3 pts)</b></td>
            <td>
              <ul>
                <li>Say which probability is being subtracted.
                    <span class="ideal-ans">Target: "' . $t_term . '"</span></li>
                <li>Say what goes wrong if it is left out.
                    <span class="ideal-ans">Target: "' . $t_skip . '"</span></li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="full-response-box">
        <span>Model Narrative Response:</span><br><br>
        ' . $model . '
      </div>
      <p><b>The trap.</b> Adding ' . $cA . ' and ' . $cB . ' and dividing by ' . $N . ' gives ' . ($twice / $N) . '. A student who does this has skipped the subtraction and earns 1 of the 4 points for Apply the Addition Rule.</p>
      <p><b>Marking note.</b> A correct answer of &quot;' . $pU . '&quot; with no rule and no overlap named earns 2 of the 4 points for Apply the Addition Rule and nothing for the other two categories.</p>
    </div>
  </details>
</div>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 8px 0;"><b>The count.</b> A survey sorted $setting. There are $N $who in all. $cA $doA, $cB $doB, and $both do both.</p>
    <p style="margin:8px 0 0 0;">One $one is chosen at random. Find the probability that the $one $phrase. Say how many are in both groups, write the addition rule with the numbers, give the probability, and explain why the overlap is subtracted.</p>
  </div>
  $rubric
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    $answerbox[0]
  </div>
</div>

// === ANSWER ===

$rubricanswerbutton
