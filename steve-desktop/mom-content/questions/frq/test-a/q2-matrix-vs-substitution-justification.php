// === NAME - DESCRIPTION: FRQ: Matrix vs Substitution for Systems - Justify using matrix methods vs substitution for a 3x3 linear system, and explain when the inverse fails. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
    array(
        "scenario"    => "business resource allocation",
        "desc"        => "A small company produces three products. Each product requires a certain number of labor hours, machine hours, and pounds of raw material. The company has a fixed supply of each resource available per week.",
        "system_desc" => "the three equations represent the resource constraints: one for labor hours, one for machine hours, and one for raw materials: each product appears in all three equations",
        "vars"        => "x, y, and z (units produced of each product)",
        "matrix_fit"  => "the system has three equations and three unknowns with consistent structure, which is exactly the situation matrix methods handle efficiently",
        "det_context" => "if one product's resource usage is a linear combination of the others, the rows of the coefficient matrix become dependent and the determinant equals zero"
    ),
    array(
        "scenario"    => "recipe scaling",
        "desc"        => "A caterer mixes three ingredients to prepare three different dishes. Each dish requires a specific amount of each ingredient. The caterer has a target quantity for each dish and needs to know how much of each ingredient to buy.",
        "system_desc" => "the three equations each represent a dish, and the unknowns are the amounts of the three ingredients purchased",
        "vars"        => "x, y, and z (pounds of each ingredient)",
        "matrix_fit"  => "all three ingredient amounts appear in each dish equation, giving a fully interconnected 3x3 system where matrix methods organize the relationships more clearly than substitution",
        "det_context" => "if two dishes use ingredients in exactly the same proportions, the corresponding rows are scalar multiples of each other and the determinant is zero"
    ),
    array(
        "scenario"    => "traffic flow at intersections",
        "desc"        => "City engineers model traffic flow through three intersections. At each intersection, the number of cars entering must equal the number leaving. This gives a system of three equations in three unknown flow rates.",
        "system_desc" => "each equation represents conservation of flow at one intersection, where three unknown road segments (x, y, z) connect the intersections",
        "vars"        => "x, y, and z (vehicles per hour on each road segment)",
        "matrix_fit"  => "the three flow equations link all three unknown road segments, creating a 3x3 system where Gauss-Jordan elimination or the inverse method finds all flow rates at once",
        "det_context" => "if the three intersection equations are not independent (for example, one is just a combination of the other two), the system has no unique solution and the determinant of the coefficient matrix is zero"
    )
);
$ci = rand(0, count($contexts)-1);
$ctx = $contexts[$ci];

$scenario    = $ctx["scenario"];
$desc        = $ctx["desc"];
$system_desc = $ctx["system_desc"];
$vars        = $ctx["vars"];
$matrix_fit  = $ctx["matrix_fit"];
$det_context = $ctx["det_context"];

/* ---------- Narrative Variables ---------- */
$r_method     = 'For this '.$scenario.' system, matrix methods work well because '.$matrix_fit.'. Writing the system as AX = B and solving with X = A^-1 * B (or using RREF) handles all three equations simultaneously';
$r_inv_fails  = 'The inverse method fails when the determinant of the coefficient matrix A equals zero. In this context, '.$det_context.'. When det(A) = 0, the matrix has no inverse and the system either has no solution or infinitely many solutions';
$r_comparison = 'Matrix methods shine when you have three or more equations because they scale well and can be set up and solved systematically. Substitution works better for smaller systems (two equations) or when one equation already isolates a variable, but it becomes messy and error-prone with three unknowns';

$sample_narrative = '<b>'.$r_method.'</b>. However, <b>'.$r_inv_fails.'</b>. Comparing the two approaches: <b>'.$r_comparison.'</b>.';

/* ---------- 2. SHARED CSS & JS ---------- */
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
    .ideal-ans { display: block; background-color: #e8f5e9; font-style: italic; font-weight: bold; font-size: 0.95em; margin: 5px 0 10px 0; border-left: 3px solid #4CAF50; padding-left: 8px; }
    .full-response-box { margin-top: 15px; border: 2px solid #4CAF50; background-color: #e8f5e9; padding: 15px; border-radius: 5px; }
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

/* ---------- 3. Student Rubric (Neutral Checklist) ---------- */
$rubricbutton = $css_block . '
<div class="rubric-container">
  <details>
    <summary>
      <span class="arrow-closed">&#9656;</span><span class="arrow-open">&#9662;</span>
      Click to View Grading Checklist
    </summary>
    <div class="rubric-content">
      <p style="margin:0 0 0.5em 0;"><b>Grading Criteria</b> -- ensure your explanation covers these points:</p>
      <table class="rubric-table">
        <tbody>
          <tr>
            <th class="col-header">Category</th>
            <th class="col-check">Requirement</th>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Method Choice<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain why a matrix method (inverse or RREF) is appropriate for this system.</label></li>
                <li><label><input type="checkbox"> Reference how the system is set up as a matrix equation (AX = B).</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>When Inverse Fails<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State the condition on the coefficient matrix that prevents the inverse method from working.</label></li>
                <li><label><input type="checkbox"> Connect that condition to what it means for the system of equations.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Comparison Reasoning<br>(4 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Describe a situation where matrix methods are preferable to substitution.</label></li>
                <li><label><input type="checkbox"> Describe a situation where substitution might be easier or more practical.</label></li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </details>
</div>';

/* ---------- 4. Instructor Rubric (With Answer Targets) ---------- */
$rubricanswerbutton = $css_block . '
<div class="rubric-container">
  <details>
    <summary>
      <span class="arrow-closed">&#9656;</span><span class="arrow-open">&#9662;</span>
      Rubric &amp; Model Response
    </summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr>
            <th class="col-header">Category</th>
            <th class="col-check">Checklist &amp; Ideal Targets</th>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Method Choice<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain why matrix methods work for this system.
                    <span class="ideal-ans">Target: "The system fits the AX = B form with a 3x3 coefficient matrix A, a variable vector X (the unknowns '.$vars.'), and a constants vector B. If A is invertible, X = A^-1 * B gives the unique solution. RREF on the augmented matrix [A|B] also works."</span></li>
                <li>Reference the matrix equation setup.
                    <span class="ideal-ans">Target: "Student explicitly names or describes AX = B, the coefficient matrix, or RREF/row reduction applied to the augmented matrix."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>When Inverse Fails<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>State the condition that blocks the inverse method.
                    <span class="ideal-ans">Target: "The inverse method fails when det(A) = 0, meaning the coefficient matrix A has no inverse (is singular)."</span></li>
                <li>Connect it to the system&#39;s behavior.
                    <span class="ideal-ans">Target: "In this context, '.$det_context.'. When det(A) = 0, the system either has no solution (inconsistent) or infinitely many solutions."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Comparison Reasoning<br>(4 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>When matrix methods are better.
                    <span class="ideal-ans">Target: "Matrix methods are preferable for three or more equations because they scale well, are systematic, and avoid the algebraic complexity of chaining substitutions."</span></li>
                <li>When substitution is easier.
                    <span class="ideal-ans">Target: "Substitution is simpler for two-equation systems or when one equation already isolates a single variable, making one direct substitution sufficient."</span></li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="full-response-box">
        <span style="color:#2E7D32; font-weight:bold;">Model Narrative Response:</span><br><br>
        '.$sample_narrative.'
      </div>
    </div>
  </details>
</div>';

/* ---------- 5. Question Text ---------- */
$questiontext = '
<div style="font-family:Arial; font-size:medium; line-height:1.6;">
<p>'.$desc.' This situation leads to a 3x3 linear system where the unknowns are '.$vars.', and '.$system_desc.'.</p>
<p>In 4 to 6 sentences, discuss how you would approach solving this system using matrix methods, and compare that approach to substitution.</p>
<p>In your explanation, be sure to cover:</p>
<ul>
<li>Why a matrix method such as X = A&#8315;&#185;B (inverse method) or RREF works for this type of system.</li>
<li>What condition on the coefficient matrix would prevent the inverse method from working, and what that means for the system.</li>
<li>When matrix methods are a better choice than substitution, and when substitution might be simpler.</li>
</ul>
'.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
