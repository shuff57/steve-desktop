// === NAME - DESCRIPTION: Subsets - Two-element set: count, then list each non-empty subset ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "ntuple", "ntuple", "ntuple")

// Numeric pairs so ntuple-set grading stays reliable.
$pairs_a = array(2, 3, 4, 5, 1)
$pairs_b = array(5, 7, 9, 8, 6)
$idx = rand(0, 4)
$a = $pairs_a[$idx]
$b = $pairs_b[$idx]
$set_display = $a . ", " . $b

$answer[0] = 4
$answer[1] = "{" . $a . "}"
$answer[2] = "{" . $b . "}"
$answer[3] = "{" . $a . ", " . $b . "}"
$displayformat[1] = "set"
$displayformat[2] = "set"
$displayformat[3] = "set"
$answerformat[1] = "anyorder"
$answerformat[2] = "anyorder"
$answerformat[3] = "anyorder"

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p>The set S = {'.$set_display.'} has 2 elements, so it has 2<sup>2</sup> = <b>4</b> subsets in total:</p>
      <ol>
        <li>&empty; (the empty set)</li>
        <li>{'.$a.'} &mdash; the subset containing only '.$a.'</li>
        <li>{'.$b.'} &mdash; the subset containing only '.$b.'</li>
        <li>{'.$a.', '.$b.'} &mdash; S itself, containing both elements</li>
      </ol>
      <p>Every subset is built by choosing, for each element of S, whether to include it or not. With 2 elements that gives 2 &times; 2 = 4 choices &mdash; one of which is "include nothing" (&empty;) and one of which is "include everything" (S).</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Total subsets: <b>4</b>. Non-empty subsets: <b>{'.$a.'}</b>, <b>{'.$b.'}</b>, <b>{'.$a.', '.$b.'}</b>.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Consider the set <b>S = { $set_display }</b>.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> How many subsets does <b>S</b> have?
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The subset containing only <b>$a</b>.
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> The subset containing only <b>$b</b>.
    <div style="margin-top:12px;text-align:center;">$answerbox[2]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> The subset that is <b>S</b> itself.
    <div style="margin-top:12px;text-align:center;">$answerbox[3]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
