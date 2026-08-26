// === NAME - DESCRIPTION: Why Switch Tables - which of the four questions forces a switch to the ungrouped table, and when staying is fine ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices")

$questions[0] = array(
  "More than two. Its complement needs the at-most-two share, which the grouped table cannot show because 2 and 3 share a row.",
  "At most three. Only the ungrouped table can show a cumulative that reaches 3.",
  "More than three. The grouped table cannot show any share above a boundary.",
  "None of the four. Any of them can be answered from either table."
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  "At most three, because the cut at 3 lands exactly on the boundary between the 2-3 and 4-5 groups, so both tables show the same cumulative.",
  "More than two, because the grouped table shows its whole answer without any complement.",
  "At most two, because the grouped 2-3 row gives the at-most-2 share directly.",
  "Only the questions about the grouped table itself, because the two tables are not about the same data."
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
      <p><span class="term-label">When the switch is forced.</span> The at-most-2 and more-than-2 questions cut through the middle of the 2-3 row, where the grouped table cannot separate 2 from 3. Their answers need the ungrouped table &mdash; so the student who started on the grouped table has to switch.</p>
      <p><span class="term-label">When staying is fine.</span> The cut at 3 lands exactly on the boundary between the 2-3 and 4-5 groups, so the at-most-3 and more-than-3 questions give the same answer from either table. No switch needed.</p>
      <p><span class="term-label">The lesson.</span> Which table answers a question is decided by where the question&rsquo;s cut lands, not by the data.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">The lab&rsquo;s four questions were: at most two, at most three, more than two, and more than three. A student started on the grouped table and found that one of them could not be answered there.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 8px 0;"><b>a.</b> Which question <b>forces the switch</b> to the ungrouped table? $answerbox[0]</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0 0 8px 0;"><b>b.</b> In which situation is staying on the grouped table fine? $answerbox[1]</p>
  </div>
</div>


// === ANSWER ===

$solutionguide