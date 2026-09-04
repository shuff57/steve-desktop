// === NAME - DESCRIPTION: Matrix Multiplication - Compute AB for 2x2 and Cv for (2x3)(3x1) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("matrix")

// Part (a): 2x2 times 2x2 -> 2x2
$a11 = nonzerorand(-4, 4)
$a12 = nonzerorand(-4, 4)
$a21 = nonzerorand(-4, 4)
$a22 = nonzerorand(-4, 4)
$b11 = nonzerorand(-4, 4)
$b12 = nonzerorand(-4, 4)
$b21 = nonzerorand(-4, 4)
$b22 = nonzerorand(-4, 4)

$A = matrix(array($a11,$a12,$a21,$a22), 2, 2)
$B = matrix(array($b11,$b12,$b21,$b22), 2, 2)

$p11 = $a11*$b11 + $a12*$b21
$p12 = $a11*$b12 + $a12*$b22
$p21 = $a21*$b11 + $a22*$b21
$p22 = $a21*$b12 + $a22*$b22
$Prod = matrix(array($p11,$p12,$p21,$p22), 2, 2)

// Part (b): 2x3 times 3x1 -> 2x1
$c11 = nonzerorand(-3, 3)
$c12 = nonzerorand(-3, 3)
$c13 = nonzerorand(-3, 3)
$c21 = nonzerorand(-3, 3)
$c22 = nonzerorand(-3, 3)
$c23 = nonzerorand(-3, 3)
$v1  = nonzerorand(-3, 3)
$v2  = nonzerorand(-3, 3)
$v3  = nonzerorand(-3, 3)

$C = matrix(array($c11,$c12,$c13, $c21,$c22,$c23), 2, 3)
$v = matrix(array($v1,$v2,$v3), 3, 1)

$cv1 = $c11*$v1 + $c12*$v2 + $c13*$v3
$cv2 = $c21*$v1 + $c22*$v2 + $c23*$v3
$Cv = matrix(array($cv1,$cv2), 2, 1)

$Adisp = matrixdisplaytable($A, "", 0, 0)
$Bdisp = matrixdisplaytable($B, "", 0, 0)
$Pdisp = matrixdisplaytable($Prod, "", 0, 0)
$Cdisp = matrixdisplaytable($C, "", 0, 0)
$vdisp = matrixdisplaytable($v, "", 0, 0)
$Cvdisp = matrixdisplaytable($Cv, "", 0, 0)

$anstypes = array("matrix", "matrix")
$answer[0] = matrixformat($Prod)
$answer[1] = matrixformat($Cv)
$answersize[0] = "2,2"
$answersize[1] = "2,1"

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
  .sol-body .matrix-row { margin: 0.4em 0; }
  .sol-body .answer-box { margin: 1em 0 0 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p>Matrix multiplication is the <b>row-by-column dot product</b>. To compute the entry in row `i`, column `j` of the product, you take row `i` of the left matrix and column `j` of the right matrix, multiply matching positions, and add. The left matrix\'s number of columns must equal the right matrix\'s number of rows.</p>

      <p class="step">Part (a). Compute AB, where A and B are both 2&times;2.</p>
      <p>A is 2&times;2 and B is 2&times;2, so AB exists and is 2&times;2 (inner dimensions match; outer dimensions give the result\'s shape).</p>
      <div class="matrix-row">A = ' . $Adisp . ' &nbsp; B = ' . $Bdisp . '</div>
      <p>Computing each entry of AB:</p>
      <ul style="margin:0.4em 0 0.4em 1.5em;">
        <li><b>Entry (1,1)</b>: dot product of row 1 of A with column 1 of B.
          <br>(' . $a11 . ')(' . $b11 . ') + (' . $a12 . ')(' . $b21 . ') = ' . ($a11*$b11) . ' + (' . ($a12*$b21) . ') = ' . $p11 . '</li>
        <li><b>Entry (1,2)</b>: dot product of row 1 of A with column 2 of B.
          <br>(' . $a11 . ')(' . $b12 . ') + (' . $a12 . ')(' . $b22 . ') = ' . ($a11*$b12) . ' + (' . ($a12*$b22) . ') = ' . $p12 . '</li>
        <li><b>Entry (2,1)</b>: dot product of row 2 of A with column 1 of B.
          <br>(' . $a21 . ')(' . $b11 . ') + (' . $a22 . ')(' . $b21 . ') = ' . ($a21*$b11) . ' + (' . ($a22*$b21) . ') = ' . $p21 . '</li>
        <li><b>Entry (2,2)</b>: dot product of row 2 of A with column 2 of B.
          <br>(' . $a21 . ')(' . $b12 . ') + (' . $a22 . ')(' . $b22 . ') = ' . ($a21*$b12) . ' + (' . ($a22*$b22) . ') = ' . $p22 . '</li>
      </ul>
      <p>Assemble the four entries:</p>
      <div class="matrix-row">AB = ' . $Pdisp . '</div>

      <p class="step">Part (b). Compute Cv, where C is 2&times;3 and v is 3&times;1.</p>
      <p>C is 2&times;3 and v is 3&times;1. The inner dimensions match (3 = 3), so the product exists. The outer dimensions tell us Cv is 2&times;1: a column vector with two entries.</p>
      <div class="matrix-row">C = ' . $Cdisp . ' &nbsp; v = ' . $vdisp . '</div>
      <p>Each entry of Cv is the dot product of a row of C with the column vector v. Because v has 3 entries, each dot product has 3 terms:</p>
      <ul style="margin:0.4em 0 0.4em 1.5em;">
        <li><b>Entry 1 (row 1)</b>: (' . $c11 . ')(' . $v1 . ') + (' . $c12 . ')(' . $v2 . ') + (' . $c13 . ')(' . $v3 . ') = ' . ($c11*$v1) . ' + (' . ($c12*$v2) . ') + (' . ($c13*$v3) . ') = ' . $cv1 . '</li>
        <li><b>Entry 2 (row 2)</b>: (' . $c21 . ')(' . $v1 . ') + (' . $c22 . ')(' . $v2 . ') + (' . $c23 . ')(' . $v3 . ') = ' . ($c21*$v1) . ' + (' . ($c22*$v2) . ') + (' . ($c23*$v3) . ') = ' . $cv2 . '</li>
      </ul>
      <p>Stack the two results into a column vector:</p>
      <div class="matrix-row">Cv = ' . $Cvdisp . '</div>

      <div class="answer-box">
        <b>AB</b> (2&times;2) and <b>Cv</b> (2&times;1) shown above.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:Arial; font-size:medium; line-height:1.6;">
<p>Let <b>A =</b> $Adisp &nbsp; and &nbsp; <b>B =</b> $Bdisp</p>
<p><b>a.)</b> Compute the matrix product <b>A B</b>:</p>
$answerbox[0]
<p style="margin-top:1em;">Now let <b>C =</b> $Cdisp &nbsp; and &nbsp; <b>v =</b> $vdisp</p>
<p><b>b.)</b> Compute the matrix-vector product <b>C v</b>:</p>
$answerbox[1]
</div>


// === ANSWER ===

$solutionguide
