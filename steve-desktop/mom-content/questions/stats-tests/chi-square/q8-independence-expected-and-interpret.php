// === NAME - DESCRIPTION: Chi-square Independence — compute two expected counts from a 2x2 table, then interpret the test result ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats")

$anstypes = array("number","number","choices")
$noshuffle[2] = "all"

// 3 scenarios — all with integer expected counts
$ci = rand(0, 2)
$contexts = array(
  array("exercise frequency (regular/irregular)", "sleep quality (good/poor)",
        120, 80, 60, 140,   // obs: row1=[a,b] row2=[c,d]
        "regular exercisers with good sleep", "irregular exercisers with good sleep"),
  array("diet type (plant-based/omnivore)", "energy level (high/low)",
        90, 60, 110, 140,
        "plant-based with high energy", "omnivores with high energy"),
  array("study method (active/passive)", "exam grade (pass/fail)",
        140, 60, 70, 130,
        "active learners who passed", "passive learners who passed")
)
$var1     = $contexts[$ci][0]
$var2     = $contexts[$ci][1]
$a        = $contexts[$ci][2]
$b        = $contexts[$ci][3]
$c        = $contexts[$ci][4]
$d        = $contexts[$ci][5]
$cell1    = $contexts[$ci][6]
$cell2    = $contexts[$ci][7]

$n   = $a + $b + $c + $d
$R1  = $a + $b
$R2  = $c + $d
$C1  = $a + $c
$C2  = $b + $d

$E_a = $R1 * $C1 / $n
$E_b = $R1 * $C2 / $n

$answer[0] = $E_a
$answer[1] = $E_b
$abstolerance[0] = 0.05
$abstolerance[1] = 0.05

// Chi-square and decision (pre-computed for this scenario)
$chi2s   = array(18.2, 12.8, 34.6)
$chi2    = $chi2s[$ci]
$pval    = "< 0.001"
$alpha   = "0.05"
$decision_text = "Reject H₀"
$conclude = "There IS a significant association between $var1 and $var2."

$choices[2] = array(
  "Reject H₀. $conclude",
  "Fail to reject H₀. There is no evidence of an association.",
  "Accept H₀. The variables are definitely associated.",
  "Cannot conclude — the chi-square test requires equal sample sizes."
)
$answer[2] = 0   // index 0 = "Reject H₀" — every scenario here has p < 0.001

$solutionguide = '
<style>
  .sol-wrap details{width:100%;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;background:#fff;}
  .sol-wrap summary{cursor:pointer;display:block;width:100%;background:#f0f4ff;color:#21242c;padding:0.5em 0.75em;font-weight:700;font-size:15px;border-bottom:1px solid #e5e7eb;list-style:none;}
  .sol-wrap summary::-webkit-details-marker{display:none;}
  .sol-arrow-open{display:none;}
  .sol-wrap details[open] .sol-arrow-closed{display:none;}
  .sol-wrap details[open] .sol-arrow-open{display:inline;}
  .sol-body{padding:0.75em;background:#fafafa;}
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif;font-size:16px;line-height:1.6;color:#21242c;max-width:688px;margin:1em 0;">
  <details>
    <summary><span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span> Step-by-Step Solution</summary>
    <div class="sol-body">
      <p><b>Expected counts formula:</b> `E_{ij} = (Row_i cdot Col_j)/n`</p>
      <p>`E_{' . $cell1 . '} = (' . $R1 . ' xx ' . $C1 . ')/' . $n . ' = ' . $E_a . '`</p>
      <p>`E_{' . $cell2 . '} = (' . $R1 . ' xx ' . $C2 . ')/' . $n . ' = ' . $E_b . '`</p>
      <p><b>Part c:</b> &chi;² = ' . $chi2 . ', p-value ' . $pval . ' &lt; &alpha; = ' . $alpha . '. <b>Reject H₀.</b></p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif;font-size:16px;line-height:1.6;color:#21242c;max-width:688px;">
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:18px 20px;margin:10px 0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.07);">
    <p style="margin:0 0 10px 0;">A study investigates whether <b>$var1</b> is associated with <b>$var2</b>. The observed counts are:</p>
    <table style="border-collapse:collapse;font-size:14px;margin:0 auto;">
      <tr style="background:#f3f4f6;"><th style="border:1px solid #d1d5db;padding:6px 14px;"></th><th style="border:1px solid #d1d5db;padding:6px 14px;">Group 1</th><th style="border:1px solid #d1d5db;padding:6px 14px;">Group 2</th><th style="border:1px solid #d1d5db;padding:6px 14px;">Total</th></tr>
      <tr><td style="border:1px solid #d1d5db;padding:6px 14px;font-weight:600;">Row 1</td><td style="border:1px solid #d1d5db;padding:6px 14px;text-align:center;">$a</td><td style="border:1px solid #d1d5db;padding:6px 14px;text-align:center;">$b</td><td style="border:1px solid #d1d5db;padding:6px 14px;text-align:center;">$R1</td></tr>
      <tr><td style="border:1px solid #d1d5db;padding:6px 14px;font-weight:600;">Row 2</td><td style="border:1px solid #d1d5db;padding:6px 14px;text-align:center;">$c</td><td style="border:1px solid #d1d5db;padding:6px 14px;text-align:center;">$d</td><td style="border:1px solid #d1d5db;padding:6px 14px;text-align:center;">$R2</td></tr>
      <tr style="background:#f3f4f6;"><td style="border:1px solid #d1d5db;padding:6px 14px;font-weight:600;">Total</td><td style="border:1px solid #d1d5db;padding:6px 14px;text-align:center;">$C1</td><td style="border:1px solid #d1d5db;padding:6px 14px;text-align:center;">$C2</td><td style="border:1px solid #d1d5db;padding:6px 14px;text-align:center;">$n</td></tr>
    </table>
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:14px 20px;margin:6px 0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.07);">
    <span style="display:inline-block;background:#e8f0fe;color:#1865f2;border-radius:6px;padding:2px 9px;font-size:13px;font-weight:700;margin-right:8px;">a.</span> Expected count for Row 1, Group 1: $answerbox[0]
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:14px 20px;margin:6px 0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.07);">
    <span style="display:inline-block;background:#e8f0fe;color:#1865f2;border-radius:6px;padding:2px 9px;font-size:13px;font-weight:700;margin-right:8px;">b.</span> Expected count for Row 1, Group 2: $answerbox[1]
  </div>
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:14px 20px;margin:6px 0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.07);">
    <p style="margin:0 0 6px 0;">The completed test gives &chi;² = $chi2 and p-value $pval.</p>
    <span style="display:inline-block;background:#e8f0fe;color:#1865f2;border-radius:6px;padding:2px 9px;font-size:13px;font-weight:700;margin-right:8px;">c.</span> At &alpha; = $alpha, select the correct decision and conclusion. $answerbox[2]
  </div>
</div>

// === ANSWER ===

$solutionguide
