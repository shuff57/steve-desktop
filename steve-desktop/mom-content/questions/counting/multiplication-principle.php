// === COMMON CONTROL ===
// Section 7.3: Multiplication Principle (sequential independent choices)

// Each scenario has 3 choice dimensions. Values are preselected to avoid
// nested array indexing (which IMathAS may not support in all contexts).

$ci = rand(0, 4)

// Scenario 0: Restaurant fixed-price dinner
$s0_desc   = "A restaurant offers a fixed-price dinner. A customer must choose one item from each course:"
$s0_label0 = "Appetizers"
$s0_label1 = "Entrees"
$s0_label2 = "Desserts"
$s0_c0     = 4
$s0_c1     = 6
$s0_c2     = 3

// Scenario 1: School username (A–Z, 0–9, A–Z)
$s1_desc   = "A student must create a username consisting of one letter, one digit, then one letter (e.g. A3B). The choices are:"
$s1_label0 = "First character (A–Z)"
$s1_label1 = "Middle digit (0–9)"
$s1_label2 = "Last character (A–Z)"
$s1_c0     = 26
$s1_c1     = 10
$s1_c2     = 26

// Scenario 2: Clothing store: sizes, colors, styles
$s2_desc   = "A clothing store sells shirts. Each shirt is available in the following options:"
$s2_label0 = "Sizes (S, M, L, XL, XXL)"
$s2_label1 = "Colors"
$s2_label2 = "Styles (short sleeve, long sleeve, sleeveless)"
$s2_c0     = 5
$s2_c1_choices = array(4, 5, 6, 7, 8)
$s2_c1     = $s2_c1_choices[rand(0, 4)]
$s2_c2     = 3

// Scenario 3: Travel packages
$s3_desc   = "A travel agency offers vacation packages. A traveler must choose one option in each category:"
$s3_label0 = "Destinations"
$s3_label1 = "Hotel types (budget, standard, luxury)"
$s3_label2 = "Transportation options (flight, train, bus)"
$s3_c0_choices = array(4, 5, 6, 7)
$s3_c0     = $s3_c0_choices[rand(0, 3)]
$s3_c1     = 3
$s3_c2     = 3

// Scenario 4: Car customization
$s4_desc   = "A car dealership lets customers configure a vehicle. Options available:"
$s4_label0 = "Exterior colors"
$s4_label1 = "Interior colors"
$s4_label2 = "Trim levels"
$s4_c0_choices = array(6, 7, 8, 10)
$s4_c0     = $s4_c0_choices[rand(0, 3)]
$s4_c1_choices = array(3, 4, 5)
$s4_c1     = $s4_c1_choices[rand(0, 2)]
$s4_c2_choices = array(2, 3, 4)
$s4_c2     = $s4_c2_choices[rand(0, 2)]

// Select active scenario
$ctx_desc   = $s0_desc   if ($ci == 0)
$ctx_desc   = $s1_desc   if ($ci == 1)
$ctx_desc   = $s2_desc   if ($ci == 2)
$ctx_desc   = $s3_desc   if ($ci == 3)
$ctx_desc   = $s4_desc   if ($ci == 4)

$ctx_label0 = $s0_label0 if ($ci == 0)
$ctx_label0 = $s1_label0 if ($ci == 1)
$ctx_label0 = $s2_label0 if ($ci == 2)
$ctx_label0 = $s3_label0 if ($ci == 3)
$ctx_label0 = $s4_label0 if ($ci == 4)

$ctx_label1 = $s0_label1 if ($ci == 0)
$ctx_label1 = $s1_label1 if ($ci == 1)
$ctx_label1 = $s2_label1 if ($ci == 2)
$ctx_label1 = $s3_label1 if ($ci == 3)
$ctx_label1 = $s4_label1 if ($ci == 4)

$ctx_label2 = $s0_label2 if ($ci == 0)
$ctx_label2 = $s1_label2 if ($ci == 1)
$ctx_label2 = $s2_label2 if ($ci == 2)
$ctx_label2 = $s3_label2 if ($ci == 3)
$ctx_label2 = $s4_label2 if ($ci == 4)

$c1 = $s0_c0 if ($ci == 0)
$c1 = $s1_c0 if ($ci == 1)
$c1 = $s2_c0 if ($ci == 2)
$c1 = $s3_c0 if ($ci == 3)
$c1 = $s4_c0 if ($ci == 4)

$c2 = $s0_c1 if ($ci == 0)
$c2 = $s1_c1 if ($ci == 1)
$c2 = $s2_c1 if ($ci == 2)
$c2 = $s3_c1 if ($ci == 3)
$c2 = $s4_c1 if ($ci == 4)

$c3 = $s0_c2 if ($ci == 0)
$c3 = $s1_c2 if ($ci == 1)
$c3 = $s2_c2 if ($ci == 2)
$c3 = $s3_c2 if ($ci == 3)
$c3 = $s4_c2 if ($ci == 4)

$total = $c1 * $c2 * $c3

$anstypes = array("number")
$answer[0] = $total
$answerformat[0] = "integer"
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
            <td style="text-align:center;"><b>Identify Choices</b></td>
            <td>
              ' . $ctx_label0 . ': <b>' . $c1 . '</b> options<br>
              ' . $ctx_label1 . ': <b>' . $c2 . '</b> options<br>
              ' . $ctx_label2 . ': <b>' . $c3 . '</b> options
            </td>
          </tr>
          <tr>
            <td class="col-cat-bot" style="text-align:center;"><b>Multiplication Principle</b></td>
            <td class="col-check-bot">
              Each choice is made independently, so multiply the number of options at each step.<br>
              Total = ' . $c1 . ' &times; ' . $c2 . ' &times; ' . $c3 . '
              <div style="margin-top:8px;padding:0.6em 1em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">
                <b>Total selections = ' . $total . '</b>
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

<p><b>Multiplication Principle</b></p>
<p>$ctx_desc</p>

<table style="border-collapse:collapse; margin:10px 0; font-size:medium;">
  <tr>
    <th style="border:1px solid #ccc; padding:8px 14px; background:#f2f2f2;">Choice</th>
    <th style="border:1px solid #ccc; padding:8px 14px; background:#f2f2f2;">Options Available</th>
  </tr>
  <tr><td style="border:1px solid #ccc; padding:8px 14px;">$ctx_label0</td><td style="border:1px solid #ccc; padding:8px 14px; text-align:center;"><b>$c1</b></td></tr>
  <tr style="background:#fff9ea;"><td style="border:1px solid #ccc; padding:8px 14px;">$ctx_label1</td><td style="border:1px solid #ccc; padding:8px 14px; text-align:center;"><b>$c2</b></td></tr>
  <tr><td style="border:1px solid #ccc; padding:8px 14px;">$ctx_label2</td><td style="border:1px solid #ccc; padding:8px 14px; text-align:center;"><b>$c3</b></td></tr>
</table>

<p>Using the <b>Multiplication Principle</b>, how many different complete selections are possible?</p>
<p>Total selections = $answerbox[0]</p>

</div>

// === ANSWER ===

$solutionguide
