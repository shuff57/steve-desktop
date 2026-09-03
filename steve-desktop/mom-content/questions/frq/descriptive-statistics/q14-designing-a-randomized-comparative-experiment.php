// === NAME - DESCRIPTION: Designing a Randomized Comparative Experiment - Students build an experiment rather than critique one: treatment and control groups, explanatory and response variables, the random assignment and what it protects against, blinding, and one ethical rule ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

/* Chapter 1 individual test, slot 10. Written 2026-09-02 alongside q13, for the same reason: every
   Chapter-1 FRQ in the bank is already on the group or the practice test.

   The gap this fills: the two 1.4-adjacent FRQs both ask the student to JUDGE someone else's design.
   q10 critiques a sampling plan, q12 reads a published study. Nothing in the folder asks a student
   to PRODUCE a design, and nothing touches research ethics at all, even though 1.4 is titled
   "Experimental Design and Ethics" and the bank carries four auto-graded ethics questions.

   Rubric line targeted: students name randomization without saying what it buys. "Assign them
   randomly" is a procedure; "so the two groups are alike on everything except the treatment,
   including things nobody thought to measure" is the reason, and it is the half that gets dropped.

   All three scenarios are placebo-capable on purpose, so part (c) has real content in every draw: a design where blinding is impossible would make the blinding bullet unanswerable on that seed.

   Deliberately at the easy end (presets.ind). No numbers, no calculation; everything asked for is
   vocabulary from 1.4 applied to a one-paragraph scenario.

   Format note: five-marker house format, as q13. See that file's note. */

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$i = rand(0, 2);

$questions_asked = array(
  "Does a daily vitamin D supplement reduce the number of colds an adult catches over one winter?",
  "Does a 200 mg caffeine tablet improve reaction time in adults?",
  "Does a menthol pain-relief cream reduce muscle soreness after exercise?"
);
$recruits = array(
  "600 adult volunteers who have agreed to take part in a four-month winter study",
  "240 adult volunteers who have agreed to take part in a one-afternoon study",
  "180 adult volunteers who have agreed to take part in a two-week study"
);
$treatments = array(
  "takes one vitamin D supplement every day for four months",
  "takes one 200 mg caffeine tablet one hour before the reaction-time test",
  "applies the menthol cream to the sore muscle twice a day for two weeks"
);
$controls = array(
  "takes an identical-looking pill every day for four months that contains no vitamin D",
  "takes an identical-looking tablet one hour before the test that contains no caffeine",
  "applies an identical-smelling cream twice a day for two weeks that contains no menthol"
);
$explanatories = array(
  "whether the subject received vitamin D or the placebo pill",
  "whether the subject received caffeine or the placebo tablet",
  "whether the subject received the menthol cream or the placebo cream"
);
$responses = array(
  "the number of colds the subject catches over the four months",
  "the subject reaction time, in milliseconds, on the test",
  "the subject reported soreness rating at the end of two weeks"
);
$counts = array("600", "240", "180");
$halves = array("300", "120", "90");
$lurkings = array(
  "how much sleep a subject gets, how many young children they live with, and whether they had a flu shot",
  "how much a subject already drinks coffee, how well they slept, and how fast their reflexes are to begin with",
  "how hard a subject trains, how old they are, and how quickly they normally recover"
);
$measurers = array(
  "the nurse who records each reported cold",
  "the technician who runs the reaction-time test",
  "the assistant who records the soreness rating"
);

$question_asked = $questions_asked[$i];
$recruit = $recruits[$i];
$treatment = $treatments[$i];
$control = $controls[$i];
$explanatory = $explanatories[$i];
$response = $responses[$i];
$count = $counts[$i];
$half = $halves[$i];
$lurking = $lurkings[$i];
$measurer = $measurers[$i];

/* ---------- Narrative pieces for the model answer ---------- */
$r_groups = "the treatment group $treatment, and the control group $control. The explanatory variable is $explanatory, and the response variable is $response";
$r_random = "number the $count volunteers, then use a random process, such as a random number generator or drawing numbered slips, to pick $half of them for the treatment group, with the remaining $half forming the control group. What the randomization buys is that the two groups end up alike, on average, on EVERYTHING except the treatment: $lurking, and every other trait nobody thought to measure. Without it, any difference at the end could be caused by those traits instead of by the treatment";
$r_blinding = "in a single-blind design the subjects do not know which group they are in, which is why the control group gets an identical placebo rather than nothing at all. A subject who knows they got the real thing may report feeling better regardless. In a double-blind design $measurer does not know either, which stops the person recording the data from nudging it toward the result they expect";
$r_ethics = "every volunteer must give informed consent: they have to be told what the study involves, what the risks are, that they may be placed in the placebo group, and that they may withdraw at any time without penalty";

$sample_narrative = "To answer this question with an experiment, <b>$r_groups</b>. To assign subjects, <b>$r_random</b>. For blinding, <b>$r_blinding</b>. Finally, <b>$r_ethics</b>.";

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
      <p style="margin:0 0 0.5em 0;"><b>Grading Criteria</b> -- ensure your design covers these points:</p>
      <table class="rubric-table">
        <tbody>
          <tr>
            <th class="col-header">Category</th>
            <th class="col-check">Requirement</th>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Groups and Variables<br>(5 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Describe what the treatment group does and what the control group does.</label></li>
                <li><label><input type="checkbox"> Name the explanatory variable and the response variable.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Random Assignment<br>(5 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Describe a specific procedure for assigning the volunteers to the two groups at random.</label></li>
                <li><label><input type="checkbox"> Say what the randomization protects the study against. Saying only "assign them randomly" does not earn this point.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;" class="col-cat-bot"><b>Blinding and Ethics<br>(4 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain what blinding is here and what it protects against. Say who is blinded in a single-blind design and who else is blinded in a double-blind design.</label></li>
                <li><label><input type="checkbox"> Name one ethical requirement this study has to meet, and say why it matters.</label></li>
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
            <td style="text-align:center;"><b>Groups and Variables<br>(5 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Treatment group and control group.
                    <span class="ideal-ans">Target: "The treatment group '.$treatment.'. The control group '.$control.'." Accept a control that receives a placebo; do NOT give full credit to a control group that receives nothing, since part (c) then has nothing to blind.</span></li>
                <li>Explanatory and response variables.
                    <span class="ideal-ans">Target: "Explanatory: '.$explanatory.'. Response: '.$response.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Random Assignment<br>(5 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>A specific randomization procedure.
                    <span class="ideal-ans">Target: "Number the '.$count.' volunteers and use a random number generator, or draw numbered slips, to select '.$half.' for the treatment group; the other '.$half.' are the control group." Any genuinely random mechanism is fine. Letting subjects choose, or splitting by arrival order, is not.</span></li>
                <li>What randomization buys.
                    <span class="ideal-ans">Target: "It makes the two groups alike on average on everything except the treatment, including '.$lurking.' and traits nobody measured, so a difference at the end can be credited to the treatment." THIS IS THE POINT STUDENTS DROP. "Assign them randomly so it is fair" names the procedure and not the reason; it earns the first bullet only.</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;" class="col-cat-bot"><b>Blinding and Ethics<br>(4 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Blinding, single and double.
                    <span class="ideal-ans">Target: "Single-blind: the subjects do not know which group they are in, which is why the control gets an identical placebo. Otherwise a subject who knows may report feeling better anyway. Double-blind: '.$measurer.' does not know either, so the person recording the data cannot nudge it toward the expected result."</span></li>
                <li>One ethical requirement.
                    <span class="ideal-ans">Target: "Informed consent. Volunteers must be told what the study involves, what the risks are, that they may be assigned to the placebo group, and that they may withdraw at any time without penalty." Accept any correct requirement (confidentiality of records, review board approval, no withholding of a known effective treatment) as long as the WHY is given.</span></li>
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
  <p>A research team wants to answer this question: <b>'.$question_asked.'</b></p>
  <p>They have recruited '.$recruit.'. They have not yet decided how to run the study.</p>
  <p><b>Essay Prompt:</b><br>
  Design a randomized comparative experiment that would answer this question, and explain the choices you made.</p>
  <p>In your response, be sure to address:</p>
  <ul>
    <li>What the treatment group does and what the control group does, and which variables are the explanatory and the response variables.</li>
    <li>Exactly how you would assign the volunteers to the two groups, and what the randomization protects the study against.</li>
    <li>What blinding means here, who is blinded in a single-blind design, who else is blinded in a double-blind design, and what it protects against.</li>
    <li>One ethical requirement this study has to meet, and why it matters.</li>
  </ul>
  '.$rubricbutton.'
</div>';

// === QUESTION TEXT ===
$questiontext
$answerbox[0]

// === ANSWER ===
$rubricanswerbutton
