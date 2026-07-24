// === NAME - DESCRIPTION: FRQ: Permutation vs Combination - Diagnose whether a counting problem is perm or comb, then compute ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$scenarios = array(
    array(
        "text" => "A club with 12 members needs to elect a President, Vice President, and Treasurer. How many different ways can these three positions be filled?",
        "type" => "permutation",
        "n" => 12,
        "r" => 3,
        "answer" => 1320,
        "reason" => "the three positions are distinct from each other -- electing A as President is different from electing A as Treasurer, so order matters"
    ),
    array(
        "text" => "From a group of 15 volunteers, a 4-person cleanup crew must be chosen. The crew members will all do the same job. How many different crews are possible?",
        "type" => "combination",
        "n" => 15,
        "r" => 4,
        "answer" => 1365,
        "reason" => "the crew members are not assigned to distinct roles -- the group is just a set of 4 people, so order does not matter"
    ),
    array(
        "text" => "You have 7 different books and want to arrange them on a shelf. How many different arrangements are possible?",
        "type" => "permutation",
        "n" => 7,
        "r" => 7,
        "answer" => 5040,
        "reason" => "each spot on the shelf is a distinct position -- arranging the books in a different order produces a different shelf, so order matters"
    )
);
$i = rand(0, 2);
$s = $scenarios[$i];
$type = $s["type"];
$n = $s["n"];
$r = $s["r"];
$answer = $s["answer"];
$reason = $s["reason"];
$scenario_text = $s["text"];

if ($type == "permutation") {
    $r_identification = "permutation";
    $r_justification = $reason . ", so the problem is a permutation";
    $expression_str = "$n P $r";
    $alt_expression_str = "$n! / ($n - $r)!";
} else {
    $r_identification = "combination";
    $r_justification = $reason . ", so the problem is a combination";
    $expression_str = "$n C $r";
    $alt_expression_str = "$n! / ($r! * ($n - $r)!)";
}

$r_computation = "$expression_str = $alt_expression_str = $answer";

$sample_narrative = "This problem is a <b>$r_identification</b> because <b>$r_justification</b>. To compute the answer, we use <b>$r_computation</b>.";

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
            <td style="text-align:center;"><b>Identify the Type<br>(2 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State whether the problem is a permutation or a combination.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Justify the Choice<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain what "order matters" or "order does not matter" means in this specific scenario.</label></li>
                <li><label><input type="checkbox"> Connect the scenario to the definition of permutation or combination.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Compute the Answer<br>(4 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Write the correct formula or expression (e.g. nPr or nCr).</label></li>
                <li><label><input type="checkbox"> State the final numerical answer.</label></li>
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
            <td style="text-align:center;"><b>Identify the Type<br>(2 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>State whether the problem is a permutation or a combination.
                    <span class="ideal-ans">Target: "This is a '.$r_identification.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Justify the Choice<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain what "order matters" or "order does not matter" means in this scenario.
                    <span class="ideal-ans">Target: "'.$r_justification.'."</span></li>
                <li>Connect the scenario to the definition of permutation or combination.
                    <span class="ideal-ans">Target: "Names the distinguishing feature (distinct positions/roles = permutation; group membership without roles = combination)."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Compute the Answer<br>(4 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Write the correct formula or expression.
                    <span class="ideal-ans">Target: "'.$expression_str.' = '.$alt_expression_str.'."</span></li>
                <li>State the final numerical answer.
                    <span class="ideal-ans">Target: "'.$answer.'."</span></li>
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
<p>Consider the following counting problem:</p>
<p><b>'.$scenario_text.'</b></p>
<p>Decide whether this problem calls for a permutation or a combination, explain your reasoning, and compute the answer.</p>
<p>In your explanation, be sure to cover:</p>
<ul>
<li>Whether you classified this as a permutation or a combination.</li>
<li>What it means for order to matter (or not) in this specific scenario.</li>
<li>The formula or expression you used, and your final numerical answer.</li>
</ul>
'.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
