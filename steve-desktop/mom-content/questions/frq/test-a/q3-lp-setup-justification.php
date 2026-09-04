// === NAME - DESCRIPTION: FRQ: LP Setup Justification - Define decision variables, write the objective function, and explain constraints for a linear programming scenario. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
    array(
        "scenario"      => "bakery production",
        "intro"         => "A bakery makes two products: loaves of sourdough bread and blueberry muffin packs. Each loaf requires 3 cups of flour and 30 minutes of oven time. Each muffin pack requires 2 cups of flour and 45 minutes of oven time. The bakery has 120 cups of flour and 18 hours of oven time available each day. Sourdough earns $8 profit per loaf and muffins earn $6 profit per pack.",
        "var_x"         => "x = number of sourdough loaves produced per day",
        "var_y"         => "y = number of muffin packs produced per day",
        "obj_words"     => "maximize total daily profit",
        "obj_func"      => "P = 8x + 6y",
        "obj_explain"   => "each loaf contributes $8 and each muffin pack contributes $6, so the total profit is 8 times the number of loaves plus 6 times the number of packs",
        "constraint1"   => "3x + 2y is at most 120 (flour supply: each loaf uses 3 cups, each muffin pack uses 2 cups, and only 120 cups are available)",
        "constraint2"   => "30x + 45y is at most 1080 (oven time: each loaf takes 30 minutes, each muffin pack takes 45 minutes, and 18 hours equals 1080 minutes total)",
        "nonneg"        => "x and y must both be at least 0 because you cannot produce a negative number of loaves or muffin packs"
    ),
    array(
        "scenario"      => "t-shirt printing",
        "intro"         => "A print shop makes two styles of custom t-shirts: basic tees and premium tees. Each basic tee requires 10 minutes of printing time and 1 unit of ink. Each premium tee requires 25 minutes of printing time and 3 units of ink. The shop has 500 minutes of printing time and 90 units of ink available per shift. Basic tees earn $5 profit each and premium tees earn $12 profit each.",
        "var_x"         => "x = number of basic tees printed per shift",
        "var_y"         => "y = number of premium tees printed per shift",
        "obj_words"     => "maximize total profit per shift",
        "obj_func"      => "P = 5x + 12y",
        "obj_explain"   => "each basic tee earns $5 and each premium tee earns $12, so total profit is 5 times the number of basics plus 12 times the number of premiums",
        "constraint1"   => "10x + 25y is at most 500 (printing time: basic tees take 10 minutes each, premium tees take 25 minutes each, and only 500 minutes are available per shift)",
        "constraint2"   => "x + 3y is at most 90 (ink supply: each basic tee uses 1 unit of ink and each premium tee uses 3 units, with 90 units available)",
        "nonneg"        => "x and y must both be at least 0 because it is not possible to print a negative number of shirts"
    ),
    array(
        "scenario"      => "smoothie shop",
        "intro"         => "A smoothie shop makes two menu items: a Berry Blast smoothie and a Green Machine smoothie. Each Berry Blast uses 2 oz of protein powder and 8 oz of frozen fruit. Each Green Machine uses 1 oz of protein powder and 12 oz of frozen fruit. The shop has 60 oz of protein powder and 360 oz of frozen fruit available each morning. Berry Blast earns $4 profit per cup and Green Machine earns $3 profit per cup.",
        "var_x"         => "x = number of Berry Blast smoothies made each morning",
        "var_y"         => "y = number of Green Machine smoothies made each morning",
        "obj_words"     => "maximize total morning profit",
        "obj_func"      => "P = 4x + 3y",
        "obj_explain"   => "each Berry Blast earns $4 and each Green Machine earns $3, so total profit is 4 times the number of Berry Blasts plus 3 times the number of Green Machines",
        "constraint1"   => "2x + y is at most 60 (protein powder: Berry Blast uses 2 oz each, Green Machine uses 1 oz each, with 60 oz available)",
        "constraint2"   => "8x + 12y is at most 360 (frozen fruit: Berry Blast uses 8 oz each, Green Machine uses 12 oz each, with 360 oz available)",
        "nonneg"        => "x and y must both be at least 0 because the shop cannot make a negative number of smoothies"
    )
);
$ci = rand(0, count($contexts)-1);
$ctx = $contexts[$ci];

$scenario    = $ctx["scenario"];
$intro       = $ctx["intro"];
$var_x       = $ctx["var_x"];
$var_y       = $ctx["var_y"];
$obj_words   = $ctx["obj_words"];
$obj_func    = $ctx["obj_func"];
$obj_explain = $ctx["obj_explain"];
$constraint1 = $ctx["constraint1"];
$constraint2 = $ctx["constraint2"];
$nonneg      = $ctx["nonneg"];

/* ---------- Narrative Variables ---------- */
$r_vars       = 'The two decision variables are: '.$var_x.', and '.$var_y.'. These are the quantities we control to optimize the outcome';
$r_objective  = 'The goal is to '.$obj_words.', captured by the objective function '.$obj_func.'. This works because '.$obj_explain;
$r_constraints = 'The resource constraints are: '.$constraint1.'. The second constraint is: '.$constraint2.'. Each constraint limits how many of each product can be made given the available resources';
$r_nonneg     = $nonneg.', so we add the non-negativity constraints x is at least 0 and y is at least 0. These are always required in LP to keep the solution physically meaningful';

$sample_narrative = '<b>'.$r_vars.'</b>. <b>'.$r_objective.'</b>. <b>'.$r_constraints.'</b>. Finally, <b>'.$r_nonneg.'</b>.';

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
            <td style="text-align:center;"><b>Decision Variables<br>(2 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Define both decision variables clearly, including units.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Objective Function<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State the goal of the optimization in words (maximize or minimize what?).</label></li>
                <li><label><input type="checkbox"> Write the objective function and explain how each coefficient connects to the scenario.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Constraints<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Identify each constraint and name the limited resource it represents.</label></li>
                <li><label><input type="checkbox"> Explain what both sides of each constraint inequality mean in context.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Non-Negativity<br>(2 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State both non-negativity constraints and explain why they are required.</label></li>
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
            <td style="text-align:center;"><b>Decision Variables<br>(2 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Define both decision variables with units.
                    <span class="ideal-ans">Target: "'.$var_x.' and '.$var_y.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Objective Function<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>State the goal in words.
                    <span class="ideal-ans">Target: "The goal is to '.$obj_words.'."</span></li>
                <li>Write the objective function and explain the coefficients.
                    <span class="ideal-ans">Target: "'.$obj_func.': '.$obj_explain.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Constraints<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Identify each constraint and the resource it limits.
                    <span class="ideal-ans">Target: "Constraint 1: '.$constraint1.'. Constraint 2: '.$constraint2.'."</span></li>
                <li>Explain both sides of each inequality.
                    <span class="ideal-ans">Target: "The left side totals resource usage across both products; the right side is the maximum available: so usage cannot exceed supply."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Non-Negativity<br>(2 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>State both non-negativity constraints and explain why.
                    <span class="ideal-ans">Target: "'.$nonneg.', so we require x is at least 0 and y is at least 0."</span></li>
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
<p>'.$intro.'</p>
<p>In 4 to 6 sentences, set up the linear programming model for this '.$scenario.' situation and explain each component.</p>
<p>In your explanation, be sure to cover:</p>
<ul>
<li>What your decision variables are and what each one represents (include units).</li>
<li>The objective function in words and as an equation, with an explanation of how the coefficients come from the scenario.</li>
<li>Each constraint inequality, the limited resource it represents, and what both sides of the inequality mean.</li>
<li>The non-negativity constraints and why they are always required in linear programming problems.</li>
</ul>
'.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
