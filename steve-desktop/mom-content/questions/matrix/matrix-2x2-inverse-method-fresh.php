// === NAME - DESCRIPTION: Matrix Inverse Method 2x2 (Fresh) - Solve AX=B using A^{-1}; multipart with det, inverse entries, and solution ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("matrix")

// Construct-from-solution pattern.
// Choose an invertible 2x2 A where det is small and nonzero, and A^{-1} entries are integers or half-integers.
// Strategy: pick det in {1, -1, 2, -2} and entries so inverse is clean.
// Use jointrandfrom to pick from a curated set of (a,b,c,d,det,det_display,ainv entries) tuples.
// Represent each scenario as parallel arrays indexed by $k.

// Each row: a, b, c, d (entries of A), det(A), d_over_det, neg_b_over_det, neg_c_over_det, a_over_det
// Chosen so all inverse entries are integers or half-integers.
// A = [[a,b],[c,d]], A^{-1} = (1/det)*[[d,-b],[-c,a]]

$as   = array(2, 3, 1, 4, 2, 3, 1, 2)
$bs   = array(1, 1, 2, 1, 3, 2, 3, 1)
$cs   = array(1, 1, 1, 1, 1, 1, 2, 3)
$ds   = array(1, 2, 3, 3, 4, 4, 7, 7)
// det = a*d - b*c
$dets = array(1, 5, 1, 11, 5, 10, 1, 11)
// A^{-1} entries (all integer here since det=1 or small, chosen carefully)
// For det=1: inverse is [[d,-b],[-c,a]] directly
// For det=5,10,11: choose solutions so Z = A^{-1}*B is integer
// We will keep only det=1 cases for guaranteed integer inverse entries: indices 0,2,6
// Re-curate: use only scenarios with det = 1
// A = [[2,1],[1,1]], det=1, A^{-1} = [[1,-1],[-1,2]]
// A = [[1,2],[1,3]], det=1, A^{-1} = [[3,-2],[-1,1]]
// A = [[1,3],[2,7]], det=1, A^{-1} = [[7,-3],[-2,1]]
// A = [[3,2],[4,3]], det=1, A^{-1} = [[3,-2],[-4,3]]
// A = [[2,3],[1,2]], det=1, A^{-1} = [[2,-3],[-1,2]]
// A = [[3,5],[1,2]], det=1, A^{-1} = [[2,-5],[-1,3]]

$as2  = array(2, 1, 1, 3, 2, 3)
$bs2  = array(1, 2, 3, 2, 3, 5)
$cs2  = array(1, 1, 2, 4, 1, 1)
$ds2  = array(1, 3, 7, 3, 2, 2)
// All det=1
// A^{-1} = [[d,-b],[-c,a]] since det=1
$inv_d  = array(1, 3, 7, 3, 2, 2)
$inv_nb = array(-1,-2,-3,-2,-3,-5)
$inv_nc = array(-1,-1,-2,-4,-1,-1)
$inv_a  = array(2, 1, 1, 3, 2, 3)

$k = rand(0, 5)
$A11 = $as2[$k]
$A12 = $bs2[$k]
$A21 = $cs2[$k]
$A22 = $ds2[$k]
$detA = 1

// A^{-1} entries
$Ai11 = $inv_d[$k]
$Ai12 = $inv_nb[$k]
$Ai21 = $inv_nc[$k]
$Ai22 = $inv_a[$k]

// Pick solution (x0, y0) with nonzerodiffrands so they are distinct nonzero integers
$x0,$y0 = nonzerodiffrands(-4,4,2)

// B = A * X
$B1 = $A11*$x0 + $A12*$y0
$B2 = $A21*$x0 + $A22*$y0

// Display matrices using matrixformat
$Amat  = matrix(array($A11,$A12,$A21,$A22), 2, 2)
$Adisplay = matrixformat($Amat)
$Bmat  = matrix(array($B1,$B2), 2, 1)
$Bdisplay = matrixformat($Bmat)
$Aimat = matrix(array($Ai11,$Ai12,$Ai21,$Ai22), 2, 2)
$Aidisplay = matrixformat($Aimat)
$Xmat  = matrix(array($x0,$y0), 2, 1)
$Xdisplay = matrixformat($Xmat)

// Answer types: (a) det [number], (b) A^{-1}[1,1] [number], (c) A^{-1}[1,2] [number], (d) solution X [matrix 2x1]
$anstypes = array("number", "number", "number", "matrix")
$answerformat[0] = "integer"
$answerformat[1] = "integer"
$answerformat[2] = "integer"
$answer[0] = $detA
$answer[1] = $Ai11
$answer[2] = $Ai12
$answer[3] = matrixformat($Xmat)
$answersize[3] = "2,1"

// Shared CSS & JS
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
</script>';

$solutionguide = $css_block . '
<div class="rubric-container">
  <details>
    <summary>
      <span class="arrow-closed">▸</span><span class="arrow-open">▾</span>
      Step-by-Step Solution
    </summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr>
            <th class="col-header">Step</th>
            <th class="col-check">Work</th>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Given</b></td>
            <td>Solve AX = B using the inverse method: X = A<sup>&minus;1</sup>B<br>
            A = ' . $Adisplay . ' &nbsp;&nbsp; B = ' . $Bdisplay . '</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Step 1</b></td>
            <td>Find det(A) = ad &minus; bc = (' . $A11 . ')(' . $A22 . ') &minus; (' . $A12 . ')(' . $A21 . ') = ' . $detA . '</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Step 2</b></td>
            <td>Apply the inverse formula: A<sup>&minus;1</sup> = (1/det) &middot; [[d, &minus;b], [&minus;c, a]]<br>
            A<sup>&minus;1</sup> = (1/' . $detA . ') &middot; [[' . $A22 . ', &minus;(' . $A12 . ')], [&minus;(' . $A21 . '), ' . $A11 . ']] = ' . $Aidisplay . '</td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Step 3</b></td>
            <td class="col-check-bot">Multiply X = A<sup>&minus;1</sup>B
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                X = ' . $Xdisplay . ' &nbsp;&rarr;&nbsp; x = ' . $x0 . ', y = ' . $y0 . '
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.4; color:#21242c; max-width:688px;">
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<p style="margin:0 0 8px 0;">Use the <b>inverse matrix method</b> to solve the linear system `AX = B`, where:</p>
<p style="margin:0 0 4px 0;">`A = $Adisplay` &nbsp;&nbsp; `B = $Bdisplay`</p>
<p style="margin:6px 0 0 0;">The 2&times;2 inverse formula is: `A^{-1} = (1/det(A)) [[d, -b],[-c, a]]`</p>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a</span>det(A) = <span style="margin-left:8px;">$answerbox[0]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b</span>Entry A<sup>&minus;1</sup>[row 1, col 1] = <span style="margin-left:8px;">$answerbox[1]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c</span>Entry A<sup>&minus;1</sup>[row 1, col 2] = <span style="margin-left:8px;">$answerbox[2]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d</span>Solution X = A<sup>&minus;1</sup>B (enter as a 2&times;1 column matrix): <span style="margin-left:8px;">$answerbox[3]</span>
</div>
</div>

// === ANSWER ===

$solutionguide
