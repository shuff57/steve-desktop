// === COMMON CONTROL ===
// Sections 8.2–8.4: Addition Rule P(A or B) with overlapping events (2 number answers)

// Context: classroom with two attributes, students can have both
$contexts = array(
  array("students in a class", "wear glasses", "are left-handed", "students"),
  array("employees at an office", "use public transit", "bring lunch from home", "employees"),
  array("customers at a store", "used a coupon", "paid with cash", "customers"),
  array("survey respondents", "own a car", "own a bicycle", "respondents"),
  array("adults in a study", "exercise regularly", "follow a specific diet", "adults")
)
$ci = rand(0, count($contexts)-1)
$ctx_pop  = $contexts[$ci][0]
$ctx_a    = $contexts[$ci][1]
$ctx_b    = $contexts[$ci][2]
$ctx_noun = $contexts[$ci][3]

// Build from region counts to ensure consistency
$total_choices = array(30, 35, 40, 45, 50)
$total = $total_choices[rand(0, count($total_choices)-1)]

$only_a_pct = rand(20, 35)    // percent only A
$only_b_pct = rand(15, 30)    // percent only B
$both_pct   = rand(8, 18)     // percent both

$only_a = round($total * $only_a_pct / 100)
$both   = round($total * $both_pct / 100)
$only_b = round($total * $only_b_pct / 100)
$neither = $total - $only_a - $both - $only_b

// Ensure neither >= 0; if not, shrink both by 1
if ($neither < 0) {
  $both = $both - 1
  $neither = $total - $only_a - $both - $only_b
}

$n_a = $only_a + $both
$n_b = $only_b + $both
$n_ab = $both

// Part A: P(A or B) using addition rule
$n_union = $only_a + $both + $only_b
$p_union = round($n_union / $total, 4)

// Part B: P(A or B) verified by complement
$p_neither = round($neither / $total, 4)
$p_union_check = round(1 - $p_neither, 4)

// Use p_union as both answers (they match); Part B asks student to use complement
$answer[0] = $p_union
$answer[1] = $p_neither

$anstypes = array("number", "number")
$abstolerance[0] = 0.0001
$abstolerance[1] = 0.0001
$ansprompt[0] = "P(A or B) = "
$ansprompt[1] = "P(neither A nor B) = "

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
              n(U) = ' . $total . '<br>
              n(A) = ' . $n_a . ' (' . $ctx_a . ')<br>
              n(B) = ' . $n_b . ' (' . $ctx_b . ')<br>
              n(A &cap; B) = ' . $n_ab . ' (both)<br>
              n(neither) = ' . $neither . '
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Part A<br>Addition Rule</b></td>
            <td>
              P(A or B) = P(A) + P(B) &minus; P(A &cap; B)<br>
              = ' . $n_a . '/' . $total . ' + ' . $n_b . '/' . $total . ' &minus; ' . $n_ab . '/' . $total . '<br>
              = (' . $n_a . ' + ' . $n_b . ' &minus; ' . $n_ab . ') / ' . $total . '<br>
              = ' . $n_union . ' / ' . $total . '
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>P(A or B) = ' . $p_union . '</b>
              </div>
            </td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Part B<br>Complement</b></td>
            <td class="col-check-bot">
              "Neither A nor B" = complement of (A &cup; B)<br>
              n(neither) = ' . $neither . '<br>
              P(neither) = ' . $neither . ' / ' . $total . '
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>P(neither A nor B) = ' . $p_neither . '</b>
              </div>
              <em>Check: P(A or B) + P(neither) = ' . $p_union . ' + ' . $p_neither . ' = 1.0000 ✓</em>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:Arial; font-size:medium; line-height:1.6;">

<p><b>Addition Rule for Probability</b></p>
<p>A survey of <b>$total $ctx_pop</b> found the following information. Let <b>A</b> = &#123;$ctx_noun who $ctx_a&#125; and <b>B</b> = &#123;$ctx_noun who $ctx_b&#125;.</p>

<table style="border-collapse:collapse; margin:10px 0; font-size:medium;">
  <tr>
    <th style="border:1px solid #ccc; padding:8px 16px; background:#f2f2f2;">Group</th>
    <th style="border:1px solid #ccc; padding:8px 16px; background:#f2f2f2;">Count</th>
  </tr>
  <tr><td style="border:1px solid #ccc; padding:8px 16px;">$ctx_a only (not $ctx_b)</td><td style="border:1px solid #ccc; padding:8px 16px; text-align:center;"><b>$only_a</b></td></tr>
  <tr style="background:#fff9ea;"><td style="border:1px solid #ccc; padding:8px 16px;">$ctx_b only (not $ctx_a)</td><td style="border:1px solid #ccc; padding:8px 16px; text-align:center;"><b>$only_b</b></td></tr>
  <tr><td style="border:1px solid #ccc; padding:8px 16px;">Both ($ctx_a and $ctx_b)</td><td style="border:1px solid #ccc; padding:8px 16px; text-align:center;"><b>$both</b></td></tr>
  <tr style="background:#fff9ea;"><td style="border:1px solid #ccc; padding:8px 16px;">Neither</td><td style="border:1px solid #ccc; padding:8px 16px; text-align:center;"><b>$neither</b></td></tr>
</table>

<p>One person is selected at random. Give all answers as decimals rounded to 4 decimal places.</p>

<p><b>Part A.</b> Using the <b>Addition Rule</b>, find P(A or B): the probability the selected person <em>$ctx_a or $ctx_b</em> (or both).</p>
<p>$ansprompt[0] $answerbox[0]</p>

<p><b>Part B.</b> Find the probability that the selected person is in <b>neither</b> set A nor set B.</p>
<p>$ansprompt[1] $answerbox[1]</p>

</div>

// === ANSWER ===

$solutionguide
