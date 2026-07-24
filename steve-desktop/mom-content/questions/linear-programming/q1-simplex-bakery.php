// === NAME - DESCRIPTION: Simplex Bakery - Maximize bakery profit using the simplex method ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

// Product/resource context (independent of numbers)
$prod1s = array("dozens of cookies", "dozen muffins", "loaves of bread", "dozen cupcakes", "dozen croissants")
$prod2s = array("cakes", "pastries", "pies", "pans of brownies", "tarts")
$res1s = array("Flour (lbs)", "Flour (lbs)", "Butter (lbs)", "Sugar (lbs)", "Flour (lbs)")
$res2s = array("Oven time (hrs)", "Prep time (hrs)", "Baking time (hrs)", "Labor (hrs)", "Oven time (hrs)")

$j = rand(0, 4)
$prod1 = $prod1s[$j]
$prod2 = $prod2s[$j]
$res1 = $res1s[$j]
$res2 = $res2s[$j]

// Pick constraint matrix type (all have |det|=1 so any integer RHS gives integer solutions)
$type = rand(0, 2)

if ($type == 0) {
  // Matrix [[1,1],[2,1]]
  $a11 = 1
  $a12 = 1
  $a21 = 2
  $a22 = 1
  $b1 = rand(8, 14)
  $b1lo = $b1 + 2
  $b1hi = 2 * $b1 - 2
  $b2 = rand($b1lo, $b1hi)
  $x1ans = $b2 - $b1
  $x2ans = 2 * $b1 - $b2
  $c2 = 5 * rand(4, 8)
  $c2lo = $c2 + 5
  $c2hi = 2 * $c2 - 5
  $c1 = 5 * rand($c2lo / 5, $c2hi / 5)
} elseif ($type == 1) {
  // Matrix [[1,1],[1,2]]
  $a11 = 1
  $a12 = 1
  $a21 = 1
  $a22 = 2
  $b1 = rand(8, 14)
  $b1lo = $b1 + 2
  $b1hi = 2 * $b1 - 2
  $b2 = rand($b1lo, $b1hi)
  $x1ans = 2 * $b1 - $b2
  $x2ans = $b2 - $b1
  $c1 = 5 * rand(4, 8)
  $c1lo = $c1 + 5
  $c1hi = 2 * $c1 - 5
  $c2 = 5 * rand($c1lo / 5, $c1hi / 5)
} else {
  // Matrix [[2,1],[1,1]]
  $a11 = 2
  $a12 = 1
  $a21 = 1
  $a22 = 1
  $b2 = rand(6, 12)
  $b2lo = $b2 + 2
  $b2hi = 2 * $b2 - 2
  $b1 = rand($b2lo, $b2hi)
  $x1ans = $b1 - $b2
  $x2ans = 2 * $b2 - $b1
  $c2 = 5 * rand(4, 8)
  $c2lo = $c2 + 5
  $c2hi = 2 * $c2 - 5
  $c1 = 5 * rand($c2lo / 5, $c2hi / 5)
}

$zans = $c1 * $x1ans + $c2 * $x2ans

$anstypes = array("number", "number", "number")
$answerformat[0] = "integer"
$answerformat[1] = "integer"
$answerformat[2] = "integer"

$answer[0] = $x1ans
$answer[1] = $x2ans
$answer[2] = $zans

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
      <p><b>Set up the LP:</b></p>
      <p>Maximize Z = ' . $c1 . 'x<sub>1</sub> + ' . $c2 . 'x<sub>2</sub></p>
      <p>Subject to:<br>
        &nbsp;&nbsp;' . $a11 . 'x<sub>1</sub> + ' . $a12 . 'x<sub>2</sub> &le; ' . $b1 . '<br>
        &nbsp;&nbsp;' . $a21 . 'x<sub>1</sub> + ' . $a22 . 'x<sub>2</sub> &le; ' . $b2 . '<br>
        &nbsp;&nbsp;x<sub>1</sub>, x<sub>2</sub> &ge; 0
      </p>
      <p>Convert to standard form with slack variables, build the initial tableau, and perform pivots until all objective row indicators are non-negative.</p>
      <p><b>Optimal solution:</b></p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        x<sub>1</sub> = ' . $x1ans . ' (' . $prod1 . '), &nbsp;
        x<sub>2</sub> = ' . $x2ans . ' (' . $prod2 . '), &nbsp;
        Maximum profit Z = $' . $zans . '
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.4; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:8px; padding:8px 12px; margin:0 0 4px 0; box-shadow:0 2px 4px rgba(0,0,0,0.06); width:100%; box-sizing:border-box;">
    <p style="margin:0 0 2px 0;">A bakery produces two products: <b>$prod1</b> (x<sub>1</sub>) and <b>$prod2</b> (x<sub>2</sub>). Each unit of $prod1 generates $$c1 in profit, and each unit of $prod2 generates $$c2 in profit.</p>
    <p style="margin:2px 0;">Production is limited by two resources:</p>
    <table style="border-collapse:collapse; width:100%; margin:4px 0;">
      <tr><th style="background:#f7f9fa; font-weight:600; color:#21242c; border:1px solid #dee1e3; padding:6px 12px; text-align:left;">Resource</th><th style="background:#f7f9fa; font-weight:600; color:#21242c; border:1px solid #dee1e3; padding:6px 12px; text-align:center;">$prod1 (x<sub>1</sub>)</th><th style="background:#f7f9fa; font-weight:600; color:#21242c; border:1px solid #dee1e3; padding:6px 12px; text-align:center;">$prod2 (x<sub>2</sub>)</th><th style="background:#f7f9fa; font-weight:600; color:#21242c; border:1px solid #dee1e3; padding:6px 12px; text-align:center;">Available</th></tr>
      <tr><td style="padding:6px 12px; border:1px solid #dee1e3;">$res1</td><td style="padding:6px 12px; text-align:center; border:1px solid #dee1e3;">$a11</td><td style="padding:6px 12px; text-align:center; border:1px solid #dee1e3;">$a12</td><td style="padding:6px 12px; text-align:center; border:1px solid #dee1e3;">$b1</td></tr>
      <tr><td style="padding:6px 12px; border:1px solid #dee1e3;">$res2</td><td style="padding:6px 12px; text-align:center; border:1px solid #dee1e3;">$a21</td><td style="padding:6px 12px; text-align:center; border:1px solid #dee1e3;">$a22</td><td style="padding:6px 12px; text-align:center; border:1px solid #dee1e3;">$b2</td></tr>
    </table>
    <p style="margin:2px 0 0 0;"><b>Use the simplex method</b> to find the production quantities that maximize total profit.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:8px; padding:8px 12px; margin:0; box-shadow:0 2px 4px rgba(0,0,0,0.06); width:100%; box-sizing:border-box;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:2px 8px; font-size:13px; font-weight:700; margin-right:8px; vertical-align:middle;">a.</span> Optimal number of $prod1 (x<sub>1</sub>): $answerbox[0]<br>
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:2px 8px; font-size:13px; font-weight:700; margin-right:8px; vertical-align:middle; margin-top:6px;">b.</span> Optimal number of $prod2 (x<sub>2</sub>): $answerbox[1]<br>
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:2px 8px; font-size:13px; font-weight:700; margin-right:8px; vertical-align:middle; margin-top:6px;">c.</span> Maximum profit (Z): $answerbox[2]
  </div>
</div>


// === ANSWER ===

$solutionguide
