// === NAME - DESCRIPTION: Domain of a Function - Find domain of a polynomial, rational, and radical function ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

$anstypes = array("number", "number", "number")
$displayformat[0] = "select"
$noshuffle[0] = "all"

/* ---------- 1. Randomize k values ---------- */
// k_rat: excluded value for rational  f(x) = (x+m)/(x-k_rat)
// k_rad: lower boundary for radical   k(x) = sqrt(x - k_rad)
$ks_rat = array(-5, -4, -3, -2, -1, 1, 2, 3, 4, 5)
$ks_rad = array(-4, -3, -2, -1, 0, 1, 2, 3, 4, 5)
$k_rat = randfrom($ks_rat)
$k_rad = randfrom($ks_rad)

// Numerator constant for rational (keep different from k_rat to avoid hole)
$ms = array(-3, -2, -1, 1, 2, 3)
$m = randfrom($ms)

// Simple quadratic for part (a)
$pa = rand(1, 3)
$pb_opts = array(-3, -2, -1, 1, 2, 3)
$pc_opts = array(-5, -4, -3, -2, -1, 1, 2, 3, 4, 5)
$pb = randfrom($pb_opts)
$pc = randfrom($pc_opts)

/* ---------- 2. Answer types ---------- */
// Part (a): choices select: "all real numbers" is option index 0
// Part (b): number: the excluded value k_rat
// Part (c): number: the lower bound k_rad

// choices for part (a): index 0 = correct
$choices[0] = array("all real numbers", "x >= 0", "x > 0", "x != 0")
$answer[0] = 0

$answer[1] = $k_rat
$answer[2] = $k_rad

/* ---------- 3. Display strings ---------- */
// Denominator: x - k_rat  ->  display
$k_rat_abs = abs($k_rat)
if ($k_rat > 0) { $denom_display = "x - " . $k_rat }
if ($k_rat < 0) { $denom_display = "x + " . $k_rat_abs }
if ($k_rat == 0) { $denom_display = "x" }

// Numerator: x + m
$m_abs = abs($m)
if ($m > 0) { $numer_display = "x + " . $m }
if ($m < 0) { $numer_display = "x - " . $m_abs }
if ($m == 0) { $numer_display = "x" }

// Radicand: x - k_rad
$k_rad_abs = abs($k_rad)
if ($k_rad > 0) { $rad_inside = "x - " . $k_rad }
if ($k_rad < 0) { $rad_inside = "x + " . $k_rad_abs }
if ($k_rad == 0) { $rad_inside = "x" }

// pb/pc display with signs
$pb_abs = abs($pb)
$pc_abs = abs($pc)
if ($pb > 0) { $pb_str = "+ " . $pb }
if ($pb < 0) { $pb_str = "- " . $pb_abs }
if ($pc > 0) { $pc_str = "+ " . $pc }
if ($pc < 0) { $pc_str = "- " . $pc_abs }

/* ---------- 4. Solution guide ---------- */
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
      <p><b>(a)</b> Polynomials have no denominators or square roots. They are defined for every real number.
        <br>Domain: <b>all real numbers</b>.</p>
      <p><b>(b)</b> Rational functions are undefined where the denominator equals zero.
        <br>Set '.$denom_display.' = 0 &rArr; x = <b>'.$k_rat.'</b>.
        <br>Domain: all real numbers with x &ne; '.$k_rat.'.</p>
      <p><b>(c)</b> A square root requires the radicand to be &ge; 0.
        <br>'.$rad_inside.' &ge; 0 &rArr; x &ge; <b>'.$k_rad.'</b>.
        <br>Domain: x &ge; '.$k_rad.'.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        (a) all real numbers &nbsp;&bull;&nbsp; (b) excluded value = '.$k_rat.' &nbsp;&bull;&nbsp; (c) lower bound = '.$k_rad.'
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<p style="margin:0 0 4px 0;">Find the domain of each function.</p>
<p style="margin:0; font-size:14px; color:#555;">For (b) enter the x value that must be excluded. For (c) enter the smallest x value in the domain.</p>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> <b>g(x) = $pa x&sup2; $pb_str x $pc_str</b><br>
<span style="font-size:14px; color:#374151; margin-right:6px;">Domain:</span><span style="margin-left:4px;">$answerbox[0]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> <b>h(x) = ($numer_display) / ($denom_display)</b><br>
<span style="font-size:14px; color:#374151;">Enter the excluded x value (where x &ne; ?):</span><span style="margin-left:8px;">$answerbox[1]</span>
</div>
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
<span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> <b>k(x) = &#8730;($rad_inside)</b><br>
<span style="font-size:14px; color:#374151;">Enter the smallest x value in the domain (where x &ge; ?):</span><span style="margin-left:8px;">$answerbox[2]</span>
</div>
</div>


// === ANSWER ===

$solutionguide
