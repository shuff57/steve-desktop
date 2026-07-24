// === NAME - DESCRIPTION: 2-Circle Venn - Find the number who are in neither set ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number")

// Randomize while keeping all regions positive.
$both = rand(8, 15)
$job_only = rand(15, 28)
$sport_only = rand(10, 22)
$neither = rand(5, 15)

$job = $job_only + $both
$sport = $sport_only + $both
$total = $job_only + $sport_only + $both + $neither

$answer[0] = $neither

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
      <p>Use inclusion-exclusion to find how many are in at least one group, then subtract from the total.</p>
      <p>|Job &cup; Sport| = |Job| + |Sport| &minus; |Job &cap; Sport| = '.$job.' + '.$sport.' &minus; '.$both.' = <b>'.($job + $sport - $both).'</b>.</p>
      <p>Neither = Total &minus; |Job &cup; Sport| = '.$total.' &minus; '.($job + $sport - $both).' = <b>'.$neither.'</b>.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Students doing neither: <b>'.$neither.'</b>
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Of <b>$total</b> students, <b>$job</b> have a part-time job, <b>$sport</b> play a sport, and <b>$both</b> do both.</p>
    <p style="margin:0.5em 0 0 0;">How many students do <b>neither</b>?</p>
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
