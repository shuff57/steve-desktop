// === NAME - DESCRIPTION: Insurance Health Costs Key Terms - Match population, sample, parameter, statistic, variable, and data to their descriptions in a study of the mean yearly health costs of an insurer's clients ===
// === SET QUESTION TYPE TO: matching ===

// === COMMON CONTROL ===

// Randomize the company wording, the number of clients surveyed, and the example dollar amounts.
$comps = array("insurance company", "health insurer", "insurance provider")
$arts = array("An", "A", "An")
$ci = rand(0, count($comps)-1)
$comp = $comps[$ci]
$art = $arts[$ci]

$n = rand(60, 250)

// Yearly health costs, kept in increasing order so the example list reads naturally.
$c1 = rand(300, 900)
$c2 = $c1 + rand(200, 800)
$c3 = $c2 + rand(300, 1500)

// The dollar sign is written as an entity: a bare $ in front of a digit is currency here, not a
// variable, and only the entity keeps the parser out of it. One scalar for prompt and key alike.
$costlist = "&#36;$c1, &#36;$c2, and &#36;$c3"

// The six terms stay in teaching order — population before sample, parameter before statistic —
// so the list reads as the definition sequence rather than a scramble.
$questions = array("Population", "Sample", "Parameter", "Statistic", "Variable", "Data")

// One description per term, in the same order, then two distractors. With six terms and exactly
// six descriptions the last answer is free by elimination; the extras take that away.
$answers = array(
  "all clients of the $comp",
  "the $n clients whose yearly health costs were actually recorded",
  "the mean yearly health cost for ALL clients of the $comp",
  "the mean yearly health cost for the $n clients in the SAMPLE",
  "the yearly health cost of ONE client",
  "the recorded dollar amounts, such as $costlist",
  "everyone in the country who has health insurance",
  "the total health costs paid out by the $comp last year"
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
      <p><b>Step 1 &mdash; Name the group.</b> The ' . $comp . ' cares about its own clients, so all of those clients are the population. The ' . $n . ' clients whose yearly costs were actually recorded are the sample.</p>
      <p><b>Step 2 &mdash; Sort the two mean costs.</b> The mean taken over all clients is a parameter; the same mean computed from the surveyed clients is a statistic. The two read almost identically &mdash; only the group they point at differs.</p>
      <p><b>Step 3 &mdash; Name the measurement and its values.</b> What is measured on one client is the variable; the dollar amounts written down are the data.</p>
      <div class="term-row"><span class="term-label">Population:</span> all clients of the ' . $comp . '</div>
      <div class="term-row"><span class="term-label">Sample:</span> the ' . $n . ' clients whose yearly health costs were actually recorded</div>
      <div class="term-row"><span class="term-label">Parameter:</span> the mean yearly health cost for <em>all</em> clients of the ' . $comp . '</div>
      <div class="term-row"><span class="term-label">Statistic:</span> the mean yearly health cost for the ' . $n . ' clients in the sample</div>
      <div class="term-row"><span class="term-label">Variable:</span> <em>X</em> = the yearly health cost of one client</div>
      <div class="term-row"><span class="term-label">Data:</span> the recorded dollar amounts, such as ' . $costlist . '</div>
      <div class="sol-note">Two of the descriptions are not used. <b>Everyone in the country who has health insurance</b> is far wider than the group this ' . $comp . ' is studying &mdash; a population drawn too wide. And <b>the total health costs paid out last year</b> is a total, not a mean; the study asks what one typical client costs, so that it can price a policy.</div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$art <b>$comp</b> wants to know the <b>mean yearly health cost</b> of its clients, so that it can set the price of health insurance. The yearly costs of $n of its clients are recorded; the recorded amounts include $costlist.</p>
    <p style="margin:12px 0 0 0;">Match each key term to the description that fits this study. <b>Two descriptions are not used.</b></p>
  </div>
</div>


// === ANSWER ===

$solutionguide
