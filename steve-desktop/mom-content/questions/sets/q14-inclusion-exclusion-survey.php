// === NAME - DESCRIPTION: Inclusion-Exclusion Survey - Given |U|, |A|, |B|, |neither|, find |A∪B|, |A∩B|, |A only| ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number")

// Randomize counts FIRST so $total is available for the scene template interpolation
$only_a = rand(12, 22)
$only_b = rand(10, 20)
$both   = rand(6, 14)
$neither = rand(8, 18)
$total = $only_a + $only_b + $both + $neither

$sizeA = $only_a + $both
$sizeB = $only_b + $both
$union = $only_a + $only_b + $both

$answer[0] = $union
$answer[1] = $both
$answer[2] = $only_a

// Randomize a context (scene strings use $total: must be after the count block above)
$ctx_label_a = array("Math", "Python", "Fiction")
$ctx_label_b = array("Art", "SQL", "Mystery")
$ctx_subj   = array("students", "employees", "library books")
$ctx_verb   = array("take", "know", "are tagged")
$ctx_unit   = array("students", "employees", "books")
$ctx_scene  = array(
  "A high school surveyed its $total seniors about which electives they take this year.",
  "A tech company surveyed its $total engineers about which languages they use on the job.",
  "A librarian surveyed $total recent acquisitions and recorded which genre tags each book carries."
)

$ci = rand(0, 2)
$labelA = $ctx_label_a[$ci]
$labelB = $ctx_label_b[$ci]
$subj   = $ctx_subj[$ci]
$verb   = $ctx_verb[$ci]
$unit   = $ctx_unit[$ci]
$scene  = $ctx_scene[$ci]

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
      <p>Let A = "'.$labelA.'" and B = "'.$labelB.'". We are given a total of '.$total.', '.$sizeA.' in A, '.$sizeB.' in B, and '.$neither.' in neither.</p>
      <p><b>(a) Number in A &cup; B.</b> Everyone is either in A or B, or in neither. So the number in A &cup; B = total &minus; neither = '.$total.' &minus; '.$neither.' = <b>'.$union.'</b>.</p>
      <p><b>(b) Number in A &cap; B.</b> Inclusion-Exclusion: number in A &cup; B = number in A + number in B &minus; number in A &cap; B, so number in A &cap; B = '.$sizeA.' + '.$sizeB.' &minus; '.$union.' = <b>'.$both.'</b>.</p>
      <p><b>(c) A only.</b> The "A only" region is the count for A with the overlap removed: '.$sizeA.' &minus; '.$both.' = <b>'.$only_a.'</b>.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Answers: A &cup; B = '.$union.', A &cap; B = '.$both.', A only = '.$only_a.'.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 0.6em 0;">$scene</p>
    <ul style="margin:0; padding-left:1.4em;">
      <li>$sizeA $verb $labelA</li>
      <li>$sizeB $verb $labelB</li>
      <li>$neither $verb neither $labelA nor $labelB</li>
    </ul>
    <p style="margin:0.6em 0 0 0;">Let <b>A = "$labelA"</b> and <b>B = "$labelB"</b>.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> How many $unit are in <b>A &cup; B</b> (in at least one of the two categories)?
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> How many $unit are in <b>A &cap; B</b> (in both categories)?
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> How many $unit are in $labelA but <b>not</b> in $labelB ($labelA only)?
    <div style="margin-top:12px;text-align:center;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
