// === NAME - DESCRIPTION: Independence Expected Counts (2x2) - Compute three expected cells from row and column totals ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc", "numfunc", "numfunc")

// Each scenario is a 2x2 contingency table where the expected counts are all clean integers.
// Formula: E_ij = (row_i_total * col_j_total) / n
// Ask for E(top-left), E(top-right), E(bottom-left). E(bottom-right) is implied by marginals.

// Scenario 0: Diet x Cholesterol. n=100
//   Plant: 45 Normal, 15 High (row 60); Mixed: 25 Normal, 15 High (row 40)
//   Col totals: Normal 70, High 30
//   E: 42, 18, 28, 12
// Scenario 1: Smoker x Heart Disease. n=200
//   Smoker: 70 Yes, 50 No (row 120); Non: 30 Yes, 50 No (row 80)
//   Col totals: Yes 100, No 100
//   E: 60, 60, 40, 40
// Scenario 2: Workout x Energy. n=150
//   Regular: 55 High, 35 Low (row 90); Rare: 45 High, 15 Low (row 60)
//   Col totals: High 100, Low 50
//   E: 60, 30, 40, 20

$contexts = array(
  "A nutrition study surveys <b>100</b> adults on diet type and cholesterol level.",
  "A clinical study tracks <b>200</b> patients on smoking status and heart-disease diagnosis.",
  "A wellness survey records <b>150</b> adults on workout frequency and self-reported energy level."
)
$rowLabels1 = array("Plant-based", "Smoker", "Regular")
$rowLabels2 = array("Mixed",       "Non-smoker", "Rare")
$colLabels1 = array("Normal", "Yes", "High")
$colLabels2 = array("High",   "No",  "Low")
$rowVar     = array("Diet",   "Smoking status", "Workout frequency")
$colVar     = array("Cholesterol",   "Heart disease", "Energy level")

// Observed cells row1col1, row1col2, row2col1, row2col2
$o11 = array(45, 70, 55)
$o12 = array(15, 50, 35)
$o21 = array(25, 30, 45)
$o22 = array(15, 50, 15)

// Marginals
$rt1 = array(60, 120, 90)
$rt2 = array(40,  80, 60)
$ct1 = array(70, 100, 100)
$ct2 = array(30, 100,  50)
$ns  = array(100, 200, 150)

// Expected counts (precomputed integers)
$e11 = array(42, 60, 60)
$e12 = array(18, 60, 30)
$e21 = array(28, 40, 40)

$picked = jointrandfrom($contexts, $rowLabels1, $rowLabels2, $colLabels1, $colLabels2, $rowVar, $colVar, $o11, $o12, $o21, $o22, $rt1, $rt2, $ct1, $ct2, $ns, $e11, $e12, $e21)
$context = $picked[0]
$rL1 = $picked[1]
$rL2 = $picked[2]
$cL1 = $picked[3]
$cL2 = $picked[4]
$rVar = $picked[5]
$cVar = $picked[6]
$obs11 = $picked[7]
$obs12 = $picked[8]
$obs21 = $picked[9]
$obs22 = $picked[10]
$rowT1 = $picked[11]
$rowT2 = $picked[12]
$colT1 = $picked[13]
$colT2 = $picked[14]
$nval  = $picked[15]
$answer[0] = $picked[16]
$answer[1] = $picked[17]
$answer[2] = $picked[18]
$abstolerance[0] = 0.05
$abstolerance[1] = 0.05
$abstolerance[2] = 0.05

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
      <p><b>Rule:</b> For a chi-square test of independence, the expected count in row `i`, column `j` is `E_(ij) = (text(row total)_i xx text(col total)_j) / n`.</p>
      <p>Here `n = ' . $nval . '`. Row totals: $rL1 = ' . $rowT1 . ', $rL2 = ' . $rowT2 . '. Column totals: $cL1 = ' . $colT1 . ', $cL2 = ' . $colT2 . '.</p>
      <p><b>$rL1 / $cL1:</b> `E = (' . $rowT1 . ' xx ' . $colT1 . ') / ' . $nval . ' = ' . $answer[0] . '`</p>
      <p><b>$rL1 / $cL2:</b> `E = (' . $rowT1 . ' xx ' . $colT2 . ') / ' . $nval . ' = ' . $answer[1] . '`</p>
      <p><b>$rL2 / $cL1:</b> `E = (' . $rowT2 . ' xx ' . $colT1 . ') / ' . $nval . ' = ' . $answer[2] . '`</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Sanity check:</b> the four expected counts must have the same row and column marginals as the observed counts. If they do not, recheck the totals you multiplied.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">$context The observed counts are:</p>
    <table style="border-collapse:collapse; margin:0 auto; font-size:15px;">
      <tr>
        <th style="border:1px solid #21242c; padding:6px 12px; background:#f0f4ff;"></th>
        <th style="border:1px solid #21242c; padding:6px 12px; background:#f0f4ff;">$cL1</th>
        <th style="border:1px solid #21242c; padding:6px 12px; background:#f0f4ff;">$cL2</th>
        <th style="border:1px solid #21242c; padding:6px 12px; background:#f0f4ff;">Row total</th>
      </tr>
      <tr>
        <th style="border:1px solid #21242c; padding:6px 12px; background:#f0f4ff;">$rL1</th>
        <td style="border:1px solid #21242c; padding:6px 12px; text-align:center;">$obs11</td>
        <td style="border:1px solid #21242c; padding:6px 12px; text-align:center;">$obs12</td>
        <td style="border:1px solid #21242c; padding:6px 12px; text-align:center; background:#fafafa;">$rowT1</td>
      </tr>
      <tr>
        <th style="border:1px solid #21242c; padding:6px 12px; background:#f0f4ff;">$rL2</th>
        <td style="border:1px solid #21242c; padding:6px 12px; text-align:center;">$obs21</td>
        <td style="border:1px solid #21242c; padding:6px 12px; text-align:center;">$obs22</td>
        <td style="border:1px solid #21242c; padding:6px 12px; text-align:center; background:#fafafa;">$rowT2</td>
      </tr>
      <tr>
        <th style="border:1px solid #21242c; padding:6px 12px; background:#fafafa;">Col total</th>
        <td style="border:1px solid #21242c; padding:6px 12px; text-align:center; background:#fafafa;">$colT1</td>
        <td style="border:1px solid #21242c; padding:6px 12px; text-align:center; background:#fafafa;">$colT2</td>
        <td style="border:1px solid #21242c; padding:6px 12px; text-align:center; background:#fafafa;">$nval</td>
      </tr>
    </table>
    <p style="margin:10px 0 0 0;">Find the <b>expected counts</b> under the hypothesis that $rVar and $cVar are independent. Use `E_(ij) = (R_i xx C_j) / n`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Expected count for <b>$rL1 / $cL1</b>: $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Expected count for <b>$rL1 / $cL2</b>: $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Expected count for <b>$rL2 / $cL1</b>: $answerbox[2]
  </div>
</div>


// === ANSWER ===

$solutionguide
