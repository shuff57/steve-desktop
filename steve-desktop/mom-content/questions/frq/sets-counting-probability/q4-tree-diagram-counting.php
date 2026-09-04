// === NAME - DESCRIPTION: FRQ: Tree Diagram and Counting - Build a tree for a 2-stage experiment, list the sample space, compute a simple probability ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
    array(
      "stage1" => "flip a fair coin",
      "stage1_outcomes" => "H, T",
      "stage1_count" => 2,
      "stage2" => "roll a 4-sided die (sides 1-4)",
      "stage2_outcomes" => "1, 2, 3, 4",
      "stage2_count" => 4,
      "ss_size" => 8,
      "event_name" => "heads with an even number",
      "favorable" => "(H,2), (H,4)",
      "fav_count" => 2,
      "p_event" => "2/8 = 1/4 = 0.25"
    ),
    array(
      "stage1" => "draw a marble from a bag with one Red, one Green, and one Blue marble",
      "stage1_outcomes" => "R, G, B",
      "stage1_count" => 3,
      "stage2" => "flip a fair coin",
      "stage2_outcomes" => "H, T",
      "stage2_count" => 2,
      "ss_size" => 6,
      "event_name" => "Red marble AND tails",
      "favorable" => "(R, T)",
      "fav_count" => 1,
      "p_event" => "1/6 &approx; 0.1667"
    ),
    array(
      "stage1" => "spin a 3-sector spinner (sectors A, B, C)",
      "stage1_outcomes" => "A, B, C",
      "stage1_count" => 3,
      "stage2" => "draw a card from three numbered cards (1, 2, 3)",
      "stage2_outcomes" => "1, 2, 3",
      "stage2_count" => 3,
      "ss_size" => 9,
      "event_name" => "matching letter-number pair (A&1, B&2, C&3)",
      "favorable" => "(A,1), (B,2), (C,3)",
      "fav_count" => 3,
      "p_event" => "3/9 = 1/3 &approx; 0.3333"
    )
);
$i = rand(0, 2);
$c = $contexts[$i];
$stage1 = $c["stage1"];
$stage1_outcomes = $c["stage1_outcomes"];
$stage1_count = $c["stage1_count"];
$stage2 = $c["stage2"];
$stage2_outcomes = $c["stage2_outcomes"];
$stage2_count = $c["stage2_count"];
$ss_size = $c["ss_size"];
$event_name = $c["event_name"];
$favorable = $c["favorable"];
$fav_count = $c["fav_count"];
$p_event = $c["p_event"];

/* ---------- Narrative Variables ---------- */
$r_tree = "a tree with $stage1_count first-level branches ($stage1_outcomes) and $stage2_count second-level branches off each ($stage2_outcomes)";
$r_ss = "the sample space lists $ss_size equally likely outcomes ($stage1_count &times; $stage2_count = $ss_size by the multiplication principle)";
$r_event = "the event '$event_name' occurs in $fav_count of the $ss_size outcomes: $favorable";
$r_prob = "P(event) = favorable / total = $fav_count/$ss_size = $p_event";

$sample_narrative = "Stage 1: <b>$stage1</b>, giving outcomes $stage1_outcomes. Stage 2: <b>$stage2</b>, giving outcomes $stage2_outcomes. The tree has <b>$r_tree</b>. So <b>$r_ss</b>. For the event &quot;$event_name&quot;, <b>$r_event</b>. Therefore <b>$r_prob</b>.";

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
    .ideal-ans { display: block; background-color: #e8f5e9; font-style: italic; font-weight: bold; font-size: 0.95em; margin: 5px 0 10px 0; border-left: 3px solid #4CAF50; padding-left: 8px; }
    .full-response-box { margin-top: 15px; border: 2px solid #4CAF50; background-color: #e8f5e9; padding: 15px; border-radius: 5px; }
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
            <td style="text-align:center;"><b>Tree Diagram<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Draw correct first-stage branches with labels.</label></li>
                <li><label><input type="checkbox"> Draw correct second-stage branches off each first-stage branch.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Sample Space<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> List every outcome (or describe the structure).</label></li>
                <li><label><input type="checkbox"> State the total count and explain why (multiplication principle).</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Event &amp; Probability<br>(4 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Identify the favorable outcomes for the named event.</label></li>
                <li><label><input type="checkbox"> Compute and state the probability with reasoning.</label></li>
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
            <td style="text-align:center;"><b>Tree Diagram<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>First-stage branches.
                    <span class="ideal-ans">Target: "'.$stage1_count.' branches labeled '.$stage1_outcomes.'"</span></li>
                <li>Second-stage branches.
                    <span class="ideal-ans">Target: "'.$stage2_count.' branches off each first-stage branch, labeled '.$stage2_outcomes.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Sample Space<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Sample space listing.
                    <span class="ideal-ans">Target: "'.$ss_size.' outcomes; explicitly listed or described as all (stage1, stage2) pairs."</span></li>
                <li>Total via multiplication principle.
                    <span class="ideal-ans">Target: "'.$stage1_count.' &times; '.$stage2_count.' = '.$ss_size.' by the multiplication principle."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Event &amp; Probability<br>(4 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Favorable outcomes.
                    <span class="ideal-ans">Target: "'.$favorable.': '.$fav_count.' outcome(s)."</span></li>
                <li>Probability.
                    <span class="ideal-ans">Target: "P(event) = '.$fav_count.'/'.$ss_size.' = '.$p_event.'"</span></li>
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
<p>Consider a two-stage random experiment:</p>
<ul>
<li><b>Stage 1:</b> '.$stage1.'</li>
<li><b>Stage 2:</b> '.$stage2.'</li>
</ul>
<p>Draw a tree diagram for this experiment, list the full sample space, and find the probability of the event &quot;<b>'.$event_name.'</b>&quot;.</p>
<p>In your explanation, be sure to cover:</p>
<ul>
<li>How you draw and label both stages of the tree.</li>
<li>How you list (or describe) the sample space, and why the total count is what it is.</li>
<li>Which outcomes are favorable for the event, and the resulting probability.</li>
</ul>
'.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
