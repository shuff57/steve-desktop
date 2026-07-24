// === NAME - DESCRIPTION: Identify Nonlinearity from Residual Plots - Which residual plot shows evidence of a nonlinear relationship ===
// === SET QUESTION TYPE TO: multiple_choice ===

// === COMMON CONTROL ===

// Three residual plots described; randomize which position holds the nonlinear one
$pos = rand(0, 2)

$descs = array(
  "Points are scattered randomly above and below zero with no obvious trend.",
  "Points are scattered randomly above and below zero with no obvious trend.",
  "Points are scattered randomly above and below zero with no obvious trend."
)

$nonlinear_descs = array(
  "Residuals form a clear U-shape: positive on the left, dipping negative in the middle, and rising positive again on the right.",
  "Residuals form a clear inverted-U: negative on the left, positive in the middle, and negative again on the right.",
  "Residuals curve downward: positive on the left, crossing through zero, and becoming increasingly negative on the right."
)
$nl_pick = $nonlinear_descs[rand(0, count($nonlinear_descs) - 1)]
$descs[$pos] = $nl_pick

$labels = array("A", "B", "C")

$questions = array("Plot A", "Plot B", "Plot C")
$answer = $pos

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><b>Plot ' . $labels[$pos] . '</b> shows a systematic <b>curved pattern</b> in the residuals. This means the linear model misses something — the true relationship between the variables is nonlinear.</p>
      <p>The other plots show residuals with <b>random scatter</b> around zero, which is exactly what we expect when a linear model is appropriate.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Key idea:</b> A curved pattern (U-shape, inverted-U, or systematic sweep) in a residual plot indicates that a straight line does not capture the true relationship. A more flexible model (quadratic, logarithmic, etc.) may be needed.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">Three researchers each fit a linear model to their data and created residual plots. The descriptions of the three residual plots are below:</p>
    <table style="width:100%; border-collapse:collapse; margin:10px 0;">
      <tr style="background:#f0f4ff;">
        <th style="border:1px solid #e5e7eb; padding:8px; text-align:left; width:60px;">Plot</th>
        <th style="border:1px solid #e5e7eb; padding:8px; text-align:left;">Description of Residuals</th>
      </tr>
      <tr>
        <td style="border:1px solid #e5e7eb; padding:8px; font-weight:700;">A</td>
        <td style="border:1px solid #e5e7eb; padding:8px; user-select:text;">$descs[0]</td>
      </tr>
      <tr>
        <td style="border:1px solid #e5e7eb; padding:8px; font-weight:700;">B</td>
        <td style="border:1px solid #e5e7eb; padding:8px; user-select:text;">$descs[1]</td>
      </tr>
      <tr>
        <td style="border:1px solid #e5e7eb; padding:8px; font-weight:700;">C</td>
        <td style="border:1px solid #e5e7eb; padding:8px; user-select:text;">$descs[2]</td>
      </tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Which residual plot shows evidence that the relationship is <b>nonlinear</b>?</p>
  </div>
</div>


// === ANSWER ===

$solutionguide
