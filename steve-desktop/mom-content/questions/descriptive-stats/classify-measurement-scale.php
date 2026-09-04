// === NAME - DESCRIPTION: Classify Measurement Scale - Determine whether each item is measured on a nominal, ordinal, interval, or ratio scale ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$pool_items = array(
  "High school soccer players classified by athletic ability: Superior, Average, Above average",
  "Baking temperatures for various main dishes: 350, 400, 325, 250, 300",
  "The colors of crayons in a 24-crayon box",
  "Social security numbers",
  "Incomes measured in dollars",
  "A satisfaction survey of a social website by number: 1 = very satisfied, 2 = somewhat satisfied, 3 = not satisfied",
  "Political outlook: extreme left, left-of-center, right-of-center, extreme right",
  "Time of day on an analog watch",
  "The distance in miles to the closest grocery store",
  "The dates 1066, 1492, 1644, 1947, and 1944",
  "The heights of 21-65 year-old women",
  "Common letter grades: A, B, C, D, and F",
  "T-shirt sizes: S, M, L, XL",
  "Eye color: blue, brown, green, hazel",
  "Temperature in degrees Celsius",
  "Number of siblings a person has",
  "Years of education completed",
  "Zip codes",
  "Movie ratings: G, PG, PG-13, R",
  "Phone numbers"
)
$pool_answers = array(1, 2, 0, 0, 3, 1, 1, 2, 3, 2, 3, 1, 1, 0, 2, 3, 3, 0, 1, 0)
$pool_reasons = array(
  "Ranked labels with no measurable gap between them.",
  "Numerical with meaningful differences, but 0&deg; is not 'no heat.'",
  "Pure labels with no order.",
  "Identifiers written with digits; averaging them is meaningless.",
  "Ordered, differences are meaningful, and zero dollars is a true zero.",
  "Ranked categories; the numbers are stand-ins for words.",
  "Ranked left to right along a spectrum, but with no measurable gap between positions.",
  "Meaningful differences, but 12:00 is a convention, not an absence of time.",
  "True zero and meaningful ratios.",
  "Ordered with meaningful differences, but year 0 is a convention.",
  "True zero and meaningful ratios.",
  "Ranked, but the gap from A to B is not a measured quantity.",
  "Ranked sizes with no measurable gap between them.",
  "Pure labels with no order.",
  "Numerical with meaningful differences, but 0&deg;C is a convention.",
  "Counted values with a true zero.",
  "Counted values with a true zero.",
  "Identifiers written with digits; averaging them is meaningless.",
  "Ranked categories with no measurable gap between them.",
  "Identifiers written with digits; averaging them is meaningless."
)

$scale_names = array("Nominal", "Ordinal", "Interval", "Ratio")

$indices = diffrands(0, count($pool_items)-1, 12, 'inc')

$anstypes = array("choices", "choices", "choices", "choices", "choices", "choices", "choices", "choices", "choices", "choices", "choices", "choices")

for ($i=0..11) {
  $questions[$i] = $scale_names
  $answer[$i] = $pool_answers[$indices[$i]]
  $item[$i] = $pool_items[$indices[$i]]
  $reason[$i] = $pool_reasons[$indices[$i]]
  $displayformat[$i] = "select"
}

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
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><b>How to decide the level of measurement:</b></p>
      <p><b>Step 1: Can the values be ordered?</b> If no, the scale is <b>nominal</b>. If yes, go to Step 2.</p>
      <p><b>Step 2: Are the differences between values measurable?</b> If no, the scale is <b>ordinal</b>. If yes, go to Step 3.</p>
      <p><b>Step 3: Is there a true zero?</b> If no, the scale is <b>interval</b>. If yes, the scale is <b>ratio</b>.</p>
      <hr style="border:0; border-top:1px solid #e5e7eb; margin:1em 0;">
      <p><b>Answers:</b></p>
      <div class="term-row"><span class="term-label">1.</span> ' . $item[0] . '<br><b>Answer:</b> ' . $scale_names[$answer[0]] . ': ' . $reason[0] . '</div>
      <div class="term-row"><span class="term-label">2.</span> ' . $item[1] . '<br><b>Answer:</b> ' . $scale_names[$answer[1]] . ': ' . $reason[1] . '</div>
      <div class="term-row"><span class="term-label">3.</span> ' . $item[2] . '<br><b>Answer:</b> ' . $scale_names[$answer[2]] . ': ' . $reason[2] . '</div>
      <div class="term-row"><span class="term-label">4.</span> ' . $item[3] . '<br><b>Answer:</b> ' . $scale_names[$answer[3]] . ': ' . $reason[3] . '</div>
      <div class="term-row"><span class="term-label">5.</span> ' . $item[4] . '<br><b>Answer:</b> ' . $scale_names[$answer[4]] . ': ' . $reason[4] . '</div>
      <div class="term-row"><span class="term-label">6.</span> ' . $item[5] . '<br><b>Answer:</b> ' . $scale_names[$answer[5]] . ': ' . $reason[5] . '</div>
      <div class="term-row"><span class="term-label">7.</span> ' . $item[6] . '<br><b>Answer:</b> ' . $scale_names[$answer[6]] . ': ' . $reason[6] . '</div>
      <div class="term-row"><span class="term-label">8.</span> ' . $item[7] . '<br><b>Answer:</b> ' . $scale_names[$answer[7]] . ': ' . $reason[7] . '</div>
      <div class="term-row"><span class="term-label">9.</span> ' . $item[8] . '<br><b>Answer:</b> ' . $scale_names[$answer[8]] . ': ' . $reason[8] . '</div>
      <div class="term-row"><span class="term-label">10.</span> ' . $item[9] . '<br><b>Answer:</b> ' . $scale_names[$answer[9]] . ': ' . $reason[9] . '</div>
      <div class="term-row"><span class="term-label">11.</span> ' . $item[10] . '<br><b>Answer:</b> ' . $scale_names[$answer[10]] . ': ' . $reason[10] . '</div>
      <div class="term-row"><span class="term-label">12.</span> ' . $item[11] . '<br><b>Answer:</b> ' . $scale_names[$answer[11]] . ': ' . $reason[11] . '</div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 12px 0;"><b>For each item below, select the correct level of measurement.</b></p>
    <p style="margin:0 0 12px 0; font-size:14px; color:#666;">Nominal = labels with no order &bull; Ordinal = ordered but gaps not measurable &bull; Interval = ordered with measurable differences but no true zero &bull; Ratio = ordered with measurable differences and a true zero</p>
    <ol style="margin:0; padding-left:1.5em;">
      <li style="margin:8px 0;">$item[0] $answerbox[0]</li>
      <li style="margin:8px 0;">$item[1] $answerbox[1]</li>
      <li style="margin:8px 0;">$item[2] $answerbox[2]</li>
      <li style="margin:8px 0;">$item[3] $answerbox[3]</li>
      <li style="margin:8px 0;">$item[4] $answerbox[4]</li>
      <li style="margin:8px 0;">$item[5] $answerbox[5]</li>
      <li style="margin:8px 0;">$item[6] $answerbox[6]</li>
      <li style="margin:8px 0;">$item[7] $answerbox[7]</li>
      <li style="margin:8px 0;">$item[8] $answerbox[8]</li>
      <li style="margin:8px 0;">$item[9] $answerbox[9]</li>
      <li style="margin:8px 0;">$item[10] $answerbox[10]</li>
      <li style="margin:8px 0;">$item[11] $answerbox[11]</li>
    </ol>
  </div>
</div>

// === ANSWER ===

$solutionguide
