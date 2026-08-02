// === NAME - DESCRIPTION: Ballot Measure Poll Key Terms (Proportion) - Match population, sample, parameter, statistic, variable, and data to their descriptions in a poll estimating the proportion of eligible voters who will vote for a local ballot measure ===
// === SET QUESTION TYPE TO: matching ===

// === COMMON CONTROL ===

// Randomize the ballot measure being polled, and the jurisdiction word that keeps it local.
$causes = array("school funding measure", "transit bond", "park levy", "recycling ordinance")
$cause = $causes[rand(0, count($causes)-1)]
$jurisdictions = array("city", "county", "district")
$juris = $jurisdictions[rand(0, count($jurisdictions)-1)]

// Randomize how many eligible voters the pollsters reached.
$sizes = array(400, 500, 650, 800, 1000, 1200)
$n = $sizes[rand(0, count($sizes)-1)]

// The six terms stay in teaching order — population before sample, parameter before statistic.
$questions = array("Population", "Sample", "Parameter", "Statistic", "Variable", "Data")

// One description per term in the same order, then two distractors aimed at the two mistakes a
// poll invites: reporting a count where a proportion belongs, and drawing the population far
// wider than the measure it is about.
$answers = array(
  "all people eligible to vote on the {$cause} in this {$juris}",
  "the {$n} eligible voters the pollsters actually contacted",
  "the proportion of ALL eligible voters in this {$juris} who will vote for the {$cause}",
  "the proportion of the {$n} CONTACTED voters who will vote for the {$cause}",
  "whether ONE eligible voter will vote for the {$cause}",
  "yes, no",
  "the number of the {$n} contacted voters who said yes",
  "everyone living in the country"
)

// Spelled out rather than left to the default, because the two distractors make the answer list
// longer than the question list and the mapping must stay pinned to the first six.
$matchlist = "0,1,2,3,4,5"

$questiontitle = "Key term"
$answertitle = "Description"
// Terms hold their teaching order; only the descriptions move.
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
      <p><b>Step 1 &mdash; Name the group the poll is about.</b> Only people who can actually vote on the ' . $cause . ' in this ' . $juris . ' matter here, so they are the population. The ' . $n . ' of them the pollsters reached are the sample.</p>
      <p><b>Step 2 &mdash; Sort the two &ldquo;proportion&rdquo; numbers.</b> The proportion for <em>all</em> eligible voters is a population number (parameter); the proportion computed from the ' . $n . ' contacted voters is a sample number (statistic). The two phrases are word-for-word the same except for which group they point at &mdash; that is the whole trick.</p>
      <p><b>Step 3 &mdash; Name the measurement and its values.</b> What is recorded about one voter is the variable; the recorded responses are the data.</p>
      <div class="term-row"><span class="term-label">Population:</span> all people eligible to vote on the ' . $cause . ' in this ' . $juris . '</div>
      <div class="term-row"><span class="term-label">Sample:</span> the ' . $n . ' eligible voters the pollsters actually contacted</div>
      <div class="term-row"><span class="term-label">Parameter:</span> the proportion of <em>all</em> eligible voters in this ' . $juris . ' who will vote for the ' . $cause . '</div>
      <div class="term-row"><span class="term-label">Statistic:</span> the proportion of the ' . $n . ' <em>contacted</em> voters who will vote for the ' . $cause . '</div>
      <div class="term-row"><span class="term-label">Variable:</span> <em>X</em> = whether one eligible voter will vote for the ' . $cause . '</div>
      <div class="term-row"><span class="term-label">Data:</span> yes, no</div>
      <div class="sol-note">Two descriptions go unused, and each is a real mistake. <b>The number</b> of contacted voters who said yes is a count, not a proportion &mdash; a count of yeses cannot be the statistic here, because a proportion is what the poll set out to estimate. <b>Everyone living in the country</b> is a population drawn far too wide: this is a local ' . $juris . ' measure, and people who cannot vote on it are not under study.</div>
      <div class="sol-note">Notice the variable is <b>categorical</b> &mdash; each voter is a yes or a no &mdash; so averaging the responses would make no sense. That is why this study reports a proportion rather than a mean.</div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Political pollsters want to know the <b>proportion</b> of voters who will vote for a <b>$cause</b> appearing on the next ballot in one $juris. Working from the $juris voter rolls, they contact <b>$n</b> eligible voters and ask each one whether they will vote for the $cause.</p>
    <p style="margin:12px 0 0 0;">Match each key term to the description that fits this study. <b>Two descriptions are not used.</b></p>
  </div>
</div>


// === ANSWER ===

$solutionguide
