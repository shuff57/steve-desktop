// === NAME - DESCRIPTION: 500 Values Instead of 50 - Which column changes and the new expected count ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's Discussion Question. Parts: (a) choices - which column changes when you generate
// 500 values instead of 50 (the empirical one; the theoretical never looks at the generator)
// (b) numfunc - expected count per bar with 8 bars and 500 values = 500 * 0.125 = 62.5.
// Invariant: (a) constant; (b) = 62.5 on every seed.

$anstypes = array("choices", "numfunc")

$answer[0] = 0
$answer[1] = 62.5
$abstolerance[1] = 0.005

$questions[0] = array(
  "The empirical column changes and the theoretical column does not",
  "The theoretical column changes and the empirical column does not",
  "Both columns change",
  "Neither column changes"
)
$noshuffle[0] = "all"

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
      <p><span class="term-label">Part (a): which column changes.</span> The empirical column holds statistics of numbers the generator actually produced; the theoretical column holds values computed from `a = 0` and `b = 1`. The theoretical column never looks at the generator, so producing ten times as many values cannot move a single entry in it. The empirical column changes: with 500 values, every statistic is built from ten times as much information, so each one settles closer to its theoretical partner.</p>
      <p><span class="term-label">Part (b): the new expected count.</span> With eight bars the expected count per bar rises from 6.25 to `500 * 0.125 = 62.5`, so a bar that runs a few values over or under is now a small fraction of its height instead of a large one. The histogram flattens out and starts looking like the rectangle the density function actually is.</p>
      <p>This is the whole meaning of a continuous distribution\'s parameters, and it is the lab\'s real payoff. The 0.5 was never a promise about your 50 values: it was a promise about what the average of generated values does as you keep generating. Fifty values cannot show you that. Five hundred can start to.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Suppose the number of values generated was 500, not 50.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which of your two columns, empirical or theoretical, changes?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> With eight bars, how many values would you expect in each bar?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
