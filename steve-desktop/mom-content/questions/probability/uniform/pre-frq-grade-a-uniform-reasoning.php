// === NAME - DESCRIPTION: Pre-FRQ Grade a Uniform Reasoning - a flat-density scenario with its grading checklist, where the student grades four sample responses against the rubric instead of writing one ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Authored-first pre-FRQ for 4.5 (no uniform FRQ exists to mirror; this defines the scenario and
// rubric a later FRQ should match). See reference/pre-frq-template.md.
//
// Categories: State the Two Properties (2 pts) / Compute the Probability (5 pts) /
// Interpret in Context (5 pts) = 12.
//
// The dropped category is STATE THE TWO PROPERTIES. A student can compute the area and interpret
// it without ever checking the curve is a legitimate density, so nothing catches a density that
// dips negative or fails to total 1. Distinct from every earlier dropped category: 2.3 Percentile,
// 2.4 Contextual Interpretation, 2.5 Outlier Impact, 2.6 Further Investigation, 2.7 Practical
// Conclusion, 3.1 Sample Space, 3.2 Distinguish the Two, 3.3 Second Factor, 3.4 State the
// Direction, 3.5 Draw the Structure, 4.1 State the Values, 4.2 Verify the Sum, 4.3 Name the
// Parameters -- none is this.
//
// CATEGORY PURITY: $sProps states the two properties and nothing else; $sCompute shows only the
// area arithmetic (base, height, product) and ends at the number; $sInterpret reads the number as
// the probability of the event and states P(x = c) = 0 without re-deriving the calculation. Each
// sentence earns exactly one rubric line and no other.
$anstypes = array("choices", "multans", "choices")

$bs = array(20, 10, 25, 40)
$cs = array(4, 2, 5, 0)
$ds = array(12, 6, 15, 30)
$probs = array(0.4, 0.4, 0.4, 0.75)

$i = rand(0, 3)
$b = $bs[$i]
$c = $cs[$i]
$d = $ds[$i]
$prob = $probs[$i]

$scenarios = array(
  "A city bus line states that the wait for the next bus is equally likely to be any length from 0 to 20 minutes. Let `x` be the wait (in minutes) for the next bus.",
  "A coffee shop's drive-through promises that the time to the next car is equally likely to be any length from 0 to 10 minutes. Let `x` be the time (in minutes) until the next car arrives.",
  "An elevator company states that the wait for the next car is equally likely to be any length from 0 to 25 seconds. Let `x` be the wait (in seconds) for the next elevator.",
  "A traffic light cycles every 40 seconds, and the wait for the light to change is equally likely to be any length from 0 to 40 seconds. Let `x` be the wait (in seconds) until the light changes."
)

$sProps = "The density is never negative on its interval, and the total area under the curve is exactly 1."

$sCompute = array(
  "The strip from 4 to 12 minutes is base 8 with height 1/20, so `P(4 < x < 12) = (8)(1/20) = 0.4`.",
  "The strip from 2 to 6 minutes is base 4 with height 1/10, so `P(2 < x < 6) = (4)(1/10) = 0.4`.",
  "The strip from 5 to 15 seconds is base 10 with height 1/25, so `P(5 < x < 15) = (10)(1/25) = 0.4`.",
  "The strip from 0 to 30 seconds is base 30 with height 1/40, so `P(0 < x < 30) = (30)(1/40) = 0.75`."
)

$sInterpret = array(
  "So there is a 0.4 probability that the wait falls between 4 and 12 minutes, and the probability of waiting exactly one particular time is 0.",
  "So there is a 0.4 probability that the wait falls between 2 and 6 minutes, and the probability of waiting exactly one particular time is 0.",
  "So there is a 0.4 probability that the wait falls between 5 and 15 seconds, and the probability of waiting exactly one particular time is 0.",
  "So there is a 0.75 probability that the wait falls between 0 and 30 seconds, and the probability of waiting exactly one particular time is 0."
)

$sP = $sProps
$sC = $sCompute[$i]
$sI = $sInterpret[$i]

$rFull    = $sP . ' ' . $sC . ' ' . $sI
$rDropped = $sC . ' ' . $sI
$rNoInterp = $sP . ' ' . $sC
$rMinimal = $sP . ' That is the whole picture for a uniform wait.'

$pos = rand(0, 3)
$rA = $rFull
$rB = $rDropped
$rC = $rNoInterp
$rD = $rMinimal
if ($pos == 1) {
  $rA = $rDropped
  $rB = $rFull
}
if ($pos == 2) {
  $rA = $rNoInterp
  $rC = $rFull
}
if ($pos == 3) {
  $rA = $rMinimal
  $rD = $rFull
}

$questions[0] = array("Response A", "Response B", "Response C", "Response D")
$answer[0] = $pos

$droppedLabel = "B"
if ($pos == 1) { $droppedLabel = "A" }

$questions[1] = array(
  "State the Two Properties (2 pts)",
  "Compute the Probability (5 pts)",
  "Interpret in Context (5 pts)"
)
$answer[1] = "0"
$scoremethod[1] = "allornothing"

$questions[2] = array(
  "No. The area can be computed correctly from a curve that is not a legitimate density, so nothing catches a broken setup unless the two properties are checked on their own.",
  "Yes. Once the probability is computed correctly, the two properties are implied by the arithmetic, so there is nothing separate to award.",
  "No, but only because the area computation is the hard part.",
  "Yes, as long as the computed probability is between 0 and 1, the setup is fine."
)
$answer[2] = 0

$css = '
<style>
  .qscope45 .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
  .qscope45 .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
  .qscope45 .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; }
  .qscope45 .rubric-container summary::-webkit-details-marker { display:none; }
  .qscope45 .rubric-content { padding:0.75em; background:#fafafa; }
  .qscope45 .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
  .qscope45 .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
  .qscope45 .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; }
  .qscope45 .row-colored { background:#fff9ea; }
  .qscope45 .resp { border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin:10px 0; background:#fff; }
  .qscope45 .resp b { color:#1865f2; }
</style>'

$rubric = $css . '
<div class="qscope45">
<div class="rubric-container">
  <details open>
    <summary>Grading Checklist</summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr><th>Category</th><th>Requirement</th></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>State the Two Properties<br>(2 pts)</b></td>
            <td>State that the density is never negative and that the total area under the curve is exactly 1.</td></tr>
          <tr><td style="text-align:center;"><b>Compute the Probability<br>(5 pts)</b></td>
            <td>Find `P(c < x < d)` as base times height (equivalently `(d - c)/(b - a)`), with the arithmetic shown.</td></tr>
          <tr class="row-colored"><td style="text-align:center;"><b>Interpret in Context<br>(5 pts)</b></td>
            <td>Read the area as the probability of the event in the situation, and state that `P(x = c) = 0`.</td></tr>
        </tbody>
      </table>
    </div>
  </details>
</div>
</div>'

$responses = '
<div class="qscope45">
  <div class="resp"><b>Response A.</b> ' . $rA . '</div>
  <div class="resp"><b>Response B.</b> ' . $rB . '</div>
  <div class="resp"><b>Response C.</b> ' . $rC . '</div>
  <div class="resp"><b>Response D.</b> ' . $rD . '</div>
</div>'

$fullLabel = "A"
if ($pos == 1) { $fullLabel = "B" }
if ($pos == 2) { $fullLabel = "C" }
if ($pos == 3) { $fullLabel = "D" }

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
      <p><span class="term-label">Part (a) &mdash; only one response earns all three.</span> <b>Response ' . $fullLabel . '</b> states the two properties, shows the base-times-height arithmetic, and interprets the area as the probability of the event with `P(x = c) = 0`. Each of the other three misses a whole category.</p>
      <p><span class="term-label">The setup, and the answer.</span> Here `f(x) = 1/(' . $b . ')` on `[0, ' . $b . ']`, and the strip runs from `x = ' . $c . '` to `x = ' . $d . '`. ' . $sC . ' So ' . $sI . '</p>
      <p><span class="term-label">Part (b) &mdash; grading Response ' . $droppedLabel . ' line by line.</span></p>
      <ul>
        <li><b>State the Two Properties &mdash; NOT earned.</b> The response computes and interprets, but nowhere does it check the density is never negative and totals 1, so a broken setup would sail through.</li>
        <li><b>Compute the Probability &mdash; earned.</b> The base-times-height arithmetic is present and correct.</li>
        <li><b>Interpret in Context &mdash; earned.</b> The area is read as the probability of the wait falling in the window, and `P(x = c) = 0` is stated.</li>
      </ul>
      <p><span class="term-label">Part (c) &mdash; why the two properties are their own category.</span> The area of a strip is arithmetic on whatever curve you draw, and a curve that dips negative or totals more than 1 is not a density at all. The properties are the definition\'s own check, and nothing in the computation or the interpretation forces them &mdash; they have to be verified on their own.</p>
      <p><span class="term-label">Why you are grading instead of writing.</span> On the test this scenario comes with a blank box and this same checklist. The two properties are the category most often skipped, because once the numbers are in the formula the setup feels like busywork.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;"><b>The scenario.</b> ' . $scenarios[$i] . '</p>
    <p style="margin:8px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;"><b>The task students were given:</b> State the two properties of a probability density, compute `P(' . $c . ' < x < ' . $d . ')` as base times height, and interpret the area as the probability of the event, noting what `P(x = c)` is.</p>
  </div>
  ' . $rubric . '
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 4px 0;"><b>Four students answered.</b></p>
    ' . $responses . '
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which response earns <b>full credit</b> on all three categories? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which categories does <b>Response $droppedLabel</b> <b>fail</b> to earn? Select every one it fails to earn, and none that it does. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Is computing the probability correctly enough on its own to cover stating the two properties? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
