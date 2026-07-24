// === NAME - DESCRIPTION: FRQ: Compound vs Simple Interest Comparison - Compare I = Prt and A = P(1+r/n)^(nt) in a real-world investment context, explaining compounding advantage, frequency, simple-interest use cases, and time horizon effects. ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
    array(
        "scenario"     => "starting your own small business",
        "P"            => 5000,
        "r_pct"        => "6%",
        "r_dec"        => 0.06,
        "t"            => 10,
        "n_annual"     => 1,
        "n_monthly"    => 12,
        "SI_total"     => 3000,
        "SI_final"     => 8000,
        "CI_annual"    => "8954.24",
        "CI_monthly"   => "9096.98",
        "use_case"     => "a short-term business loan where the lender charges simple interest, making the total cost easy to predict up front"
    ),
    array(
        "scenario"     => "saving for a down payment on a home",
        "P"            => 8000,
        "r_pct"        => "4%",
        "r_dec"        => 0.04,
        "t"            => 8,
        "n_annual"     => 1,
        "n_monthly"    => 12,
        "SI_total"     => 2560,
        "SI_final"     => 10560,
        "CI_annual"    => "10946.37",
        "CI_monthly"   => "11021.66",
        "use_case"     => "a simple-interest car loan or a store installment plan where the payment schedule is fixed and the interest does not compound"
    ),
    array(
        "scenario"     => "building a retirement nest egg",
        "P"            => 3000,
        "r_pct"        => "7%",
        "r_dec"        => 0.07,
        "t"            => 20,
        "n_annual"     => 1,
        "n_monthly"    => 12,
        "SI_total"     => 4200,
        "SI_final"     => 7200,
        "CI_annual"    => "11613.84",
        "CI_monthly"   => "12055.17",
        "use_case"     => "a straightforward personal loan between friends or family, where simple interest keeps the math transparent and avoids confusion over compounding schedules"
    )
)
$i = rand(0, 2)
$c = $contexts[$i]
$scenario    = $c["scenario"]
$P           = $c["P"]
$r_pct       = $c["r_pct"]
$r_dec       = $c["r_dec"]
$t           = $c["t"]
$n_annual    = $c["n_annual"]
$n_monthly   = $c["n_monthly"]
$SI_total    = $c["SI_total"]
$SI_final    = $c["SI_final"]
$CI_annual   = $c["CI_annual"]
$CI_monthly  = $c["CI_monthly"]
$use_case    = $c["use_case"]

/* ---------- Narrative Variables ---------- */
$r_compound_adv = "compound interest grows faster because each period it earns interest on the <b>accumulated balance</b>, not just the original principal. With simple interest, the $SI_total in interest is always computed on the same $P base, giving a final balance of $$SI_final. With compound interest (annual), the final balance is $$CI_annual, which is more because each year's earned interest is folded back in and starts earning interest of its own"
$r_freq = "compounding more frequently accelerates growth further: compounding annually gives $$CI_annual, while compounding monthly gives $$CI_monthly. The more compounding periods per year, the more often interest gets added to the balance and starts earning more interest, so the final amount keeps climbing"
$r_simple_cases = "simple interest can still be the right choice in some real-world situations, such as $use_case"
$r_time_horizon = "over $t years, the gap between simple and compound interest is already substantial, but over a much longer horizon the difference becomes enormous because compounding is exponential while simple interest is linear. The longer the time period, the more the exponential compounding pulls ahead"

$sample_narrative = "When saving for $scenario, <b>$r_compound_adv</b>. Compounding frequency matters too: <b>$r_freq</b>. That said, <b>$r_simple_cases</b>. Finally, <b>$r_time_horizon</b>."

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
            <td style="text-align:center;"><b>Compound Advantage<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain why compound interest produces a higher final balance than simple interest over the same period.</label></li>
                <li><label><input type="checkbox"> Reference the specific numbers given to support the comparison.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Compounding Frequency<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Explain what the compounding frequency n represents in the formula.</label></li>
                <li><label><input type="checkbox"> Describe how changing n (annual vs monthly vs daily) affects the final amount.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Simple Interest Use Cases<br>(2 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Give a realistic example where simple interest might be preferable or more common in practice.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Time Horizon Effect<br>(2 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Describe how the length of time affects the size of the gap between compound and simple interest.</label></li>
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
            <td style="text-align:center;"><b>Compound Advantage<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Why compound beats simple.
                    <span class="ideal-ans">Target: "Compound interest earns interest on interest: each period the accrued interest is added to the balance and starts earning more. Simple interest always applies only to the original $'.$P.', giving a final balance of $'.$SI_final.', while compounding annually gives $'.$CI_annual.'."</span></li>
                <li>Use of specific numbers.
                    <span class="ideal-ans">Target: "Student references P = $'.$P.', r = '.$r_pct.', t = '.$t.' years, and compares the two final balances."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Compounding Frequency<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>What n means.
                    <span class="ideal-ans">Target: "n is the number of times per year interest is compounded and added to the balance. Annual means n = 1; monthly means n = 12."</span></li>
                <li>Effect of increasing n.
                    <span class="ideal-ans">Target: "Compounding monthly (n = 12) gives $'.$CI_monthly.' vs $'.$CI_annual.' annually. More frequent compounding means interest compounds on a slightly larger balance each time, so the final amount is higher."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Simple Interest Use Cases<br>(2 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>A realistic simple-interest scenario.
                    <span class="ideal-ans">Target: "Simple interest applies in situations like '.$use_case.'."</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>Time Horizon Effect<br>(2 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>How time magnifies the difference.
                    <span class="ideal-ans">Target: "The longer the time period, the larger the gap between compound and simple interest, because compound interest grows exponentially while simple interest grows linearly. Over '.$t.' years the gap is already noticeable; over 30 or 40 years it becomes dramatic."</span></li>
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
<p>You are considering two ways to invest <b>$'.$P.'</b> for <b>'.$t.' years</b> at an annual interest rate of <b>'.$r_pct.'</b>, in the context of <b>'.$scenario.'</b>.</p>
<ul>
<li><b>Option A (Simple Interest):</b> I = Prt &nbsp;&nbsp; Final balance = $'.$SI_final.' after '.$t.' years.</li>
<li><b>Option B (Compound Interest, annual):</b> A = P(1 + r/n)^(nt) &nbsp;&nbsp; Final balance = $'.$CI_annual.' after '.$t.' years.</li>
<li><b>Option C (Compound Interest, monthly, n = 12):</b> Final balance = $'.$CI_monthly.' after '.$t.' years.</li>
</ul>
<p>Write 4-6 sentences comparing these two approaches. You do not need to re-derive the formulas, but you should use the numbers above to support your points.</p>
<p>In your explanation, be sure to cover:</p>
<ul>
<li>Why compound interest grows faster than simple interest over the same period.</li>
<li>What the compounding frequency n means and how changing it (annual vs monthly) affects the outcome.</li>
<li>A real-world situation where you might still prefer or encounter simple interest in a contract.</li>
<li>How the length of time affects the size of the difference between the two approaches.</li>
</ul>
'.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
