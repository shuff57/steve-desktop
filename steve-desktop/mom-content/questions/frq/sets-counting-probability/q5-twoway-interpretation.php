// === NAME - DESCRIPTION: FRQ: Two-Way Table Interpretation - Compute marginals and a conditional, then explain in plain English why P(A|B) != P(B|A) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
    array(
      "topic" => "smartphone use vs. sleep quality",
      "labelA" => "Heavy phone use",      "labelB" => "Poor sleep",
      "rowA" => "Heavy phone use",        "rowB" => "Light phone use",
      "colA" => "Poor sleep",             "colB" => "Good sleep",
      "n" => 200, "a" => 70, "b" => 40, "c" => 30, "d" => 60
    ),
    array(
      "topic" => "coffee consumption vs. afternoon productivity",
      "labelA" => "Drinks coffee",        "labelB" => "High productivity",
      "rowA" => "Drinks coffee",          "rowB" => "No coffee",
      "colA" => "High productivity",      "colB" => "Low productivity",
      "n" => 150, "a" => 60, "b" => 30, "c" => 25, "d" => 35
    ),
    array(
      "topic" => "regular exercise vs. positive mood",
      "labelA" => "Exercises regularly",  "labelB" => "Reports positive mood",
      "rowA" => "Exercises regularly",    "rowB" => "Sedentary",
      "colA" => "Positive mood",          "colB" => "Negative mood",
      "n" => 180, "a" => 80, "b" => 30, "c" => 30, "d" => 40
    )
);
$i = rand(0, 2);
$ctx = $contexts[$i];
$topic  = $ctx["topic"];
$labelA = $ctx["labelA"];
$labelB = $ctx["labelB"];
$rowA   = $ctx["rowA"];
$rowB   = $ctx["rowB"];
$colA   = $ctx["colA"];
$colB   = $ctx["colB"];
$n  = $ctx["n"];
$a  = $ctx["a"];
$b  = $ctx["b"];
$c  = $ctx["c"];
$d  = $ctx["d"];

$rowA_total = $a + $b
$rowB_total = $c + $d
$colA_total = $a + $c
$colB_total = $b + $d

$pA  = $rowA_total / $n
$pB  = $colA_total / $n
$pAB = $a / $n
$pAgivB = $a / $colA_total
$pBgivA = $a / $rowA_total

$pA_show     = round($pA, 4)
$pB_show     = round($pB, 4)
$pAB_show    = round($pAB, 4)
$pAgivB_show = round($pAgivB, 4)
$pBgivA_show = round($pBgivA, 4)

$cell_plain = 'border:1px solid #dee1e3; padding:6px 12px; text-align:center;'
$head_style = 'border:1px solid #dee1e3; background:#f7f9fa; padding:6px 12px; font-weight:600;'

$tbl_html = '<table style="border-collapse:collapse; margin:0.6em auto;"><tr><th style="' . $head_style . '">&nbsp;</th><th style="' . $head_style . '">' . $colA . '</th><th style="' . $head_style . '">' . $colB . '</th><th style="' . $head_style . '">Total</th></tr>'
$tbl_html = $tbl_html . '<tr><th style="' . $head_style . '">' . $rowA . '</th><td style="' . $cell_plain . '">' . $a . '</td><td style="' . $cell_plain . '">' . $b . '</td><td style="' . $cell_plain . '">' . $rowA_total . '</td></tr>'
$tbl_html = $tbl_html . '<tr><th style="' . $head_style . '">' . $rowB . '</th><td style="' . $cell_plain . '">' . $c . '</td><td style="' . $cell_plain . '">' . $d . '</td><td style="' . $cell_plain . '">' . $rowB_total . '</td></tr>'
$tbl_html = $tbl_html . '<tr><th style="' . $head_style . '">Total</th><td style="' . $cell_plain . '">' . $colA_total . '</td><td style="' . $cell_plain . '">' . $colB_total . '</td><td style="' . $cell_plain . '">' . $n . '</td></tr>'
$tbl_html = $tbl_html . '</table>'

/* ---------- Narrative Variables ---------- */
$r_pA = "P(A) = $rowA_total/$n = $pA_show, since $rowA_total of the $n people are in the '$rowA' row";
$r_pB = "P(B) = $colA_total/$n = $pB_show, since $colA_total of the $n people are in the '$colA' column";
$r_pAB = "P(A &cap; B) = $a/$n = $pAB_show, the joint cell at '$rowA' and '$colA'";
$r_pAgivB = "P(A | B) = $a/$colA_total = $pAgivB_show. In context, this is the proportion of people with '$colA' who also have '$rowA'";
$r_asym = "P(A | B) = $pAgivB_show but P(B | A) = $pBgivA_show. They differ because they share the joint count $a but divide by different totals ($colA_total vs $rowA_total). The conditional flips the question being asked.";

$sample_narrative = "From the table, <b>$r_pA</b>; <b>$r_pB</b>; <b>$r_pAB</b>. Then <b>$r_pAgivB</b>. Asymmetry: <b>$r_asym</b>.";

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
            <td style="text-align:center;"><b>Marginals<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Compute P(A) and explain which row/total you used.</label></li>
                <li><label><input type="checkbox"> Compute P(B) and explain which column/total you used.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Joint &amp; Conditional<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State P(A &cap; B) using the joint cell.</label></li>
                <li><label><input type="checkbox"> Compute P(A | B) and interpret it in context.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Asymmetry Explanation<br>(4 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Compute P(B | A) and compare it to P(A | B).</label></li>
                <li><label><input type="checkbox"> Explain in plain English WHY they differ.</label></li>
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
            <td style="text-align:center;"><b>Marginals<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>P(A).
                    <span class="ideal-ans">Target: "P(A) = '.$rowA_total.'/'.$n.' = '.$pA_show.'"</span></li>
                <li>P(B).
                    <span class="ideal-ans">Target: "P(B) = '.$colA_total.'/'.$n.' = '.$pB_show.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Joint &amp; Conditional<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>P(A &cap; B).
                    <span class="ideal-ans">Target: "P(A &cap; B) = '.$a.'/'.$n.' = '.$pAB_show.' (the joint cell)."</span></li>
                <li>P(A | B) with interpretation.
                    <span class="ideal-ans">Target: "P(A | B) = '.$a.'/'.$colA_total.' = '.$pAgivB_show.'. In context: among people with '.$colA.', this fraction also have '.$rowA.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Asymmetry Explanation<br>(4 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>P(B | A) compared to P(A | B).
                    <span class="ideal-ans">Target: "P(B | A) = '.$a.'/'.$rowA_total.' = '.$pBgivA_show.', which differs from P(A | B) = '.$pAgivB_show.'."</span></li>
                <li>Why they differ.
                    <span class="ideal-ans">Target: "Same joint count ('.$a.'), but different denominators ('.$colA_total.' vs '.$rowA_total.'). Conditioning on a different event changes the reference group."</span></li>
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
<p>The following 2&times;2 table summarizes data on '.$topic.' from a sample of '.$n.' people. Let <b>A = "'.$labelA.'"</b> and <b>B = "'.$labelB.'"</b>.</p>
'.$tbl_html.'
<p>Using this table, find P(A), P(B), and P(A &cap; B). Then compute P(A | B) and interpret it in context. Finally, explain why P(A | B) and P(B | A) are NOT equal in general.</p>
<p>In your explanation, be sure to cover:</p>
<ul>
<li>Which row, column, or cell you used for each marginal and joint probability.</li>
<li>The numerical value of P(A | B) and what it means in plain English for this scenario.</li>
<li>The numerical value of P(B | A) and a clear reason why P(A | B) &ne; P(B | A).</li>
</ul>
'.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
