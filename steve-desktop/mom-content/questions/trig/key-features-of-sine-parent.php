// === Key Features of y = sin(x): amplitude, period, midline, zeros, max, min ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

// No randomization needed: these are fixed properties of the parent function

// Graph of y = sin(x) over one full period [0, 2pi]
$graph = showplot("sin(x),blue,0,6.2832,,,2", -0.5, 7, -1.5, 1.5, "1:1", "1:1", 400, 250)
$graph = addfractionaxislabels($graph, "pi/2")

// Answer types: number, calculated, choices, calculated, calculated, choices
$anstypes = array("number", "calculated", "choices", "calculated", "calculated", "choices")

// Part (a): amplitude
$answer[0] = 1
$answerformat[0] = "integer"

// Part (b): period
$answer[1] = "2pi"
$answerformat[1] = "nodecimal"

// Part (c): midline: choices
$questions[2] = array("y = 0", "y = 1", "y = -1", "y = x")
$answer[2] = 0
$noshuffle[2] = "all"
$displayformat[2] = "select"

// Part (d): x-value where sin(x) reaches its max in [0, 2pi]
$answer[3] = "pi/2"
$answerformat[3] = "nodecimal"

// Part (e): x-value where sin(x) reaches its min in [0, 2pi]
$answer[4] = "(3pi)/2"
$answerformat[4] = "nodecimal"

// Part (f): zeros of sin(x) in [0, 2pi]: choices
$questions[5] = array("0 only", "0 and pi", "0, pi, and 2pi", "pi/2 and 3pi/2", "pi only")
$answer[5] = 2
$noshuffle[5] = "all"
$displayformat[5] = "select"

// Shared CSS & JS for collapsible solution guide
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
</script>';

// Solution guide
$solutionguide = $css_block . '
<div class="rubric-container">
  <details>
    <summary>
      <span class="arrow-closed">&#9656;</span><span class="arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="rubric-content">
      <table class="rubric-table">
        <tbody>
          <tr>
            <th class="col-header">Part</th>
            <th class="col-check">Explanation</th>
          </tr>
          <tr>
            <td style="text-align:center;"><b>(a) Amplitude</b></td>
            <td><b>The amplitude of y = sin(x) is the distance from the midline to the peak.</b><br>
            The midline is y = 0. The maximum value of sin(x) is 1 and the minimum is -1, so the amplitude is |1 - 0| = 1.<br>
            <div style="margin-top:6px;padding:0.5em 0.8em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">Amplitude = <b>1</b></div></td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>(b) Period</b></td>
            <td><b>The period is the length of one complete cycle.</b><br>
            The parent function y = sin(x) completes one full wave over an interval of 2pi radians. Starting at (0, 0), it goes up to (pi/2, 1), back through (pi, 0), down to (3pi/2, -1), and returns to (2pi, 0).<br>
            <div style="margin-top:6px;padding:0.5em 0.8em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">Period = <b>2pi</b></div></td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>(c) Midline</b></td>
            <td><b>The midline is the horizontal line halfway between the maximum and minimum values.</b><br>
            Max = 1, Min = -1. The average is (1 + (-1))/2 = 0, so the midline is y = 0.<br>
            <div style="margin-top:6px;padding:0.5em 0.8em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">Midline: <b>y = 0</b></div></td>
          </tr>
          <tr class="row-colored">
            <td style="text-align:center;"><b>(d) Maximum</b></td>
            <td><b>Find where sin(x) reaches its maximum value of 1 on [0, 2pi].</b><br>
            sin(x) = 1 at x = pi/2. This is the peak of the wave, where the graph is at its highest point.<br>
            <div style="margin-top:6px;padding:0.5em 0.8em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">Maximum at <b>x = pi/2</b></div></td>
          </tr>
          <tr>
            <td style="text-align:center;"><b>(e) Minimum</b></td>
            <td><b>Find where sin(x) reaches its minimum value of -1 on [0, 2pi].</b><br>
            sin(x) = -1 at x = 3pi/2. This is the valley of the wave, where the graph is at its lowest point.<br>
            <div style="margin-top:6px;padding:0.5em 0.8em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">Minimum at <b>x = 3pi/2</b></div></td>
          </tr>
          <tr class="row-colored">
            <td class="col-cat-bot" style="text-align:center;"><b>(f) Zeros</b></td>
            <td class="col-check-bot"><b>The zeros are the x-values where sin(x) = 0 in [0, 2pi].</b><br>
            sin(x) = 0 at the beginning, middle, and end of one full cycle: x = 0, x = pi, and x = 2pi. These are the three points where the graph crosses the midline on the x-axis.<br>
            <div style="margin-top:6px;padding:0.5em 0.8em;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:0 6px 6px 0;">Zeros: <b>0, pi, and 2pi</b></div></td>
          </tr>
        </tbody>
      </table>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:Arial; font-size:medium; line-height:1.6;">
<p>Consider the parent sine function <b>y = sin(x)</b>. The graph of one full period is shown below.</p>
<div style="margin:15px auto; text-align:center;">$graph</div>
<p>Use the graph and your knowledge of the parent sine function to identify its key features.</p>
</div>

<div style="font-family:Arial; font-size:medium; line-height:1.6;">

<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
  <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part (a)</span>
  <p>What is the <b>amplitude</b> of y = sin(x)?</p>
  <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
</div>

<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
  <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part (b)</span>
  <p>What is the <b>period</b> of y = sin(x)? (Express your answer in terms of pi.)</p>
  <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
</div>

<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
  <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part (c)</span>
  <p>What is the <b>midline</b> (equation of the horizontal axis of symmetry) of y = sin(x)?</p>
  <div style="margin-top:12px;text-align:center;">$answerbox[2]</div>
</div>

<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
  <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part (d)</span>
  <p>At what <b>x-value</b> in [0, 2pi] does sin(x) reach its <b>maximum</b> value?</p>
  <div style="margin-top:12px;text-align:center;">$answerbox[3]</div>
</div>

<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
  <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part (e)</span>
  <p>At what <b>x-value</b> in [0, 2pi] does sin(x) reach its <b>minimum</b> value?</p>
  <div style="margin-top:12px;text-align:center;">$answerbox[4]</div>
</div>

<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
  <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">Part (f)</span>
  <p>What are the <b>zeros</b> of sin(x) in [0, 2pi]?</p>
  <div style="margin-top:12px;text-align:center;">$answerbox[5]</div>
</div>

</div>

// === ANSWER ===

$solutionguide