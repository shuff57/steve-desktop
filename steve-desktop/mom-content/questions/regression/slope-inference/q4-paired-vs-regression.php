// === NAME - DESCRIPTION: Pick the Right Test - Paired t vs. regression vs. two-sample t for pre/post data ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices")

// Scenario randomization: pre/post on the SAME subjects, so the right call is paired t
$ctx_subj   = array("30 college athletes",     "24 algebra students",      "40 hypertension patients",       "28 office workers")
$ctx_meas   = array("vertical jump (inches)",  "exam score",               "systolic blood pressure (mmHg)", "typing speed (wpm)")
$ctx_treat  = array("a 6-week strength program", "a 4-week study workshop", "a new medication",              "a new keyboard layout")
$ctx_window = array("before training and again after training",
                    "before the workshop and again after the workshop",
                    "before the medication and again 8 weeks later",
                    "before the switch and again 3 weeks later")
$picked_ctx = jointrandfrom($ctx_subj, $ctx_meas, $ctx_treat, $ctx_window)
$subj   = $picked_ctx[0]
$meas   = $picked_ctx[1]
$treat  = $picked_ctx[2]
$window = $picked_ctx[3]

// Part a: which method?
$choices[0] = array(
  "Paired t-test on the differences (post `-` pre).",
  "Two-sample t-test comparing the mean pre score to the mean post score as if they came from independent groups.",
  "Linear regression of the post measurement on the pre measurement, with an indicator variable for the treatment.",
  "Chi-square test of independence."
)
$noshuffle[0] = "all"
$answer[0] = 0

// Part b: why?
$choices[1] = array(
  "Each subject contributes a pre value and a post value, so the two measurements are linked. Working with the within-subject differences removes the person-to-person variability.",
  "The pre and post groups are independent samples of the same size.",
  "We want to predict the post score from the pre score, so a regression slope is the natural quantity.",
  "The data are categorical, so we should test for an association between two categorical variables."
)
$noshuffle[1] = "all"
$answer[1] = 0

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
      <p><b>(a) Which test.</b> The same ' . $subj . ' are measured twice, before and after ' . $treat . '. Two values per subject means the data are <b>paired</b>. The right call is a paired t-test on the differences `d_i = post_i - pre_i`.</p>
      <p><b>(b) Why not regression or two-sample t.</b> A two-sample t treats the two groups as independent, but here the same person sits in both columns. A regression with an indicator could work in some study designs, but it answers a different question (predicting one measurement from another) and ignores the natural pairing. The paired t-test removes person-to-person variability by working with each subject\'s own change.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Decision rule: when each subject contributes BOTH measurements (pre/post, twin pairs, matched pairs), use a paired t-test on the within-subject differences.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A researcher records <b>$meas</b> for <b>$subj</b> $window. The goal is to test whether $treat changed the mean $meas.</p>
    <p style="margin:0;">Each subject contributes two measurements (one pre, one post).</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which procedure best fits this design? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Pick the best reason for that choice. $answerbox[1]
  </div>
</div>


// === ANSWER ===

$solutionguide
