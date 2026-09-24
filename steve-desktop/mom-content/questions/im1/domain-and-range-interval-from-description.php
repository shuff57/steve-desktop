// === NAME - DESCRIPTION: Domain and range as inequalities read off a graphed line segment with both endpoints included ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = array("calcinterval", "calcinterval")
$p = rand(-8, 2)
$len = rand(4, 9)
$q = $p + $len
$m = rand(1, 2)
$k = rand(-4, 4)
$r = $m*$p + $k
$s = $m*$q + $k
$variables[0] = "x"
$variables[1] = "y"
$answerformat[0] = "inequality"
$answerformat[1] = "inequality"
$answer[0] = "[$p,$q]"
$answer[1] = "[$r,$s]"
$showanswer[0] = "`$p le x le $q`"
$showanswer[1] = "`$r le y le $s`"

if ($m == 1) {
  $coef = "x"
} else {
  $coef = "$m x"
}
if ($k >= 0) {
  $linefn = "$coef + $k"
} else {
  $kn = -$k
  $linefn = "$coef - $kn"
}
$gxmin = $p - 2
$gxmax = $q + 2
$gymin = $r - 2
$gymax = $s + 2
$gr = showplot("$linefn,blue,$p,$q,closed,closed", $gxmin, $gxmax, $gymin, $gymax, 1, 1, 280, 280)
$gralt = "A line segment graphed on a coordinate plane. The segment starts at x = " . $p . " and ends at x = " . $q . ", with a closed dot at each end. Its lowest point is at y = " . $r . " and its highest point is at y = " . $s . "."
$gr = replacealttext($gr, $gralt)

$solutionguide = '
<div style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <p><b>Step 1: find the domain.</b> The graph reaches left to the closed dot at x = ' . $p . ' and right to the closed dot at x = ' . $q . '. Both dots are filled in, so both endpoints are included.</p>
  <p><b>Step 2: write the domain as an inequality.</b> The smallest x is ' . $p . ' and the largest is ' . $q . ', so ' . $p . ' &le; x &le; ' . $q . '.</p>
  <p><b>Step 3: find the range the same way.</b> Read the height of the graph. The lowest point sits at y = ' . $r . ' and the highest point sits at y = ' . $s . ', and both are included, so ' . $r . ' &le; y &le; ' . $s . '.</p>
  <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
    Domain: ' . $p . ' &le; x &le; ' . $q . ' &nbsp;&nbsp; Range: ' . $r . ' &le; y &le; ' . $s . '
  </div>
</div>'

// === QUESTION TEXT ===
<p>The graph below is a line segment. Both endpoints are included in the graph.</p>
<p style="text-align:center">$gr</p>
<p>Write the domain and the range as inequalities, such as `2 <= x <= 7`.</p>
<p>Domain: $answerbox[0]</p>
<p>Range: $answerbox[1]</p>

// === ANSWER ===
$solutionguide
