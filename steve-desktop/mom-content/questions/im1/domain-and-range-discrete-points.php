// === NAME - DESCRIPTION: Domain and range as sets from a list of discrete points ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = array("ntuple", "ntuple")
$n = rand(4, 5)
$xs = diffrands(-9, 9, $n)
$ys = diffrands(-9, 9, $n)
$pointstr = ""
$domstr = ""
$ranstr = ""
for ($i = 0..$n-1) {
  $pointstr .= "`($xs[$i], $ys[$i])`"
  if ($i < $n-1) {
    $pointstr .= ", "
  }
  $domstr .= $xs[$i]
  $ranstr .= $ys[$i]
  if ($i < $n-1) {
    $domstr .= ", "
    $ranstr .= ", "
  }
}
$answer[0] = "{" . $domstr . "}"
$answer[1] = "{" . $ranstr . "}"
$displayformat[0] = "set"
$displayformat[1] = "set"
$answerformat[0] = "anyorder"
$answerformat[1] = "anyorder"
$solutionguide = '
<div style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <p><b>Step 1: read the domain from the first coordinate of each point.</b> The x values are ' . $domstr . '.</p>
  <p><b>Step 2: read the range from the second coordinate of each point.</b> The y values are ' . $ranstr . '.</p>
  <p><b>Step 3: write each one as a set.</b> Put the values inside braces and separate them with commas. The order of the values does not matter.</p>
  <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
    Domain: &#123;' . $domstr . '&#125; &nbsp;&nbsp; Range: &#123;' . $ranstr . '&#125;
  </div>
</div>'

// === QUESTION TEXT ===
<p>A relation is given by the following set of points.</p>
<p style="text-align:center">$pointstr</p>
<p>List the domain and the range. Enter each answer as a set of values inside braces, such as &#123;1, 2, 3&#125;. Order does not matter.</p>
<p>Domain: $answerbox[0]</p>
<p>Range: $answerbox[1]</p>

// === ANSWER ===
$solutionguide
