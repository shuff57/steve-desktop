// === NAME - DESCRIPTION: Informed Consent Violation Identification - identify which specific informed-consent violation (coercion of a captive population, a minor unable to legally consent, or a withheld material fact with an oversold benefit) is present in each of three randomized research scenarios ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices", "choices")

$institutions = array("Ridgeline Correctional Facility", "Elm Street Detention Center", "Cedar Valley State Prison", "Harborview Correctional Institution", "Twin Oaks County Jail")
$ci = rand(0, 4)
$institution = $institutions[$ci]

$incentives = array("credit toward a good-behavior review", "extra visitation time with family", "priority placement in the job-training program", "a reduction in required community-service hours", "additional commissary credit")
$ii = rand(0, 4)
$incentive = $incentives[$ii]

$drugs = array("a pediatric asthma inhaler", "a childrens seasonal-allergy medication", "a new pediatric ear-infection antibiotic", "an experimental childhood eczema cream", "a pediatric migraine medication")
$di = rand(0, 4)
$drug = $drugs[$di]

$products = array("a new anti-inflammatory drug", "an experimental blood-pressure medication", "a new migraine treatment", "an experimental type-2-diabetes drug", "a new arthritis medication")
$pi = rand(0, 4)
$product = $products[$pi]

$benefits = array("highly promising", "a major breakthrough", "extremely effective in early testing", "the most effective option currently available")
$bi = rand(0, 3)
$benefit = $benefits[$bi]

$violation_options = array("Coercion of a captive population &mdash; declining is not truly free when a benefit is offered for saying yes", "Inability of a minor to legally give consent on their own behalf", "A material fact about the study was withheld while its benefit was oversold", "A breach of participant privacy &mdash; identifying data collected from subjects was not protected")

$choices[0] = $violation_options
$choices[1] = $violation_options
$choices[2] = $violation_options
$answer[0] = 0
$answer[1] = 1
$answer[2] = 2

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
      <p><b>Part a &mdash; ' . $incentive . ' at ' . $institution . '.</b> Consent has to be freely given, and it is not free when refusing costs the participant something they value. Tying ' . $incentive . ' to study participation makes it feel compulsory. That is ' . $violation_options[0] . ', and incarcerated people are treated as a protected population for exactly this reason.</p>
      <p><b>Part b &mdash; a trial of ' . $drug . ' in children.</b> Children are not legally able to weigh risks and consent to research on their own. Consent must come from a parent or guardian, with the child also asked for age-appropriate assent. This is ' . $violation_options[1] . '.</p>
      <p><b>Part c &mdash; told ' . $product . ' is ' . $benefit . ', not told about the other arms.</b> This fails informed consent on both words: it is not informed, because a material fact (that most participants receive a placebo or a traditional treatment) was withheld, and the framing oversells the benefit while the risk of receiving no active treatment goes unmentioned. This is ' . $violation_options[2] . '.</p>
      <p><b>Answer:</b> (a) ' . $violation_options[0] . '; (b) ' . $violation_options[1] . '; (c) ' . $violation_options[2] . '.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <p>Each research scenario below has a specific problem with how informed consent was obtained. For each one, select the informed-consent violation that is actually present.</p>

  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span>
    People incarcerated at $institution are offered $incentive in return for participating in a research study.
    <p style="margin:10px 0 4px;">Which informed-consent violation is present? $answerbox[0]</p>
  </div>

  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span>
    A research team is designing a clinical trial to test $drug in young children.
    <p style="margin:10px 0 4px;">Which informed-consent violation is present? $answerbox[1]</p>
  </div>

  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span>
    Participants are told that $product being tested is $benefit, but they are not told that only a small portion of participants will receive the new drug &mdash; the rest will receive a placebo or a traditional treatment.
    <p style="margin:10px 0 4px;">Which informed-consent violation is present? $answerbox[2]</p>
  </div>
</div>


// === ANSWER ===

$solutionguide
