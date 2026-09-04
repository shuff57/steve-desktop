// === NAME - DESCRIPTION: Which Rule, and Why That Factor - Choose between the addition and multiplication rules, carry it out, and justify which of two competing rates belongs in the product ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The FRQ for 3.3, written 2026-08-10 to match pre-frq-grade-a-joint-probability exactly: same
// three categories, same point split, same target sentences.
//
// The survey deliberately supplies THREE rates: P(A), P(B given A), and P(B) overall. Only one of
// the last two belongs in the product, and a student who cannot say which has a 50% chance of
// looking correct. The justification category is where that shows.

loadlibrary("stats")

$anstypes = array("essay")
$displayformat[0] = 'editornopaste'

$i = rand(0, 2)

$settings = array(
  "customers at a restaurant, ordering dessert and ordering coffee",
  "riders on a bus route, buying a day pass and transferring to a second line",
  "visitors to a museum, joining a guided tour and visiting the gift shop"
)
$setting = $settings[$i]

$eventA = array("ordered dessert", "bought a day pass", "joined a guided tour")
$eA = $eventA[$i]

$eventB = array("ordered coffee", "transferred to a second line", "visited the gift shop")
$eB = $eventB[$i]

$whoPlural = array("customers", "riders", "visitors")
$who = $whoPlural[$i]

$whoOneLabels = array("customer", "rider", "visitor")
$whoOne = $whoOneLabels[$i]

$a = 10 * rand(3, 6)
$b = 10 * rand(2, 5)
$bOverall = $b + 15
$jointPct = $a * $b / 100

$aDec = $a / 100
$bDec = $b / 100
$jointDec = $jointPct / 100

$r_rule = "This asks for the chance that both happened, an AND rather than an OR, so it calls for the multiplication rule."
$r_apply = "That works out as " . $aDec . " x " . $bDec . " = " . $jointDec . ", or " . $jointPct . "% of all " . $who . "."
$r_justify = "The second factor has to be the " . $b . "% measured among those who " . $eA . ", not the " . $bOverall . "% measured across everyone, because the two events are not independent: knowing that a " . $whoOne . " " . $eA . " changes the chance they also " . $eB . "."

$model = $r_rule . " " . $r_apply . " " . $r_justify

$css = '
<style>
  .frq4 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .frq4 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .frq4 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .frq4 .rubric-container summary::-webkit-details-marker { display:none; }
  .frq4 .rubric-content { padding:0.75em; background:#fafafa; }
  .frq4 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .frq4 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .frq4 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .frq4 .row-colored { background:#fff9ea; }
  .frq4 .facts { border-collapse:collapse; margin:10px 0; font-size:15px; }
  .frq4 .facts td, .frq4 .facts th { border:1px solid #d1d5db; padding:6px 14px; text-align:left; }
  .frq4 .facts th { background:#f0f4ff; }
</style>'

$rubric = $css . '
<div class="frq4">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist: 10 points</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Choose the Right Rule<br>(3 pts)</b></td>
            <td>Say which rule the question calls for, and what about the question decides it.</td></tr>
          <tr><td style="text-align:center;"><b>Apply It Correctly<br>(4 pts)</b></td>
            <td>Carry out the calculation and give the resulting probability.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Justify the Second Factor<br>(3 pts)</b></td>
            <td>Say which of the two rates for the second event belongs in the product, and why the other does not.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$factBlock = '
<div class="frq4">
  <table class="facts">
    <tr><th>What was measured</th><th>Rate</th></tr>
    <tr><td>' . $who . ' who ' . $eA . '</td><td>' . $a . '%</td></tr>
    <tr><td>among those who ' . $eA . ', the share who also ' . $eB . '</td><td>' . $b . '%</td></tr>
    <tr><td>' . $who . ' who ' . $eB . ', across everyone</td><td>' . $bOverall . '%</td></tr>
  </table>
</div>'

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-body { padding:0.75em; background:#fafafa; }
  .term-label { font-weight:700; color:#1865f2; }
  .ideal { display:block; background:#eef4ff; border-left:3px solid #1865f2; padding:8px 12px; margin:6px 0; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>Model Response and Marking Notes</summary>
    <div class="sol-body">
      <p><span class="term-label">A full-credit answer.</span></p>
      <span class="ideal">' . $model . '</span>
      <p><span class="term-label">The trap, stated plainly.</span> The ' . $bOverall . '% is there to be REJECTED. Using it gives `' . $aDec . ' xx ' . ($bOverall / 100) . '`, a plausible-looking number that answers nothing, and a response that uses it can still earn the first category.</p>
      <p><span class="term-label">Choose the Right Rule (3).</span> Needs the rule AND what decided it (the word "both", an AND). Naming the rule alone earns 1.</p>
      <p><span class="term-label">Apply It Correctly (4).</span> Full marks only if the product uses the conditional rate. A correct multiplication of the WRONG two numbers earns 1 for method.</p>
      <p><span class="term-label">Justify the Second Factor (3).</span> Award only if the response says which rate and why the other is wrong. "I used ' . $b . '%" without a reason earns 1. This is the category most often missing, because once two numbers are multiplied the work looks finished.</p>
      <p><span class="term-label">Marking shortcut.</span> Read the justification first. If it is absent, the arithmetic above it is unverifiable as reasoning even when the number is right: the student may simply have taken the nearer of the two rates.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 8px 0;"><b>The survey.</b> A study of $setting produced three figures.</p>
    $factBlock
    <p style="margin:8px 0 0 0;">Find the probability that one of the $who both $eA and $eB. Say which rule you used and why, carry it out, and explain which figures you used and which you did not.</p>
  </div>
  $rubric
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    $answerbox[0]
  </div>
</div>

// === ANSWER ===

$solutionguide
