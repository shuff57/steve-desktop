// === NAME - DESCRIPTION: Compare the Pilot to the Theory - The IQR and the two quartile gaps ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's Compare-the-Data step on the fixed pilot dataset (Q1 = 0.2404, Q3 = 0.7631).
// Parts: (a) numfunc - pilot IQR = 0.7631 - 0.2404 = 0.5227
// (b) numfunc - Q1 gap = 0.25 - 0.2404 = 0.0096
// (c) numfunc - Q3 gap = 0.7631 - 0.75 = 0.0131
// Invariant: ~ 0.5227, ~ 0.0096, ~ 0.0131 on every seed.

$anstypes = array("numfunc", "numfunc", "numfunc")

$answer[0] = 0.5227
$answer[1] = 0.0096
$answer[2] = 0.0131
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005
$abstolerance[2] = 0.005

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
      <p><span class="term-label">Part (a) &mdash; the pilot IQR.</span> `IQR = Q3 - Q1 = 0.7631 - 0.2404 = 0.5227`, against a theoretical 0.5000 — the sample\'s middle half is 0.0227 wider than the model predicts.</p>
      <p><span class="term-label">Part (b) &mdash; the Q1 gap.</span> The pilot\'s `Q1 = 0.2404` against a theoretical 0.2500, so the sample\'s first quartile is `0.25 - 0.2404 = 0.0096` lower than expected.</p>
      <p><span class="term-label">Part (c) &mdash; the Q3 gap.</span> The pilot\'s `Q3 = 0.7631` against a theoretical 0.7500, so the sample\'s third quartile is `0.7631 - 0.75 = 0.0131` higher than expected.</p>
      <p>Read those together and the picture is coherent: both quartiles drifted outward, so of course the distance between them grew. Reporting the IQR gap as a separate surprise, when it is just the sum of the two quartile gaps you already reported, is the most common way this part of the lab gets written up wrong.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">The pilot group\'s twelve values gave `Q1 = 0.2404` and `Q3 = 0.7631`. Compare them to the theoretical quartiles of `U(0, 1)`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The pilot\'s IQR.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> How far the pilot\'s Q1 sits below the theoretical 0.25.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> How far the pilot\'s Q3 sits above the theoretical 0.75.
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
