// === NAME - DESCRIPTION: Observational Study vs Experiment - Classify a study, identify treatment vs control group, and decide whether causal conclusions are supported ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices", "choices")

// Scenario 0: Researchers randomly assign 200 patients to drug A or placebo and compare BP after 6 weeks → experiment; treatment = drug A; causal OK
// Scenario 1: Researchers ask 500 adults about exercise hours and resting heart rate from a survey → observational; no treatment; not causal
// Scenario 2: A new tutoring program is offered to all who sign up; researchers compare grades to non-sign-ups → observational (self-selection); no treatment assignment; not causal
// Scenario 3: Students are randomly split into "study with music" vs "study in silence" groups; test scores compared → experiment; treatment = music; causal OK
// Scenario 4: A nutritionist follows 300 people who already eat vegan and compares cholesterol to 300 omnivores → observational; not causal

$ctxs = array(
  "Researchers <b>randomly assigned</b> 200 hypertension patients to receive either a new drug or a placebo. After 6 weeks, average blood pressure was compared between the two groups.",
  "A health survey asked 500 adults to report their weekly <b>exercise hours and resting heart rate</b>. No instructions were given about how much to exercise.",
  "A tutoring program was offered campus-wide. The 120 students <b>who signed up</b> were compared to the 180 who did not, on end-of-semester grades.",
  "Volunteers were <b>randomly split</b> into two groups: one studied with background music, the other in silence. Both groups then took the same test, and scores were compared.",
  "A nutritionist followed 300 people who <b>already followed a vegan diet</b> for one year and compared their cholesterol to 300 omnivores. No diet was assigned by the researcher."
)
$study_type = array(1, 0, 0, 1, 0) // 0 observational, 1 experiment
$treat_known = array(0, 1, 1, 0, 1) // 0 = treatment imposed (Yes), 1 = no treatment imposed
$causal = array(0, 1, 1, 0, 1) // 0 causal supported (random assignment), 1 not causal

$why_type = array(
  "The researchers actively assigned treatments (drug vs placebo): this is an <b>experiment</b>.",
  "The researchers only observed and recorded existing behavior: this is an <b>observational study</b>.",
  "Subjects self-selected into the program: no random assignment of treatment, so it is an <b>observational study</b>.",
  "The researchers randomly assigned study conditions (music vs silence): this is an <b>experiment</b>.",
  "The researchers only observed diets that subjects had already chosen: this is an <b>observational study</b>."
)
$why_treat = array(
  "The new drug is the <b>treatment</b>; the placebo group is the control.",
  "There is no imposed treatment: only measurement.",
  "There is no random treatment assignment; sign-up vs non-sign-up is a self-selected grouping, not a treatment.",
  "Background music is the <b>treatment</b>; silence is the control.",
  "There is no imposed treatment: diet was already chosen by the subjects."
)
$why_causal = array(
  "Random assignment balances confounders, so cause-and-effect conclusions are supported.",
  "Without random assignment, lurking variables could explain any association; only an association can be claimed.",
  "Self-selection means the groups may differ in motivation, prior grades, etc.: these confounders block a causal claim.",
  "Random assignment balances confounders, so cause-and-effect conclusions are supported.",
  "Self-selected vegans may differ in many ways (exercise, income, age): confounders block a causal claim."
)

$picked = jointrandfrom($ctxs, $study_type, $treat_known, $causal, $why_type, $why_treat, $why_causal)
$ctx       = $picked[0]
$answer[0] = $picked[1]
$answer[1] = $picked[2]
$answer[2] = $picked[3]
$wt        = $picked[4]
$wtr       = $picked[5]
$wc        = $picked[6]

$choices[0] = array("Observational study", "Experiment")
$choices[1] = array("Yes: the researcher imposed a treatment", "No: the researcher only observed existing behavior")
$choices[2] = array("Yes: causal conclusions are supported", "No: only an association can be claimed")
$noshuffle[0] = "all"
$noshuffle[1] = "all"
$noshuffle[2] = "all"

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><b>Part a:</b> ' . $wt . '</p>
      <p><b>Part b:</b> ' . $wtr . '</p>
      <p><b>Part c:</b> ' . $wc . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Is this an observational study or an experiment? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Did the researcher impose a treatment on the subjects? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Can a cause-and-effect conclusion be drawn from this study? $answerbox[2]
  </div>
</div>


// === ANSWER ===

$solutionguide
