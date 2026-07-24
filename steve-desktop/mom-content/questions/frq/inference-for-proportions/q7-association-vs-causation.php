// === NAME - DESCRIPTION: Association vs. Causation - Students explain why an observed association does not prove causation, provide at least two specific alternative explanations for the pattern, and describe what study design would be needed to support a causal claim. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$i = rand(0, 2);

$scenarios = array(
  "a study finds that students who drink more coffee tend to get lower grades",
  "a study finds that children who spend more time watching TV tend to score lower on reading tests",
  "a study finds that adults who exercise more frequently tend to report higher levels of happiness"
);
$scenario = $scenarios[$i];

$var_a = array("drinking coffee", "watching more TV", "exercising more frequently");
$var_b = array("lower grades", "lower reading scores", "higher happiness");
$va = $var_a[$i];
$vb = $var_b[$i];

$causal_claims = array(
  "drinking coffee causes lower grades",
  "watching TV causes lower reading scores",
  "exercising causes people to be happier"
);
$causal_claim = $causal_claims[$i];

// --- Alternative explanation 1: confounding variable ---
$alt1_labels = array("stress and course difficulty", "lack of parental involvement", "physical health and income");
$alt1_explanations = array(
  "Students who are overwhelmed by a heavy or difficult course load may turn to coffee to stay awake and study longer. That same academic pressure is likely what drags their grades down. The coffee is a symptom of the struggle, not the cause of poor performance.",
  "Children whose parents are less involved in their daily routines may end up with more unsupervised screen time. Those same households may also provide fewer reading opportunities, such as bedtime stories or trips to the library. The lack of parental involvement could drive both the TV watching and the weaker reading skills.",
  "People with higher incomes and better overall health tend to have more time and energy to exercise. Those same advantages, including lower financial stress, better access to healthcare, and more leisure time, could also explain why they report being happier. The exercise may not be the reason for the happiness; a more comfortable life could be behind both."
);
$alt1_label = $alt1_labels[$i];
$alt1_explain = $alt1_explanations[$i];

// --- Alternative explanation 2: reverse causation or different confounder ---
$alt2_labels = array("sleep deprivation", "reading difficulty leading to screen preference", "happiness leading to more activity");
$alt2_explanations = array(
  "Students who are already struggling academically may sleep poorly due to worry, then drink coffee to compensate for lost sleep. In this case the poor grades come first and the coffee follows. The causal arrow might point the other direction: academic difficulty leads to sleep loss, which leads to coffee, rather than coffee leading to bad grades.",
  "Children who find reading difficult or frustrating may naturally gravitate toward TV because it requires less effort. Their low reading ability came first and pushed them toward screens, rather than the screens damaging their reading skills. The causal direction could be reversed.",
  "People who are already happier and more optimistic may be more motivated to get off the couch and go for a run. If happiness comes first and drives the exercise habit, then the causal direction is the opposite of what the headline suggests. Exercise might not be creating the happiness at all."
);
$alt2_label = $alt2_labels[$i];
$alt2_explain = $alt2_explanations[$i];

// --- Study design that would better support causation ---
$better_studies = array(
  "To test whether coffee actually causes lower grades, researchers would need a controlled experiment. They could randomly assign students to drink a set amount of coffee or a placebo each day for a semester, while keeping other factors like course load and sleep habits as similar as possible. Because the original study only observed existing behavior without controlling for anything, there is no way to separate the effect of coffee from all the other differences between heavy coffee drinkers and light coffee drinkers.",
  "To test whether TV actually causes lower reading scores, researchers would need a controlled experiment. They could randomly assign children to different amounts of daily screen time over several months, while keeping other factors like parental involvement and reading instruction constant. Because the original study only observed existing behavior without controlling for anything, there is no way to know whether the TV caused the reading gap or something else did.",
  "To test whether exercise actually causes greater happiness, researchers would need a controlled experiment. They could randomly assign adults to different exercise routines for several weeks, while keeping other lifestyle factors as similar as possible. Because the original study only observed existing behavior without controlling for anything, there is no way to separate the effect of exercise from all the other differences between active and inactive people."
);
$better_study = $better_studies[$i];

// --- Build model narrative ---
$sample_narrative = "We cannot conclude that $causal_claim just because the study found an association between $va and $vb. An association only tells us the two variables tend to appear together in the data. It does not tell us that one is causing the other, because there could be other factors at work that the study did not account for.<br><br><b>Alternative explanation 1 ($alt1_label):</b> $alt1_explain<br><br><b>Alternative explanation 2 ($alt2_label):</b> $alt2_explain<br><br><b>What would be needed to establish causation:</b> $better_study";

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
            <td style="text-align:center;"><b>Why Causation Fails</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain why the observed association does not prove that one variable causes the other, referencing the nature of the study.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Alternative Explanations</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Provide a first specific alternative explanation for the association, connecting it to both variables.</label></li>
                <li><label><input type="checkbox"> Provide a second specific alternative explanation for the association, connecting it to both variables.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Stronger Evidence</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Describe what kind of study design would be needed to better support a causal claim.</label></li>
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
            <td style="text-align:center;"><b>Why Causation Fails<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain why the association does not prove causation, referencing the study type.
                    <span class="ideal-ans">Target: "This is an observational study, meaning the researchers simply measured existing behavior without assigning anyone to groups or controlling other factors. Because of that, we only know that '.$va.' and '.$vb.' tend to go together. We cannot rule out the possibility that something else entirely is responsible for the pattern. Association alone never proves causation."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Alternative Explanations<br>(5 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>First alternative explanation (confounding variable).
                    <span class="ideal-ans">Target: "'.$alt1_explain.'"</span></li>
                <li>Second alternative explanation (reverse causation or different confounder).
                    <span class="ideal-ans">Target: "'.$alt2_explain.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Stronger Evidence<br>(2 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Describe the study design needed to support causation.
                    <span class="ideal-ans">Target: "'.$better_study.'"</span></li>
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
  Explain why we cannot conclude that '.$causal_claim.', and give at least two alternative explanations for this association.</p>
  <p>In your response, be sure to cover:</p>
  <ul>
    <li>Why the association found in this study does not prove that one variable causes the other.</li>
    <li>At least two specific alternative explanations that could account for the observed pattern, with a clear connection to both variables in each case.</li>
    <li>What type of study would be needed to better establish whether there is a real causal relationship.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton