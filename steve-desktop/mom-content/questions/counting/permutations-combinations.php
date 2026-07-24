// === COMMON CONTROL ===
// Section 7.5 — Permutations and Combinations (two-part problem)

// Contexts: each has a permutation scenario and a combination scenario
$contexts = array(
  array(
    "perm" => array("A club is awarding prizes", "members", "there are", "distinct prizes (1st, 2nd, 3rd place) will be awarded to different members"),
    "comb" => array("A committee needs to select members for a subgroup", "volunteers", "there are", "people will be chosen for the subgroup (order does not matter)")
  ),
  array(
    "perm" => array("A race is being held", "runners", "there are", "finishing positions (gold, silver, bronze) will be recorded"),
    "comb" => array("A student is choosing books to read", "books on the shelf", "there are", "books will be selected to read this semester (order does not matter)")
  ),
  array(
    "perm" => array("Officers are being elected for a club", "candidates", "there are", "distinct officer positions (President, VP, Treasurer) will be filled"),
    "comb" => array("A team is being formed from a group", "players available", "there are", "players will be chosen for the team (order does not matter)")
  ),
  array(
    "perm" => array("Students are lining up for a photo", "students", "there are", "of them will stand in the front row in a specific order"),
    "comb" => array("A lottery requires choosing numbers", "numbers (1 through the pool size)", "there are", "numbers must be chosen (order does not matter)")
  )
)
$ci = rand(0, count($contexts)-1)
$ctx_perm = $contexts[$ci]["perm"]
$ctx_comb = $contexts[$ci]["comb"]

// Permutation: choose r from n (ordered)
$perm_n_choices = array(8, 9, 10, 12, 15)
$perm_r_choices = array(3, 4, 5)
$pn = $perm_n_choices[rand(0, count($perm_n_choices)-1)]
$pr = $perm_r_choices[rand(0, count($perm_r_choices)-1)]
$perm_ans = nPr($pn, $pr)

// Combination: choose r from n (unordered)
$comb_n_choices = array(8, 9, 10, 12, 15, 20)
$comb_r_choices = array(3, 4, 5, 6)
$cn = $comb_n_choices[rand(0, count($comb_n_choices)-1)]
$cr = $comb_r_choices[rand(0, count($comb_r_choices)-1)]
$comb_ans = nCr($cn, $cr)

$anstypes = array("number", "number")
$answer[0] = $perm_ans
$answer[1] = $comb_ans
$answerformat[0] = "integer"
$answerformat[1] = "integer"
$abstolerance = 0.5
$ansprompt[0] = "Number of arrangements = "
$ansprompt[1] = "Number of selections = "

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

$perm_factorial_num = $pn
$perm_factorial_den = $pn - $pr

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
            <td style="text-align:center;"><b>Part A<br>Permutation<br>P(n,r) = n!/(n&minus;r)!</b></td>
            <td>
              n = ' . $pn . ', r = ' . $pr . '<br>
              <b>Order matters</b> &rarr; use Permutation formula<br>
              P(' . $pn . ', ' . $pr . ') = ' . $pn . '! / (' . $pn . ' &minus; ' . $pr . ')!<br>
              = ' . $pn . '! / ' . $perm_factorial_den . '!<br>
              = ' . $pn . ' &times; ' . ($pn-1) . ' &times; &hellip; &times; ' . ($pn-$pr+1) . '
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>P(' . $pn . ', ' . $pr . ') = ' . $perm_ans . '</b>
              </div>
            </td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Part B<br>Combination<br>C(n,r) = n!/(r!(n&minus;r)!)</b></td>
            <td class="col-check-bot">
              n = ' . $cn . ', r = ' . $cr . '<br>
              <b>Order does not matter</b> &rarr; use Combination formula<br>
              C(' . $cn . ', ' . $cr . ') = ' . $cn . '! / (' . $cr . '! &times; ' . ($cn-$cr) . '!)
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>C(' . $cn . ', ' . $cr . ') = ' . $comb_ans . '</b>
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

<p><b>Permutations and Combinations</b></p>

<p><b>Part A — Permutation (order matters)</b></p>
<p>$ctx_perm[0]. There are <b>$pn $ctx_perm[1]</b>, and <b>$pr</b> $ctx_perm[3].</p>
<p>How many different arrangements are possible?</p>
<p>$ansprompt[0] $answerbox[0]</p>

<hr>

<p><b>Part B — Combination (order does not matter)</b></p>
<p>$ctx_comb[0]. There are <b>$cn $ctx_comb[1]</b>, and <b>$cr</b> $ctx_comb[3].</p>
<p>How many different selections are possible?</p>
<p>$ansprompt[1] $answerbox[1]</p>

</div>

// === ANSWER ===

$solutionguide
