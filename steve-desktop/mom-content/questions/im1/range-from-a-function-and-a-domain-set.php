// === NAME - DESCRIPTION: Find the range of a linear function given an explicit domain set ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = array("ntuple")
$a = nonzerorand(-5, 5)
$b = rand(-9, 9)
$n = rand(3, 4)
$xs = diffrands(-6, 6, $n)
$aterm = "$a x"
if ($a == 1) {
  $aterm = "x"
}
if ($a == -1) {
  $aterm = "-x"
}
$bterm = " + $b"
if ($b < 0) {
  $bterm = " - " . abs($b)
}
if ($b == 0) {
  $bterm = ""
}
$fdisp = "$aterm$bterm"
$domstr = ""
$ranstr = ""
$workstr = '<table border="1" cellpadding="6" cellspacing="0"><tr><th>x</th>'
for ($i = 0..$n-1) {
  $ys[$i] = $a * $xs[$i] + $b
  $workstr .= '<td>' . $xs[$i] . '</td>'
  $domstr .= $xs[$i]
  $ranstr .= $ys[$i]
  if ($i < $n-1) {
    $domstr .= ", "
    $ranstr .= ", "
  }
}
$workstr .= '</tr><tr><th>f(x)</th>'
for ($i = 0..$n-1) {
  $workstr .= '<td>' . $ys[$i] . '</td>'
}
$workstr .= '</tr></table>'
$answer[0] = "{" . $ranstr . "}"
$displayformat[0] = "set"
$answerformat[0] = "anyorder"
$solutionguide = '
<div style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <p><b>Step 1: write down the domain.</b> The domain is the set of inputs you were given: &#123;' . $domstr . '&#125;.</p>
  <p><b>Step 2: apply the rule to each input.</b> The rule is f(x) = ' . $fdisp . '. Substitute each input and simplify.</p>
  ' . $workstr . '
  <p><b>Step 3: collect the outputs into a set.</b> The outputs are ' . $ranstr . ', so the range is &#123;' . $ranstr . '&#125;.</p>
  <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
    Range: &#123;' . $ranstr . '&#125;
  </div>
</div>'

// === QUESTION TEXT ===
<p>The function `f(x) = $fdisp` has the domain &#123;$domstr&#125;.</p>
<p>Find the range of the function for this domain. Enter the answer as a set of values inside braces, such as &#123;1, 2, 3&#125;. Order does not matter.</p>
<p>Range: $answerbox[0]</p>

// === ANSWER ===
$solutionguide
