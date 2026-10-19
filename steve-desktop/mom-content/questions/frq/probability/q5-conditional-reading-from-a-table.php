// === NAME - DESCRIPTION: Conditional Reading from a Table - Read two counts off a two-way table, compute a conditional probability, and say which direction it is ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The FRQ for 3.4, written 2026-10-19 to match pre-frq-grade-a-conditional-reading exactly: same three
// categories, same point split, same target sentences.
//
// The table has different row totals and a different column total, so the right denominator is a
// choice and not the only number on offer. The direction category is where the common slip shows:
// a correct division attached to the wrong sentence.
$anstypes = array("essay")
$displayformat[0] = 'editornopaste'

$i = rand(0, 2)
$settings = array("residents of a town, by whether they own a car and whether they use the bus", "players in a league, by division and by whether they made the playoffs", "diners at a cafe, by meal and by whether they left a tip")
$setting = $settings[$i]
$whos = array("residents", "players", "diners")
$who = $whos[$i]
$row1s = array("Owns a car", "Junior division", "Lunch")
$row2s = array("No car", "Senior division", "Dinner")
$col1s = array("Uses the bus", "Made the playoffs", "Left a tip")
$col2s = array("Does not use the bus", "Missed the playoffs", "Left no tip")
$row1 = $row1s[$i]
$row2 = $row2s[$i]
$col1 = $col1s[$i]
$col2 = $col2s[$i]
$rowPhrases = array("who own a car", "in the junior division", "who ate lunch")
$colPhrases = array("uses the bus", "made the playoffs", "left a tip")
$rowPhrase = $rowPhrases[$i]
$colPhrase = $colPhrases[$i]
$aShorts = array("Car", "Junior", "Lunch")
$bShorts = array("Bus", "Playoffs", "Tip")
$aS = $aShorts[$i]
$bS = $bShorts[$i]

$t1 = rand(4, 7)
$t2 = rand(4, 7)
$d = rand(3, 7)
$d2 = rand(2, 4)
$tot1 = 20 * $t1
$tot2 = 20 * $t2
$r1c1 = 2 * $t1 * $d
$r1c2 = $tot1 - $r1c1
$r2c1 = 2 * $t2 * $d2
if ($r2c1 == $r1c2) {
  $r2c1 = $r2c1 + 10
}
$r2c2 = $tot2 - $r2c1
$c1 = $r1c1 + $r2c1
$c2 = $r1c2 + $r2c2
$grand = $tot1 + $tot2
$pDec = $r1c1 / $tot1
$pPct = 100 * $pDec

$t_counts = "The two numbers needed are " . $r1c1 . " and " . $tot1 . "."
$t_denom = "The denominator is " . $tot1 . ", the number of " . $who . " " . $rowPhrase . ", because we are told to look only at them."
$t_div = $r1c1 . " / " . $tot1 . " = " . $pDec . "."
$t_prob = "So the probability is " . $pDec . ", or " . $pPct . "% of those " . $who . "."
$t_dir = "This is P(" . $bS . " given " . $aS . ")."
$t_swap = "Swapping the two gives P(" . $aS . " given " . $bS . "), which divides by the column total of " . $c1 . " instead, so it is a different number."
$model = $t_counts . " " . $t_denom . " " . $t_div . " " . $t_prob . " " . $t_dir . " " . $t_swap

$css = '
<style>
  .frq5 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .frq5 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .frq5 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .frq5 .rubric-container summary::-webkit-details-marker { display:none; }
  .frq5 .rubric-container .arrow-open { display:none; }
  .frq5 .rubric-container details[open] .arrow-closed { display:none; }
  .frq5 .rubric-container details[open] .arrow-open { display:inline; }
  .frq5 .rubric-table ul { margin:0; padding-left:1.1em; list-style:none; }
  .frq5 .ideal-ans { display:block; background:#e8f5e9; font-style:italic; font-weight:bold; font-size:0.95em; margin:5px 0 10px 0; border-left:3px solid #4CAF50; padding-left:8px; }
  .frq5 .full-response-box { margin-top:15px; border:2px solid #4CAF50; background:#e8f5e9; padding:15px; border-radius:5px; }
  .frq5 .rubric-content { padding:0.75em; background:#fafafa; }
  .frq5 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .frq5 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .frq5 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .frq5 .row-colored { background:#fff9ea; }
  .frq5 .facts { border-collapse:collapse; margin:10px 0; font-size:15px; }
  .frq5 .facts td, .frq5 .facts th { border:1px solid #d1d5db; padding:6px 14px; text-align:left; }
  .frq5 .facts th { background:#f0f4ff; }
  .frq5 .facts td.num { text-align:center; }
</style>'

$rubric = $css . '
<div class="frq5">
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
            <td><b>Read the Right Counts<br>(3 pts)</b></td>
            <td>
              <ul>
                <li><label><input type="checkbox"> Take the right two numbers off the table.</label></li>
                <li><label><input type="checkbox"> Say which group the denominator is.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td><b>Compute the Conditional<br>(4 pts)</b></td>
            <td>
              <ul>
                <li><label><input type="checkbox"> Divide the count by the group total.</label></li>
                <li><label><input type="checkbox"> Give the probability.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td><b>State the Direction<br>(3 pts)</b></td>
            <td>
              <ul>
                <li><label><input type="checkbox"> Say which conditional this is.</label></li>
                <li><label><input type="checkbox"> Say why swapping the two events gives a different number.</label></li>
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
<div class="frq5">
  <table class="facts">
    <tr><th></th><th>' . $col1 . '</th><th>' . $col2 . '</th><th>Total</th></tr>
    <tr><th>' . $row1 . '</th><td class="num">' . $r1c1 . '</td><td class="num">' . $r1c2 . '</td><td class="num">' . $tot1 . '</td></tr>
    <tr><th>' . $row2 . '</th><td class="num">' . $r2c1 . '</td><td class="num">' . $r2c2 . '</td><td class="num">' . $tot2 . '</td></tr>
    <tr><th>Total</th><td class="num">' . $c1 . '</td><td class="num">' . $c2 . '</td><td class="num">' . $grand . '</td></tr>
  </table>
</div>'

$rubricanswerbutton = $css . '
<div class="frq5">
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
            <td><b>Read the Right Counts<br>(3 pts)</b></td>
            <td>
              <ul>
                <li>Take the right two numbers off the table.
                    <span class="ideal-ans">Target: "' . $t_counts . '"</span></li>
                <li>Say which group the denominator is.
                    <span class="ideal-ans">Target: "' . $t_denom . '"</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td><b>Compute the Conditional<br>(4 pts)</b></td>
            <td>
              <ul>
                <li>Divide the count by the group total.
                    <span class="ideal-ans">Target: "' . $t_div . '"</span></li>
                <li>Give the probability.
                    <span class="ideal-ans">Target: "' . $t_prob . '"</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td><b>State the Direction<br>(3 pts)</b></td>
            <td>
              <ul>
                <li>Say which conditional this is.
                    <span class="ideal-ans">Target: "' . $t_dir . '"</span></li>
                <li>Say why swapping the two events gives a different number.
                    <span class="ideal-ans">Target: "' . $t_swap . '"</span></li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="full-response-box">
        <span>Model Narrative Response:</span><br><br>
        ' . $model . '
      </div>
      <p><b>The trap.</b> The column total of ' . $c1 . ' is on the table too. Dividing by it answers the other question, P(' . $aS . ' given ' . $bS . ') = ' . $r1c1 . ' / ' . $c1 . ', and earns 1 for method, not full marks for Compute the Conditional.</p>
      <p><b>Marking note.</b> Read the denominator first. A bare &quot;' . $pDec . '&quot; with no group named earns 1 of the 3 points for Read the Right Counts.</p>
    </div>
  </details>
</div>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 8px 0;"><b>The survey.</b> A survey sorted $setting.</p>
    $factBlock
    <p style="margin:8px 0 0 0;">Among the $who $rowPhrase, find the probability that one chosen at random $colPhrase. Show which two numbers you used, do the division, and say which conditional probability you found.</p>
  </div>
  $rubric
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    $answerbox[0]
  </div>
</div>

// === ANSWER ===

$rubricanswerbutton
