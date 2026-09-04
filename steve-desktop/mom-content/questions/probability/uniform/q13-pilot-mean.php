// === NAME - DESCRIPTION: The Pilot Mean - x-bar of the 12-value dataset ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's Collect-the-Data step. x-bar of the fixed pilot dataset = 5.9758/12 ~ 0.4980.
// Invariant: ~ 0.4980 on every seed.

$anstypes = array("numfunc")

$answer[0] = 0.4980
$abstolerance[0] = 0.005

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
      <p><span class="term-label">Add the twelve values.</span></p>
      <p>`0.0412 + 0.1187 + 0.2043 + 0.2765 + 0.3391 + 0.4508 + 0.5624 + 0.6130 + 0.7042 + 0.8219 + 0.8873 + 0.9564 = 5.9758`</p>
      <p><span class="term-label">Divide by the count.</span></p>
      <p>`x-bar = 5.9758/12 ~= 0.4980`</p>
      <p>The theoretical mean is 0.5000, so this sample landed within 0.002 of it. Do not read that as "twelve values is plenty": it is one run, and a different twelve values could easily have come out at 0.44 or 0.56. What the mean does reliably is sit near the center; how near depends on how many values you averaged.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Using the same twelve pilot values (0.0412, 0.1187, 0.2043, 0.2765, 0.3391, 0.4508, 0.5624, 0.6130, 0.7042, 0.8219, 0.8873, 0.9564), find the sample mean `x-bar`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> `x-bar =`
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
