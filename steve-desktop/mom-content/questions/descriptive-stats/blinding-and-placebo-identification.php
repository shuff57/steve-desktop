// === NAME - DESCRIPTION: Blinding and Placebo in a Clinical Trial - identify which feature of an experimental design is the blinding, and classify the trial as single- or double-blind ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices","choices")

// Randomize the fictitious drug name and the number of volunteers so each
// student sees a different version of the same scenario.
$treatments = array("Cardiozol","Nervalex","Mendaprin","Vitaqure","Thermacin","Sedaphex","Loxamine")
$ti = rand(0, count($treatments)-1)
$treatment = $treatments[$ti]

$conditions = array("migraines","chronic insomnia","seasonal allergies","mild hypertension","joint pain","generalized anxiety")
$ci = rand(0, count($conditions)-1)
$condition = $conditions[$ci]

$n = rand(4, 20) * 20

// Part a: which feature of the design IS the blinding.
// Index 1 (participants and nurses kept from knowing the assignment) is correct;
// random assignment, sample size, and post-trial comparison are all real features
// of the design but none of them is the blinding itself.
$questions[0] = array(
  "Volunteers were randomly assigned to receive $treatment or an identical-looking placebo.",
  "Neither the participants nor the nurses recording their symptoms are told who received $treatment and who received the placebo.",
  "The study enrolled $n volunteers.",
  "The researchers compared symptom scores between the two groups after the trial ended."
)
$answer[0] = 1

// Part b: single- vs double-blind. The statistician holding the master list still
// knows the assignment, so the trial is double- (not triple-) blind.
$questions[1] = array(
  "Single-blind &#8212; only the participants are kept from knowing which pill they received.",
  "Double-blind &#8212; both the participants and the nurses working directly with them are kept from knowing who received $treatment.",
  "Not blinded &#8212; everyone involved knows which participants received $treatment.",
  "Triple-blind &#8212; the participants, the nurses, and the statistician are all kept from knowing the assignment."
)
$answer[1] = 1

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
      <p><b>Step 1 &#8212; What blinding means.</b> Blinding (masking) means a person involved in the study does not know who is receiving the active treatment and who is receiving the placebo. It is not the random assignment itself, the sample size, or the final comparison of results &#8212; it is specifically the withholding of that knowledge.</p>
      <p><b>Step 2 &#8212; Find the blinding in this design.</b> Here, neither the participants nor the nurses recording symptoms are told who received $treatment and who received the placebo. That withholding of information is the blinding.</p>
      <p><b>Step 3 &#8212; Single- or double-blind?</b> A single-blind design keeps only the participants from knowing their assignment. A double-blind design keeps both the participants and the researchers working directly with them from knowing. Since both the volunteers and the nurses are kept in the dark here, this trial is double-blind. (The statistician also not knowing would make it a different design, but the statistician holds the master list, so only two groups &#8212; participants and nurses &#8212; are blinded.)</p>
      <p><b>Answer:</b> the blinding is that neither the participants nor the nurses know who received $treatment versus the placebo; the trial is double-blind.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A team of researchers is testing a new drug called $treatment for $condition. They recruit $n volunteers and randomly assign half of them to take $treatment and half to take an identical-looking placebo pill on the same schedule. Neither the participants nor the nurses who record each participant's symptoms are told which pill any individual received. Only the trial's statistician &#8212; who never meets or interacts with the participants &#8212; holds the master list of who was assigned to which pill, so the results can be unblinded once the study ends.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which feature of this trial's design is the blinding?
    <div style="margin-top:12px;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Based on that feature, is this trial single-blind or double-blind?
    <div style="margin-top:12px;">$answerbox[1]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
