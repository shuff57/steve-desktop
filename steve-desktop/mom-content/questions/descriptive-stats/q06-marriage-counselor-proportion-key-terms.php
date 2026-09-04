// === NAME - DESCRIPTION: Marriage Counseling Key Terms (Proportion) - Match population, sample, parameter, statistic, variable, and data to their descriptions in a study measuring the proportion of a counselor's clients who stay married ===
// === SET QUESTION TYPE TO: matching ===

// === COMMON CONTROL ===

// Randomize the practitioner's title; the client word is paired with it so the scenario stays coherent.
$titles = array("marriage counselor", "couples therapist", "family counselor")
$clientwords = array("couples", "couples", "families")
$ci = rand(0, 2)
$title = $titles[$ci]
$client = $clientwords[$ci]

// Randomize how many clients were followed up, and how long the follow-up ran.
$sizes = array(45, 60, 80, 120, 150, 200)
$n = $sizes[rand(0, count($sizes)-1)]
$yrs = rand(2, 6)

// The six terms stay in teaching order: population before sample, parameter before statistic.
$questions = array("Population", "Sample", "Parameter", "Statistic", "Variable", "Data")

// One description per term in the same order, then two distractors chosen to punish the two
// mistakes this scenario invites: reporting a count where a proportion belongs, and reaching for
// a mean when the variable is categorical.
$answers = array(
  "all {$client} this {$title} has counseled",
  "the {$n} {$client} whose outcomes were actually followed up",
  "the proportion of ALL of this {$title}'s {$client} who stay married",
  "the proportion of the {$n} FOLLOWED-UP {$client} who stay married",
  "whether ONE counseled couple stayed married",
  "yes, no",
  "the number of the {$n} followed-up {$client} who stayed married",
  "the mean number of years the {$n} followed-up {$client} stayed married"
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
      <p><b>Step 1: Name the group the study is about.</b> The question asks about this ' . $title . '&rsquo;s own ' . $client . ', so every couple they have counseled is the population, and the ' . $n . ' whose outcomes were traced are the sample.</p>
      <p><b>Step 2: Sort the two &ldquo;proportion&rdquo; numbers.</b> The proportion for <em>all</em> of the counselor&rsquo;s ' . $client . ' is a population number (parameter); the proportion computed from the ' . $n . ' followed-up ' . $client . ' is a sample number (statistic). The two phrases are word-for-word the same except for which group they point at: that is the whole trick.</p>
      <p><b>Step 3: Name the measurement and its values.</b> What is recorded about one couple is the variable; the recorded responses are the data.</p>
      <div class="term-row"><span class="term-label">Population:</span> all ' . $client . ' this ' . $title . ' has counseled</div>
      <div class="term-row"><span class="term-label">Sample:</span> the ' . $n . ' ' . $client . ' whose outcomes were actually followed up</div>
      <div class="term-row"><span class="term-label">Parameter:</span> the proportion of <em>all</em> of this ' . $title . '&rsquo;s ' . $client . ' who stay married</div>
      <div class="term-row"><span class="term-label">Statistic:</span> the proportion of the ' . $n . ' <em>followed-up</em> ' . $client . ' who stay married</div>
      <div class="term-row"><span class="term-label">Variable:</span> <em>X</em> = whether one counseled couple stayed married</div>
      <div class="term-row"><span class="term-label">Data:</span> yes, no</div>
      <div class="sol-note">Two descriptions go unused, and each is a real mistake. <b>The number</b> who stayed married is a count, not a proportion: the statistic here has to be a proportion, because a proportion is what the study set out to estimate. <b>The mean number of years</b> married is the answer to a different study altogether: this one never measures years.</div>
      <div class="sol-note">This study measures a <b>proportion</b>, not a mean. The variable is <b>categorical</b>: each couple is a yes or a no after ' . $yrs . ' years: so averaging the responses would make no sense. Compare it with a study of the mean number of sessions per client, where the variable is numerical and a mean is exactly right.</div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A <b>$title</b> wants to know the <b>proportion</b> of the $client they counsel who stay married. Working from their practice records, they reach <b>$n</b> of their former $client and record, for each one, whether that couple was still married <b>$yrs</b> years after counseling ended.</p>
    <p style="margin:12px 0 0 0;">Match each key term to the description that fits this study. <b>Two descriptions are not used.</b></p>
  </div>
</div>


// === ANSWER ===

$solutionguide
