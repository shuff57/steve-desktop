// === NAME - DESCRIPTION: Chi-Square Goodness of Fit - Students evaluate whether observed data matches an expected distribution, explain their reasoning without calculations, and describe what a chi-square goodness of fit test measures. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
  "you roll a standard six-sided die 60 times and get the following results: 8 ones, 9 twos, 11 threes, 10 fours, 12 fives, and 10 sixes",
  "you grab a handful of 200 candies from a large bag that claims to contain equal proportions of five colors, and you count: 52 red, 41 blue, 38 green, 35 yellow, and 34 orange",
  "you observe 120 cars arriving at an intersection where the city claims traffic splits evenly among three directions, and you count: 28 turning left, 65 going straight, and 27 turning right"
);
$i = rand(0, count($contexts)-1);
$topic = $contexts[$i];

$items = array("die", "bag of candies", "intersection");
$n_obs = array("60", "200", "120");
$n_cats = array("6", "5", "3");
$cat_labels = array("faces of the die (1 through 6)", "candy colors (red, blue, green, yellow, orange)", "directions (left, straight, right)");
$exp_each = array("10", "40", "40");
$exp_basis = array("each face would come up about 10 times out of 60 rolls", "each color would appear about 40 times out of 200 candies", "each direction would have about 40 cars out of 120 total");
$obs_summary = array("the observed counts (8, 9, 11, 10, 12, 10) are all fairly close to 10, with the largest gap being only 2 away", "the observed counts (52, 41, 38, 35, 34) show noticeable variation from 40, especially red at 52, which is 12 higher than expected", "the observed counts (28, 65, 27) are far from equal -- straight-through traffic at 65 is way higher than the expected 40, while left and right are both well below");
$fair_judgment = array("the die appears roughly fair because the observed frequencies are all close to the expected value of 10, with only small deviations that look like normal random variation", "the distribution looks somewhat uneven because red appears much more often than expected, though the other colors are only slightly off -- this could be random variation, but the gap for red is suspicious", "the distribution clearly does not match the claimed equal split because the straight-through count of 65 is far above the expected 40, which is too large a deviation to easily explain as random chance");

$item = $items[$i];
$obs = $n_obs[$i];
$cats = $n_cats[$i];
$labels = $cat_labels[$i];
$expected = $exp_each[$i];
$basis = $exp_basis[$i];
$summary = $obs_summary[$i];
$judgment = $fair_judgment[$i];

// Narrative variables for the model answer
$r_judgment = $judgment;
$r_expected = "if the distribution were truly equal, $basis. Looking at the data, $summary";
$r_chisquare = "a &#967;&#178; goodness of fit test measures how well an observed frequency distribution matches an expected distribution. It computes (observed - expected)&#178; / expected for each category, then adds up all those values into one test statistic. A larger &#967;&#178; value means the observed data deviates more from what was expected, while a small &#967;&#178; means the data fits the expected pattern closely";

$sample_narrative = "Looking at this data, <b>$r_judgment</b>. If the distribution were truly equal, <b>$r_expected</b>. A &#967;&#178; goodness of fit test would formalize this intuitive comparison. Specifically, <b>$r_chisquare</b>.";

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
      <p style="margin:0 0 0.5em 0;"><b>Grading Criteria</b> -- ensure your explanation covers these points:</p>
      <table class="rubric-table">
        <tbody>
          <tr>
            <th class="col-header">Category</th>
            <th class="col-check">Requirement</th>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Fairness Judgment</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State whether you believe the distribution is fair or unfair.</label></li>
                <li><label><input type="checkbox"> Explain what the expected frequencies would be if the distribution were truly equal.</label></li>
                <li><label><input type="checkbox"> Describe how the observed results compare to those expected values and why that supports your conclusion.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>&#967;&#178; Test Purpose</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Describe what a &#967;&#178; goodness of fit test measures.</label></li>
                <li><label><input type="checkbox"> Explain how the test uses observed and expected frequencies.</label></li>
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
            <td style="text-align:center;"><b>Fairness Judgment<br>(5 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>State whether the distribution appears fair or unfair.
                    <span class="ideal-ans">Target: "'.$judgment.'"</span></li>
                <li>Identify the expected frequencies if the distribution were equal.
                    <span class="ideal-ans">Target: "If the distribution were truly equal, '.$basis.'."</span></li>
                <li>Compare observed results to expected values and connect that to the conclusion.
                    <span class="ideal-ans">Target: "'.$summary.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>&#967;&#178; Test Purpose<br>(5 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Describe what the &#967;&#178; goodness of fit test measures.
                    <span class="ideal-ans">Target: "A &#967;&#178; goodness of fit test measures how well an observed frequency distribution matches an expected distribution across multiple categories."</span></li>
                <li>Explain how the test uses observed and expected frequencies.
                    <span class="ideal-ans">Target: "The test computes (observed - expected)&#178; / expected for each category, then sums those values into a single &#967;&#178; statistic. A larger value means greater deviation from what was expected; a smaller value means the data fits the expected pattern closely."</span></li>
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
  <p>Imagine '.$topic.'.</p>
  <p><b>Essay Prompt:</b><br>
  Without doing any calculations, do you think this distribution is fair (equal across categories) or unfair? Explain your reasoning. Then describe what a &#967;&#178; (chi-square) goodness of fit test would measure in this situation.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>Whether you believe the distribution is fair or unfair, including what you would expect to see and how the observed data compares.</li>
    <li>What a &#967;&#178; goodness of fit test measures and how it uses observed and expected frequencies.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
