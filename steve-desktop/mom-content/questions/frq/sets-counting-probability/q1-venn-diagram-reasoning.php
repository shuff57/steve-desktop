// === NAME - DESCRIPTION: FRQ: Venn Diagram Reasoning - Draw and interpret a 2-circle Venn diagram from survey data ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
    array("subj1" => "art", "subj2" => "biology", "n" => 120, "a" => 45, "b" => 52, "ab" => 14),
    array("subj1" => "chess", "subj2" => "drama", "n" => 150, "a" => 38, "b" => 55, "ab" => 12),
    array("subj1" => "band", "subj2" => "choir", "n" => 100, "a" => 42, "b" => 36, "ab" => 8)
);
$i = rand(0, 2);
$c = $contexts[$i];
$subj1 = $c["subj1"];
$subj2 = $c["subj2"];
$n = $c["n"];
$a = $c["a"];
$b = $c["b"];
$ab = $c["ab"];

$a_only = $a - $ab;
$b_only = $b - $ab;
$exactly_one = $a_only + $b_only;
$at_least_one = $a + $b - $ab;
$neither = $n - $at_least_one;

/* ---------- Narrative Variables ---------- */
$r_diagram = "two overlapping circles labeled $subj1 and $subj2, with $ab in the overlap, $a_only in the $subj1-only region, and $b_only in the $subj2-only region";
$r_exactly_one = "the students in exactly one subject are in the $subj1-only region ($a_only) plus the $subj2-only region ($b_only), which is $exactly_one";
$r_neither = "to find how many take neither, subtract everyone in at least one subject from the total: $n - ($a + $b - $ab) = $n - $at_least_one = $neither";

$sample_narrative = "A survey of <b>$n students</b> found <b>$a take $subj1</b>, <b>$b take $subj2</b>, and <b>$ab take both</b>. The Venn diagram has <b>$r_diagram</b>. Students taking <b>exactly one</b> subject are <b>$r_exactly_one</b>. For the neither count, <b>$r_neither</b>.";

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
            <td style="text-align:center;"><b>Venn Diagram Setup<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Draw two overlapping circles labeled with the two subjects.</label></li>
                <li><label><input type="checkbox"> Place the given numbers in the correct regions.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Exactly One Calculation<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Identify which regions count as "exactly one."</label></li>
                <li><label><input type="checkbox"> Give the correct count with an explanation.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Neither Calculation<br>(4 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain how to find the "neither" count.</label></li>
                <li><label><input type="checkbox"> Compute and state the result.</label></li>
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
            <td style="text-align:center;"><b>Venn Diagram Setup<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Two overlapping circles labeled with the subjects.
                    <span class="ideal-ans">Target: "Two overlapping circles with labels matching the subjects ('.$subj1.' and '.$subj2.')."</span></li>
                <li>Numbers placed in the correct regions.
                    <span class="ideal-ans">Target: "'.$ab.' in the overlap, '.$a_only.' in '.$subj1.'-only, '.$b_only.' in '.$subj2.'-only."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Exactly One Calculation<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Identify which regions count as "exactly one."
                    <span class="ideal-ans">Target: "Exactly one means in '.$subj1.'-only OR '.$subj2.'-only, not the overlap. Add the two outer pieces: '.$a_only.' + '.$b_only.' = '.$exactly_one.'."</span></li>
                <li>Give the correct count with an explanation.
                    <span class="ideal-ans">Target: "Correct sum of '.$exactly_one.' computed."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Neither Calculation<br>(4 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain how to find the "neither" count.
                    <span class="ideal-ans">Target: "Total students ('.$n.') minus everyone in at least one subject. Everyone in at least one = '.$a_only.' + '.$b_only.' + '.$ab.' = '.$a.' + '.$b.' - '.$ab.' = '.$at_least_one.'. So neither = '.$n.' - '.$at_least_one.' = '.$neither.'."</span></li>
                <li>Compute and state the result.
                    <span class="ideal-ans">Target: "Correct neither count of '.$neither.' with clear reasoning."</span></li>
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
<p>A survey of '.$n.' students found that '.$a.' take '.$subj1.', '.$b.' take '.$subj2.', and '.$ab.' take both.</p>
<p>Draw and label a 2-circle Venn diagram for this situation, then answer: how many students take exactly one of these subjects, and how many take neither?</p>
<p>In your explanation, be sure to cover:</p>
<ul>
<li>How you label the two circles and where you place each given number.</li>
<li>Which regions of the diagram count as "exactly one," and how you found that total.</li>
<li>How you used the diagram (or formulas) to find the number of students who take neither subject.</li>
</ul>
'.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
