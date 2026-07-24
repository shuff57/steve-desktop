// === NAME - DESCRIPTION: Three Children Genders - P(exactly 2 of one gender) and P(at least one) for a 3-child family ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc", "numfunc")

$labels   = array("boys", "girls")
$singular = array("boy",  "girl")
$targets  = array("B",    "G")
$picked = jointrandfrom($labels, $singular, $targets)
$gender = $picked[0]
$gender_singular = $picked[1]
$target = $picked[2]

$answer[0] = 3 / 8
$answer[1] = 7 / 8
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005

// Outcome enumeration helper. Build three strings:
//   $enum_neutral - all 8 outcomes shown unhighlighted (in the question)
//   $enum_exactly2 - 8 outcomes with the 3 "exactly 2 $target" outcomes highlighted
//   $enum_atleast1 - 8 outcomes with the 7 "at least 1 $target" outcomes highlighted
// All three share the same outer wrapper. Build inline.
$open_div = '<div style="display:flex;flex-wrap:wrap;justify-content:center;gap:6px;margin:10px 0">'
$enum_neutral = $open_div
$enum_exactly2 = $open_div
$enum_atleast1 = $open_div

for ($code = 0..7) {
  $outcome = ""
  $target_count = 0
  for ($pos = 0..2) {
    $shift = 2 - $pos
    $bit_val = floor($code / (2^$shift)) % 2
    if ($bit_val == 1) {
      // bit set -> "B"
      if ($target == "B") { $target_count = $target_count + 1 }
      $outcome = $outcome . "B"
    } else {
      // bit clear -> "G"
      if ($target == "G") { $target_count = $target_count + 1 }
      $outcome = $outcome . "G"
    }
  }
  // Neutral cell
  $cell_n = '<span style="display:inline-block;padding:6px 10px;background:#ffffff;border:2px solid #d1d5db;border-radius:6px;font-family:ui-monospace,Consolas,monospace;font-size:14px;color:#374151;font-weight:500">' . $outcome . '</span>'
  $enum_neutral = $enum_neutral . $cell_n
  // Exactly 2 highlight
  $bg2 = "#ffffff"
  $st2 = "#d1d5db"
  $co2 = "#374151"
  $w2 = "500"
  if ($target_count == 2) {
    $bg2 = "#bbf7d0"
    $st2 = "#10b981"
    $co2 = "#065f46"
    $w2 = "700"
  }
  $enum_exactly2 = $enum_exactly2 . '<span style="display:inline-block;padding:6px 10px;background:' . $bg2 . ';border:2px solid ' . $st2 . ';border-radius:6px;font-family:ui-monospace,Consolas,monospace;font-size:14px;color:' . $co2 . ';font-weight:' . $w2 . '">' . $outcome . '</span>'
  // At least 1 highlight
  $bg1 = "#ffffff"
  $st1 = "#d1d5db"
  $co1 = "#374151"
  $w1 = "500"
  if ($target_count >= 1) {
    $bg1 = "#bbf7d0"
    $st1 = "#10b981"
    $co1 = "#065f46"
    $w1 = "700"
  }
  $enum_atleast1 = $enum_atleast1 . '<span style="display:inline-block;padding:6px 10px;background:' . $bg1 . ';border:2px solid ' . $st1 . ';border-radius:6px;font-family:ui-monospace,Consolas,monospace;font-size:14px;color:' . $co1 . ';font-weight:' . $w1 . '">' . $outcome . '</span>'
}
$enum_neutral = $enum_neutral . '</div>'
$enum_exactly2 = $enum_exactly2 . '</div>'
$enum_atleast1 = $enum_atleast1 . '</div>'

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
      <p><b>Sample space:</b> 2<sup>3</sup> = 8 equally likely birth-order outcomes.</p>
      <p><b>(a)</b> Outcomes with <b>exactly 2 '.$gender.'</b>:</p>
      '.$enum_exactly2.'
      <p>3 outcomes &mdash; choose which 2 of the 3 slots are '.$gender.' (C(3,2) = 3).</p>
      <p>P(exactly 2 '.$gender.') = <b>3/8 = 0.375</b></p>
      <p><b>(b)</b> Outcomes with <b>at least one '.$gender_singular.'</b>:</p>
      '.$enum_atleast1.'
      <p>7 outcomes &mdash; all 8 except the all-other-gender outcome.</p>
      <p>P(at least one '.$gender_singular.') = <b>7/8 = 0.875</b></p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        (a) 3/8 = 0.375 &nbsp;&bull;&nbsp; (b) 7/8 = 0.875
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A family has three children. Assume each child is equally likely to be a boy (B) or a girl (G), and the genders are independent. The 8 equally likely birth orders are:</p>
    $enum_neutral
    <p style="margin:0.5em 0 0 0; font-size:14px; color:#555;">Enter your answers as fractions or as decimals rounded to 4 places.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the probability the family has <b>exactly 2 $gender</b>.
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find the probability the family has <b>at least one $gender_singular</b>.
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
