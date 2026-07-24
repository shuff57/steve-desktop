// === NAME - DESCRIPTION: Choose the Appropriate Display - Given a data scenario, choose the best graphical display from a fixed list (bar chart, histogram, pie chart, boxplot, scatterplot, time series) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices")

// Scenario 0: A single quantitative variable, want to see shape and outliers — histogram (or boxplot, but histogram is best for shape)
// Scenario 1: Counts of preferred ice-cream flavor among 200 kids — bar chart
// Scenario 2: Daily closing price of a stock over 6 months — time series
// Scenario 3: Compare 5-number summary across 3 schools' SAT scores — side-by-side boxplots
// Scenario 4: Relationship between hours studied and exam score — scatterplot
// Scenario 5: Percent of city budget by category (one whole, parts of 100%) — pie chart

$ctxs = array(
  "A researcher has measured the <b>resting heart rates of 250 adults</b> (a single quantitative variable) and wants a display that shows the <b>shape, center, spread, and any unusual values</b>.",
  "A survey recorded each of <b>200 children's favorite ice-cream flavor</b> (chocolate, vanilla, strawberry, mint, or cookies-and-cream). The researcher wants to compare counts across flavors.",
  "An analyst wants to display the <b>daily closing price of one stock over six months</b> to look for trends.",
  "An admissions office wants to compare <b>SAT scores at three different high schools</b>, showing the median, quartiles, and any outliers for each school side by side.",
  "A teacher wants to investigate the <b>relationship between hours studied and exam score</b> across 80 students.",
  "A city council wants to show <b>what percent of the city's budget</b> goes to each of five departments (parts of one whole)."
)
$correct = array(1, 0, 4, 3, 5, 2)
// index: 0 bar, 1 histogram, 2 pie, 3 boxplot, 4 time series, 5 scatterplot

$why = array(
  "A <b>bar chart</b> compares counts across distinct categories.",
  "A <b>histogram</b> shows the shape, center, and spread of a single quantitative variable.",
  "A <b>pie chart</b> shows parts of one whole (percentages that sum to 100%).",
  "A side-by-side <b>boxplot</b> compares medians, quartiles, and outliers across groups.",
  "A <b>time series plot</b> shows how one quantitative variable changes over time.",
  "A <b>scatterplot</b> shows the relationship between two quantitative variables."
)

$picked = jointrandfrom($ctxs, $correct)
$ctx = $picked[0]
$answer[0] = $picked[1]
$wkey = $picked[1]
$wexplain = $why[$wkey]

$choices[0] = array(
  "Bar chart",
  "Histogram",
  "Pie chart",
  "Boxplot",
  "Time series plot",
  "Scatterplot"
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
      <p>' . $wexplain . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p>Which display is most appropriate?</p>
    $answerbox[0]
  </div>
</div>


// === ANSWER ===

$solutionguide
