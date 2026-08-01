// === NAME - DESCRIPTION: Product Launch Survey Key Terms (Proportion) - Match population, sample, parameter, statistic, variable, and data to their descriptions in a marketing study estimating the proportion of people in an area who will buy a new product ===
// === SET QUESTION TYPE TO: matching ===

// === COMMON CONTROL ===

// Randomize the product being tested and the area the marketing firm is targeting.
$products = array("energy drink", "streaming subscription", "running shoe", "meal kit")
$product = $products[rand(0, count($products)-1)]
$areas = array("metro area", "college town", "coastal county", "suburban school district")
$area = $areas[rand(0, count($areas)-1)]

// Randomize how many people in that area were surveyed.
$sizes = array(150, 250, 300, 450, 600, 750)
$n = $sizes[rand(0, count($sizes)-1)]

// The six terms stay in teaching order — population before sample, parameter before statistic.
$questions = array("Population", "Sample", "Parameter", "Statistic", "Variable", "Data")

// One description per term in the same order, then two distractors aimed at the two mistakes this
// study invites: reporting a count where a proportion belongs, and reporting a mean in a study
// whose variable is categorical.
$answers = array(
  "all people in the targeted {$area} (the potential buyers of the new {$product})",
  "the {$n} people in the {$area} who were actually surveyed",
  "the proportion of ALL people in the {$area} who will buy the new {$product}",
  "the proportion of the {$n} SURVEYED people who will buy the new {$product}",
  "whether ONE person buys the new {$product}",
  "buy, not buy",
  "the number of the {$n} surveyed people who said they would buy the new {$product}",
  "the mean amount of money one shopper spends per visit"
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
      <p><b>Step 1 &mdash; Name the group the study is about.</b> The firm wants to know about the people it could sell the ' . $product . ' to, so everyone in the targeted ' . $area . ' is the population. The ' . $n . ' of them who were actually surveyed are the sample.</p>
      <p><b>Step 2 &mdash; Sort the two &ldquo;proportion&rdquo; numbers.</b> The proportion for <em>all</em> people in the ' . $area . ' is a population number (parameter); the proportion computed from the ' . $n . ' surveyed people is a sample number (statistic). The two phrases are word-for-word the same except for which group they point at &mdash; that is the whole trick.</p>
      <p><b>Step 3 &mdash; Name the measurement and its values.</b> What gets recorded about one person is the variable; the recorded responses are the data.</p>
      <div class="term-row"><span class="term-label">Population:</span> all people in the targeted ' . $area . ' (the potential buyers of the new ' . $product . ')</div>
      <div class="term-row"><span class="term-label">Sample:</span> the ' . $n . ' people in the ' . $area . ' who were actually surveyed</div>
      <div class="term-row"><span class="term-label">Parameter:</span> the proportion of <em>all</em> people in the ' . $area . ' who will buy the new ' . $product . '</div>
      <div class="term-row"><span class="term-label">Statistic:</span> the proportion of the ' . $n . ' <em>surveyed</em> people who will buy the new ' . $product . '</div>
      <div class="term-row"><span class="term-label">Variable:</span> <em>X</em> = whether one person buys the new ' . $product . '</div>
      <div class="term-row"><span class="term-label">Data:</span> buy, not buy</div>
      <div class="sol-note">Two descriptions go unused, and each is a real mistake. <b>The number</b> of surveyed people who said they would buy is a count, not a proportion &mdash; the study set out to estimate a proportion, so the statistic has to be one. <b>The mean amount of money one shopper spends per visit</b> is an average of dollars, a measurement this survey never took; it belongs to a different study altogether.</div>
      <div class="sol-note">Notice the variable is <b>categorical</b> &mdash; each person either buys or does not &mdash; so averaging the responses would make no sense. That is why this study reports a proportion rather than a mean.</div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A marketing company wants to know the <b>proportion</b> of people in one $area who will buy a new <b>$product</b> before it decides where to advertise. The company surveys <b>$n</b> people living in that $area and asks each one whether they would buy the new $product.</p>
    <p style="margin:12px 0 0 0;">Match each key term to the description that fits this study. <b>Two descriptions are not used.</b></p>
  </div>
</div>


// === ANSWER ===

$solutionguide
