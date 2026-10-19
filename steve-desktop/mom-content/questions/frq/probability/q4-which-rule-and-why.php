// === NAME - DESCRIPTION: Which Rule, and Why That Factor - Choose between the addition and multiplication rules, carry it out, and justify which of two competing rates belongs in the product ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The FRQ for 3.3, written 2026-08-10 to match pre-frq-grade-a-joint-probability exactly: same
// three categories, same point split, same target sentences.
//
// The survey deliberately supplies THREE rates: P(A), P(B given A), and P(B) overall. Only one of
// the last two belongs in the product, and a student who cannot say which has a 50% chance of
// looking correct. The justification category is where that shows.

loadlibrary("stats")

$anstypes = array("essay")
$displayformat[0] = 'editornopaste'

$i = rand(0, 2)

$settings = array(
  "customers at a restaurant, ordering dessert and ordering coffee",
  "riders on a bus route, buying a day pass and transferring to a second line",
  "visitors to a museum, joining a guided tour and visiting the gift shop"
)
$setting = $settings[$i]

$eventA = array("ordered dessert", "bought a day pass", "joined a guided tour")
$eA = $eventA[$i]

$eventB = array("ordered coffee", "transferred to a second line", "visited the gift shop")
$eB = $eventB[$i]

$whoPlural = array("customers", "riders", "visitors")
$who = $whoPlural[$i]

$whoOneLabels = array("customer", "rider", "visitor")
$whoOne = $whoOneLabels[$i]

$a = 10 * rand(3, 6)
$b = 10 * rand(2, 5)
$bOverall = $b + 15
$jointPct = $a * $b / 100

$aDec = $a / 100
$bDec = $b / 100
$jointDec = $jointPct / 100

$r_rule = "This asks for the chance that both happened, an AND rather than an OR, so it calls for the multiplication rule."
$r_apply = "That works out as " . $aDec . " x " . $bDec . " = " . $jointDec . ", or " . $jointPct . "% of all " . $who . "."
$r_justify = "The second factor has to be the " . $b . "% measured among those who " . $eA . ", not the " . $bOverall . "% measured across everyone, because the two events are not independent: knowing that a " . $whoOne . " " . $eA . " changes the chance they also " . $eB . "."

$model = $r_rule . " " . $r_apply . " " . $r_justify
$t_rulename = "The multiplication rule."
$t_calc = $aDec . " x " . $bDec . " = " . $jointDec . "."
$t_which = "The " . $b . "% measured among those who " . $eA . "."

$css = '
<style>
  .frq4 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .frq4 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .frq4 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .frq4 .rubric-container summary::-webkit-details-marker { display:none; }
  .frq4 .rubric-container .arrow-open { display:none; }
  .frq4 .rubric-container details[open] .arrow-closed { display:none; }
  .frq4 .rubric-container details[open] .arrow-open { display:inline; }
  .frq4 .rubric-table ul { margin:0; padding-left:1.1em; list-style:none; }
  .frq4 .ideal-ans { display:block; background:#e8f5e9; font-style:italic; font-weight:bold; font-size:0.95em; margin:5px 0 10px 0; border-left:3px solid #4CAF50; padding-left:8px; }
  .frq4 .full-response-box { margin-top:15px; border:2px solid #4CAF50; background:#e8f5e9; padding:15px; border-radius:5px; }
  .frq4 .rubric-content { padding:0.75em; background:#fafafa; }
  .frq4 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .frq4 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .frq4 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .frq4 .row-colored { background:#fff9ea; }
  .frq4 .facts { border-collapse:collapse; margin:10px 0; font-size:15px; }
  .frq4 .facts td, .frq4 .facts th { border:1px solid #d1d5db; padding:6px 14px; text-align:left; }
  .frq4 .facts th { background:#f0f4ff; }
</style>'

$rubric = $css . '
<div class="frq4">
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
            <td><b>Choose the Right Rule<br>(3 pts)</b></td>
            <td>
              <ul>
                <li><label><input type="checkbox"> Say which rule you used (addition or multiplication).</label></li>
                <li><label><input type="checkbox"> Say what in the question told you to use it.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td><b>Apply It Correctly<br>(4 pts)</b></td>
            <td>
              <ul>
                <li><label><input type="checkbox"> Carry out the calculation.</label></li>
                <li><label><input type="checkbox"> Give the final probability.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td><b>Justify the Second Factor<br>(3 pts)</b></td>
            <td>
              <ul>
                <li><label><input type="checkbox"> Say which of the two rates for the second event you used.</label></li>
                <li><label><input type="checkbox"> Say why the other rate does not belong in the product.</label></li>
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
<div class="frq4">
  <table class="facts">
    <tr><th>What was measured</th><th>Rate</th></tr>
    <tr><td>' . $who . ' who ' . $eA . '</td><td>' . $a . '%</td></tr>
    <tr><td>among those who ' . $eA . ', the share who also ' . $eB . '</td><td>' . $b . '%</td></tr>
    <tr><td>' . $who . ' who ' . $eB . ', across everyone</td><td>' . $bOverall . '%</td></tr>
  </table>
</div>'

$rubricanswerbutton = $css . '
<div class="frq4">
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
            <td><b>Choose the Right Rule<br>(3 pts)</b></td>
            <td>
              <ul>
                <li>Say which rule you used (addition or multiplication).
                    <span class="ideal-ans">Target: "' . $t_rulename . '"</span></li>
                <li>Say what in the question told you to use it.
                    <span class="ideal-ans">Target: "' . $r_rule . '"</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td><b>Apply It Correctly<br>(4 pts)</b></td>
            <td>
              <ul>
                <li>Carry out the calculation.
                    <span class="ideal-ans">Target: "' . $t_calc . '"</span></li>
                <li>Give the final probability.
                    <span class="ideal-ans">Target: "' . $r_apply . '"</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td><b>Justify the Second Factor<br>(3 pts)</b></td>
            <td>
              <ul>
                <li>Say which of the two rates for the second event you used.
                    <span class="ideal-ans">Target: "' . $t_which . '"</span></li>
                <li>Say why the other rate does not belong in the product.
                    <span class="ideal-ans">Target: "' . $r_justify . '"</span></li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="full-response-box">
        <span>Model Narrative Response:</span><br><br>
        ' . $model . '
      </div>
      <p><b>The trap.</b> The ' . $bOverall . '% is there to be rejected. A correct multiplication of the wrong two numbers earns 1 for method, not full marks for Apply It Correctly.</p>
      <p><b>Marking note.</b> Read the justification first. A bare &quot;I used ' . $b . '%&quot; with no reason earns 1 of the 3 points.</p>
    </div>
  </details>
</div>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 8px 0;"><b>The survey.</b> A study of $setting produced three figures.</p>
    $factBlock
    <p style="margin:8px 0 0 0;">Find the probability that one of the $who both $eA and $eB. Say which rule you used and why, carry it out, and explain which figures you used and which you did not.</p>
  </div>
  $rubric
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    $answerbox[0]
  </div>
</div>

// === ANSWER ===

$rubricanswerbutton
