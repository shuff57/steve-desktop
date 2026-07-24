// === NAME - DESCRIPTION: Matrix Add and Scalar Multiply - Compute A+B and kA-mB on 2x2 matrices ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("matrix")

// Random integer entries in [-5,5] excluding 0
$a11 = nonzerorand(-5, 5)
$a12 = nonzerorand(-5, 5)
$a21 = nonzerorand(-5, 5)
$a22 = nonzerorand(-5, 5)
$b11 = nonzerorand(-5, 5)
$b12 = nonzerorand(-5, 5)
$b21 = nonzerorand(-5, 5)
$b22 = nonzerorand(-5, 5)

// Pick small positive scalars for kA - mB
$k = rand(2, 4)
$m = rand(2, 4)

$A = matrix(array($a11,$a12,$a21,$a22), 2, 2)
$B = matrix(array($b11,$b12,$b21,$b22), 2, 2)

// Part (a): A + B
$s11 = $a11 + $b11
$s12 = $a12 + $b12
$s21 = $a21 + $b21
$s22 = $a22 + $b22
$Sum = matrix(array($s11,$s12,$s21,$s22), 2, 2)

// Part (b): k*A - m*B
$d11 = $k*$a11 - $m*$b11
$d12 = $k*$a12 - $m*$b12
$d21 = $k*$a21 - $m*$b21
$d22 = $k*$a22 - $m*$b22
$Diff = matrix(array($d11,$d12,$d21,$d22), 2, 2)

$Adisp = matrixdisplaytable($A, "", 0, 0)
$Bdisp = matrixdisplaytable($B, "", 0, 0)
$Sdisp = matrixdisplaytable($Sum, "", 0, 0)
$Ddisp = matrixdisplaytable($Diff, "", 0, 0)

$anstypes = array("matrix", "matrix")
$answer[0] = matrixformat($Sum)
$answer[1] = matrixformat($Diff)
$answersize[0] = "2,2"
$answersize[1] = "2,2"

$css_block = '
<style>
    .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
    .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
    .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; border:none; }
    .rubric-container details[open] summary { box-shadow: inset 0 -1px 0 #ccc; }
    .rubric-container summary::-webkit-details-marker { display:none; }
    .arrow-open { display:none; }
    .rubric-container details[open] .arrow-closed { display:none; }
    .rubric-container details[open] .arrow-open { display:inline; }
    .rubric-content { overflow:hidden; max-height:0; opacity:0; transition:max-height 300ms ease-out, opacity 300ms ease-out, padding 200ms ease-out; margin-top:0; background:#fafafa; box-sizing:border-box; padding:0 0.75em; }
    .rubric-container details[open] .rubric-content { max-height:2000px; opacity:1; padding:0.75em; }
    .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
    .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
    .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; user-select:text; }
    .row-colored { background:#fff9ea; }
    .col-header { width:25%; border-top-left-radius:8px; }
    .col-check { border-top-right-radius:8px; }
    .col-cat-bot { border-bottom-left-radius:8px; }
    .col-check-bot { border-bottom-right-radius:8px; }
</style>
<script>
document.addEventListener("DOMContentLoaded", function() {
  var details = document.querySelectorAll(".rubric-container details");
  details.forEach(function(det) {
    var content = det.querySelector(".rubric-content");
    det.addEventListener("toggle", function() {
      if (det.open) {
        content.style.maxHeight = content.scrollHeight + "px";
        content.style.opacity = "1";
      } else {
        content.style.maxHeight = content.scrollHeight + "px";
        content.offsetHeight;
        content.style.maxHeight = "0";
        content.style.opacity = "0";
      }
    });
    content.addEventListener("transitionend", function() {
      if (!det.open) content.style.maxHeight = null;
    });
  });
});
</script>'

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em 1em; background:#fafafa; }
  .sol-body p { margin: 0.6em 0; }
  .sol-body .step { font-weight:700; color:#1865f2; margin-top:1em; }
  .sol-body .calc { margin: 0.4em 0 0.4em 1.5em; font-size:16px; }
  .sol-body .matrix-row { margin: 0.4em 0 0.4em 0; }
  .sol-body .answer-box { margin: 1em 0 0 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p>Matrix addition and scalar multiplication are <b>entrywise</b> operations. That means we work one position at a time: row 1 column 1, row 1 column 2, and so on. The two matrices must have the same dimensions for addition; for scalar multiplication, every entry is multiplied by the same number.</p>

      <p><b>The given matrices:</b></p>
      <div class="matrix-row">A = ' . $Adisp . ' &nbsp; B = ' . $Bdisp . '</div>

      <p class="step">Part (a). Compute A + B.</p>
      <p>Rule: `(A + B)_{ij} = A_{ij} + B_{ij}`. Add the entry in row `i`, column `j` of A to the entry in the same position of B. The result also lives in row `i`, column `j` of the new matrix.</p>
      <p>Going position by position:</p>
      <ul style="margin:0.4em 0 0.4em 1.5em;">
        <li>(1,1): ' . $a11 . ' + (' . $b11 . ') = ' . $s11 . '</li>
        <li>(1,2): ' . $a12 . ' + (' . $b12 . ') = ' . $s12 . '</li>
        <li>(2,1): ' . $a21 . ' + (' . $b21 . ') = ' . $s21 . '</li>
        <li>(2,2): ' . $a22 . ' + (' . $b22 . ') = ' . $s22 . '</li>
      </ul>
      <p>Place these four numbers back into a 2&times;2 matrix:</p>
      <div class="matrix-row">A + B = ' . $Sdisp . '</div>

      <p class="step">Part (b). Compute ' . $k . 'A − ' . $m . 'B.</p>
      <p>Two ideas combine here. <b>First</b>, multiply matrix A by ' . $k . ': scalar multiplication means multiplying each entry by ' . $k . '. <b>Second</b>, multiply matrix B by ' . $m . ' the same way. <b>Then</b> subtract the two resulting matrices entrywise (subtract corresponding entries).</p>
      <p>Combined into one expression per position: `(' . $k . 'A - ' . $m . 'B)_{ij} = ' . $k . ' \\cdot A_{ij} - ' . $m . ' \\cdot B_{ij}`.</p>
      <p>Working through each position:</p>
      <ul style="margin:0.4em 0 0.4em 1.5em;">
        <li>(1,1): ' . $k . '(' . $a11 . ') &minus; ' . $m . '(' . $b11 . ') = ' . ($k*$a11) . ' &minus; (' . ($m*$b11) . ') = ' . $d11 . '</li>
        <li>(1,2): ' . $k . '(' . $a12 . ') &minus; ' . $m . '(' . $b12 . ') = ' . ($k*$a12) . ' &minus; (' . ($m*$b12) . ') = ' . $d12 . '</li>
        <li>(2,1): ' . $k . '(' . $a21 . ') &minus; ' . $m . '(' . $b21 . ') = ' . ($k*$a21) . ' &minus; (' . ($m*$b21) . ') = ' . $d21 . '</li>
        <li>(2,2): ' . $k . '(' . $a22 . ') &minus; ' . $m . '(' . $b22 . ') = ' . ($k*$a22) . ' &minus; (' . ($m*$b22) . ') = ' . $d22 . '</li>
      </ul>
      <p>Assemble the result:</p>
      <div class="matrix-row">' . $k . 'A &minus; ' . $m . 'B = ' . $Ddisp . '</div>

      <div class="answer-box">
        <b>A + B</b> and <b>' . $k . 'A &minus; ' . $m . 'B</b> shown above.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:Arial; font-size:medium; line-height:1.6;">
<p>Let <b>A =</b> $Adisp &nbsp; and &nbsp; <b>B =</b> $Bdisp</p>
<p><b>a.)</b> Compute <b>A + B</b>:</p>
$answerbox[0]
<p><b>b.)</b> Compute <b>$k A − $m B</b>:</p>
$answerbox[1]
</div>


// === ANSWER ===

$solutionguide
