// === NAME - DESCRIPTION: The z Critical Value - z_(alpha/2) for a stated confidence level, and the area to feed invNorm ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A confidence level CL. Parts: (a) numfunc - z_(alpha/2) (1.645, 1.96, 2.326, 2.576)
// (b) choices - the area to feed invNorm (the area to the LEFT, 1 - alpha/2).
// Invariant: (a) is the precomputed constant for the stated CL, (b) is constant on every seed.

$anstypes = array("numfunc", "choices")

$contexts = array(
  array("90%", 0.90, 1.645),
  array("95%", 0.95, 1.96),
  array("98%", 0.98, 2.326),
  array("99%", 0.99, 2.576)
)
// [clLabel, cl, z]

$i = rand(0, 3)
$clLabel = $contexts[$i][0]
$cl = $contexts[$i][1]
$z = $contexts[$i][2]

$alpha = 1 - $cl
$half = $alpha / 2
$leftArea = 1 - $half
$leftAreaRounded = round($leftArea, 3)

$answer[0] = $z
$abstolerance[0] = 0.005

$questions[1] = array(
  "The area to the LEFT, " . round($leftArea, 3) . " &mdash; invNorm wants the area below the value, not the tail above it",
  "The tail area, " . round($half, 3) . " &mdash; the area to the right of the critical value"
)
$answer[1] = 0
$noshuffle[1] = "all"

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
      <p><span class="term-label">Part (a) &mdash; the critical value.</span> The confidence level is the area in the middle. Since `CL = ' . $cl . '`, the leftover area is `alpha = 1 - CL = ' . round($alpha, 2) . '`, split equally between the two tails, so each tail holds `alpha/2 = ' . round($half, 3) . '`. The critical value `z_(alpha/2)` is the z-score with that much area to its right:</p>
      <p>`z_(alpha/2) = z_(' . round($half, 3) . ') = ' . $z . '`</p>
      <p><span class="term-label">Part (b) &mdash; what you feed invNorm.</span> invNorm wants the area BELOW the value, not the tail above it. The area to the left is `1 - alpha/2 = $leftAreaRounded`, so you run invNorm(`$leftAreaRounded`, 0, 1). The last two arguments stay 0 and 1 because this is the standard normal curve.</p>
      <p>The three confidence levels you will meet most often have critical values worth recognising on sight: `z = 1.645` for 90%, `z = 1.96` for 95%, and `z = 2.326` for 98%. Every one of them comes from the same two moves &mdash; split alpha in half, then ask for the z-score with that much area above it.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Find the critical value `z_(alpha/2)` for a $clLabel confidence level.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> `z_(alpha/2) =`
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which area must you feed to invNorm?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
