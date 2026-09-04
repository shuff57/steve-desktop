// === NAME - DESCRIPTION: Set Up a Probability from Scratch - Given a compound experiment, define the sample space, identify the event, and find its probability, showing why the denominator is what it is ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The FRQ for 3.1, written 2026-08-10 AFTER its pre-FRQ rather than before. Chapter 3 had no
// intro-stats FRQ, so pre-frq-grade-a-probability-setup defined the scenario and the checklist and
// this matches them exactly: same three categories, same point split, same target sentences. A
// student who worked the pre-FRQ has already read the prose this rewards.
//
// Hand-graded, so there is no answer key. The rubric is the deliverable.

loadlibrary("stats")

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

$r_space = "The sample space has " . $spaceSize . " equally likely outcomes, because " . $why . "."
$r_event = "The outcomes in the event are " . $favList . ", so the event contains " . $fav . " of them."
$r_prob = "The probability is therefore " . $fav . " out of " . $spaceSize . ", or about " . $probDec . "."

$model = $r_space . " " . $r_event . " " . $r_prob

$css = '
<style>
  .frq2 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .frq2 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .frq2 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .frq2 .rubric-container summary::-webkit-details-marker { display:none; }
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
  <details open>
    <summary>Grading Checklist: 10 points</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Define the Sample Space<br>(3 pts)</b></td>
            <td>State how many equally likely outcomes the experiment has, and why that is the count.</td></tr>
          <tr><td style="text-align:center;"><b>Identify the Event<br>(4 pts)</b></td>
            <td>Say which outcomes make up the event, and how many there are.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>State the Probability<br>(3 pts)</b></td>
            <td>Give the probability of the event as a number.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
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
      <p><span class="term-label">Define the Sample Space (3).</span> Award only if BOTH the count and the reason appear. "There are ' . $spaceSize . ' outcomes" alone is 1 of 3: the reason is what shows the student did not memorise a number.</p>
      <p><span class="term-label">Identify the Event (4).</span> The outcomes must be named or characterised, not just counted. A bare "' . $fav . ' outcomes" earns 2; listing them earns the rest. Award full credit for a correct characterisation in words if listing would be long.</p>
      <p><span class="term-label">State the Probability (3).</span> A fraction, a decimal or a percentage all earn full marks. Do not deduct for an unreduced fraction.</p>
      <p><span class="term-label">The category most often missing.</span> The sample space. Students count the favourable outcomes and divide by a denominator they never justify, which is exactly the habit the pre-FRQ for this section targets.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 8px 0;"><b>The experiment.</b> Suppose $experiment.</p>
    <div class="frq2"><div class="setup"><b>The event:</b> $event.</div></div>
    <p style="margin:8px 0 0 0;">Set up and find the probability of that event. Your answer must define the sample space, identify the event, and give the probability: in sentences, showing where each number comes from.</p>
  </div>
  $rubric
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    $answerbox[0]
  </div>
</div>

// === ANSWER ===

$solutionguide
