// === NAME - DESCRIPTION: Compare the Empirical and Theoretical - the gaps between the pilot and the model ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's Discussion Question on the fixed numbers. Parts: (a) numfunc - the gap between the
// empirical median (129.15) and the theoretical median (129.42): 0.27
// (b) numfunc - the gap between the empirical IQR (3.70) and the theoretical IQR (3.40): 0.30
// (c) choices - does the pilot data give a close approximation to the theoretical model (yes).
// Invariant: (a) ~ 0.27, (b) ~ 0.30, (c) is constant on every seed.

$anstypes = array("numfunc", "numfunc", "choices")

$answer[0] = 0.27
$answer[1] = 0.30
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005

$questions[2] = array(
  "Yes: every pair lands within a few tenths of a second, and the empirical P(X > 130) = 0.4167 sits within 0.01 of the theoretical 0.4085, so the normal model is describing the process honestly",
  "No: the empirical median is below the theoretical one, so the model does not fit",
  "No: the empirical IQR is wider than the theoretical one, so the data is not normal",
  "Yes: the empirical and theoretical values are exactly equal, so the model is perfect"
)
$answer[2] = 0
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
  .term-label { font-weight:700; color:#1865f2; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><span class="term-label">Line the two sets of numbers up side by side.</span></p>
      <p>Empirical median 129.15 vs theoretical median 129.42: gap of 0.27 seconds. Empirical IQR 3.70 vs theoretical IQR 3.40: gap of 0.30 seconds. Empirical P(X > 130) = 0.4167 vs theoretical 0.4085: gap of 0.008.</p>
      <p><span class="term-label">Part (a): the median gap.</span> `129.42 - 129.15 = 0.27`</p>
      <p><span class="term-label">Part (b): the IQR gap.</span> `3.70 - 3.40 = 0.30`</p>
      <p><span class="term-label">Part (c): the verdict.</span> A gap of a tenth of a second is agreement; a gap of three seconds is not. Every pair here lands within a few tenths of a second, and the probability pair agrees to within a hundredth: so the pilot data gives a close approximation to the theoretical model. The specific comparison that convinces is the one you name, not a general impression.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A group ran a short pilot before the lap-time lab and sampled twelve lap times. The empirical summary: median 129.15, IQR 3.70, P(X > 130) = 0.4167. The theoretical model `N(129.42, 2.52)`: median 129.42, IQR 3.40, P(X > 130) = 0.4085.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The gap between the empirical median and the theoretical median.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The gap between the empirical IQR and the theoretical IQR.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Do the pilot data give a close approximation to the theoretical distribution?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
