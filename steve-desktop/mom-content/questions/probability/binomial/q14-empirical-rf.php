// === NAME - DESCRIPTION: Empirical Relative Frequencies - RF(x = 2) and RF(x = 3) from the class data ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's Organize-the-Data step on the fixed 30-group dataset
// (2 groups at 0, 5 at 1, 9 at 2, 7 at 3, 4 at 4, 2 at 5, 1 at 6).
// Parts: (a) numfunc - RF(x = 2) = 9/30 = 0.3000; (b) numfunc - RF(x = 3) = 7/30 ~ 0.2333.
// Invariant: (a) = 0.3 and (b) ~ 0.2333 on every seed.

$anstypes = array("numfunc", "numfunc")

$answer[0] = 0.3
$answer[1] = 0.2333
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005

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
      <p><span class="term-label">The dataset.</span> 30 groups each ran the experiment once: 2 groups got 0 diamonds, 5 got 1, 9 got 2, 7 got 3, 4 got 4, 2 got 5, 1 got 6. The counts sum to 30.</p>
      <p><span class="term-label">Part (a).</span> `RF(x = 2) = 9/30 = 0.3000`</p>
      <p><span class="term-label">Part (b).</span> `RF(x = 3) = 7/30 ~= 0.2333`</p>
      <p>Relative frequency is an empirical quantity: it is measured, not derived, and it changes every time you rerun the experiment. The RF column must sum to 1.0000; if it does not, you have either lost a repetition or divided by the wrong total.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A class of 30 groups each runs the card experiment once and records: 2 groups got 0 diamonds, 5 got 1, 9 got 2, 7 got 3, 4 got 4, 2 got 5, and 1 got 6. Find the relative frequencies.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> `RF(x = 2) =`
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> `RF(x = 3) =`
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
