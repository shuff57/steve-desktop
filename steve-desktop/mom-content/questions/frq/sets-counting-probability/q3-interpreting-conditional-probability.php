// === NAME - DESCRIPTION: FRQ: Interpreting Conditional Probability - Explain P(A|B) in context and why it is not symmetric ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
    array(
        "row_var" => "drink preference",
        "col_var" => "time of day",
        "row_val" => "prefer coffee",
        "col_val" => "morning person",
        "total" => 200,
        "row_total" => 120,
        "col_total" => 90,
        "both_count" => 72
    ),
    array(
        "row_var" => "housing",
        "col_var" => "employment",
        "row_val" => "own their home",
        "col_val" => "employed full-time",
        "total" => 180,
        "row_total" => 95,
        "col_total" => 130,
        "both_count" => 78
    ),
    array(
        "row_var" => "exercise",
        "col_var" => "gender",
        "row_val" => "exercise regularly",
        "col_val" => "female",
        "total" => 160,
        "row_total" => 72,
        "col_total" => 88,
        "both_count" => 36
    )
);
$i = rand(0, 2);
$c = $contexts[$i];
$row_var = $c["row_var"];
$col_var = $c["col_var"];
$row_val = $c["row_val"];
$col_val = $c["col_val"];
$total = $c["total"];
$row_total = $c["row_total"];
$col_total = $c["col_total"];
$both_count = $c["both_count"];

$forward_prob = round($both_count / $row_total, 4);
$reverse_prob = round($both_count / $col_total, 4);
$forward_display = sprintf("%.3f", $forward_prob);
$reverse_display = sprintf("%.3f", $reverse_prob);

$r_interpretation = "P($col_val | $row_val) means: out of the people who are $row_val, what fraction are also $col_val? We restrict attention to the $row_total people who are $row_val, and among them, $both_count are also $col_val, so the probability is $both_count/$row_total = $forward_display";
$r_asymmetry = "they are different because each conditional probability uses a different reduced sample space. P($col_val | $row_val) restricts to the $row_total $row_val people as the denominator, while P($row_val | $col_val) restricts to the $col_total $col_val people. Unless the two groups happen to produce the same ratio, the probabilities will not be equal -- and here P($row_val | $col_val) = $both_count/$col_total = $reverse_display, which is different from $forward_display";
$r_clarity = "uses complete sentences, proper notation, and clearly distinguishes the two conditional probabilities";

$sample_narrative = "<b>P($col_val | $row_val)</b> asks: given that a person is <b>$row_val</b>, what is the probability they are also <b>$col_val</b>? $r_interpretation. The reversed conditional is different: $r_asymmetry.";

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
            <td style="text-align:center;"><b>Interpret the Conditional<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Restate P(A | B) in plain English for this context.</label></li>
                <li><label><input type="checkbox"> Identify the reduced sample space.</label></li>
                <li><label><input type="checkbox"> Compute the value from the table.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Explain the Asymmetry<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain why P(A | B) is not generally equal to P(B | A).</label></li>
                <li><label><input type="checkbox"> Give the reversed conditional for comparison.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Clear Communication<br>(2 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Write in complete sentences with proper notation.</label></li>
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
            <td style="text-align:center;"><b>Interpret the Conditional<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Restate P(A | B) in plain English for this context.
                    <span class="ideal-ans">Target: "P('.$col_val.' | '.$row_val.') means: out of the people who are '.$row_val.', what fraction are also '.$col_val.'?"</span></li>
                <li>Identify the reduced sample space.
                    <span class="ideal-ans">Target: "We restrict our attention to the '.$row_val.' group ('.$row_total.' people), not the full '.$total.'."</span></li>
                <li>Compute the value from the table.
                    <span class="ideal-ans">Target: "Divide the count who are both '.$row_val.' and '.$col_val.' ('.$both_count.') by the total '.$row_val.' ('.$row_total.'), giving '.$forward_display.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Explain the Asymmetry<br>(4 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain why P(A | B) is not generally equal to P(B | A).
                    <span class="ideal-ans">Target: "They use different reduced sample spaces. P('.$col_val.' | '.$row_val.') restricts to '.$row_val.' people; P('.$row_val.' | '.$col_val.') restricts to '.$col_val.' people. Unless the two groups are the same size and the overlap is symmetric, the probabilities differ."</span></li>
                <li>Give the reversed conditional for comparison.
                    <span class="ideal-ans">Target: "P('.$row_val.' | '.$col_val.') = '.$both_count.'/'.$col_total.' = '.$reverse_display.', which is different from '.$forward_display.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Clear Communication<br>(2 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Write in complete sentences with proper notation.
                    <span class="ideal-ans">Target: "Uses clear prose and distinguishes the two conditional probabilities."</span></li>
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
<p>A two-way table summarizes data on '.$row_var.' and '.$col_var.' for a group of '.$total.' people. Among the '.$row_total.' people who '.$row_val.', '.$both_count.' are also '.$col_val.'. Among the '.$col_total.' people who are '.$col_val.', '.$both_count.' also '.$row_val.'.</p>
<p>Explain what P('.$col_val.' | '.$row_val.') means in this context, and explain why P('.$col_val.' | '.$row_val.') is not generally the same as P('.$row_val.' | '.$col_val.').</p>
<p>In your explanation, be sure to cover:</p>
<ul>
<li>A plain-English restatement of P('.$col_val.' | '.$row_val.').</li>
<li>What the reduced sample space is (which group you are conditioning on).</li>
<li>The computed value from the numbers given.</li>
<li>Why switching what comes before and after the "|" changes the probability, and the value of the reversed conditional.</li>
</ul>
'.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
