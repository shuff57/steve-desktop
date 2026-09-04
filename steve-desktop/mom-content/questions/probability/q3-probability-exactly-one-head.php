// === NAME - DESCRIPTION: P(Exactly One Head) - Probability of getting exactly one head when flipping n coins ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc")

// n in {2,3,4} -> P(exactly one head) = n / 2^n
$ns       = array(2,     3,     4)
$nums     = array(2,     3,     4)
$dens     = array(4,     8,     16)
$reduced  = array("1/2", "3/8", "1/4")

$picked = jointrandfrom($ns, $nums, $dens, $reduced)
$n = $picked[0]
$num = $picked[1]
$den = $picked[2]
$frac = $picked[3]

$answer = $num / $den
$abstolerance[0] = 0.005
$decimal_show = round($answer, 4)

// Coin pair (heads / tails) for the question text.
$coin_h = '<svg width="42" height="42" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="heads" style="display:inline-block;vertical-align:middle;margin:0 3px"><circle cx="24" cy="24" r="20" fill="#fde68a" stroke="#b45309" stroke-width="2"/><circle cx="24" cy="24" r="16" fill="none" stroke="#b45309" stroke-width="0.8" stroke-dasharray="2,2"/><text x="24" y="31" font-size="20" font-weight="800" fill="#78350f" text-anchor="middle" font-family="Georgia,serif">H</text></svg>'
$coin_t = '<svg width="42" height="42" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="tails" style="display:inline-block;vertical-align:middle;margin:0 3px"><circle cx="24" cy="24" r="20" fill="#e5e7eb" stroke="#6b7280" stroke-width="2"/><circle cx="24" cy="24" r="16" fill="none" stroke="#6b7280" stroke-width="0.8" stroke-dasharray="2,2"/><text x="24" y="31" font-size="20" font-weight="800" fill="#1f2937" text-anchor="middle" font-family="Georgia,serif">T</text></svg>'
$coin_pair = '<span style="display:inline-flex;align-items:center;gap:2px">' . $coin_h . '<span style="color:#6b7280;font-weight:600;margin:0 6px">or</span>' . $coin_t . '</span>'

// Solution-side enumeration of all 2^n outcomes. Highlight strings with exactly one "H".
// Build outcome strings by binary counting from 0 to 2^n - 1; bit (n-1-i) -> position i (so index 0 is leftmost coin).
$enum_html = '<div style="display:flex;flex-wrap:wrap;justify-content:center;gap:6px;margin:10px 0">'
for ($code = 0..($den - 1)) {
  $outcome = ""
  $head_count = 0
  for ($pos = 0..($n - 1)) {
    // Bit at position (n-1-pos): most significant bit first so output reads left-to-right.
    $shift = $n - 1 - $pos
    $bit_val = floor($code / (2^$shift)) % 2
    if ($bit_val == 1) {
      $outcome = $outcome . "H"
      $head_count = $head_count + 1
    } else {
      $outcome = $outcome . "T"
    }
  }
  $bg = "#ffffff"
  $stroke = "#d1d5db"
  $color = "#374151"
  $weight = "500"
  if ($head_count == 1) {
    $bg = "#bbf7d0"
    $stroke = "#10b981"
    $color = "#065f46"
    $weight = "700"
  }
  $enum_html = $enum_html . '<span style="display:inline-block;padding:6px 10px;background:' . $bg . ';border:2px solid ' . $stroke . ';border-radius:6px;font-family:ui-monospace,Consolas,monospace;font-size:14px;color:' . $color . ';font-weight:' . $weight . '">' . $outcome . '</span>'
}
$enum_html = $enum_html . '</div>'

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
      <p><b>Sample space:</b> '.$n.' coins give n(S) = 2<sup>'.$n.'</sup> = '.$den.' equally likely outcomes:</p>
      '.$enum_html.'
      <p><b>Event:</b> "exactly one head": the highlighted outcomes above. There are '.$num.' of them (one for each position of the single H).</p>
      <p>P(exactly one head) = '.$num.'/'.$den.' = <b>'.$frac.' &approx; '.$decimal_show.'</b></p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        P(exactly one head) = '.$frac.' &approx; '.$decimal_show.'
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">You flip <b>$n</b> fair coins. Each coin has two faces:</p>
    <div style="margin:10px 0;text-align:center;">$coin_pair</div>
    <p style="margin:0.5em 0 0 0;">What is the probability of getting <b>exactly one head</b>?</p>
    <p style="margin:0.5em 0 0 0; font-size:14px; color:#555;">Enter as a fraction (e.g. <code>3/8</code>) or as a decimal rounded to 4 places.</p>
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
