// === NAME - DESCRIPTION: Is a High Percentile Good News - Decide whether a high or a low percentile is the desirable one in two situations that pull opposite ways, then pick the sentence that interprets a stated percentile correctly ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices", "choices")

// Half of these measure something you want more of, half something you want less of. Pairing one
// of each in every draw is the point: the answer is never "high is good", it depends on what is
// being measured, and a student who learns one habit gets the other half wrong.
$goodDesc = array(
  "the scores on a college entrance exam",
  "the salaries earned by recent graduates",
  "the speeds, in miles per hour, of the riders in a bicycle race"
)
$goodThing = array("score", "salary", "speed")
$gi = rand(0, 2)
$goodContext = $goodDesc[$gi]
$goodNoun = $goodThing[$gi]

$badDesc = array(
  "the wait times at a passport office",
  "the finishing times of the runners in a race",
  "the repair costs after a standard crash test"
)
$badThing = array("wait time", "finishing time", "repair cost")
$bi = rand(0, 2)
$badContext = $badDesc[$bi]
$badNoun = $badThing[$bi]

$questions[0] = array(
  "A high percentile, because a larger " . $goodNoun . " is the better outcome here.",
  "A low percentile, because a smaller " . $goodNoun . " is the better outcome here.",
  "It makes no difference; a percentile says nothing about whether an outcome is good.",
  "A percentile near 50, because being close to average is always safest."
)
$answer[0] = 0

$questions[1] = array(
  "A low percentile, because a smaller " . $badNoun . " is the better outcome here.",
  "A high percentile, because a larger " . $badNoun . " is the better outcome here.",
  "It makes no difference; a percentile says nothing about whether an outcome is good.",
  "A percentile near 50, because being close to average is always safest."
)
$answer[1] = 0

// The stated percentile for part (c), attached to the undesirable measure so the correct sentence
// has to say "most people did better", not "did well".
$kc = 10 * rand(7, 9)
// kc is a multiple of ten, so the ordinal suffix is always "th": but it still cannot be written
// as "$kc th" in the question text, which substitutes with a space before the suffix.
$kcLabel = $kc . "th"
$valc = 10 * rand(3, 9)
$belowPct = $kc

$questions[2] = array(
  "About " . $belowPct . "% of the group had a " . $badNoun . " at or below that value, so most of the group did better.",
  "About " . $belowPct . "% of the group had a " . $badNoun . " at or above that value, so most of the group did worse.",
  "The " . $badNoun . " was " . $belowPct . "% higher than the group's average.",
  "About " . $belowPct . "% of the group had exactly that " . $badNoun . "."
)
$answer[2] = 0

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
  .term-label { font-weight:700; color:#1865f2; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><span class="term-label">A percentile is a position, not a verdict.</span> It only says where a value sits among the others. Whether that position is good news depends entirely on whether you want more of the thing being measured or less of it: so the first question to ask is never "is the percentile high", it is "is a bigger number better here".</p>
      <p><span class="term-label">Part (a): ' . $goodNoun . '.</span> A bigger ' . $goodNoun . ' is the better outcome, so being above more of the group is good: you want a <b>high</b> percentile.</p>
      <p><span class="term-label">Part (b): ' . $badNoun . '.</span> A smaller ' . $badNoun . ' is the better outcome, so now being above most of the group is bad news: you want a <b>low</b> percentile. Same statistic, opposite reading, and nothing about the arithmetic changed.</p>
      <p><span class="term-label">Part (c): saying it in words.</span> The ' . $kc . 'th percentile means about ' . $kc . '% of the group came in at or below that value. With a ' . $badNoun . ', "below" is the good side: so sitting at the ' . $kc . 'th percentile says most of the group did <i>better</i>. Note what the sentence does not say: nothing about the average, and nothing about how many people got that exact value.</p>
      <p><b>Answer:</b> (a) high &nbsp;&nbsp; (b) low &nbsp;&nbsp; (c) about ' . $kc . '% were at or below, so most did better</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Each part below describes a different measurement. A percentile tells you where one value sits among all the others: it does not, on its own, say whether that is good news.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> A study records $goodContext. Would you rather be at a high or a low percentile? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Another study records $badContext. Would you rather be at a high or a low percentile? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> In that second study, one $badNoun of $valc is at the <b>$kcLabel percentile</b>. Which sentence interprets that correctly? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
