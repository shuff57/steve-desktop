//common control (mandatory)

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array("annual household income", "number of text messages sent per day", "customer wait times at a call center");
$i = rand(0, 2);
$topic = $contexts[$i];

// Narrative Variables for the Model Answer
$r_process = "repeatedly taking random samples and calculating the average (mean) for each one";
$r_mechanism = "the act of averaging dilutes the extreme outliers found in the skewed population";
$r_result = "the resulting graph of sample means forms a Normal distribution (Bell Curve)";

$sample_narrative = "The Central Limit Theorem describes a specific process: We start by <b>$r_process</b>. We then plot these averages on a graph. Even though the original population of $topic is skewed, the distribution of these sample means becomes Normal. This happens because <b>$r_mechanism</b>. As we add more averages to our plot, <b>$r_result</b>.";

/* ---------- 2. SHARED CSS & JS ---------- */
$css_block = '
<style>
    /* Container & Details */
    .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
    .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
    
    /* Summary styling */
    .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; border:none; }
    .rubric-container details[open] summary { box-shadow: inset 0 -1px 0 #ccc; }
    .rubric-container summary::-webkit-details-marker { display:none; }
    
    /* Arrows */
    .arrow-open { display:none; }
    .rubric-container details[open] .arrow-closed { display:none; }
    .rubric-container details[open] .arrow-open { display:inline; }

    /* Content Animation Wrapper */
    .rubric-content { overflow:hidden; max-height:0; opacity:0; transition:max-height 300ms ease-out, opacity 300ms ease-out, padding 200ms ease-out; margin-top:0; background:#fafafa; box-sizing:border-box; padding:0 0.75em; }
    .rubric-container details[open] .rubric-content { max-height:2000px; opacity:1; padding:0.75em; }

    /* Table Styling */
    .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
    .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
    .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; user-select:text; }
    
    /* Theme Colors */
    .row-colored { background:#fff9ea; }
    .col-header { width:25%; border-top-left-radius:8px; }
    .col-check { border-top-right-radius:8px; }
    .col-cat-bot { border-bottom-left-radius:8px; }
    .col-check-bot { border-bottom-right-radius:8px; }

    /* Answer Key Specifics */
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

/* ---------- 3. Student Rubric (Unbiased / Neutral) ---------- */
$rubricbutton = $css_block . '
<div class="rubric-container">
  <details>
    <summary>
      <span class="arrow-closed">▸</span><span class="arrow-open">▾</span> 
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
            <td style="text-align:center;"><b>The Procedure</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Describe the action taken regarding the population (the sampling method).</label></li>
                <li><label><input type="checkbox"> Identify the specific statistic calculated for each sample.</label></li>
              </ul>
            </td>
          </tr>

          <tr class="row-colored">
            <td style="text-align:center;"><b>The Transformation</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                 <li><label><input type="checkbox"> Explain why the shape of the data changes (the effect of the calculation identified above).</label></li>
              </ul>
            </td>
          </tr>

          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>The Outcome</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Describe the final shape of the distribution of these statistics.</label></li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </details>
</div>';

/* ---------- 4. Instructor Rubric (Explicit Answers) ---------- */
$rubricanswerbutton = $css_block . '
<div class="rubric-container">
  <details>
    <summary>
      <span class="arrow-closed">▸</span><span class="arrow-open">▾</span> 
      Rubric & Model Response
    </summary>
    <div class="rubric-content">
      
      <table class="rubric-table">
        <tbody>
          <tr>
            <th class="col-header">Category</th>
            <th class="col-check">Checklist & Ideal Targets</th>
          </tr>

          <tr class="row-colored">
            <td style="text-align:center;"><b>The Procedure</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Sampling Step. 
                    <span class="ideal-ans">Target: "Take many random samples."</span></li>
                <li>Calculation Step. 
                    <span class="ideal-ans">Target: "Calculate the MEAN (average) of each sample."</span></li>
              </ul>
            </td>
          </tr>

          <tr class="row-colored">
            <td style="text-align:center;"><b>The Transformation</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Mechanism. 
                    <span class="ideal-ans">Target: "Averaging dilutes outliers/extremes."</span></li>
              </ul>
            </td>
          </tr>

          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>The Outcome</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Shape. 
                    <span class="ideal-ans">Target: "The distribution becomes Normal / Bell-shaped."</span></li>
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
  <p>Imagine you are studying a population that is heavily skewed (for example, '.$topic.', where most data points are low but a few are extremely high).</p> 
  
  <p>The Central Limit Theorem (CLT) explains how we can derive a Normal Bell Curve from this skewed data.</p>
  
  

  <p><b>Essay Prompt:</b><br>
  Describe the <b>process</b> described by the Central Limit Theorem. Specifically, explain the steps you would take to transform the raw, skewed population data into a symmetric Normal Distribution. </p>
  
  <p>In your explanation, be sure to cover:</p>
  <ul>
    <li>What you do with the samples.</li>
    <li>What calculation you perform on each sample.</li>
    <li>Why this process creates a bell curve shape even if the original people/data were skewed.</li>
  </ul>

  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton