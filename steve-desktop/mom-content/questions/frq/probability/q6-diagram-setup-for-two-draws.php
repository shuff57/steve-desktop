// === NAME - DESCRIPTION: Diagram Setup for Two Draws - Draw a tree for two draws without replacement, fill in its probabilities, and answer an at-least-one question from it ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The FRQ for 3.5, written 2026-10-19 to match pre-frq-grade-a-diagram-setup exactly: same three
// categories, same point split, same target sentences.
//
// The question asks for P(at least one of the first kind) with the first item NOT put back. The
// student has to commit to a tree (or Venn) before any number appears, which is the category students
// habitually skip. The answer is found by the complement: 1 minus P(both of the other kind).
$anstypes = array("essay")
$displayformat[0] = 'editornopaste'

$i = rand(0, 2)
$containers = array("tray", "hat", "toolbox")
$items = array("cupcakes", "raffle tickets", "screws")
$c1s = array("chocolate", "yellow", "long")
$c2s = array("vanilla", "green", "short")
$container = $containers[$i]
$item = $items[$i]
$c1 = $c1s[$i]
$c2 = $c2s[$i]

$r = rand(3, 5)
$total = 10
$b = $total - $r
$n1 = $total - 1
$b1 = $b - 1
$bb = $b * $b1
$dd = $total * $n1
$ansTop = $dd - $bb
$pBoth = round($bb / $dd, 4)
$ans = round($ansTop / $dd, 4)

$t_struct = "A tree: the first draw branches into " . $c1 . " and " . $c2 . ", and each of those branches again for the second draw."
$t_change = "The first one is not put back, so only " . $n1 . " are left for the second draw, and the second-draw branches have " . $n1 . " underneath."
$t_first = "The first draw is " . $c1 . " with probability " . $r . "/" . $total . " and " . $c2 . " with probability " . $b . "/" . $total . "."
$t_second = "After a " . $c2 . " first, the second draw is " . $c2 . " with probability " . $b1 . "/" . $n1 . "."
$t_event = "The opposite of at least one " . $c1 . " is both " . $c2 . ", which is " . $b . "/" . $total . " x " . $b1 . "/" . $n1 . " = " . $bb . "/" . $dd . ", about " . $pBoth . "."
$t_ans = "So P(at least one " . $c1 . ") = 1 - " . $bb . "/" . $dd . " = " . $ansTop . "/" . $dd . ", about " . $ans . "."
$model = $t_struct . " " . $t_change . " " . $t_first . " " . $t_second . " " . $t_event . " " . $t_ans

$css = '
<style>
  .frq6 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .frq6 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .frq6 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .frq6 .rubric-container summary::-webkit-details-marker { display:none; }
  .frq6 .rubric-container .arrow-open { display:none; }
  .frq6 .rubric-container details[open] .arrow-closed { display:none; }
  .frq6 .rubric-container details[open] .arrow-open { display:inline; }
  .frq6 .rubric-table ul { margin:0; padding-left:1.1em; list-style:none; }
  .frq6 .ideal-ans { display:block; background:#e8f5e9; font-style:italic; font-weight:bold; font-size:0.95em; margin:5px 0 10px 0; border-left:3px solid #4CAF50; padding-left:8px; }
  .frq6 .full-response-box { margin-top:15px; border:2px solid #4CAF50; background:#e8f5e9; padding:15px; border-radius:5px; }
  .frq6 .rubric-content { padding:0.75em; background:#fafafa; }
  .frq6 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .frq6 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .frq6 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .frq6 .row-colored { background:#fff9ea; }
  .frq6 .facts { border-collapse:collapse; margin:10px 0; font-size:15px; }
  .frq6 .facts td, .frq6 .facts th { border:1px solid #d1d5db; padding:6px 14px; text-align:left; }
  .frq6 .facts th { background:#f0f4ff; }
  .frq6 .facts td.num { text-align:center; }
</style>'

$rubric = $css . '
<div class="frq6">
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
            <td><b>Draw the Structure<br>(3 pts)</b></td>
            <td>
              <ul>
                <li><label><input type="checkbox"> Say what diagram you would draw.</label></li>
                <li><label><input type="checkbox"> Say what changes on the second draw.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td><b>Fill the Branches or Regions<br>(4 pts)</b></td>
            <td>
              <ul>
                <li><label><input type="checkbox"> Give the first-draw probabilities.</label></li>
                <li><label><input type="checkbox"> Give the second-draw probabilities you need.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td><b>Answer the Question Asked<br>(3 pts)</b></td>
            <td>
              <ul>
                <li><label><input type="checkbox"> Say which event you worked out and how.</label></li>
                <li><label><input type="checkbox"> Give the final probability.</label></li>
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
<div class="frq6">
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
            <td><b>Draw the Structure<br>(3 pts)</b></td>
            <td>
              <ul>
                <li>Say what diagram you would draw.
                    <span class="ideal-ans">Target: "' . $t_struct . '"</span></li>
                <li>Say what changes on the second draw.
                    <span class="ideal-ans">Target: "' . $t_change . '"</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td><b>Fill the Branches or Regions<br>(4 pts)</b></td>
            <td>
              <ul>
                <li>Give the first-draw probabilities.
                    <span class="ideal-ans">Target: "' . $t_first . '"</span></li>
                <li>Give the second-draw probabilities you need.
                    <span class="ideal-ans">Target: "' . $t_second . '"</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td><b>Answer the Question Asked<br>(3 pts)</b></td>
            <td>
              <ul>
                <li>Say which event you worked out and how.
                    <span class="ideal-ans">Target: "' . $t_event . '"</span></li>
                <li>Give the final probability.
                    <span class="ideal-ans">Target: "' . $t_ans . '"</span></li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="full-response-box">
        <span>Model Narrative Response:</span><br><br>
        ' . $model . '
      </div>
      <p><b>The trap.</b> Using ' . $r . '/' . $total . ' on the second draw as well, as if the first one went back, gives a different number. It earns the structure and 1 for the branches, not full marks for Fill the Branches or Regions.</p>
      <p><b>Marking note.</b> Accept a Venn diagram or a list of the outcomes if the first-draw and second-draw probabilities are clear. A bare &quot;' . $ans . '&quot; with no diagram earns at most 3 of the 10 points.</p>
    </div>
  </details>
</div>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 8px 0;"><b>The draw.</b> A $container holds <b>$r $c1</b> and <b>$b $c2</b> $item. Two are drawn one after the other, and the first is not put back.</p>
    <p style="margin:8px 0 0 0;">Find the probability that at least one is $c1. Describe the tree or Venn diagram you would draw, give the probabilities you put on it, and answer from it.</p>
  </div>
  $rubric
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    $answerbox[0]
  </div>
</div>

// === ANSWER ===

$rubricanswerbutton
