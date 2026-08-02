// === NAME - DESCRIPTION: Approval Poll Key Terms (Proportion) - Match population, sample, parameter, statistic, variable, and data to their descriptions in a study measuring the proportion of voters who approve of a politician ===
// === SET QUESTION TYPE TO: matching ===

// === COMMON CONTROL ===

// Randomize the office; the region word is paired with the office so the scenario stays coherent.
$offices = array("mayor", "senator", "city council member", "governor")
$regions = array("city", "state", "district", "state")
$ci = rand(0, 3)
$office = $offices[$ci]
$region = $regions[$ci]

// Randomize the poll size.
$sizes = array(400, 500, 600, 750, 800, 1000)
$n = $sizes[rand(0, count($sizes)-1)]

// The six terms stay in teaching order — population before sample, parameter before statistic —
// so the list reads as the definition sequence rather than a scramble.
$questions = array("Population", "Sample", "Parameter", "Statistic", "Variable", "Data")

// One description per term, in the same order, then two distractors. With six terms and exactly
// six descriptions the last answer is free by elimination; the extras take that away.
$answers = array(
  "all voters in the {$region}",
  "the {$n} voters who were polled",
  "the proportion of ALL {$region} voters who think the {$office} is doing a good job",
  "the proportion of the {$n} POLLED voters who think the {$office} is doing a good job",
  "whether one voter thinks the {$office} is doing a good job",
  "yes, no",
  "the number of polled voters who think the {$office} is doing a good job",
  "all {$region} residents old enough to vote, whether registered or not"
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
      <p><b>Step 1 &mdash; Name the group the study is about.</b> The ' . $office . ' cares about the voters in the ' . $region . ', so that whole group is the population and the polled voters are the sample.</p>
      <p><b>Step 2 &mdash; Sort the two &ldquo;proportion&rdquo; numbers.</b> The proportion for <em>all</em> voters is a population number (parameter); the proportion computed from the ' . $n . ' polled voters is a sample number (statistic). Notice the two phrases are identical except for which group they point at.</p>
      <p><b>Step 3 &mdash; Name the measurement and its values.</b> What is recorded from one voter is the variable; the recorded responses are the data.</p>
      <div class="term-row"><span class="term-label">Population:</span> all voters in the ' . $region . '</div>
      <div class="term-row"><span class="term-label">Sample:</span> the ' . $n . ' voters who were polled</div>
      <div class="term-row"><span class="term-label">Parameter:</span> the proportion of <em>all</em> ' . $region . ' voters who think the ' . $office . ' is doing a good job</div>
      <div class="term-row"><span class="term-label">Statistic:</span> the proportion of the ' . $n . ' <em>polled</em> voters who think the ' . $office . ' is doing a good job</div>
      <div class="term-row"><span class="term-label">Variable:</span> <em>X</em> = whether one voter thinks the ' . $office . ' is doing a good job</div>
      <div class="term-row"><span class="term-label">Data:</span> yes, no</div>
      <div class="sol-note">Two of the descriptions are not used. <b>The number</b> of voters who approve is a count, not a proportion &mdash; a statistic has to be the proportion to answer this question. And <b>everyone old enough to vote</b> is a wider group than the voters this poll is about.</div>
      <div class="sol-note">This study measures a <b>proportion</b>, not a mean. The variable is <b>categorical</b> &mdash; each voter answers yes or no &mdash; so averaging the responses would make no sense. Compare this with a study of the mean amount of time clients exercise, where the variable is numerical.</div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A <b>$office</b> wants to know the <b>proportion</b> of voters in the $region who think the $office is doing a good job. A polling firm randomly selects <b>$n</b> voters from the $region and asks each one whether the $office is doing a good job.</p>
    <p style="margin:12px 0 0 0;">Match each key term to the description that fits this study. <b>Two descriptions are not used.</b></p>
  </div>
</div>


// === ANSWER ===

$solutionguide
