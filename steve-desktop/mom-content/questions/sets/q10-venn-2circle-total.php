// === NAME - DESCRIPTION: 2-Circle Venn - Find total when every person is in at least one set ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number")

// Randomize while keeping all regions positive and the numbers friendly.
$both = rand(8, 18)
$dog_only = rand(18, 40)
$cat_only = rand(12, 30)

$dog = $dog_only + $both
$cat = $cat_only + $both
$total = $dog_only + $cat_only + $both

$answer[0] = $total

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
      <p>Build the Venn diagram from the middle out.</p>
      <ul>
        <li>Both dog and cat: <b>'.$both.'</b></li>
        <li>Dog only: '.$dog.' &minus; '.$both.' = <b>'.$dog_only.'</b></li>
        <li>Cat only: '.$cat.' &minus; '.$both.' = <b>'.$cat_only.'</b></li>
      </ul>
      <p>Since every respondent owns at least one pet, the total is the sum of the three regions:</p>
      <p>Total = '.$dog_only.' + '.$cat_only.' + '.$both.' = <b>'.$total.'</b>.</p>
      <p>Equivalent inclusion-exclusion check: |D &cup; C| = |D| + |C| &minus; |D &cap; C| = '.$dog.' + '.$cat.' &minus; '.$both.' = '.$total.'.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Total surveyed: <b>'.$total.'</b>
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">In a survey, <b>$dog</b> people own a dog, <b>$cat</b> people own a cat, and <b>$both</b> own both. Every respondent owns at least one pet.</p>
    <p style="margin:0.5em 0 0 0;">How many people were surveyed in total?</p>
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
