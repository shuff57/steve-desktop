// === COMMON CONTROL ===

loadlibrary("matrix")

$a = nonzerorand(-2,2)
$c = nonzerorand(-2,2)
$a22 = $a*$c + 1

$x0,$y0 = nonzerodiffrands(-4,4,2)

$b1 = $x0 + $a*$y0
$b2 = $c*$x0 + $a22*$y0

$Aug = matrix(array(1,$a,$b1, $c,$a22,$b2), 2, 3)
$disp = matrixdisplaytable($Aug, "", 1, 1)

$RREF = matrix(array(1,0,$x0, 0,1,$y0), 2, 3)
$anstypes = array("matrix", "file")
$answer[0] = matrixformat($RREF)
$answersize[0] = "2,3"
$scoremethod[1] = "takeanything"
$answerformat[1] = "images,.pdf"

// --- Solution guide ---
$step1 = matrix(array(1,$a,$b1, 0,1,$y0), 2, 3)
$step1d = matrixdisplaytable($step1, "", 1, 1)
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
            <td>We need to row reduce this augmented matrix to reduced row echelon form (RREF). The goal is to get an identity matrix on the left side.<br>' . $disp . '</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Step 1</b></td>
            <td><b>Eliminate the entry below the first pivot.</b><br>
            Subtract ' . $c . ' times Row 1 from Row 2 to create a zero in position (2,1).<br>
            R<sub>2</sub> &larr; R<sub>2</sub> &minus; (' . $c . ')R<sub>1</sub><br>' . $step1d . '</td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Step 2</b></td>
            <td class="col-check-bot"><b>Eliminate the entry above the second pivot.</b><br>
            Subtract ' . $a . ' times Row 2 from Row 1 to create a zero in position (1,2), completing the RREF.<br>
            R<sub>1</sub> &larr; R<sub>1</sub> &minus; (' . $a . ')R<sub>2</sub>
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                ' . $rrefd . '
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
<p>Row reduce the following augmented matrix to reduced row echelon form. Upload a photo of your hand-written work.</p>
<div style="margin:15px 0; text-align:center;">$disp</div>
</div>

RREF: $answerbox[0]

Work upload: $answerbox[1]


// === ANSWER ===

$solutionguide
