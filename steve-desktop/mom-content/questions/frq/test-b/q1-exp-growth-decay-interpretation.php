// === NAME - DESCRIPTION: FRQ: Exponential Growth and Decay Interpretation - Explain A(t) = A0 * b^t in context: initial value, base meaning, time-specific value, and model limitation. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
    array(
        "scenario"    => "bacterial growth in a lab culture",
        "A0"          => 500,
        "A0_unit"     => "bacteria",
        "b"           => 1.3,
        "b_type"      => "growth",
        "b_pct"       => "30%",
        "t_unit"      => "hour",
        "t_val"       => 4,
        "At_val"      => "1432",
        "At_interp"   => "there are approximately 1432 bacteria in the culture after 4 hours",
        "limit_note"  => "nutrients become depleted and the culture cannot sustain unlimited exponential growth indefinitely"
    ),
    array(
        "scenario"    => "a pain medication wearing off in the bloodstream",
        "A0"          => 200,
        "A0_unit"     => "mg",
        "b"           => 0.75,
        "b_type"      => "decay",
        "b_pct"       => "25%",
        "t_unit"      => "hour",
        "t_val"       => 3,
        "At_val"      => "84.4",
        "At_interp"   => "approximately 84.4 mg of the medication remains in the bloodstream after 3 hours",
        "limit_note"  => "the body may metabolize the drug at a rate that changes over time, or the drug interacts with food and other substances in ways the simple model ignores"
    ),
    array(
        "scenario"    => "a savings account earning compound interest",
        "A0"          => 1000,
        "A0_unit"     => "dollars",
        "b"           => 1.05,
        "b_type"      => "growth",
        "b_pct"       => "5%",
        "t_unit"      => "year",
        "t_val"       => 6,
        "At_val"      => "1340.10",
        "At_interp"   => "the account balance is approximately $1340.10 after 6 years",
        "limit_note"  => "interest rates can change over time and the model assumes a fixed rate, so the prediction becomes less reliable the further out you project"
    )
)
$i = rand(0, 2)
$c = $contexts[$i]
$scenario  = $c["scenario"]
$A0        = $c["A0"]
$A0_unit   = $c["A0_unit"]
$b         = $c["b"]
$b_type    = $c["b_type"]
$b_pct     = $c["b_pct"]
$t_unit    = $c["t_unit"]
$t_val     = $c["t_val"]
$At_val    = $c["At_val"]
$At_interp = $c["At_interp"]
$limit_note = $c["limit_note"]

/* ---------- Narrative Variables ---------- */
$r_A0 = "A0 = $A0 $A0_unit is the <b>initial value</b>: the amount present at time t = 0, before any growth or decay has occurred"
$r_base = "the base b = $b indicates <b>$b_type</b>: since b is " . ($b_type == "growth" ? "greater than 1" : "between 0 and 1") . ", the quantity $b_type. Each $t_unit, the amount is multiplied by $b, which corresponds to a $b_pct " . ($b_type == "growth" ? "increase" : "decrease") . " per $t_unit"
$r_tval = "A($t_val) = $A0 x $b^$t_val = $At_val $A0_unit, meaning $At_interp"
$r_limit = "one key limitation is that $limit_note, so the exponential model is only reasonable over a limited time window"

$sample_narrative = "In this model for $scenario, <b>$r_A0</b>. The base tells us the rate: <b>$r_base</b>. Plugging in t = $t_val gives <b>$r_tval</b>. However, <b>$r_limit</b>."

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
            <td style="text-align:center;"><b>Initial Value Meaning<br>(2 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain what A0 represents in this specific context.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Base Interpretation<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> State what the base b tells you about the rate of change per time unit.</label></li>
                <li><label><input type="checkbox"> Identify whether the model represents growth or decay, and justify using the value of b.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Time-Specific Value<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Compute A(t) at the given time value.</label></li>
                <li><label><input type="checkbox"> Interpret the result in plain English within this context.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Model Limitation<br>(2 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Describe at least one realistic reason the exponential model may not hold in the long run.</label></li>
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
            <td style="text-align:center;"><b>Initial Value Meaning<br>(2 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Explain what A0 represents in context.
                    <span class="ideal-ans">Target: "A0 = '.$A0.' '.$A0_unit.' is the initial amount at time t = 0, before any '.$b_type.' has occurred in the context of '.$scenario.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Base Interpretation<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Rate of change per time unit.
                    <span class="ideal-ans">Target: "Each '.$t_unit.', the quantity is multiplied by '.$b.', representing a '.$b_pct.' '.$b_type.' per '.$t_unit.'."</span></li>
                <li>Growth or decay identification.
                    <span class="ideal-ans">Target: "Because b = '.$b.' is ' . ($b_type == 'growth' ? 'greater than 1' : 'between 0 and 1') . ', the model describes '.$b_type.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Time-Specific Value<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Compute A('.$t_val.').
                    <span class="ideal-ans">Target: "A('.$t_val.') = '.$A0.' x '.$b.'^'.$t_val.' = '.$At_val.' '.$A0_unit.'."</span></li>
                <li>Interpret the result.
                    <span class="ideal-ans">Target: "This means '.$At_interp.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Model Limitation<br>(2 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>A realistic long-run limitation.
                    <span class="ideal-ans">Target: "In this context, '.$limit_note.', so the model only applies over a limited time range."</span></li>
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
<p>The following exponential model describes <b>'.$scenario.'</b>:</p>
<p style="text-align:center; font-size:1.1em;"><b>A(t) = '.$A0.' &times; '.$b.'^t</b></p>
<p>where t is measured in '.$t_unit.'s and A(t) is measured in '.$A0_unit.'.</p>
<p>Write 4-6 sentences explaining what this model tells you. Use specific numbers from the formula and interpret them in the context of '.$scenario.'.</p>
<p>In your explanation, be sure to cover:</p>
<ul>
<li>What A0 = '.$A0.' represents in this situation.</li>
<li>What the base b = '.$b.' tells you about how the quantity changes each '.$t_unit.' (and whether this is growth or decay).</li>
<li>The value of A('.$t_val.') and what it means in context.</li>
<li>At least one reason why this exponential model might not be accurate in the very long run.</li>
</ul>
'.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
