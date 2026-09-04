// === NAME - DESCRIPTION: Is the Survey Question Fair - spot a leading question that praises one option and not the other, and state the neutral rewrite ===
// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL ===

$ci = rand(0, 3)
$adjs = array("delicious taste", "refreshing taste", "superior quality", "trusted reliability")
$plains = array("taste", "taste", "quality", "reliability")
$adj = $adjs[$ci]
$plain = $plains[$ci]

$bx = rand(0, 3)
$bnamesX = array("Brand X", "Brand A", "Brand M", "Brand P")
$bnamesY = array("Brand Y", "Brand B", "Brand N", "Brand Q")
$bX = $bnamesX[$bx]
$bY = $bnamesY[$bx]

$questions = array(
  "No. It praises one option and not the other, which steers the answer. A fair version asks about the " . $plain . " of both brands in identical words.",
  "Yes. Both brands are named, so the respondent is free to choose either one.",
  "No. It should not name the brands at all, because naming them introduces bias.",
  "Yes. Describing one product accurately is not bias as long as the description is true."
)
$answer = 0

$solutionguide = '<div style="font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <p><b>Read the two halves of the question against each other.</b></p>
  <p style="margin-left:1em;">' . $bX . ' gets "the <b>' . $adj . '</b>". ' . $bY . ' gets only "the ' . $plain . '".</p>
  <p>The two options are not described in the same words, and the extra praise attaches to one of them. That is a <b>leading question</b>: it signals which answer is expected before the respondent answers.</p>
  <p><b>The neutral version</b> treats both identically: "Do you prefer the ' . $plain . ' of ' . $bX . ' or the ' . $plain . ' of ' . $bY . '?": and does not steer the response.</p>
  <p>Note this is a <b>nonsampling</b> problem. Nothing is wrong with who was asked, so a larger sample would only collect more of the same skewed answers.</p>
  <p><b>Answer:</b> no, it is not a fair question.</p>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0;">A question on a survey reads:</p>
    <p style="margin:12px 0 0 0; padding-left:14px; border-left:3px solid #c8d4ea;"><i>"Do you prefer the $adj of $bX, or the $plain of $bY?"</i></p>
    <p style="margin:12px 0 0 0;">Is this a fair question?</p>
  </div>
</div>

// === ANSWER ===

$solutionguide
