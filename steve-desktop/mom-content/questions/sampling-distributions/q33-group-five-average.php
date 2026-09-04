// === NAME - DESCRIPTION: The Group-of-Five Average - (0.05 + 0.28 + 0.63 + 1.06 + 2.10)/5 = 0.824 ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's group-of-five step (Try It Now 6.4 step 1).
// Part: (a) numfunc - (0.05 + 0.28 + 0.63 + 1.06 + 2.10)/5 = 4.12/5 = 0.824.
// Invariant: ~ 0.824 on every seed.

$anstypes = array("numfunc")

$answer[0] = 0.824
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
      <p><span class="term-label">Average the five values.</span></p>
      <p>`(0.05 + 0.28 + 0.63 + 1.06 + 2.10)/5 = 4.12/5 = 0.824`</p>
      <p><span class="term-label">The extreme is still in there.</span> The $2.10 pocket sat far out on the right of the population picture. Averaged with four typical pockets it produced $0.824, right in the middle of the distribution of averages: nothing was thrown away and nothing was corrected; the extreme value is just sharing its slot with four partners.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">One group of five in the pocket-change lab held $0.05, $0.28, $0.63, $1.06, and $2.10. Find the group\'s average.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The group average.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
