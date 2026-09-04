// === NAME - DESCRIPTION: Cardiologist Recovery Key Terms - Match population, sample, parameter, statistic, variable, and data to their descriptions in a study of the mean recovery period after a heart attack ===
// === SET QUESTION TYPE TO: matching ===

// === COMMON CONTROL ===

// Randomize the specialist wording, the number of patients tracked, and the example recovery lengths.
$specs = array("cardiologist", "heart specialist", "cardiology clinic")
$spec = $specs[rand(0, count($specs)-1)]
$whose = "this " . $spec . "'s"

$n = rand(12, 45)

$d1 = rand(8, 20)
$d2 = $d1 + rand(3, 10)
$d3 = $d2 + rand(3, 10)

// Two or three example day-counts, collapsed to one scalar the prompt and the key both use.
$k = rand(2, 3)
if ($k == 2) {
  $daylist = "$d1 and $d2"
} else {
  $daylist = "$d1, $d2, and $d3"
}

// The six terms stay in teaching order, population before sample, parameter before statistic, 
// so the list reads as the definition sequence rather than a scramble.
$questions = array("Population", "Sample", "Parameter", "Statistic", "Variable", "Data")

// One description per term, in the same order, then two distractors. With six terms and exactly
// six descriptions the last answer is free by elimination; the extras take that away.
$answers = array(
  "all of $whose heart-attack patients",
  "the $n heart-attack patients whose recovery was actually tracked",
  "the mean recovery period for ALL of $whose heart-attack patients",
  "the mean recovery period for the $n patients in the SAMPLE",
  "the recovery period of ONE heart-attack patient",
  "the recorded recovery lengths, such as $daylist days",
  "all heart-attack patients in the country",
  "the number of patients whose recovery was tracked"
)

// Spelled out rather than left to the default, because the two distractors make the answer list
// longer than the question list and the mapping must stay pinned to the first six.
$matchlist = "0,1,2,3,4,5"

$questiontitle = "Key term"
$answertitle = "Description"
// Keep the terms in order and shuffle only the descriptions: the point is recognising which
// description fits, not remembering where it sat last time.
$noshuffle = "questions"

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
  .term-row { margin:0.6em 0; }
  .term-label { font-weight:700; color:#1865f2; }
  .sol-note { margin-top:0.9em; padding:0.6em 0.75em; background:#e8f0fe; border-radius:8px; font-size:15px; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><b>Step 1: Name the group.</b> The study is about ' . $whose . ' own heart-attack patients, so all of those patients are the population. The ' . $n . ' whose recovery was actually tracked are the sample.</p>
      <p><b>Step 2: Sort the two mean recovery periods.</b> The mean taken over all of the patients is a parameter; the same mean computed from the tracked group is a statistic. The two read almost identically: only the group they point at differs.</p>
      <p><b>Step 3: Name the measurement and its values.</b> What is measured on one patient is the variable; the numbers written down are the data.</p>
      <div class="term-row"><span class="term-label">Population:</span> all of ' . $whose . ' heart-attack patients</div>
      <div class="term-row"><span class="term-label">Sample:</span> the ' . $n . ' patients whose recovery was actually tracked</div>
      <div class="term-row"><span class="term-label">Parameter:</span> the mean recovery period for <em>all</em> of ' . $whose . ' heart-attack patients</div>
      <div class="term-row"><span class="term-label">Statistic:</span> the mean recovery period for the ' . $n . ' patients in the sample</div>
      <div class="term-row"><span class="term-label">Variable:</span> <em>X</em> = the recovery period of one heart-attack patient</div>
      <div class="term-row"><span class="term-label">Data:</span> the recorded recovery lengths, such as ' . $daylist . ' days</div>
      <div class="sol-note">Two of the descriptions are not used. <b>All heart-attack patients in the country</b> is a far wider group than the one ' . $spec . ' is studying. And <b>the number</b> of patients tracked is a count: the study is about a mean recovery period, not how many people were watched.</div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A <b>$spec</b> wants to know the <b>mean recovery period</b> of their own patients who have had heart attacks. The recovery of $n of those patients is tracked; the recorded lengths include $daylist days.</p>
    <p style="margin:12px 0 0 0;">Match each key term to the description that fits this study. <b>Two descriptions are not used.</b></p>
  </div>
</div>


// === ANSWER ===

$solutionguide
