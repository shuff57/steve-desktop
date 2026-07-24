// === NAME - DESCRIPTION: Comparing Tests - Students explain what a chi-square test of independence and a difference of proportions test each compare in the context of a 2x2 table, then describe why both tests are mathematically equivalent for this type of data. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$i = rand(0, 2);

$sample_sizes = array(200, 180, 240);
$n = $sample_sizes[$i];

$contexts = array(
  "whether they passed or failed a class, and whether they attended tutoring or not",
  "whether they met a fitness goal or not, and whether they participated in a school exercise program or not",
  "whether they earned a B or above in chemistry, and whether they used an online study tool or not"
);
$context = $contexts[$i];

$row_vars = array("tutoring status", "exercise program participation", "study tool usage");
$col_vars = array("class outcome", "fitness goal outcome", "chemistry grade");
$row_var = $row_vars[$i];
$col_var = $col_vars[$i];

$group1s = array("students who attended tutoring", "students who participated in the exercise program", "students who used the study tool");
$group2s = array("students who did not attend tutoring", "students who did not participate in the program", "students who did not use the study tool");
$group1 = $group1s[$i];
$group2 = $group2s[$i];

$outcome_labels = array("passing the class", "meeting the fitness goal", "earning a B or above");
$outcome_label = $outcome_labels[$i];

// --- Chi-square explanation ---
$chisq_explanations = array(
  "A &#967;&#178; test of independence looks at the entire 2x2 table of tutoring status and class outcome. It tests whether knowing a student's tutoring status tells you anything about their likelihood of passing. It asks: are these two categorical variables independent, or is there a relationship between them in the population?",
  "A &#967;&#178; test of independence looks at the entire 2x2 table of program participation and fitness goal outcome. It tests whether knowing a student's participation status tells you anything about their likelihood of meeting the goal. It asks: are these two categorical variables independent, or is there a relationship between them in the population?",
  "A &#967;&#178; test of independence looks at the entire 2x2 table of study tool usage and chemistry grade. It tests whether knowing a student's study tool usage tells you anything about their likelihood of earning a B or above. It asks: are these two categorical variables independent, or is there a relationship between them in the population?"
);
$chisq_explain = $chisq_explanations[$i];

// --- Difference of proportions test explanation ---
$zprop_explanations = array(
  "A difference of proportions test directly compares two specific proportions: the pass rate among tutored students versus the pass rate among non-tutored students. Rather than looking at the whole table, it zeroes in on one outcome and asks whether the two groups differ on that proportion.",
  "A difference of proportions test directly compares two specific proportions: the rate of meeting the fitness goal among program participants versus the rate among non-participants. Rather than looking at the whole table, it zeroes in on one outcome and asks whether the two groups differ on that proportion.",
  "A difference of proportions test directly compares two specific proportions: the rate of earning a B or above among study tool users versus the rate among non-users. Rather than looking at the whole table, it zeroes in on one outcome and asks whether the two groups differ on that proportion."
);
$zprop_explain = $zprop_explanations[$i];

// --- Equivalence explanation ---
$equiv_explanation = "For a 2x2 table, the &#967;&#178; test of independence and the difference of proportions test are mathematically equivalent. They will always produce the same p-value and the same conclusion. This is because asking whether two binary variables are independent is the same question as asking whether the two group proportions are equal. The &#967;&#178; statistic in a 2x2 table is the square of the test statistic from the difference of proportions test. Either test is a valid choice for this data.";

// --- Build model narrative ---
$sample_narrative = "Both a &#967;&#178; test of independence and a difference of proportions test could be used here, and either one would be a valid choice.<br><br><b>What the &#967;&#178; test of independence compares:</b> $chisq_explain<br><br><b>What the difference of proportions test compares:</b> $zprop_explain<br><br><b>Why both tests work for a 2x2 table:</b> $equiv_explanation";

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
<script>document.addEventListener("DOMContentLoaded",function(){var d=document.querySelectorAll(".rubric-container details");d.forEach(function(det){var c=det.querySelector(".rubric-content");det.addEventListener("toggle",function(){if(det.open){c.style.maxHeight=c.scrollHeight+"px";c.style.opacity="1"}else{c.style.maxHeight=c.scrollHeight+"px";det.offsetHeight;c.style.maxHeight="0";c.style.opacity="0"}});c.addEventListener("transitionend",function(){if(!det.open)c.style.maxHeight=null})})});</script>';

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
            <td style="text-align:center;"><b>What Each Test Compares</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Describe what the &#967;&#178; test of independence examines and what question it answers about the data.</label></li>
                <li><label><input type="checkbox"> Describe what the difference of proportions test examines and what question it answers about the data.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Equivalence for 2x2 Tables</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State whether one test, the other, or both could be used for this data.</label></li>
                <li><label><input type="checkbox"> Explain how the two tests relate to each other when the data form a 2x2 table.</label></li>
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
            <td style="text-align:center;"><b>What Each Test Compares<br>(6 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Describe what the &#967;&#178; test of independence examines.
                    <span class="ideal-ans">Target: "'.$chisq_explain.'"</span></li>
                <li>Describe what the difference of proportions test examines.
                    <span class="ideal-ans">Target: "'.$zprop_explain.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Equivalence for 2x2 Tables<br>(4 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>State whether one or both tests could be used.
                    <span class="ideal-ans">Target: "Either test is a valid choice for this data. Both will produce the same p-value and the same conclusion."</span></li>
                <li>Explain how the two tests relate for a 2x2 table.
                    <span class="ideal-ans">Target: "'.$equiv_explanation.'"</span></li>
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
  <p>You have data on '.$n.' students showing '.$context.'. The data can be organized into a 2x2 table.</p>
  <p><b>Essay Prompt:</b><br>
  Would you use a &#967;&#178; (chi-square) test of independence or a difference of proportions test to analyze this data? Explain what each test would be comparing, and describe how the two tests relate to each other for a 2x2 table like this one.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>What the &#967;&#178; test of independence examines and what question it answers about the two variables.</li>
    <li>What the difference of proportions test examines and what specific proportions it compares.</li>
    <li>Whether one or both tests could be used, and how they relate to each other when the data form a 2x2 table.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton