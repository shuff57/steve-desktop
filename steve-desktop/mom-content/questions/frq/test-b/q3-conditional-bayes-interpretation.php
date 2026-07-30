// === NAME - DESCRIPTION: FRQ: Conditional Probability and Bayes Interpretation - Explain P(A|B) in context, why P(A|B) is not equal to P(B|A), how to compute from the table, and why the asymmetry matters in decisions. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
    array(
        "topic"        => "a medical screening test for a rare condition",
        "A"            => "has the condition",
        "B"            => "tests positive",
        "notA"         => "does not have the condition",
        "notB"         => "tests negative",
        "n"            => 1000,
        "tp"           => 90,
        "fp"           => 80,
        "fn"           => 10,
        "tn"           => 820,
        "pAgivB_num"   => 90,
        "pAgivB_den"   => 170,
        "pAgivB_show"  => "0.529",
        "pBgivA_num"   => 90,
        "pBgivA_den"   => 100,
        "pBgivA_show"  => "0.900",
        "practical"    => "a patient who tests positive has only about a 53% chance of actually having the condition, even though the test catches 90% of true cases. Treating everyone who tests positive as definitely sick would lead to many unnecessary follow-up procedures for the 80 people who are false positives"
    ),
    array(
        "topic"        => "an email spam filter",
        "A"            => "email is spam",
        "B"            => "filter flags it",
        "notA"         => "email is legitimate",
        "notB"         => "filter passes it",
        "n"            => 500,
        "tp"           => 180,
        "fp"           => 20,
        "fn"           => 45,
        "tn"           => 255,
        "pAgivB_num"   => 180,
        "pAgivB_den"   => 200,
        "pAgivB_show"  => "0.900",
        "pBgivA_num"   => 180,
        "pBgivA_den"   => 225,
        "pBgivA_show"  => "0.800",
        "practical"    => "P(spam | flagged) = 0.900, so 90% of flagged emails are actually spam. But P(flagged | spam) = 0.800, meaning the filter misses 20% of spam. Confusing these two would lead to wrong conclusions: a manager who thinks the flag rate equals the catch rate might think the filter is better than it actually is"
    ),
    array(
        "topic"        => "a quality control check on a factory assembly line",
        "A"            => "part is defective",
        "B"            => "inspector flags it",
        "notA"         => "part is non-defective",
        "notB"         => "inspector passes it",
        "n"            => 800,
        "tp"           => 60,
        "fp"           => 30,
        "fn"           => 15,
        "tn"           => 695,
        "pAgivB_num"   => 60,
        "pAgivB_den"   => 90,
        "pAgivB_show"  => "0.667",
        "pBgivA_num"   => 60,
        "pBgivA_den"   => 75,
        "pBgivA_show"  => "0.800",
        "practical"    => "P(defective | flagged) = 0.667, so only about 2 out of 3 flagged parts are actually defective, while P(flagged | defective) = 0.800, meaning the inspector catches 80% of true defects. Confusing these leads to different decisions: one describes how reliable a flag is; the other describes how thorough the inspection is"
    )
)
$i = rand(0, 2)
$c = $contexts[$i]
$topic       = $c["topic"]
$A           = $c["A"]
$B           = $c["B"]
$notA        = $c["notA"]
$notB        = $c["notB"]
$n           = $c["n"]
$tp          = $c["tp"]
$fp          = $c["fp"]
$fn          = $c["fn"]
$tn          = $c["tn"]
$pAgivB_num  = $c["pAgivB_num"]
$pAgivB_den  = $c["pAgivB_den"]
$pAgivB_show = $c["pAgivB_show"]
$pBgivA_num  = $c["pBgivA_num"]
$pBgivA_den  = $c["pBgivA_den"]
$pBgivA_show = $c["pBgivA_show"]
$practical   = $c["practical"]

$rowA_total  = $tp + $fn
$rowB_total  = $fp + $tn
$colA_total  = $tp + $fp
$colB_total  = $fn + $tn

$cell_plain = 'border:1px solid #dee1e3; padding:6px 12px; text-align:center;'
$head_style = 'border:1px solid #dee1e3; background:#f7f9fa; padding:6px 12px; font-weight:600;'

$tbl_html = '<table style="border-collapse:collapse; margin:0.6em auto;"><tr><th style="' . $head_style . '">&nbsp;</th><th style="' . $head_style . '">' . $B . '</th><th style="' . $head_style . '">NOT (' . $B . ')</th><th style="' . $head_style . '">Total</th></tr>'
$tbl_html = $tbl_html . '<tr><th style="' . $head_style . '">' . $A . '</th><td style="' . $cell_plain . '">' . $tp . '</td><td style="' . $cell_plain . '">' . $fn . '</td><td style="' . $cell_plain . '">' . $rowA_total . '</td></tr>'
$tbl_html = $tbl_html . '<tr><th style="' . $head_style . '">' . $notA . '</th><td style="' . $cell_plain . '">' . $fp . '</td><td style="' . $cell_plain . '">' . $tn . '</td><td style="' . $cell_plain . '">' . $rowB_total . '</td></tr>'
$tbl_html = $tbl_html . '<tr><th style="' . $head_style . '">Total</th><td style="' . $cell_plain . '">' . $colA_total . '</td><td style="' . $cell_plain . '">' . $colB_total . '</td><td style="' . $cell_plain . '">' . $n . '</td></tr>'
$tbl_html = $tbl_html . '</table>'

/* ---------- Narrative Variables ---------- */
$r_meaning = 'P(A | B) = P('.$A.' | '.$B.') means: given that we know the outcome is '.$B.', what is the probability that '.$A.' is also true? We restrict our attention to the '.$colA_total.' cases where '.$B.', and among those, '.$pAgivB_num.' also '.$A.', so P(A | B) = '.$pAgivB_num.'/'.$pAgivB_den.' = '.$pAgivB_show
$r_asymmetry = 'P(A | B) and P(B | A) use the same joint count ('.$pAgivB_num.') but different denominators. P(A | B) divides by the '.$B.' total ('.$pAgivB_den.'), while P(B | A) divides by the '.$A.' total ('.$pBgivA_den.'). The result P(B | A) = '.$pBgivA_num.'/'.$pBgivA_den.' = '.$pBgivA_show.', which is different from '.$pAgivB_show
$r_compute = 'to find P(A | B), take the count in the joint cell where both '.$A.' and '.$B.' are true ('.$pAgivB_num.') and divide by the marginal total for '.$B.' ('.$pAgivB_den.'). This formula P(A | B) = P(A and B) / P(B) restricts the sample space to only the '.$B.' cases'
$r_practical = 'the practical stakes are real: '.$practical

$sample_narrative = 'In the context of '.$topic.', <b>'.$r_meaning.'</b>. The asymmetry: <b>'.$r_asymmetry.'</b>. Computing from the table: <b>'.$r_compute.'</b>. Why it matters: <b>'.$r_practical.'</b>.'

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
            <td style="text-align:center;"><b>Conditional Meaning<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Restate P(A | B) in plain English for this specific context.</label></li>
                <li><label><input type="checkbox"> Identify which group you are restricting attention to (the reduced sample space).</label></li>
                <li><label><input type="checkbox"> Give the numerical value of P(A | B) using the table.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Asymmetry Explanation<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State that P(A | B) is generally not equal to P(B | A).</label></li>
                <li><label><input type="checkbox"> Explain why the two values differ (different denominators, different reference groups).</label></li>
                <li><label><input type="checkbox"> Give the numerical value of P(B | A) from the table.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Computation Logic<br>(2 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Describe which table cells you used to compute P(A | B) and why.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Practical Importance<br>(2 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain a concrete consequence of confusing P(A | B) with P(B | A) in this context.</label></li>
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
            <td style="text-align:center;"><b>Conditional Meaning<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Plain-English restatement of P(A | B).
                    <span class="ideal-ans">Target: "P('.$A.' | '.$B.') means: among all the cases where ' . $B . ', what fraction also ' . $A . '?"</span></li>
                <li>Identify the reduced sample space.
                    <span class="ideal-ans">Target: "We restrict attention to the '.$pAgivB_den.' cases where ' . $B . ' (not the full '.$n.')."</span></li>
                <li>Numerical value.
                    <span class="ideal-ans">Target: "P(A | B) = '.$pAgivB_num.'/'.$pAgivB_den.' = '.$pAgivB_show.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Asymmetry Explanation<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>State that P(A | B) is not equal to P(B | A).
                    <span class="ideal-ans">Target: "P(A | B) = '.$pAgivB_show.' but P(B | A) = '.$pBgivA_show.'; they are not equal."</span></li>
                <li>Why they differ.
                    <span class="ideal-ans">Target: "Both fractions share the same numerator ('.$pAgivB_num.' joint cases) but use different denominators: P(A | B) divides by the ' . $B . ' total ('.$pAgivB_den.'), while P(B | A) divides by the ' . $A . ' total ('.$pBgivA_den.'). Different denominators give different answers."</span></li>
                <li>Value of P(B | A).
                    <span class="ideal-ans">Target: "P('.$B.' | '.$A.') = '.$pBgivA_num.'/'.$pBgivA_den.' = '.$pBgivA_show.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Computation Logic<br>(2 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Which cells and why.
                    <span class="ideal-ans">Target: "Take the joint cell where both ' . $A . ' and ' . $B . ' are true ('.$pAgivB_num.') and divide by the marginal total for ' . $B . ' ('.$pAgivB_den.'). This is P(A and B) / P(B), which restricts the universe to B cases."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Practical Importance<br>(2 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>A concrete consequence of confusing the two.
                    <span class="ideal-ans">Target: "'.$practical.'"</span></li>
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
<p>The table below summarizes results from <b>'.$topic.'</b> based on a sample of '.$n.' cases. Let <b>A = "'.$A.'"</b> and <b>B = "'.$B.'"</b>.</p>
'.$tbl_html.'
<p>Write 4-6 sentences explaining conditional probability using this table. You do not need to re-derive the probability rules from scratch, but you should use the numbers in the table to support your reasoning.</p>
<p>In your explanation, be sure to cover:</p>
<ul>
<li>What P(A | B) means in plain English for this specific context.</li>
<li>Why P(A | B) is generally NOT equal to P(B | A), and the value of each from the table.</li>
<li>Which cells in the table you use to compute P(A | B) and why.</li>
<li>A concrete reason why mixing up P(A | B) and P(B | A) would lead to a bad decision in this situation.</li>
</ul>
'.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
