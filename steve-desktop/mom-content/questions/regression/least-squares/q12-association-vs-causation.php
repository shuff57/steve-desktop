// === NAME - DESCRIPTION: Association vs Causation - Correct conclusion from observational regression ===
// === SET QUESTION TYPE TO: multiple_choice ===

// === COMMON CONTROL ===

$ctx_options = array(
  array("ice cream sales (per day)", "number of drownings (per day)", "ice cream sales", "drownings", "hotter weather drives both"),
  array("number of firefighters at a fire", "dollar amount of fire damage", "firefighters on scene", "fire damage", "bigger fires call more firefighters AND cause more damage"),
  array("shoe size of elementary school children", "reading level", "shoe size", "reading level", "age drives both"),
  array("hours spent on social media", "feelings of loneliness", "social media use", "loneliness", "many lurking variables (sleep, personality, life events) could explain both")
)
$ctx = $ctx_options[rand(0, count($ctx_options) - 1)]
$xname = $ctx[0]
$yname = $ctx[1]
$xshort = $ctx[2]
$yshort = $ctx[3]
$reason = $ctx[4]

$r = rand(60, 85) / 100

$questions = array(
  "There is a positive association between $xshort and $yshort, but we cannot conclude that $xshort causes a change in $yshort from an observational study.",
  "Increasing $xshort will directly cause $yshort to increase.",
  "$xshort and $yshort must be unrelated, because correlation never implies anything.",
  "Because `r` is greater than 0.5, we can conclude that $xshort causes $yshort."
)
$answer = 0

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
      <p>A strong correlation and a well-fitting regression line only tell us there is an <b>association</b> between two variables: they do <b>not</b> establish that changing one variable will cause the other to change.</p>
      <p>Observational data can be explained by:</p>
      <ul>
        <li><b>Reverse causation</b>: maybe `y` drives `x`.</li>
        <li><b>Confounding / lurking variables</b>: a third variable affects both.</li>
        <li><b>Chance</b>: especially in small samples.</li>
      </ul>
      <p>In this example, ' . $reason . '.</p>
      <p>Causal conclusions generally require a <b>randomized experiment</b>, not an observational regression.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#fff4e5; border-left:4px solid #f59e0b; border-radius:0 8px 8px 0;">
        <b>Rule:</b> association &rArr; worth investigating; causation &rArr; requires controlled experiment or a carefully argued causal design.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">Observational data are collected on <b>$xname</b> and <b>$yname</b>. A least squares regression line is fit, and the correlation is `r = $r`.</p>
    <p style="margin:0;">Which conclusion is most appropriate?</p>
  </div>
</div>


// === ANSWER ===

$solutionguide
