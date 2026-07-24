// === COMMON CONTROL ===
// Sections 8.1–8.2 — Sample space, P(event), complement (3 number answers)

// Concrete contexts: bag of colored marbles
$contexts = array(
  array("red", "blue", "green", "yellow"),
  array("red", "white", "blue", "green"),
  array("orange", "purple", "red", "white"),
  array("black", "white", "gray", "red")
)
$ci = rand(0, count($contexts)-1)
$col1 = $contexts[$ci][0]
$col2 = $contexts[$ci][1]
$col3 = $contexts[$ci][2]
$col4 = $contexts[$ci][3]

// Counts for each color (positive integers)
$n1 = rand(3, 8)
$n2 = rand(4, 10)
$n3 = rand(2, 7)
$n4 = rand(3, 9)
$total = $n1 + $n2 + $n3 + $n4

// Part A: P(col1) — single color
$ans_a = round($n1 / $total, 4)

// Part B: P(col2 or col3) — union of two mutually exclusive events
$ans_b = round(($n2 + $n3) / $total, 4)

// Part C: P(not col1) — complement
$ans_c = round(1 - $n1 / $total, 4)

$anstypes = array("number", "number", "number")
$answer[0] = $ans_a
$answer[1] = $ans_b
$answer[2] = $ans_c
$abstolerance[0] = 0.0001
$abstolerance[1] = 0.0001
$abstolerance[2] = 0.0001
$ansprompt[0] = "P(A) = "
$ansprompt[1] = "P(B or C) = "
$ansprompt[2] = "P(not A) = "

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
            <td style="text-align:center;"><b>Sample Space</b></td>
            <td>
              Total marbles n(S) = ' . $n1 . ' + ' . $n2 . ' + ' . $n3 . ' + ' . $n4 . ' = ' . $total . '<br>
              P(E) = n(E) / n(S)
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Part A<br>P(' . $col1 . ')</b></td>
            <td>
              P(' . $col1 . ') = ' . $n1 . ' / ' . $total . '
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>P(' . $col1 . ') = ' . $ans_a . '</b>
              </div>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Part B<br>P(' . $col2 . ' or ' . $col3 . ')</b></td>
            <td>
              ' . $col2 . ' and ' . $col3 . ' are mutually exclusive (no overlap).<br>
              P(' . $col2 . ' or ' . $col3 . ') = P(' . $col2 . ') + P(' . $col3 . ')<br>
              = ' . $n2 . '/' . $total . ' + ' . $n3 . '/' . $total . ' = ' . ($n2+$n3) . '/' . $total . '
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>P(' . $col2 . ' or ' . $col3 . ') = ' . $ans_b . '</b>
              </div>
            </td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Part C<br>P(not ' . $col1 . ')</b></td>
            <td class="col-check-bot">
              Complement Rule: P(not E) = 1 &minus; P(E)<br>
              P(not ' . $col1 . ') = 1 &minus; ' . $ans_a . '
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>P(not ' . $col1 . ') = ' . $ans_c . '</b>
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

<p><b>Basic Probability — Marble Bag</b></p>
<p>A bag contains marbles of four colors:</p>

<table style="border-collapse:collapse; margin:10px 0; font-size:medium;">
  <tr>
    <th style="border:1px solid #ccc; padding:8px 16px; background:#f2f2f2;">Color</th>
    <th style="border:1px solid #ccc; padding:8px 16px; background:#f2f2f2;">Count</th>
  </tr>
  <tr><td style="border:1px solid #ccc; padding:8px 16px; text-transform:capitalize;">$col1</td><td style="border:1px solid #ccc; padding:8px 16px; text-align:center;"><b>$n1</b></td></tr>
  <tr style="background:#fff9ea;"><td style="border:1px solid #ccc; padding:8px 16px; text-transform:capitalize;">$col2</td><td style="border:1px solid #ccc; padding:8px 16px; text-align:center;"><b>$n2</b></td></tr>
  <tr><td style="border:1px solid #ccc; padding:8px 16px; text-transform:capitalize;">$col3</td><td style="border:1px solid #ccc; padding:8px 16px; text-align:center;"><b>$n3</b></td></tr>
  <tr style="background:#fff9ea;"><td style="border:1px solid #ccc; padding:8px 16px; text-transform:capitalize;">$col4</td><td style="border:1px solid #ccc; padding:8px 16px; text-align:center;"><b>$n4</b></td></tr>
</table>

<p>One marble is drawn at random. Give all answers as decimals rounded to 4 decimal places.</p>

<p><b>Part A.</b> What is the probability of drawing a <b>$col1</b> marble?</p>
<p>$ansprompt[0] $answerbox[0]</p>

<p><b>Part B.</b> What is the probability of drawing a <b>$col2</b> or <b>$col3</b> marble?</p>
<p>$ansprompt[1] $answerbox[1]</p>

<p><b>Part C.</b> What is the probability of drawing a marble that is <b>NOT $col1</b>?</p>
<p>$ansprompt[2] $answerbox[2]</p>

</div>

// === ANSWER ===

$solutionguide
