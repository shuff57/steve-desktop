// === NAME - DESCRIPTION: Matrix 3x3 System (Fresh) - Solve a 3-variable production schedule system via RREF ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("matrix")

// Construct-from-solution: pick solution first, derive coefficients.
// Use different parameter ranges than matrix-3x3-rref.php to produce a fresh seed.
$p = nonzerorand(-3,3)
$q = nonzerorand(-3,3)
$r,$s = nonzerodiffrands(-2,2,2)
$t = nonzerorand(-3,3)
$u = nonzerorand(-3,3)
$v = $u*$t + 1

$m22 = $p*$r + 1
$m23 = $q*$r + $t
$m32 = $p*$s + $u
$m33 = $q*$s + $v

$x0,$y0,$z0 = nonzerodiffrands(-4,4,3)

$b1 = $x0 + $p*$y0 + $q*$z0
$b2 = $r*$x0 + $m22*$y0 + $m23*$z0
$b3 = $s*$x0 + $m32*$y0 + $m33*$z0

$Aug = matrix(array(1,$p,$q,$b1, $r,$m22,$m23,$b2, $s,$m32,$m33,$b3), 3, 4)
$disp = matrixdisplaytable($Aug, "", 1, 1)

$RREF = matrix(array(1,0,0,$x0, 0,1,0,$y0, 0,0,1,$z0), 3, 4)
$anstypes = array("matrix", "file")
$answer[0] = matrixformat($RREF)
$answersize[0] = "3,4"
$scoremethod[1] = "takeanything"
$answerformat[1] = "images,.pdf"

// --- Intermediate matrices for solution guide ---
$r2_4 = $y0 + $t*$z0
$r3_4 = $u*$y0 + $v*$z0
$r1_4 = $x0 + $p*$y0

// Step 1: R2 <- R2 - (r)R1
$s1 = matrix(array(1,$p,$q,$b1, 0,1,$t,$r2_4, $s,$m32,$m33,$b3), 3, 4)
$s1d = matrixdisplaytable($s1, "", 1, 1)

// Step 2: R3 <- R3 - (s)R1
$s2 = matrix(array(1,$p,$q,$b1, 0,1,$t,$r2_4, 0,$u,$v,$r3_4), 3, 4)
$s2d = matrixdisplaytable($s2, "", 1, 1)

// Step 3: R3 <- R3 - (u)R2  [pivot (3,3) = 1 because v = u*t + 1]
$s3 = matrix(array(1,$p,$q,$b1, 0,1,$t,$r2_4, 0,0,1,$z0), 3, 4)
$s3d = matrixdisplaytable($s3, "", 1, 1)

// Step 4: R2 <- R2 - (t)R3
$s4 = matrix(array(1,$p,$q,$b1, 0,1,0,$y0, 0,0,1,$z0), 3, 4)
$s4d = matrixdisplaytable($s4, "", 1, 1)

// Step 5: R1 <- R1 - (q)R3
$s5 = matrix(array(1,$p,0,$r1_4, 0,1,0,$y0, 0,0,1,$z0), 3, 4)
$s5d = matrixdisplaytable($s5, "", 1, 1)

// Step 6: R1 <- R1 - (p)R2  (= RREF)
$rrefd = matrixdisplaytable($RREF, "", 1, 1)

// Shared CSS & JS (copy verbatim from free-response-template.php)
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
            <td>The system of 3 equations in 3 unknowns (units of products A, B, C in the schedule) is represented by this augmented matrix. The goal is RREF: an identity matrix on the left.<br>' . $disp . '</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Step 1</b></td>
            <td>Eliminate the entry below the first pivot (position 2,1).<br>
            R<sub>2</sub> &larr; R<sub>2</sub> &minus; (' . $r . ')R<sub>1</sub><br>' . $s1d . '</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Step 2</b></td>
            <td>Eliminate the entry below the first pivot (position 3,1).<br>
            R<sub>3</sub> &larr; R<sub>3</sub> &minus; (' . $s . ')R<sub>1</sub><br>' . $s2d . '</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Step 3</b></td>
            <td>Eliminate the entry below the second pivot (position 3,2).<br>
            R<sub>3</sub> &larr; R<sub>3</sub> &minus; (' . $u . ')R<sub>2</sub><br>' . $s3d . '</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Step 4</b></td>
            <td>Back-substitute: clear entry above the third pivot (position 2,3).<br>
            R<sub>2</sub> &larr; R<sub>2</sub> &minus; (' . $t . ')R<sub>3</sub><br>' . $s4d . '</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Step 5</b></td>
            <td>Back-substitute: clear entry above the third pivot (position 1,3).<br>
            R<sub>1</sub> &larr; R<sub>1</sub> &minus; (' . $q . ')R<sub>3</sub><br>' . $s5d . '</td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Step 6</b></td>
            <td class="col-check-bot">Back-substitute: clear entry above the second pivot (position 1,2).<br>
            R<sub>1</sub> &larr; R<sub>1</sub> &minus; (' . $p . ')R<sub>2</sub>
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                Solution: x = ' . $x0 . ', y = ' . $y0 . ', z = ' . $z0 . '<br>' . $rrefd . '
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:Arial; font-size:medium; line-height:1.6;">
<p>A factory schedules three products (x, y, z) across three production lines. The daily output requirements produce the following system of equations, written as an augmented matrix:</p>
<div style="margin:12px 0; text-align:center;">$disp</div>
<p>Row reduce this matrix to <b>reduced row echelon form (RREF)</b> to find the balanced production schedule. Upload a photo of your hand-written row operations.</p>
</div>
RREF: $answerbox[0]
Work upload: $answerbox[1]

// === ANSWER ===

$solutionguide
