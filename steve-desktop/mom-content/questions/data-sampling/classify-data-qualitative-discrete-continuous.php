// === NAME - DESCRIPTION: Classify Data as Qualitative, Quantitative Discrete, or Quantitative Continuous - Sort six everyday measurements into the three data types, separating counting from measuring ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices", "choices", "choices", "choices", "choices", "choices")

// Each pool holds items of ONE type, so drawing one from each pool guarantees the six parts
// cover all three types rather than landing on six categorical items by chance.
// 0 = qualitative, 1 = quantitative discrete, 2 = quantitative continuous.

$qual = array(
  "favorite baseball team",
  "brand of toothpaste",
  "most-watched television show",
  "eye color",
  "college major"
)

// Discrete = it comes from COUNTING. Whole things, nothing between two neighbouring values.
$disc = array(
  "number of tickets sold to a concert",
  "number of students enrolled at a community college",
  "number of siblings a student has",
  "number of cars in a parking lot",
  "number of text messages sent in a day"
)

// Continuous = it comes from MEASURING. Fractions and decimals are meaningful.
$cont = array(
  "time in line to buy groceries",
  "percent of body fat",
  "distance to the closest movie theatre",
  "weight of a newborn baby",
  "height of a sunflower plant"
)

// Draw the INDEX, then step the second one forward. Offsetting a known index is what keeps the
// two draws from a pool distinct: re-rolling could collide again, and there is no loop here.
$iq = rand(0, count($qual)-1)
$id = rand(0, count($disc)-1)
$ic = rand(0, count($cont)-1)

$jq = ($iq + rand(1, count($qual)-1)) % count($qual)
$jd = ($id + rand(1, count($disc)-1)) % count($disc)
$jc = ($ic + rand(1, count($cont)-1)) % count($cont)

$q1 = $qual[$iq]
$q2 = $qual[$jq]
$d1 = $disc[$id]
$d2 = $disc[$jd]
$c1 = $cont[$ic]
$c2 = $cont[$jc]

$items = array($q1, $d1, $c1, $q2, $d2, $c2)
$keys = array(0, 1, 2, 0, 1, 2)

$item_a = $items[0]
$item_b = $items[1]
$item_c = $items[2]
$item_d = $items[3]
$item_e = $items[4]
$item_f = $items[5]

$opts = array("qualitative (categorical)", "quantitative discrete", "quantitative continuous")

$questions[0] = $opts
$questions[1] = $opts
$questions[2] = $opts
$questions[3] = $opts
$questions[4] = $opts
$questions[5] = $opts

$answer[0] = $keys[0]
$answer[1] = $keys[1]
$answer[2] = $keys[2]
$answer[3] = $keys[3]
$answer[4] = $keys[4]
$answer[5] = $keys[5]

// A fixed taxonomy reads better in a stable order: shuffling three type names every part
// just makes the student re-read the same list six times.
$displayformat[0] = "select"
$displayformat[1] = "select"
$displayformat[2] = "select"
$displayformat[3] = "select"
$displayformat[4] = "select"
$displayformat[5] = "select"

$noshuffle[0] = "all"
$noshuffle[1] = "all"
$noshuffle[2] = "all"
$noshuffle[3] = "all"
$noshuffle[4] = "all"
$noshuffle[5] = "all"

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
      <p>Ask two questions in order.</p>
      <p><b>1. Is it a number at all?</b> If the answer is a word or a label, it is <b>qualitative (categorical)</b>: no arithmetic on it means anything.</p>
      <p><b>2. If it is a number, did it come from counting or from measuring?</b> Counting gives <b>quantitative discrete</b> data: whole things, with nothing in between. Measuring gives <b>quantitative continuous</b> data, where fractions and decimals are meaningful.</p>
      <div class="term-row"><span class="term-label">a. ' . $item_a . ':</span> qualitative (categorical)</div>
      <div class="term-row"><span class="term-label">b. ' . $item_b . ':</span> quantitative discrete: it is a count</div>
      <div class="term-row"><span class="term-label">c. ' . $item_c . ':</span> quantitative continuous: it is a measurement</div>
      <div class="term-row"><span class="term-label">d. ' . $item_d . ':</span> qualitative (categorical)</div>
      <div class="term-row"><span class="term-label">e. ' . $item_e . ':</span> quantitative discrete: it is a count</div>
      <div class="term-row"><span class="term-label">f. ' . $item_f . ':</span> quantitative continuous: it is a measurement</div>
      <p style="margin-top:1em;"><b>The trap:</b> "number of" almost always signals counting, so it is discrete even though the word "number" sounds measured. Time, distance, weight and percent are measured on a scale that can always be divided further, so they are continuous.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Classify each item below as <b>qualitative (categorical)</b>, <b>quantitative discrete</b>, or <b>quantitative continuous</b>.</p>
    <p style="margin:12px 0 0 0; color:#5b6270; font-size:15px;">Counting produces discrete data. Measuring produces continuous data.</p>
  </div>

  <div style="margin:12px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> $item_a $answerbox[0]
  </div>
  <div style="margin:12px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> $item_b $answerbox[1]
  </div>
  <div style="margin:12px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> $item_c $answerbox[2]
  </div>
  <div style="margin:12px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> $item_d $answerbox[3]
  </div>
  <div style="margin:12px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">e.</span> $item_e $answerbox[4]
  </div>
  <div style="margin:12px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">f.</span> $item_f $answerbox[5]
  </div>
</div>

// === ANSWER ===

$solutionguide
