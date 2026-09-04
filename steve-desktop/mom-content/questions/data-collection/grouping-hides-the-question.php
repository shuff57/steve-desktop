// === NAME - DESCRIPTION: Grouping Hides the Question - why a grouped table cannot answer more than two, and what the answer actually needs ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices")

$questions[0] = array(
  "Counts of 2 and 3 are locked together in one 2-3 row, so the grouped table cannot separate the 2s from the 3s.",
  "The grouped table has too few rows to answer any question about individual counts.",
  "The grouped table only shows relative frequencies, never cumulative ones.",
  "The 60 values were reordered when the table was built, so the counts no longer match the data."
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  "The share of the data at or below 2, which only the ungrouped table displays.",
  "The 0-1 row of the grouped table, because it already holds everything up to 1.",
  "A new random sample, so the question can be asked of fresh data.",
  "The total of 60, because the answer is always a share of the whole class."
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
      <p><span class="term-label">Why the grouped table stalls.</span> &quot;More than two&quot; needs the count of 2 separated from the count of 3, because 2 is inside and 3 is outside. In the grouped table, 2 and 3 are locked together in one 2-3 row, so the separation is impossible without going back to the raw data.</p>
      <p><span class="term-label">What the answer actually needs.</span> The share of the data at or below 2, the at-most-2 cumulative, which only the ungrouped table displays. Then &quot;more than two&quot; is 1 minus that share.</p>
      <p><span class="term-label">The lesson of the lab.</span> Grouping never changes the data, but it can hide the very question you wanted to ask.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A class grouped its 60 movie counts into the intervals 0-1, 2-3, 4-5, and 6-7+, and then tried to answer the question &quot;what percent of the data is more than two?&quot; from the grouped table alone.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 8px 0;"><b>a.</b> Why can the grouped table <b>not</b> answer this question? $answerbox[0]</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 8px 0;"><b>b.</b> What does the answer actually require? $answerbox[1]</p>
  </div>
</div>


// === ANSWER ===

$solutionguide