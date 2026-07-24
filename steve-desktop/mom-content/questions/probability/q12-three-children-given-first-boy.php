// === NAME - DESCRIPTION: Three Children Given First Boy - Reduced sample space size and conditional P(2 boys | first-born is boy) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc", "numfunc")

$answer[0] = 4
$answer[1] = 2 / 4
$abstolerance[0] = 0.5
$abstolerance[1] = 0.005

// Build three enumeration HTMLs over the 8 birth-order outcomes.
$open_div = '<div style="display:flex;flex-wrap:wrap;justify-content:center;gap:6px;margin:10px 0">'
$enum_neutral = $open_div
$enum_first_b = $open_div
$enum_first_b_two_b = $open_div

for ($code = 0..7) {
  $outcome = ""
  $boy_count = 0
  $first_letter = ""
  for ($pos = 0..2) {
    $shift = 2 - $pos
    $bit_val = floor($code / (2^$shift)) % 2
    $letter = "G"
    if ($bit_val == 1) {
      $letter = "B"
      $boy_count = $boy_count + 1
    }
    $outcome = $outcome . $letter
    if ($pos == 0) { $first_letter = $letter }
  }
  $first_is_b = ($first_letter == "B")
  $exactly2 = ($boy_count == 2)

  $cell_n = '<span style="display:inline-block;padding:6px 10px;background:#ffffff;border:2px solid #d1d5db;border-radius:6px;font-family:ui-monospace,Consolas,monospace;font-size:14px;color:#374151;font-weight:500">' . $outcome . '</span>'
  $enum_neutral = $enum_neutral . $cell_n

  // first-B highlight (the reduced sample space for part a)
  $bg1 = "#ffffff"
  $st1 = "#d1d5db"
  $co1 = "#374151"
  $w1 = "500"
  if ($first_is_b) {
    $bg1 = "#bbf7d0"
    $st1 = "#10b981"
    $co1 = "#065f46"
    $w1 = "700"
  }
  $enum_first_b = $enum_first_b . '<span style="display:inline-block;padding:6px 10px;background:' . $bg1 . ';border:2px solid ' . $st1 . ';border-radius:6px;font-family:ui-monospace,Consolas,monospace;font-size:14px;color:' . $co1 . ';font-weight:' . $w1 . '">' . $outcome . '</span>'

  // first-B AND exactly 2 boys total highlight (favorable for part b)
  // Outcomes outside the reduced sample space (first-G) shown faded; others either green (favorable) or white.
  $bg2 = "#ffffff"
  $st2 = "#d1d5db"
  $co2 = "#374151"
  $w2 = "500"
  $opacity2 = "1"
  if ($first_is_b && $exactly2) {
    $bg2 = "#bbf7d0"
    $st2 = "#10b981"
    $co2 = "#065f46"
    $w2 = "700"
  }
  if (!$first_is_b) {
    $opacity2 = "0.35"
  }
  $enum_first_b_two_b = $enum_first_b_two_b . '<span style="display:inline-block;padding:6px 10px;background:' . $bg2 . ';border:2px solid ' . $st2 . ';border-radius:6px;font-family:ui-monospace,Consolas,monospace;font-size:14px;color:' . $co2 . ';font-weight:' . $w2 . ';opacity:' . $opacity2 . '">' . $outcome . '</span>'
}
$enum_neutral = $enum_neutral . '</div>'
$enum_first_b = $enum_first_b . '</div>'
$enum_first_b_two_b = $enum_first_b_two_b . '</div>'

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
      <p><b>Full sample space</b> for 3 children: 8 equally likely outcomes.</p>
      <p><b>(a)</b> Conditioning on "first-born is a boy" keeps only outcomes whose first letter is B:</p>
      '.$enum_first_b.'
      <p>Reduced sample space size = <b>4</b>.</p>
      <p><b>(b)</b> Inside the reduced sample space, find outcomes with <b>exactly 2 boys total</b>. Outcomes outside the reduced sample space are faded:</p>
      '.$enum_first_b_two_b.'
      <p>2 outcomes (BBG, BGB) out of 4 in the reduced sample space.</p>
      <p>P(exactly 2 boys | first-born is a boy) = 2/4 = <b>1/2 = 0.5</b></p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        (a) 4 &nbsp;&bull;&nbsp; (b) 1/2 = 0.5
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A family has 3 children. Each child is independently and equally likely to be a boy (B) or a girl (G). The 8 equally likely birth orders are:</p>
    $enum_neutral
    <p style="margin:0.5em 0 0 0;">Suppose you are told that <b>the first-born is a boy</b>.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Given that the first-born is a boy, how many outcomes are in the <b>reduced sample space</b>?
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find <b>P(exactly 2 boys total | first-born is a boy)</b>. Enter as a fraction or decimal rounded to 4 places.
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
