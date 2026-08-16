// === NAME - DESCRIPTION: Describe the Random Variable - the sample mean or sample proportion in words, and whether the test is about an average or a share ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// A claim about a mean or a proportion. Parts: (a) choices - the random variable in words
// (b) choices - whether the test is about an average or a share.
// Invariant: both answers are constant per scenario.

$anstypes = array("choices", "choices")

$cases = array(
  array("You are testing that the mean speed of your cable Internet connection is more than three Megabits per second.",
        "The mean Internet speed in Megabits per second",
        "an average of a measured quantity"),
  array("The American family has an average of two children.",
        "The mean number of children an American family has",
        "an average of a measured quantity"),
  array("Dr. Minho Kang claims the probability that a person picked at random in Times Square is visiting the area is 0.83.",
        "The proportion of people picked at random in Times Square visiting the city",
        "a share of a group with a yes-or-no trait"),
  array("A study claims the mean time to graduate from college is 4.5 years.",
        "The mean time to graduate, in years, for a sample of college graduates",
        "an average of a measured quantity"),
  array("A health organization claims 9.5% of adults suffer from depression.",
        "The proportion of adults who suffer from depression",
        "a share of a group with a yes-or-no trait")
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$rvLabel = $cases[$i][1]
$kindLabel = $cases[$i][2]

$questions[0] = array(
  $rvLabel,
  "The sample size of the study",
  "The population standard deviation"
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  "The test is about " . $kindLabel . ".",
  "The test is about the sample size.",
  "The test is about the spread of the data."
)
$answer[1] = 0
$noshuffle[1] = "all"

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
      <p><span class="term-label">Part (a) &mdash; the random variable.</span> The random variable is the sample statistic that varies from sample to sample: ' . $rvLabel . '. Name the units, the group, and the sample size when you describe it.</p>
      <p><span class="term-label">Part (b) &mdash; average or share.</span> The test is about ' . $kindLabel . '. That distinction decides the parameter &mdash; `mu` for an average, `p` for a share &mdash; and the parameter picks the whole row of the hypothesis-testing table.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the random variable? Describe it in words.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What kind of quantity is the test about?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
