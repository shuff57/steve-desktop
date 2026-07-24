// === COMMON CONTROL ===
// Section 8.4 — Conditional Probability P(A|B) from a two-way table (2 number answers)

// Two-way table contexts
$contexts = array(
  array(
    "students", "grade level", "Freshman", "Sophomore", "Junior", "Senior",
    "owns a laptop", "does not own a laptop"
  ),
  array(
    "employees", "department", "Marketing", "Engineering", "HR", "Sales",
    "works remotely", "works on-site"
  ),
  array(
    "survey participants", "age group", "18–29", "30–44", "45–59", "60+",
    "prefers streaming", "prefers cable"
  ),
  array(
    "customers", "membership status", "Basic", "Silver", "Gold", "Platinum",
    "made a purchase this month", "did not make a purchase"
  )
)
$ci = rand(0, count($contexts)-1)
$ctx_noun   = $contexts[$ci][0]
$ctx_cat    = $contexts[$ci][1]
$ctx_r1     = $contexts[$ci][2]
$ctx_r2     = $contexts[$ci][3]
$ctx_r3     = $contexts[$ci][4]
$ctx_r4     = $contexts[$ci][5]
$ctx_col1   = $contexts[$ci][6]
$ctx_col2   = $contexts[$ci][7]

// Build 4x2 table from counts (4 row categories x 2 column categories)
// Each cell is a positive integer
$a11 = rand(4, 12)
$a12 = rand(2, 8)
$a21 = rand(5, 14)
$a22 = rand(3, 10)
$a31 = rand(4, 11)
$a32 = rand(2, 9)
$a41 = rand(3, 10)
$a42 = rand(2, 8)

// Row totals
$r1 = $a11 + $a12
$r2 = $a21 + $a22
$r3 = $a31 + $a32
$r4 = $a41 + $a42

// Column totals
$col1_total = $a11 + $a21 + $a31 + $a41
$col2_total = $a12 + $a22 + $a32 + $a42

// Grand total
$grand = $r1 + $r2 + $r3 + $r4

// Part A: P(col1 | row2) = a21 / r2
$p_cond_a = round($a21 / $r2, 4)

// Part B: P(row3 | col1) = a31 / col1_total
$p_cond_b = round($a31 / $col1_total, 4)

$answer[0] = $p_cond_a
$answer[1] = $p_cond_b
$anstypes = array("number", "number")
$abstolerance[0] = 0.0001
$abstolerance[1] = 0.0001
$ansprompt[0] = "P(col1 | row2) = "
$ansprompt[1] = "P(row3 | col1) = "

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
            <td style="text-align:center;"><b>Formula</b></td>
            <td>
              Conditional probability: P(A|B) = P(A &cap; B) / P(B) = n(A &cap; B) / n(B)<br>
              For table problems: restrict to the given row or column, then count.
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Part A<br>P(' . $ctx_col1 . ' | ' . $ctx_r2 . ')</b></td>
            <td>
              Condition: restrict to ' . $ctx_r2 . ' row &rarr; row total = ' . $r2 . '<br>
              Among ' . $ctx_r2 . ': ' . $a21 . ' ' . $ctx_col1 . '<br>
              P(' . $ctx_col1 . ' | ' . $ctx_r2 . ') = ' . $a21 . ' / ' . $r2 . '
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>P = ' . $p_cond_a . '</b>
              </div>
            </td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Part B<br>P(' . $ctx_r3 . ' | ' . $ctx_col1 . ')</b></td>
            <td class="col-check-bot">
              Condition: restrict to ' . $ctx_col1 . ' column &rarr; column total = ' . $col1_total . '<br>
              Among ' . $ctx_col1 . ': ' . $a31 . ' are ' . $ctx_r3 . '<br>
              P(' . $ctx_r3 . ' | ' . $ctx_col1 . ') = ' . $a31 . ' / ' . $col1_total . '
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>P = ' . $p_cond_b . '</b>
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

<p><b>Conditional Probability — Two-Way Table</b></p>
<p>The table below classifies <b>$grand $ctx_noun</b> by <em>$ctx_cat</em> and by whether they <em>$ctx_col1</em>.</p>

<table style="border-collapse:collapse; margin:10px 0; font-size:medium; width:100%;">
  <tr>
    <th style="border:1px solid #ccc; padding:8px 12px; background:#f2f2f2; text-align:left;">$ctx_cat</th>
    <th style="border:1px solid #ccc; padding:8px 12px; background:#f2f2f2; text-align:center;">$ctx_col1</th>
    <th style="border:1px solid #ccc; padding:8px 12px; background:#f2f2f2; text-align:center;">$ctx_col2</th>
    <th style="border:1px solid #ccc; padding:8px 12px; background:#f2f2f2; text-align:center;">Row Total</th>
  </tr>
  <tr>
    <td style="border:1px solid #ccc; padding:8px 12px;"><b>$ctx_r1</b></td>
    <td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">$a11</td>
    <td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">$a12</td>
    <td style="border:1px solid #ccc; padding:8px 12px; text-align:center;"><b>$r1</b></td>
  </tr>
  <tr style="background:#fff9ea;">
    <td style="border:1px solid #ccc; padding:8px 12px;"><b>$ctx_r2</b></td>
    <td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">$a21</td>
    <td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">$a22</td>
    <td style="border:1px solid #ccc; padding:8px 12px; text-align:center;"><b>$r2</b></td>
  </tr>
  <tr>
    <td style="border:1px solid #ccc; padding:8px 12px;"><b>$ctx_r3</b></td>
    <td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">$a31</td>
    <td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">$a32</td>
    <td style="border:1px solid #ccc; padding:8px 12px; text-align:center;"><b>$r3</b></td>
  </tr>
  <tr style="background:#fff9ea;">
    <td style="border:1px solid #ccc; padding:8px 12px;"><b>$ctx_r4</b></td>
    <td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">$a41</td>
    <td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">$a42</td>
    <td style="border:1px solid #ccc; padding:8px 12px; text-align:center;"><b>$r4</b></td>
  </tr>
  <tr style="background:#f2f2f2;">
    <td style="border:1px solid #ccc; padding:8px 12px;"><b>Column Total</b></td>
    <td style="border:1px solid #ccc; padding:8px 12px; text-align:center;"><b>$col1_total</b></td>
    <td style="border:1px solid #ccc; padding:8px 12px; text-align:center;"><b>$col2_total</b></td>
    <td style="border:1px solid #ccc; padding:8px 12px; text-align:center;"><b>$grand</b></td>
  </tr>
</table>

<p>One person is selected at random. Give all answers as decimals rounded to 4 decimal places.</p>

<p><b>Part A.</b> Given that the selected person is a <b>$ctx_r2</b>, what is the probability that they <em>$ctx_col1</em>?</p>
<p>P($ctx_col1 | $ctx_r2) = $answerbox[0]</p>

<p><b>Part B.</b> Given that the selected person <em>$ctx_col1</em>, what is the probability they are a <b>$ctx_r3</b>?</p>
<p>P($ctx_r3 | $ctx_col1) = $answerbox[1]</p>

</div>

// === ANSWER ===

$solutionguide
