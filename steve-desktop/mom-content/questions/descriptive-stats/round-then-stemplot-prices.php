// === NAME - DESCRIPTION: Rounding Before a Stemplot - Three-digit prices are rounded to the nearest ten so the hundreds digit can serve as the stem, then read back: round one price, name its leaf, count the prices on one stem, and say why the rounding step is needed ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number", "choices")

$ci = rand(0, 1)
$contexts = array(
  "the prices, in dollars, of the laptops on display at an electronics store",
  "the prices, in dollars, of the bicycles on the floor at a cycle shop"
)
$items = array("laptop", "bicycle")
$context = $contexts[$ci]
$item = $items[$ci]

// Fifteen prices in ascending order. The per-value gap is bounded above so the top price
// cannot reach four digits, which would break the one-digit-leaf scheme entirely.
$nVals = 15
$p = rand(235, 255)

$priceList = ""
$countByStem = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0)
$prices = array()
$rounded = array()

for ($i=0..14) {
  $prices[$i] = $p
  // round() to the nearest ten: divide by ten, round to a whole number, multiply back.
  $rv = round($p / 10, 0) * 10
  $rounded[$i] = $rv
  $st = ($rv - ($rv % 100)) / 100
  $countByStem[$st] = $countByStem[$st] + 1
  if ($i == 0) { $priceList = "" . $p }
  if ($i > 0) { $priceList = $priceList . ", " . $p }
  $p = $p + rand(4, 40)
}

$loStem = ($rounded[0] - ($rounded[0] % 100)) / 100
$hiStem = ($rounded[14] - ($rounded[14] % 100)) / 100

// Part (a) and (b): one price picked out of the list, deliberately not the first or last.
$ai = rand(2, 12)
$askPrice = $prices[$ai]
$askRounded = $rounded[$ai]
$askLeaf = ($askRounded % 100) / 10
$askStem = ($askRounded - ($askRounded % 100)) / 100

// Part (c): a stem that actually holds something.
$cStem = $loStem
$tries = rand(0, 9)
for ($k=0..$tries) {
  $cand = rand($loStem, $hiStem)
  if ($countByStem[$cand] > 0) { $cStem = $cand }
}
$cCount = $countByStem[$cStem]
$cLow = 100 * $cStem
$cHigh = 100 * $cStem + 99

$questions[3] = array(
  "Without rounding, the leaf would have to hold two digits, and a reader could no longer tell where one value ends and the next begins.",
  "Without rounding, the plot would have too many rows to fit on a page.",
  "Rounding is what makes the data symmetric, which a stemplot requires.",
  "Rounding removes the outliers, which a stemplot cannot display."
)
$answer[3] = 0

$answer[0] = $askRounded
$answerformat[0] = "integer"

$answer[1] = $askLeaf
$answerformat[1] = "integer"

$answer[2] = $cCount
$answerformat[2] = "integer"

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
      <p><span class="term-label">Step 1 &mdash; round to the nearest ten.</span> Look at the ones digit of $' . $askPrice . '. Five or more rounds up, four or less rounds down, and the ones digit becomes 0. That gives <b>$' . $askRounded . '</b>.</p>
      <p><span class="term-label">Step 2 &mdash; split the rounded price.</span> With prices in the hundreds the stem is the hundreds digit and the leaf is the tens digit, so $' . $askRounded . ' becomes stem ' . $askStem . ' and leaf <b>' . $askLeaf . '</b>. The ones digit is gone &mdash; that is the price of rounding, and it is why a stemplot of rounded data no longer holds the original values exactly.</p>
      <p><span class="term-label">Step 3 &mdash; count one row.</span> Rounded prices from $' . $cLow . ' to $' . $cHigh . ' all sit on stem ' . $cStem . '. Counting them gives <b>' . $cCount . '</b>. Round first, then count: a price like $299 rounds to $300 and moves up a row, so counting the unrounded list gives the wrong answer.</p>
      <p><span class="term-label">Step 4 &mdash; why round at all.</span> A stem-and-leaf plot needs a one-digit leaf. Left unrounded, $' . $askPrice . ' would need the two-digit leaf ' . ($askPrice % 100) . ', and a row reading 49 62 85 could just as easily be read as 4 9 6 2 8 5. Rounding buys a readable plot at the cost of the last digit.</p>
      <p><b>Answer:</b> (a) $' . $askRounded . ' &nbsp;&nbsp; (b) ' . $askLeaf . ' &nbsp;&nbsp; (c) ' . $cCount . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 12px 0;">The data below are $context, listed from lowest to highest.</p>
    <p style="margin:0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; font-family:ui-monospace,Menlo,Consolas,monospace; font-size:15px; line-height:1.8;">$priceList</p>
    <p style="margin:12px 0 0 0;">To display these in a stem-and-leaf plot, first round every price to the nearest ten. Then use the hundreds digit as the stem and the tens digit as the leaf.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Rounded to the nearest ten, what is the price of the $item that costs &#36;$askPrice? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> On the plot, what leaf will that rounded price be written as? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> After rounding, how many of the prices land on the row for stem $cStem, that is, between &#36;$cLow and &#36;$cHigh? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> Why does this data have to be rounded before it can go into a stem-and-leaf plot? $answerbox[3]
  </div>
</div>

// === ANSWER ===

$solutionguide
