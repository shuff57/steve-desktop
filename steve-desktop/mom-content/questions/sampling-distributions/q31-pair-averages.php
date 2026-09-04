// === NAME - DESCRIPTION: The Pair Averages - (0.13 + 1.52)/2 = 0.825 and (0.45 + 0.72)/2 = 0.585 ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's pair-averaging step (Try It Now 6.3).
// Parts: (a) numfunc - the average of the pair ($0.13, $1.52): 0.825
// (b) numfunc - the average of the pair ($0.45, $0.72): 0.585.
// Invariant: ~ 0.825 and ~ 0.585 on every seed.

$anstypes = array("numfunc", "numfunc")

$answer[0] = 0.825
$answer[1] = 0.585
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
      <p><span class="term-label">Part (a): the first pair.</span> `(0.13 + 1.52)/2 = 1.65/2 = 0.825`.</p>
      <p><span class="term-label">Part (b): the second pair.</span> `(0.45 + 0.72)/2 = 1.17/2 = 0.585`.</p>
      <p><span class="term-label">Watch what happened to the $1.52.</span> On its own it sat well out in the right tail; paired with a thirteen-cent pocket it produced an average of $0.825, barely above the middle of the picture. Nothing was thrown away and nothing was corrected: the extreme value is still in there, it is just sharing its slot with a partner. That dilution is the whole mechanism of the lab.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Two of the pairs a class surveyed held $0.13 and $1.52, and $0.45 and $0.72. Find those two pair averages.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The average of the pair ($0.13, $1.52).
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The average of the pair ($0.45, $0.72).
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
