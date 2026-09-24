// === NAME - DESCRIPTION: Domain and range from a table of input and output values ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===
$anstypes = array("ntuple", "ntuple")
$n = rand(4, 5)
$xs = diffrands(-9, 9, $n)
$ys = diffrands(-9, 9, $n)
$domstr = ""
$ranstr = ""
$table = "<table border='1' cellpadding='6' cellspacing='0'><tr><th>input (x)</th>"
for ($i = 0..$n-1) {
  $table .= "<td>$xs[$i]</td>"
  $domstr .= $xs[$i]
  if ($i < $n-1) {
    $domstr .= ", "
  }
}
$table .= "</tr><tr><th>output (y)</th>"
for ($i = 0..$n-1) {
  $table .= "<td>$ys[$i]</td>"
  $ranstr .= $ys[$i]
  if ($i < $n-1) {
    $ranstr .= ", "
  }
}
$table .= "</tr></table>"
$answer[0] = "{" . $domstr . "}"
$answer[1] = "{" . $ranstr . "}"
$displayformat[0] = "set"
$displayformat[1] = "set"
$answerformat[0] = "anyorder"
$answerformat[1] = "anyorder"
$solutionguide = '
<div style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <p><b>Step 1: read the domain from the input row.</b> The domain is every input value the table lists. Reading across the top row gives ' . $domstr . '.</p>
  <p><b>Step 2: read the range from the output row.</b> The range is every output value the table lists. Reading across the bottom row gives ' . $ranstr . '.</p>
  <p><b>Step 3: write each one as a set.</b> Put the values inside braces and separate them with commas. The order of the values does not matter.</p>
  <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
    Domain: &#123;' . $domstr . '&#125; &nbsp;&nbsp; Range: &#123;' . $ranstr . '&#125;
  </div>
</div>'

// === QUESTION TEXT ===
<p>The table below shows the input and output values of a relation.</p>
$table
<p>List the domain and the range. Enter each answer as a set of values inside braces, such as &#123;1, 2, 3&#125;. Order does not matter.</p>
<p>Domain: $answerbox[0]</p>
<p>Range: $answerbox[1]</p>

// === ANSWER ===
$solutionguide
