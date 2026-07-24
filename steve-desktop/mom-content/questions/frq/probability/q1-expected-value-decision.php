// === NAME - DESCRIPTION: Expected Value Decision - Compute E(X) for a real-world game/policy and explain whether to play or buy ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$scenarios = array(
  "you are considering a roulette bet at a casino. You bet $5 on red. If the ball lands on red (which happens 18 out of 38 spins), you win $5 net. If the ball lands on black or green, you lose your $5.",
  "you are deciding whether to buy a state lottery ticket. The ticket costs $2 and has a 1 in 1,000,000 chance of winning the $500,000 jackpot. Otherwise you lose the $2 cost of the ticket.",
  "you are at a carnival ring-toss booth. It costs $1 to play. You win a $15 prize with probability 0.05 and win nothing otherwise."
);
$i = rand(0, count($scenarios) - 1);
$topic = $scenarios[$i];

$ev_strings   = array("approximately -$0.26", "-$1.50", "-$0.25");
$ev_computes  = array(
  "(+5)(18/38) + (-5)(20/38) = 90/38 - 100/38 = -10/38 &asymp; -$0.26",
  "(500000 - 2)(1/1000000) + (-2)(999999/1000000) = 0.5 - 1.999998 = -$1.50",
  "(15 - 1)(0.05) + (-1)(0.95) = 0.70 - 0.95 = -$0.25"
);
$decisions    = array(
  "you should not place this bet repeatedly. The expected loss is small per spin, but it accumulates: over 100 spins you would expect to lose about $26",
  "buying the ticket is a bad bet in terms of expected value. People still buy lottery tickets because the small cost feels worth the tiny chance at a life-changing prize, but the math says you lose $1.50 on average per ticket",
  "the game has negative expected value, so you should not plan to play it repeatedly. For a single try at a carnival it can still be fun, but expecting to come out ahead in the long run is unrealistic"
);
$longrun_phrases = array(
  "over many spins the average net result approaches -$0.26 per spin",
  "if you bought a million tickets, you would expect roughly one win and a total net loss of about $1.5 million",
  "across hundreds of tries the average net result approaches -$0.25 per play"
);

$topic_one_line = $scenarios[$i];
$ev_string = $ev_strings[$i];
$ev_compute = $ev_computes[$i];
$decision_text = $decisions[$i];
$longrun_text = $longrun_phrases[$i];

$sample_narrative = "The expected value of one play is <b>$ev_string</b>. Computed step by step: <b>$ev_compute</b>. Because `E(X)` is negative, <b>$decision_text</b>. The interpretation of expected value here is the long-run average: <b>$longrun_text</b>. So even though a single play could go either way, the math points to a steady loss over many plays.";

/* ---------- 2. SHARED CSS & JS ---------- */
$css_block = '
<style>
    .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
    .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
    .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; border:none; }
    .rubric-container details[open] summary { box-shadow: inset 0 -1px 0 #ccc; }
    .rubric-container summary::-webkit-details-marker { display:none; }
    .arrow-open { display:none; }
    .rubric-container details[open] .arrow-closed { display:none; }
    .rubric-container details[open] .arrow-open { display:inline; }
    .rubric-content { overflow:hidden; max-height:0; opacity:0; transition:max-height 300ms ease-out, opacity 300ms ease-out, padding 200ms ease-out; margin-top:0; background:#fafafa; box-sizing:border-box; padding:0 0.75em; }
    .rubric-container details[open] .rubric-content { max-height:2000px; opacity:1; padding:0.75em; }
    .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
    .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
    .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; user-select:text; }
    .row-colored { background:#fff9ea; }
    .col-header { width:25%; border-top-left-radius:8px; }
    .col-check { border-top-right-radius:8px; }
    .col-cat-bot { border-bottom-left-radius:8px; }
    .col-check-bot { border-bottom-right-radius:8px; }
    .ideal-ans { display:block; background-color:#e8f5e9; font-style:italic; font-weight:bold; font-size:0.95em; margin:5px 0 10px 0; border-left:3px solid #4CAF50; padding-left:8px; }
    .full-response-box { margin-top:15px; border:2px solid #4CAF50; background-color:#e8f5e9; padding:15px; border-radius:5px; }
</style>
<script>
document.addEventListener("DOMContentLoaded", function() {
  var details = document.querySelectorAll(".rubric-container details");
  details.forEach(function(det) {
    var content = det.querySelector(".rubric-content");
    det.addEventListener("toggle", function() {
      if (det.open) {
        content.style.maxHeight = content.scrollHeight + "px";
        content.style.opacity = "1";
      } else {
        content.style.maxHeight = content.scrollHeight + "px";
        content.offsetHeight;
        content.style.maxHeight = "0";
        content.style.opacity = "0";
      }
    });
    content.addEventListener("transitionend", function() {
      if (!det.open) content.style.maxHeight = null;
    });
  });
});
</script>';

/* ---------- 3. Student Rubric (Neutral Checklist) ---------- */
$rubricbutton = $css_block . '
<div class="rubric-container">
  <details>
    <summary>
      <span class="arrow-closed">&#9656;</span><span class="arrow-open">&#9662;</span>
      Click to View Grading Checklist
    </summary>
    <div class="rubric-content">
      <p style="margin:0 0 0.5em 0;"><b>Grading Criteria</b> -- ensure your response covers these points:</p>
      <table class="rubric-table">
        <tbody>
          <tr>
            <th class="col-header">Category</th>
            <th class="col-check">Requirement</th>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Compute Expected Value</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Identify the values of `X` (net outcomes) and the probabilities for each.</label></li>
                <li><label><input type="checkbox"> Show the `E(X) = sum x cdot P(X = x)` calculation step by step.</label></li>
                <li><label><input type="checkbox"> Report `E(X)` with the correct sign and units (dollars).</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Decision and Interpretation</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State whether the player should play or pass, based on `E(X)`.</label></li>
                <li><label><input type="checkbox"> Explain `E(X)` as a long-run average per play, not a guaranteed outcome of one play.</label></li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </details>
</div>';

/* ---------- 4. Instructor Rubric (With Answer Targets) ---------- */
$rubricanswerbutton = $css_block . '
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
            <th class="col-header">Category</th>
            <th class="col-check">Checklist &amp; Ideal Targets</th>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Compute Expected Value<br>(6 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Show the values of `X` and matching probabilities.
                    <span class="ideal-ans">Target: list the net win value, the net loss value, and the matching probabilities for this scenario.</span></li>
                <li>Apply `E(X) = sum x cdot P(X = x)` and show the arithmetic.
                    <span class="ideal-ans">Target: "'.$ev_compute.'"</span></li>
                <li>Report `E(X)` with the correct sign.
                    <span class="ideal-ans">Target: "'.$ev_string.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Decision and Interpretation<br>(4 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>State the decision tied to the sign of `E(X)`.
                    <span class="ideal-ans">Target: "'.$decision_text.'"</span></li>
                <li>Explain the long-run interpretation.
                    <span class="ideal-ans">Target: "'.$longrun_text.'"</span></li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="full-response-box">
        <span style="color:#2E7D32; font-weight:bold;">Model Narrative Response:</span><br><br>
        '.$sample_narrative.'
      </div>
    </div>
  </details>
</div>';

/* ---------- 5. Question Text ---------- */
$questiontext = '
<div style="font-family:Arial; font-size:medium; line-height:1.6;">
  <p>Suppose '.$topic.'.</p>
  <p><b>Essay Prompt:</b><br>
  Should the person in this scenario play, bet, or buy in the long run? Use expected value to support your reasoning.</p>
  <p>In your response, be sure to:</p>
  <ul>
    <li>Identify the net outcomes for `X` and the probability of each.</li>
    <li>Compute `E(X) = sum x cdot P(X = x)` and report the result with the correct sign.</li>
    <li>State your decision (play or pass) and explain how `E(X)` supports it as a long-run average.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
