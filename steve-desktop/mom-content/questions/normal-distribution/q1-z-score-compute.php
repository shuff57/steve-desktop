// === NAME - DESCRIPTION: Compute a z-Score and Compare Two Values - Compute z = (x - mu) / sigma for a given observation, then compare two z-scores to decide which is more unusual ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc", "numfunc", "choices")

// Scenario: A and B are two observations from N(mu, sigma); compute z_A, z_B; pick "which is more unusual" (larger |z|)
// Pre-computed so we control numerics.

$cases = array(
  array("test scores", 75, 8, 86, 64, 1.375, -1.375, 2),    // |z| equal — equally unusual
  array("heights of adult men (in)", 70, 3, 76, 67, 2.0, -1.0, 0), // |z_A|=2 > |z_B|=1 → A more unusual
  array("daily steps", 8000, 1500, 11000, 6500, 2.0, -1.0, 0),
  array("baby weights (lb)", 7.5, 1.0, 6.0, 9.7, -1.5, 2.2, 1), // B more unusual
  array("commute times (min)", 28, 6, 22, 40, -1.0, 2.0, 1),
  array("SAT scores", 1050, 200, 1320, 780, 1.35, -1.35, 2)
)
// Fields: [context, mu, sigma, xA, xB, zA, zB, more_unusual (0=A, 1=B, 2=equal)]

$i = rand(0, count($cases)-1)
$ctx   = $cases[$i][0]
$mu    = $cases[$i][1]
$sigma = $cases[$i][2]
$xA    = $cases[$i][3]
$xB    = $cases[$i][4]
$answer[0] = $cases[$i][5]
$answer[1] = $cases[$i][6]
$answer[2] = $cases[$i][7]

$reltolerance[0] = 0.02
$reltolerance[1] = 0.02
$abstolerance[0] = 0.01
$abstolerance[1] = 0.01

$choices[2] = array("Value A is more unusual", "Value B is more unusual", "They are equally unusual")
$noshuffle[2] = "all"

$why = ""
if ($answer[2] == 0) { $why = "Since |z_A| > |z_B|, value A is farther from the mean and therefore more unusual." }
if ($answer[2] == 1) { $why = "Since |z_B| > |z_A|, value B is farther from the mean and therefore more unusual." }
if ($answer[2] == 2) { $why = "Since |z_A| = |z_B|, both values are the same distance from the mean (just in opposite directions), so they are equally unusual." }

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
      <p><b>Part a:</b> `z_A = (' . $xA . ' - ' . $mu . ') / ' . $sigma . ' = ' . $answer[0] . '`.</p>
      <p><b>Part b:</b> `z_B = (' . $xB . ' - ' . $mu . ') / ' . $sigma . ' = ' . $answer[1] . '`.</p>
      <p><b>Part c:</b> ' . $why . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A population of <b>$ctx</b> is normally distributed with mean `mu = $mu` and standard deviation `sigma = $sigma`.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Compute the z-score of value A = <b>$xA</b>. (Round to 2 decimal places.) $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Compute the z-score of value B = <b>$xB</b>. (Round to 2 decimal places.) $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which value is <b>more unusual</b> (farther from the mean in standard deviations)? $answerbox[2]
  </div>
</div>


// === ANSWER ===

$solutionguide
