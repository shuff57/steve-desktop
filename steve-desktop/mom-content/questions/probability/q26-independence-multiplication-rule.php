// === NAME - DESCRIPTION: Independence Multiplication Rule - P(A and B), P(A or B), P(neither) for two independent events ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc", "numfunc", "numfunc")

// Four independent-trial scenarios. Each gives two events with clean decimal
// probabilities. Events are structurally independent (separate machines, separate
// trials, separate locations) so P(A and B) = P(A)*P(B) applies directly.
//
// Scenario 0: Assembly line: two machines each have a defect rate
//   A = Machine 1 produces a defect  P(A)=0.3
//   B = Machine 2 produces a defect  P(B)=0.2
//   P(A and B) = 0.06   P(A or B) = 0.44   P(neither) = 0.56
//
// Scenario 1: Two students taking separate exams independently
//   A = Student 1 passes her exam  P(A)=0.75
//   B = Student 2 passes his exam  P(B)=0.8
//   P(A and B) = 0.60   P(A or B) = 0.95   P(neither) = 0.05
//
// Scenario 2: Two independent weather stations (different cities)
//   A = City A gets rain on Saturday  P(A)=0.4
//   B = City B gets rain on Saturday  P(B)=0.25
//   P(A and B) = 0.10   P(A or B) = 0.55   P(neither) = 0.45
//
// Scenario 3: Two independent components in a backup system
//   A = Component 1 fails this month  P(A)=0.05
//   B = Component 2 fails this month  P(B)=0.1
//   P(A and B) = 0.005  P(A or B) = 0.145  P(neither) = 0.855

// --- Scenario text fragments ---
$ctx_intros = array(
  "A factory assembly line has two machines that operate independently. Quality inspectors track defects on each machine separately.",
  "Two students are taking completely separate exams on the same day, with no shared questions or shared preparation. Their results are independent.",
  "Two cities are far enough apart that their Saturday weather is independent. Each city's forecast is based on its own local conditions.",
  "A backup power system uses two components that operate independently. Engineers estimate a monthly failure probability for each component based on separate test data."
)
$ctx_A_labels = array(
  "Machine 1 produces a defective item",
  "Student 1 passes her exam",
  "City A receives rain on Saturday",
  "Component 1 fails this month"
)
$ctx_B_labels = array(
  "Machine 2 produces a defective item",
  "Student 2 passes his exam",
  "City B receives rain on Saturday",
  "Component 2 fails this month"
)

// --- Probabilities (clean decimals, stored as strings for display) ---
$pA_vals = array(0.3,  0.75, 0.4,  0.05)
$pB_vals = array(0.2,  0.8,  0.25, 0.1)

$pA_strs = array("0.30", "0.75", "0.40", "0.05")
$pB_strs = array("0.20", "0.80", "0.25", "0.10")

// --- Precomputed answers ---
// P(A and B) = P(A)*P(B)
$pAB_vals   = array(0.06, 0.60, 0.10, 0.005)
// P(A or B)  = P(A) + P(B) - P(A and B)
$pAorB_vals = array(0.44, 0.95, 0.55, 0.145)
// P(neither) = 1 - P(A or B)
$pNei_vals  = array(0.56, 0.05, 0.45, 0.855)

$picked = jointrandfrom($ctx_intros, $ctx_A_labels, $ctx_B_labels, $pA_vals, $pB_vals, $pA_strs, $pB_strs, $pAB_vals, $pAorB_vals, $pNei_vals)
$ctx_intro  = $picked[0]
$labelA     = $picked[1]
$labelB     = $picked[2]
$pA         = $picked[3]
$pB         = $picked[4]
$pA_str     = $picked[5]
$pB_str     = $picked[6]
$pAB        = $picked[7]
$pAorB      = $picked[8]
$pNei       = $picked[9]

$answer[0] = $pAB
$answer[1] = $pAorB
$answer[2] = $pNei
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005
$abstolerance[2] = 0.005

// Display strings for solution guide
$pA_show   = $pA_str
$pB_show   = $pB_str
$pAB_show  = $pAB
$pAorB_show = $pAorB
$pNei_show = $pNei

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
      <p>Since A and B are independent, the <b>Multiplication Rule</b> applies: P(A &cap; B) = P(A) &middot; P(B).</p>
      <p><b>(a) P(A &cap; B): both events occur:</b></p>
      <p>P(A &cap; B) = P(A) &middot; P(B) = '.$pA_show.' &times; '.$pB_show.'</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        P(A &cap; B) = <b>'.$pAB_show.'</b>
      </div>
      <p><b>(b) P(A &cup; B): at least one event occurs (Inclusion-Exclusion Rule):</b></p>
      <p>P(A &cup; B) = P(A) + P(B) &minus; P(A &cap; B) = '.$pA_show.' + '.$pB_show.' &minus; '.$pAB_show.'</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        P(A &cup; B) = <b>'.$pAorB_show.'</b>
      </div>
      <p><b>(c) P(neither A nor B): complement of the union:</b></p>
      <p>P(neither) = 1 &minus; P(A &cup; B) = 1 &minus; '.$pAorB_show.'</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        P(neither) = <b>'.$pNei_show.'</b>
      </div>
      <p style="font-size:14px; color:#374151;">Tip: you can also compute P(neither) directly as (1&minus;P(A)) &middot; (1&minus;P(B)), which equals (1&minus;'.$pA_show.') &middot; (1&minus;'.$pB_show.'). Both give the same result because independence extends to the complements.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">$ctx_intro</p>
    <p style="margin:0 0 8px 0;"><b>Assume events A and B are independent.</b></p>
    <div style="border-radius:12px; overflow:hidden; box-shadow:0 4px 6px -1px rgba(0,0,0,0.08),0 2px 4px -2px rgba(0,0,0,0.05); border:1px solid #e5e7eb; display:inline-block;">
      <table style="border-collapse:collapse;">
        <tr><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 18px; font-weight:600; text-align:left;">Event</th><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 18px; font-weight:600;">Probability</th></tr>
        <tr><td style="border:1px solid #dee1e3; padding:8px 18px;">A: $labelA</td><td style="border:1px solid #dee1e3; padding:8px 18px; text-align:center;">P(A) = $pA_str</td></tr>
        <tr><td style="border:1px solid #dee1e3; padding:8px 18px;">B: $labelB</td><td style="border:1px solid #dee1e3; padding:8px 18px; text-align:center;">P(B) = $pB_str</td></tr>
      </table>
    </div>
    <p style="margin:10px 0 0 0;">Enter probabilities as decimals rounded to 4 places (or exact fractions).</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find <b>P(A &cap; B)</b>: the probability that <i>both</i> events occur.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find <b>P(A &cup; B)</b>: the probability that <i>at least one</i> of the events occurs.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find <b>P(neither A nor B)</b>: the probability that <i>neither</i> event occurs.
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>


// === ANSWER ===

$solutionguide
