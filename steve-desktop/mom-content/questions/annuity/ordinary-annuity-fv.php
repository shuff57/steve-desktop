// === COMMON CONTROL ===
// Section 6.4 — Ordinary Annuity Future Value (single answer)

loadlibrary("finance")

// Scenario context
$contexts = array(
  array("retirement account", "saves", "retirement"),
  array("college savings plan", "deposits", "college tuition"),
  array("vacation fund", "sets aside", "a dream vacation"),
  array("emergency fund", "contributes", "emergencies"),
  array("new car fund", "deposits", "a new car")
)
$ci = rand(0, count($contexts)-1)
$ctx_name = $contexts[$ci][0]
$ctx_verb = $contexts[$ci][1]
$ctx_goal = $contexts[$ci][2]

// Payment amount
$m_choices = array(100, 150, 200, 250, 300, 400, 500, 600, 800)
$m = $m_choices[rand(0, count($m_choices)-1)]

// Rate
$rate_pcts = array(4, 5, 6, 7, 8, 9, 10, 12)
$rate_pct = $rate_pcts[rand(0, count($rate_pcts)-1)]

// Compounding frequency
$n_choices = array(12, 4, 2, 1)
$n_labels  = array("monthly", "quarterly", "semi-annually", "annually")
$ni = rand(0, 3)
$n = $n_choices[$ni]
$n_label = $n_labels[$ni]

// Time
$t_choices = array(5, 8, 10, 12, 15, 20, 25, 30)
$t = $t_choices[rand(0, count($t_choices)-1)]

// Derived values
$bigN = $n * $t
$FV = round(futureValue($rate_pct, $bigN, $n, -$m, 0), 2)

// Answer setup
$anstypes = array("number")
$answer[0] = $FV
$abstolerance = 0.01
$reqdecimals = "r2"
$ansprompt[0] = "Future value FV = $"

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
</script>'

$r_over_n = round($rate_pct / 100 / $n, 8)
$factor = round(((1 + $r_over_n)^$bigN - 1) / $r_over_n, 6)

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
          <tr class="row-colored">
            <td style="text-align:center;"><b>Given</b></td>
            <td>
              Payment m = $' . $m . '/period<br>
              Annual rate r = ' . $rate_pct . '%, compounded ' . $n_label . ' (n = ' . $n . ')<br>
              Time t = ' . $t . ' years &rarr; total periods N = ' . $bigN . '<br>
              <em>Ordinary annuity: deposits at end of each period</em>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Step 1</b></td>
            <td>
              Formula: FV = m &times; [(1 + r/n)<sup>N</sup> &minus; 1] / (r/n)<br>
              r/n = ' . $rate_pct . '% &divide; ' . $n . ' = ' . $r_over_n . '
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Step 2</b></td>
            <td>
              (1 + r/n)<sup>N</sup> = (1 + ' . $r_over_n . ')<sup>' . $bigN . '</sup><br>
              Factor = [(1 + r/n)<sup>N</sup> &minus; 1] / (r/n) &asymp; ' . $factor . '
            </td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Answer</b></td>
            <td class="col-check-bot">
              FV = ' . $m . ' &times; ' . $factor . '
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Future Value FV = $' . $FV . '</b>
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

<p><b>Ordinary Annuity — Future Value</b></p>
<p>Each $n_label, a person $ctx_verb <b>$$m</b> into a $ctx_name earning <b>$rate_pct%</b> per year compounded <b>$n_label</b>. Deposits are made at the <b>end</b> of each period (ordinary annuity).</p>
<p>What is the <b>future value</b> of this annuity after <b>$t years</b>?</p>

<p>$ansprompt[0] $answerbox[0]</p>

</div>

// === ANSWER ===

$solutionguide
