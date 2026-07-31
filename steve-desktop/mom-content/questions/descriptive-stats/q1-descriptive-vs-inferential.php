// === NAME - DESCRIPTION: Descriptive vs Inferential Statistics - Classify a randomized study goal as descriptive or inferential statistics ===
// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL ===

$anstypes = array("choices")

// Scenarios. 0 = descriptive (summarize data we already have), 1 = inferential (use sample to estimate population).
$topics = array(
  array(
    0,
    "make a graph of last term's exam scores",
    "Last term's exam scores are a complete set of already-collected data; the graph only summarizes what happened in that one term."
  ),
  array(
    1,
    "estimate the mean commute time for all city residents",
    "A mean computed for every resident is usually impossible to measure directly, so this study uses a sample to draw a conclusion about the whole population."
  ),
  array(
    0,
    "list the ages of the 40 patients seen in one clinic last month",
    "This is a complete list of data for a specific group at a specific time; it summarizes without reaching beyond the data."
  ),
  array(
    1,
    "predict the proportion of voters in the state who support a new tax",
    "The goal is to learn about all voters in the state from a smaller sample, which is the hallmark of inferential statistics."
  ),
  array(
    0,
    "compute the average price of every house sold in the county this year",
    "Every house sold this year is the complete data set; the average is a summary of that data, not a guess about a larger group."
  ),
  array(
    1,
    "use the heights of 120 randomly chosen students to estimate the average height of all students at the college",
    "The 120 students are a sample, and the goal is to estimate a population average."
  )
)

$picked = $topics[rand(0, 5)]
$correct = $picked[0]
$goal = $picked[1]
$explanation = $picked[2]

$answer[0] = $correct
$questions[0] = array(
  "Descriptive statistics",
  "Inferential statistics"
)
$noshuffle[0] = "all"

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p>' . $explanation . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A researcher wants to <b>$goal</b>.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p>Is this an example of descriptive statistics or inferential statistics?</p>
    $answerbox[0]
  </div>
</div>


// === ANSWER ===

$solutionguide
