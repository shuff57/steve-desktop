// === NAME - DESCRIPTION: What the Sample Can and Cannot Tell You - Students name the population and sample of a well-run study, say which population value the sample number estimates, explain why the two differ under good sampling, and connect a named flaw to the estimate ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

/* Chapter 1 individual test, slot 9. Written 2026-09-02 because all six Chapter-1 FRQs already in
   the bank are consumed by the group and practice tests, and the six unused ones (q4, q5, q7, q8,
   q9, q11) are Chapter 2 topics.

   The gap this fills: nothing in questions/frq/descriptive-statistics/ covers SAMPLING VARIABILITY.
   q1 and q10 both hand the student a BADLY drawn sample and ask what is wrong with it. This one
   hands them a sample drawn WELL and asks why the number still is not the population value. That is
   the idea 1.1 and 1.2 are built on, and the one students skip straight past when every sampling
   question they have seen is a critique.

   Rubric line targeted: students name a limitation and stop there. Category 3 will not score
   without the direction the limitation pushes the estimate, or a statement of whose value the
   estimate now describes instead.

   Deliberately at the easy end. presets.ind says the FRQs exist to give an accessible essay path
   rather than to gate the grade, and every part is answerable from the scenario paragraph alone.

   Format note: this file uses the FIVE-marker house format from skills/mom-question, not the
   three-marker variant the other twelve FRQs in this folder use. _push/file-question.mjs parses
   five markers and cannot read the variant; the three MOM fields end up identical either way. */

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$i = rand(0, 2);

$intros = array(
  "A school district wants to know how much sleep its students get on a school night. The district enrolls 8,400 high school students. A researcher takes the district enrollment list, selects 300 students completely at random, and asks each of them how many hours they slept last night. The 300 students report a mean of 6.8 hours.",
  "A city parks department wants to know how many of its residents used a city park in the past month. The city has 52,000 residents. A researcher selects 400 residents completely at random from the city resident address list and asks each one whether they visited a city park in the past month. Of the 400, 46 percent say yes.",
  "A hospital network wants to know how long patients wait before being seen in its emergency room. The network recorded 12,000 emergency room visits last year. A researcher selects 250 of those visit records completely at random and reads the wait time off each one. The 250 records give a mean wait of 41 minutes."
);

$populations = array(
  "all 8,400 high school students enrolled in the district",
  "all 52,000 residents of the city",
  "all 12,000 emergency room visits at the network last year"
);
$samples = array(
  "the 300 students who were randomly selected and asked",
  "the 400 residents who were randomly selected from the address list and asked",
  "the 250 visit records that were randomly selected and read"
);
$variables = array(
  "the number of hours a student slept last night",
  "whether or not a resident visited a city park in the past month",
  "the wait time, in minutes, before a patient was seen"
);
$sample_values = array(
  "the sample mean of 6.8 hours",
  "the sample proportion of 46 percent",
  "the sample mean of 41 minutes"
);
$stat_names = array("sample mean", "sample proportion", "sample mean");
$parameters = array(
  "the mean number of hours slept by all 8,400 students in the district",
  "the proportion of all 52,000 residents who visited a city park in the past month",
  "the mean wait time across all 12,000 emergency room visits last year"
);

/* The named flaw. One per scenario, each a DIFFERENT kind of threat, so the model answer is not the
   same sentence three times: a timing problem, a leading-question problem, and a nonresponse problem.

   NONE of the three may be a problem with the DRAW itself. The question text asserts that every
   member of the population had an equal chance of being selected, so a flaw that contradicts that
   makes the prompt argue with itself. Caught live on 2026-09-02: seed 1 originally sampled from the
   voter registration roll and named that frame as the flaw, which is exactly the contradiction. */
$flaws = array(
  "the survey was run during the first week of final exams",
  "the question was worded as: You have visited one of our beautiful city parks recently, have you not?",
  "68 of the 250 selected records were incomplete and were quietly left out of the calculation"
);
$flaw_effects = array(
  "sleep during exam week is not typical sleep, so 6.8 hours is probably lower than the district mean on an ordinary school night. The estimate is pulled downward",
  "a leading question invites agreement, so more residents answer yes than actually visited a park, and the estimate of 46 percent is pushed upward. It measures how agreeable the question is as much as it measures park use",
  "an incomplete record is likeliest on a chaotic, overloaded shift, which is exactly when waits are longest, so dropping those records pulls the estimate of 41 minutes downward"
);

$intro = $intros[$i];
$population = $populations[$i];
$sample = $samples[$i];
$variable = $variables[$i];
$sample_value = $sample_values[$i];
$stat_name = $stat_names[$i];
$parameter = $parameters[$i];
$flaw = $flaws[$i];
$flaw_effect = $flaw_effects[$i];

/* ---------- Narrative pieces for the model answer ---------- */
$r_popsample = "the population is <b>$population</b>, and the sample is <b>$sample</b>. The variable recorded on each member of the sample is $variable";
$r_estimates = "$sample_value is a <b>statistic</b>, and it estimates the <b>parameter</b> $parameter. The two are not the same number: a different random draw of the same size would have landed on a slightly different value, because the sample contains only some members of the population and which ones you happen to get varies from draw to draw. That variation is <b>sampling variability</b>, and it is present even when the sampling is done perfectly";
$r_flaw = "if $flaw, then $flaw_effect. The point is that the flaw moves the answer in a knowable direction, or changes whose value is being estimated. Saying only that the study has a flaw stops short of that";

$sample_narrative = "In this study, <b>$r_popsample</b>. Next, <b>$r_estimates</b>. Finally, <b>$r_flaw</b>.";

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
      <p style="margin:0 0 0.5em 0;"><b>Grading Criteria</b> -- ensure your response covers these points:</p>
      <table class="rubric-table">
        <tbody>
          <tr>
            <th class="col-header">Category</th>
            <th class="col-check">Requirement</th>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Population and Sample<br>(5 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State what the population is in this study.</label></li>
                <li><label><input type="checkbox"> State what the sample is, and say what was recorded on each member of it.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>What the Number Estimates<br>(5 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Name the population value that this sample number is an estimate of.</label></li>
                <li><label><input type="checkbox"> Explain why the sample number is not exactly that population value, even though the sampling was done well.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;" class="col-cat-bot"><b>What Would Make It Untrustworthy<br>(4 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Name one specific thing that would make this estimate untrustworthy.</label></li>
                <li><label><input type="checkbox"> Say which way it would push the estimate, or whose value the estimate would then describe instead. Naming the problem alone does not earn this point.</label></li>
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
            <td style="text-align:center;"><b>Population and Sample<br>(5 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>State what the population is.
                    <span class="ideal-ans">Target: "The population is '.$population.'."</span></li>
                <li>State what the sample is, and what was recorded on each member.
                    <span class="ideal-ans">Target: "The sample is '.$sample.', and the variable recorded is '.$variable.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>What the Number Estimates<br>(5 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Name the population value being estimated.
                    <span class="ideal-ans">Target: "'.$sample_value.' is a statistic; it estimates '.$parameter.', which is a parameter."</span></li>
                <li>Explain why the two differ under good sampling.
                    <span class="ideal-ans">Target: "A different random sample of the same size would give a slightly different '.$stat_name.', because the sample holds only part of the population. That is sampling variability, and it is there even when nothing was done wrong." Accept any wording that says the value MOVES from sample to sample. Do NOT accept "the researcher made a mistake", since the scenario states the sampling was random.</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;" class="col-cat-bot"><b>What Would Make It Untrustworthy<br>(4 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Name one specific threat to the estimate.
                    <span class="ideal-ans">Target (any defensible one): "'.$flaw.'."</span></li>
                <li>Connect it to the estimate.
                    <span class="ideal-ans">Target: "'.$flaw_effect.'." THIS IS THE POINT STUDENTS DROP. A response that names a flaw and stops, such as "the sample might not be representative", earns the first bullet and not the second. Require a direction (too high / too low) or a restatement of whose value is now being estimated.</span></li>
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
  <p>'.$intro.'</p>
  <p>The sampling here was done well: every member of the population had an equal chance of being selected, and the sample is a good size.</p>
  <p><b>Essay Prompt:</b><br>
  Explain what this sample can tell the researcher about the population, and what it cannot.</p>
  <p>In your response, be sure to address:</p>
  <ul>
    <li>What the population is, what the sample is, and what was recorded on each member of the sample.</li>
    <li>Which population value '.$sample_value.' estimates, and why it is not exactly equal to that value even though the sampling was done well.</li>
    <li>One specific thing that would make this estimate untrustworthy, and which way it would push the estimate, or whose value the estimate would then describe instead.</li>
  </ul>
  '.$rubricbutton.'
</div>';

// === QUESTION TEXT ===
$questiontext
$answerbox[0]

// === ANSWER ===
$rubricanswerbutton
