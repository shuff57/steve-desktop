// === NAME - DESCRIPTION: Approval Poll Key Terms (Proportion) - Identify population, sample, parameter, statistic, variable, and data in a study measuring the proportion of voters who approve of a politician ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("string", "string", "string", "string", "string", "string")

// Randomize the office; the region word is paired with the office so the scenario stays coherent.
$offices = array("mayor", "senator", "city council member", "governor")
$regions = array("city", "state", "district", "state")
$ci = rand(0, 3)
$office = $offices[$ci]
$region = $regions[$ci]

// Randomize the poll size.
$sizes = array(400, 500, 600, 750, 800, 1000)
$n = $sizes[rand(0, count($sizes)-1)]

// Short phrases for each key term. Alternatives are separated by *or* (special_or flag)
// so that phrases containing the ordinary word "or" still grade correctly.
$answer[0] = "all voters in the {$region}*or*all of the voters in the {$region}*or*all {$region} voters*or*every voter in the {$region}*or*all registered voters in the {$region}*or*all voters in the {$office}'s {$region}"
$answer[1] = "the {$n} voters polled*or*the {$n} voters who were polled*or*the {$n} polled voters*or*the {$n} voters in the poll*or*the polled voters*or*the voters who were polled*or*the voters in the poll*or*a group of {$n} voters"
$answer[2] = "the proportion of all {$region} voters who approve*or*the proportion of all voters in the {$region} who approve*or*the proportion of all {$region} voters who think the {$office} is doing a good job*or*the proportion of all voters in the {$region} who think the {$office} is doing a good job*or*the population proportion"
$answer[3] = "the proportion of the polled voters who approve*or*the proportion of the {$n} polled voters who approve*or*the proportion of the voters in the sample who approve*or*the proportion of the polled voters who think the {$office} is doing a good job*or*the proportion of the {$n} polled voters who think the {$office} is doing a good job*or*the sample proportion"
$answer[4] = "whether one voter thinks the {$office} is doing a good job*or*whether a voter thinks the {$office} is doing a good job*or*whether one voter approves of the {$office}*or*whether a voter approves of the {$office}*or*whether one voter thinks the {$office} is doing a good job or not"
$answer[5] = "yes, no*or*yes no*or*yes/no*or*yes or no*or*yes and no*or*no, yes"

// Clean single phrasings shown when the student asks to see the answer.
$showanswer[0] = "all voters in the {$region}"
$showanswer[1] = "the {$n} voters who were polled"
$showanswer[2] = "the proportion of all {$region} voters who think the {$office} is doing a good job"
$showanswer[3] = "the proportion of the {$n} polled voters who think the {$office} is doing a good job"
$showanswer[4] = "whether one voter thinks the {$office} is doing a good job"
$showanswer[5] = "yes, no"

// Common flags for all six string parts: case-insensitive, ignore stray outer spaces,
// and use *or* (not the word "or") to separate the accepted phrasings.
for ($i=0..5) {
  $strflags[$i] = "ignore_case,trim_whitespace,special_or"
  $ansprompt[$i] = ""
  $answerboxsize[$i] = 50
}

$answeights = array(.1667, .1667, .1667, .1667, .1667, .1667)

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
      <div class="sol-note">This study measures a <b>proportion</b>, not a mean. The variable is <b>categorical</b> &mdash; each voter answers yes or no &mdash; so averaging the responses would make no sense. Compare this with a study of the mean amount of time clients exercise, where the variable is numerical.</div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A <b>$office</b> wants to know the <b>proportion</b> of voters in the $region who think the $office is doing a good job. A polling firm randomly selects <b>$n</b> voters from the $region and asks each one whether the $office is doing a good job.</p>
    <p style="margin:12px 0 0 0;">For this study, identify each key term with a <b>short phrase</b>. Type the phrase only &mdash; not a full sentence, and no ending period.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> <b>Population:</b> $answerbox[0]</p>
    <p><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> <b>Sample:</b> $answerbox[1]</p>
    <p><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> <b>Parameter:</b> $answerbox[2]</p>
    <p><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> <b>Statistic:</b> $answerbox[3]</p>
    <p><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">e.</span> <b>Variable:</b> `X` = $answerbox[4]</p>
    <p><span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">f.</span> <b>Data:</b> $answerbox[5]</p>
  </div>
</div>


// === ANSWER ===

$solutionguide
