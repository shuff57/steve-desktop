// === NAME - DESCRIPTION: Compare Two Distributions in Context - Students compare two groups on shape, center, spread, and unusual values, then write a verdict in the context of what was measured ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL (paste into Common Control) ===

loadlibrary("stats");

$anstypes = array("essay");
$displayformat[0]='editornopaste';

/* ---------- 1. Dynamic Context Generation ---------- */
$contexts = array(
  "the end-of-semester exam scores of two algebra classes",
  "the drive-through wait times at two competing coffee shops",
  "the hours of sleep per night reported by students in two grade levels"
);
$i = rand(0, count($contexts)-1);
$ctx = $contexts[$i];

$labels_a = array("Class A", "Shop A", "Sophomores");
$labels_b = array("Class B", "Shop B", "Seniors");
$things = array("exam score", "wait time", "amount of sleep");
$things_pl = array("exam scores", "wait times", "hours of sleep");
$units = array("points", "minutes", "hours");
$steps = array(1, 0.1, 0.1);
$decs = array(0, 1, 1);

$labelA = $labels_a[$i];
$labelB = $labels_b[$i];
$thing = $things[$i];
$thing_pl = $things_pl[$i];
$unit = $units[$i];
$dp = $decs[$i];

/* ---------- 2. Summary statistics ---------- */
// One pre-built pair of groups per context. Order: mean, median, SD, min, Q1, Q3, max.
// Group A is roughly symmetric with no unusual values (mean = median, min and max equally far from the median).
// Group B is skewed with unusual values: exam scores and sleep are left-skewed (mean below median, low outliers),
// wait times are right-skewed (mean above median, high outliers). Outliers checked against the 1.5 x IQR fences.
$stats_a = array(
  array(78, 78, 6, 63, 74, 82, 93),
  array(3.2, 3.2, 0.8, 1.6, 2.7, 3.7, 4.9),
  array(7.4, 7.4, 0.9, 5.4, 6.8, 8.0, 9.4)
);
$stats_b = array(
  array(75, 79, 11, 38, 71, 86, 96),
  array(4.1, 3.5, 1.8, 1.1, 2.8, 4.6, 11.5),
  array(6.2, 6.6, 1.4, 2.8, 5.7, 7.2, 8.6)
);
$sa = $stats_a[$i];
$sb = $stats_b[$i];

// Slide every location statistic by the same amount so each shape relationship survives; the SD and IQR do not move.
$shift = rand(-3, 3) * $steps[$i];

$meanA = prettyreal($sa[0] + $shift, $dp, "");
$medianA = prettyreal($sa[1] + $shift, $dp, "");
$sdA = prettyreal($sa[2], $dp, "");
$minA = prettyreal($sa[3] + $shift, $dp, "");
$q1A = prettyreal($sa[4] + $shift, $dp, "");
$q3A = prettyreal($sa[5] + $shift, $dp, "");
$maxA = prettyreal($sa[6] + $shift, $dp, "");
$iqrA = prettyreal($sa[5] - $sa[4], $dp, "");

$meanB = prettyreal($sb[0] + $shift, $dp, "");
$medianB = prettyreal($sb[1] + $shift, $dp, "");
$sdB = prettyreal($sb[2], $dp, "");
$minB = prettyreal($sb[3] + $shift, $dp, "");
$q1B = prettyreal($sb[4] + $shift, $dp, "");
$q3B = prettyreal($sb[5] + $shift, $dp, "");
$maxB = prettyreal($sb[6] + $shift, $dp, "");
$iqrB = prettyreal($sb[5] - $sb[4], $dp, "");

/* ---------- 3. Model answer pieces ---------- */
// Plain-word pieces that depend on the context: which way group B is skewed and what its unusual values are.
$dirs = array("left", "right", "left");
$rels = array("below", "above", "below");
$pulls = array("down", "up", "down");
$nouns = array("low scores", "long waits", "short nights");
// The group labels are not all the same grammatical person: Class A / Shop A are singular,
// Sophomores / Seniors are plural. Every verb and pronoun in the model answer comes from here.
$verb_is = array("is", "is", "are");
$verb_get = array("gets", "gets", "get");
$verb_have = array("includes", "includes", "include");
$poss = array("its", "its", "their");
$poss_cap = array("Its", "Its", "Their");
$subj = array("it", "it", "they");
// How the two typical values compare, per context. Direction must match the medians: exam B>A, shop B>A, sleep A>B.
$cmps = array(", so Class B is slightly higher", ", so Shop B is a little higher", ", so Sophomores sleep noticeably more");
$dir = $dirs[$i];
$rel = $rels[$i];
$pull = $pulls[$i];
$noun = $nouns[$i];
$cmps = $cmps[$i];
$verb_is = $verb_is[$i];
$verb_get = $verb_get[$i];
$verb_have = $verb_have[$i];
$poss = $poss[$i];
$poss_cap = $poss_cap[$i];
$subj = $subj[$i];
$exts = array($minB, $maxB, $minB);
$ext_b = $exts[$i];

// Targets shown to the grader (double-quoted so apostrophes need no escaping)
$t_shape = "$labelA $verb_is roughly symmetric: $poss mean ($meanA) and median ($medianA) are the same, and $poss smallest and largest values ($minA and $maxA) sit about the same distance from the median, with no unusual values. $labelB $verb_is skewed $dir: $poss mean ($meanB) is $rel $poss median ($medianB), and $poss most extreme value ($ext_b) stands well apart from the rest of $poss values.";
$t_center = "For the typical $thing, $labelA $verb_is about $medianA $unit and $labelB $verb_is about $medianB $unit$cmps. The median is the better choice for $labelB because $subj $verb_is skewed: $poss mean ($meanB) is pulled $pull by the $noun.";
$t_spread = "$labelB $verb_is more spread out than $labelA. $poss_cap standard deviation is $sdB compared with $sdA, and $poss IQR is $iqrB compared with $iqrA.";

$verdicts = array(
  "In terms of exam scores, $labelA $verb_is the more consistent class, with most scores close to $medianA points, while $labelB has a similar typical score but $poss scores are far more spread out, including a few very low ones.",
  "In terms of wait time, $labelA is faster and more consistent, typically about $medianA minutes, while $labelB is a little slower on a typical visit and sometimes keeps customers waiting as long as $maxB minutes.",
  "In terms of sleep, $labelA $verb_get more sleep and $verb_is more consistent, typically about $medianA hours a night, while $labelB $verb_get less typical sleep and $verb_have at least one student who sleeps only about $minB hours."
);
$t_verdict = $verdicts[$i];

$sample_narrative = "$t_shape $t_center $t_spread $t_verdict";

/* ---------- 4. SHARED CSS & JS ---------- */
$css_block = '
<style>
    /* Container & Details */
    .rubric-container { width:100%; font-family:Arial; font-size:medium; margin:1em 0; }
    .rubric-container details { width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; background:#fff; }
    
    /* Summary styling */
    .rubric-container summary { cursor:pointer; display:block; width:100%; background:#f8f8f8; color:#333; padding:0.35em 0.6em; font-weight:bold; border-bottom:1px solid #ccc; list-style:none; border:none; }
    .rubric-container details[open] summary { box-shadow: inset 0 -1px 0 #ccc; }
    .rubric-container summary::-webkit-details-marker { display:none; }
    
    /* Arrows */
    .arrow-open { display:none; }
    .rubric-container details[open] .arrow-closed { display:none; }
    .rubric-container details[open] .arrow-open { display:inline; }

    /* Content Animation Wrapper */
    .rubric-content { overflow:hidden; max-height:0; opacity:0; transition:max-height 300ms ease-out, opacity 300ms ease-out, padding 200ms ease-out; margin-top:0; background:#fafafa; box-sizing:border-box; padding:0 0.75em; }
    .rubric-container details[open] .rubric-content { max-height:2000px; opacity:1; padding:0.75em; }

    /* Table Styling */
    .rubric-table { border-collapse:separate; border-spacing:0; width:100%; border:1px solid #ccc; border-radius:8px; overflow:hidden; font-family:Arial; font-size:small; margin-top:10px; }
    .rubric-table th { background:#f2f2f2; padding:8px; text-align:center; border:1px solid #ccc; }
    .rubric-table td { padding:10px; border:1px solid #ccc; vertical-align:top; user-select:text; }
    
    /* Theme Colors */
    .row-colored { background:#fff9ea; }
    .col-header { width:25%; border-top-left-radius:8px; }
    .col-check { border-top-right-radius:8px; }
    .col-cat-bot { border-bottom-left-radius:8px; }
    .col-check-bot { border-bottom-right-radius:8px; }

    /* Answer Key Specifics */
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

/* ---------- 5. Summary Table ---------- */
$summaryTable = '
<style>
  .summary-table { border-collapse:collapse; margin:0.6em 0; font-family:Arial; font-size:14px; }
  .summary-table th, .summary-table td { border:1px solid #ccc; padding:6px 12px; text-align:center; }
  .summary-table th { background:#f2f2f2; }
</style>
<table class="summary-table">
<tr><th></th><th>Mean</th><th>Median</th><th>s (SD)</th><th>Min</th><th>Q1</th><th>Q3</th><th>Max</th></tr>
<tr><td><b>'.$labelA.'</b></td><td>'.$meanA.'</td><td>'.$medianA.'</td><td>'.$sdA.'</td><td>'.$minA.'</td><td>'.$q1A.'</td><td>'.$q3A.'</td><td>'.$maxA.'</td></tr>
<tr><td><b>'.$labelB.'</b></td><td>'.$meanB.'</td><td>'.$medianB.'</td><td>'.$sdB.'</td><td>'.$minB.'</td><td>'.$q1B.'</td><td>'.$q3B.'</td><td>'.$maxB.'</td></tr>
</table>
<p style="font-size:small; color:#555;">All values are in '.$unit.'.</p>';

/* ---------- 6. Student Rubric (Neutral Checklist) ---------- */
// NO answers shown - just checkbox criteria for students
$rubricbutton = $css_block . '
<div class="rubric-container">
  <details>
    <summary>
      <span class="arrow-closed">&#9656;</span><span class="arrow-open">&#9662;</span>
      Click to View Grading Checklist
    </summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr>
            <th class="col-header">Category</th>
            <th class="col-check">What to include</th>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Shape and Unusual Values<br>(2 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Describe the shape of each distribution and mention anything unusual, and say which numbers in the table show it.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Center<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Compare the typical '.$thing.' of the two groups. Using the mean or the median is fine, as long as you say which and why it suits the shape you described.</label></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Spread<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Compare how spread out the two groups are. Using the standard deviation or the IQR is fine, as long as you say which.</label></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;" class="col-cat-bot"><b>In-Context Verdict<br>(2 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li><label><input type="checkbox"> Finish with one sentence about the '.$thing_pl.' that says how the two groups differ, in everyday words.</label></li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </details>
</div>';

/* ---------- 7. Instructor Rubric (With Answer Targets) ---------- */
// Shows ideal answers and full model response - only visible when grading
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
            <td style="text-align:center;"><b>Shape and Unusual Values<br>(2 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Describe the shape of each distribution, mention any unusual values, and cite the numbers in the table.
                    <span class="ideal-ans">Target: "'.$t_shape.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>Center<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Compare the typical values, choosing the mean or the median and using the shape to explain the choice.
                    <span class="ideal-ans">Target: "'.$t_center.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>Spread<br>(3 pts)</b></td>
            <td>
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Compare how spread out the groups are and name the measure used (standard deviation or IQR).
                    <span class="ideal-ans">Target: "'.$t_spread.'"</span></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;" class="col-cat-bot"><b>In-Context Verdict<br>(2 pts)</b></td>
            <td class="col-check-bot">
              <ul style="list-style:none; margin:0; padding-left:0;">
                <li>Finish with one sentence, in the context of the scenario, about how the two groups differ.
                    <span class="ideal-ans">Target: "'.$t_verdict.'"</span></li>
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

/* ---------- 8. Question Text ---------- */
$questiontext = '
<div style="font-family:Arial; font-size:medium; line-height:1.6;">
  <p>The summary statistics below describe '.$ctx.'.</p>
  '.$summaryTable.'
  <p><b>Essay Prompt:</b><br>
  Write a paragraph comparing the two distributions.</p>
  <p>In your response, be sure to address:</p>
  <ul>
    <li>The shape of each distribution, and anything unusual about it.</li>
    <li>How the typical '.$thing.' compares between '.$labelA.' and '.$labelB.'.</li>
    <li>How the spread of the two groups compares.</li>
    <li>A one-sentence conclusion about the '.$thing_pl.' in the two groups, in everyday words.</li>
    <li>A one-sentence verdict about the '.$thing.' in the two groups, in everyday words.</li>
  </ul>
  '.$rubricbutton.'
</div>';

//question text

$questiontext
$answerbox[0]

///

$rubricanswerbutton
