// === NAME - DESCRIPTION: Perm or Comb Three Scenarios - Identify permutation vs combination across three random situations ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

$anstypes = array("choices", "choices", "choices")

/* ---------- 1. Scenario pools ---------- */
// 0 = Permutation (order matters), 1 = Combination (order does not matter)
// All scenarios are distinct from q15-perm-or-comb.php

$pa_prompts = array(
  "Assigning 3 distinct door prizes (1st, 2nd, 3rd) to 3 of 15 raffle ticket holders",
  "Ranking 4 job applicants in order of preference from a pool of 10 candidates",
  "Creating a 3-song set list (in order) for a DJ choosing from 12 tracks",
  "Filling labeled trophy spots (champion, runner-up, third place) at a bowling tournament with 8 teams",
  "Choosing 4 students from a homeroom of 20 to attend a field trip, with no roles assigned",
  "Selecting 5 flavors from a 10-flavor menu to serve at a party tasting, with no ranking",
  "Picking 3 novels from a display of 8 to borrow from the library",
  "Forming a 4-person carpool group from 11 coworkers, where anyone can drive"
)
$pa_correct = array(0, 0, 0, 0, 1, 1, 1, 1)

$pb_prompts = array(
  "Arranging 5 different flags in a row on a flagpole display from a collection of 9",
  "Determining the batting order for the first 4 hitters on a 9-player baseball roster",
  "Choosing a president, secretary, and treasurer from 8 club members",
  "Assigning 5 students to 5 distinct lab stations in a chemistry class",
  "Selecting 6 songs from a playlist of 15 to compile into a mix (the order the songs play does not matter)",
  "Picking 3 toppings for a custom pizza from a list of 9 available ingredients",
  "Choosing 4 books from a shelf of 12 to donate to a library sale",
  "Forming a 5-person study group from a class of 18 students"
)
$pb_correct = array(0, 0, 0, 0, 1, 1, 1, 1)

$pc_prompts = array(
  "Scheduling 3 of 7 job applicants for same-day interviews in a specific time-slot order",
  "Writing a 4-digit code for a combination lock where each digit position is distinct",
  "Selecting the lead, co-lead, and understudy roles from 10 actors auditioning",
  "Ranking the top 3 finishing teams (gold, silver, bronze) out of 6 teams in a quiz bowl",
  "Choosing 3 committee members from a board of 12, with no distinction between roles",
  "Picking 4 ingredients from a pantry of 10 to add to a recipe, in any combination",
  "Selecting 5 volunteers from a sign-up sheet of 14 to help at a fundraiser",
  "Choosing 2 representatives from a group of 9 to attend a meeting together"
)
$pc_correct = array(0, 0, 0, 0, 1, 1, 1, 1)

$picked_a = jointrandfrom($pa_prompts, $pa_correct)
$picked_b = jointrandfrom($pb_prompts, $pb_correct)
$picked_c = jointrandfrom($pc_prompts, $pc_correct)

$prompt_a = $picked_a[0]; $answer[0] = $picked_a[1]
$prompt_b = $picked_b[0]; $answer[1] = $picked_b[1]
$prompt_c = $picked_c[0]; $answer[2] = $picked_c[1]

$questions[0] = array("Permutation", "Combination")
$questions[1] = array("Permutation", "Combination")
$questions[2] = array("Permutation", "Combination")
$displayformat[0] = "select"
$displayformat[1] = "select"
$displayformat[2] = "select"
$noshuffle[0] = "all"
$noshuffle[1] = "all"
$noshuffle[2] = "all"

$labels = array("Permutation", "Combination")
$label_a = $labels[$answer[0]]
$label_b = $labels[$answer[1]]
$label_c = $labels[$answer[2]]

/* ---------- 2. Solution Guide ---------- */
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
      <p>The key question for each scenario: <i>"If I swap two of the chosen items, do I get the same result or a different one?"</i></p>
      <p><b>Same result</b> &rarr; combination (order does not matter). &nbsp; <b>Different result</b> &rarr; permutation (order matters).</p>
      <p><b>(a)</b> '.$label_a.': '.$prompt_a.'</p>
      <p><b>(b)</b> '.$label_b.': '.$prompt_b.'</p>
      <p><b>(c)</b> '.$label_c.': '.$prompt_c.'</p>
    </div>
  </details>
</div>'

/* ---------- 3. Question Text ---------- */
$questiontext = '
<div style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">For each scenario, decide whether the count involves a <b>permutation</b> (order matters) or a <b>combination</b> (order does not matter).</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> '.$prompt_a.'
    <div style="margin-top:12px; text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> '.$prompt_b.'
    <div style="margin-top:12px; text-align:center;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> '.$prompt_c.'
    <div style="margin-top:12px; text-align:center;">$answerbox[2]</div>
  </div>
</div>'

//question text

$questiontext

///

$solutionguide
