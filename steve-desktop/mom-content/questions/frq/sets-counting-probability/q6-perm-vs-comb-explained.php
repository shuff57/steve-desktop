// === NAME - DESCRIPTION: FRQ: Explain Permutations vs Combinations - Contrast a perm scenario with a comb scenario, identify each, compute both, explain why "order matters" gives different counts ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
    array(
      "n" => 8, "r" => 3, "rfact" => 6,
      "perm_text" => "From 8 students, a President, Vice President, and Treasurer will be chosen.",
      "comb_text" => "From 8 students, a 3-person committee (no titles) will be chosen.",
      "perm_answer" => 336,
      "comb_answer" => 56
    ),
    array(
      "n" => 10, "r" => 4, "rfact" => 24,
      "perm_text" => "From 10 runners, the order in which the first 4 finish is recorded.",
      "comb_text" => "From 10 runners, a 4-person team (with no positions) is selected.",
      "perm_answer" => 5040,
      "comb_answer" => 210
    ),
    array(
      "n" => 12, "r" => 3, "rfact" => 6,
      "perm_text" => "From 12 books, 3 books will be placed on a shelf in a specific order (left, middle, right).",
      "comb_text" => "From 12 books, 3 books will be picked to give away (any order).",
      "perm_answer" => 1320,
      "comb_answer" => 220
    )
);
$i = rand(0, 2);
$c = $contexts[$i];
$n = $c["n"];
$r = $c["r"];
$rfact = $c["rfact"];
$perm_text = $c["perm_text"];
$comb_text = $c["comb_text"];
$perm_answer = $c["perm_answer"];
$comb_answer = $c["comb_answer"];

/* ---------- Narrative Variables ---------- */
$r_identify = "Scenario A is a <b>permutation</b> because the chosen $r are placed in distinguishable roles or positions, so order matters. Scenario B is a <b>combination</b> because the chosen $r form an unordered group, so order does not matter.";
$r_compute = "Permutation count: $n P $r = $perm_answer. Combination count: $n C $r = $comb_answer.";
$r_why = "The permutation count is exactly $r! = $rfact times the combination count, because every unordered group of $r people can be arranged in $rfact different orders. Dividing the permutation count by $r! removes the over-count from order, giving the combination count.";

$sample_narrative = "<b>Identify:</b> $r_identify <b>Compute:</b> $r_compute <b>Why they differ:</b> $r_why";

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
            <td style="text-align:center;"><b>Identify<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Label Scenario A as permutation OR combination, with reasoning.</label></li>
                <li><label><input type="checkbox"> Label Scenario B as the other, with reasoning.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Compute<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Compute the count for Scenario A.</label></li>
                <li><label><input type="checkbox"> Compute the count for Scenario B.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Explain Why They Differ<br>(4 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State in plain English what "order matters" means.</label></li>
                <li><label><input type="checkbox"> Explain WHY the permutation count is bigger than the combination count (using r! reasoning).</label></li>
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
            <td style="text-align:center;"><b>Identify<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Scenario A label.
                    <span class="ideal-ans">Target: "Permutation &mdash; the chosen '.$r.' are placed in distinguishable roles, so order matters."</span></li>
                <li>Scenario B label.
                    <span class="ideal-ans">Target: "Combination &mdash; the chosen '.$r.' form an unordered group, so order does not matter."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Compute<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Permutation count.
                    <span class="ideal-ans">Target: "'.$n.' P '.$r.' = '.$perm_answer.'"</span></li>
                <li>Combination count.
                    <span class="ideal-ans">Target: "'.$n.' C '.$r.' = '.$comb_answer.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Explain Why They Differ<br>(4 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Plain-English meaning of "order matters."
                    <span class="ideal-ans">Target: "If swapping two of the chosen items produces a different outcome (different roles, different positions), order matters &rarr; permutation. If the same group of '.$r.' counts as one outcome regardless of order, it does not matter &rarr; combination."</span></li>
                <li>Why permutation count > combination count.
                    <span class="ideal-ans">Target: "Each unordered group of '.$r.' can be arranged in '.$r.'! = '.$rfact.' different orders. Permutations count each ordering separately, so the permutation count is exactly '.$r.'! = '.$rfact.' times the combination count ('.$perm_answer.' = '.$rfact.' &times; '.$comb_answer.')."</span></li>
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
<p>Compare the following two scenarios. Both involve picking '.$r.' people (or items) from a group of '.$n.', but they are NOT the same kind of counting problem.</p>
<ul>
<li><b>Scenario A:</b> '.$perm_text.'</li>
<li><b>Scenario B:</b> '.$comb_text.'</li>
</ul>
<p>For each scenario:</p>
<ol>
<li>Identify whether it is a <b>permutation</b> or a <b>combination</b>, and justify with one sentence about whether order matters.</li>
<li>Compute the count.</li>
</ol>
<p>Then, in plain English, <b>explain WHY</b> one count is bigger than the other &mdash; what does it mean that "order matters" in one but not the other, and how does that translate into a numerical factor?</p>
'.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
