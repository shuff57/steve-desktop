// === GRAPH SINE TRANSFORMATION (MEDIUM) - Two transformations active. Student draws y = a sin(b(x-h)) + k over one period using the sine curve tool. Degree mode. ===
// === SET QUESTION TYPE TO: draw ===

// === COMMON CONTROL ===

loadlibrary("stats")

/* ---------- 1. Medium (exactly 2 of {a, b, h, k} active) ---------- */
$_combo = rand(0, 5)
$a_on = 0
$b_on = 0
$h_on = 0
$k_on = 0
if ($_combo == 0) { $a_on = 1; $b_on = 1 }
if ($_combo == 1) { $a_on = 1; $h_on = 1 }
if ($_combo == 2) { $a_on = 1; $k_on = 1 }
if ($_combo == 3) { $b_on = 1; $h_on = 1 }
if ($_combo == 4) { $b_on = 1; $k_on = 1 }
if ($_combo == 5) { $h_on = 1; $k_on = 1 }

/* ---------- 2. Randomize each parameter ---------- */
$a_pool = array(-3, -2,  2,  3)
$a_idx  = rand(0, 3)
if ($a_on == 1) { $a = $a_pool[$a_idx] } else { $a = 1 }
$amp = abs($a)

$b_pool = array(2, 3)
$b_idx  = rand(0, 1)
if ($b_on == 1) { $b = $b_pool[$b_idx] } else { $b = 1 }
$p_deg = 360 / $b

$h_deg_pool = array( 30,  45,  60,  90, -30, -45, -60, -90)
$h_idx      = rand(0, 7)
if ($h_on == 1) { $h_deg = $h_deg_pool[$h_idx] } else { $h_deg = 0 }

$k_pool = array(-3, -2, -1, 1, 2, 3)
$k_idx  = rand(0, 5)
if ($k_on == 1) { $k = $k_pool[$k_idx] } else { $k = 0 }

/* ---------- 3. Function string for showplot (degree mode) ---------- */
$func_str = $a . "*sin(" . $b . "*(x-" . $h_deg . ")*pi/180)+" . $k

/* ---------- 4. Five anchor x-values (in degrees) ---------- */
$x0 = $h_deg
$x1 = $h_deg + ($p_deg / 4)
$x2 = $h_deg + ($p_deg / 2)
$x3 = $h_deg + (3 * $p_deg / 4)
$x4 = $h_deg + $p_deg

/* ---------- 5. Five anchor y-values ---------- */
$y0 = $k
$y1 = $a + $k
$y2 = $k
$y3 = 0 - $a + $k
$y4 = $k
$max_val = $amp + $k
$min_val = 0 - $amp + $k

/* ---------- 6. Equation display ---------- */
if ($a == 1)       { $a_str = "" }
elseif ($a == -1)  { $a_str = "-" }
else               { $a_str = $a }

if ($b == 1 && $h_deg == 0) {
  $inner = "x"
} elseif ($b == 1) {
  if ($h_deg > 0) { $inner = "x - " . $h_deg . "&deg;" }
  else            { $inner = "x + " . abs($h_deg) . "&deg;" }
} elseif ($h_deg == 0) {
  $inner = $b . "x"
} else {
  if ($h_deg > 0) { $inner = $b . "(x - " . $h_deg . "&deg;)" }
  else            { $inner = $b . "(x + " . abs($h_deg) . "&deg;)" }
}

if ($k > 0)      { $k_str = " + " . $k }
elseif ($k < 0)  { $k_str = " - " . abs($k) }
else             { $k_str = "" }

$eq_show = "y = " . $a_str . "sin(" . $inner . ")" . $k_str

/* ---------- 7. Window (degrees) ---------- */
$x_min = $x0 - 30
$x_max = $x4 + 30
$xscl  = "30"
$yextent = $amp + abs($k) + 1.5
$y_min = 0 - $yextent
$y_max = $yextent

/* ---------- 8. Solution graph ---------- */
$mid_plt   = showplot($k . ",red," . $x_min . "," . $x_max . ",,,1.5,dash",     $x_min, $x_max, $y_min, $y_max, $xscl, "1", 480, 290)
$curve_plt = showplot($func_str . ",blue," . $x0 . "," . $x4 . ",,,2.5",         $x_min, $x_max, $y_min, $y_max, $xscl, "1", 480, 290)
$dot0      = showplot("dot," . $x0 . "," . $y0 . ",closed,green",                $x_min, $x_max, $y_min, $y_max, $xscl, "1", 480, 290)
$dot1      = showplot("dot," . $x1 . "," . $y1 . ",closed,purple",               $x_min, $x_max, $y_min, $y_max, $xscl, "1", 480, 290)
$dot2      = showplot("dot," . $x2 . "," . $y2 . ",closed,green",                $x_min, $x_max, $y_min, $y_max, $xscl, "1", 480, 290)
$dot3      = showplot("dot," . $x3 . "," . $y3 . ",closed,red",                  $x_min, $x_max, $y_min, $y_max, $xscl, "1", 480, 290)
$dot4      = showplot("dot," . $x4 . "," . $y4 . ",closed,green",                $x_min, $x_max, $y_min, $y_max, $xscl, "1", 480, 290)
$solgraph  = mergeplots($mid_plt, $curve_plt, $dot0, $dot1, $dot2, $dot3, $dot4)
$solgraph  = addlabel($solgraph, $x_max - 5, $k + 0.3, "midline y = " . $k, "red", "right")

/* ---------- 9. Drawing canvas setup ---------- */
$grid       = $x_min . "," . $x_max . "," . $y_min . "," . $y_max . "," . $xscl . ",1,480,290"
$background = array()
$answers      = $func_str . "," . $x0 . "," . $x4
$answerformat = "twopoint,trig"
// snap to 15° on x (covers all phase shifts: 30, 45, 60, 90) and integer y
$snaptogrid   = "15:1"

/* ---------- 10. Notes ---------- */
if ($a < 0) { $a_role_max = "minimum"; $a_role_min = "maximum"; $start_dir = "downward" }
else        { $a_role_max = "maximum"; $a_role_min = "minimum"; $start_dir = "upward" }

$solutionguide = '<div style="text-align:center; margin:8px 0;">' . $solgraph . '</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.4; color:#21242c; max-width:688px;"><p style="margin:0 0 4px 0;">Graph one full period of</p><p style="text-align:center; font-size:1.2em; font-weight:bold; background:#f0f4ff; padding:12px; border-radius:8px; border:1px solid #c5d3f0; margin:0 0 8px 0;">{$eq_show}</p><p style="margin:0 0 8px 0;">Use the <b>sine curve tool</b> to draw one full period.</p></div>

$answerbox

// === ANSWER ===

$solutionguide
