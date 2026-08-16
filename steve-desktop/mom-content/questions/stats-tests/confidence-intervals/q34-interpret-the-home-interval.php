// === NAME - DESCRIPTION: Interpret the Home Interval - the specific sentence, the general sentence, and the bad reading ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's interpretation sentences (Try It Now 7.8). Parts: (a) choices - the correct specific
// sentence (b) choices - the correct general sentence (c) choices - what is wrong with the
// "90% chance" reading.
// Invariant: all three answers are constant on every seed.

$anstypes = array("choices", "choices", "choices")

$questions[0] = array(
  "We are 90% confident that the true mean sale price of all homes recently listed in Butte County lies between $377,702 and $442,298",
  "There is a 90% chance the mean home price is between $377,702 and $442,298",
  "90% of the homes in Butte County sold for between $377,702 and $442,298"
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  "A confidence interval is a range built from a sample by a recipe that has a known success rate: if you repeated the whole study many times, about 90% of those intervals would contain the true population value",
  "There is a 90% chance that any particular interval contains the true population value",
  "The interval contains 90% of the data"
)
$answer[1] = 0
$noshuffle[1] = "all"

$questions[2] = array(
  "It treats mu as a random quantity that might fall in a fixed range &mdash; mu is a constant, and once the endpoints are computed they are constants too, so the statement is either entirely true or entirely false",
  "It is too vague &mdash; it names no population and no quantity",
  "It is wrong because the interval is too wide"
)
$answer[2] = 0
$noshuffle[2] = "all"

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
      <p><span class="term-label">Part (a) &mdash; the specific sentence.</span> A good specific sentence names all three: who was studied, what was averaged, and which two numbers came out. "We are 90% confident that the true mean sale price of all homes recently listed in Butte County lies between $377,702 and $442,298."</p>
      <p><span class="term-label">Part (b) &mdash; the general sentence.</span> The general sentence goes wrong when it attaches the probability to the interval instead of to the procedure. Say instead that the METHOD captures the true mean 90% of the time: if every group in your class collects 35 prices and builds an interval the same way, about nine out of every ten of those intervals will contain the true mean, and none of you will know which ones.</p>
      <p><span class="term-label">Part (c) &mdash; the bad sentence.</span> "There is a 90% chance the mean home price is between $377,702 and $442,298" treats mu as a random quantity that might fall in a fixed range. mu is a constant, and once the endpoints are computed they are constants too, so the statement is either entirely true or entirely false &mdash; there is no 90% about it. The randomness was in the sampling, not in the population mean.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">The demonstration data produced the 90% interval ($377,702, $442,298) for the mean sale price of homes recently listed in Butte County.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which sentence correctly interprets the interval for THIS study?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which sentence correctly explains what a confidence interval means IN GENERAL?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is wrong with "there is a 90% chance the mean home price is between $377,702 and $442,298"?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
