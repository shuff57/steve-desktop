// === NAME - DESCRIPTION: Two Researchers Reach Different Conclusions - decide what follows when two sound samples of the same population disagree, short of declaring either one wrong ===
// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL ===

$loA = rand(3, 5)
$hiA = $loA + 2
$loB = $loA - 2
$hiB = $loA
$n = rand(2, 4) * 50

$questions = array(
  "Neither conclusion is established. The two samples support different ranges, so more data are needed before any conclusion can be reached.",
  "Researcher A is correct, because a higher reported range is the more cautious estimate.",
  "Researcher B is correct, because the lower range is closer to what most students actually report.",
  "Both are correct, because a sample always describes its population accurately."
)
$answer = 0

$solutionguide = '<div style="font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <p><b>Two samples, two ranges, and no basis yet for choosing between them.</b></p>
  <p>Researcher A found most students play ' . $loA . ' to ' . $hiA . ' hours; Researcher B found ' . $loB . ' to ' . $hiB . '. Both surveyed ' . $n . ' students at random from the same population, and neither did anything wrong.</p>
  <p>The difference is <b>sampling variability</b> &mdash; two draws of ' . $n . ' land on different mixes of heavy and light players. That is expected, and it is not evidence against either researcher.</p>
  <p><b>What it does mean is that neither conclusion is established.</b> The honest reading is that the specific data support each researcher within their own sample, and the disagreement is a signal that more data are needed before anyone claims to know the answer for the whole population.</p>
  <p>Picking the one you prefer, or splitting the difference, both skip the actual finding: the study is not finished.</p>
  <p><b>Answer:</b> neither is established &mdash; more data need to be collected.</p>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0;">Two researchers each surveyed <b>$n</b> randomly chosen students at the same school about weekly video game hours.</p>
    <p style="margin:12px 0 0 0;">Researcher A concludes that most students play between <b>$loA and $hiA</b> hours each week. Researcher B concludes that most students play between <b>$loB and $hiB</b> hours each week.</p>
    <p style="margin:12px 0 0 0;">Given what you know, which conclusion is correct?</p>
  </div>
</div>

// === ANSWER ===

$solutionguide
