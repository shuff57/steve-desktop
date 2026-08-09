// === NAME - DESCRIPTION: Turn a Class Rank into a Percentile - Convert two graduating ranks into percentiles, noticing that rank counts down from the top while percentile counts up from the bottom, then read what the percentile says about the class ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "choices")

$ci = rand(0, 1)
if ($ci == 0) {
  $who1 = "Leilani"
  $who2 = "Marcus"
  $group = "graduating class"
  $member = "students"
}
else {
  $who1 = "Priya"
  $who2 = "Tomas"
  $group = "cycling club"
  $member = "riders"
}

$nTotal = 20 * rand(6, 15)

// Two ranks well clear of each other, both near the top where ranks are actually quoted.
$rank1 = rand(12, 45)
$rank2 = $rank1 + rand(30, 90)
if ($rank2 > $nTotal - 5) { $rank2 = $nTotal - rand(5, 20) }

// Rank counts down from the top, so the number BELOW a rank is total minus rank. That inversion
// is the whole difficulty here: rank 1 is the best and lands at the highest percentile.
$below1 = $nTotal - $rank1
$below2 = $nTotal - $rank2

$raw1 = ($below1 + 0.5) / $nTotal * 100
$raw2 = ($below2 + 0.5) / $nTotal * 100
$p1 = round($raw1, 0)
$p2 = round($raw2, 0)
$raw1Shown = round($raw1, 2)
$raw2Shown = round($raw2, 2)

$answer[0] = $p1
$answerformat[0] = "integer"

$answer[1] = $p2
$answerformat[1] = "integer"

$questions[2] = array(
  "A rank nearer 1 is better, so it gives a higher percentile. Rank counts down from the top and percentile counts up from the bottom.",
  "A rank nearer 1 is better, so it gives a lower percentile. Rank and percentile both count down from the top.",
  "Rank and percentile are the same measurement on different scales, so rank " . $rank1 . " is the " . $rank1 . "th percentile.",
  "The relationship depends on the size of the group; in a large group a low rank gives a low percentile."
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
      <p><span class="term-label">The inversion to watch.</span> Rank 1 is the top of the group, but the 1st percentile is the bottom of it. Rank counts down from the best; percentile counts up from the worst. So before touching the formula, turn the rank into a count of who is <i>below</i>.</p>
      <p><span class="term-label">Part (a).</span> ' . $who1 . ' is ranked ' . $rank1 . ' out of ' . $nTotal . ', so ' . $nTotal . ' &minus; ' . $rank1 . ' = <b>' . $below1 . '</b> are ranked below, and one rank equals hers. With `x = ' . $below1 . '`, `y = 1`, `n = ' . $nTotal . '`: `(' . $below1 . ' + 0.5(1))/' . $nTotal . ' xx 100 = ' . $raw1Shown . '`, so the <b>' . $p1 . 'th percentile</b>. About ' . $p1 . '% of the ' . $member . ' ranked at or below her.</p>
      <p><span class="term-label">Part (b).</span> Same working for rank ' . $rank2 . ': ' . $below2 . ' below, so `(' . $below2 . ' + 0.5)/' . $nTotal . ' xx 100 = ' . $raw2Shown . '`, the <b>' . $p2 . 'th percentile</b>. The larger rank gives the smaller percentile, which is the check that you have not flipped the subtraction.</p>
      <p><span class="term-label">Part (c).</span> Reading rank ' . $rank1 . ' as "the ' . $rank1 . 'th percentile" is the mistake this question exists to catch. It would put a near-top ' . $member . ' near the bottom of the group.</p>
      <p><b>Answer:</b> (a) ' . $p1 . 'th percentile &nbsp;&nbsp; (b) ' . $p2 . 'th percentile</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A $group has <b>$nTotal $member</b>, ranked from 1 (the top) to $nTotal. Round each percentile to the nearest whole number.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> $who1 is ranked <b>$rank1</b>. At what percentile is her ranking? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> $who2 is ranked <b>$rank2</b>. At what percentile is his ranking? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> How does a ranking relate to a percentile? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
