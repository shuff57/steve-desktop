// === NAME - DESCRIPTION: FRQ: Linear Modeling Justification - Explain slope, intercept, variable roles, and model fit in a real-world linear scenario. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
    array(
        "scenario"   => "gym membership",
        "intro"      => "A local gym charges a one-time sign-up fee plus a fixed monthly rate. After signing up, a member tracks their total amount paid over the first several months.",
        "indep"      => "number of months since sign-up",
        "dep"        => "total amount paid (in dollars)",
        "slope_val"  => "$40",
        "int_val"    => "$60",
        "slope_ctx"  => "each additional month costs $40 in membership fees",
        "int_ctx"    => "the $60 one-time sign-up fee is paid before any months begin",
        "linear_why" => "the monthly rate is constant, the same $40 is added each month, so the total grows at a steady rate, which is exactly what a linear model captures"
    ),
    array(
        "scenario"   => "taxi fare",
        "intro"      => "A taxi company charges a flat base fare when you get in, then a fixed rate per mile driven. A rider records total cost for several different trip distances.",
        "indep"      => "distance traveled (in miles)",
        "dep"        => "total fare (in dollars)",
        "slope_val"  => "$2.50",
        "int_val"    => "$3.00",
        "slope_ctx"  => "each additional mile driven adds $2.50 to the fare",
        "int_ctx"    => "the $3.00 base fare is charged the moment you enter the cab, before traveling any distance",
        "linear_why" => "the per-mile rate is constant throughout the trip, so the fare increases by the same amount for every mile added, making a linear model appropriate"
    ),
    array(
        "scenario"   => "water tank drainage",
        "intro"      => "A large water tank starts full and drains at a constant rate through a valve. A technician measures the volume remaining every hour.",
        "indep"      => "time elapsed (in hours)",
        "dep"        => "volume of water remaining (in gallons)",
        "slope_val"  => "-150",
        "int_val"    => "2400",
        "slope_ctx"  => "the tank loses 150 gallons per hour while draining",
        "int_ctx"    => "at time zero (before any draining), the tank holds 2400 gallons",
        "linear_why" => "the drain rate is constant, so the volume decreases by the same 150 gallons every hour, which produces a perfectly straight-line relationship"
    )
);
$ci = rand(0, count($contexts)-1);
$ctx = $contexts[$ci];

$scenario   = $ctx["scenario"];
$intro      = $ctx["intro"];
$indep      = $ctx["indep"];
$dep        = $ctx["dep"];
$slope_val  = $ctx["slope_val"];
$int_val    = $ctx["int_val"];
$slope_ctx  = $ctx["slope_ctx"];
$int_ctx    = $ctx["int_ctx"];
$linear_why = $ctx["linear_why"];

/* ---------- Narrative Variables ---------- */
$r_variables   = 'The independent variable is the '.$indep.' and the dependent variable is the '.$dep.': the cost (or volume) depends on how much time or distance has passed';
$r_slope       = 'The slope of '.$slope_val.' means that '.$slope_ctx.', so it represents the constant rate of change in this situation';
$r_intercept   = 'The y-intercept of '.$int_val.' means '.$int_ctx.', which is the value of the dependent variable when the independent variable equals zero';
$r_model       = $linear_why . ', so a linear model is a good fit; the main limitation is that it assumes the rate stays constant forever, which may not hold in reality';

$sample_narrative = 'In this '.$scenario.' scenario, <b>'.$r_variables.'</b>. <b>'.$r_slope.'</b>. <b>'.$r_intercept.'</b>. As for the model itself, <b>'.$r_model.'</b>.';

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
            <td style="text-align:center;"><b>Variable Identification<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Name the independent variable and the dependent variable.</label></li>
                <li><label><input type="checkbox"> Explain which one depends on the other in this scenario.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Slope Interpretation<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State what the slope value represents in the context of the scenario.</label></li>
                <li><label><input type="checkbox"> Connect the slope to the idea of rate of change.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Intercept Interpretation<br>(2 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain what the y-intercept means when the independent variable equals zero.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Model Justification<br>(2 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain why a linear model fits (or describe a limitation of using it).</label></li>
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
            <td style="text-align:center;"><b>Variable Identification<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Name the independent and dependent variables.
                    <span class="ideal-ans">Target: "Independent variable is '.$indep.'; dependent variable is '.$dep.'."</span></li>
                <li>Explain the dependency relationship.
                    <span class="ideal-ans">Target: "The '.$dep.' depends on the '.$indep.': as the independent variable changes, the dependent variable responds."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Slope Interpretation<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>State what the slope means in context.
                    <span class="ideal-ans">Target: "The slope of '.$slope_val.' means '.$slope_ctx.'."</span></li>
                <li>Connect slope to rate of change.
                    <span class="ideal-ans">Target: "For each one-unit increase in the independent variable, the dependent variable changes by '.$slope_val.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Intercept Interpretation<br>(2 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain what the y-intercept means at x = 0.
                    <span class="ideal-ans">Target: "The y-intercept of '.$int_val.' means '.$int_ctx.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Model Justification<br>(2 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain why a linear model fits (or name a limitation).
                    <span class="ideal-ans">Target: "'.$linear_why.'."</span></li>
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
<p>'.$intro.' The data turns out to be perfectly linear, with a slope of '.$slope_val.' and a y-intercept of '.$int_val.'.</p>
<p>In 4 to 6 sentences, explain what this linear model is telling you about the '.$scenario.' situation.</p>
<p>In your explanation, be sure to cover:</p>
<ul>
<li>Which variable is independent and which is dependent, and why.</li>
<li>What the slope of '.$slope_val.' means in the context of this scenario.</li>
<li>What the y-intercept of '.$int_val.' represents when the independent variable equals zero.</li>
<li>Why a linear model is a reasonable fit for this situation, or what its limitations might be.</li>
</ul>
'.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
