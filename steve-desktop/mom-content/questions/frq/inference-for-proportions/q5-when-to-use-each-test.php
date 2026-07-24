// === NAME - DESCRIPTION: When to Use Each Test - Students read a research scenario and decide whether a single proportion test or a two-proportion test is appropriate, then explain their reasoning and why the other test does not apply. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$i = rand(0, 2);

// Three scenarios: contexts 0 and 2 need a single proportion test, context 1 needs a two-proportion test
$scenarios = array(
  "a local health department wants to know whether more than 40% of adults in their county have received a flu shot this season. They survey a random sample of adults from the county",
  "a school principal wants to compare the proportion of students who passed a state math exam at two different high schools in the district. They collect pass/fail results from both schools",
  "a restaurant chain claims that 70% of customers are satisfied with their new menu. A consumer group surveys a random sample of the chain's customers to test this claim"
);
$scenario = $scenarios[$i];

$correct_tests = array("single proportion test", "two-proportion test", "single proportion test");
$wrong_tests = array("two-proportion test", "single proportion test", "two-proportion test");
$correct_test = $correct_tests[$i];
$wrong_test = $wrong_tests[$i];

// How many groups and what is being compared
$group_descriptions = array(
  "one group (adults in the county)",
  "two groups (students at School A and students at School B)",
  "one group (customers of the restaurant chain)"
);
$comparison_descriptions = array(
  "one sample proportion against a fixed benchmark value of 0.40",
  "two sample proportions against each other to see which school has a higher pass rate",
  "one sample proportion against the chain's claimed value of 0.70"
);
$group_desc = $group_descriptions[$i];
$comparison_desc = $comparison_descriptions[$i];

// Why the correct test fits
$why_correct_reasons = array(
  "there is only one group being studied (county adults), and the research question asks whether their flu shot rate differs from a specific claimed value (40%). A single proportion test is designed for exactly this situation: comparing one sample proportion to a known or hypothesized value.",
  "there are two separate groups (School A students and School B students), and the research question asks whether their pass rates differ from each other. A two-proportion test is designed for comparing proportions between two independent groups, with no fixed benchmark involved.",
  "there is only one group being studied (the chain's customers), and the research question asks whether the true satisfaction rate differs from the company's specific claim of 70%. A single proportion test is designed for comparing one sample proportion to a fixed claimed value."
);
$why_correct = $why_correct_reasons[$i];

// Why the other test does NOT fit
$why_wrong_reasons = array(
  "a two-proportion test requires two separate groups whose proportions are being compared to each other. In this scenario, there is only one group of adults and one proportion being tested against a fixed value (40%), so there is no second group to compare.",
  "a single proportion test requires comparing one group's proportion to a fixed or claimed number. In this scenario, there is no fixed benchmark. The question is entirely about how the two schools compare to each other, so there is no single known value to test against.",
  "a two-proportion test requires two separate groups whose proportions are being compared to each other. In this scenario, there is only one group of customers and one proportion being tested against the company's claim (70%), so there is no second group to compare."
);
$why_wrong = $why_wrong_reasons[$i];

// What kind of scenario would call for the other test
$other_test_scenarios = array(
  "You would use a two-proportion test if the health department wanted to compare flu shot rates between two different counties or between two different age groups, where each group has its own proportion and you want to know if those proportions differ.",
  "You would use a single proportion test if the principal wanted to know whether one particular school's pass rate differs from a statewide benchmark, like 65%, rather than comparing two schools to each other.",
  "You would use a two-proportion test if the consumer group wanted to compare satisfaction rates between two different restaurant chains, where each chain's customers form a separate group and you want to know if those rates differ."
);
$other_scenario = $other_test_scenarios[$i];

// Build model narrative
$sample_narrative = "The researcher should use a <b>$correct_test</b>. This scenario involves $group_desc, and the comparison is $comparison_desc. The $correct_test is the right choice because $why_correct A $wrong_test would not be appropriate here because $why_wrong. $other_scenario";

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
            <td style="text-align:center;"><b>Choosing the Correct Test</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State which test the researcher should use (single proportion or two-proportion).</label></li>
                <li><label><input type="checkbox"> Identify how many groups are involved and what is being compared.</label></li>
                <li><label><input type="checkbox"> Explain why the chosen test fits the structure of this research question.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Ruling Out the Other Test</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain why the other test would not be appropriate for this scenario.</label></li>
                <li><label><input type="checkbox"> Describe what kind of situation would call for the other test instead.</label></li>
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
            <td style="text-align:center;"><b>Choosing the Correct Test<br>(6 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>State which test the researcher should use.
                    <span class="ideal-ans">Target: "The researcher should use a '.$correct_test.'."</span></li>
                <li>Identify how many groups are involved and what is being compared.
                    <span class="ideal-ans">Target: "This scenario involves '.$group_desc.', comparing '.$comparison_desc.'."</span></li>
                <li>Explain why the chosen test fits this research question.
                    <span class="ideal-ans">Target: "A '.$correct_test.' is appropriate because '.$why_correct.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Ruling Out the Other Test<br>(4 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain why the other test would not be appropriate.
                    <span class="ideal-ans">Target: "A '.$wrong_test.' would not work here because '.$why_wrong.'."</span></li>
                <li>Describe what kind of situation would call for the other test.
                    <span class="ideal-ans">Target: "'.$other_scenario.'"</span></li>
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
  <p>Suppose '.$scenario.'.</p>
  <p><b>Essay Prompt:</b><br>
  Should the researcher use a single proportion test or a two-proportion test? Explain why that test is the right choice for this scenario, and why the other test would not be appropriate.</p>
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>Which test the researcher should use, how many groups are involved, and what is being compared.</li>
    <li>Why the chosen test fits the structure of this research question.</li>
    <li>Why the other test would not work here, and what kind of scenario would call for it instead.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton