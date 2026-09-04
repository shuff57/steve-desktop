// === NAME - DESCRIPTION: Grouping the Data Differently - a legal regrouping of the same 60 counts and what a different grouping can and cannot change ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices")

$questions[0] = array(
  "0 / 1-2 / 3-4 / 5-6 / 7+",
  "1-4 / 5-8, because every interval must hold exactly two counts",
  "0-3 / 2-6 / 4-7+, because overlapping rows show more of the data",
  "60 separate rows, because that is the only grouping a statistician may use"
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  "A different row layout answers different questions, but any cut that falls inside a row becomes invisible: the data never change, only what the table can show.",
  "Grouping changes the values themselves, so the grouped answers are approximations of the ungrouped ones.",
  "A finer grouping always loses information, because more rows means less data per row.",
  "Whatever grouping is chosen, the answer to every question must match the ungrouped table."
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
      <p><span class="term-label">Another legal grouping.</span> The lab groups 0-1, 2-3, 4-5, 6-7+. The same 60 counts could just as well be grouped as 0 / 1-2 / 3-4 / 5-6 / 7+, or 0-2 / 3-5 / 6-7+, as long as every count lands in exactly one interval and every interval is described.</p>
      <p><span class="term-label">The advantage of a different layout.</span> Each grouping answers the questions whose cuts land on its boundaries. A grouping with a boundary at 2, for example, can show the at-most-2 share that the 2-3 row hides.</p>
      <p><span class="term-label">The cost.</span> Any cut that falls inside a row becomes invisible: the data never change, only what the table can show. That is the real lesson of the lab: grouping never changes the data, but it can hide the very question you wanted to ask.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A class grouped its 60 movie counts into the intervals 0-1, 2-3, 4-5, and 6-7+.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 8px 0;"><b>a.</b> Which of these is a <b>different, legal</b> way to group the same 60 values? $answerbox[0]</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 8px 0;"><b>b.</b> In general, what are the advantages and costs of the way the data are grouped? $answerbox[1]</p>
  </div>
</div>


// === ANSWER ===

$solutionguide