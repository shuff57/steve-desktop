// === COMMON CONTROL ===
// Section 7.1 — 2-Set Venn Diagram Cardinalities (3 number answers)

// Survey contexts: (survey topic, Set A label, Set B label, population noun)
$contexts = array(
  array("students in a class were surveyed", "own a dog", "own a cat", "students"),
  array("employees at a company were surveyed", "drink coffee", "drink tea", "employees"),
  array("shoppers were surveyed at a mall", "bought clothing", "bought shoes", "shoppers"),
  array("adults in a neighborhood were surveyed", "have a gym membership", "ride a bike to work", "adults"),
  array("customers at a restaurant were surveyed", "ordered dessert", "ordered an appetizer", "customers")
)
$ci = rand(0, count($contexts)-1)
$ctx_survey = $contexts[$ci][0]
$ctx_a = $contexts[$ci][1]
$ctx_b = $contexts[$ci][2]
$ctx_noun = $contexts[$ci][3]

// Build from region counts (guarantees consistency)
// Regions: only-A, A-and-B, only-B, neither
$only_a_choices = array(8, 10, 12, 14, 15, 16, 18, 20)
$ab_choices = array(4, 5, 6, 7, 8, 9, 10)
$only_b_choices = array(6, 8, 9, 10, 11, 12, 14)
$neither_choices = array(3, 4, 5, 6, 7, 8, 10)

$only_a = $only_a_choices[rand(0, count($only_a_choices)-1)]
$ab     = $ab_choices[rand(0, count($ab_choices)-1)]
$only_b = $only_b_choices[rand(0, count($only_b_choices)-1)]
$neither= $neither_choices[rand(0, count($neither_choices)-1)]

// Derived set sizes
$n_a = $only_a + $ab
$n_b = $ab + $only_b
$n_u = $only_a + $ab + $only_b + $neither

// Answers
// Part A: n(A ∪ B)
// Part B: n(A ∩ B')  — in A but not B
// Part C: n(A' ∩ B') — in neither A nor B (complement of union)
$ans_a = $only_a + $ab + $only_b   // n(A ∪ B)
$ans_b = $only_a                    // n(A ∩ B') = only in A
$ans_c = $neither                   // n((A ∪ B)') = neither

$anstypes = array("number", "number", "number")
$answer[0] = $ans_a
$answer[1] = $ans_b
$answer[2] = $ans_c
$answerformat[0] = "integer"
$answerformat[1] = "integer"
$answerformat[2] = "integer"
$abstolerance = 0.5

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
            <td style="text-align:center;"><b>Given</b></td>
            <td>
              From the Venn diagram regions:<br>
              Only A (not B): ' . $only_a . '<br>
              Both A and B: ' . $ab . '<br>
              Only B (not A): ' . $only_b . '<br>
              Neither: ' . $neither . '<br>
              Total n(U) = ' . $n_u . '
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Part A<br>n(A &cup; B)</b></td>
            <td>
              n(A &cup; B) = n(A) + n(B) &minus; n(A &cap; B)<br>
              = ' . $n_a . ' + ' . $n_b . ' &minus; ' . $ab . '<br>
              OR: count all regions inside A or B: ' . $only_a . ' + ' . $ab . ' + ' . $only_b . '
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>n(A &cup; B) = ' . $ans_a . '</b>
              </div>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Part B<br>n(A &cap; B&prime;)</b></td>
            <td>
              A &cap; B&prime; = elements in A but NOT in B = "only A" region
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>n(A &cap; B&prime;) = ' . $ans_b . '</b>
              </div>
            </td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Part C<br>n(A&prime; &cap; B&prime;)</b></td>
            <td class="col-check-bot">
              A&prime; &cap; B&prime; = complement of (A &cup; B) = elements in neither set<br>
              = n(U) &minus; n(A &cup; B) = ' . $n_u . ' &minus; ' . $ans_a . '
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>n(A&prime; &cap; B&prime;) = ' . $ans_c . '</b>
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

<p><b>Two-Set Venn Diagram</b></p>
<p>A total of <b>$n_u $ctx_noun</b> $ctx_survey. Let <b>A</b> = &#123;$ctx_noun who $ctx_a&#125; and <b>B</b> = &#123;$ctx_noun who $ctx_b&#125;.</p>

<p>The survey results are summarized in the Venn diagram regions below:</p>

<table style="border-collapse:collapse; margin:10px 0; font-size:medium;">
  <tr>
    <th style="border:1px solid #ccc; padding:8px 16px; background:#f2f2f2;">Region</th>
    <th style="border:1px solid #ccc; padding:8px 16px; background:#f2f2f2;">Description</th>
    <th style="border:1px solid #ccc; padding:8px 16px; background:#f2f2f2;">Count</th>
  </tr>
  <tr>
    <td style="border:1px solid #ccc; padding:8px 16px; text-align:center;">Only A</td>
    <td style="border:1px solid #ccc; padding:8px 16px;">$ctx_a, but <em>not</em> $ctx_b</td>
    <td style="border:1px solid #ccc; padding:8px 16px; text-align:center;"><b>$only_a</b></td>
  </tr>
  <tr style="background:#fff9ea;">
    <td style="border:1px solid #ccc; padding:8px 16px; text-align:center;">Both A and B</td>
    <td style="border:1px solid #ccc; padding:8px 16px;">$ctx_a <em>and</em> $ctx_b</td>
    <td style="border:1px solid #ccc; padding:8px 16px; text-align:center;"><b>$ab</b></td>
  </tr>
  <tr>
    <td style="border:1px solid #ccc; padding:8px 16px; text-align:center;">Only B</td>
    <td style="border:1px solid #ccc; padding:8px 16px;">$ctx_b, but <em>not</em> $ctx_a</td>
    <td style="border:1px solid #ccc; padding:8px 16px; text-align:center;"><b>$only_b</b></td>
  </tr>
  <tr style="background:#fff9ea;">
    <td style="border:1px solid #ccc; padding:8px 16px; text-align:center;">Neither</td>
    <td style="border:1px solid #ccc; padding:8px 16px;">neither $ctx_a nor $ctx_b</td>
    <td style="border:1px solid #ccc; padding:8px 16px; text-align:center;"><b>$neither</b></td>
  </tr>
</table>

<p>Use the Venn diagram to answer each question. Enter whole numbers.</p>

<p><b>Part A.</b> How many $ctx_noun are in A &cup; B (in set A <em>or</em> set B, or both)?</p>
<p>n(A &cup; B) = $answerbox[0]</p>

<p><b>Part B.</b> How many $ctx_noun are in A but <em>not</em> in B?</p>
<p>n(A &cap; B&prime;) = $answerbox[1]</p>

<p><b>Part C.</b> How many $ctx_noun are in <em>neither</em> set A nor set B?</p>
<p>n(A&prime; &cap; B&prime;) = $answerbox[2]</p>

</div>

// === ANSWER ===

$solutionguide
