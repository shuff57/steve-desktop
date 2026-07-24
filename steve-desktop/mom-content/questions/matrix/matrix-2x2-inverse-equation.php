// === NAME - DESCRIPTION: Matrix Inverse & Equation (2x2) - Find A^{-1} then solve AX=B using the inverse method ===

// === COMMON CONTROL ===

loadlibrary("matrix")

$m,$mi = matrixrandinvertible(2)
$md = matrixformat($m)

$x = matrix(nonzerodiffrands(-5,5,2),2,1)
$b = matrixprod($m,$x)
$bd = matrixformat($b)

$anstypes = array("matrix", "matrix", "file")
$answer[0] = matrixformat($mi)
$answersize[0] = "2,2"
$answer[1] = matrixformat($x)
$answersize[1] = "2,1"
$answerformat[2] = "images,.pdf"
// Omitting $scoremethod[2] leaves the upload scored as 0 until manually graded.
// To auto-grant full credit for any submission, add: $scoremethod[2] = "takeanything"

// --- Solution guide ---
$mid = matrixformat($mi)
$xd = matrixformat($x)

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
            <td>AX = B<br><b>A</b> = ' . $md . '&nbsp;&nbsp;&nbsp;<b>B</b> = ' . $bd . '</td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Step 1</b></td>
            <td>Compute A<sup>&minus;1</sup><br><b>A<sup>&minus;1</sup></b> = ' . $mid . '</td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Step 2</b></td>
            <td class="col-check-bot">Multiply X = A<sup>&minus;1</sup>B
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>X</b> = ' . $xd . '
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

Solve the matrix equation `A X = B` for `X` by finding `A^{-1}`. Upload a photo of your hand-written work.

`A = $md, \ \ B = $bd`

`A^{-1}` = $answerbox[0]

`X` = $answerbox[1]

Work upload: $answerbox[2]

// === ANSWER ===

$solutionguide
