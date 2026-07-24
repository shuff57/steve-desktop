// === COMMON CONTROL ===
// Section 7.1 — 3-Set Venn Diagram Inclusion-Exclusion (3 number answers)

// Survey contexts: (topic, Set A, Set B, Set C, population noun)
$contexts = array(
  array("students were surveyed about their activities", "play a sport", "play an instrument", "volunteer", "students"),
  array("adults were surveyed about their hobbies", "garden", "cook", "read for fun", "adults"),
  array("employees were surveyed about fitness habits", "go to the gym", "run outside", "do yoga", "employees"),
  array("customers were surveyed about streaming services", "use Netflix", "use Hulu", "use Disney+", "customers")
)
$ci = rand(0, count($contexts)-1)
$ctx_survey = $contexts[$ci][0]
$ctx_a = $contexts[$ci][1]
$ctx_b = $contexts[$ci][2]
$ctx_c = $contexts[$ci][3]
$ctx_noun = $contexts[$ci][4]

// Build from 7 non-overlapping regions + neither
// Regions: only_a, only_b, only_c, ab_only, ac_only, bc_only, abc
$only_a  = rand(5, 12)
$only_b  = rand(4, 10)
$only_c  = rand(4, 10)
$ab_only = rand(2, 6)
$ac_only = rand(2, 6)
$bc_only = rand(2, 6)
$abc     = rand(1, 4)
$neither = rand(3, 8)

// Set sizes
$n_a = $only_a + $ab_only + $ac_only + $abc
$n_b = $only_b + $ab_only + $bc_only + $abc
$n_c = $only_c + $ac_only + $bc_only + $abc
$n_ab = $ab_only + $abc
$n_ac = $ac_only + $abc
$n_bc = $bc_only + $abc
$n_abc = $abc
$n_u = $only_a + $only_b + $only_c + $ab_only + $ac_only + $bc_only + $abc + $neither

// Inclusion-exclusion: n(A ∪ B ∪ C)
$n_union = $n_a + $n_b + $n_c - $n_ab - $n_ac - $n_bc + $n_abc

// Answers
// Part A: n(A ∪ B ∪ C)
// Part B: n(A ∩ B ∩ C)  — in all three
// Part C: n(A ∩ B′ ∩ C′) — in A only
$answer[0] = $n_union
$answer[1] = $abc
$answer[2] = $only_a

$anstypes = array("number", "number", "number")
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

$ie_expand = $n_a . ' + ' . $n_b . ' + ' . $n_c . ' &minus; ' . $n_ab . ' &minus; ' . $n_ac . ' &minus; ' . $n_bc . ' + ' . $n_abc

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
              From the region counts:<br>
              n(A) = ' . $n_a . ', n(B) = ' . $n_b . ', n(C) = ' . $n_c . '<br>
              n(A&cap;B) = ' . $n_ab . ', n(A&cap;C) = ' . $n_ac . ', n(B&cap;C) = ' . $n_bc . '<br>
              n(A&cap;B&cap;C) = ' . $abc . '<br>
              n(U) = ' . $n_u . '
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Part A<br>n(A&cup;B&cup;C)</b></td>
            <td>
              Inclusion-Exclusion Formula:<br>
              n(A&cup;B&cup;C) = n(A)+n(B)+n(C) &minus; n(A&cap;B) &minus; n(A&cap;C) &minus; n(B&cap;C) + n(A&cap;B&cap;C)<br>
              = ' . $ie_expand . '
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>n(A&cup;B&cup;C) = ' . $n_union . '</b>
              </div>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Part B<br>n(A&cap;B&cap;C)</b></td>
            <td>
              This is the center region of the Venn diagram — those in all three sets.
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>n(A&cap;B&cap;C) = ' . $abc . '</b>
              </div>
            </td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Part C<br>Only A</b></td>
            <td class="col-check-bot">
              "Only A" means in A, but not in B or C. This is the region of A with all overlapping parts removed:<br>
              Only A = n(A) &minus; n(A&cap;B) &minus; n(A&cap;C) + n(A&cap;B&cap;C)<br>
              = ' . $n_a . ' &minus; ' . $n_ab . ' &minus; ' . $n_ac . ' + ' . $abc . '
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Only in A = ' . $only_a . '</b>
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

<p><b>Three-Set Venn Diagram</b></p>
<p>A total of <b>$n_u $ctx_noun</b> $ctx_survey. Let:</p>
<ul>
  <li><b>A</b> = {$ctx_noun who $ctx_a}</li>
  <li><b>B</b> = {$ctx_noun who $ctx_b}</li>
  <li><b>C</b> = {$ctx_noun who $ctx_c}</li>
</ul>

<p>The number of people in each region of the three-circle Venn diagram is given below:</p>

<table style="border-collapse:collapse; margin:10px 0; font-size:medium;">
  <tr>
    <th style="border:1px solid #ccc; padding:8px 14px; background:#f2f2f2;">Region</th>
    <th style="border:1px solid #ccc; padding:8px 14px; background:#f2f2f2;">Count</th>
  </tr>
  <tr><td style="border:1px solid #ccc; padding:7px 14px;">Only A (not B, not C)</td><td style="border:1px solid #ccc; padding:7px 14px; text-align:center;"><b>$only_a</b></td></tr>
  <tr style="background:#fff9ea;"><td style="border:1px solid #ccc; padding:7px 14px;">Only B (not A, not C)</td><td style="border:1px solid #ccc; padding:7px 14px; text-align:center;"><b>$only_b</b></td></tr>
  <tr><td style="border:1px solid #ccc; padding:7px 14px;">Only C (not A, not B)</td><td style="border:1px solid #ccc; padding:7px 14px; text-align:center;"><b>$only_c</b></td></tr>
  <tr style="background:#fff9ea;"><td style="border:1px solid #ccc; padding:7px 14px;">A and B only (not C)</td><td style="border:1px solid #ccc; padding:7px 14px; text-align:center;"><b>$ab_only</b></td></tr>
  <tr><td style="border:1px solid #ccc; padding:7px 14px;">A and C only (not B)</td><td style="border:1px solid #ccc; padding:7px 14px; text-align:center;"><b>$ac_only</b></td></tr>
  <tr style="background:#fff9ea;"><td style="border:1px solid #ccc; padding:7px 14px;">B and C only (not A)</td><td style="border:1px solid #ccc; padding:7px 14px; text-align:center;"><b>$bc_only</b></td></tr>
  <tr><td style="border:1px solid #ccc; padding:7px 14px;">All three (A and B and C)</td><td style="border:1px solid #ccc; padding:7px 14px; text-align:center;"><b>$abc</b></td></tr>
  <tr style="background:#fff9ea;"><td style="border:1px solid #ccc; padding:7px 14px;">None of A, B, or C</td><td style="border:1px solid #ccc; padding:7px 14px; text-align:center;"><b>$neither</b></td></tr>
</table>

<p>Enter whole numbers for each answer.</p>

<p><b>Part A.</b> How many $ctx_noun are in A &cup; B &cup; C (in <em>at least one</em> of the three sets)?<br>Use the inclusion-exclusion formula.</p>
<p>n(A &cup; B &cup; C) = $answerbox[0]</p>

<p><b>Part B.</b> How many $ctx_noun are in all three sets A &cap; B &cap; C?</p>
<p>n(A &cap; B &cap; C) = $answerbox[1]</p>

<p><b>Part C.</b> How many $ctx_noun are in set A only (in A but not in B or C)?</p>
<p>n(A only) = $answerbox[2]</p>

</div>

// === ANSWER ===

$solutionguide
