// === NAME - DESCRIPTION: Set Up a Probability from Scratch - Given a compound experiment, define the sample space, identify the event, and find its probability, showing why the denominator is what it is ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The FRQ for 3.1, written 2026-08-10 AFTER its pre-FRQ rather than before. Chapter 3 had no
// intro-stats FRQ, so pre-frq-grade-a-probability-setup defined the scenario and the checklist and
// this matches them exactly: same three categories, same point split, same target sentences. A
// student who worked the pre-FRQ has already read the prose this rewards.
//
// Hand-graded, so there is no answer key. The rubric is the deliverable.

$anstypes = array("essay")
$displayformat[0] = 'editornopaste'

$i = rand(0, 2)

$experiments = array(
  "two fair six-sided dice are rolled and the result recorded as an ordered pair (first die, second die)",
  "a fair coin is flipped three times and the result recorded as an ordered sequence of heads and tails",
  "a card is drawn from a standard 52-card deck and a fair six-sided die is rolled"
)
$experiment = $experiments[$i]

$spaceSizes = array(36, 8, 312)
$spaceSize = $spaceSizes[$i]

$spaceWhy = array(
  "each of the 6 faces on the first die pairs with each of the 6 on the second, and (2,3) is a different outcome from (3,2)",
  "each of the 3 flips has 2 possibilities, so there are 2 x 2 x 2 arrangements and HTT is different from THT",
  "each of the 52 cards can occur with each of the 6 die faces"
)
$why = $spaceWhy[$i]

$events = array(
  "the two faces sum to 9",
  "exactly two of the three flips are heads",
  "the card is a heart and the die shows a number greater than 4"
)
$event = $events[$i]

$favCounts = array(4, 3, 26)
$fav = $favCounts[$i]

$favLists = array(
  "(3,6), (4,5), (5,4) and (6,3)",
  "HHT, HTH and THH",
  "any of the 13 hearts paired with either a 5 or a 6"
)
$favList = $favLists[$i]

$probDec = round($fav / $spaceSize, 4)

// Dice only: a 6 x 6 grid of sums, first die down the side and second die across the top. The student
// view is plain so they find the sum-9 cells themselves. The instructor copy shades them.
$sumGrid = ""
$sumGridKey = ""
if ($i == 0) {
  $head = '<svg width="300" height="272" viewBox="0 0 300 272" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Sums of two dice, first die down the side, second die across the top" style="display:block;margin:10px auto;background:#fff">'
  $sumGrid = $head . '<text x="165" y="14" font-size="12" font-weight="700" fill="#374151" text-anchor="middle">Second die</text>'
  $sumGridKey = $sumGrid
  for ($c = 1..6) {
    $hx = 30 + $c * 40 - 20
    $hdr = '<text x="' . ($hx + 25) . '" y="36" font-size="13" font-weight="700" fill="#1865f2" text-anchor="middle">' . $c . '</text>'
    $sumGrid = $sumGrid . $hdr
    $sumGridKey = $sumGridKey . $hdr
  }
  for ($rw = 1..6) {
    $ry = 44 + ($rw - 1) * 36
    $lab = '<text x="20" y="' . ($ry + 23) . '" font-size="13" font-weight="700" fill="#1865f2" text-anchor="middle">' . $rw . '</text>'
    $sumGrid = $sumGrid . $lab
    $sumGridKey = $sumGridKey . $lab
    for ($c = 1..6) {
      $cx = 30 + ($c - 1) * 40 + 15
      $tot = $rw + $c
      $cellQ = '<rect x="' . $cx . '" y="' . $ry . '" width="38" height="34" fill="#ffffff" stroke="#d1d5db"/><text x="' . ($cx + 19) . '" y="' . ($ry + 22) . '" font-size="14" fill="#1f2937" text-anchor="middle">' . $tot . '</text>'
      $fillK = "#ffffff"
      if ($tot == 9) {
        $fillK = "#c8e6c9"
      }
      $cellK = '<rect x="' . $cx . '" y="' . $ry . '" width="38" height="34" fill="' . $fillK . '" stroke="#d1d5db"/><text x="' . ($cx + 19) . '" y="' . ($ry + 22) . '" font-size="14" fill="#1f2937" text-anchor="middle">' . $tot . '</text>'
      $sumGrid = $sumGrid . $cellQ
      $sumGridKey = $sumGridKey . $cellK
    }
  }
  $sumGrid = $sumGrid . '<text x="8" y="20" font-size="11" fill="#6b7280">First</text></svg>'
  $sumGridKey = $sumGridKey . '<text x="8" y="20" font-size="11" fill="#6b7280">First</text></svg>'
}

$r_space = "The sample space has " . $spaceSize . " equally likely outcomes, because " . $why . "."
$r_event = "The outcomes in the event are " . $favList . ", so the event contains " . $fav . " of them."
$r_prob = "The probability is therefore " . $fav . " out of " . $spaceSize . ", or about " . $probDec . "."

$t_n = "There are " . $spaceSize . " equally likely outcomes."
$t_why = "That is the count because " . $why . "."
$t_list = "The outcomes in the event are " . $favList . "."
$t_cnt = "There are " . $fav . " of them."
$t_prob = "The probability is " . $fav . " out of " . $spaceSize . ", or about " . $probDec . "."
$t_from = $fav . " favourable outcomes over " . $spaceSize . " outcomes in the sample space = " . $fav . "/" . $spaceSize . "."
$model = $r_space . " " . $r_event . " " . $r_prob

$css = '
<style>
  .frq2 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .frq2 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .frq2 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .frq2 .rubric-container summary::-webkit-details-marker { display:none; }
  .frq2 .rubric-container .arrow-open { display:none; }
  .frq2 .rubric-container details[open] .arrow-closed { display:none; }
  .frq2 .rubric-container details[open] .arrow-open { display:inline; }
  .frq2 .rubric-table ul { margin:0; padding-left:1.1em; list-style:none; }
  .frq2 .ideal-ans { display:block; background:#e8f5e9; font-style:italic; font-weight:bold; font-size:0.95em; margin:5px 0 10px 0; border-left:3px solid #4CAF50; padding-left:8px; }
  .frq2 .full-response-box { margin-top:15px; border:2px solid #4CAF50; background:#e8f5e9; padding:15px; border-radius:5px; }
  .frq2 .rubric-content { padding:0.75em; background:#fafafa; }
  .frq2 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .frq2 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .frq2 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .frq2 .row-colored { background:#fff9ea; }
  .frq2 .setup { border:1px solid #d1d5db; border-radius:8px; padding:10px 14px; margin:10px 0; background:#f8fafc; font-size:15px; }
</style>'

$rubric = $css . '
<div class="frq2">
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
            <td><b>Define the Sample Space<br>(3 pts)</b></td>
            <td>
              <ul>
                <li><label><input type="checkbox"> Say how many equally likely outcomes there are.</label></li>
                <li><label><input type="checkbox"> Say why that is the count.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td><b>Identify the Event<br>(4 pts)</b></td>
            <td>
              <ul>
                <li><label><input type="checkbox"> Say which outcomes make up the event.</label></li>
                <li><label><input type="checkbox"> Say how many there are.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td><b>State the Probability<br>(3 pts)</b></td>
            <td>
              <ul>
                <li><label><input type="checkbox"> Give the probability as a fraction, decimal or percentage.</label></li>
                <li><label><input type="checkbox"> Show the numbers it comes from.</label></li>
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
<div class="frq2">
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
            <td><b>Define the Sample Space<br>(3 pts)</b></td>
            <td>
              <ul>
                <li>Say how many equally likely outcomes there are.
                    <span class="ideal-ans">Target: "' . $t_n . '"</span></li>
                <li>Say why that is the count.
                    <span class="ideal-ans">Target: "' . $t_why . '"</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td><b>Identify the Event<br>(4 pts)</b></td>
            <td>
              <ul>
                <li>Say which outcomes make up the event.
                    <span class="ideal-ans">Target: "' . $t_list . '"</span></li>
                <li>Say how many there are.
                    <span class="ideal-ans">Target: "' . $t_cnt . '"</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td><b>State the Probability<br>(3 pts)</b></td>
            <td>
              <ul>
                <li>Give the probability as a fraction, decimal or percentage.
                    <span class="ideal-ans">Target: "' . $t_prob . '"</span></li>
                <li>Show the numbers it comes from.
                    <span class="ideal-ans">Target: "' . $t_from . '"</span></li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>
      ' . $sumGridKey . '
      <div class="full-response-box">
        <span>Model Narrative Response:</span><br><br>
        ' . $model . '
      </div>
      <p><b>The trap.</b> A bare &quot;' . $fav . ' out of ' . $spaceSize . '&quot; with no reason for the ' . $spaceSize . ' earns 2 of the 3 points for Define the Sample Space and nothing more there.</p>
      <p><b>Marking note.</b> Both the count and the reason must appear for the full 3. A fraction, a decimal or a percentage all earn the full 3 for State the Probability, and an unreduced fraction is not marked down.</p>
    </div>
  </details>
</div>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 8px 0;"><b>The experiment.</b> Suppose $experiment.</p>
    $sumGrid
    <div class="frq2"><div class="setup"><b>The event:</b> $event.</div></div>
    <p style="margin:8px 0 0 0;">Set up and find the probability of that event. Your answer must define the sample space, identify the event, and give the probability: in sentences, showing where each number comes from.</p>
  </div>
  $rubric
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    $answerbox[0]
  </div>
</div>

// === ANSWER ===

$rubricanswerbutton
