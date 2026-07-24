// === NAME - DESCRIPTION: Histogram - Relative Frequency and Shape - Read class counts from a histogram, compute a relative frequency, and identify the shape of the distribution ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc", "choices")

// 5 bins, randomized counts that produce different shapes
$cases = array(
  array(array(2, 6, 14, 10, 3), 3, 2), // roughly symmetric, picks bin 3 for rel freq; n=35, bin3=10 → 10/35
  array(array(1, 4, 9, 14, 12), 2, 0), // left-skewed; n=40, bin2=9 → 9/40
  array(array(13, 11, 8, 4, 2), 1, 1), // right-skewed; n=38, bin1=11 → 11/38
  array(array(7, 15, 18, 16, 6), 0, 2), // symmetric; n=62, bin0=7 → 7/62
  array(array(3, 5, 6, 12, 18), 4, 0)   // left-skewed (long tail on left); n=44, bin4=18 → 18/44
)
// shape codes: 0 left-skewed, 1 right-skewed, 2 symmetric

$pick = $cases[rand(0, count($cases)-1)]
$counts = $pick[0]
$binIdx = $pick[1]
$shapeIdx = $pick[2]
$n = $counts[0] + $counts[1] + $counts[2] + $counts[3] + $counts[4]
$binCount = $counts[$binIdx]
$relFreq = $binCount / $n

$answer[0] = $relFreq
$reltolerance[0] = 0.02
$abstolerance[0] = 0.005
$answer[1] = $shapeIdx

$choices[1] = array(
  "Left-skewed (long tail on the left, mean < median)",
  "Right-skewed (long tail on the right, mean > median)",
  "Roughly symmetric"
)
$noshuffle[1] = "all"

$binLabels = array("0-10", "10-20", "20-30", "30-40", "40-50")
$label = $binLabels[$binIdx]

// Build a tiny ASCII-style table for the histogram counts since live image is not available
$tableHtml = '<table style="border-collapse:collapse;margin:0.5em 0;font-family:Arial;font-size:14px;">'
$tableHtml = $tableHtml . '<tr style="background:#f2f2f2"><th style="border:1px solid #ccc;padding:6px 12px;">Bin</th><th style="border:1px solid #ccc;padding:6px 12px;">Count</th></tr>'
for ($k=0..4) {
  $tableHtml = $tableHtml . '<tr><td style="border:1px solid #ccc;padding:6px 12px;">' . $binLabels[$k] . '</td><td style="border:1px solid #ccc;padding:6px 12px;text-align:center;">' . $counts[$k] . '</td></tr>'
}
$tableHtml = $tableHtml . '</table>'

$shapeWhy = array(
  "the counts pile up on the right and trail off to the left (the tail extends toward smaller values)",
  "the counts pile up on the left and trail off to the right (the tail extends toward larger values)",
  "the counts are roughly mirrored around the middle, with no obvious tail"
)
$why = $shapeWhy[$shapeIdx]

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
      <p><b>Part a:</b> Total count = ' . $n . '. The bin ' . $label . ' has count ' . $binCount . '. Relative frequency = ' . $binCount . ' / ' . $n . ' &approx; ' . round($relFreq, 3) . '.</p>
      <p><b>Part b:</b> Looking at the bin counts, ' . $why . '.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">A histogram is summarized by this frequency table:</p>
    $tableHtml
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the <b>relative frequency</b> of the bin <b>$label</b>. (Give a decimal, accurate to at least 3 decimal places.) $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Describe the <b>shape</b> of the distribution. $answerbox[1]
  </div>
</div>


// === ANSWER ===

$solutionguide
