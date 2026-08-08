// === NAME - DESCRIPTION: What Each Display Keeps and Throws Away - Decide which display still holds every original value, which suits a categorical variable and which shows change over time, then say what a stemplot gives up as the data grows ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices", "choices", "choices")

// The four displays are offered in the same order for the first three parts, so the student is
// choosing between the same set each time rather than re-reading a new list.
$displays = array(
  "Stem-and-leaf plot",
  "Bar graph",
  "Line graph",
  "Histogram"
)

$ci = rand(0, 2)
$catExamples = array(
  "which of five bus routes each of 180 commuters takes to work",
  "which of four blood types each of 260 donors has",
  "which of six departments each of 300 employees works in"
)
$timeExamples = array(
  "the number of visitors to a museum in each month of last year",
  "a shop's takings at the end of each week for the past year",
  "the number of overnight guests at a hostel in each month of last year"
)
$valueExamples = array(
  "the exam scores of 24 students, where the teacher also wants to read off every individual score",
  "the resting heart rates of 20 volunteers, where the nurse also wants each individual reading",
  "the ages of 22 members of a club, where the secretary also wants every individual age"
)
$catEx = $catExamples[$ci]
$timeEx = $timeExamples[$ci]
$valueEx = $valueExamples[$ci]

$questions[0] = $displays
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = $displays
$answer[1] = 1
$noshuffle[1] = "all"

$questions[2] = $displays
$answer[2] = 2
$noshuffle[2] = "all"

$questions[3] = array(
  "The rows grow so long that the shape stops being readable, which is the point at which a histogram, whose bars need no room per observation, takes over.",
  "It stops working because stem-and-leaf plots may only be used on data sets of fewer than 30 values by definition.",
  "It stops working because large data sets always contain outliers, and a stemplot cannot show an outlier.",
  "Nothing is given up; a stem-and-leaf plot is the better display at any size."
)
$answer[3] = 0

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
      <p><span class="term-label">Part (a) &mdash; which display keeps every value.</span> The <b>stem-and-leaf plot</b>, and it is the only one of the four that does. Each leaf is one observation written out in full, so the original list can be read straight back off the plot. The other three record how many fell somewhere, and once a value has been counted into a bar it can never be recovered.</p>
      <p><span class="term-label">Part (b) &mdash; a categorical variable.</span> The <b>bar graph</b>. The categories are names, so the horizontal axis is a list rather than a number line &mdash; which is why the bars are drawn apart. A histogram would be wrong here: its touching bars claim the axis is continuous.</p>
      <p><span class="term-label">Part (c) &mdash; change over time.</span> The <b>line graph</b>. Time runs left to right along the axis and the segments carry the eye from one period to the next, so a rise or a dip is visible immediately. Here the segments really do mean something, because time genuinely passes between the points.</p>
      <p><span class="term-label">Part (d) &mdash; what a stemplot gives up.</span> Its detail is exactly its limit. Twenty values make tidy rows; two thousand make rows hundreds of digits long, and the shape everyone came for disappears. That is the moment to hand over to a histogram, which spends the same space no matter how many observations it holds. Keeping every value and staying readable are in tension, and no display wins both.</p>
      <p><b>Answer:</b> (a) Stem-and-leaf plot &nbsp;&nbsp; (b) Bar graph &nbsp;&nbsp; (c) Line graph</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Each part below describes a data set. Choose the display that fits it best. The same four displays are offered every time.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> A researcher has $valueEx. Which display shows the overall shape <b>and</b> still lets every original value be read back off it? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> A survey recorded $catEx. Which display fits this variable? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> An analyst has $timeEx, and wants to show how it rose and fell across the year. Which display fits? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> A stem-and-leaf plot keeps every value, so why not always use one? What goes wrong as the data set gets large? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
