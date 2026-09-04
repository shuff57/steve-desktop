// === NAME - DESCRIPTION: Informed Consent Validity Requirements - Select every statement describing a genuine requirement of a valid informed-consent process, against misconceptions such as a signed form or verbal willingness being sufficient on its own ===
// === SET QUESTION TYPE TO: multans ===

// === COMMON CONTROL ===

$anstypes = "multans"

$contexts = array("a university lab testing a new sleep-tracking wearable", "a hospital testing a new blood-pressure medication", "a research center testing a new memory-training app", "a clinic testing a new smoking-cessation program", "a university lab testing a new caffeine-alertness supplement")
$ci = rand(0, 4)
$context = $contexts[$ci]

$populations = array("college students", "hospital outpatients", "retirees at a community center", "factory-line workers", "graduate students")
$pi = rand(0, 4)
$population = $populations[$pi]

$questions = array("Participation must be voluntary: no one may be coerced into joining, and a subject who does join must be free to withdraw at any time without losing any benefit they were already entitled to.", "The study team must clearly disclose the risks of participation before a subject agrees to take part.", "The study team must disclose the actual design of the study, including whether any participants will receive a placebo or an inactive treatment instead of the treatment being tested.", "A subject must have the legal and mental capacity to understand what is being asked; if a subject cannot legally consent for themselves, consent must come from a parent or other legally authorized representative instead.", "As long as a subject signs a consent form, the consent counts as informed, even if the risks of the study were never actually explained to them.", "A subject saying they are willing to participate is sufficient informed consent by itself, even if no one has told them what the study's risks are.", "Offering a subject a benefit tied to their standing, such as credit toward release, a grade, or a promotion, for agreeing to participate is acceptable as long as the subject ultimately says yes.", "Once a subject has given informed consent for one study, that consent automatically covers any later study the same research team decides to run, without needing new disclosure.")
$answers = "0,1,2,3"
$scoremethod = "allornothing"

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
  .term-label { font-weight:700; color:#1865f2; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><span class="term-label">Step 1: What informed consent actually requires.</span> A valid process has to be freely given (no coercion, and the right to walk away with no penalty), it has to disclose the real risks, it has to disclose the real design, including any placebo or inactive-treatment arm, and the person agreeing has to actually be capable of consenting, or have a legally authorized representative consent for them. Those are the four true statements.</p>
      <p><span class="term-label">Step 2: Why the others are not enough.</span> A signature on a form and a verbal &ldquo;yes&rdquo; are only evidence that consent happened: they are not a substitute for the disclosure that has to happen first. If the risks or the study design were never explained, neither a signed form nor a spoken agreement makes the consent informed. Tying a benefit like release credit, a grade, or a promotion to agreeing is coercion dressed up as a choice: refusing has to be free of cost, and it is not free here. And consent is specific to a study: it does not roll forward automatically to whatever the research team decides to run next.</p>
      <p><span class="term-label">Step 3: Applying it.</span> If $context recruits $population for its study, the team still has to clear all four bars above before anyone enrolls: getting a signature or a nod is not a shortcut past disclosure, capacity, or freely-given agreement.</p>
      <p><b>Answer:</b> The true requirements are voluntary participation with no coercion, disclosure of risks, disclosure of the study design including any placebo arm, and the subject&#8217;s capacity to consent (or a legally authorized representative&#8217;s consent). A signed form alone, verbal willingness alone, an incentive tied to standing, and consent rolling over to a future study are all common misconceptions, not valid substitutes.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Researchers at $context are recruiting $population to take part in the study. Before anyone enrolls, the research team has to decide what their informed-consent process must actually include.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;"><strong>Select all statements below that describe a genuine requirement for a valid informed-consent process.</strong> (Leave unselected any statement that describes a common misconception rather than an actual requirement.)</p>
    $answerbox
  </div>
</div>

// === ANSWER ===

$solutionguide
